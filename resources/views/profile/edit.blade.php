<x-admin-layout>
    <div class="container-fluid px-4 py-4">
        {{-- Encabezado --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="fa-solid fa-user-circle text-primary me-2"></i>
                    Mi Perfil
                </h2>
                <p class="text-muted mb-0">Administra tu información personal y seguridad</p>
            </div>
        </div>

        <div class="row g-4">
            {{-- Información del Perfil --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-id-card me-2"></i>
                            Información del Perfil
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Cambiar Contraseña --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-key me-2"></i>
                            Actualizar Contraseña
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Eliminar Cuenta --}}
            <div class="col-12">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            Zona Peligrosa
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
