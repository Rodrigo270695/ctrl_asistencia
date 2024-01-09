<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('detail_horaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pdv_id');
            $table->unsignedBigInteger('horary_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_horaries');
    }

};
