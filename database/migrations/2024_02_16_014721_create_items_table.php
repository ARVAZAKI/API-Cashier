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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->nullable();
            $table->unsignedBigInteger('id_kategori'); // relasi ke table kategori
            $table->foreign('id_kategori')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_barang');
            $table->bigInteger('harga_beli');
            $table->bigInteger('harga_jual');
            $table->bigInteger('stock');
            $table->string('status');
            $table->string('foto_barang')->nullable();
            $table->date('tanggal_input');
            $table->date('tanggal_kadaluwarsa')->nullable();
            $table->unsignedBigInteger('id_supplier')->nullable(); // relasi ke table supplier
            $table->foreign('id_supplier')->references('id')->on('supplier')->onDelete('cascade')->onUpdate('cascade');
            $table->string('input_by'); 
            $table->unsignedBigInteger('id_shop');
            $table->foreign('id_shop')->references('id')->on('shops')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
