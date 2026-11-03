<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecepcionController extends Controller
{
    public function index()
    {
        $tipoCambioMXN = $this->obtenerTipoCambioMXN();

        $productos = Producto::orderBy('created_at', 'desc')->get();

        return view('admin.recepcion', compact('tipoCambioMXN', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_caducidad' => 'required|in:perecedero,no_perecedero,semiperecedero',
            'divisa' => 'required|in:USD,MXN',
            'precio_costo' => 'required|numeric|min:0.01',
            'precio_venta' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:1',
            'fecha_llegada' => 'required|date',
        ]);

        $tipoCambioMXN = $this->obtenerTipoCambioMXN();
        $costoOriginal = $request->precio_costo;
        $divisa = $request->divisa;

        // Calculamos los precios según la divisa seleccionada
        if ($divisa === 'USD') {
            $precioUsd = $costoOriginal;
            $precioMxn = $costoOriginal * $tipoCambioMXN;
        } else { // MXN
            $precioMxn = $costoOriginal;
            $precioUsd = $costoOriginal / $tipoCambioMXN;
        }

        Producto::create([
            'nombre' => $request->nombre,
            'tipo_caducidad' => $request->tipo_caducidad,
            'divisa' => $divisa,
            'precio_costo_original' => $costoOriginal,
            'precio_usd' => round($precioUsd, 2),
            'precio_mxn' => round($precioMxn, 2),
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'fecha_llegada' => $request->fecha_llegada,
        ]);

        return redirect()->back()->with('success', 'Producto registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_caducidad' => 'required|in:perecedero,no_perecedero,semiperecedero',
            'divisa' => 'required|in:USD,MXN',
            'precio_costo' => 'required|numeric|min:0.01',
            'precio_venta' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'fecha_llegada' => 'required|date',
        ]);

        $tipoCambioMXN = $this->obtenerTipoCambioMXN();
        $costoOriginal = $request->precio_costo;
        $divisa = $request->divisa;

        if ($divisa === 'USD') {
            $precioUsd = $costoOriginal;
            $precioMxn = $costoOriginal * $tipoCambioMXN;
        } else { // MXN
            $precioMxn = $costoOriginal;
            $precioUsd = $costoOriginal / $tipoCambioMXN;
        }

        $producto->update([
            'nombre' => $request->nombre,
            'tipo_caducidad' => $request->tipo_caducidad,
            'divisa' => $divisa,
            'precio_costo_original' => $costoOriginal,
            'precio_usd' => round($precioUsd, 2),
            'precio_mxn' => round($precioMxn, 2),
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'fecha_llegada' => $request->fecha_llegada,
        ]);

        return redirect()->back()->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->back()->with('success', 'Producto eliminado correctamente.');
    }

    // --- MÉTODO AUXILIAR ---
    private function obtenerTipoCambioMXN()
    {
        $tipoCambioMXN = 18.00;

        try {
            $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/USD');
            if ($response->successful()) {
                $tipoCambioMXN = $response->json()['rates']['MXN'] ?? 18.00;
            }
        } catch (\Exception $e) {}

        return $tipoCambioMXN;
    }
}