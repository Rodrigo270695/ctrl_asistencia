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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('reason_id');
            $table->foreign('reason_id')->references('id')->on('reasons')->onUpdate('cascade')->onDelete('cascade');
            $table->string('file', 2048)->nullable();
            $table->enum('status', ['POR APROBAR','APROBADO','RECHAZADO','OBSERVADO'])->default('POR APROBAR');
            $table->text('status_detail')->nullable();
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
        Schema::dropIfExists('absences');
    }
};
