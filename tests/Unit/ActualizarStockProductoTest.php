<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Producto;
use PHPUnit\Framework\Attributes\Test;

class ActualizarStockProductoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function el_stock_de_un_producto_se_actualiza_correctamente()
    {
        // 1. Crear un producto inicial con stock de 10
        $producto = Producto::create([
            'nombre' => 'Teclado Mecánico RGB',
            'tipo_caducidad' => 'no_perecedero',
            'precio_usd' => 30.00,
            'precio_mxn' => 600.00,
            'stock' => 10,
            'fecha_llegada' => '2026-10-25'
        ]);

        // 2. Simular una actualización de stock (ej. llegaron 5 piezas más, total 15)
        $producto->update(['stock' => 15]);

        // 3. Verificar que el cambio se refleje en la base de datos
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'stock' => 15
        ]);
    }
}