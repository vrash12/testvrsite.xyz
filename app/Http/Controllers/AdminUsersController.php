<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = [
            'admin',
            'admission',
            'pharmacy',
            'doctor',
            'patient',
            'laboratory',
            'supplies',
            'operating_room',
            'billing',
        ];
        $departments = \App\Models\Department::orderBy('department_name')->get();
        $rooms       = \App\Models\Room::where('status','available')->orderBy('room_number')->get();
        $beds        = \App\Models\Bed::where('status','available')->orderBy('bed_number')->get();
        return view('admin.users.create', compact('roles','departments','rooms','beds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,patient,doctor,admission,billing,hospital_services,pharmacy',
            'password' => 'required|string|min:8|confirmed',
            'rate'     => 'required_if:role,doctor|nullable|numeric|min:0',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create([
            'username'    => $data['username'],
            'email'       => $data['email'],
            'role'        => $data['role'],
            'password'    => $data['password'],
        ]);

        if ($data['role'] === 'doctor') {
            $doctor = Doctor::updateOrCreate(
                ['user_id' => $user->user_id],
                [
                    'doctor_name'           => $data['username'],
                    'doctor_specialization' => 'General',
                    'department_id'         => 1,
                    'rate'                  => $data['rate']
                ]
            );
            $user->update(['doctor_id' => $doctor->doctor_id]);
        }

        return redirect()->route('admin.users.index')->with('success','User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = ['admin','patient','doctor','admission','billing','hospital_services','pharmacy'];
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,'.$user->user_id.',user_id',
            'email'    => 'required|email|unique:users,email,'.$user->user_id.',user_id',
            'role'     => 'required|in:admin,patient,doctor,admission,billing,hospital_services,pharmacy',
            'password' => 'nullable|string|min:8|confirmed',
            'rate'     => 'required_if:role,doctor|nullable|numeric|min:0',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update([
            'username' => $data['username'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'password' => $data['password'] ?? $user->password,
        ]);

        if ($data['role'] === 'doctor') {
            $doctor = Doctor::updateOrCreate(
                ['user_id' => $user->user_id],
                [
                    'doctor_name'           => $data['username'],
                    'doctor_specialization' => 'General',
                    'department_id'         => 1,
                    'rate'                  => $data['rate']
                ]
            );
            $user->update(['doctor_id' => $doctor->doctor_id]);
        } else {
            $user->update(['doctor_id' => null]);
        }

        return redirect()->route('admin.users.index')->with('success','User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success','User deleted successfully.');
    }
}
