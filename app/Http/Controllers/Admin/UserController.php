<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::latest()->paginate(10);
        $totalUsers = User::count();
        return view('admin.user.index', compact('users', 'totalUsers'));
    }
}
