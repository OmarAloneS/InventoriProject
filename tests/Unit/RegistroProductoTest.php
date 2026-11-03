<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Producto;
use PHPUnit\Framework\Attributes\Test;

class RegistroProductoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_producto_se_registra_correctamente_con_sus_datos_validos()
    {
        // 1. Datos de prueba usando exclusivamente las columnas reales de tu migración
        $datosProducto = [
            'nombre' => 'Lector de Código de Barras USB',
            'tipo_caducidad' => 'no_perecedero',
            'precio_usd' => 50.00,
            'precio_mxn' => 850.00,
            'stock' => 15,
            'fecha_llegada' => '2026-10-25'
        ];

        // 2. Ejecutar la acción de creación
        $producto = Producto::create($datosProducto);

        // 3. Aserciones para verificar el resultado
        $this->assertNotNull($producto, 'El producto no fue creado.');
        $this->assertEquals('Lector de Código de Barras USB', $producto->nombre);
        $this->assertEquals(850.00, $producto->precio_mxn);
        $this->assertEquals(15, $producto->stock);
        
        // Verificar que exista en la base de datos
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Lector de Código de Barras USB',
            'stock' => 15
        ]);
    }

    #[Test]
    public function falla_el_registro_si_falta_un_campo_obligatorio()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Producto::create([
            'stock' => 5
            // Faltan campos obligatorios como precio_usd o precio_mxn para forzar el fallo
        ]);
    }
}