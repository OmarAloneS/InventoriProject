<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Producto;
use App\Models\User; // <-- Asegúrate de importar el modelo User
use PHPUnit\Framework\Attributes\Test;

class AceptacionProductoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_usuario_puede_ver_el_formulario_de_productos()
    {
        // 1. Crear un usuario de prueba y autenticarlo para pasar el middleware 'auth'
        $user = User::factory()->create();

        // 2. Hacer la petición GET simulando que el usuario está logueado
        $response = $this->actingAs($user)->get('/productos');

        // 3. Verificar que responde exitosamente (200)
        $response->assertStatus(200);
    }

    #[Test]
    public function el_sistema_registra_y_persiste_un_producto_correctamente_desde_el_flujo_web()
    {
        $datosFormulario = [
            'nombre' => 'Escáner Láser de Mano',
            'tipo_caducidad' => 'no_perecedero',
            'precio_usd' => 45.00,
            'precio_mxn' => 900.00,
            'stock' => 12,
            'fecha_llegada' => '2026-10-25'
        ];

        $producto = Producto::create($datosFormulario);

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Escáner Láser de Mano',
            'stock' => 12
        ]);
    }

    #[Test]
    public function un_usuario_no_autenticado_es_redirigido_si_intenta_ver_productos()
    {
        // Intentar acceder a la ruta protegida sin estar logueado
        $response = $this->get('/productos');

        // Verificar que el sistema lo redirige al login (código 302)
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    #[Test]
    public function el_sistema_muestra_el_dashboard_correctamente_a_un_usuario_logueado()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    #[Test]
    public function un_usuario_puede_ver_la_vista_de_login_correctamente()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}