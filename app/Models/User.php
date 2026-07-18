<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nis',
        'google_id',
        'avatar',
        'is_admin',
        'is_teacher',       // 🔥 TAMBAHAN BARU
        'reputation_points',
        'store_name',        // 🔥 TAMBAHAN BARU
        'store_photo',
        'store_banner',
        'whatsapp_number',   // 🔥 TAMBAHAN BARU
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_teacher' => 'boolean',
        ];
    }

    public function submissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function appNotifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->appNotifications()->unread()->count();
    }

    /**
     * Fix avatar URL dynamically so it handles both local storage and Google Avatars properly.
     */
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return null;
        }
        
        if (\Illuminate\Support\Str::startsWith($this->avatar, 'http')) {
            return $this->avatar;
        }
        
        return asset('storage/' . $this->avatar);
    }
}