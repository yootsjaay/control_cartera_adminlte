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
        Schema::create('sub_tipo_seguros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_seguro_id')->constrained('tipo_seguros')->onDelete('cascade');
            $table->string('nombre', 255); // Nombre del subtipo de seguro
            $table->unique(['nombre', 'tipo_seguro_id']); // Unicidad en combinación de nombre y tipo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_tipo_seguros');
    }
};
