<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float|null $precio_venta
 */
class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'tipo_caducidad',
        'divisa',
        'precio_costo_original',
        'precio_usd',
        'precio_mxn',
        'precio_venta',
        'stock',
        'fecha_llegada',
    ];
}
