<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar_homestay', function (Blueprint $table) {
            $table->bigIncrements('kamar_id');
            $table->unsignedBigInteger('homestay_id');

            $table->string('nama_kamar');
            $table->integer('kapasitas')->default(1);
            $table->json('fasilitas_json')->nullable();
            $table->decimal('harga', 12, 2);

            $table->timestamps();

            // Foreign key relasi ke homestay
            $table->foreign('homestay_id')
                  ->references('homestay_id')
                  ->on('homestay')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar_homestay');
    }
};
