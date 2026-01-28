<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class AdminLogged extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'admin_logged';
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'admin_id',
        'action',
        'description',
        'target_type',
        'target_id',
        'ip_address',
        'user_agent',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function target()
    {
        return $this->morphTo();
    }
}