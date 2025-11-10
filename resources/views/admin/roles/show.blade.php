<x-admin-layout>
    <div class="container py-6">
        <h2 class="h4 mb-3">Rol: {{ $role->name }}</h2>

        <div class="card">
            <div class="card-body">
                <p><strong>Permisos:</strong> {{ $role->permissions->pluck('name')->join(', ') }}</p>
                <p><strong>Usuarios asignados:</strong> {{ $role->users()->count() }}</p>

                <a href="{{ route('admin.roles.index') }}" class="btn btn-link">Volver</a>
            </div>
        </div>
    </div>
</x-admin-layout>
