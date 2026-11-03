<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Producto;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class InventoriIntegrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function integracion_de_autenticacion_y_acceso_al_dashboard()
    {
        // 1. Crear un usuario en la BD de pruebas
        $user = User::factory()->create();

        // 2. Simular sesión activa (integra Middleware auth + Ruta + Vista)
        $response = $this->actingAs($user)->get('/dashboard');

        // 3. Verificar respuesta exitosa integrada
        $response->assertStatus(200);
    }

    #[Test]
    public function integracion_de_restriccion_por_middleware_de_invitados()
    {
        $user = User::factory()->create();

        // Un usuario ya logueado intenta entrar al login (integra middleware 'guest')
        $response = $this->actingAs($user)->get('/login');

        // El sistema debe detectarlo y redirigirlo fuera del login
        $response->assertStatus(302);
    }

    #[Test]
    public function integracion_de_persistencia_de_productos_con_base_de_datos()
    {
        // Integrar la creación del modelo directamente con la base de datos SQLite de pruebas
        $producto = Producto::create([
            'nombre' => 'Pistola Lectora de Códigos',
            'tipo_caducidad' => 'no_perecedero',
            'precio_usd' => 35.00,
            'precio_mxn' => 700.00,
            'stock' => 20,
            'fecha_llegada' => '2026-10-25'
        ]);

        // Verificar que la base de datos relacional almacene e integre el registro correctamente
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Pistola Lectora de Códigos',
            'stock' => 20
        ]);
    }

    #[Test]
    public function integracion_de_seguridad_en_rutas_protegidas_sin_sesion()
    {
        // Intentar acceder al panel de recepción sin iniciar sesión
        $response = $this->get('/admin/recepcion');

        // Debe rechazar la petición y redirigir al login por seguridad
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    #[Test]
    public function integracion_de_consulta_y_existencia_de_vista_de_productos()
    {
        $user = User::factory()->create();

        // Verificar la integración de la ruta /productos que creamos previamente con el usuario autenticado
        $response = $this->actingAs($user)->get('/productos');

        $response->assertStatus(200);
    }
}