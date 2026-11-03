<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('nombre', 'asc')->get();
        return view('empleado.pos', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalVenta = 0;
            $detallesAInsertar = [];

            // 1. Validar stock, descontar inventario y calcular total
            foreach ($request->items as $item) {
                $producto = Producto::lockForUpdate()->find($item['id']);

                if ($producto->stock < $item['cantidad']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false, 
                        'message' => "Stock insuficiente para: {$producto->nombre}"
                    ], 400);
                }

                $subtotal = $producto->precio_venta * $item['cantidad'];
                $totalVenta += $subtotal;

                // Descontar stock
                $producto->stock -= $item['cantidad'];
                $producto->save();

                $detallesAInsertar[] = [
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio_venta,
                    'subtotal' => $subtotal,
                ];
            }

            // 2. Crear cabecera de la Venta
            $venta = Venta::create([
                'user_id' => Auth::id(),
                'total' => $totalVenta,
            ]);

            // 3. Crear el detalle de la compra
            foreach ($detallesAInsertar as $detalle) {
                $venta->detalles()->create($detalle);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '¡Venta registrada con éxito!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 500);
        }
    }
}