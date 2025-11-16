<div>
    <div class="alert alert-danger" role="alert">
        <h5 class="alert-heading">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            Advertencia Importante
        </h5>
        <p class="mb-0">
            Una vez que elimines tu cuenta, todos tus datos y recursos serán eliminados permanentemente.
            Antes de continuar, asegúrate de descargar cualquier información que desees conservar.
        </p>
    </div>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
        <i class="fa-solid fa-trash-can me-1"></i>
        Eliminar Cuenta
    </button>
</div>

{{-- Modal de Confirmación --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    ¿Estás seguro?
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                        Esta acción <strong>no se puede deshacer</strong>. Todos tus datos serán eliminados permanentemente.
                    </div>

                    <p class="mb-3">Para confirmar, por favor ingresa tu contraseña:</p>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fa-solid fa-lock me-1"></i>
                            Contraseña
                        </label>
                        <input type="password"
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Ingresa tu contraseña"
                               required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash-can me-1"></i>
                        Sí, Eliminar Mi Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            deleteModal.show();
        });
    </script>
@endif
