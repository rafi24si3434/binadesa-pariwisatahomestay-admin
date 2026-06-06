<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('destinasi_wisata', function (Blueprint $table) {
            $table->bigIncrements('destinasi_id');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('alamat');
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('jam_buka')->nullable();
            $table->decimal('tiket', 10, 2)->default(0);
            $table->string('kontak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinasi_wisata');
    }
};
