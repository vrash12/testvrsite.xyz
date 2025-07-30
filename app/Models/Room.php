<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Room extends Model
{
    protected $primaryKey = 'room_id';
    public $timestamps = true;

    protected $fillable = [
        'department_id',
        'room_number',
        'status',
        'capacity',    
          'rate',   
    ];

      protected $casts = [
        'rate'     => 'decimal:2',
    ];
public function getRateFormattedAttribute(): string
    {
        return number_format($this->rate, 2);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class, 'room_id', 'room_id');
    }
public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * How many beds are occupied?
     */
    public function occupiedCount()
    {
        return $this->beds()->whereNotNull('patient_id')->count();
    }

    /**
     * Is this room at full capacity?
     */
    public function isFull(): bool
    {
        return $this->occupiedCount() >= $this->capacity;
    }
}
