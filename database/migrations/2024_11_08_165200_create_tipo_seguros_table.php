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
        Schema::create('tipo_seguros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255)->unique(); // El nombre debe ser único
            $table->boolean('activo')->default(true); // Estado del tipo de seguro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_seguros');
    }
};
