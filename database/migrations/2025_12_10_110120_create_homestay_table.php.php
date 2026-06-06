<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homestay', function (Blueprint $table) {
            $table->bigIncrements('homestay_id');

            $table->unsignedBigInteger('pemilik_warga_id'); // FK ke warga
            $table->string('nama');
            $table->string('alamat');
            $table->integer('rt')->nullable();
            $table->integer('rw')->nullable();

            $table->json('fasilitas_json')->nullable(); // fasilitas
            $table->decimal('harga_per_malam', 12, 2)->default(0);
            $table->string('status')->default('tersedia'); // tersedia / penuh / maintenance

            $table->timestamps();

            // Relasi FK
            $table->foreign('pemilik_warga_id')
                  ->references('warga_id')
                  ->on('warga')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homestay');
    }
};
