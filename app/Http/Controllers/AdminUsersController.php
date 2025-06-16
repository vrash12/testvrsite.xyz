<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{
    public function __construct()
    {
        // ensure only logged-in admins can access
        $this->middleware(['auth:admin']);
    }

    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = ['admin','patient','doctor','admission','billing','hospital_services','pharmacy'];
        return view('admin.users.create', compact('roles'));
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
