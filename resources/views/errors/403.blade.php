<x-admin-layout>
    <div class="container py-6">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">
                            <i class="fa-solid fa-ban"></i> Acceso Denegado
                        </h4>
                    </div>
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-shield-halved fa-5x text-danger"></i>
                        </div>
                        <h2 class="text-danger mb-3">Error 403</h2>
                        <h5 class="mb-4">No tienes permiso para acceder a esta sección</h5>
                        <p class="text-muted mb-4">
                            Tu rol de usuario no tiene los permisos necesarios para ver este contenido.
                            Si crees que esto es un error, contacta al administrador del sistema.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fa-solid fa-home"></i> Ir al Inicio
                            </a>
                            <button onclick="window.history.back()" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Volver
                            </button>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center">
                        <small>
                            <i class="fa-solid fa-info-circle"></i>
                            Usuario: <strong>{{ auth()->user()->name }}</strong> |
                            Rol: <strong>{{ auth()->user()->roles->pluck('name')->join(', ') }}</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
