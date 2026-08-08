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
        Schema::table('users', function (Blueprint $table) {
            // Estados posibles: 'activo', 'solicitado_arco', 'deshabilitado'
            $table->string('status')->default('activo')->after('id_rol'); 
            $table->timestamp('arco_requested_at')->nullable()->after('status');
            $table->timestamp('arco_resolved_at')->nullable()->after('arco_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'arco_requested_at', 'arco_resolved_at']);
        });
    }
};