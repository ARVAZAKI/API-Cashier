<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_transaksi',
        'id_barang',
        'jumlah_barang',
        'subtotal',
        'id_shop'
    ];
 
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi', 'id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_barang', 'id');
    }
}
