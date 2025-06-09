<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Tell Eloquent the primary key column is `user_id`, not `id`
    protected $primaryKey = 'user_id';

    // If you don’t have `created_at`/`updated_at` columns:
    public $timestamps = false;

    // (Optional) If you need to cast primary key to int
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
