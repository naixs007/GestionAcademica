<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Detalle de Usuario</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>

        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nombre</dt>
                    <dd class="col-sm-9">{{ $user->name }}</dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ $user->email }}</dd>

                    <dt class="col-sm-3">Roles</dt>
                    <dd class="col-sm-9">{{ $user->getRoleNames()->join(', ') }}</dd>

                    <dt class="col-sm-3">Estado</dt>
                    <dd class="col-sm-9">{{ $user->estado ?? '-' }}</dd>

                    <dt class="col-sm-3">Creado</dt>
                    <dd class="col-sm-9">{{ $user->created_at->toDateTimeString() }}</dd>
                </dl>
            </div>
        </div>
    </div>
</x-admin-layout>
