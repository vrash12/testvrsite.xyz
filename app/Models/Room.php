<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    protected $primaryKey = 'room_id';
    public $timestamps = true;

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function beds()
    {
        return $this->hasMany(Bed::class, 'room_id', 'room_id');
    }
}
