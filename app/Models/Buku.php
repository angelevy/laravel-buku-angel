<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku-api';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'author',
        'email',
        'image'
    ];

    public $timestamps = false;

    public function getGambarUrlAttribute()
    {
        return asset('storage/' . $this->gambar);
    }
}
