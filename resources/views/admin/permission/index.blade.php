@extends('layouts.app')

@section('content')
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 style="margin:0; font-weight:600;">Gestión de Permisos</h5>
            <a href="{{ route('permissions.create') }}" class="btn"
                style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                <svg class="icon me-2">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use>
                </svg>
                Crear Permiso
            </a>
        </div>

        <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
            {{-- Mensaje de estado --}}
            @if (session('status'))
                <div class="alert alert-success"
                    style="border:none; border-radius:8px; background-color:#c8e6c9; color:#155724; border-left:4px solid #28a745;">
                    {{ session('status') }}
                </div>
            @endif

            <div class="row">
                @foreach ($permissions as $permission)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card"
                            style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition:transform 0.2s, box-shadow 0.2s; background-color:white;"
                            onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(134,142,150,0.15)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'">
                            <div class="card-body" style="padding:1.5rem;">
                                <div class="d-flex align-items-center mb-3">
                                    <div
                                        style="width:45px; height:45px; background-color:#CC5CB8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:1rem;">
                                        <svg class="icon" style="color:white; width:24px; height:24px;">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                                        </svg>
                                    </div>
                                    {{-- <div style="width:50px; height:50px; background-color:#6c757d; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:1rem;">
                                        <svg class="icon" style="color:white; width:24px; height:24px;">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-vpn') }}"></use>
                                        </svg>
                                    </div> --}}
                                    <div>
                                        <h6 class="card-title mb-1" style="color:#212529; font-weight:600; margin:0;">
                                            {{ $permission->name }}</h6>
                                        <small class="text-muted" style="color:#6c757d;">{{ $permission->roles_count ?? 0 }}
                                            {{ ($permission->roles_count ?? 0) == 1 ? 'Rol' : 'Roles' }}</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div style="display:flex; align-items:center;">
                                        <svg class="icon me-2" style="color:#6c757d; width:16px; height:16px;">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use>
                                        </svg>
                                        <span style="color:#6c757d; font-size:0.85rem;">Creado:
                                            {{ $permission->created_at->format('d/m/y') }}</span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if ($permission->roles->count() > 0)
                                            <span class="badge"
                                                style="background-color:#6c757d; color:white; font-size:0.75rem; padding:0.25rem 0.5rem; border-radius:12px;">
                                                {{ $permission->roles->count() }}
                                                {{ $permission->roles->count() == 1 ? 'Rol' : 'Roles' }}
                                            </span>
                                        @else
                                            <span class="badge"
                                                style="background-color:#CC5CB8; color:white; font-size:0.75rem; padding:0.25rem 0.5rem; border-radius:12px;">
                                                Sin roles
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <a href="{{ route('permissions.show', $permission) }}" class="btn btn-sm me-1"
                                            style="background-color:#17a2b8; color:white; border:none; border-radius:6px; padding:0.375rem 0.75rem; font-size:0.8rem; font-weight:500;">
                                            <svg class="icon me-1" style="width:14px; height:14px;">
                                                <use xlink:href="{{ asset('icons/coreui.svg#cil-eye') }}"></use>
                                                <span>ver</span>
                                            </svg>

                                        </a>

                                        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm me-1"
                                            style="background-color:#ffc107; color:white; border:none; border-radius:6px; padding:0.375rem 0.75rem; font-size:0.8rem; font-weight:500;">
                                            <svg class="icon me-1" style="width:14px; height:14px;">
                                                <use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use>
                                            </svg>
                                        </a>

                                        <form method="POST" action="{{ route('permissions.destroy', $permission) }}"
                                            style="display:inline;"
                                            onsubmit="return confirm('¿Estás seguro de eliminar este permiso?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm"
                                                style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:0.375rem 0.75rem; font-size:0.8rem; font-weight:500;">
                                                <svg class="icon me-1" style="width:14px; height:14px;">
                                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
