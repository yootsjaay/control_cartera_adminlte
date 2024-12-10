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
        Schema::create('detalle_poliza', function (Blueprint $table) {
            $table->id();  // Identificador único para el detalle de la póliza
            $table->unsignedBigInteger('id_poliza');  // Relación con la tabla 'polizas'
            $table->unsignedBigInteger('id_tipo_seguro');  // Relación con la tabla 'tipos_seguros'
            $table->string('subtipo_seguro');  // Subtipo del seguro
            $table->text('otros_detalles')->nullable();  // Otros detalles
            $table->timestamps();  // Para llevar el control de fechas
    
            // Agregar las claves foráneas
            $table->foreign('id_poliza')->references('id')->on('polizas')->onDelete('cascade');
            $table->foreign('id_tipo_seguro')->references('id')->on('tipo_seguros')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_poliza');
    }
};
