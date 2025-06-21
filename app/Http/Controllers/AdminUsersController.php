<?php

namespace App\Http\Controllers;

use App\Models\User;
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
    $roles = ['admin','patient','doctor','admission','billing','hospital_services','pharmacy'];

    // load for dropdowns:
    $departments = \App\Models\Department::orderBy('department_name')->get();
    $rooms       = \App\Models\Room::where('status','available')->orderBy('room_number')->get();
    $beds        = \App\Models\Bed::where('status','available')->orderBy('bed_number')->get();

    return view('admin.users.create', compact(
        'roles','departments','rooms','beds'
    ));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,patient,doctor,admission,billing,hospital_services,pharmacy',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')
                         ->with('success','User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = ['admin','patient','doctor','admission','billing','hospital_services','pharmacy'];
        return view('admin.users.edit', compact('user','roles'));
    }

    public function showAssignment(User $user)
{
    $departments = Department::all();
    // pre-load rooms for the user’s current department, if any
    $rooms = $user->department
           ? $user->department->rooms()->where('status','available')->get()
           : collect();
    // pre-load beds for the user’s current room, if any
    $beds  = $user->room
           ? $user->room->beds()->where('status','available')->get()
           : collect();

    return view('admin.users.assign', compact('user','departments','rooms','beds'));
}

public function updateAssignment(Request $request, User $user)
{
    $data = $request->validate([
      'department_id' => 'nullable|exists:departments,department_id',
      'room_id'       => 'nullable|exists:rooms,room_id',
      'bed_id'        => 'nullable|exists:beds,bed_id',
    ]);

    $user->update($data);

    return redirect()
        ->route('admin.users.index')
        ->with('success','Assignments updated successfully.');
}


    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,'.$user->user_id.',user_id',
            'email'    => 'required|email|unique:users,email,'.$user->user_id.',user_id',
            'role'     => 'required|in:admin,patient,doctor,admission,billing,hospital_services,pharmacy',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                         ->with('success','User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success','User deleted successfully.');
    }
}
