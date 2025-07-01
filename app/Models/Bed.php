<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bed extends Model
{
    use HasFactory;

    protected $primaryKey = 'bed_id';
    protected $table = 'beds';
    public $timestamps = true;

    protected $fillable = [
        'room_id',
        'bed_number',
        'status',
        'patient_id',    // ← new
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    /**
     * Is this bed currently occupied?
     */
    public function isOccupied(): bool
    {
        return $this->patient_id !== null;
    }
}
