<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Producto;
use PHPUnit\Framework\Attributes\Test;

class CalculoPreciosProductoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function los_precios_en_usd_y_mxn_se_almacenan_con_precision_decimal()
    {
        $producto = Producto::create([
            'nombre' => 'Monitor Curvo 24"',
            'tipo_caducidad' => 'no_perecedero',
            'precio_usd' => 150.50,
            'precio_mxn' => 3010.00,
            'stock' => 4,
            'fecha_llegada' => '2026-10-25'
        ]);

        // Verificar que los decimales se guarden exactamente igual
        $this->assertEquals(150.50, $producto->precio_usd);
        $this->assertEquals(3010.00, $producto->precio_mxn);
    }
}