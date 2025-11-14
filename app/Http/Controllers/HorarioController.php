<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los horarios agrupados por hora_inicio y hora_fin
        $horariosRaw = Horario::orderBy('hora_inicio')->orderBy('hora_fin')->get();

        // Agrupar horarios con las mismas horas
        $horariosAgrupados = $horariosRaw->groupBy(function($horario) {
            return $horario->hora_inicio . '-' . $horario->hora_fin;
        })->map(function($grupo) {
            // Tomar el primer horario como base
            $horarioBase = $grupo->first();
            // Agregar todos los días del grupo
            $horarioBase->dias_agrupados = $grupo->pluck('dia_semana')->toArray();
            return $horarioBase;
        });

        // Convertir a colección paginable
        $page = request()->get('page', 1);
        $perPage = 15;
        $horarios = new \Illuminate\Pagination\LengthAwarePaginator(
            $horariosAgrupados->forPage($page, $perPage),
            $horariosAgrupados->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.horarios.index', compact('horarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.horarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dias_semana' => 'required|array|min:1',
            'dias_semana.*' => 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ], [
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'dias_semana.required' => 'Debe seleccionar al menos un día de la semana.',
            'dias_semana.min' => 'Debe seleccionar al menos un día de la semana.',
        ]);

        try {
            DB::beginTransaction();

            $horariosCreados = [];
            $diasDuplicados = [];

            foreach ($validated['dias_semana'] as $dia) {
                // Verificar si ya existe un horario con los mismos datos
                $existente = Horario::where('dia_semana', $dia)
                    ->where('hora_inicio', $validated['hora_inicio'])
                    ->where('hora_fin', $validated['hora_fin'])
                    ->first();

                if ($existente) {
                    $diasDuplicados[] = $dia;
                    continue;
                }

                $horario = Horario::create([
                    'dia_semana' => $dia,
                    'hora_inicio' => $validated['hora_inicio'],
                    'hora_fin' => $validated['hora_fin'],
                ]);

                $horariosCreados[] = $horario;

                // Registrar en bitácora
                Bitacora::create([
                    'user_id' => auth()->id(),
                    'usuario' => auth()->user()->name,
                    'descripcion' => "Creó el bloque horario {$horario->dia_semana} {$horario->hora_inicio}-{$horario->hora_fin}",
                    'metodo' => 'POST',
                    'ruta' => request()->path(),
                    'direccion_ip' => request()->ip(),
                    'navegador' => request()->userAgent(),
                    'fecha_hora' => now(),
                ]);
            }

            DB::commit();

            $mensaje = count($horariosCreados) . ' bloque(s) horario(s) registrado(s) exitosamente.';
            if (count($diasDuplicados) > 0) {
                $mensaje .= ' Días omitidos por duplicados: ' . implode(', ', $diasDuplicados) . '.';
            }

            return redirect()->route('admin.horario.index')
                ->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar horario: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        // Cargar relaciones
        $horario->load(['cargasAcademicas.docente.user', 'cargasAcademicas.materia', 'cargasAcademicas.grupo', 'cargasAcademicas.aula']);

        // Buscar todos los horarios con las mismas horas
        $horariosRelacionados = Horario::where('hora_inicio', $horario->hora_inicio)
            ->where('hora_fin', $horario->hora_fin)
            ->get();

        // Agregar los días relacionados al horario
        $horario->dias_relacionados = $horariosRelacionados->pluck('dia_semana')->toArray();

        return view('admin.horarios.show', compact('horario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        // Buscar todos los horarios con las mismas horas
        $horariosRelacionados = Horario::where('hora_inicio', $horario->hora_inicio)
            ->where('hora_fin', $horario->hora_fin)
            ->get();
        
        // Agregar los días relacionados al horario
        $horario->dias_relacionados = $horariosRelacionados->pluck('dia_semana')->toArray();
        
        return view('admin.horarios.edit', compact('horario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        $validated = $request->validate([
            'dias_semana' => 'required|array|min:1',
            'dias_semana.*' => 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ], [
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'dias_semana.required' => 'Debe seleccionar al menos un día de la semana.',
            'dias_semana.min' => 'Debe seleccionar al menos un día de la semana.',
        ]);

        try {
            DB::beginTransaction();

            $horariosActualizados = [];
            $diasDuplicados = [];

            // Si solo seleccionó el mismo día, actualizar el horario actual
            if (count($validated['dias_semana']) == 1 && $validated['dias_semana'][0] == $horario->dia_semana) {
                $horario->update([
                    'hora_inicio' => $validated['hora_inicio'],
                    'hora_fin' => $validated['hora_fin'],
                ]);

                $horariosActualizados[] = $horario;

                // Registrar en bitácora
                Bitacora::create([
                    'user_id' => auth()->id(),
                    'usuario' => auth()->user()->name,
                    'descripcion' => "Actualizó el bloque horario {$horario->dia_semana} {$horario->hora_inicio}-{$horario->hora_fin}",
                    'metodo' => 'PUT',
                    'ruta' => request()->path(),
                    'direccion_ip' => request()->ip(),
                    'navegador' => request()->userAgent(),
                    'fecha_hora' => now(),
                ]);
            } else {
                // Si cambió días, eliminar el actual y crear nuevos
                $oldDescripcion = $horario->descripcion;
                $horario->delete();

                foreach ($validated['dias_semana'] as $dia) {
                    // Verificar si ya existe un horario con los mismos datos
                    $existente = Horario::where('dia_semana', $dia)
                        ->where('hora_inicio', $validated['hora_inicio'])
                        ->where('hora_fin', $validated['hora_fin'])
                        ->first();

                    if ($existente) {
                        $diasDuplicados[] = $dia;
                        continue;
                    }

                    $nuevoHorario = Horario::create([
                        'dia_semana' => $dia,
                        'hora_inicio' => $validated['hora_inicio'],
                        'hora_fin' => $validated['hora_fin'],
                    ]);

                    $horariosActualizados[] = $nuevoHorario;

                    // Registrar en bitácora
                    Bitacora::create([
                        'user_id' => auth()->id(),
                        'usuario' => auth()->user()->name,
                        'descripcion' => "Actualizó el bloque horario de '{$oldDescripcion}' a '{$nuevoHorario->descripcion}'",
                        'metodo' => 'PUT',
                        'ruta' => request()->path(),
                        'direccion_ip' => request()->ip(),
                        'navegador' => request()->userAgent(),
                        'fecha_hora' => now(),
                    ]);
                }
            }

            DB::commit();

            $mensaje = count($horariosActualizados) . ' bloque(s) horario(s) actualizado(s) exitosamente.';
            if (count($diasDuplicados) > 0) {
                $mensaje .= ' Días omitidos por duplicados: ' . implode(', ', $diasDuplicados) . '.';
            }

            return redirect()->route('admin.horario.index')
                ->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar horario: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        try {
            // Verificar si tiene asignaciones de carga académica
            if ($horario->cargasAcademicas()->count() > 0) {
                return back()->withErrors(['error' => 'No se puede eliminar el horario porque tiene asignaciones de carga académica.']);
            }

            DB::beginTransaction();

            // Capturar datos antes de eliminar
            $descripcionHorario = $horario->descripcion;

            $horario->delete();

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Eliminó el bloque horario '{$descripcionHorario}'",
                'metodo' => 'DELETE',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.horario.index')
                ->with('success', 'Bloque horario eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar horario: ' . $e->getMessage()]);
        }
    }
}
