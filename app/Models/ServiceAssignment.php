<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table      = 'payment_methods';
    protected $primaryKey = 'payment_method_id';
    public $timestamps    = true;
    protected $fillable   = ['name'];
}