<?php 
namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Schedule;
use App\Models\DoctorAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{

    public function profile()
    {
        $patient = Auth::user();
        return view('patient.profile', compact('patient'));
    }

 
    public function editProfile()
    {
        $patient = Auth::user();
        return view('patient.edit-profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . Auth::id(),
            'phone_number' => 'required|string|max:15',
            'birthday' => 'required|date',
        ]);

        $patient = Auth::user();
        $patient->update($request->only('first_name', 'last_name', 'email', 'phone_number', 'birthday'));

        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully!');
    }

 
    public function changePasswordForm()
    {
        return view('patient.change-password');
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $patient = Auth::user();
        
      
        if (!Hash::check($request->current_password, $patient->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }


        $patient->password = Hash::make($request->new_password);
        $patient->save();

        return redirect()->route('patient.profile')->with('success', 'Password updated successfully!');
    }


    public function dashboard()
    {
 
        $patient = Auth::user();

 
        $prescriptions = Prescription::where('patient_id', $patient->id)->get();

        $schedules = Schedule::where('patient_id', $patient->id)->get();

        $assignedDoctors = DoctorAssignment::where('patient_id', $patient->id)->get();

        return view('patient.dashboard', compact('patient', 'prescriptions', 'schedules', 'assignedDoctors'));
    }
}
