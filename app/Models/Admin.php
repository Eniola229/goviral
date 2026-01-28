<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasUuid;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasUuid;

    protected $guard = 'admin';
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    public function isHR(): bool
    {
        return $this->role === 'hr';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isSupport(): bool
    {
        return $this->role === 'support';
    }

    public function canManageAdmins(): bool
    {
        return $this->isSuperAdmin() || $this->isHR();
    }

    public function canViewAdminLogs(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canEditOrders(): bool
    {
        return $this->isSuperAdmin() || $this->isAccountant();
    }

    public function canDeleteOrders(): bool
    {
        return $this->isSuperAdmin() || $this->isAccountant();
    }

    public function canEditTransactions(): bool
    {
        return $this->isSuperAdmin() || $this->isAccountant();
    }

    public function canDeleteTransactions(): bool
    {
        return $this->isSuperAdmin() || $this->isAccountant();
    }

    public function canAdjustBalance(): bool
    {
        return $this->isSuperAdmin() || $this->isAccountant();
    }

    public function canViewCustomerLogs(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canEditCustomer(): bool
    {
        return $this->isSuperAdmin() || $this->isAccountant();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function logs()
    {
        return $this->hasMany(AdminLogged::class);
    }
}