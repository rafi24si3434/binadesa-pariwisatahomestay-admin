<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UlasanWisata extends Model
{
    protected $table = 'ulasan_wisata';
    protected $primaryKey = 'ulasan_id';

    protected $fillable = [
        'destinasi_id',
        'warga_id',
        'rating',
        'komentar',
        'waktu'
    ];

    // Relasi ke Destinasi
    public function destinasi()
    {
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_id', 'destinasi_id');
    }

    // Relasi ke Warga
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id', 'warga_id');
    }

    // Scope Searching
    public function scopeSearch($query, $keyword)
    {
        if (!$keyword) return $query;

        return $query->where('komentar', 'like', "%$keyword%");
    }

    // Scope Filter (Rating)
    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['rating']) && $filters['rating'] !== 'all') {
            $query->where('rating', $filters['rating']);
        }

        if (!empty($filters['destinasi_id']) && $filters['destinasi_id'] !== 'all') {
            $query->where('destinasi_id', $filters['destinasi_id']);
        }

        return $query;
    }
}
