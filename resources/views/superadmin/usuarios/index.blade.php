@extends('dashboard')

@section('content')
<div class="container mt-5">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-5">
            <h2 class="mb-1 fw-bold text-dark">Control de Usuarios</h2>
        </div>
        
        {{-- SWITCH PARA FILTRAR BLOQUEADOS --}}
        <div class="col-12 col-md-3 text-md-end mt-2 mt-md-0 d-flex align-items-center justify-content-md-end">
            <div class="form-check form-switch bg-light border rounded px-3 py-2 shadow-sm">
                <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" id="toggleBloqueados" {{ $verBloqueados ? 'checked' : '' }} onchange="filtrarBloqueados(this)">
                <label class="form-check-label fw-semibold text-secondary" style="cursor: pointer;" for="toggleBloqueados">
                    <i class="fas fa-eye me-1"></i> Mostrar Bloqueados
                </label>
            </div>
        </div>

        <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-user-plus me-2"></i> Crear Nuevo Usuario
            </a>
        </div>
    </div>

    {{-- Alertas de éxito tras crear, editar, eliminar o procesar ARCO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alertas de información o rechazos --}}
    @if(session('status'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 border-start border-info border-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-2 fs-5"></i>
                <div>{{ session('status') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark text-uppercase fs-7 tracking-wider">
                        <tr>
                            <th class="ps-4 py-3" style="width: 10%;">ID</th>
                            <th class="py-3" style="width: 25%;">Nombre Completo</th>
                            <th class="py-3" style="width: 25%;">Correo Electrónico</th>
                            <th class="py-3" style="width: 20%;">Rol Asignado</th>
                            <th class="text-end pe-4 py-3" style="width: 20%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">#{{ $usuario->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">{{ $usuario->name }}</span>
                                            
                                            {{-- Badges de Estado ARCO bajo el nombre --}}
                                            @if($usuario->status === 'solicitado_arco')
                                                <span class="badge bg-warning text-dark mt-1 align-self-start fw-bold" style="font-size: 0.7rem;">
                                                    ⚠️ ARCO Solicitado
                                                </span>
                                            @elseif($usuario->status === 'bloqueado_arco')
                                                <span class="badge bg-danger text-white mt-1 align-self-start fw-bold" style="font-size: 0.7rem;">
                                                    🚫 Datos Bloqueados
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $usuario->email }}</td>
                                <td>
                                    @if($usuario->id_rol == 3)
                                        <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold">Superadmin</span>
                                    @elseif($usuario->id_rol == 1)
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">Admin</span>
                                    @else
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold">Empleado</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        
                                        @if($usuario->status === 'solicitado_arco')
                                            
                                            {{-- Botón Aprobar --}}
                                            <form action="{{ route('superadmin.arco.aprobar', $usuario->id) }}" method="POST" class="d-inline m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success px-2" title="Aprobar solicitud y bloquear usuario" onclick="return confirm('¿Estás seguro de APROBAR la solicitud ARCO de {{ $usuario->name }}? Su cuenta se bloqueará.')">
                                                    <i class="fas fa-check me-1"></i> Aprobar
                                                </button>
                                            </form>

                                            {{-- Botón / Enlace para Rechazar --}}
                                            <form action="{{ route('superadmin.arco.rechazar', $usuario->id) }}" method="POST" class="d-inline m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2" title="Rechazar solicitud" onclick="return confirm('¿Estás seguro de RECHAZAR la solicitud ARCO de {{ $usuario->name }}?')">
                                                    <i class="fas fa-times me-1"></i> Rechazar
                                                </button>
                                            </form>

                                        @elseif($usuario->status === 'bloqueado_arco')

                                            {{-- BOTÓN REACTIVAR: Unica acción permitida para bloqueados --}}
                                            <form action="{{ route('superadmin.arco.reactivar', $usuario->id) }}" method="POST" class="d-inline m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success px-3 shadow-sm fw-bold" title="Reactivar cuenta del usuario" onclick="return confirm('¿Deseas reactivar la cuenta de {{ $usuario->name }} y restaurar su acceso?')">
                                                    <i class="fas fa-unlock me-1"></i> Reactivar
                                                </button>
                                            </form>

                                        @else
                                            
                                            {{-- Botones estándar de Editar y Eliminar --}}
                                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-outline-secondary px-3" title="Editar datos del usuario">
                                                <i class="fas fa-edit me-1"></i> Editar
                                            </a>
                                            
                                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-3" title="Eliminar usuario" onclick="return confirm('¿Estás completamente seguro de que deseas eliminar a {{ $usuario->name }} del sistema?')">
                                                    <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                </button>
                                            </form>

                                        @endif
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fas fa-users-slash fa-3x text-secondary opacity-50"></i>
                                    </div>
                                    <h5 class="fw-bold">Sin registros</h5>
                                    <p class="mb-0 small">No hay usuarios activos que mostrar en la lista.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Script para recargar la página aplicando el filtro del switch --}}
<script>
function filtrarBloqueados(checkbox) {
    const verBloqueados = checkbox.checked ? 1 : 0;
    // Redirige agregando el parámetro a la URL actual
    window.location.href = "{{ route('usuarios.index') }}?ver_bloqueados=" + verBloqueados;
}
</script>
@endsection