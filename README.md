# Traductor LSC a Español

Un traductor de Lengua de Señas Colombiana (LSC) a texto en español usando inteligencia artificial. La aplicación permite grabar videos de señas y traducirlos automáticamente mediante Google Gemini AI.

## 🌟 Características

- ✅ **Interfaz moderna** con Material Design v3 (Material You) 
- ✅ **Responsive Design** - Layout desktop tipo Twitch y móvil optimizado
- ✅ **Grabación de video** con MediaRecorder API (30 segundos)
- ✅ **Hand Tracking** en tiempo real con MediaPipe Hands
- ✅ **Traducción IA** usando Google Gemini AI
- ✅ **Base de datos** MySQL para historial de traducciones
- ✅ **Controles de cámara** (frontal/trasera, modo espejo)
- ✅ **Múltiples pantallas**: grabación, historial, configuración

## 🛠️ Tecnologías

- **Frontend**: Vue.js 3, Material Web Components, CSS3
- **Backend**: PHP 8+, MySQL
- **IA**: Google Gemini AI API
- **Computer Vision**: MediaPipe Hands
- **APIs**: MediaRecorder, getUserMedia

## 📋 Requisitos

- PHP 8.0+
- MySQL 5.7+
- Servidor web (Apache/Nginx)
- Cámara web
- API Key de Google Gemini

## ⚙️ Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/traductor-lsc.git
cd traductor-lsc
```

### 2. Configurar credenciales
```bash
# Copiar archivo de configuración
cp includes/config.example.php includes/config.local.php

# Editar config.local.php con tus credenciales:
nano includes/config.local.php
```

Completa los siguientes datos en `config.local.php`:
- `GEMINI_API_KEY`: Tu API Key de Google AI Studio
- `DB_USER`, `DB_PASS`, `DB_NAME`: Credenciales de tu base de datos MySQL

### 3. Crear base de datos
```bash
# Ejecutar script de inicialización
php init_db.php
```

### 4. Configurar servidor web
Apunta tu servidor web a la carpeta del proyecto y asegúrate de que PHP esté habilitado.

## 🔑 Obtener API Key de Google Gemini

1. Ve a [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Inicia sesión con tu cuenta de Google
3. Crea una nueva API Key
4. Copia la clave y pégala en `config.local.php`

## 🚀 Uso

1. Abre la aplicación en tu navegador
2. Haz clic en "Comenzar" para ir a la grabación
3. Permite acceso a la cámara cuando se solicite
4. Graba un video de máximo 30 segundos en LSC
5. Haz clic en "Traducir" para obtener el texto en español

## 📁 Estructura del Proyecto

```
traductor_lsc/
├── assets/              # CSS y JavaScript frontend
├── includes/            # Configuración PHP
├── uploads/videos/      # Videos temporales
├── materialv3/          # Componentes Material Design
├── index.php           # Aplicación principal
├── procesar_video.php  # Procesamiento de video
└── README.md          # Este archivo
```

## 🔒 Seguridad

- Las credenciales están en `config.local.php` (no incluido en Git)
- Los videos se almacenan temporalmente y se eliminan después del procesamiento
- Validación de tamaño de archivo y duración de video

## 🐛 Solución de Problemas

### Error: "API Key no configurada"
- Verifica que `config.local.php` existe y tiene tu API Key válida

### Error: "No se puede conectar a la base de datos"
- Verifica las credenciales de MySQL en `config.local.php`
- Asegúrate de que el servidor MySQL esté corriendo

### Error: "No se detecta la cámara"
- Permite acceso a la cámara en tu navegador
- Usa HTTPS en producción (requerido para getUserMedia)

## 📊 Base de Datos

La aplicación crea automáticamente las siguientes tablas:
- `traducciones`: Almacena el historial de traducciones
- `usuarios`: Para futuras funcionalidades de usuario

## 🤝 Contribuir

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-caracteristica`)
3. Commit tus cambios (`git commit -am 'Agregar nueva característica'`)
4. Push a la rama (`git push origin feature/nueva-caracteristica`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

## 🔗 Enlaces

- [Documentación Google Gemini](https://ai.google.dev/docs)
- [Material Design v3](https://m3.material.io/)
- [MediaPipe Hands](https://developers.google.com/mediapipe/solutions/vision/hand_landmarker)

---

Desarrollado con ❤️ para la comunidad LSC 