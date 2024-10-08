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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname');
            $table->integer('phone');
            $table->string('email')->nullable();
            $table->enum('response', ['asistire', 'no asistire']);
            $table->integer('number_of_people')->default(1); // Número total de personas
            $table->text('memory_text')->nullable();
            $table->string('memory_file')->nullable();
            $table->enum('dish', ['pollo', 'cerdo', 'res'])->nullable();
            $table->string('special_peticion')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
