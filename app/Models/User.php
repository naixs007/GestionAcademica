<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * Helper annotations to assist static analyzers and IDEs. Methods like
 * hasPermissionTo, hasRole, assignRole, etc. are provided by the
 * Spatie\Permission traits at runtime.
 *
 * @mixin \Spatie\Permission\Traits\HasRoles
 * @method bool hasPermissionTo(string|int|\Spatie\Permission\Contracts\Permission $permission)
 * @method bool hasAnyPermission(array|string $permissions)
 * @method bool hasRole(string|int|array|\Spatie\Permission\Contracts\Role $roles)
 * @method bool hasAnyRole(array|string $roles)
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany roles()
 * @method \Illuminate\Support\Collection getRoleNames()
 * @method mixed assignRole(...$roles)
 * @method \Illuminate\Support\Collection getDirectPermissions()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
        'estado',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
