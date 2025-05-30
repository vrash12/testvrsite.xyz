<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function create()
    {
       
        return view('admin.admissions.create');
    }


}
