<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function index(Request $request)
    {
        // Paginar cada recurso por separado; permitir tamaños independientes si se desea luego
        $users = User::with('roles')
            ->orderBy('name')
            ->paginate(10, ['*'], 'users_page');

        $roles = Role::withCount(['users','permissions'])
            ->orderBy('name')
            ->paginate(10, ['*'], 'roles_page');

        $permissions = Permission::withCount('roles')
            ->orderBy('name')
            ->paginate(10, ['*'], 'permissions_page');

        return view('admin.security.index', compact('users','roles','permissions'));
    }
}
