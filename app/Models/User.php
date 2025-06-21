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
      'username','email','password','role',
      'department_id','room_id','bed_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class, 'bed_id', 'bed_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
