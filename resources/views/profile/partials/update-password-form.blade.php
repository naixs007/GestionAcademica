<div>
    <p class="text-muted mb-4">
        Asegúrate de usar una contraseña larga y segura para proteger tu cuenta.
    </p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        {{-- Contraseña Actual --}}
        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">
                <i class="fa-solid fa-lock text-success me-1"></i>
                Contraseña Actual
            </label>
            <input type="password"
                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                   id="update_password_current_password"
                   name="current_password"
                   autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nueva Contraseña --}}
        <div class="mb-3">
            <label for="update_password_password" class="form-label">
                <i class="fa-solid fa-key text-success me-1"></i>
                Nueva Contraseña
            </label>
            <input type="password"
                   class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                   id="update_password_password"
                   name="password"
                   autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">
                <i class="fa-solid fa-info-circle me-1"></i>
                Mínimo 8 caracteres. Usa mayúsculas, minúsculas y números.
            </div>
        </div>

        {{-- Confirmar Contraseña --}}
        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">
                <i class="fa-solid fa-shield-halved text-success me-1"></i>
                Confirmar Nueva Contraseña
            </label>
            <input type="password"
                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                   id="update_password_password_confirmation"
                   name="password_confirmation"
                   autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botón Guardar --}}
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Actualizar Contraseña
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    ¡Contraseña actualizada!
                </span>
            @endif
        </div>
    </form>
</div>
