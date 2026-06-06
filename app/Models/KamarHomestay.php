<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KamarHomestay extends Model
{
    protected $table = 'kamar_homestay';
    protected $primaryKey = 'kamar_id';

    protected $fillable = [
        'homestay_id',
        'nama_kamar',
        'kapasitas',
        'fasilitas_json',
        'harga'
    ];

    // Relasi ke Homestay
    public function homestay()
    {
        return $this->belongsTo(Homestay::class, 'homestay_id', 'homestay_id');
    }

    // Relasi ke media (foto kamar)
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'kamar_id')
                    ->where('ref_table', 'kamar_homestay')
                    ->orderBy('sort_order', 'ASC');
    }

    // Accessor fasilitas (json → array)
    public function getFasilitasAttribute()
    {
        return json_decode($this->fasilitas_json ?? '[]', true);
    }

    // Searching
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            $query->where('nama_kamar', 'LIKE', "%$keyword%");
        }
        return $query;
    }

    // Filtering
    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['homestay_id']) && $filters['homestay_id'] !== 'all') {
            $query->where('homestay_id', $filters['homestay_id']);
        }

        if (!empty($filters['harga_min'])) {
            $query->where('harga', '>=', $filters['harga_min']);
        }

        if (!empty($filters['harga_max'])) {
            $query->where('harga', '<=', $filters['harga_max']);
        }

        return $query;
    }
}
