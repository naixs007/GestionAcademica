<x-admin-layout>
    <div class="container py-6">
        <h2 class="h4 mb-3">Ver asistencia</h2>

        <div class="card">
            <div class="card-body">
                <p><strong>Fecha:</strong> {{ $asistencia->fecha }}</p>
                <p><strong>Estado:</strong> {{ $asistencia->estado ? 'Presente' : 'Ausente' }}</p>
                <p><strong>Observaciones:</strong> {{ $asistencia->observaciones ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.asistencia.index') }}" class="btn btn-link">Volver</a>
        </div>
    </div>
</x-admin-layout>
