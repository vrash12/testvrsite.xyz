<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;

class ResourceController extends Controller
{
    public function index()
    {
        // eager‐load department and beds
        $rooms = Room::with('department','beds')->get();

        return view('admin.resources.index', compact('rooms'));
    }

    public function create()
    {
   $departments = \App\Models\Department::all();
$rooms       = \App\Models\Room::with('department')->get();
return view('admin.resources.create', compact('departments','rooms'));
    }
}
