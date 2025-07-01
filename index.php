<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traductor LSC a Español</title>
    
    <!-- Material Web Components (Material Design v3) -->
    <script type="module" src="https://esm.run/@material/web/all.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <!-- MediaPipe Hands for Hand Tracking -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
    
    <script type="importmap">
      {
        "imports": {
          "@material/web/": "https://esm.run/@material/web/"
        }
      }
    </script>
    <script type="module">
      import {styles as typescaleStyles} from 'https://esm.run/@material/web/typography/md-typescale-styles.js';
      document.adoptedStyleSheets.push(typescaleStyles.styleSheet);
    </script>
    
    <!-- Vue.js 3 -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/estilos.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background: var(--md-sys-color-surface-container, #f4f4f6);
            font-family: 'Roboto', Arial, sans-serif;
        }
        
        /* Desktop Twitch-like layout */
        @media (min-width: 769px) {
            .app-container {
                display: flex;
                height: 100vh;
                max-width: 100vw;
                margin: 0;
                border-radius: 0;
                background: var(--md-sys-color-surface-container, #f4f4f6);
                box-shadow: none;
            }
            
            .desktop-sidebar {
                width: 240px;
                background: var(--md-sys-color-surface, #fff);
                border-right: 1px solid var(--md-sys-color-outline-variant, #e0e0e0);
                display: flex;
                flex-direction: column;
                position: fixed;
                height: 100vh;
                z-index: 1000;
            }
            
            .sidebar-header {
                padding: 20px 16px;
                border-bottom: 1px solid var(--md-sys-color-outline-variant, #e0e0e0);
                background: var(--md-sys-color-primary, #6750a4);
                color: white;
            }
            
            .sidebar-logo {
                font-size: 1.5rem;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .sidebar-nav {
                flex: 1;
                padding: 16px 0;
            }
            
            .sidebar-nav-item {
                display: flex;
                align-items: center;
                padding: 12px 16px;
                color: var(--md-sys-color-on-surface, #222);
                text-decoration: none;
                border: none;
                background: none;
                width: 100%;
                cursor: pointer;
                transition: background-color 0.2s;
                font-family: 'Roboto', Arial, sans-serif;
                font-size: 0.95rem;
            }
            
            .sidebar-nav-item:hover {
                background: var(--md-sys-color-surface-container-high, #f4f4f6);
            }
            
            .sidebar-nav-item.active {
                background: var(--md-sys-color-primary-container, #e8def8);
                color: var(--md-sys-color-on-primary-container, #21005d);
                font-weight: 500;
            }
            
            .sidebar-nav-item .material-icons {
                margin-right: 12px;
                font-size: 20px;
            }
            
            .desktop-main {
                flex: 1;
                margin-left: 240px;
                display: flex;
                flex-direction: column;
                height: 100vh;
            }
            
            .desktop-header {
                background: var(--md-sys-color-surface, #fff);
                border-bottom: 1px solid var(--md-sys-color-outline-variant, #e0e0e0);
                padding: 16px 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                height: 64px;
                box-sizing: border-box;
            }
            
            .desktop-header h1 {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 600;
                color: var(--md-sys-color-on-surface, #222);
            }
            
            .desktop-content {
                flex: 1;
                padding: 24px;
                overflow-y: auto;
                background: var(--md-sys-color-surface-container, #f4f4f6);
            }
            
            .desktop-content-inner {
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .bottom-nav {
                display: none !important;
            }
            
            .welcome-card {
                max-width: 600px;
                margin: 0 auto;
                padding: 48px 32px;
            }
            
            .material-card {
                margin-bottom: 0;
                max-width: 800px;
                margin: 0 auto;
            }
            
            .desktop-video-section {
                display: grid;
                grid-template-columns: 1fr 300px;
                gap: 24px;
                align-items: start;
            }
            
            .desktop-video-main {
                background: var(--md-sys-color-surface, #fff);
                border-radius: 12px;
                padding: 24px;
                border: 1px solid var(--md-sys-color-outline-variant, #e0e0e0);
            }
            
            .desktop-video-sidebar {
                background: var(--md-sys-color-surface, #fff);
                border-radius: 12px;
                padding: 20px;
                border: 1px solid var(--md-sys-color-outline-variant, #e0e0e0);
            }
            
            .desktop-camera-preview {
                width: 100%;
                max-width: 600px;
                height: 400px;
                object-fit: cover;
                border-radius: 12px;
                border: 1px solid var(--md-sys-color-outline-variant, #e0e0e0);
                background: #000;
            }
            
            /* Hide mobile layout completely on desktop */
            .app-container {
                display: none !important;
            }
        }
        
        /* Mobile layout (existing) */
        @media (max-width: 768px) {
            .app-container {
                max-width: 420px;
                margin: 0 auto;
                min-height: 100vh;
                background: var(--md-sys-color-surface, #fff);
                border-radius: 16px 16px 0 0;
                display: flex;
                flex-direction: column;
                box-shadow: var(--md-elevation-level3, 0 4px 20px rgba(0,0,0,0.08));
            }
            
            .desktop-sidebar {
                display: none !important;
            }
            
            .desktop-main {
                display: none !important;
            }
            
            .app-container {
                display: flex !important;
            }
        }
        
        @media (max-width: 600px) {
            .app-container {
                max-width: 100vw;
                min-height: 100vh;
                border-radius: 0;
                border: none;
            }
        }
        
        .material-card {
            margin-bottom: 80px;
            padding: 0;
        }
        .material-card .card-content {
            padding: 24px 20px 20px 20px;
        }
        .material-card .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 18px;
            color: var(--md-sys-color-on-surface, #222);
        }
        .material-btn {
            margin-bottom: 8px;
        }
        .centered-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            padding-top: 32px;
        }
        .welcome-card {
            max-width: 350px;
            margin: 0 auto;
            padding: 32px 24px 28px 24px;
            border-radius: 20px;
            box-shadow: var(--md-elevation-level2, 0 2px 8px rgba(0,0,0,0.06));
            background: var(--md-sys-color-surface-container-high, #f8f7fa);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .welcome-logo {
            width: 72px;
            margin-bottom: 18px;
            border-radius: 16px;
            box-shadow: var(--md-elevation-level1, 0 1px 4px rgba(0,0,0,0.04));
        }
        .welcome-title {
            color: var(--md-sys-color-primary,#6750a4);
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
            letter-spacing: -1px;
        }
        .welcome-desc {
            color: var(--md-sys-color-on-surface,#222);
            font-size: 1.08rem;
            margin-bottom: 28px;
            text-align: center;
        }
        .bottom-nav {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--md-sys-color-surface, #fff);
            border-top: 2px solid var(--md-sys-color-outline-variant, #e0e0e0);
            z-index: 100;
            display: flex;
            justify-content: space-around;
            height: 68px;
            align-items: center;
            box-shadow: var(--md-elevation-level1, 0 1px 4px rgba(0,0,0,0.04));
        }
        .bottom-nav .nav-btn {
            flex: 1;
            text-align: center;
            background: none;
            border: none;
            border-radius: 12px 12px 0 0;
            margin: 0 2px;
            transition: background 0.2s, color 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            color: var(--md-sys-color-on-surface-variant, #757575);
            font-family: 'Roboto', Arial, sans-serif;
        }
        .bottom-nav .nav-btn.active {
            color: var(--md-sys-color-primary, #6750a4);
            background: var(--md-sys-color-surface-container-high, #ede7f6);
        }
        .bottom-nav .nav-btn .material-icons {
            font-size: 28px;
            margin-bottom: 2px;
        }
        .overlay-traduciendo {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .overlay-traduciendo md-circular-progress {
            margin-bottom: 20px;
            --md-circular-progress-active-indicator-color: #fff;
        }
        .overlay-traduciendo .overlay-text {
            color: white;
            font-size: 1.25rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .material-panel {
            border-radius: 12px;
            border: 1.5px solid var(--md-sys-color-outline-variant, #e0e0e0);
            background: var(--md-sys-color-surface-container-low, #f4f4f6);
            padding: 12px 16px;
            margin-bottom: 16px;
            color: var(--md-sys-color-on-surface, #444);
        }
        .material-translation {
            border-radius: 12px;
            border: 1.5px solid var(--md-sys-color-secondary-container, #bdbdbd);
            background: var(--md-sys-color-secondary-container, #f0f0f0);
            padding: 16px;
            font-size: 1.1rem;
            color: var(--md-sys-color-on-secondary-container, #222);
        }
        .dummy-content {
            padding: 32px 16px 80px 16px;
            text-align: center;
            color: var(--md-sys-color-on-surface, #444);
        }
        .md-typescale-title-large {
            font-size: 1.25rem;
            font-weight: 500;
            letter-spacing: 0.1px;
            margin-bottom: 8px;
        }
        .md-typescale-body-medium {
            font-size: 1rem;
            font-weight: 400;
            letter-spacing: 0.25px;
        }
                    .camera-controls {
                position: absolute;
                top: 10px;
                right: 10px;
                display: flex;
                gap: 8px;
                z-index: 2;
            }
            
            .hand-tracking-canvas {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 3;
            }
            
            .hand-tracking-controls {
                position: absolute;
                top: 10px;
                left: 10px;
                display: flex;
                gap: 8px;
                z-index: 4;
            }
            
            @media (min-width: 769px) {
                .hand-tracking-controls {
                    top: 15px;
                    left: 15px;
                }
            }
    </style>
</head>
<body>
    <div id="app">
        <!-- Desktop Layout -->
        <div class="desktop-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <span class="material-icons">translate</span>
                    Traductor LSC
                </div>
            </div>
            <nav class="sidebar-nav">
                <button class="sidebar-nav-item" :class="{active: pantalla === 'bienvenida'}" @click="irA('bienvenida')">
                    <span class="material-icons">home</span>
                    Inicio
                </button>
                <button class="sidebar-nav-item" :class="{active: pantalla === 'grabacion'}" @click="irA('grabacion')">
                    <span class="material-icons">videocam</span>
                    Grabación
                </button>
                <button class="sidebar-nav-item" :class="{active: pantalla === 'historial'}" @click="irA('historial')">
                    <span class="material-icons">history</span>
                    Historial
                </button>
                <button class="sidebar-nav-item" :class="{active: pantalla === 'opciones'}" @click="irA('opciones')">
                    <span class="material-icons">settings</span>
                    Configuración
                </button>
                <button class="sidebar-nav-item" :class="{active: pantalla === 'acerca'}" @click="irA('acerca')">
                    <span class="material-icons">info</span>
                    Acerca de
                </button>
            </nav>
        </div>

        <div class="desktop-main">
            <header class="desktop-header">
                <h1 v-if="pantalla === 'bienvenida'">Bienvenido al Traductor LSC</h1>
                <h1 v-if="pantalla === 'grabacion'">Grabación y Traducción</h1>
                <h1 v-if="pantalla === 'historial'">Historial de Traducciones</h1>
                <h1 v-if="pantalla === 'opciones'">Configuración</h1>
                <h1 v-if="pantalla === 'acerca'">Información de la Aplicación</h1>
                <div>
                    <md-icon-button aria-label="Usuario">
                        <span class="material-icons">account_circle</span>
                    </md-icon-button>
                </div>
            </header>
            <main class="desktop-content">
                <div class="desktop-content-inner">
                    <!-- Desktop content will go here -->
                    
                    <!-- Desktop Bienvenida -->
                    <div v-if="pantalla === 'bienvenida'">
                        <div class="welcome-card">
                            <img src="https://cdn-icons-png.flaticon.com/512/9068/9068753.png" alt="LSC" class="welcome-logo">
                            <div class="welcome-title">Traductor LSC</div>
                            <div class="welcome-desc">
                                Graba un video en Lengua de Señas Colombiana (LSC) y tradúcelo automáticamente a texto en español usando inteligencia artificial.
                            </div>
                            <md-filled-button class="material-btn" @click="comenzar">
                                <span class="material-icons" slot="icon">videocam</span>
                                Comenzar
                            </md-filled-button>
                        </div>
                    </div>

                    <!-- Desktop Grabación -->
                    <div v-if="pantalla === 'grabacion'" class="desktop-video-section">
                        <div class="desktop-video-main">
                            <div v-if="grabando" class="material-panel" style="background:var(--md-sys-color-secondary-container,#ededed); color:var(--md-sys-color-on-secondary-container,#444); margin-bottom: 20px;">
                                <b>Grabando...</b> Tiempo restante: {{ tiempoRestante }} segundos
                            </div>
                            
                            <div v-if="pantalla === 'grabacion' && mostrarPreview" class="section" style="position:relative; margin-bottom: 20px;">
                                <video ref="previewDesktop" class="desktop-camera-preview" :style="espejo ? 'transform:scaleX(-1);' : ''" autoplay muted playsinline></video>
                                
                                <!-- Canvas para Hand Tracking (Desktop) -->
                                <canvas ref="handCanvasDesktop" class="hand-tracking-canvas" :style="espejo ? 'transform:scaleX(-1);' : ''" v-show="handTrackingEnabled"></canvas>
                                
                                <!-- Controles de Hand Tracking -->
                                <div class="hand-tracking-controls">
                                    <md-icon-button class="material-btn" @click="toggleHandTracking" :aria-label="handTrackingEnabled ? 'Desactivar hand tracking' : 'Activar hand tracking'">
                                        <span class="material-icons" :style="handTrackingEnabled ? 'color: #4CAF50;' : ''">{{ handTrackingEnabled ? 'back_hand' : 'pan_tool' }}</span>
                                    </md-icon-button>
                                </div>
                                
                                <div class="camera-controls">
                                    <md-icon-button class="material-btn" @click="alternarCamara" aria-label="Alternar cámara">
                                        <span class="material-icons">flip_camera_android</span>
                                    </md-icon-button>
                                    <md-icon-button class="material-btn" @click="alternarEspejo" aria-label="Modo espejo">
                                        <span class="material-icons">flip</span>
                                    </md-icon-button>
                                </div>
                            </div>
                            
                            <div v-if="videoGrabado" class="section" style="margin-bottom: 20px;">
                                <video class="desktop-camera-preview" controls :src="videoUrl"></video>
                            </div>
                            
                            <div v-if="traduccion" class="section">
                                <h3 class="md-typescale-title-large" style="color:var(--md-sys-color-primary,#6750a4); margin-bottom: 16px;">Traducción</h3>
                                <div class="material-translation">
                                    {{ traduccion }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="desktop-video-sidebar">
                            <h3 style="margin-top: 0; color: var(--md-sys-color-primary,#6750a4);">Controles</h3>
                            
                            <md-filled-button v-if="!grabando && !videoGrabado" class="material-btn" @click="iniciarGrabacion" style="width: 100%; margin-bottom: 12px;">
                                <span class="material-icons" slot="icon">videocam</span>
                                Grabar Video
                            </md-filled-button>
                            
                            <md-filled-button v-if="!grabando && videoGrabado" class="material-btn" @click="iniciarGrabacion" style="width: 100%; margin-bottom: 12px;">
                                <span class="material-icons" slot="icon">videocam</span>
                                Volver a Grabar
                            </md-filled-button>
                            
                            <md-filled-tonal-button v-if="grabando" class="material-btn" @click="detenerGrabacion" style="width: 100%; margin-bottom: 12px;">
                                <span class="material-icons" slot="icon">stop</span>
                                Detener
                            </md-filled-tonal-button>
                            
                            <md-filled-button v-if="videoGrabado" class="material-btn" @click="procesarVideo" style="width: 100%; margin-bottom: 12px; --md-filled-button-container-color: #4CAF50;">
                                <span class="material-icons" slot="icon">translate</span>
                                Traducir
                            </md-filled-button>
                            
                            <div style="margin-top: 24px;">
                                <h4 style="color: var(--md-sys-color-on-surface,#444); margin-bottom: 12px;">Hand Tracking</h4>
                                <md-outlined-button v-if="!handTrackingEnabled" @click="toggleHandTracking" style="width: 100%; margin-bottom: 12px;">
                                    <span class="material-icons" slot="icon">pan_tool</span>
                                    Activar Tracking
                                </md-outlined-button>
                                <md-filled-tonal-button v-if="handTrackingEnabled" @click="toggleHandTracking" style="width: 100%; margin-bottom: 12px;">
                                    <span class="material-icons" slot="icon">back_hand</span>
                                    Desactivar Tracking
                                </md-filled-tonal-button>
                                <p style="font-size: 0.8rem; color: var(--md-sys-color-on-surface-variant,#666); line-height: 1.3; margin-bottom: 16px;">
                                    {{ handTrackingEnabled ? 'Hand tracking activo. Se muestran 21 landmarks por mano detectada.' : 'Activa el hand tracking para ver los nodos y skeleton de las manos en tiempo real.' }}
                                </p>
                            </div>
                            
                            <div style="margin-top: 24px;">
                                <h4 style="color: var(--md-sys-color-on-surface,#444); margin-bottom: 12px;">Instrucciones</h4>
                                <p style="font-size: 0.9rem; color: var(--md-sys-color-on-surface-variant,#666); line-height: 1.4;">
                                    1. Haz clic en "Grabar Video"<br>
                                    2. Realiza las señas en LSC<br>
                                    3. El video se detendrá automáticamente en 30 segundos<br>
                                    4. Haz clic en "Traducir" para obtener el texto
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Historial -->
                    <div v-if="pantalla === 'historial'">
                        <md-elevated-card style="max-width:100%; padding: 32px;">
                            <h2 class="md-typescale-title-large" style="color:var(--md-sys-color-primary,#6750a4); margin-top: 0;">Historial de Traducciones</h2>
                            <p class="md-typescale-body-medium">Próximamente podrás ver aquí el historial de traducciones realizadas.</p>
                        </md-elevated-card>
                    </div>

                    <!-- Desktop Opciones -->
                    <div v-if="pantalla === 'opciones'">
                        <md-elevated-card style="max-width:100%; padding: 32px;">
                            <h2 class="md-typescale-title-large" style="color:var(--md-sys-color-primary,#6750a4); margin-top: 0;">Configuración</h2>
                            <p class="md-typescale-body-medium">Configuraciones y preferencias de la aplicación estarán disponibles aquí.</p>
                        </md-elevated-card>
                    </div>

                    <!-- Desktop Acerca de -->
                    <div v-if="pantalla === 'acerca'">
                        <md-elevated-card style="max-width:100%; padding: 32px;">
                            <h2 class="md-typescale-title-large" style="color:var(--md-sys-color-primary,#6750a4); margin-top: 0;">Acerca de</h2>
                            <p class="md-typescale-body-medium">
                                <strong>Traductor LSC a Español</strong><br>
                                Desarrollado con Material Web Components y Vue.js<br>
                                Versión 1.0<br><br>
                                Esta aplicación utiliza inteligencia artificial para traducir videos en Lengua de Señas Colombiana (LSC) a texto en español.
                            </p>
                        </md-elevated-card>
                    </div>
                </div>
            </main>
        </div>

        <!-- Mobile Layout (existing) -->
        <div class="app-container">
            <!-- Overlay traduciendo -->
            <div v-if="traduciendo" class="overlay-traduciendo">
                <md-circular-progress indeterminate aria-label="Traduciendo"></md-circular-progress>
                <span class="overlay-text">Traduciendo...</span>
                <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.875rem; margin-top: 8px;">Analizando señas con IA</span>
            </div>

            <!-- Pantalla de bienvenida -->
            <div v-if="pantalla === 'bienvenida'" class="centered-content">
                <div class="welcome-card">
                    <img src="https://cdn-icons-png.flaticon.com/512/9068/9068753.png" alt="LSC" class="welcome-logo">
                    <div class="welcome-title">Traductor LSC</div>
                    <div class="welcome-desc">
                        Graba un video en Lengua de Señas Colombiana (LSC) y tradúcelo automáticamente a texto en español usando inteligencia artificial.
                    </div>
                    <md-filled-button class="material-btn" @click="comenzar">
                        <span class="material-icons" slot="icon">videocam</span>
                        Comenzar
                    </md-filled-button>
                </div>
            </div>

            <!-- Pantalla de grabación -->
            <div v-if="pantalla === 'grabacion'" class="row center-align" style="margin-top: 24px; flex: 1;">
                <div class="col s12">
                    <md-elevated-card class="material-card">
                        <div class="card-content">
                            <span class="card-title md-typescale-title-large" style="color:var(--md-sys-color-primary,#6750a4);">Traductor LSC a Español</span>
                            <div class="section">
                                <div v-if="grabando" class="material-panel" style="background:var(--md-sys-color-secondary-container,#ededed); color:var(--md-sys-color-on-secondary-container,#444);">
                                    <b>Grabando...</b> Tiempo restante: {{ tiempoRestante }} segundos
                                </div>
                                <md-filled-button v-if="!grabando && !videoGrabado" class="material-btn" @click="iniciarGrabacion" style="width: 100%; margin-bottom: 12px;">
                                    <span class="material-icons" slot="icon">videocam</span>
                                    Grabar Video
                                </md-filled-button>
                                <md-filled-button v-if="!grabando && videoGrabado" class="material-btn" @click="iniciarGrabacion" style="width: 100%; margin-bottom: 12px;">
                                    <span class="material-icons" slot="icon">videocam</span>
                                    Volver a Grabar
                                </md-filled-button>
                                <md-filled-tonal-button v-if="grabando" class="material-btn" @click="detenerGrabacion" style="width: 100%; margin-bottom: 12px;">
                                    <span class="material-icons" slot="icon">stop</span>
                                    Detener
                                </md-filled-tonal-button>
                            </div>
                            <div v-if="pantalla === 'grabacion' && mostrarPreview" class="section" style="position:relative;">
                                <video ref="preview" class="responsive-video" :style="espejo ? 'transform:scaleX(-1);max-width:100%;max-height:240px;border-radius:12px;border:1.5px solid var(--md-sys-color-outline-variant,#e0e0e0);' : 'max-width:100%;max-height:240px;border-radius:12px;border:1.5px solid var(--md-sys-color-outline-variant,#e0e0e0);'" autoplay muted playsinline></video>
                                
                                <!-- Canvas para Hand Tracking (Mobile) -->
                                <canvas ref="handCanvasMobile" class="hand-tracking-canvas" :style="espejo ? 'transform:scaleX(-1);max-width:100%;max-height:240px;border-radius:12px;' : 'max-width:100%;max-height:240px;border-radius:12px;'" v-show="handTrackingEnabled"></canvas>
                                
                                <!-- Controles de Hand Tracking -->
                                <div class="hand-tracking-controls">
                                    <md-icon-button class="material-btn" @click="toggleHandTracking" :aria-label="handTrackingEnabled ? 'Desactivar hand tracking' : 'Activar hand tracking'">
                                        <span class="material-icons" :style="handTrackingEnabled ? 'color: #4CAF50;' : ''">{{ handTrackingEnabled ? 'back_hand' : 'pan_tool' }}</span>
                                    </md-icon-button>
                                </div>
                                
                                <div class="camera-controls">
                                    <md-icon-button class="material-btn" @click="alternarCamara" aria-label="Alternar cámara">
                                        <span class="material-icons">flip_camera_android</span>
                                    </md-icon-button>
                                    <md-icon-button class="material-btn" @click="alternarEspejo" aria-label="Modo espejo">
                                        <span class="material-icons">flip</span>
                                    </md-icon-button>
                                </div>
                            </div>
                            <div v-if="videoGrabado" class="section">
                                <video class="responsive-video" controls :src="videoUrl" style="max-width: 100%; border-radius: 12px; border: 1.5px solid var(--md-sys-color-outline-variant,#e0e0e0);"></video>
                                <md-filled-button class="material-btn" style="margin-top: 15px; --md-filled-button-container-color: #4CAF50;" @click="procesarVideo">
                                    <span class="material-icons" slot="icon">translate</span>
                                    Traducir
                                </md-filled-button>
                            </div>
                            <div v-if="traduccion" class="section">
                                <h5 class="md-typescale-title-large" style="color:var(--md-sys-color-primary,#6750a4);">Traducción</h5>
                                <div class="material-translation">
                                    {{ traduccion }}
                                </div>
                            </div>
                        </div>
                    </md-elevated-card>
                </div>
            </div>

            <!-- Dummy: Historial -->
            <div v-if="pantalla === 'historial'" class="dummy-content">
                <md-elevated-card style="max-width:340px;margin:0 auto;">
                    <div class="card-content">
                        <h2 class="md-typescale-title-large" style="color:var(--md-sys-color-primary,#6750a4);">Historial</h2>
                        <p class="md-typescale-body-medium">Próximamente podrás ver aquí el historial de traducciones realizadas.</p>
                        <md-outlined-button class="material-btn" @click="irA('grabacion')">
                            <span class="material-icons" slot="icon">arrow_back</span>
                            Volver
                        </md-outlined-button>
                    </div>
                </md-elevated-card>
            </div>
            <!-- Dummy: Opciones -->
            <div v-if="pantalla === 'opciones'" class="dummy-content">
                <md-elevated-card style="max-width:340px;margin:0 auto;">
                    <div class="card-content">
                        <h2 class="md-typescale-title-large" style="color:var(--md-sys-color-primary,#6750a4);">Opciones</h2>
                        <p class="md-typescale-body-medium">Configuraciones y preferencias de la aplicación estarán disponibles aquí.</p>
                        <md-outlined-button class="material-btn" @click="irA('grabacion')">
                            <span class="material-icons" slot="icon">arrow_back</span>
                            Volver
                        </md-outlined-button>
                    </div>
                </md-elevated-card>
            </div>
            <!-- Dummy: Acerca de -->
            <div v-if="pantalla === 'acerca'" class="dummy-content">
                <md-elevated-card style="max-width:340px;margin:0 auto;">
                    <div class="card-content">
                        <h2 class="md-typescale-title-large" style="color:var(--md-sys-color-primary,#6750a4);">Acerca de</h2>
                        <p class="md-typescale-body-medium">Traductor LSC a Español<br>Desarrollado con Material Web Components.<br>Versión 1.0</p>
                        <md-outlined-button class="material-btn" @click="irA('grabacion')">
                            <span class="material-icons" slot="icon">arrow_back</span>
                            Volver
                        </md-outlined-button>
                    </div>
                </md-elevated-card>
            </div>
        </div>
        <!-- Barra de navegación inferior -->
        <nav class="bottom-nav">
            <button class="nav-btn" :class="{active: barra==='inicio'}" @click="irA('bienvenida')" aria-label="Inicio">
                <span class="material-icons">home</span>
                <span style="font-size:12px;">Inicio</span>
            </button>
            <button class="nav-btn" :class="{active: barra==='historial'}" @click="irA('historial')" aria-label="Historial">
                <span class="material-icons">history</span>
                <span style="font-size:12px;">Historial</span>
            </button>
            <button class="nav-btn" :class="{active: barra==='opciones'}" @click="irA('opciones')" aria-label="Opciones">
                <span class="material-icons">settings</span>
                <span style="font-size:12px;">Opciones</span>
            </button>
            <button class="nav-btn" :class="{active: barra==='acerca'}" @click="irA('acerca')" aria-label="Acerca de">
                <span class="material-icons">info</span>
                <span style="font-size:12px;">Acerca de</span>
            </button>
        </nav>
    </div>

    <!-- App JS -->
    <script src="assets/js/app.js"></script>
</body>
</html> 