<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Producto;
use PHPUnit\Framework\Attributes\Test;

class EliminarProductoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_producto_puede_ser_eliminado_del_inventario()
    {
        // 1. Crear el producto
        $producto = Producto::create([
            'nombre' => 'Mouse Gamer Inalámbrico',
            'tipo_caducidad' => 'no_perecedero',
            'precio_usd' => 20.00,
            'precio_mxn' => 400.00,
            'stock' => 8,
            'fecha_llegada' => '2026-10-25'
        ]);

        // 2. Eliminar el producto
        $producto->delete();

        // 3. Verificar que ya no exista en la base de datos
        $this->assertDatabaseMissing('productos', [
            'id' => $producto->id,
            'nombre' => 'Mouse Gamer Inalámbrico'
        ]);
    }
}