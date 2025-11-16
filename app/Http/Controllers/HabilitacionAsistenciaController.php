<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HabilitacionAsistencia;
use App\Models\Docente;
use App\Models\CargaAcademica;
use Illuminate\Support\Facades\Auth;

class HabilitacionAsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HabilitacionAsistencia::with([
            'docente.user',
            'cargaAcademica.materia',
            'cargaAcademica.grupo',
            'cargaAcademica.horario',
            'creador'
        ]);

        // Filtros
        if ($request->filled('docente_id')) {
            $query->where('docente_id', $request->docente_id);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $habilitaciones = $query->orderBy('fecha', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->paginate(15)
                                ->withQueryString();

        $docentes = Docente::with('user')->orderBy('id')->get();

        return view('admin.habilitaciones.index', compact('habilitaciones', 'docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener docentes con sus cargas académicas
        $cargasAcademicas = CargaAcademica::with([
            'docente.user',
            'materia',
            'grupo',
            'horario',
            'aula'
        ])
            ->orderBy('docente_id')
            ->get();

        $docentesConCargas = $cargasAcademicas->groupBy('docente_id')->map(function($cargas) {
            return [
                'docente' => $cargas->first()->docente,
                'cargas' => $cargas
            ];
        });

        return view('admin.habilitaciones.create', compact('docentesConCargas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'docente_id' => ['required', 'integer', 'exists:docentes,id'],
            'carga_academica_id' => ['required', 'integer', 'exists:carga_academica,id'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        // Verificar que la carga pertenece al docente
        $carga = CargaAcademica::findOrFail($validated['carga_academica_id']);

        if ($carga->docente_id != $validated['docente_id']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La carga académica no pertenece al docente seleccionado.');
        }

        // Verificar si ya existe una habilitación
        $existe = HabilitacionAsistencia::where('docente_id', $validated['docente_id'])
            ->where('carga_academica_id', $validated['carga_academica_id'])
            ->whereDate('fecha', $validated['fecha'])
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe una habilitación para este docente, materia y fecha.');
        }

        // Crear habilitación
        $validated['creado_por'] = Auth::id();

        HabilitacionAsistencia::create($validated);

        return redirect()->route('admin.habilitaciones.index')
            ->with('success', 'Habilitación creada exitosamente. El docente podrá marcar su asistencia.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HabilitacionAsistencia $habilitacion)
    {
        $habilitacion->load([
            'docente.user',
            'cargaAcademica.materia',
            'cargaAcademica.grupo',
            'cargaAcademica.horario',
            'cargaAcademica.aula',
            'creador'
        ]);

        return view('admin.habilitaciones.show', compact('habilitacion'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HabilitacionAsistencia $habilitacion)
    {
        if ($habilitacion->estado === 'Utilizada') {
            return redirect()->back()
                ->with('error', 'No se puede eliminar una habilitación que ya fue utilizada.');
        }

        $habilitacion->delete();

        return redirect()->route('admin.habilitaciones.index')
            ->with('success', 'Habilitación eliminada exitosamente.');
    }

    /**
     * Cancelar una habilitación
     */
    public function cancelar(HabilitacionAsistencia $habilitacion)
    {
        if ($habilitacion->estado === 'Utilizada') {
            return redirect()->back()
                ->with('error', 'No se puede cancelar una habilitación que ya fue utilizada.');
        }

        $habilitacion->update(['estado' => 'Cancelada']);

        return redirect()->back()
            ->with('success', 'Habilitación cancelada exitosamente.');
    }

    /**
     * Obtener cargas académicas de un docente (para AJAX)
     */
    public function getMateriasByDocente($docenteId)
    {
        $cargas = CargaAcademica::with(['materia', 'grupo', 'horario', 'aula'])
            ->where('docente_id', $docenteId)
            ->get();

        return response()->json($cargas);
    }
}
