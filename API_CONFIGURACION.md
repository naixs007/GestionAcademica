# API de Configuración del Sistema (CU23)

## Descripción
Esta API permite a los administradores gestionar los parámetros generales del sistema de gestión académica.

## Autenticación
Todas las rutas requieren:
- Autenticación de usuario (`auth` middleware)
- Rol de **Administrador** (`role:Administrador` middleware)

## Endpoints

### 1. Obtener Configuración Actual

**GET** `/api/configuraciones`

Obtiene la configuración actual del sistema (registro singleton).

#### Response Exitoso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nombre_institucion": "Sistema de Gestión Académica",
        "logo_institucional_path": null,
        "periodo_academico_default_id": null,
        "tolerancia_asistencia_minutos": 10,
        "requerir_motivo_ausencia": false,
        "expiracion_contrasena_dias": 90,
        "notificaciones_email_remitente": null,
        "created_at": "2025-11-13T15:26:15.000000Z",
        "updated_at": "2025-11-13T15:26:15.000000Z"
    },
    "message": "Configuración obtenida exitosamente"
}
```

#### Response Error (500)
```json
{
    "success": false,
    "message": "Error al obtener la configuración",
    "error": "Mensaje de error detallado"
}
```

---

### 2. Actualizar Configuración

**POST** `/api/configuraciones`

Actualiza los parámetros de configuración del sistema.

#### Request Body
```json
{
    "nombre_institucion": "Universidad Autónoma Gabriel René Moreno",
    "logo_institucional_path": "/storage/logos/logo.png",
    "periodo_academico_default_id": 1,
    "tolerancia_asistencia_minutos": 15,
    "requerir_motivo_ausencia": true,
    "expiracion_contrasena_dias": 120,
    "notificaciones_email_remitente": "sistema@universidad.edu"
}
```

#### Validaciones

| Campo | Tipo | Reglas | Descripción |
|-------|------|--------|-------------|
| `nombre_institucion` | string | nullable, max:255 | Nombre de la institución |
| `logo_institucional_path` | string | nullable, max:255 | Ruta del archivo del logo |
| `periodo_academico_default_id` | integer | nullable, exists:periodos_academicos,id | ID del período académico por defecto |
| `tolerancia_asistencia_minutos` | integer | nullable, min:0, max:60 | Minutos de tolerancia para asistencia |
| `requerir_motivo_ausencia` | boolean | nullable | Si se requiere justificar ausencias |
| `expiracion_contrasena_dias` | integer | nullable, min:30, max:365 | Días para expiración de contraseña |
| `notificaciones_email_remitente` | string | nullable, email, max:255 | Email remitente de notificaciones |

#### Response Exitoso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nombre_institucion": "Universidad Autónoma Gabriel René Moreno",
        "logo_institucional_path": "/storage/logos/logo.png",
        "periodo_academico_default_id": 1,
        "tolerancia_asistencia_minutos": 15,
        "requerir_motivo_ausencia": true,
        "expiracion_contrasena_dias": 120,
        "notificaciones_email_remitente": "sistema@universidad.edu",
        "created_at": "2025-11-13T15:26:15.000000Z",
        "updated_at": "2025-11-13T15:30:45.000000Z"
    },
    "message": "Configuración actualizada exitosamente"
}
```

#### Response Error de Validación (422)
```json
{
    "success": false,
    "message": "Error de validación",
    "errors": {
        "tolerancia_asistencia_minutos": [
            "El campo tolerancia asistencia minutos debe ser un número entre 0 y 60."
        ],
        "notificaciones_email_remitente": [
            "El campo notificaciones email remitente debe ser un correo electrónico válido."
        ]
    }
}
```

#### Response Error del Servidor (500)
```json
{
    "success": false,
    "message": "Error al actualizar la configuración",
    "error": "Mensaje de error detallado"
}
```

---

## Bitácora (CU24)

Cada actualización de configuración registra automáticamente una entrada en la tabla `bitacoras` con:

- **user_id**: ID del administrador que realizó el cambio
- **usuario**: Nombre del usuario
- **descripcion**: "El usuario [ID] actualizó los parámetros generales del sistema"
- **metodo**: POST
- **ruta**: /api/configuraciones
- **direccion_ip**: IP del cliente
- **navegador**: User-Agent del navegador
- **fecha_hora**: Timestamp de la operación

---

## Modelo Singleton

La tabla `configuraciones` está diseñada como **singleton** (una única fila):
- El método estático `Configuracion::current()` siempre retorna la configuración activa
- Si no existe configuración, se crea automáticamente con valores por defecto
- No se permite crear múltiples registros de configuración

---

## Pruebas con Postman/Insomnia

### Obtener Configuración
```
GET http://localhost/api/configuraciones
Headers:
  Accept: application/json
  Cookie: laravel_session=YOUR_SESSION_COOKIE
```

### Actualizar Configuración
```
POST http://localhost/api/configuraciones
Headers:
  Accept: application/json
  Content-Type: application/json
  Cookie: laravel_session=YOUR_SESSION_COOKIE
Body:
{
    "nombre_institucion": "UAGRM",
    "tolerancia_asistencia_minutos": 15
}
```

---

## Notas Técnicas

1. **Período Académico**: La foreign key `periodo_academico_default_id` está preparada para cuando se implemente la tabla `periodos_academicos`.

2. **Cache**: El controlador mantiene métodos legacy para manejo de configuración con cache key-value. Los nuevos endpoints API usan el modelo `Configuracion` directamente.

3. **Transacciones**: La actualización usa transacciones de base de datos para garantizar consistencia entre la actualización de configuración y el registro en bitácora.

4. **Valores por Defecto**:
   - `nombre_institucion`: "Sistema de Gestión Académica"
   - `tolerancia_asistencia_minutos`: 10
   - `requerir_motivo_ausencia`: false
   - `expiracion_contrasena_dias`: 90

---

## Ejemplo de Uso en Frontend (JavaScript)

```javascript
// Obtener configuración
async function getConfiguracion() {
    const response = await fetch('/api/configuraciones', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'include'
    });
    
    const data = await response.json();
    console.log(data.data);
}

// Actualizar configuración
async function updateConfiguracion(config) {
    const response = await fetch('/api/configuraciones', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'include',
        body: JSON.stringify(config)
    });
    
    const data = await response.json();
    if (data.success) {
        console.log('Configuración actualizada:', data.data);
    } else {
        console.error('Error:', data.message);
    }
}
```

---

## Estructura de Base de Datos

```sql
CREATE TABLE configuraciones (
    id BIGSERIAL PRIMARY KEY,
    nombre_institucion VARCHAR(255),
    logo_institucional_path VARCHAR(255),
    periodo_academico_default_id BIGINT,
    tolerancia_asistencia_minutos INTEGER DEFAULT 10,
    requerir_motivo_ausencia BOOLEAN DEFAULT FALSE,
    expiracion_contrasena_dias INTEGER DEFAULT 90,
    notificaciones_email_remitente VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
