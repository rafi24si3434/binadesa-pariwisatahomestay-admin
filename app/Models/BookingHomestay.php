<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BookingHomestay extends Model
{
    protected $table = 'booking_homestay';
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'kamar_id',
        'warga_id',
        'checkin',
        'checkout',
        'total',
        'status',
        'metode_bayar'
    ];

    // RELASI
    public function kamar()
    {
        return $this->belongsTo(KamarHomestay::class, 'kamar_id', 'kamar_id');
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id', 'warga_id');
    }

    public function media()
    {
        return $this->hasOne(Media::class, 'ref_id', 'booking_id')
                     ->where('ref_table', 'booking_homestay');
    }

    // SEARCH
    public function scopeSearch(Builder $query, $keyword)
    {
        if (!$keyword) return $query;

        return $query->whereHas('warga', function ($q) use ($keyword) {
            $q->where('nama', 'like', "%$keyword%");
        });
    }

    // FILTER
    public function scopeFilter(Builder $query, $filters)
    {
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['metode_bayar']) && $filters['metode_bayar'] !== 'all') {
            $query->where('metode_bayar', $filters['metode_bayar']);
        }

        return $query;
    }
}
