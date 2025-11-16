<div>
    <p class="text-muted mb-4">
        Actualiza tu información personal y dirección de correo electrónico.
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        {{-- Nombre --}}
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="fa-solid fa-user text-primary me-1"></i>
                Nombre Completo
            </label>
            <input type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   required
                   autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fa-solid fa-envelope text-primary me-1"></i>
                Correo Electrónico
            </label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    Tu correo electrónico no está verificado.
                    <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
                        Haz clic aquí para reenviar el correo de verificación.
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-2" role="alert">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        Se ha enviado un nuevo enlace de verificación a tu correo.
                    </div>
                @endif
            @endif
        </div>

        {{-- Botón Guardar --}}
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Guardar Cambios
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    ¡Guardado correctamente!
                </span>
            @endif
        </div>
    </form>
</div>
