<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ramos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del ramo (Ej. Transporte, Casa, etc.)
            $table->enum('periodicidad', ['semestral', 'anual', 'mensual']); // Periodicidad (semestre, año, mes)
            $table->foreignId('seguro_id')->constrained('seguros')->onDelete('cascade'); // Relación con el seguro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramos');
    }
};
