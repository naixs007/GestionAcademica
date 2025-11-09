<x-admin-layout>
    <div class="container py-6">
        <h2 class="h4 mb-3">Editar asistencia</h2>

        <form action="{{ route('admin.asistencia.update', $asistencia) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $asistencia->fecha) }}">
            </div>

            <div class="mb-3 form-check">
                <input type="hidden" name="estado" value="0">
                <input type="checkbox" name="estado" value="1" class="form-check-input" id="estado" {{ old('estado', $asistencia->estado) ? 'checked' : '' }}>
                <label class="form-check-label" for="estado">Presente</label>
            </div>

            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $asistencia->observaciones) }}</textarea>
            </div>

            <button class="btn btn-primary">Actualizar</button>
            <a href="{{ route('admin.asistencia.index') }}" class="btn btn-link">Cancelar</a>
        </form>
    </div>
</x-admin-layout>
