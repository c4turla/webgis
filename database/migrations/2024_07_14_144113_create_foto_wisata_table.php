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
        Schema::create('foto_tempat', function (Blueprint $table) {
            $table->id('id_foto');
            $table->foreignId('id_tempat')->constrained('tempat', 'id_tempat')->onDelete('cascade');
            $table->string('nama_file', 255);
            $table->string('deskripsi', 255)->nullable();
            $table->boolean('is_utama')->default(false);
            $table->integer('urutan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_tempat');
    }
};
