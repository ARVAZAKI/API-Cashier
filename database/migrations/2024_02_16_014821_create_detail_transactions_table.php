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
        Schema::create('detail_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_transaksi');
            $table->foreign('id_transaksi')->references('id')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_barang');
            $table->foreign('id_barang')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('jumlah_barang');
            $table->bigInteger('subtotal');
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
        Schema::dropIfExists('detail_transactions');
    }
};
