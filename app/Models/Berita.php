<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $appends = ['url_gambar'];
    protected $primaryKey = 'id_berita';
    protected $fillable = [
        'judul_berita',
        'konten_berita',
        'gambar_berita',
    ];

    public function getUrlGambarAttribute()
    {
        if (!$this->gambar_berita) {
            return null;
        }
        return asset('storage/berita/' . $this->gambar_berita);
    }
}
