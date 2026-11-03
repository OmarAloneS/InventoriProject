<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Producto;
use PHPUnit\Framework\Attributes\Test;

class ReglasNegocioStockTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function se_puede_consultar_si_un_producto_tiene_stock_disponible()
    {
        $productoConStock = Producto::create([
            'nombre' => 'Impresora Térmica',
            'tipo_caducidad' => 'no_perecedero',
            'precio_usd' => 80.00,
            'precio_mxn' => 1600.00,
            'stock' => 5,
            'fecha_llegada' => '2026-10-25'
        ]);

        // Aserción lógica de negocio unitaria
        $this->assertTrue($productoConStock->stock > 0, 'El producto debería tener stock disponible.');
    }
}