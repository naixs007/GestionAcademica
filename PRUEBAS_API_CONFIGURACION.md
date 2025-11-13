# Script de Prueba - API de Configuración

## Requisitos Previos
1. Usuario autenticado como Administrador
2. Cookie de sesión válida
3. Token CSRF (si es necesario)

## Prueba 1: Obtener Configuración Actual

### Usando curl
```bash
curl -X GET http://localhost/api/configuraciones \
  -H "Accept: application/json" \
  -H "Cookie: laravel_session=YOUR_SESSION_COOKIE_HERE"
```

### Respuesta Esperada
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
        "created_at": "2025-11-13T15:29:21.000000Z",
        "updated_at": "2025-11-13T15:29:21.000000Z"
    },
    "message": "Configuración obtenida exitosamente"
}
```

---

## Prueba 2: Actualizar Configuración

### Usando curl
```bash
curl -X POST http://localhost/api/configuraciones \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Cookie: laravel_session=YOUR_SESSION_COOKIE_HERE" \
  -d '{
    "nombre_institucion": "Universidad Autónoma Gabriel René Moreno",
    "tolerancia_asistencia_minutos": 15,
    "requerir_motivo_ausencia": true,
    "expiracion_contrasena_dias": 120,
    "notificaciones_email_remitente": "sistema@uagrm.edu.bo"
  }'
```

### Respuesta Esperada
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nombre_institucion": "Universidad Autónoma Gabriel René Moreno",
        "logo_institucional_path": null,
        "periodo_academico_default_id": null,
        "tolerancia_asistencia_minutos": 15,
        "requerir_motivo_ausencia": true,
        "expiracion_contrasena_dias": 120,
        "notificaciones_email_remitente": "sistema@uagrm.edu.bo",
        "created_at": "2025-11-13T15:29:21.000000Z",
        "updated_at": "2025-11-13T15:35:00.000000Z"
    },
    "message": "Configuración actualizada exitosamente"
}
```

---

## Prueba 3: Validación - Email Inválido

### Request
```bash
curl -X POST http://localhost/api/configuraciones \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Cookie: laravel_session=YOUR_SESSION_COOKIE_HERE" \
  -d '{
    "notificaciones_email_remitente": "email_invalido"
  }'
```

### Respuesta Esperada (422)
```json
{
    "success": false,
    "message": "Error de validación",
    "errors": {
        "notificaciones_email_remitente": [
            "El campo notificaciones email remitente debe ser un correo electrónico válido."
        ]
    }
}
```

---

## Prueba 4: Validación - Tolerancia Fuera de Rango

### Request
```bash
curl -X POST http://localhost/api/configuraciones \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Cookie: laravel_session=YOUR_SESSION_COOKIE_HERE" \
  -d '{
    "tolerancia_asistencia_minutos": 100
  }'
```

### Respuesta Esperada (422)
```json
{
    "success": false,
    "message": "Error de validación",
    "errors": {
        "tolerancia_asistencia_minutos": [
            "El campo tolerancia asistencia minutos debe ser entre 0 y 60."
        ]
    }
}
```

---

## Prueba 5: Validación - Expiración de Contraseña Mínima

### Request
```bash
curl -X POST http://localhost/api/configuraciones \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Cookie: laravel_session=YOUR_SESSION_COOKIE_HERE" \
  -d '{
    "expiracion_contrasena_dias": 10
  }'
```

### Respuesta Esperada (422)
```json
{
    "success": false,
    "message": "Error de validación",
    "errors": {
        "expiracion_contrasena_dias": [
            "El campo expiracion contrasena dias debe ser al menos 30."
        ]
    }
}
```

---

## Verificar Registro en Bitácora

Después de actualizar la configuración, verifica que se haya registrado en la bitácora:

```bash
php artisan tinker --execute="
\$bitacora = \App\Models\Bitacora::latest()->first();
echo json_encode(\$bitacora->toArray(), JSON_PRETTY_PRINT);
"
```

### Resultado Esperado
```json
{
    "id": 123,
    "user_id": 1,
    "usuario": "Admin Usuario",
    "descripcion": "El usuario 1 actualizó los parámetros generales del sistema",
    "metodo": "POST",
    "ruta": "/api/configuraciones",
    "direccion_ip": "127.0.0.1",
    "navegador": "Mozilla/5.0...",
    "browser_info": "Mozilla/5.0...",
    "fecha_hora": "2025-11-13 15:35:00",
    "created_at": "2025-11-13T15:35:00.000000Z",
    "updated_at": "2025-11-13T15:35:00.000000Z"
}
```

---

## Pruebas desde el Navegador (Console)

### Obtener configuración
```javascript
fetch('/api/configuraciones', {
    method: 'GET',
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    credentials: 'include'
})
.then(response => response.json())
.then(data => console.log(data));
```

### Actualizar configuración
```javascript
fetch('/api/configuraciones', {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    credentials: 'include',
    body: JSON.stringify({
        nombre_institucion: 'UAGRM',
        tolerancia_asistencia_minutos: 20
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## Notas Importantes

1. **Autenticación**: Debes estar autenticado como Administrador
2. **CSRF Token**: Para requests POST desde el navegador, incluye el token CSRF
3. **Session Cookie**: Para requests externos (curl, Postman), necesitas la cookie de sesión válida
4. **Singleton**: Solo existe un registro de configuración en la base de datos
5. **Bitácora**: Cada actualización genera un registro automático en la tabla `bitacoras`

---

## Comandos Útiles

### Ver configuración actual
```bash
php artisan tinker --execute="echo json_encode(\App\Models\Configuracion::current()->toArray(), JSON_PRETTY_PRINT);"
```

### Ver última entrada de bitácora
```bash
php artisan tinker --execute="echo json_encode(\App\Models\Bitacora::latest()->first()->toArray(), JSON_PRETTY_PRINT);"
```

### Resetear configuración a valores por defecto
```bash
php artisan tinker --execute="
\$config = \App\Models\Configuracion::first();
\$config->update([
    'nombre_institucion' => 'Sistema de Gestión Académica',
    'tolerancia_asistencia_minutos' => 10,
    'requerir_motivo_ausencia' => false,
    'expiracion_contrasena_dias' => 90
]);
echo 'Configuración reseteada';
"
```
