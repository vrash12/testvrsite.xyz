<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bed extends Model
{
    use HasFactory;

    // if your table is named "beds"
    protected $table = 'beds';

    // if your PK is bed_id
    protected $primaryKey = 'bed_id';

    // if you’re using created_at/updated_at
    public $timestamps = true;

    // fillable attributes
    protected $fillable = [
        'room_id',
        'status',
        // add any other columns here…
    ];

    /**
     * Optional: link back to Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }
}
