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
    { Schema::create('pagos_subsecuentes', function (Blueprint $table) {
        $table->id(); // Crea una columna 'id' autoincrementable
        $table->foreignId('poliza_id')->constrained('polizas')->onDelete('cascade'); // Relación con polizas
        $table->date('vigencia_inicio'); // Columna para la fecha de inicio de la vigencia
        $table->date('vigencia_fin'); // Columna para la fecha de fin de la vigencia
        $table->decimal('importe', 10, 2); // Columna para el importe con 10 dígitos y 2 decimales
        $table->date('fecha_vencimiento'); // Columna para la fecha de vencimiento
        $table->timestamps(); // Crea las columnas 'created_at' y 'updated_at'
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_subsecuentes');
    }
};
