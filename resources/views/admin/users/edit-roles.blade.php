<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4">Asignar Roles / Permisos: {{ $user->name }}</h2>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Volver</a>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.user.roles.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h5>Roles</h5>
                    <div class="row mb-3">
                        @foreach($roles as $role)
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $role->id }}" id="role-{{ $role->id }}" name="roles[]"
                                        {{ in_array($role->id, old('roles', $userRoleIds ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h5>Permisos</h5>
                    <div class="row mb-3">
                        @foreach($permissions as $perm)
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $perm->id }}" id="perm-{{ $perm->id }}" name="permissions[]"
                                        {{ in_array($perm->id, old('permissions', $userPermissionIds ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm-{{ $perm->id }}">{{ $perm->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary">Guardar cambios</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
