<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoApiController extends Controller
{
    public function index()
    {
        // Consultamos todos los productos en stock
        $productos = Producto::all();

        // Retornamos la respuesta estandarizada en JSON (Web Service)
        return response()->json([
            'status' => 'success',
            'mensaje' => 'Catálogo de Inventario - API Inventori POS',
            'total_registros' => $productos->count(),
            'data' => $productos
        ], 200);
    }
}