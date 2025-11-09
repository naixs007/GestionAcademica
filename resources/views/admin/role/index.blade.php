@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-4" >
                <div class="card-header " >
                    {{-- style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);" --}}
                    {{-- style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;" --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 style="margin:0; font-weight:600;">{{ __('Gestión de Roles') }}</h5>
                            <small style="opacity:0.9;">Administra los roles del sistema</small>
                        </div>
                        <a href="{{ route('roles.create') }}" class="btn"
                           style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use>
                            </svg>
                            Crear Rol
                        </a>
                    </div>
                </div>

                <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert" style="border:none; border-radius:8px; background-color:#f8d7da; color:#721c24; border-left:4px solid #dc3545;">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($roles->count() > 0)
                        <div class="row">
                            @foreach($roles as $role)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease;"
                                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(204,92,184,0.15)'"
                                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.08)'">

                                        <div class="card-body" style="padding:1.5rem;">
                                            <div class="d-flex align-items-center mb-3">
                                                <div style="width:45px; height:45px; background-color:#CC5CB8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:1rem;">
                                                    <svg class="icon" style="color:white; width:24px; height:24px;">
                                                        {{--cil-badge  user-plus --}}
                                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-badge') }}"></use>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 style="margin:0; color:#212529; font-weight:600;">{{ ucfirst($role->name) }}</h6>
                                                    <small style="color:#6c757d;">{{ $role->users_count }} usuario(s)</small>
                                                </div>
                                            </div>

                                            <div style="background-color:#f8f9fa; border-radius:8px; padding:1rem; margin-bottom:1rem;">
                                                <small style="color:#495057; font-weight:500;">Permisos asignados:</small>
                                                <div class="mt-2">
                                                    @if($role->permissions_count > 0)
                                                        <span class="badge rounded-pill" style="background-color:#CC5CB8; color:white; font-size:0.75rem;">{{ $role->permissions_count }} permisos</span>
                                                    @else
                                                        <span class="badge rounded-pill" style="background-color:#6c757d; color:white; font-size:0.75rem;">Sin permisos</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1rem 1.5rem;">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('roles.show', $role) }}" class="btn btn-sm"
                                                   style="background-color:#17a2b8; color:white; border:none; border-radius:6px; padding:6px 12px; font-size:0.875rem;">
                                                    <svg class="icon" style="width:14px; height:14px;">
                                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-eye') }}"></use>
                                                        <span>ver</span>
                                                    </svg>
                                                </a>

                                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm"
                                                   style="background-color:#ffc107; color:#212529; border:none; border-radius:6px; padding:6px 12px; font-size:0.875rem;">
                                                    <svg class="icon" style="width:14px; height:14px;">
                                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use>
                                                    </svg>
                                                </a>

                                                <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                                      style="display:inline-block;"
                                                      onsubmit="return confirm('¿Estás seguro de que quieres eliminar este rol?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm"
                                                            style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:6px 12px; font-size:0.875rem;">
                                                        <svg class="icon" style="width:14px; height:14px;">
                                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div style="width:80px; height:80px; background-color:#e9ecef; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem;">
                                <svg class="icon" style="color:#6c757d; width:40px; height:40px;">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-badge') }}"></use>
                                </svg>
                            </div>
                            <h5 style="color:#495057; margin-bottom:1rem;">No hay roles registrados</h5>
                            <p style="color:#6c757d; margin-bottom:1.5rem;">Comienza creando un nuevo rol para tu sistema</p>
                            <a href="{{ route('roles.create') }}" class="btn"
                               style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:10px 20px; font-weight:500;">
                                <svg class="icon me-2">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use>
                                </svg>
                                Crear primer rol
                            </a>
                        </div>
                    @endif
                </div>

                @if($roles->count() > 0)
                    <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                        <style>
                            .pagination .page-link {
                                color: #662a5b;
                                border-color: #662a5b;
                                background-color: white;
                                border-radius: 8px;
                                margin: 0 2px;
                                padding: 0.5rem 0.75rem;
                                font-weight: 500;
                                transition: all 0.2s;
                            }
                            .pagination .page-link:hover {
                                color: white;
                                background-color: #662a5b;
                                border-color: #662a5b;
                                transform: translateY(-1px);
                            }
                            .pagination .page-item.active .page-link {
                                color: white;
                                background-color: #662a5b;
                                border-color: #662a5b;
                                box-shadow: 0 2px 8px rgba(102, 42, 91, 0.3);
                            }
                            .pagination .page-item.disabled .page-link {
                                color: #6c757d;
                                background-color: #f8f9fa;
                                border-color: #dee2e6;
                            }
                        </style>
                        {{ $roles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
