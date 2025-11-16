# Sistema de Registro de Asistencia Docente con Ventana Activa

## 📋 Resumen

Se ha implementado un sistema de registro de asistencia **para docentes** con ventana temporal activa de 15 minutos antes y 15 minutos después de la hora de inicio de clase.

⚠️ **IMPORTANTE**: Este sistema registra la asistencia de **DOCENTES únicamente**, NO de estudiantes.

## 🎯 Características Implementadas

### 1. **Controlador API** (`App\Http\Controllers\Api\AsistenciaController.php`)

#### Método `getMarcadoData($horarioId)`
- **Entrada**: ID del horario
- **Proceso**:
  - Busca la carga académica asociada al horario
  - Calcula la ventana activa (15 min antes / 15 min después)
  - Obtiene datos del docente, materia, grupo, aula
  - Calcula segundos restantes hasta cierre
- **Salida**: JSON con datos de carga y estado de ventana

#### Método `registrarAsistenciaDocente(Request $request)`
- **Validaciones**:
  - `horario_id`: requerido, existe en tabla horarios
  - `carga_academica_id`: requerido, existe en tabla carga_academica
  - `fecha`: requerida, formato fecha válido
  - `estado`: debe ser "Presente", "Ausente", "Justificado" o "Tardanza"
  - `observaciones`: opcional, máximo 500 caracteres
- **Verificaciones**:
  - Ventana activa (rechaza si está cerrada)
  - No duplicar registros del mismo día
- **Proceso**:
  - Inicia transacción DB
  - Crea registro de asistencia del docente
  - Guarda hora de llegada automáticamente
- **Salida**: JSON con éxito/error y datos del registro

## 🚀 Rutas Creadas

### Rutas API (`routes/api.php`)
```php
GET  /api/asistencia/marcado/{horarioId}
POST /api/asistencia/registrar-docente
```

### Permisos de Acceso
- **Middleware**: `auth` (usuario autenticado)
- Disponible para todos los usuarios autenticados
- Las validaciones de rol se manejan en el frontend

## 🔐 Control de Acceso por Roles

### Admin / Super-Admin
- ✅ Ver todas las asistencias
- ✅ Registrar asistencia de cualquier docente
- ✅ Editar asistencias
- ✅ Eliminar asistencias
- ✅ Acceso a bitácora
- ✅ Configurar parámetros del sistema

### Decano
- ✅ Ver asistencias de su facultad
- ✅ Registrar asistencia de docentes
- ✅ Editar asistencias
- ✅ Eliminar asistencias
- ✅ Acceso a reportes

### Docente
- ✅ Ver su propia asistencia
- ✅ No puede registrar asistencia manualmente
- ❌ No puede editar/eliminar registros

## 📊 Flujo de Uso

### Escenario 1: Registro Manual por Admin/Decano
1. Admin/Decano accede a `/admin/asistencia/create`
2. Selecciona docente y carga académica
3. Ingresa fecha y estado
4. Sistema valida y guarda registro

### Escenario 2: Marcado mediante API con Ventana Activa
1. Sistema frontend obtiene datos: `GET /api/asistencia/marcado/{horarioId}`
2. Verifica si ventana está activa
3. Usuario marca asistencia
4. Envía: `POST /api/asistencia/registrar-docente`
5. Sistema valida ventana activa en backend
6. Guarda registro con hora exacta

### Escenario 3: Ventana Cerrada
1. Usuario intenta registrar fuera de ventana
2. API retorna error 403 (Forbidden)
3. Frontend muestra mensaje de error

### Escenario 4: Registro Duplicado
1. Usuario intenta registrar asistencia ya existente
2. API retorna error 409 (Conflict)
3. Frontend muestra mensaje de error

## 🔧 Cálculo de Ventana Activa

```php
$horaInicio = Carbon::parse($horario->hora_inicio);
$horaApertura = $horaInicio->copy()->subMinutes(15);  // -15 min
$horaCierre = $horaInicio->copy()->addMinutes(15);    // +15 min
$esVentanaActiva = $ahora->between($horaApertura, $horaCierre);
```

**Ejemplo**:
- Clase inicia: 08:00
- Ventana abre: 07:45
- Ventana cierra: 08:15
- Duración total: 30 minutos

## 🔐 Seguridad

- Middleware `auth` en todas las rutas
- Middleware `role:admin,super-admin,decano` en rutas de creación/edición/eliminación
- Token CSRF en formularios
- Validación de entrada en API
- Transacciones DB para integridad
- Verificación de ventana activa en backend

## 📦 Archivos Modificados

### Modificados:
- `app/Http/Controllers/Api/AsistenciaController.php` (eliminadas referencias a estudiantes)
- `app/Http/Controllers/AsistenciaController.php` (eliminado método marcar)
- `routes/api.php` (actualizada ruta de registro)
- `routes/web.php` (eliminada ruta marcar)
- `resources/views/admin/asistencia/create.blade.php` (actualizado título)
- `resources/views/layouts/partials/admin/sidebar.blade.php` (permisos por rol)

### Eliminados:
- `resources/views/admin/asistencia/marcar.blade.php` (ya no se necesita)

## ✅ Checklist de Implementación

- [x] Controlador API creado y corregido
- [x] Método `getMarcadoData()` sin estudiantes
- [x] Método `registrarAsistenciaDocente()` para docentes únicamente
- [x] Cálculo de ventana activa (15 min antes/después)
- [x] Rutas API actualizadas
- [x] Eliminada vista de marcado interactivo
- [x] Permisos por rol en sidebar
- [x] Admin/Super-Admin: acceso completo
- [x] Decano: ver y registrar asistencias
- [x] Docente: solo ver su propia asistencia
- [x] Documentación actualizada

## 🎯 Uso del Sistema

### Para Admin/Super-Admin:
1. Acceder a **Control de Asistencia Docente** en el sidebar
2. Click en **Registrar Asistencia**
3. Seleccionar docente y carga académica
4. Ingresar fecha y estado
5. Agregar observaciones (opcional)
6. Guardar registro

### Para Decano:
1. Mismo proceso que Admin
2. Limitado a docentes de su facultad (según configuración)

### Para Docente:
1. Solo puede ver su historial de asistencias
2. No puede registrar asistencia manualmente

---

**Desarrollado por**: GitHub Copilot  
**Fecha**: 15 de Noviembre de 2025  
**Versión**: 2.0.0 (Sistema solo para docentes)
