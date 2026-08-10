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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Mi Pointer 2005');
            $table->string('brand')->default('Volkswagen');
            $table->string('model')->default('Pointer');
            $table->unsignedSmallInteger('year')->default(2005);
            $table->decimal('tank_capacity', 8, 2)->default(51.00); // 51 litros oficiales Pointer 2005
            $table->decimal('current_liters', 8, 2)->default(51.00); // 51 litros a tanque lleno
            $table->decimal('avg_consumption', 8, 2)->default(12.50); // 12.5 km/L consumo combinado
            $table->decimal('initial_avg_consumption', 8, 2)->default(12.50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
