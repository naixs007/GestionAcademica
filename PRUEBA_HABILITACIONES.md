# 🧪 Guía de Prueba - Sistema de Habilitaciones de Asistencia

## ✅ Configuración Actual (MODO PRUEBA)

El sistema está configurado en **MODO PRUEBA** para que puedas probarlo cualquier día de la semana:

### Modificaciones Temporales:

1. **Ventana de tiempo DESHABILITADA**: El docente puede marcar asistencia en cualquier momento del día
2. **Filtro de día de semana DESHABILITADO**: Las clases habilitadas se muestran sin importar el día original del horario

---

## 📋 Pasos para Probar el Sistema

### PASO 1: Acceder como Administrador

1. Inicia sesión con una cuenta de **Administrador** o **Super-Admin**
2. Ve al menú lateral: **Control de Asistencia Docente** → **Habilitar Marcado**
3. URL directa: `http://localhost/gestionAcademica/public/admin/habilitaciones`

### PASO 2: Crear una Habilitación

1. Haz clic en **"Nueva Habilitación"**
2. Completa el formulario:
   - **Docente**: Selecciona cualquier docente que tenga cargas académicas asignadas
   - **Materia/Grupo/Horario**: Después de seleccionar el docente, se cargarán automáticamente sus clases
   - **Fecha**: Deja la fecha de hoy (2025-11-16) 
   - **Observaciones** (opcional): "Prueba del sistema de habilitaciones"
3. Haz clic en **"Crear Habilitación"**
4. Deberías ver el mensaje: "Habilitación creada exitosamente"

### PASO 3: Ver las Habilitaciones Creadas

1. En la lista de habilitaciones, verás:
   - Estado: **Habilitada** (badge verde)
   - Información del docente y la clase
   - Fecha de hoy
2. Puedes hacer clic en el ícono del ojo para ver los detalles completos

### PASO 4: Acceder como Docente

1. Cierra sesión del administrador
2. Inicia sesión con la cuenta del **docente** que habilitaste
3. Ve a: **Asistencia** → **Marcar Asistencia**
4. URL directa: `http://localhost/gestionAcademica/public/docente/asistencia/marcar`

### PASO 5: Marcar Asistencia (Docente)

1. Deberías ver la(s) clase(s) habilitada(s) con:
   - ⚠️ **Alerta amarilla**: "MODO PRUEBA: Ventana de tiempo deshabilitada para testing"
   - ℹ️ **Alerta azul**: "Habilitación activa: El administrador ha habilitado..."
   - 🟢 **Botón verde**: "Marcar Asistencia"

2. Haz clic en **"Marcar Asistencia"**

3. Se abrirá un modal de SweetAlert2 pidiendo:
   - Tu contraseña de inicio de sesión
   - Campo de texto tipo password

4. Ingresa tu contraseña y haz clic en **"Confirmar"**

5. Si la contraseña es correcta:
   - ✅ Mensaje de éxito: "¡Asistencia Marcada!"
   - La página se recargará
   - La clase ya no mostrará el botón (dirá "Ya marcaste tu asistencia")

6. Si la contraseña es incorrecta:
   - ❌ Mensaje de error: "Contraseña incorrecta"

### PASO 6: Verificar como Administrador

1. Vuelve a iniciar sesión como administrador
2. Ve a **Habilitaciones** nuevamente
3. La habilitación que creaste ahora debe mostrar:
   - Estado: **Utilizada** (badge azul)
   - Fecha de utilización
4. No puedes eliminar ni cancelar una habilitación utilizada

5. Opcionalmente, ve a **Control de Asistencia** → **Ver Asistencias**
6. Deberías ver el registro de asistencia del docente que marcó

---

## 🔄 Volver al Modo Producción

Cuando termines las pruebas y quieras usar el sistema en producción real:

### Archivo 1: `app/Http/Controllers/Docente/AsistenciaDocenteController.php`

**Línea ~80-92**, CAMBIAR de:

```php
// MODO PRUEBA: Si hay habilitaciones, mostrar todas las cargas habilitadas sin filtrar por día
$cargasHoy = CargaAcademica::with(['materia', 'grupo', 'horario', 'aula'])
    ->where('docente_id', $docente->id)
    ->whereIn('id', $cargasHabilitadasIds)
    ->get();
```

A:

```php
// Obtener cargas académicas del docente para hoy que están habilitadas
$diaHoy = Carbon::now()->locale('es')->dayName;

$cargasHoy = CargaAcademica::with(['materia', 'grupo', 'horario', 'aula'])
    ->where('docente_id', $docente->id)
    ->whereIn('id', $cargasHabilitadasIds)
    ->whereHas('horario', function($query) use ($diaHoy) {
        $query->where('dia_semana', 'LIKE', '%' . $diaHoy . '%');
    })
    ->get();
```

### Archivo 2: `resources/views/docente/asistencia/marcar.blade.php`

**Línea ~43-46**, CAMBIAR de:

```php
// MODO PRUEBA: Siempre permitir marcar si hay habilitación (para testing)
$esVentanaActiva = true; // Cambiar a: $ahora->between($horaApertura, $horaCierre); en producción
$esVentanaFutura = false;
```

A:

```php
$esVentanaActiva = $ahora->between($horaApertura, $horaCierre);
$esVentanaFutura = $ahora->lessThan($horaApertura);
```

**Línea ~69-71**, CAMBIAR de:

```blade
<div class="alert alert-warning mb-2">
    <i class="fa-solid fa-flask"></i> <strong>MODO PRUEBA:</strong> Ventana de tiempo deshabilitada para testing
</div>
```

A:

```blade
<div class="alert alert-success mb-2">
    <i class="fa-solid fa-clock"></i> <strong>Ventana Activa</strong> - Cierra a las {{ $horaCierre->format('H:i') }}
</div>
```

---

## 📊 Casos de Prueba Sugeridos

### ✅ Caso 1: Flujo Normal
1. Admin crea habilitación
2. Docente ve la clase habilitada
3. Docente marca con contraseña correcta
4. Asistencia registrada exitosamente

### ✅ Caso 2: Contraseña Incorrecta
1. Admin crea habilitación
2. Docente intenta marcar
3. Ingresa contraseña incorrecta
4. Sistema rechaza con mensaje de error

### ✅ Caso 3: Habilitación Ya Utilizada
1. Admin crea habilitación
2. Docente marca asistencia (usa la habilitación)
3. Docente intenta marcar de nuevo
4. Sistema muestra "Ya marcaste tu asistencia"

### ✅ Caso 4: Cancelar Habilitación
1. Admin crea habilitación
2. Admin cancela la habilitación (antes de que se use)
3. Docente no ve la clase en su lista de habilitadas

### ✅ Caso 5: Múltiples Habilitaciones
1. Admin crea varias habilitaciones para el mismo docente (diferentes clases)
2. Docente ve todas las clases habilitadas
3. Docente puede marcar cada una independientemente

---

## 🐛 Troubleshooting

### Problema: No se muestran clases al docente

**Solución**:
- Verifica que el docente tenga cargas académicas asignadas
- Verifica que la habilitación esté en estado "Habilitada"
- Verifica que la fecha de la habilitación sea hoy
- Revisa la consola del navegador (F12) por errores JavaScript

### Problema: Error al crear habilitación

**Solución**:
- Verifica que la tabla `habilitaciones_asistencia` exista: `php artisan migrate:status`
- Verifica que el docente seleccionado tenga cargas académicas
- Verifica que no exista ya una habilitación para ese docente/clase/fecha

### Problema: Modal de contraseña no aparece

**Solución**:
- Verifica que SweetAlert2 esté cargado (abre consola del navegador)
- Limpia caché del navegador (Ctrl + Shift + R)
- Verifica que el layout docente tenga `@stack('scripts')` antes de `</body>`

### Problema: Contraseña correcta pero dice incorrecta

**Solución**:
- El sistema usa `Hash::check()` de Laravel
- Verifica que la contraseña del usuario no haya sido cambiada manualmente en BD
- Intenta resetear la contraseña del docente desde el admin

---

## 📱 URLs Importantes

| Rol | URL | Descripción |
|-----|-----|-------------|
| Admin | `/admin/habilitaciones` | Listar habilitaciones |
| Admin | `/admin/habilitaciones/create` | Crear nueva habilitación |
| Admin | `/admin/asistencia` | Ver todas las asistencias |
| Docente | `/docente/asistencia/marcar` | Marcar mi asistencia |
| Docente | `/docente/asistencia` | Ver mi historial de asistencias |

---

## 📝 Notas Importantes

1. **Una habilitación = Un uso**: Una vez que el docente marca asistencia, la habilitación pasa a estado "Utilizada" y no se puede reutilizar.

2. **Seguridad**: El sistema valida:
   - La contraseña del docente
   - Que la habilitación pertenezca al docente
   - Que la habilitación esté activa
   - Que no haya asistencia duplicada

3. **Auditoría**: Se registra:
   - Quién creó la habilitación
   - Cuándo se utilizó
   - Observaciones del admin

4. **Estados de Habilitación**:
   - 🟢 **Habilitada**: Creada y lista para usar
   - 🔵 **Utilizada**: El docente ya marcó asistencia
   - ⚫ **Expirada**: Pasó la fecha sin usar
   - 🔴 **Cancelada**: El admin la canceló

---

## 🎯 Resumen Rápido

```bash
# 1. Como Admin: Crear habilitación
/admin/habilitaciones/create

# 2. Como Docente: Ver y marcar
/docente/asistencia/marcar

# 3. Ingresar contraseña cuando aparezca el modal

# 4. Como Admin: Verificar que estado = "Utilizada"
/admin/habilitaciones
```

¡Sistema listo para pruebas! 🚀
