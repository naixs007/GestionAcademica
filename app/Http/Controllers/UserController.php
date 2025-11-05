<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function __construct()
    {
        // Protege todas las acciones con autenticación y verificación de email
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the users for the admin area.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Requiere permiso para ver usuarios
        // Usar el método de Spatie 'hasPermissionTo' en lugar de 'can' para comprobar permisos en el modelo
        if (! $user || ! $user->hasPermissionTo('usuarios.ver')) {
            abort(403);
        }

        $perPage = 15;
        $users = User::with('roles')->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.users.index', compact('users'));
    }
}
