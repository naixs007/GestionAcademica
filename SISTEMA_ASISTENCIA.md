# Sistema de Registro de Asistencia

## Resumen
Se ha implementado un sistema completo de registro de asistencia para docentes, integrando las relaciones con materias, grupos y horarios a través del modelo CargaAcademica.

## Cambios Realizados

### 1. Base de Datos

#### Migración: `add_materia_grupo_to_asistencias_table`
Se agregaron las siguientes columnas a la tabla `asistencias`:
- `materia_id` (foreignId → materias)
- `grupo_id` (foreignId → grupos)
- `hora_llegada` (time, nullable)
- Se modificó `estado` a string(20) para soportar valores descriptivos

### 2. Modelo (Asistencia.php)

#### Relaciones Agregadas
```php
public function materia() // belongsTo Materia
public function grupo()   // belongsTo Grupo
```

#### Campos Fillable
- materia_id
- grupo_id
- hora_llegada
- observaciones

#### Scopes Agregados
```php
scopeFecha($query, $fecha)
scopeDocente($query, $docenteId)
scopeEstado($query, $estado)
```

#### Accessor
```php
getEstadoBadgeColorAttribute() // Retorna color de badge según estado
```

#### Estados Soportados
- **Presente**: Estado normal de asistencia (verde)
- **Ausente**: No asistió a la clase (rojo)
- **Tardanza**: Llegó tarde (amarillo/warning)
- **Justificado**: Falta justificada (azul/info)

### 3. Controlador (AsistenciaController.php)

#### Método create()
- Obtiene todas las CargaAcademica agrupadas por docente
- Envía datos a la vista para selector dinámico

#### Método store()
- Recibe `carga_academica_id` en lugar de IDs individuales
- Extrae automáticamente: docente_id, materia_id, grupo_id, horario_id
- Valida duplicados: docente + materia + grupo + fecha
- Estados permitidos: Presente, Ausente, Justificado, Tardanza

#### Método index()
- Filtros disponibles:
  - fecha_desde / fecha_hasta
  - docente_id
  - estado
- Paginación de 15 registros
- Carga eager loading de relaciones

#### Método edit() y update()
- Solo permite editar: fecha, estado, hora_llegada, observaciones
- Mantiene las relaciones originales (docente, materia, grupo, horario)

#### Método getMateriasByDocente($docenteId)
- Endpoint AJAX para cargar asignaciones
- Retorna CargaAcademica con todas las relaciones
- Formato JSON para consumo en frontend

### 4. Rutas (web.php)

```php
// Ruta AJAX para obtener asignaciones por docente
Route::get('asistencia/get-materias/{docente}', [AsistenciaController::class, 'getMateriasByDocente'])
    ->name('asistencia.get-materias');
```

### 5. Vistas

#### create.blade.php
**Características:**
- Selector de docente (carga inicial)
- Selector de asignación (AJAX dinámico)
  - Muestra: Materia | Grupo | Horario | Aula
- Campos del formulario:
  - Fecha (máx: hoy)
  - Estado (dropdown con 4 opciones)
  - Hora de llegada (solo si es Tardanza)
  - Observaciones
- JavaScript integrado:
  - AJAX para cargar asignaciones al seleccionar docente
  - Show/hide de hora_llegada según estado
- Validaciones del lado del cliente
- Diseño responsive con Bootstrap 5

#### show.blade.php
**Características:**
- Vista dividida en cards:
  - Información General (fecha, estado, hora llegada, observaciones)
  - Docente y Asignación (docente, materia, grupo, horario)
  - Metadatos (created_at, updated_at)
- Badge colorido para estado
- Iconos de Bootstrap Icons
- Botones de acción: Editar, Eliminar, Volver
- Diseño responsive en 2 columnas (lg)

#### edit.blade.php
**Características:**
- Card de información de asignación (solo lectura)
- Formulario de edición:
  - Fecha (editable)
  - Estado (dropdown)
  - Hora de llegada (condicional)
  - Observaciones
- JavaScript para show/hide de hora_llegada
- No permite modificar docente/materia/grupo/horario
- Diseño responsive con Bootstrap 5

#### index.blade.php
(Ya estaba actualizado en trabajo previo)
- Listado con paginación
- Filtros por fecha, docente y estado
- Tabla responsive
- Badges de estado coloreados

## Flujo de Uso

### Registrar Nueva Asistencia
1. Ir a "Registrar Asistencia"
2. Seleccionar docente
3. Esperar carga AJAX de asignaciones
4. Seleccionar asignación específica (materia + grupo + horario)
5. Ingresar fecha
6. Seleccionar estado
7. Si es tardanza: ingresar hora de llegada
8. Opcional: agregar observaciones
9. Guardar

### Ver Asistencia
1. Desde listado, click en "Ver"
2. Visualiza toda la información
3. Opciones: Editar o Eliminar

### Editar Asistencia
1. Desde vista detalle o listado
2. Modificar fecha, estado, hora u observaciones
3. No se puede cambiar docente/materia/grupo/horario
4. Guardar cambios

### Filtrar Asistencias
1. En listado, usar filtros:
   - Rango de fechas
   - Docente específico
   - Estado específico
2. Ver resultados filtrados

## Validaciones Implementadas

### Backend (Controller)
- Fecha: requerida, formato date, no puede ser futura
- Estado: requerido, in:Presente,Ausente,Justificado,Tardanza
- Hora llegada: formato time
- Observaciones: opcional, string
- carga_academica_id: requerido, exists en tabla
- Duplicados: unique por docente+materia+grupo+fecha

### Frontend (JavaScript)
- Docente debe estar seleccionado para habilitar asignaciones
- Estado debe ser seleccionado
- Hora llegada solo requerida si estado = Tardanza
- Fecha máxima = hoy

## Integración con CargaAcademica

El sistema utiliza `CargaAcademica` como fuente única de verdad, lo que garantiza:
- Solo se pueden registrar asistencias para asignaciones válidas
- Consistencia de datos (no combinaciones inválidas)
- Trazabilidad completa: docente → materia → grupo → horario → aula
- Facilidad de selección para el usuario

## Tecnologías Utilizadas

- Laravel 10+
- Bootstrap 5.3.3
- Bootstrap Icons
- Fetch API (AJAX)
- Carbon (fechas)
- Blade Components

## Archivos Modificados

1. `database/migrations/2025_11_14_095112_add_materia_grupo_to_asistencias_table.php`
2. `app/Models/Asistencia.php`
3. `app/Http/Controllers/AsistenciaController.php`
4. `routes/web.php`
5. `resources/views/admin/asistencia/create.blade.php`
6. `resources/views/admin/asistencia/show.blade.php`
7. `resources/views/admin/asistencia/edit.blade.php`

## Comandos Ejecutados

```bash
# Crear migración
php artisan make:migration add_materia_grupo_to_asistencias_table --table=asistencias

# Ejecutar migración
php artisan migrate

# Compilar assets
npm run build
```

## Próximos Pasos Sugeridos

1. Implementar reportes de asistencia
2. Agregar vista de asistencia propia para docentes
3. Exportación a PDF/Excel
4. Estadísticas de asistencia por docente/materia
5. Notificaciones automáticas por faltas
6. Calendario visual de asistencias

## Notas Importantes

- Los estados son case-sensitive: usar exactamente "Presente", "Ausente", "Tardanza", "Justificado"
- La hora_llegada debe ser guardada en formato TIME (HH:MM:SS) en la BD
- El campo observaciones es opcional pero recomendable para justificaciones
- Los filtros en index() usan scopes para mejor mantenibilidad
- El AJAX endpoint retorna JSON con todas las relaciones cargadas
