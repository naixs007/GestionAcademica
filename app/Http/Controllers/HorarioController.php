<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Horario;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horarios = Horario::with('materia')->paginate(10);
        return view('admin.horarios.index', compact('horarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materias = Materia::orderBy('nombre')->get();
        return view('admin.horarios.create', compact('materias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'diaSemana' => 'required|array|min:1',
            'diaSemana.*' => 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'materia_id' => 'required|exists:materias,id',
            'modalidad' => 'required|in:presencial,virtual',
        ], [
            'horaFin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'diaSemana.required' => 'Debe seleccionar al menos un día de la semana.',
            'diaSemana.min' => 'Debe seleccionar al menos un día de la semana.',
            'diaSemana.*.in' => 'El día de la semana no es válido.',
            'modalidad.in' => 'La modalidad debe ser presencial o virtual.',
        ]);

        try {
            DB::beginTransaction();

            $horario = Horario::create($validated);
            $materia = Materia::find($validated['materia_id']);
            $diasTexto = implode(', ', $validated['diaSemana']);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Creó el horario para la materia '{$materia->nombre}' ({$diasTexto} {$horario->horaInicio}-{$horario->horaFin}, modalidad: {$horario->modalidad})",
                'metodo' => 'POST',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.horario.index')
                ->with('success', 'Horario registrado exitosamente.');
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
        $horario->load('materia', 'asistencias');
        return view('admin.horarios.show', compact('horario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        $materias = Materia::orderBy('nombre')->get();
        return view('admin.horarios.edit', compact('horario', 'materias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        $validated = $request->validate([
            'diaSemana' => 'required|array|min:1',
            'diaSemana.*' => 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'materia_id' => 'required|exists:materias,id',
            'modalidad' => 'required|in:presencial,virtual',
        ], [
            'horaFin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'diaSemana.required' => 'Debe seleccionar al menos un día de la semana.',
            'diaSemana.min' => 'Debe seleccionar al menos un día de la semana.',
            'diaSemana.*.in' => 'El día de la semana no es válido.',
            'modalidad.in' => 'La modalidad debe ser presencial o virtual.',
        ]);

        try {
            DB::beginTransaction();

            $oldMateria = $horario->materia->nombre ?? 'N/A';
            $horario->update($validated);
            $materia = Materia::find($validated['materia_id']);
            $diasTexto = implode(', ', $validated['diaSemana']);

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Actualizó el horario de '{$oldMateria}' a '{$materia->nombre}' ({$diasTexto} {$horario->horaInicio}-{$horario->horaFin}, modalidad: {$horario->modalidad})",
                'metodo' => 'PUT',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.horario.index')
                ->with('success', 'Horario actualizado exitosamente.');
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
            // Verificar si tiene asistencias registradas
            if ($horario->asistencias()->count() > 0) {
                return back()->withErrors(['error' => 'No se puede eliminar el horario porque tiene asistencias registradas.']);
            }

            DB::beginTransaction();

            // Capturar datos antes de eliminar
            $materiaHorario = $horario->materia->nombre ?? 'N/A';
            $diasTexto = is_array($horario->diaSemana) ? implode(', ', $horario->diaSemana) : $horario->diaSemana;
            $rangoHorario = "{$horario->horaInicio}-{$horario->horaFin}";

            $horario->delete();

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'usuario' => auth()->user()->name,
                'descripcion' => "Eliminó el horario de la materia '{$materiaHorario}' ({$diasTexto} {$rangoHorario})",
                'metodo' => 'DELETE',
                'ruta' => request()->path(),
                'direccion_ip' => request()->ip(),
                'navegador' => request()->userAgent(),
                'fecha_hora' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.horario.index')
                ->with('success', 'Horario eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar horario: ' . $e->getMessage()]);
        }
    }
}
