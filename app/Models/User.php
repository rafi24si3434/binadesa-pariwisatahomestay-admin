<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* =====================================================
       RELASI FOTO PROFIL (TABLE: media)
       ref_table = 'user_profile'
       ref_id    = users.id
    ====================================================== */
    public function fotoProfil()
    {
        return $this->hasOne(Media::class, 'ref_id')
                    ->where('ref_table', 'user_profile');
    }

    /* =====================================================
       ACCESSOR: Ambil foto profil siap pakai
       Jika user tidak punya foto → avatar default
    ====================================================== */
    public function getFotoUrlAttribute()
    {
        if ($this->fotoProfil) {
            return asset('storage/' . $this->fotoProfil->file_url);
        }

        // Avatar default (inisial)
        $initials = strtoupper(substr($this->name, 0, 1));

        return "https://ui-avatars.com/api/?name={$initials}&background=C62828&color=fff&size=128";
    }
}
