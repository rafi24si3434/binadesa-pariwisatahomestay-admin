<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_homestay', function (Blueprint $table) {
            $table->id('booking_id');

            $table->unsignedBigInteger('kamar_id');
            $table->unsignedBigInteger('warga_id');

            $table->date('checkin');
            $table->date('checkout');

            $table->decimal('total', 12, 2)->default(0);
            $table->enum('status', ['pending','dibayar','selesai','batal'])->default('pending');
            $table->enum('metode_bayar', ['cash','transfer'])->nullable();

            $table->timestamps();

            // RELASI
            $table->foreign('kamar_id')->references('kamar_id')->on('kamar_homestay')->onDelete('cascade');
            $table->foreign('warga_id')->references('warga_id')->on('warga')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_homestay');
    }
};
