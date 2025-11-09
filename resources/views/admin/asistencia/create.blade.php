<x-admin-layout>
	<div class="container py-6">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<h2 class="h4">Registrar Asistencia</h2>
			<a href="{{ route('admin.asistencia.index') }}" class="btn btn-outline-secondary">Volver</a>
		</div>

		<div class="card">
			<div class="card-body">
				<form action="{{ route('admin.asistencia.store') }}" method="POST">
					@csrf

					<div class="mb-3">
						<label for="docente" class="form-label">Docente</label>
						<input type="text" id="docente" name="docente" class="form-control" required>
					</div>

					<div class="mb-3">
						<label for="fecha" class="form-label">Fecha</label>
						<input type="date" id="fecha" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
					</div>

					<div class="mb-3">
						<label for="asistio" class="form-label">Asistió</label>
						<select id="asistio" name="asistio" class="form-select">
							<option value="1">Sí</option>
							<option value="0">No</option>
						</select>
					</div>

					<button class="btn btn-primary">Registrar</button>
				</form>
			</div>
		</div>
	</div>
</x-admin-layout>
