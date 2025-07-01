# Changelog: Traductor LSC a Español

## [Inicio del Proyecto] - 2024-03-19

### Creado
- Archivo `roadmap.md` con el plan de desarrollo
- Archivo `changelog.md` para documentación de avances

### [Configuración Inicial] - 2024-03-19
- Creada estructura de directorios base
- Implementado `index.php` con estructura HTML y dependencias CDN
- Creado archivo `estilos.css` con estilos básicos
- Configurado `config.php` con constantes de la aplicación

### [Configuración Base de Datos] - 2024-03-19
- Creado archivo `database.php` con clase de conexión PDO
- Implementado patrón Singleton para conexión a base de datos
- Creadas tablas `traducciones` y `usuarios`
- Creado script `init_db.php` para inicialización de tablas
- Ejecutada migración con `php init_db.php` para crear las tablas en la base de datos

### [Implementación Grabación de Video] - 2024-03-19
- Implementado `app.js` con lógica de Vue.js
- Integrado MediaRecorder API para grabación de video
- Añadido temporizador de 30 segundos
- Mejorada interfaz de usuario con feedback visual
- Implementada funcionalidad de detener grabación

### [Integración API Gemini] - 2024-03-19
- Creado `procesar_video.php` para manejo de videos
- Implementada conversión de video a base64
- Integrada API Gemini para análisis de video
- Añadido almacenamiento de traducciones en base de datos
- Implementado manejo de errores y respuestas JSON

### [Mejoras y Actualizaciones] - 2024-03-19
- Actualizado el modelo de Gemini a `gemini-2.5-flash-preview-05-20`
- Añadida vista previa de la cámara web durante la grabación en la interfaz

### [UX/UI y Navegación] - 2024-03-19
- El botón de grabar video ahora está habilitado al cargar la app (excepto durante grabación)
- El botón de "Inicio" en la barra inferior lleva a la pantalla de bienvenida
- Todos los botones de la barra inferior muestran feedback visual de selección
- Eliminadas sombras, ahora se usan outlines (bordes) en tarjetas y contenedores
- Rediseño moderno y limpio inspirado en Material Design: bordes redondeados, colores suaves, botones modernos
- Diseño responsive tipo app móvil: ancho completo en móvil, ancho angosto en escritorio
- Apariencia de app, con navegación y feedback visual mejorados

### [Modelo Gemini y Corrección de Botón] - 2024-03-19
- Actualizado el modelo Gemini a `gemini-2.5-pro-preview-06-05`
- Solucionado definitivamente el problema del botón "Grabar Video" deshabilitado: ahora siempre está habilitado salvo durante la grabación, y se limpian correctamente los estados al cambiar de pantalla o finalizar grabación

### [Escala de Grises y Visibilidad de Botones] - 2024-03-19
- Cambiada la paleta de colores a escala de grises neutros en toda la app
- Botones ahora son menos curvos (border-radius reducido)
- El botón "Grabar Video" ahora se muestra solo cuando no se está grabando, y el botón "Detener" solo durante la grabación (no se usa disabled)
- Eliminados colores vivos, ahora todo es gris, blanco y negro

### [Preview de Cámara Siempre Visible] - 2024-03-19
- Ahora la cámara (preview) es visible siempre en la pantalla de grabación, no solo durante la grabación. Se inicializa al entrar a la pantalla de grabación y se detiene al salir de ella.

### [Botones de Alternar Cámara y Modo Espejo] - 2024-03-19
- Añadidos dos botones sobre el preview de la cámara en la pantalla de grabación:
  - Alternar cámara (frontal/trasera)
  - Girar horizontalmente (modo espejo)

### [Modo Espejo en Video Grabado] - 2024-03-19
- Ahora el modo espejo también se aplica al video grabado, usando un canvas oculto para reflejar el video si la opción está activa.

### [Rediseño con Material Web Components] - 2024-03-19
- Rediseño completo de la app usando Material Web Components (Material Design v3)
- Reemplazados todos los botones, controles y tarjetas por componentes de Material Web
- Barra inferior rediseñada con icon buttons de Material Web
- Páginas dummy agregadas para Historial, Opciones y Acerca de, accesibles desde la barra inferior
- Layout y experiencia de usuario mejorados según los principios de Material Design v3

### [Refactor visual con Material You y mejores prácticas de materialv3] - 2024-03-19
- Refactor visual completo usando Material Web v3 (Material You), siguiendo la documentación y ejemplos de la carpeta materialv3
- Uso de colores, tipografía, elevación y componentes recomendados por Material You
- Accesibilidad mejorada (aria-labels, contraste, feedback visual)
- Jerarquía visual y experiencia de usuario optimizadas en todas las pantallas
- Páginas dummy y navegación mejoradas con tarjetas elevadas, botones tonales y outlined, y feedback de selección

### [Corrección de íconos y enriquecimiento visual] - 2024-03-19
- Corregida la carga y visibilidad de los íconos Material en toda la app
- Pantalla de bienvenida centrada y enriquecida visualmente con tarjeta elevada, mejor tipografía y logo
- Barra inferior mejorada: íconos visibles, alineación y feedback visual
- Mejor jerarquía visual y detalles de Material You en todas las pantallas

### Próximos Pasos
- Mejorar pantallas de historial, opciones y acerca de
- Pruebas finales de experiencia de usuario

## v1.6.0 - Layout Desktop Tipo Twitch (2024-01-20)

### ✨ Nuevas Características
- **Layout Desktop Tipo Twitch**: Implementado diseño desktop completamente nuevo que imita la interfaz de Twitch
  - Sidebar izquierdo fijo con navegación principal
  - Header superior con título dinámico y avatar de usuario
  - Área principal de contenido con diseño optimizado para desktop
  - Logo y branding en el sidebar con iconos Material
  - Navegación por botones en el sidebar con estados activos/hover

### 🎨 Mejoras de UI/UX Desktop
- **Sección de Video Mejorada**: Layout de dos columnas para la grabación
  - Área principal para video de 600x400px optimizado para cámaras
  - Sidebar derecho con controles y instrucciones
  - Controles de cámara (alternar/espejo) posicionados sobre el video
  - Instrucciones paso a paso para el usuario

### 📱 Responsividad Mantenida
- **Mobile First**: La versión móvil existente se mantiene intacta
- **Breakpoints**: Layout desktop activo desde 769px, móvil hasta 768px
- **Componentes Ocultos**: Desktop y mobile layouts se ocultan mutuamente según el viewport

### 🎯 Funcionalidades Desktop
- **Pantallas Adaptadas**: Todas las páginas (bienvenida, grabación, historial, opciones, acerca) optimizadas para desktop
- **Header Dinámico**: Títulos que cambian según la sección activa
- **Navegación Lateral**: Iconos Material con texto descriptivo y estados visuales
- **Cards Expandidas**: Contenido con máximo ancho de 1200px centrado

### 🔧 Aspectos Técnicos
- CSS Grid para layout de video (main + sidebar)
- Flexbox para estructura general desktop
- Media queries específicas para separar mobile/desktop
- Mantenimiento de la funcionalidad Vue.js existente
- Material Design v3 consistente en ambas versiones 

## v1.7.0 - Hand Tracking con MediaPipe Hands (2024-01-20)

### 🖐️ Nueva Funcionalidad: Hand Tracking
- **MediaPipe Hands Integration**: Implementado sistema completo de detección y seguimiento de manos
  - 21 landmarks (nodos) por mano detectada en tiempo real
  - Skeleton completo con conexiones entre articulaciones
  - Soporte para hasta 2 manos simultáneamente
  - Visualización con nodos rojos y conexiones verdes

### 🎯 Características del Hand Tracking
- **Toggle de Activación**: Botón dedicado para activar/desactivar hand tracking
  - Icono dinámico (pan_tool/back_hand) con indicador visual verde cuando está activo
  - Disponible tanto en móvil como desktop
  - Controles integrados en la interfaz de cámara

### 🎨 Visualización de Landmarks
- **Nodos Numerados**: Cada landmark muestra su número identificador (0-20)
- **Skeleton Completo**: Conexiones que dibujan la estructura completa de la mano:
  - Pulgar: landmarks 0-4
  - Índice: landmarks 5-8
  - Medio: landmarks 9-12
  - Anular: landmarks 13-16
  - Meñique: landmarks 17-20
  - Conexiones de palma entre dedos

### 🔧 Integración Técnica
- **Canvas Overlay**: Capa transparente sobre el video para dibujar landmarks
- **Responsive Design**: Canvas se ajusta automáticamente al tamaño del video
- **Performance Optimizado**: RequestAnimationFrame para renderizado suave
- **Detección Robusta**: Configuración optimizada para LSC:
  - Confianza de detección: 0.5
  - Confianza de seguimiento: 0.5
  - Complejidad del modelo: 1

### 📱 Compatibilidad
- **Desktop**: Canvas integrado en layout tipo Twitch
- **Mobile**: Canvas adaptativo en layout móvil
- **Ambos Modos Espejo**: Hand tracking funciona con transformación de espejo
- **Controles Unificados**: Mismo comportamiento en ambas plataformas

### 🎛️ Controles de Usuario
- **Sidebar Desktop**: Botón dedicado con descripción del estado
- **Overlay Mobile**: Botón flotante en esquina superior izquierda
- **Estados Visuales**: 
  - Inactivo: Icono gris con "pan_tool"
  - Activo: Icono verde con "back_hand"
  - Feedback textual en desktop sobre el estado

### 🌐 Dependencias
- **MediaPipe Hands**: Librería oficial de Google via CDN
- **CDN Scripts**: camera_utils, control_utils, drawing_utils, hands.js
- **Detección de Disponibilidad**: Fallback y alertas si la librería no está disponible

## [1.1.5] - Restauración de generationConfig

### Corregido
- **generationConfig Restaurado**: 
  - Reintegrado con parámetros ajustados que no causan conflicto
  - `maxOutputTokens` aumentado de 100 a 256 para evitar conflictos con prompts largos
  - Eliminado `candidateCount` que no es soportado por todos los modelos
  - `topK` ajustado de 32 a 40 (valor más estándar)

### Documentado
- **Análisis del Error 500**:
  - Creado `debug_generationconfig.md` con explicación detallada
  - El error era causado por conflicto entre prompt largo y `maxOutputTokens` muy bajo
  - También por parámetros no soportados como `candidateCount`

## [1.1.4] - Sistema de Logging y Corrección Error 500

### Agregado
- **Sistema de Logging Completo**:
  - Archivo `error.log` en la raíz del proyecto para todos los errores PHP
  - Función `logError()` para registro detallado del proceso
  - Logging en cada etapa del procesamiento de video
  - Captura de stack traces en caso de error

- **Archivo de Diagnóstico**:
  - `test_config.php` para verificar la configuración del sistema
  - Verifica: archivos requeridos, constantes, permisos, base de datos, extensiones PHP

- **Configuración .htaccess**:
  - Configuración de errores PHP
  - Límites de upload aumentados (50MB)
  - Protección del archivo error.log

### Corregido
- **Error 500**: 
  - Cambiado modelo de Gemini de `gemini-2.5-flash` a `gemini-1.5-flash` (modelo válido)
  - Eliminado `generationConfig` que podía causar conflictos con el prompt largo
  - Mejorado manejo de errores CURL y JSON

### Mejorado
- **Diagnóstico de Errores**:
  - Captura de errores CURL específicos
  - Validación de respuesta JSON antes de procesarla
  - Mensajes de error más descriptivos

## [1.1.3] - Mejoras de Debug y Traducción

### Cambiado
- **Debug Desactivado**: Comentados los console.log del hand tracking que llenaban la consola constantemente.
- **Prompt Simplificado**: 
  - Reducido de 30+ líneas a una sola instrucción clara y directa.
  - Garantiza respuestas únicamente en español.
  - Elimina explicaciones innecesarias en las traducciones.

### Agregado
- **Configuración de Generación**: 
  - Temperature: 0.4 (respuestas más consistentes)
  - maxOutputTokens: 100 (respuestas más concisas)
  - topK: 32, topP: 0.95 (mejor calidad de respuesta)

## [1.1.2] - Corrección de Rendimiento y Espejo en Hand Tracking

### Corregido
- **Rendimiento del Hand Tracking**: Restaurado el uso de `requestAnimationFrame` para mantener 60 FPS y movimiento fluido de los vértices.
- **Problema de Espejo en Landmarks**: 
  - Desactivado `selfieMode` en MediaPipe que causaba inversión no deseada.
  - Agregada lógica para aplicar corrección de espejo solo cuando el video está en modo espejo.
  - Los landmarks ahora se muestran correctamente alineados con la posición real de las manos.

### Mejorado
- **Limpieza de Canvas**: Mejorada la limpieza de ambos canvas (mobile y desktop) al detener el tracking.
- **Reseteo de Errores**: El contador de errores se resetea correctamente al iniciar y detener el tracking.

## [1.1.1] - Corrección de Hand Tracking

### Corregido
- **Error de Module.arguments**: Solucionado el problema de compatibilidad con MediaPipe Hands que causaba errores al inicializar.
- **Versión Estable**: Ahora se usa una versión específica y estable de MediaPipe Hands (0.4.1646424915) para evitar problemas con versiones más recientes.
- **Rendimiento Mejorado**: 
  - Reducida la complejidad del modelo de 1 a 0 para mejor rendimiento.
  - Cambiado de 60 FPS a 20 FPS para reducir la carga del procesador.
  - Eliminado createImageBitmap que causaba problemas de compatibilidad.

### Mejorado
- **Manejo de Errores Robusto**: 
  - Agregado contador de errores para evitar alertas repetitivas.
  - Mejor verificación antes de activar hand tracking automáticamente.
  - Manejo de errores más granular con mensajes de advertencia en consola.
- **Inicialización más Confiable**:
  - Aumentado el delay de activación automática a 1 segundo.
  - Verificación de que el video stream esté listo antes de activar.
  - Agregado selfieMode para evitar problemas con el flip de la imagen.

## [1.2.0] - Preparación para GitHub y Seguridad (2024-01-20)

### 🔒 Seguridad Implementada
- **Credenciales Protegidas**: Movidas todas las credenciales sensibles a `config.local.php` (no incluido en Git)
- **Configuración Segura**: Creado sistema de configuración que carga credenciales de archivo local
- **Documentación Limpia**: Eliminadas credenciales de API y base de datos de todos los archivos públicos
- **Template de Configuración**: Creado `config.example.php` como plantilla para nuevos desarrolladores

### 📚 Documentación
- **README Completo**: Creado README.md con instrucciones detalladas de instalación y uso
- **Guía de Configuración**: Instrucciones paso a paso para obtener API Keys y configurar el proyecto
- **Estructura del Proyecto**: Documentación clara de la arquitectura y archivos
- **Solución de Problemas**: Guía de troubleshooting para errores comunes

### 🚀 Preparación para GitHub
- **Repositorio Git**: Inicializado con commits organizados y descriptivos
- **Archivos Protegidos**: .gitignore actualizado para proteger información sensible
- **Documentación de Contribución**: Instrucciones para colaboradores
- **Licencia**: Preparado para licencia MIT

### 🎯 Subida a GitHub Completada
- **Repositorio Privado**: `https://github.com/danfelbm/traductor-lsc`
- **1,214 archivos subidos** con éxito (9.61 MB)
- **Seguridad confirmada**: Credenciales no incluidas en Git
- **README.md completo** con instrucciones de instalación

## [1.1.0] - Mejoras de Experiencia de Usuario

### Agregado
- **Hand Tracking Automático**: Ahora el hand tracking se activa automáticamente al hacer clic en "Comenzar", mejorando la experiencia del usuario al no requerir activación manual.
- **Scroll Automático**: Al recibir una traducción, la página hace scroll automáticamente hasta el resultado para mejor visibilidad.
- **Feedback Visual Mejorado**: El overlay de traducción ahora tiene un fondo oscuro con efecto blur y texto descriptivo adicional.

### Cambiado
- **Flujo de Grabación Mejorado**: 
  - El feed de la webcam se oculta automáticamente al detener la grabación, evitando mostrar tanto la webcam como el video grabado simultáneamente.
  - El botón "Grabar Video" ahora dice "Volver a Grabar" cuando ya existe un video grabado.
- **Diseño de Botones**:
  - El botón "Traducir" ahora es de color verde (#4CAF50) para indicar la acción principal.
  - Mejor organización visual de los botones según el estado de la aplicación.

### Mejorado
- **Experiencia de Usuario General**:
  - Flujo más intuitivo desde el inicio hasta la traducción.
  - Estados visuales más claros durante todo el proceso.
  - Reducción de confusión al mostrar solo los elementos relevantes en cada etapa.

## [1.2.1] - Corrección de Error de Configuración (2024-01-20)

### 🛠 Corregido
- **Error "Error al procesar el video"**: 
  - Mejorado el manejo de errores para mostrar mensajes específicos
  - Agregada validación de credenciales antes de procesar
  - Detección automática de configuración faltante

### 🔍 Diagnóstico Agregado
- **Script de Verificación**: `verificar_config.php`
  - Verifica existencia de `config.local.php`
  - Valida todas las credenciales
  - Prueba la conexión con API de Gemini
  - Muestra últimas líneas del log de errores

### 📝 Mejoras en Manejo de Errores
- **Frontend (app.js)**:
  - Mensajes de error específicos según el problema
  - Detección de respuestas no-JSON del servidor
  - Logging en consola para debugging

- **Backend (procesar_video.php)**:
  - Validación de API Key antes de procesar
  - Supresión de avisos de configuración en contexto AJAX
  - Mensajes de error más descriptivos

## [1.2.2] - Corrección de Error JSON (2024-01-20)

### 🛠 Corregido
- **Error "Unexpected token '<'"**: 
  - Eliminadas constantes duplicadas en `config.local.php` que causaban warnings PHP
  - Implementado output buffering en `procesar_video.php` para evitar que warnings corrompan el JSON
  - Agregada lógica condicional en `config.php` para evitar redefinición de constantes

### 📝 Mejoras Técnicas
- **Output Buffering**: 
  - `ob_start()` al inicio para capturar cualquier output no deseado
  - `ob_clean()` después de cargar configuración
  - `ob_end_clean()` antes de enviar respuestas JSON
- **Prevención de Warnings**:
  - Verificación con `!defined()` antes de definir constantes
  - `display_errors` desactivado en procesar_video.php

### 📚 Archivos Actualizados
- `config.example.php`: Removidas constantes duplicadas con nota explicativa
- `procesar_video.php`: Implementado manejo robusto de output
- `includes/config.php`: Agregada verificación antes de definir constantes

## [1.3.0] - Actualización Completa y Subida a GitHub (2024-01-20)

### 🚀 Subido a GitHub
- **Commit**: `884b920` - feat: Mejoras de UX, corrección de errores y actualización de modelo Gemini
- **Repositorio**: https://github.com/danfelbm/traductor-lsc
- **Estado**: Completamente sincronizado y funcional

### 🔄 Actualización de Modelo
- **Gemini 2.5 Flash**: Actualizado de `gemini-1.5-flash` a `gemini-2.5-flash`
- **Nueva API Key**: Configurada y funcionando correctamente

### 📦 Resumen de Características Incluidas
- ✅ Hand tracking automático con MediaPipe
- ✅ Interfaz mejorada con Material Design 3
- ✅ Manejo robusto de errores
- ✅ Sistema de logging completo
- ✅ Configuración segura con archivos locales
- ✅ Documentación completa

## [1.3.1] - 2025-01-01

### Corregido
- **Compatibilidad con Gemini 2.5 Pro**: 
  - Implementado `systemInstruction` para forzar comportamiento correcto del modelo
  - Reducida temperatura a 0.3 para respuestas más consistentes
  - Eliminado límite de tokens para permitir respuestas completas
  - Añadido manejo robusto de diferentes formatos de respuesta
  - Implementada extracción de texto cuando el modelo devuelve formato de visión por computadora
  - Mejorado manejo de errores MAX_TOKENS con mensaje descriptivo al usuario
- **Logging mejorado**: Añadidos logs más detallados para debugging de respuestas de API
- **Configuración**: Añadida constante GEMINI_MODEL para identificar el modelo en uso

### Técnico
- Actualizado `procesar_video.php` con mejor procesamiento de respuestas de Gemini 2.5 Pro
- Implementada limpieza automática de respuestas JSON no deseadas
- Añadida validación para detectar y manejar múltiples formatos de respuesta de la API
- Eliminados límites de `maxOutputTokens` y `candidateCount` para evitar truncamiento de respuestas
- **Solución definitiva**: Uso de `systemInstruction` en lugar de prompt en `contents` para evitar que Gemini 2.5 Pro devuelva respuestas en formato de detección de objetos

--- 