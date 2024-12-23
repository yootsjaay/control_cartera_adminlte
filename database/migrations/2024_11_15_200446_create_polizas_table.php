<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polizas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_poliza', 100)->unique();
            $table->date('vigencia_inicio');
            $table->date('vigencia_fin');
            $table->string('forma_pago', 50);
            $table->decimal('total_a_pagar', 10, 2);
            $table->string('archivo_pdf', 255)->nullable();
            $table->enum('status', ['activa', 'vencida', 'cancelada'])->default('activa');

            $table->timestamps();

            // Relación con otras tablas
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('compania_id')->constrained('companias')->onDelete('cascade');
            $table->foreignId('agente_id')->constrained('agentes')->onDelete('cascade');
            $table->foreignId('tipo_seguro_id')->constrained('tipo_seguros')->onDelete('cascade');

            // Índices para consultas frecuentes
            $table->index(['vigencia_inicio', 'vigencia_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polizas');
    }
};

