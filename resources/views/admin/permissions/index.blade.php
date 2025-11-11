<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Permisos</h2>
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">Crear permiso</a>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th class="text-center">Roles</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $perm)
                            <tr>
                                <td>{{ $perm->name }}</td>
                                <td class="text-center">{{ $perm->roles_count ?? 0 }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.permissions.show', $perm) }}" class="btn btn-sm btn-outline-info">Ver</a>
                                    <a href="{{ route('admin.permissions.edit', $perm) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                    <form action="{{ route('admin.permissions.destroy', $perm) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar permiso?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay permisos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $permissions->links() }}
        </div>
    </div>
</x-admin-layout>
