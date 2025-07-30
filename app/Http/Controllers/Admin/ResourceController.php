<?php
// app/Http/Controllers/Admin/ResourceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Bed;
use App\Models\Department;

class ResourceController extends Controller
{
    public function index()
    {
        $rooms       = Room::with(['department','beds'])->get();
        $departments = Department::all();
        return view('admin.resources.index', compact('rooms','departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $rooms       = Room::with('department')->get();
        return view('admin.resources.create', compact('departments', 'rooms'));
    }

    public function store(Request $request)
    {
        if ($request->type === 'room') {
            $data = $request->validate([
                'department_id' => 'required|exists:departments,department_id',
                'room_number'   => 'required|string|max:50',
                'status'        => 'required|in:available,unavailable',
                'capacity'      => 'required|integer|min:1',
                'rate'          => 'required|numeric|min:0',
            ]);

            Room::create($data);

        } else { // bed
            $data = $request->validate([
                'room_id'    => 'required|exists:rooms,room_id',
                'bed_number' => 'required|string|max:50',
                'status'     => 'required|in:available,occupied',
                'rate'       => 'required|numeric|min:0',
                'patient_id' => 'nullable|exists:patients,patient_id',
            ]);

            Bed::create($data);
        }

        return redirect()->route('admin.resources.index')
                         ->with('success', 'Resource created successfully.');
    }

    public function edit($type, $id)
    {
        $departments = Department::all();
        $rooms       = Room::with('department')->get();

        if ($type === 'room') {
            $room = Room::findOrFail($id);
            return view('admin.resources.edit', compact('room', 'departments', 'rooms'));
        }

        $bed = Bed::with('room.department')->findOrFail($id);
        return view('admin.resources.edit', compact('bed', 'departments', 'rooms'));
    }

    public function update(Request $request, $type, $id)
    {
        if ($type === 'room') {
            $room = Room::findOrFail($id);

            $data = $request->validate([
                'department_id' => 'required|exists:departments,department_id',
                'room_number'   => 'required|string|max:50',
                'status'        => 'required|in:available,unavailable',
                'capacity'      => 'required|integer|min:1',
                'rate'          => 'required|numeric|min:0',
            ]);

            $room->update($data);

        } else {
            $bed = Bed::findOrFail($id);

            $data = $request->validate([
                'room_id'    => 'required|exists:rooms,room_id',
                'bed_number' => 'required|string|max:50',
                'status'     => 'required|in:available,occupied',
                'rate'       => 'required|numeric|min:0',
                'patient_id' => 'nullable|exists:patients,patient_id',
            ]);

            $bed->update($data);
        }

        return redirect()->route('admin.resources.index')
                         ->with('success', ucfirst($type).' updated successfully.');
    }

    public function destroy($type, $id)
    {
        if ($type === 'room') {
            Room::destroy($id);
        } else {
            Bed::destroy($id);
        }

        return back()->with('success', ucfirst($type).' deleted.');
    }
}
