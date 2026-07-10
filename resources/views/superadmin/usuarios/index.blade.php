@extends('dashboard')

@section('content')
<div class="container mt-5">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-6">
            <h2 class="mb-1 fw-bold text-dark">Control de Usuarios</h2>
            <!-- <p class="text-muted small mb-0">Gestión de accesos, roles y credenciales del sistema</p> -->
        </div>
        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-user-plus me-2"></i> Crear Nuevo Usuario
            </a>
        </div>
    </div>

    {{-- Alertas de éxito tras crear, editar o eliminar --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
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
                                        <span class="fw-semibold text-dark">{{ $usuario->name }}</span>
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
                                        {{-- Botón de Editar --}}
                                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-outline-secondary px-3" title="Editar datos del usuario">
                                            <i class="fas fa-edit me-1"></i> Editar
                                        </a>
                                        
                                        {{-- Botón de Eliminar --}}
                                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-3" title="Eliminar usuario" onclick="return confirm('¿Estás completamente seguro de que deseas eliminar a {{ $usuario->name }} del sistema?')">
                                                <i class="fas fa-trash-alt me-1"></i> Eliminar
                                            </button>
                                        </form>
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
                                    <p class="mb-0 small">No hay usuarios dados de alta en la base de datos actualmente.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection