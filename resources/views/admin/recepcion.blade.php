@extends('dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/stylesAdmin.css') }}">
@endpush

@section('content')
<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Recepción de Mercancía e Inventario</h2>
        
        {{-- TARJETA DEL CONSUMO DE WEB SERVICE EN TIEMPO REAL --}}
        <div class="card border-primary bg-light shadow-sm card-webservice">
            <div class="card-body py-2 px-3 text-center">
                <span class="fs-6 fw-bold text-dark">Tipo de Cambio Hoy (USD/MXN):</span>
                <h4 class="mb-0 text-primary fw-bold">$ {{ number_format($tipoCambioMXN, 2) }} MXN</h4>
            </div>
        </div>
    </div>

    {{-- ALERTAS DE ÉXITO Y ERRORES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Formulario de Registro de Recepción --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Registrar Llegada de Producto</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.recepcion.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre del Producto</label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Ej. Papas Sabritas 45g">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Caducidad</label>
                            <select name="tipo_caducidad" class="form-select" required>
                                <option value="perecedero">Perecedero</option>
                                <option value="semiperecedero">Semiperecedero</option>
                                <option value="no_perecedero" selected>No Perecedero</option>
                            </select>
                        </div>

                        {{-- INPUT CON SELECTOR DE DIVISA (USD / MXN) --}}
                        <div class="mb-3">
                            <label class="form-label">Precio de Costo</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="precio_costo" id="precio_costo" class="form-control" required placeholder="0.00" min="0.01">
                                <select name="divisa" id="divisa_select" class="form-select bg-light" style="max-width: 95px;" required>
                                    <option value="USD" selected>USD</option>
                                    <option value="MXN">MXN</option>
                                </select>
                            </div>
                            <small class="text-muted conversion-preview" id="conversion_preview">Equivalente aprox: $0.00 MXN</small>
                        </div>

                        <div class="mb-3">
                            <label for="precio_venta" class="form-label font-weight-bold">Precio de Venta al Público ($MXN)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="precio_venta" name="precio_venta" placeholder="Ej. 20.00" min="0.01" required>
                                <span class="input-group-text">MXN</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cantidad Recibida (Stock)</label>
                            <input type="number" name="stock" class="form-control" min="1" required placeholder="Ej. 50">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha de Llegada</label>
                            <input type="date" name="fecha_llegada" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Guardar Recepción</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabla de Productos Recibidos --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">Histórico de Recepción</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th>Caducidad</th>
                                    <th>Costo (USD)</th>
                                    <th>Costo (MXN)</th>
                                    <th>Precio de Venta ($MXN)</th>
                                    <th>Stock</th>
                                    <th>Fecha Entrada</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productos as $producto)
                                    <tr>
                                        <td class="fw-bold">{{ $producto->nombre }}</td>
                                        <td>
                                            <span class="badge bg-info text-dark">{{ ucfirst(str_replace('_', ' ', $producto->tipo_caducidad)) }}</span>
                                        </td>
                                        <td>$ {{ number_format($producto->precio_usd, 2) }} USD</td>
                                        <td class="text-success fw-bold">$ {{ number_format($producto->precio_mxn, 2) }} MXN</td>
                                        <td class="text-primary fw-bold">$ {{ number_format($producto->precio_venta, 2) }} MXN</td>
                                        <td><span class="badge bg-secondary">{{ $producto->stock }} unids.</span></td>
                                        <td>{{ $producto->fecha_llegada }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <!-- Botón Editar -->
                                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $producto->id }}" title="Editar">
                                                    ✏️
                                                </button>
                                                <!-- Botón Eliminar -->
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $producto->id }}" title="Eliminar">
                                                    🗑️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- MODAL PARA EDITAR PRODUCTO --}}
                                    <div class="modal fade" id="editModal{{ $producto->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.recepcion.update', $producto->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title fw-bold">Editar Producto: {{ $producto->nombre }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Nombre del Producto</label>
                                                            <input type="text" name="nombre" class="form-control" value="{{ $producto->nombre }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Tipo de Caducidad</label>
                                                            <select name="tipo_caducidad" class="form-select" required>
                                                                <option value="perecedero" {{ $producto->tipo_caducidad == 'perecedero' ? 'selected' : '' }}>Perecedero</option>
                                                                <option value="semiperecedero" {{ $producto->tipo_caducidad == 'semiperecedero' ? 'selected' : '' }}>Semiperecedero</option>
                                                                <option value="no_perecedero" {{ $producto->tipo_caducidad == 'no_perecedero' ? 'selected' : '' }}>No Perecedero</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Precio de Costo Original</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">$</span>
                                                                <input type="number" step="0.01" min="0.01" name="precio_costo" class="form-control" value="{{ $producto->precio_costo_original ?? $producto->precio_usd }}" required>
                                                                <select name="divisa" class="form-select bg-light" style="max-width: 95px;" required>
                                                                    <option value="USD" {{ ($producto->divisa ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                                                    <option value="MXN" {{ ($producto->divisa ?? '') == 'MXN' ? 'selected' : '' }}>MXN</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Precio Venta ($MXN)</label>
                                                            <input type="number" step="0.01" min="0.01" name="precio_venta" class="form-control" value="{{ $producto->precio_venta }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Stock</label>
                                                            <input type="number" name="stock" class="form-control" value="{{ $producto->stock }}" min="0" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Fecha de Llegada</label>
                                                            <input type="date" name="fecha_llegada" class="form-control" value="{{ $producto->fecha_llegada }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-warning fw-bold">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- MODAL PARA ELIMINAR PRODUCTO --}}
                                    <div class="modal fade" id="deleteModal{{ $producto->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.recepcion.destroy', $producto->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title fw-bold">Confirmar Eliminación</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <p class="mb-0">¿Estás seguro de que deseas eliminar el producto <strong>{{ $producto->nombre }}</strong>?</p>
                                                        <small class="text-danger">Esta acción no se puede deshacer.</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger fw-bold">Sí, Eliminar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-3 text-muted">No hay registros de recepción de mercancía.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const tipoCambio = {{ $tipoCambioMXN }};
    const inputCosto = document.getElementById('precio_costo');
    const selectDivisa = document.getElementById('divisa_select');
    const previewMxn = document.getElementById('conversion_preview');

    function actualizarPreview() {
        const monto = parseFloat(inputCosto.value) || 0;
        const divisa = selectDivisa.value;

        if (divisa === 'USD') {
            const mxn = monto * tipoCambio;
            previewMxn.innerText = 'Equivalente aprox: $' + mxn.toFixed(2) + ' MXN';
        } else {
            const usd = monto / tipoCambio;
            previewMxn.innerText = 'Equivalente aprox: $' + usd.toFixed(2) + ' USD';
        }
    }

    inputCosto.addEventListener('input', actualizarPreview);
    selectDivisa.addEventListener('change', actualizarPreview);
</script>
@endsection