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
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // ID utama untuk transaksi
            $table->dateTime('date_issued')->index(); // Tanggal transaksi, diindex
            $table->string('invoice', 60)->index(); // Nomor invoice, diindex untuk pencarian cepat

            // Kolom yang berhubungan dengan produk, menggunakan foreign key
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict'); // Foreign key untuk products
            $table->integer('quantity'); // Jumlah barang yang terjual

            // Kolom harga dan biaya, lebih baik menggunakan decimal untuk akurasi
            $table->decimal('price', 15, 2); // Harga per unit, menggunakan decimal
            $table->decimal('cost', 15, 2);  // Biaya per unit, menggunakan decimal

            // Kolom yang berhubungan dengan warehouse dan user, menggunakan foreign key
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict'); // Foreign key untuk warehouses
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // Foreign key untuk users

            $table->integer('status')->default(1); // Status penjualan (misal: 1 untuk aktif, 0 untuk dibatalkan)
            $table->timestamps(); // Kolom created_at dan updated_at

            // Anda bisa menambahkan kolom soft delete jika ingin memberikan fitur soft delete
            // $table->softDeletes(); // Opsional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
