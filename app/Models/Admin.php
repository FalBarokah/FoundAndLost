<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_SATPAM = 'satpam';

    protected $table = 'admins';

    protected $primaryKey = 'admin_id';

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
        return [];
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN], true);
    }

    public function isSatpam(): bool
    {
        return $this->role === self::ROLE_SATPAM;
    }

    public function handovers()
    {
        return $this->hasMany(Handover::class, 'admin_id', 'admin_id');
    }
}
