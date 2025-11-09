<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use illuminate\Support\Facades\Auth;
class RoleController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth');
        // $this->authorizeResource(User::class);
    }

    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->paginate(10);
        return view('roles.index', compact('roles'));
    }
}
