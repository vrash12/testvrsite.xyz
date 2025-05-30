<?php
namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // Show the doctor registration form
    public function showRegistrationForm()
    {
        return view('doctors.create');
    }

    // Handle doctor registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'doctor_name' => 'required|string|max:255',
            'doctor_specialization' => 'required|string|max:255',
        ]);

        Doctor::create($validated);

        return redirect()->route('doctors.index')->with('success', 'Doctor registered successfully');
    }
}
