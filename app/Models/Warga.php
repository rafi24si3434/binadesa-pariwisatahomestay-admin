<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Warga extends Model
{
    protected $table = 'warga';
    protected $primaryKey = 'warga_id';

    protected $fillable = [
        'no_ktp', 'nama', 'jenis_kelamin', 'agama',
        'pekerjaan', 'telp', 'email'
    ];

    /* 🔍 SEARCH GLOBAL */
    public function scopeSearch(Builder $query, $keyword): Builder
    {
        if (!$keyword) return $query;

        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'like', "%$keyword%")
              ->orWhere('no_ktp', 'like', "%$keyword%")
              ->orWhere('email', 'like', "%$keyword%");
        });
    }

    /* 🔽 FILTER DINAMIS */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['jenis_kelamin']) && $filters['jenis_kelamin'] !== 'all') {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
        }

        if (!empty($filters['agama']) && $filters['agama'] !== 'all') {
            $query->where('agama', $filters['agama']);
        }

        if (!empty($filters['pekerjaan']) && $filters['pekerjaan'] !== 'all') {
            $query->where('pekerjaan', $filters['pekerjaan']);
        }

        return $query;
    }

    /* ↕ SORTING */
    public function scopeSort(Builder $query, $sortBy, $sortOrder): Builder
    {
        return $query->orderBy($sortBy ?? 'warga_id', $sortOrder ?? 'DESC');
    }
}
