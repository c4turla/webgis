<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = $request->input('query');

        $usersQuery = User::query();

        if ($query) {
            $usersQuery->where('name', 'like', "%$query%")
                       ->orWhere('email', 'like', "%$query%");
        }

        $users = $usersQuery->paginate(10);
        $totalUsers = $usersQuery->count();
        return view('admin.user.index', compact('users', 'totalUsers', 'query'));
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required',
            'status' => 'required',
            'role_name' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'position' => 'required',
            'department' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $validatedData['password'] = Hash::make($request->password);

        // Create new user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->status = $request->input('status');
        $user->role_name = $request->input('role_name');
        $user->position = $request->input('position');
        $user->department = $request->input('department');
        $user->password = bcrypt($request->input('password'));
        if ($request->hasFile('avatar')) {
            $avatarName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('avatars'), $avatarName);
            $user->avatar = $avatarName;
        }
        dd($request->all());
        $user->save();

        Toastr::success('Data berhasil ditambahkan :)','Success');

        // Redirect or return response
        return redirect()->back();
    }


}
