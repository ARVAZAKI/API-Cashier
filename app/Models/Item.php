<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'barcode',
        'id_kategori',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'stock',
        'status',
        'foto_barang',
        'tanggal_input',
        'tanggal_kadaluwarsa',
        'input_by',
        'id_shop'
    ];

   
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id');
    }
   
    public function cart()
    {
        return $this->hasOne(Cart::class, 'id_barang', 'id');
    }
   
    public function transaction()
    {
        return $this->hasManyThrough(Transaction::class, DetailTransaction::class);
    }
}
