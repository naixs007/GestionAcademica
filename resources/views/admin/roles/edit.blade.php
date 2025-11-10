<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Editar rol</h2>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Volver</a>
        </div>

        <div class="card">
            <div class="card-body">
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
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permisos</label>
                        <div class="row">
                            @php $assigned = $role->permissions->pluck('id')->toArray(); @endphp
                            @foreach(\Spatie\Permission\Models\Permission::orderBy('name')->get() as $perm)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm-{{ $perm->id }}" class="form-check-input" {{ in_array($perm->id, old('permissions', $assigned)) ? 'checked' : '' }}>
                                        <label for="perm-{{ $perm->id }}" class="form-check-label">{{ $perm->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
