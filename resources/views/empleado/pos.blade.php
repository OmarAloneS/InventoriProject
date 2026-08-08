@extends('dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/stylesEmpleado.css') }}?v={{ time() }}">
@endpush

@section('content')
{{-- CSRF Token cargado correctamente dentro del contenido --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Estilos de respaldo en caso de que dashboard.blade.php no tenga @stack('styles') --}}
<link rel="stylesheet" href="{{ asset('css/stylesEmpleado.css') }}?v={{ time() }}">

<div class="container-fluid py-3 pos-container">
    <div class="row h-100">
        
        {{-- SECCIÓN 3/4: CATÁLOGO Y BUSCADOR --}}
        <div class="col-md-8 border-end pe-4">
            {{-- Buscador superior --}}
            <div class="mb-3">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white">🔍</span>
                    <input type="text" id="search-input" class="form-control" placeholder="Buscar producto por nombre..." autocomplete="off">
                </div>
            </div>

            {{-- Grid de Productos (3/4) --}}
            <div class="row g-3 product-grid p-1" id="product-list">
                @foreach($productos as $prod)
                    <div class="col-md-4 col-sm-6 product-item" data-nombre="{{ strtolower($prod->nombre) }}">
                        <div class="card h-100 shadow-sm product-card {{ $prod->stock <= 0 ? 'disabled' : '' }}"
                             onclick="agregarAlTicket({{ json_encode($prod) }})">
                            <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                                <div>
                                    <span class="badge bg-secondary mb-2">{{ ucfirst($prod->tipo_caducidad) }}</span>
                                    <h6 class="card-title fw-bold text-dark mb-2">{{ $prod->nombre }}</h6>
                                </div>
                                <div>
                                    <h5 class="text-primary fw-bold mb-0">$ {{ number_format($prod->precio_venta, 2) }} MXN</h5>
                                    @if($prod->stock <= 0)
                                        <span class="badge bg-danger mt-1">Agotado</span>
                                    @else
                                        <small class="text-muted d-block mt-1">Stock: {{ $prod->stock }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- SECCIÓN 1/4: RESUMEN DE COMPRA / TICKET --}}
        <div class="col-md-4 ps-3">
            <div class="card shadow-sm ticket-container">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fs-6">Venta Actual</h5>
                    <button class="btn btn-sm btn-outline-light" onclick="vaciarTicket()">Limpiar</button>
                </div>

                {{-- Lista de items agregados --}}
                <div class="card-body p-2 ticket-items">
                    <ul class="list-group list-group-flush" id="ticket-list">
                        <li class="list-group-item text-center text-muted py-4" id="empty-ticket-msg">
                            Selecciona productos de la lista para vender
                        </li>
                    </ul>
                </div>

                {{-- Pie del Ticket: Total y Botón de Pago --}}
                <div class="card-footer bg-white border-top p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fs-5 fw-bold">Total:</span>
                        <span class="fs-4 fw-bold text-success" id="ticket-total">$0.00 MXN</span>
                    </div>
                    <button class="btn btn-success btn-lg w-100 fw-bold" id="btn-pagar" disabled onclick="procesarVenta()">
                        PAGAR
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    let carrito = [];

    // Buscador interactivo en tiempo real
    document.getElementById('search-input').addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const items = document.querySelectorAll('.product-item');

        items.forEach(item => {
            const nombre = item.getAttribute('data-nombre');
            if (nombre.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Agregar producto al ticket
    function agregarAlTicket(producto) {
        if (producto.stock <= 0) return;

        const existe = carrito.find(item => item.id === producto.id);

        if (existe) {
            if (existe.cantidad < producto.stock) {
                existe.cantidad++;
            } else {
                alert(`Límite alcanzado: solo hay ${producto.stock} unidades disponibles de ${producto.nombre}.`);
                return;
            }
        } else {
            carrito.push({
                id: producto.id,
                nombre: producto.nombre,
                precio: parseFloat(producto.precio_venta),
                cantidad: 1,
                maxStock: parseInt(producto.stock)
            });
        }

        renderizarTicket();
    }

    // Cambiar cantidad con los botones [+] y [-]
    function cambiarCantidad(index, cambio) {
        const item = carrito[index];
        const nuevaCantidad = item.cantidad + cambio;

        if (nuevaCantidad <= 0) {
            quitarDelTicket(index);
            return;
        }

        if (nuevaCantidad > item.maxStock) {
            alert(`Stock máximo alcanzado (${item.maxStock} disponibles).`);
            item.cantidad = item.maxStock;
        } else {
            item.cantidad = nuevaCantidad;
        }

        renderizarTicket();
    }

    // Cambiar cantidad escribiendo directo en el input
    function actualizarCantidadManual(index, valorInput) {
        let valor = parseInt(valorInput);
        const item = carrito[index];

        if (isNaN(valor) || valor <= 0) {
            item.cantidad = 1;
        } else if (valor > item.maxStock) {
            item.cantidad = item.maxStock;
        } else {
            item.cantidad = valor;
        }

        renderizarTicket();
    }

    // Dibujar lista del ticket
    function renderizarTicket() {
        const ticketList = document.getElementById('ticket-list');
        const totalEl = document.getElementById('ticket-total');
        const btnPagar = document.getElementById('btn-pagar');

        ticketList.innerHTML = '';

        if (carrito.length === 0) {
            ticketList.innerHTML = `<li class="list-group-item text-center text-muted py-4" id="empty-ticket-msg">Selecciona productos de la lista para vender</li>`;
            totalEl.innerText = '$0.00 MXN';
            btnPagar.disabled = true;
            return;
        }

        let total = 0;

        carrito.forEach((item, index) => {
            const subtotal = item.precio * item.cantidad;
            total += subtotal;

            const li = document.createElement('li');
            li.className = 'list-group-item px-2 py-2 pos-ticket-item';
            
            li.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="pos-item-name" title="${item.nombre}">${item.nombre}</strong>
                    <span class="fw-bold text-dark fs-6">$${subtotal.toFixed(2)}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <small class="text-muted text-nowrap">$${item.precio.toFixed(2)} c/u</small>
                    
                    <div class="pos-qty-container">
                        <button type="button" class="btn-qty btn-qty-minus" onclick="cambiarCantidad(${index}, -1)">-</button>
                        <input type="number" class="pos-qty-input" value="${item.cantidad}" min="1" max="${item.maxStock}" onchange="actualizarCantidadManual(${index}, this.value)">
                        <button type="button" class="btn-qty btn-qty-plus" onclick="cambiarCantidad(${index}, 1)">+</button>
                        <button type="button" class="btn-delete-item ms-2" onclick="quitarDelTicket(${index})" title="Eliminar">🗑️</button>
                    </div>
                </div>
            `;
            ticketList.appendChild(li);
        });

        totalEl.innerText = `$${total.toFixed(2)} MXN`;
        btnPagar.disabled = false;
    }

    function quitarDelTicket(index) {
        carrito.splice(index, 1);
        renderizarTicket();
    }

    function vaciarTicket() {
        carrito = [];
        renderizarTicket();
    }

    function procesarVenta() {
        if (carrito.length === 0) return;

        const btnPagar = document.getElementById('btn-pagar');
        btnPagar.disabled = true;

        fetch("{{ route('empleado.pos.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                items: carrito.map(item => ({
                    id: item.id,
                    cantidad: item.cantidad
                }))
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                vaciarTicket();
                window.location.reload();
            } else {
                alert('❌ ' + data.message);
                btnPagar.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error inesperado al procesar la venta.');
            btnPagar.disabled = false;
        });
    }
</script>
@endsection