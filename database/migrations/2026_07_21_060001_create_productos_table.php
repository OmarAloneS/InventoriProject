<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo_caducidad', ['perecedero', 'no_perecedero', 'semiperecedero'])->default('no_perecedero');
            $table->decimal('precio_usd', 10, 2); // Precio de costo de importación en USD
            $table->decimal('precio_mxn', 10, 2); // Precio calculado en MXN según la API
            $table->integer('stock')->default(0);
            $table->date('fecha_llegada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
