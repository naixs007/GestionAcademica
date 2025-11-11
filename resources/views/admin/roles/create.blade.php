<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Crear rol</h2>
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
                <form action="{{ route('admin.roles.store') }}" method="POST" id="roleForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nombre del Rol</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        <small class="text-muted">Nombre único para identificar el rol</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Plantilla de Rol</label>
                        <select name="template" id="templateSelect" class="form-select">
                            <option value="custom" {{ old('template') == 'custom' ? 'selected' : '' }}>Personalizado (seleccionar permisos manualmente)</option>
                            @foreach($templates as $key => $template)
                                @if($key !== 'custom')
                                    <option value="{{ $key }}" {{ old('template') == $key ? 'selected' : '' }}>
                                        {{ $template['name'] }} - {{ $template['description'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <small class="text-muted" id="templateDescription">Selecciona una plantilla predeterminada o crea un rol personalizado</small>
                    </div>

                    <div class="mb-3" id="permissionsSection">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">Permisos</label>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAll">Seleccionar todos</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">Deseleccionar todos</button>
                            </div>
                        </div>
                        <div class="row" id="permissionsList">
                            @foreach($permissions as $perm)
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" 
                                               id="perm-{{ $perm->id }}" class="form-check-input permission-checkbox"
                                               {{ in_array($perm->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <label for="perm-{{ $perm->id }}" class="form-check-label">{{ $perm->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Crear Rol</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const templateSelect = document.getElementById('templateSelect');
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
            const selectAllBtn = document.getElementById('selectAll');
            const deselectAllBtn = document.getElementById('deselectAll');
            const templateDescription = document.getElementById('templateDescription');

            // Datos de plantillas desde el servidor
            const templates = @json($templates);
            
            // Crear mapeo de permisos (id => nombre)
            const permissionsMap = {};
            @foreach($permissions as $perm)
                permissionsMap[{{ $perm->id }}] = '{{ $perm->name }}';
            @endforeach

            // Función para aplicar permisos de plantilla
            function applyTemplatePermissions(templateKey) {
                if (templateKey === 'custom') {
                    templateDescription.textContent = 'Selecciona manualmente los permisos para este rol';
                    // No cambiar checkboxes en modo personalizado
                    return;
                }

                const template = templates[templateKey];
                if (!template) return;

                templateDescription.textContent = template.description || '';
                
                // Desmarcar todos los checkboxes primero
                permissionCheckboxes.forEach(cb => cb.checked = false);
                
                // Marcar solo los permisos de la plantilla seleccionada
                if (template.permissions && template.permissions.length > 0) {
                    permissionCheckboxes.forEach(cb => {
                        const permId = parseInt(cb.value);
                        const permName = permissionsMap[permId];
                        
                        // Si el nombre del permiso está en la plantilla, marcarlo
                        if (permName && template.permissions.includes(permName)) {
                            cb.checked = true;
                        }
                    });
                }
            }

            // Aplicar plantilla inicial si hay un valor old()
            const initialTemplate = templateSelect.value;
            if (initialTemplate && initialTemplate !== 'custom') {
                applyTemplatePermissions(initialTemplate);
            }

            // Evento: cambio de plantilla
            templateSelect.addEventListener('change', function() {
                applyTemplatePermissions(this.value);
            });

            // Seleccionar todos
            selectAllBtn.addEventListener('click', function() {
                permissionCheckboxes.forEach(cb => cb.checked = true);
            });

            // Deseleccionar todos
            deselectAllBtn.addEventListener('click', function() {
                permissionCheckboxes.forEach(cb => cb.checked = false);
            });
        });
    </script>
    @endpush
</x-admin-layout>
