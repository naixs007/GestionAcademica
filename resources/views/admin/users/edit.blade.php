<x-admin-layout>
    <div class="container py-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Editar Usuario</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <button class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
