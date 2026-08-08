<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Guardará 'USD', 'EUR', 'MXN', etc. Por defecto 'USD' para no romper registros anteriores.
            $table->string('divisa', 10)->default('USD')->after('tipo_caducidad');
            
            // Renombramos de concepto o permitimos que precio_costo_original guarde el valor de la divisa elegida
            $table->decimal('precio_costo_original', 10, 2)->nullable()->after('divisa');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['divisa', 'precio_costo_original']);
        });
    }
};