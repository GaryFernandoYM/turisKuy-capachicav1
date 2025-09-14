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
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('visitor_id'); // ID único del visitante
            // Se establece la relación con la tabla 'lugares'
            $table->string('nombre'); // Nombre del visitante
            $table->string('celular'); // Número de celular
            $table->decimal('latitud', 10, 7); // Latitud con precisión
            $table->decimal('longitud', 10, 7); // Longitud con precisión
            $table->string('direccion')->nullable(); // Dirección (puede ser nula)
            $table->foreignId('lugar_id')->nullable()->constrained('lugares')->onDelete('set null');
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ubicaciones'); // Elimina la tabla si se revierte la migración
    }
};
