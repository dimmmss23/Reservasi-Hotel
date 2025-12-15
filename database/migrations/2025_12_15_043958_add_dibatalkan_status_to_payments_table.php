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
        // Ubah kolom enum status untuk menambahkan 'dibatalkan'
        \DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'menunggu_validasi', 'diterima', 'ditolak', 'dibatalkan') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum status sebelumnya
        \DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'menunggu_validasi', 'diterima', 'ditolak') DEFAULT 'pending'");
    }
};
