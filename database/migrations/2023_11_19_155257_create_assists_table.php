<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assists', function (Blueprint $table) {
            $table->id();
            $table->date('current_date')->nullable();
            $table->time('hi')->nullable();
            $table->enum('status_entry',['ingreso','tolerancia','tarde','salida','antes de tiempo'])->nullable();
            $table->time('rs')->nullable();
            $table->enum('status_foodout',['ingreso','tolerancia','tarde','salida','antes de tiempo'])->nullable();
            $table->time('ri')->nullable();
            $table->enum('status_foodentry',['ingreso','tolerancia','tarde','salida','antes de tiempo'])->nullable();
            $table->time('hs')->nullable();
            $table->enum('status_out',['ingreso','tolerancia','tarde','salida','antes de tiempo'])->nullable();
            $table->unsignedBigInteger('worker_id');
            $table->foreign('worker_id')->references('id')->on('workers')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assists');
    }
};
