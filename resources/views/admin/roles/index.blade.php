<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Roles</h2>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Crear rol</a>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th class="text-center">Usuarios</th>
                            <th class="text-center">Permisos</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td class="text-center">{{ $role->users_count ?? 0 }}</td>
                                <td class="text-center">{{ $role->permissions_count ?? 0 }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-info">Ver</a>
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar rol?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay roles definidos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $roles->links() }}
        </div>
    </div>
</x-admin-layout>
