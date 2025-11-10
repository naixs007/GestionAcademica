<x-admin-layout>
    <div class="container py-6">
        <h2 class="h4 mb-3">Permiso: {{ $permission->name }}</h2>

        <div class="card">
            <div class="card-body">
                <p><strong>Roles asignados:</strong> {{ $permission->roles->pluck('name')->join(', ') }}</p>

                <a href="{{ route('admin.permissions.index') }}" class="btn btn-link">Volver</a>
            </div>
        </div>
    </div>
</x-admin-layout>
