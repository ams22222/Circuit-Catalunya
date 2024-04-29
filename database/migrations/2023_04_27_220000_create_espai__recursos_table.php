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
        Schema::create('espai__recursos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('espai_id');
            $table->unsignedBigInteger('recurso_id');
            $table->foreign('espai_id')
                ->references('id')
                ->on('espais')
                ->onDelete('cascade');
            $table->foreign('recurso_id')
                ->references('id')
                ->on('recursos')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espai__recursos');
    }
};
