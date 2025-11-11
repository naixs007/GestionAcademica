<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Usuarios</h2>
            @can('usuarios.crear')
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Crear usuario</a>
            @endcan
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            @can('usuarios.ver')
                                @if(auth()->user()->hasAnyRole(['admin', 'super-admin', 'decano']))
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                @endif
                                @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
                                    <th>Roles</th>
                                    <th>Creado</th>
                                @endif
                            @endcan
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                @can('usuarios.ver')
                                    @if(auth()->user()->hasAnyRole(['admin', 'super-admin', 'decano']))
                                        <td>{{ $user->telefono ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $user->estado === 'activo' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($user->estado ?? 'N/D') }}
                                            </span>
                                        </td>
                                    @endif
                                    @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
                                        <td>{{ $user->getRoleNames()->join(', ') }}</td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    @endif
                                @endcan
                                <td class="text-end">
                                    @can('usuarios.ver')
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info">Ver</a>
                                    @endcan
                                    @can('usuarios.editar')
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                    @endcan
                                    @can('usuarios.asignar_roles')
                                        <a href="{{ route('admin.user.roles.edit', $user) }}" class="btn btn-sm btn-outline-secondary ms-1">Roles</a>
                                    @endcan
                                    @can('usuarios.eliminar')
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay usuarios.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>
