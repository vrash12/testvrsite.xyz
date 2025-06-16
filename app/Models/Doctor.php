<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table      = 'doctors';
    protected $primaryKey = 'doctor_id';
    public $timestamps    = true;
    protected $fillable   = ['doctor_name','doctor_specialization'];
}