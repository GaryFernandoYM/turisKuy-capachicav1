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
        Schema::create('lugares', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('latitud', 10, 6);
            $table->decimal('longitud', 10, 6);
            $table->integer('radio_metros')->default(150); // 👉 AGREGADO
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('region')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lugares'); // <<< CORREGIDO
    }
};
