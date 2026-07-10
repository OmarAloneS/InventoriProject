@extends('dashboard')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <h2 class="mb-0 fw-bold">Editar Usuario: <span class="text-primary">{{ $usuario->name }}</span></h2>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Directiva obligatoria en Laravel para actualizar registros --}}

                        {{-- Nombre Completo --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Correo Electrónico --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Selección de Rol --}}
                        <div class="mb-3">
                            <label for="id_rol" class="form-label fw-bold">Rol del Usuario</label>
                            <select class="form-select @error('id_rol') is-invalid @enderror" id="id_rol" name="id_rol" required>
                                <option value="2" {{ old('id_rol', $usuario->id_rol) == '2' ? 'selected' : '' }}>Empleado</option>
                                <option value="1" {{ old('id_rol', $usuario->id_rol) == '1' ? 'selected' : '' }}>Admin</option>
                                <option value="3" {{ old('id_rol', $usuario->id_rol) == '3' ? 'selected' : '' }}>Superadmin</option>
                            </select>
                            @error('id_rol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sección de Contraseña Opcional --}}
                        <div class="p-3 bg-light rounded-3 mb-3 border">
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-info-circle"></i> Si dejas los siguientes campos en blanco, el usuario conservará su contraseña actual de forma segura.
                            </small>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="password" class="form-label fw-bold small">Nueva Contraseña</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Opcional (mín. 8 caracteres)">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="password_confirmation" class="form-label fw-bold small">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repite la contraseña">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                <i class="fas fa-sync-alt me-2"></i> Actualizar Datos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection