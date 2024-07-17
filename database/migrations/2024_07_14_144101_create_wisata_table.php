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
        Schema::create('tempat', function (Blueprint $table) {
            $table->id('id_tempat');
            $table->string('nama_tempat', 100);
            $table->text('deskripsi')->nullable();
            $table->string('alamat', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->foreignId('kategori_id')->constrained('kategori', 'id_kategori');
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->decimal('harga_tiket', 10, 2)->nullable();
            $table->text('fasilitas')->nullable();
            $table->string('kontak', 50)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat');
    }
};
