const { createApp, markRaw, toRaw } = Vue;

createApp({
    data() {
        return {
            mediaRecorder: null,
            videoStream: null,
            videoUrl: null,
            videoGrabado: false,
            traduccion: null,
            grabando: false,
            tiempoRestante: 30,
            timer: null,
            mostrarPreview: false,
            pantalla: 'bienvenida', // 'bienvenida', 'grabacion'
            traduciendo: false,
            barra: 'inicio', // para la barra inferior
            facingMode: 'user', // 'user' (frontal) o 'environment' (trasera)
            espejo: false, // modo espejo
            canvas: null,
            canvasStream: null,
            canvasInterval: null,
            // Hand tracking
            handTrackingEnabled: false,
            hands: null, // MediaPipe Hands instance - será marcado como raw para evitar proxy de Vue
            handCanvasCtx: null,
            handResults: null,
            tempCanvas: null,
            tempCanvasCtx: null,
            handTrackingLoop: null,
            errorCount: undefined
        }
    },
    watch: {
        pantalla(newVal) {
            if (newVal === 'grabacion') {
                this.inicializarPreview();
                // Activar hand tracking por defecto al entrar a grabación
                if (!this.handTrackingEnabled) {
                    this.$nextTick(() => {
                        setTimeout(() => {
                            // Verificar que el preview esté listo antes de activar hand tracking
                            const video = this.$refs.preview || this.$refs.previewDesktop;
                            if (video && this.videoStream) {
                                this.handTrackingEnabled = true;
                                this.initHandTracking().catch(error => {
                                    console.error('Error al inicializar hand tracking automáticamente:', error);
                                    this.handTrackingEnabled = false;
                                });
                            }
                        }, 1000); // Aumentar el delay para dar más tiempo a cargar todo
                    });
                }
            } else {
                this.detenerPreview();
            }
        }
    },
    methods: {
        async inicializarPreview() {
            if (!this.videoStream) {
                try {
                    this.videoStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: this.facingMode }, audio: true });
                    this.mostrarPreview = true;
                    this.$nextTick(async () => {
                        // Inicializar preview móvil
                        const preview = this.$refs.preview;
                        if (preview) {
                            preview.srcObject = this.videoStream;
                            try {
                                await preview.play();
                            } catch (error) {
                                console.log('Preview mobile play interrupted:', error);
                            }
                        }
                        // Inicializar preview desktop
                        const previewDesktop = this.$refs.previewDesktop;
                        if (previewDesktop) {
                            previewDesktop.srcObject = this.videoStream;
                            try {
                                await previewDesktop.play();
                            } catch (error) {
                                console.log('Preview desktop play interrupted:', error);
                            }
                        }
                        
                        // Inicializar hand tracking si está activado
                        if (this.handTrackingEnabled) {
                            this.createTempCanvasForHandTracking();
                            this.startHandDetection();
                        }
                    });
                } catch (error) {
                    this.mostrarPreview = false;
                    this.videoStream = null;
                    alert('No se pudo acceder a la cámara.');
                }
            } else {
                this.mostrarPreview = true;
                this.$nextTick(async () => {
                    // Reactivar preview móvil
                    const preview = this.$refs.preview;
                    if (preview && this.videoStream) {
                        preview.srcObject = this.videoStream;
                        try {
                            await preview.play();
                        } catch (error) {
                            console.log('Preview mobile reactivation interrupted:', error);
                        }
                    }
                    // Reactivar preview desktop
                    const previewDesktop = this.$refs.previewDesktop;
                    if (previewDesktop && this.videoStream) {
                        previewDesktop.srcObject = this.videoStream;
                        try {
                            await previewDesktop.play();
                        } catch (error) {
                            console.log('Preview desktop reactivation interrupted:', error);
                        }
                    }
                    
                    // Reinicializar hand tracking si está activado
                    if (this.handTrackingEnabled) {
                        this.createTempCanvasForHandTracking();
                        this.startHandDetection();
                    }
                });
            }
        },
        async alternarCamara() {
            this.facingMode = this.facingMode === 'user' ? 'environment' : 'user';
            this.detenerPreview();
            await this.inicializarPreview();
        },
        alternarEspejo() {
            this.espejo = !this.espejo;
        },
        detenerPreview() {
            if (this.videoStream) {
                this.videoStream.getTracks().forEach(track => track.stop());
                this.videoStream = null;
            }
            this.mostrarPreview = false;
            this.limpiarCanvasMirror();
            this.stopHandTracking();
            this.cleanupTempCanvas();
        },
        limpiarCanvasMirror() {
            if (this.canvasStream) {
                this.canvasStream.getTracks().forEach(track => track.stop());
                this.canvasStream = null;
            }
            if (this.canvasInterval) {
                clearInterval(this.canvasInterval);
                this.canvasInterval = null;
            }
            this.canvas = null;
        },
        irA(pantalla) {
            // Limpiar estados al cambiar de pantalla
            if (pantalla === 'bienvenida' || pantalla === 'grabacion') {
                this.grabando = false;
                this.videoGrabado = false;
                this.traduccion = null;
                this.videoUrl = null;
                if (pantalla !== 'grabacion') {
                    this.detenerPreview();
                }
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            }
            this.pantalla = pantalla;
            this.barra = pantalla;
            
            // Inicializar preview cuando se navega a grabación
            if (pantalla === 'grabacion') {
                this.$nextTick(() => {
                    this.inicializarPreview();
                });
            }
        },
        comenzar() {
            this.irA('grabacion');
        },
        async iniciarGrabacion() {
            if (this.grabando) return; // Previene doble click
            try {
                this.videoGrabado = false;
                this.traduccion = null;
                this.videoUrl = null;
                this.grabando = false;
                
                // Volver a mostrar preview si se está regrabando
                this.mostrarPreview = true;
                
                if (!this.videoStream) {
                    await this.inicializarPreview();
                } else {
                    // Reactivar el preview si ya tenemos stream
                    this.$nextTick(async () => {
                        const preview = this.$refs.preview || this.$refs.previewDesktop;
                        if (preview && this.videoStream) {
                            preview.srcObject = this.videoStream;
                            try {
                                await preview.play();
                            } catch (error) {
                                console.log('Preview reactivation interrupted:', error);
                            }
                        }
                    });
                }
                let streamToRecord = this.videoStream;
                this.limpiarCanvasMirror();
                if (this.espejo) {
                    // Crear canvas oculto y dibujar el video reflejado
                    const preview = this.$refs.preview || this.$refs.previewDesktop;
                    this.canvas = document.createElement('canvas');
                    this.canvas.width = preview.videoWidth || 640;
                    this.canvas.height = preview.videoHeight || 480;
                    const ctx = this.canvas.getContext('2d');
                    this.canvasInterval = setInterval(() => {
                        ctx.save();
                        ctx.scale(-1, 1);
                        ctx.drawImage(preview, -this.canvas.width, 0, this.canvas.width, this.canvas.height);
                        ctx.restore();
                    }, 33); // ~30fps
                    streamToRecord = this.canvas.captureStream();
                    // Copiar el audio del stream original
                    const audioTracks = this.videoStream.getAudioTracks();
                    if (audioTracks.length > 0) {
                        streamToRecord.addTrack(audioTracks[0]);
                    }
                    this.canvasStream = streamToRecord;
                }
                this.mediaRecorder = new MediaRecorder(streamToRecord);
                const chunks = [];

                this.mediaRecorder.ondataavailable = (e) => {
                    if (e.data.size > 0) {
                        chunks.push(e.data);
                    }
                };

                this.mediaRecorder.onstop = () => {
                    const blob = new Blob(chunks, { type: 'video/mp4' });
                    this.videoUrl = URL.createObjectURL(blob);
                    this.videoGrabado = true;
                    this.grabando = false;
                    clearInterval(this.timer);
                    this.tiempoRestante = 30;
                    this.limpiarCanvasMirror();
                };

                this.mediaRecorder.start();
                this.grabando = true;
                this.iniciarTemporizador();
            } catch (error) {
                this.grabando = false;
                if (this.videoStream) {
                    this.videoStream.getTracks().forEach(track => track.stop());
                    this.videoStream = null;
                }
                this.limpiarCanvasMirror();
                alert('Error al acceder a la cámara. Por favor, asegúrate de dar los permisos necesarios.');
            }
        },

        detenerGrabacion() {
            if (this.mediaRecorder && this.grabando) {
                this.mediaRecorder.stop();
                // Ocultar el preview de la cámara al detener la grabación
                this.mostrarPreview = false;
                // Detener temporalmente el videoStream pero sin liberarlo completamente
                if (this.videoStream) {
                    const preview = this.$refs.preview || this.$refs.previewDesktop;
                    if (preview) {
                        preview.srcObject = null;
                    }
                }
            }
        },

        iniciarTemporizador() {
            this.timer = setInterval(() => {
                if (this.tiempoRestante > 0) {
                    this.tiempoRestante--;
                } else {
                    this.detenerGrabacion();
                }
            }, 1000);
        },

        async procesarVideo() {
            if (!this.videoUrl) return;
            this.traduciendo = true;
            try {
                const response = await fetch(this.videoUrl);
                const blob = await response.blob();
                const formData = new FormData();
                formData.append('video', blob, 'grabacion.mp4');

                const result = await fetch('procesar_video.php', {
                    method: 'POST',
                    body: formData
                });

                // Primero verificar si la respuesta es JSON válido
                const contentType = result.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await result.text();
                    console.error('Respuesta no JSON:', text);
                    throw new Error('El servidor no devolvió una respuesta JSON válida. Revisa los logs.');
                }

                const data = await result.json();
                if (data.success) {
                    this.traduccion = data.traduccion;
                    // Hacer scroll automático a la traducción
                    this.$nextTick(() => {
                        const traduccionElement = document.querySelector('.material-translation');
                        if (traduccionElement) {
                            traduccionElement.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center'
                            });
                        }
                    });
                } else {
                    throw new Error(data.error || 'Error desconocido al procesar el video');
                }
            } catch (error) {
                console.error('Error procesando video:', error);
                // Mostrar el error real en lugar del mensaje genérico
                if (error.message.includes('config.local.php')) {
                    alert('Error de configuración: No se encontró el archivo config.local.php. Por favor, configura las credenciales.');
                } else if (error.message.includes('GEMINI_API_KEY')) {
                    alert('Error: La API Key de Gemini no está configurada correctamente.');
                } else if (error.message.includes('fetch')) {
                    alert('Error de conexión: No se pudo conectar con el servidor.');
                } else {
                    alert('Error: ' + error.message);
                }
            } finally {
                this.traduciendo = false;
            }
        },

        // Hand Tracking Methods
        toggleHandTracking() {
            this.handTrackingEnabled = !this.handTrackingEnabled;
            if (this.handTrackingEnabled) {
                // Pequeño delay para asegurar que el toggle se ha aplicado
                setTimeout(() => this.initHandTracking(), 100);
            } else {
                this.stopHandTracking();
            }
        },

        async initHandTracking() {
            if (typeof Hands === 'undefined') {
                alert('MediaPipe Hands no está disponible. Verifica tu conexión a internet.');
                this.handTrackingEnabled = false;
                return;
            }

            try {
                // Configuración mejorada para evitar problemas con Module.arguments
                const handsConfig = {
                    locateFile: (file) => {
                        // Usar una versión específica y estable
                        return `https://cdn.jsdelivr.net/npm/@mediapipe/hands@0.4.1646424915/${file}`;
                    }
                };

                // Crear la instancia y marcarla como raw inmediatamente
                this.hands = markRaw(new Hands(handsConfig));

                // Configurar opciones con valores más conservadores
                await this.hands.setOptions({
                    maxNumHands: 2,
                    modelComplexity: 0, // Cambiado a 0 para mejor rendimiento
                    minDetectionConfidence: 0.5,
                    minTrackingConfidence: 0.5,
                    selfieMode: false // Cambiado a false para evitar el flip no deseado
                });

                // Configurar callback de resultados
                this.hands.onResults((results) => {
                    this.onHandResults(results);
                });

                // Esperar un momento antes de iniciar la detección
                await new Promise(resolve => setTimeout(resolve, 100));
                
                // Crear canvas temporal e iniciar detección
                this.createTempCanvasForHandTracking();
                this.startHandDetection();
                
            } catch (error) {
                console.error('Error inicializando hand tracking:', error);
                this.handTrackingEnabled = false;
                alert('Error al inicializar hand tracking. Intenta recargar la página.');
            }
        },

        onHandResults(results) {
            // Implementar el patrón "Raw In, Plain Out"
            try {
                // Usar toRaw para asegurar que trabajamos con datos sin proxy
                const rawResults = toRaw(results);

                if (rawResults && rawResults.multiHandLandmarks) {
                    // console.log('Resultados de hand tracking recibidos:', rawResults.multiHandLandmarks.length, 'mano(s)');
                    
                    // Usar structuredClone si está disponible (moderna API), sino JSON fallback
                    if (typeof structuredClone !== 'undefined') {
                        this.handResults = structuredClone({
                            multiHandLandmarks: rawResults.multiHandLandmarks,
                            multiHandedness: rawResults.multiHandedness
                        });
                    } else {
                        // Fallback para navegadores que no soportan structuredClone
                        this.handResults = {
                            multiHandLandmarks: JSON.parse(JSON.stringify(rawResults.multiHandLandmarks)),
                            multiHandedness: rawResults.multiHandedness ? JSON.parse(JSON.stringify(rawResults.multiHandedness)) : null
                        };
                    }
                } else {
                    this.handResults = null;
                }
                
                // Dibujar en el siguiente frame para asegurar que Vue haya procesado los cambios
                requestAnimationFrame(() => {
                    this.drawHandLandmarks();
                });
            } catch (error) {
                console.error('Error procesando resultados de hand tracking:', error);
                this.handResults = null;
            }
        },

        startHandDetection() {
            if (!this.hands || !this.videoStream) return;

            // Crear un canvas temporal para copiar frames del video
            this.createTempCanvasForHandTracking();

            const processFrame = async () => {
                try {
                    if (!this.handTrackingEnabled || !this.mostrarPreview || !this.tempCanvas || !this.hands) {
                        return;
                    }

                    // Copiar frame del video al canvas temporal
                    const video = this.$refs.preview || this.$refs.previewDesktop;
                    if (video && video.readyState >= 2 && video.videoWidth > 0) {
                        // Ajustar tamaño del canvas al video
                        const width = video.videoWidth;
                        const height = video.videoHeight;
                        
                        // Solo redimensionar si es necesario
                        if (this.tempCanvas.width !== width || this.tempCanvas.height !== height) {
                            this.tempCanvas.width = width;
                            this.tempCanvas.height = height;
                        }
                        
                        // Dibujar el frame actual del video en el canvas
                        this.tempCanvasCtx.drawImage(video, 0, 0, width, height);
                        
                        // Enviar el canvas directamente sin createImageBitmap
                        // Esto evita problemas de compatibilidad
                        try {
                            await this.hands.send({ image: this.tempCanvas });
                        } catch (sendError) {
                            console.warn('Error enviando frame a MediaPipe:', sendError);
                            // No detener el tracking por un error puntual
                        }
                    }

                    if (this.handTrackingEnabled) {
                        // Volver a usar requestAnimationFrame para mayor fluidez
                        this.handTrackingLoop = requestAnimationFrame(processFrame);
                    }
                } catch (error) {
                    console.error('Error en hand detection frame:', error);
                    // Solo mostrar alerta si el error persiste
                    if (this.errorCount === undefined) this.errorCount = 0;
                    this.errorCount++;
                    
                    if (this.errorCount > 10) {
                        this.handTrackingEnabled = false;
                        alert('Error persistente en hand tracking. Se ha desactivado automáticamente.');
                        this.errorCount = 0;
                    }
                }
            };

            // Iniciar el proceso con un pequeño delay
            if (this.handTrackingEnabled && this.tempCanvas) {
                // Resetear contador de errores
                this.errorCount = 0;
                this.handTrackingLoop = requestAnimationFrame(processFrame);
            }
        },

        createTempCanvasForHandTracking() {
            try {
                // Limpiar canvas temporal anterior si existe
                this.cleanupTempCanvas();

                // Crear un canvas temporal fuera del contexto de Vue
                this.tempCanvas = document.createElement('canvas');
                this.tempCanvasCtx = this.tempCanvas.getContext('2d');
                
                // No necesita agregarse al DOM, solo existe en memoria
                // console.log('Canvas temporal creado para hand tracking');
                
            } catch (error) {
                console.error('Error creando canvas temporal:', error);
                this.tempCanvas = null;
                this.tempCanvasCtx = null;
            }
        },

        cleanupTempCanvas() {
            if (this.tempCanvas) {
                try {
                    this.tempCanvasCtx = null;
                    this.tempCanvas = null;
                } catch (e) {
                    console.log('Error cleaning temp canvas:', e);
                }
            }
        },

        drawHandLandmarks() {
            if (!this.handResults) return;

            // Mejorar la detección del canvas y video correctos para cada contexto
            let canvas, video;
            
            // Detectar si estamos en desktop o mobile basado en el ancho de pantalla
            const isDesktop = window.innerWidth >= 769;
            
            if (isDesktop) {
                canvas = this.$refs.handCanvasDesktop;
                video = this.$refs.previewDesktop;
            } else {
                canvas = this.$refs.handCanvasMobile;
                video = this.$refs.preview;
            }
            
            // Fallback a la lógica anterior si no se encuentra
            if (!canvas) {
                canvas = this.$refs.handCanvasMobile || this.$refs.handCanvasDesktop;
            }
            if (!video) {
                video = this.$refs.preview || this.$refs.previewDesktop;
            }
            
            if (!canvas || !video) {
                console.log('Canvas o video no encontrado:', { canvas: !!canvas, video: !!video, isDesktop });
                return;
            }

            // Ajustar tamaño del canvas al video
            const videoWidth = video.videoWidth || video.clientWidth || 640;
            const videoHeight = video.videoHeight || video.clientHeight || 480;
            
            canvas.width = videoWidth;
            canvas.height = videoHeight;
            canvas.style.width = video.clientWidth + 'px';
            canvas.style.height = video.clientHeight + 'px';

            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            if (this.handResults.multiHandLandmarks) {
                for (const landmarks of this.handResults.multiHandLandmarks) {
                    // Aplicar corrección de espejo si el video está en modo espejo
                    const adjustedLandmarks = this.espejo ? landmarks.map(landmark => ({
                        ...landmark,
                        x: 1 - landmark.x // Invertir coordenada X si está en modo espejo
                    })) : landmarks;
                    
                    // Dibujar landmarks (nodos)
                    ctx.fillStyle = '#FF0000';
                    for (let i = 0; i < adjustedLandmarks.length; i++) {
                        const x = adjustedLandmarks[i].x * canvas.width;
                        const y = adjustedLandmarks[i].y * canvas.height;
                        
                        ctx.beginPath();
                        ctx.arc(x, y, 5, 0, 2 * Math.PI);
                        ctx.fill();
                        
                        // Números de los landmarks
                        ctx.fillStyle = '#FFFFFF';
                        ctx.font = '12px Arial';
                        ctx.fillText(i.toString(), x + 8, y + 4);
                        ctx.fillStyle = '#FF0000';
                    }

                    // Dibujar conexiones (skeleton)
                    this.drawHandConnections(ctx, adjustedLandmarks, canvas.width, canvas.height);
                }
            }
        },

        drawHandConnections(ctx, landmarks, width, height) {
            const connections = [
                // Pulgar
                [0, 1], [1, 2], [2, 3], [3, 4],
                // Índice
                [0, 5], [5, 6], [6, 7], [7, 8],
                // Medio
                [0, 9], [9, 10], [10, 11], [11, 12],
                // Anular
                [0, 13], [13, 14], [14, 15], [15, 16],
                // Meñique
                [0, 17], [17, 18], [18, 19], [19, 20],
                // Conexiones de la palma
                [5, 9], [9, 13], [13, 17]
            ];

            ctx.strokeStyle = '#00FF00';
            ctx.lineWidth = 2;

            for (const [start, end] of connections) {
                const startPoint = landmarks[start];
                const endPoint = landmarks[end];
                
                ctx.beginPath();
                ctx.moveTo(startPoint.x * width, startPoint.y * height);
                ctx.lineTo(endPoint.x * width, endPoint.y * height);
                ctx.stroke();
            }
        },

        stopHandTracking() {
            try {
                // Cancelar el loop de animación
                if (this.handTrackingLoop) {
                    cancelAnimationFrame(this.handTrackingLoop);
                    this.handTrackingLoop = null;
                }

                // Resetear contador de errores
                this.errorCount = 0;

                // Limpiar canvas
                const canvasMobile = this.$refs.handCanvasMobile;
                const canvasDesktop = this.$refs.handCanvasDesktop;
                
                if (canvasMobile) {
                    const ctx = canvasMobile.getContext('2d');
                    ctx.clearRect(0, 0, canvasMobile.width, canvasMobile.height);
                }
                
                if (canvasDesktop) {
                    const ctx = canvasDesktop.getContext('2d');
                    ctx.clearRect(0, 0, canvasDesktop.width, canvasDesktop.height);
                }
                
                // Limpiar canvas temporal
                this.cleanupTempCanvas();
                
                // Limpiar MediaPipe Hands
                if (this.hands) {
                    try {
                        this.hands.close();
                    } catch (e) {
                        console.log('Error closing hands:', e);
                    }
                    this.hands = null;
                }
                
                this.handResults = null;
            } catch (error) {
                console.error('Error stopping hand tracking:', error);
            }
        }
    },
    beforeUnmount() {
        this.detenerPreview();
        if (this.timer) {
            clearInterval(this.timer);
        }
        // Asegurar que hand tracking se detenga completamente
        this.handTrackingEnabled = false;
        this.stopHandTracking();
    }
}).mount('#app'); 