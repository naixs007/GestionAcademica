# Actualización de Base de Datos - Carga Académica

## ⚠️ IMPORTANTE: Leer antes de ejecutar

Esta migración reestructura completamente las tablas para el sistema de Carga Académica. **Requiere datos limpios o backup completo**.

## Cambios Principales

### 1. **Docentes**
- ❌ Eliminado: `cargaHoraria` (ahora es calculado dinámicamente)
- ✅ La carga horaria se calcula sumando las horas de los horarios asignados

### 2. **Materias**
- ✅ Añadido: `sigla` (ej: "INF121")
- ℹ️ Mantiene: `codigo`, `nombre`, `nivel`, `cargaHoraria`

### 3. **Grupos**
- ❌ Eliminado: `materia_id` (grupos son genéricos)
- ✅ Renombrado: `capacidad` → `cupo_maximo`
- ℹ️ Los grupos se relacionan con materias a través de `carga_academica`

### 4. **Aulas**
- ✅ Renombrado: `nombre` → `codigo`
- ℹ️ Mantiene: `capacidad`, `tipo` (Presencial/Laboratorio/Virtual)

### 5. **Horarios** (REESTRUCTURADO)
- ❌ Eliminado: `materia_id`, `modalidad`, `diaSemana` (JSON)
- ✅ Nuevo: `dia_semana` (ENUM individual)
- ✅ Renombrado: `horaInicio` → `hora_inicio`, `horaFin` → `hora_fin`
- ℹ️ Ahora son bloques de tiempo reutilizables (ej: "Lunes 08:00-10:00")

### 6. **Carga Académica** (REESTRUCTURADO COMPLETAMENTE)
Nueva estructura:
```
- docente_id (Quién enseña)
- materia_id (Qué enseña)
- grupo_id (A quién enseña)
- horario_id (Cuándo enseña)
- aula_id (Dónde enseña)
- gestion (Año, ej: 2025)
- periodo (Semestre: 1 o 2)
```

**Validaciones automáticas**:
- Un docente NO puede tener 2 materias en el mismo horario
- Un aula NO puede tener 2 materias en el mismo horario
- Un grupo NO puede tener 2 materias en el mismo horario

## 📋 Pasos para Ejecutar

### Opción 1: Base de Datos Limpia (Recomendado para desarrollo)

```bash
# 1. Limpiar base de datos
php artisan migrate:fresh

# 2. Ejecutar seeders
php artisan db:seed
```

### Opción 2: Datos Existentes (Producción)

```bash
# 1. BACKUP OBLIGATORIO
# Exportar base de datos completa antes de continuar

# 2. Ejecutar migración
php artisan migrate

# 3. IMPORTANTE: Revisar datos después de la migración
# - Verificar que siglas de materias estén correctas
# - Verificar que grupos no tengan materia_id
# - Verificar que aulas usen 'codigo' en lugar de 'nombre'
```

## 🔄 Cambios en el Código

### Modelos Actualizados
- ✅ `Docente.php` - `cargaHoraria` es ahora un accessor calculado
- ✅ `Materia.php` - Añadido campo `sigla`
- ✅ `Grupo.php` - Usa `cupo_maximo` en lugar de `capacidad`
- ✅ `Aula.php` - Usa `codigo` en lugar de `nombre`
- ✅ `Horario.php` - Estructura simple de bloques de tiempo
- ✅ `CargaAcademica.php` - Relaciones completas con validaciones

### Controladores Afectados
- ⚠️ `DocenteController` - Eliminar referencias a `cargaHoraria` en formularios
- ⚠️ `GrupoController` - Usar `cupo_maximo` en lugar de `capacidad`
- ⚠️ `AulaController` - Usar `codigo` en lugar de `nombre`
- ⚠️ `HorarioController` - Actualizar a nueva estructura (día individual)
- ⚠️ `CargaAcademicaController` - Rehacer completamente

### Vistas Afectadas
- ⚠️ Todas las vistas de docentes (eliminar campo cargaHoraria)
- ⚠️ Todas las vistas de grupos (capacidad → cupo_maximo)
- ⚠️ Todas las vistas de aulas (nombre → codigo)
- ⚠️ Todas las vistas de horarios (reestructurar completamente)
- ⚠️ Todas las vistas de carga académica (rehacer desde cero)

## 📊 Ejemplo de Uso

### Antes (Estructura Antigua)
```php
// Docente con cargaHoraria fija
$docente->cargaHoraria = 20; // ❌ Estático

// Horario con múltiples días
$horario->diaSemana = ['Lunes', 'Miércoles']; // ❌ JSON

// Carga académica simple
$carga = [
    'docente_id' => 1,
    'materia_id' => 1,
    'grupo_id' => 1
];
```

### Después (Estructura Nueva)
```php
// Docente con cargaHoraria calculada
$cargaHoraria = $docente->cargaHoraria; // ✅ Calculado dinámicamente

// Horarios como bloques individuales
$horario1 = Horario::create([
    'dia_semana' => 'Lunes',
    'hora_inicio' => '08:00',
    'hora_fin' => '10:00'
]);

$horario2 = Horario::create([
    'dia_semana' => 'Miércoles',
    'hora_inicio' => '08:00',
    'hora_fin' => '10:00'
]);

// Carga académica completa
$carga1 = CargaAcademica::create([
    'docente_id' => 1,
    'materia_id' => 1,
    'grupo_id' => 1,
    'horario_id' => $horario1->id,
    'aula_id' => 1,
    'gestion' => 2025,
    'periodo' => '1'
]);

$carga2 = CargaAcademica::create([
    'docente_id' => 1,
    'materia_id' => 1,
    'grupo_id' => 1,
    'horario_id' => $horario2->id,
    'aula_id' => 1,
    'gestion' => 2025,
    'periodo' => '1'
]);
```

## ✅ Verificación Post-Migración

Ejecuta estas consultas para verificar:

```sql
-- 1. Verificar que docentes no tengan cargaHoraria
DESC docentes;

-- 2. Verificar que materias tengan sigla
SELECT id, nombre, codigo, sigla FROM materias LIMIT 5;

-- 3. Verificar que grupos usen cupo_maximo
DESC grupos;

-- 4. Verificar que aulas usen codigo
SELECT id, codigo, capacidad, tipo FROM aulas LIMIT 5;

-- 5. Verificar estructura de horarios
SELECT * FROM horarios LIMIT 5;

-- 6. Verificar estructura de carga_academica
DESC carga_academica;
```

## 🆘 Soporte

Si encuentras errores durante la migración:
1. Verifica que no tengas datos críticos sin backup
2. Revisa los logs de Laravel: `storage/logs/laravel.log`
3. Si falla, restaura el backup y reporta el error

## 📝 Notas Adicionales

- Los horarios son ahora **catálogos reutilizables**
- La modalidad (presencial/virtual) se deduce del tipo de aula
- La carga horaria del docente es un **reporte**, no un dato almacenado
- El sistema ahora puede validar conflictos de horarios automáticamente
