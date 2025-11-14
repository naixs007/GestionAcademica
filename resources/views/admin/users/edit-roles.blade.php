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
                <form action="{{ route('admin.user.roles.update', $user) }}" method="POST" id="rolesForm">
                    @csrf
                    @method('PUT')

                    <h5>Roles</h5>
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle"></i> Solo se puede asignar un rol por usuario.
                    </div>
                    <div class="row mb-3">
                        @foreach($roles as $role)
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input role-checkbox" type="checkbox" value="{{ $role->id }}" id="role-{{ $role->id }}" name="roles[]"
                                        {{ in_array($role->id, old('roles', $userRoleIds ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleCheckboxes = document.querySelectorAll('.role-checkbox');
            const form = document.getElementById('rolesForm');

            // Función para permitir solo un checkbox seleccionado
            roleCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        // Desmarcar todos los demás checkboxes
                        roleCheckboxes.forEach(otherCheckbox => {
                            if (otherCheckbox !== this) {
                                otherCheckbox.checked = false;
                            }
                        });
                    }
                });
            });

            // Validación al enviar el formulario
            form.addEventListener('submit', function(e) {
                const checkedRoles = Array.from(roleCheckboxes).filter(cb => cb.checked);

                if (checkedRoles.length === 0) {
                    e.preventDefault();
                    alert('Debes seleccionar al menos un rol.');
                    return false;
                }

                if (checkedRoles.length > 1) {
                    e.preventDefault();
                    alert('Solo puedes seleccionar un rol por usuario.');
                    return false;
                }
            });
        });
    </script>
</x-admin-layout>
