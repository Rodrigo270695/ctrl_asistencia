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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('name',30);
            $table->string('lastname',30);
            $table->enum('document_type',['DNI','CARNET DE EXTRANJERIA','OTROS']);
            $table->string('num_document',15)->unique();
            $table->date('entry_date');
            $table->string('charge', 100);
            $table->string('phone', 11)->nullable();
            $table->string('email',0)->nullable();
            $table->string('address',255)->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('pdv_id');
            $table->foreign('pdv_id')->references('id')->on('pdvs')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
