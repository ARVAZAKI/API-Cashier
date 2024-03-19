<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['nama_kategori','id_shop'];
    /**
     * Get all of the item for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function item()
    {
        return $this->hasMany(Item::class, 'id_kategori', 'id');
    }
}
