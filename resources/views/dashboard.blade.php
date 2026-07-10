<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inventori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v=10.0">
</head>
<body class="bg-light" style="margin: 0; padding: 0; display: block !important;">

    <header class="bg-dark text-white shadow-sm" style="width: 100% !important; display: block !important;">
        <div class="container-fluid px-4 py-3">
            <div class="d-flex align-items-center justify-content-between">
                
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-box-open text-white fs-4 me-1"></i>
                    <span class="fw-bold fs-5 m-0" style="letter-spacing: 0.5px;">Inventori POS</span>
                </div>

                <div>
                    <!-- Menú Desplegable del Perfil -->
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle d-flex align-items-center gap-2 border-0 shadow-none" type="button" id="dropdownPerfil" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 35px; height: 35px;">
                                <i class="fas fa-user fs-6"></i>
                            </div>
                            <span class="small d-none d-md-inline text-white">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="dropdownPerfil">
                            <li>
                                <h6 class="dropdown-header text-muted small">Mi Cuenta</h6>
                            </li>
                            <!-- OPCIÓN: Revisar Aviso de Privacidad -->
                            <li>
                                <button type="button" class="dropdown-item py-2 small" data-bs-toggle="modal" data-bs-target="#avisoConsultarModal">
                                    <i class="fas fa-shield-alt text-muted me-2"></i> Revisar Aviso de Privacidad
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <!-- OPCIÓN: Cerrar Sesión -->
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 small text-danger fw-semibold">
                                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <main class="w-100 container-xl my-4 px-3" style="display: block !important; clear: both !important;">
        
        @if(Request::is('dashboard'))
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-md-6 text-center">
                    <div class="card shadow-sm border-0 p-5 rounded-3 bg-white">
                        <h2 class="fw-bold mb-3">¡Bienvenido al Sistema!</h2>
                        <p class="text-muted small">Has ingresado al sistema de manera segura de forma exitosa.</p>
                        
                        @if(Auth::user()->id_rol == 3)
                            <a href="{{ route('usuarios.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-users-cog me-1"></i> Ir al Panel de Usuarios
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="table-responsive" style="border: none; padding: 0;">
                @yield('content')
            </div>
        @endif

    </main>

    <!-- ======================================================================================== -->
    <!-- DE AQUÍ EN ADELANTE ES LO ÚNICO QUE SE AGREGA (MODAL Y LÓGICA DE AVISO DE PRIVACIDAD) -->
    <!-- ======================================================================================== -->
    @if(Auth::check() && Auth::user()->acepto_aviso == 0)
    <div class="modal fade" id="avisoPrivacidadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-shield-alt me-2"></i> Aviso de Privacidad Importante</h5>
                </div>
                <div class="modal-body">
                    <p class="text-dark fw-semibold">Para continuar utilizando Inventori POS, es necesario que lea y acepte nuestro aviso de privacidad de conformidad con la LFPDPPP.</p>
                    
                    <div id="textoAviso" class="privacy-scroll-box p-3 border rounded text-muted small bg-light">
                        <h6 class="text-dark fw-bold mb-2">AVISO DE PRIVACIDAD SIMPLIFICADO</h6>
                        <p><strong>Inventori POS</strong>, con domicilio en la Universidad Tecnológica de Aguascalientes, es responsable del tratamiento de sus datos personales. Sus datos de identificación (Nombre) y de contacto (Correo electrónico) serán recolectados y tratados única y exclusivamente con las finalidades de:</p>
                        <ol>
                            <li>Gestionar el control de accesos, roles y credenciales de seguridad dentro del sistema operativo.</li>
                            <li>Monitorear las actividades operativas del catálogo de usuarios asignados.</li>
                        </ol>
                        <p>De manera adicional, le informamos que sus datos no serán transferidos a terceros bajo ninguna circunstancia ajena a las obligaciones legales correspondientes, ni serán utilizados para fines mercadotécnicos o publicitarios de carácter comercial.</p>
                        <p>Usted podrá ejercer en cualquier momento sus derechos <strong>ARCO</strong> (Acceso, Rectificación, Cancelación y Oposición), así como revocar su consentimiento otorgado directamente desde el panel de configuración de perfil dentro del sistema una vez habilitado su acceso.</p>
                        <p class="fw-bold text-center text-dark mt-3">POR FAVOR, BAJE HASTA EL FINAL DE ESTE TEXTO PARA ACTIVAR LA CASILLA DE ACEPTACIÓN.</p>
                    </div>
                    <!-- Formulario de Envío -->
                    <form action="{{ route('aviso.aceptar') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="form-check d-flex align-items-center gap-2 mb-3">
                            <input class="form-check-input" type="checkbox" id="checkboxAviso" name="aceptar" disabled required>
                            <label class="form-check-label small text-dark fw-semibold" for="checkboxAviso">
                                He leído y acepto los términos del Aviso de Privacidad.
                            </label>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <!-- BOTÓN RECHAZAR: Envía el formulario de cerrar sesión existente -->
                            <button type="button" class="btn btn-outline-danger px-3 shadow-sm" onclick="event.preventDefault(); document.getElementById('logout-form-modal').submit();">
                                No aceptar y Salir
                            </button>
                            
                            <!-- BOTÓN CONFIRMAR -->
                            <button type="submit" id="btnEnviarAviso" class="btn btn-primary px-4 shadow-sm" disabled>
                                Confirmar y Continuar
                            </button>
                        </div>
                    </form>

                    <!-- Formulario invisible auxiliar para hacer el Logout si rechaza -->
                    <form id="logout-form-modal" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL PARA CONSULTAR EL AVISO VOLUNTARIAMENTE DESDE EL PERFIL -->
    <div class="modal fade" id="avisoConsultarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-shield-alt me-2"></i> Consulta de Aviso de Privacidad</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="privacy-scroll-box p-3 border rounded text-muted small bg-light mb-3">
                        <h6 class="text-dark fw-bold mb-2">AVISO DE PRIVACIDAD SIMPLIFICADO</h6>
                        <p><strong>Inventori POS</strong>, con domicilio en la Universidad Tecnológica de Aguascalientes, es responsable del tratamiento de sus datos personales. Sus datos de identificación (Nombre) y de contacto (Correo electrónico) serán recolectados y tratados única y exclusivamente con las finalidades de:</p>
                        <ol>
                            <li>Gestionar el control de accesos, roles y credenciales de seguridad dentro del sistema operativo.</li>
                            <li>Monitorear las actividades operativas del catálogo de usuarios asignados.</li>
                        </ol>
                        <p>De manera adicional, le informamos que sus datos no serán transferidos a terceros bajo ninguna circunstancia ajena a las obligaciones legales correspondientes.</p>
                        <p>Usted podrá ejercer en cualquier momento sus derechos <strong>ARCO</strong> (Acceso, Rectificación, Cancelación y Oposición), así como revocar su consentimiento otorgado directamente desde este panel presionando el botón de revocación.</p>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary px-4 btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if(Auth::check() && Auth::user()->acepto_aviso == 0)
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('avisoPrivacidadModal'));
            myModal.show();

            var textoAviso = document.getElementById('textoAviso');
            var checkboxAviso = document.getElementById('checkboxAviso');
            var btnEnviarAviso = document.getElementById('btnEnviarAviso');

            // Detectar cuando el usuario llega al fondo del scroll
            textoAviso.addEventListener('scroll', function() {
                var totalScroll = textoAviso.scrollHeight - textoAviso.clientHeight;
                if (textoAviso.scrollTop >= totalScroll - 5) {
                    checkboxAviso.removeAttribute('disabled');
                }
            });

            // Habilitar el botón de envío solo si el checkbox está marcado
            checkboxAviso.addEventListener('change', function() {
                if (this.checked) {
                    btnEnviarAviso.removeAttribute('disabled');
                } else {
                    btnEnviarAviso.setAttribute('disabled', 'true');
                }
            });
        });
    </script>
    @endif
</body>
</html>