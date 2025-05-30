<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InsuranceProvider extends Model
{
    use HasFactory;

    protected $table = 'insurance_providers';
    protected $primaryKey = 'insurance_provider_id';
    public $timestamps = false;

    protected $fillable = [
        'provider_name',
        'contact_info',
        // any other columns your migration defines
    ];
}
