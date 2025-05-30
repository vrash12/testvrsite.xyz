<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $primaryKey = 'payment_method_id';
    public $timestamps = false;

    protected $fillable = [
        'method_name',
        'details',
        // any other columns in your table
    ];
}
