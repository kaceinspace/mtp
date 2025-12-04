<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'nama_ortu',
        'no_hp_ortu',
        'tahun_masuk',
        'tahun_lulus',
        'pendidikan_terakhir',
        'universitas',
        'jurusan_kuliah',
        'gelar',
        'spesialisasi',
        'tahun_mengajar',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'instagram',
        'skills',
        'certifications',
        'bio',
        'avatar',
        'show_email',
        'show_phone',
        'theme',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'skills' => 'array',
        'certifications' => 'array',
        'show_email' => 'boolean',
        'show_phone' => 'boolean',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full address.
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->alamat,
            $this->kota,
            $this->provinsi,
            $this->kode_pos,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get age from date of birth.
     */
    public function getAgeAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }

        return $this->tanggal_lahir->age;
    }

    /**
     * Get avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return asset('images/avatars/default.png');
    }
}
