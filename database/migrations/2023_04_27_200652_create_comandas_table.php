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
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->date('data_solicitud'); 
            $table->string('estat_comanda');
            $table->integer('entrades');
            $table->timestamps();            
            $table->unsignedBigInteger('espai_id');
            $table->foreign('espai_id')
                    ->references('id')
                    ->on('espais')
                    ->onDelete('cascade');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comandas');
    }
};
