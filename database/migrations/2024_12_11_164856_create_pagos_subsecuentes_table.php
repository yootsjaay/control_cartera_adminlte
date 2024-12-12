<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos_subsecuentes', function (Blueprint $table) {
            // Clave primaria
            $table->id();

            // Relación con la tabla 'polizas'
            $table->unsignedBigInteger('poliza_id'); // Identificador de la póliza
            
            // Información del recibo
            $table->string('numero_recibo', 50)->unique();  // Número de recibo (único)
            $table->date('vigencia_desde');                // Vigencia del recibo desde
            $table->decimal('importe', 10, 2);             // Monto del importe
            $table->date('fecha_limite_pago');             // Fecha límite para realizar el pago

            // Estado del pago
            $table->enum('estado', ['pendiente', 'pagado', 'atrasado'])->default('pendiente');

            // Timestamps
            $table->timestamps();

            // Clave foránea con índice
            $table->foreign('poliza_id')
                  ->references('id')
                  ->on('polizas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_subsecuentes');
    }
};
