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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento 1:N (Chave Estrangeira do Usuário)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Dados informativos do pacote
            $table->string('product_name');
            $table->string('tracking_code')->unique();
            
            // Endereços textuais digitados pelo usuário
            $table->string('origin_address');
            $table->string('destination_address');
            
            // Coordenadas calculadas pela API
            $table->double('latitude_origem', 10, 6)->nullable();
            $table->double('longitude_origem', 10, 6)->nullable();
            $table->double('latitude_destino', 10, 6)->nullable();
            $table->double('longitude_destino', 10, 6)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};