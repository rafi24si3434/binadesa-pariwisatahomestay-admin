<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinasiWisata extends Model
{
    protected $table = 'destinasi_wisata';
    protected $primaryKey = 'destinasi_id';

    protected $fillable = [
        'nama',
        'deskripsi',
        'alamat',
        'rt',
        'rw',
        'jam_buka',
        'tiket',
        'kontak'
    ];

    // RELASI BENAR → banyak foto
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'destinasi_id')
                    ->where('ref_table', 'destinasi_wisata')
                    ->orderBy('sort_order', 'ASC');
    }

    // COVER FOTO (ambil foto pertama)
    public function cover()
    {
        return $this->media()->first();
    }

    // Searching
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('alamat', 'like', "%$keyword%")
                  ->orWhere('kontak', 'like', "%$keyword%");
            });
        }
        return $query;
    }

    // Filtering
    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['rt']) && $filters['rt'] !== 'all') {
            $query->where('rt', $filters['rt']);
        }

        if (!empty($filters['rw']) && $filters['rw'] !== 'all') {
            $query->where('rw', $filters['rw']);
        }

        if (!empty($filters['tiket_min'])) {
            $query->where('tiket', '>=', $filters['tiket_min']);
        }

        if (!empty($filters['tiket_max'])) {
            $query->where('tiket', '<=', $filters['tiket_max']);
        }

        return $query;
    }
}
