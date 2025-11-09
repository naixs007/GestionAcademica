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

					{{-- Observaciones (colocado en el lugar originalmente ocupado por "Docente") --}}
					<div class="mb-3">
						<label for="observaciones" class="form-label">Observaciones</label>
						<textarea id="observaciones" name="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
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

					{{-- Seleccionar Docente --}}
					<div class="mb-3">
						<label for="docente_id" class="form-label">Docente</label>
						<select id="docente_id" name="docente_id" class="form-select" required>
							@if(isset($docentes) && $docentes->isNotEmpty())
								<option value="">-- Seleccione un docente --</option>
								@foreach($docentes as $doc)
									<option value="{{ $doc->id }}" {{ old('docente_id') == $doc->id ? 'selected' : '' }}>
										{{ optional($doc->user)->name ?? ('Docente #'.$doc->id) }}
									</option>
								@endforeach
							@else
								<option value="" disabled>No hay docentes registrados</option>
							@endif
						</select>
						@if(!(isset($docentes) && $docentes->isNotEmpty()))
							<div class="form-text">
								No se encontraron docentes. Crea un usuario y asígnale el rol <code>docente</code> para que aparezca en esta lista.
								<a href="{{ route('admin.users.create') }}">Crear usuario</a>
							</div>
						@endif
					</div>

					{{-- Seleccionar Horario (necesario por integridad referencial) --}}
					<div class="mb-3">
						<label for="horario_id" class="form-label">Horario</label>
						<select id="horario_id" name="horario_id" class="form-select" required>
							@if(isset($horarios) && $horarios->isNotEmpty())
								<option value="">-- Seleccione un horario --</option>
								@foreach($horarios as $h)
									<option value="{{ $h->id }}" {{ old('horario_id') == $h->id ? 'selected' : '' }}>
										{{ $h->diaSemana ?? 'Horario #'.$h->id }} {{ $h->horaInicio ?? '' }}-{{ $h->horaFin ?? '' }}
									</option>
								@endforeach
							@else
								<option value="" disabled>No hay horarios registrados</option>
							@endif
						</select>
						@if(!(isset($horarios) && $horarios->isNotEmpty()))
							<div class="form-text">No se encontraron horarios. Cree horarios en la sección correspondiente.</div>
						@endif
					</div>

					<button class="btn btn-primary">Registrar</button>
				</form>
			</div>
		</div>
	</div>
</x-admin-layout>
