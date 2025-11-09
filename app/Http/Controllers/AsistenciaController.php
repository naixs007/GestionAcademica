<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Horario;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asistencias = Asistencia::orderByDesc('fecha')->paginate(15);
        return view('admin.asistencia.index', compact('asistencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $docentes = Docente::with('user')->orderBy('id')->get();
        $horarios = Horario::orderBy('id')->get();

        return view('admin.asistencia.create', compact('docentes','horarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'docente_id' => ['required','integer','exists:docentes,id'],
            'horario_id' => ['required','integer','exists:horarios,id'],
            'fecha' => ['required','date'],
            'asistio' => ['required','in:0,1'],
            'observaciones' => ['nullable','string'],
        ]);

        Asistencia::create([
            'docente_id' => $data['docente_id'],
            'horario_id' => $data['horario_id'],
            'estado' => $data['asistio'],
            'fecha' => $data['fecha'],
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        return redirect()->route('admin.asistencia.index')->with('status', 'Asistencia registrada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asistencia = Asistencia::findOrFail($id);
        return view('admin.asistencia.show', compact('asistencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asistencia = Asistencia::findOrFail($id);
        return view('admin.asistencia.edit', compact('asistencia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'fecha' => ['required','date'],
            'asistio' => ['required','in:0,1'],
            'observaciones' => ['nullable','string'],
        ]);

        $asistencia = Asistencia::findOrFail($id);
        $asistencia->update([
            'estado' => $data['asistio'],
            'fecha' => $data['fecha'],
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        return redirect()->route('admin.asistencia.index')->with('status', 'Asistencia actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $asistencia->delete();
        return redirect()->route('admin.asistencia.index')->with('status', 'Asistencia eliminada.');
    }
}
