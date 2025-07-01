# Roadmap: Traductor LSC a Español

Consigna todos los avances en el archivo changelog.md

## Tabla de Actividades

| Actividad | Estado | Tiempo Estimado |
|-----------|---------|-----------------|
| Configurar estructura base y dependencias CDN | Completada | 1 hora |
| Configurar y crear base de datos MySQL | Completada | 1 hora |
| Implementar grabación de video con MediaRecorder API | Completada | 2 horas |
| Integrar API Gemini para procesamiento de video | Completada | 3 horas |
| Desarrollar interfaz de usuario con Material Design | Completada | 2 horas |
| Implementar lógica de traducción y visualización | Completada | 2 horas |

## Estructura de Archivos

```
traductor_lsc/
├── index.php          # Punto de entrada principal
├── assets/
│   ├── css/
│   │   └── estilos.css
│   └── js/
│       └── app.js
└── includes/
    └── config.php     # Configuración de API y constantes
```

## Notas Técnicas

### Convenciones de Código
- Nombres de variables y funciones en español
- Comentarios en español
- Indentación: 4 espacios

### Configuración API Gemini
```php
define('GEMINI_API_KEY', 'TU_API_KEY_AQUI');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent');
```

### Dependencias CDN
```html
<!-- Vue.js 3 -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<!-- Material Design (Materialize CSS) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
```

## Consideraciones Técnicas

1. **Grabación de Video**
   - Usar MediaRecorder API para captura
   - Límite de 30 segundos
   - Formato MP4 para compatibilidad

2. **Procesamiento**
   - Convertir video a base64 para API
   - Manejar errores de conexión
   - Implementar timeout de 60 segundos

3. **Interfaz**
   - Diseño responsive y Material Design
   - Feedback visual durante procesamiento
   - Mensajes de error claros

4. **Sobre PHP**
    - PHP ya funciona en este directorio, pues estamos usando Herd en MacOS. Así que *NO* debes preocuparte por instalar servidor web o php.

5. **Base de Datos MySQL**
    - Host: localhost
    - Usuario: danielb
    - Contraseña: 159753456
    - Base de datos: traductor_lsc
    - Tablas necesarias:
      - `traducciones`: Almacena historial de traducciones
      - `usuarios`: Gestión de usuarios (opcional para futuras funcionalidades)