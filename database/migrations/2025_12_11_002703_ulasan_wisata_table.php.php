<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ulasan_wisata', function (Blueprint $table) {
            $table->bigIncrements('ulasan_id');

            $table->unsignedBigInteger('destinasi_id');
            $table->unsignedBigInteger('warga_id');

            $table->tinyInteger('rating')->default(5); // 1–5
            $table->text('komentar')->nullable();
            $table->timestamp('waktu')->nullable();

            $table->timestamps();

            // RELASI FK
            $table->foreign('destinasi_id')->references('destinasi_id')->on('destinasi_wisata')->onDelete('cascade');
            $table->foreign('warga_id')->references('warga_id')->on('warga')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan_wisata');
    }
};
