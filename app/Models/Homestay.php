<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homestay extends Model
{
    protected $table = 'homestay';
    protected $primaryKey = 'homestay_id';

    protected $fillable = [
        'pemilik_warga_id',
        'nama',
        'alamat',
        'rt',
        'rw',
        'fasilitas_json',
        'harga_per_malam',
        'status',
    ];

    protected $casts = [
        'fasilitas_json' => 'array',
    ];

    // Relasi ke warga (pemilik)
    public function pemilik()
    {
        return $this->belongsTo(Warga::class, 'pemilik_warga_id', 'warga_id');
    }

    // Relasi ke media (foto homestay)
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'homestay_id')
            ->where('ref_table', 'homestay')
            ->orderBy('sort_order', 'ASC');
    }

    // Searching
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('alamat', 'like', "%$keyword%");
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

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        return $query;
    }
}
