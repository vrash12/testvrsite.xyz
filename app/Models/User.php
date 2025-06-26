<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';
    public $timestamps   = false;
    protected $keyType   = 'int';
    public $incrementing = true;

    protected $fillable = [
        'patient_id',
        'username',
        'email',
        'password',
        'role',
        'department_id',
        'room_id',
        'bed_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // hash on assignment
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function patient()
{
    return $this->belongsTo(\App\Models\Patient::class, 'patient_id');
}
}
