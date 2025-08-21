<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;







    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'birthday',
        'gender',
        'bio',
        'social_media',
        'occupation',
        'mailing_address',
        'billing_address',
        // OAuth provider columns removed as this data is now intended to be stored
        // by RadUser in radcheck (e.g., [Provider]-OAuth-Details attribute).
        // 'provider',
        // 'provider_id',
        // 'provider_token',
        // 'provider_refresh_token',
        // 'provider_avatar',
        'account_level',
        // Removed 'name' field as it's no longer needed in the database
        // - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com - 2025-05-01
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'provider_token',
        'provider_refresh_token',
    ];

    protected function casts(): array
    {
        return [
            'profile_created_at' => 'datetime',
            'profile_updated_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
            'social_media' => 'array',
            'mailing_address' => 'array',
            'billing_address' => 'array',
            'provider_avatar' => 'array',
        ];
    }

    /**
     * Check if the user is a super admin.
     *
     * @return bool
     * 
     * - 2023-08-01 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function isSuperAdmin(): bool
    {
        return $this->account_level === 'super-admin';
    }

    /**
     * Check if the user is an admin (either admin or super-admin).
     *
     * @return bool
     * 
     * - 2023-08-01 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function isAdmin(): bool
    {
        return in_array($this->account_level, ['admin', 'super-admin']);
    }

    /**
     * Check if the user is a manager or higher.
     *
     * @return bool
     * 
     * - 2023-08-01 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function isManager(): bool
    {
        return in_array($this->account_level, ['manager', 'admin', 'super-admin']);
    }

    /**
     * Check if the user is a regular user (or higher)
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return in_array($this->account_level, ['user', 'manager', 'admin', 'super-admin']);
    }

    /**
     * Check if the user is a guest
     *
     * @return bool
     */
    public function isGuest(): bool
    {
        return $this->account_level === 'guest';
    }

    /**
     * Check if the user has at least the specified account level
     *
     * @param string $level
     * @return bool
     */
    public function hasAccountLevel(string $level): bool
    {
        $levels = [
            'guest' => 1,
            'user' => 2,
            'manager' => 3,
            'admin' => 4,
            'super-admin' => 5
        ];

        $userLevel = $levels[$this->account_level] ?? 0;
        $requiredLevel = $levels[$level] ?? 0;

        return $userLevel >= $requiredLevel;
    }
}
