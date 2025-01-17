<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function uroles()
    // {
    //     return $this->belongsToMany(\App\Models\URole::class, 'u_role_user', 'user_id', 'role_id');
    // }

    // public function uhasPermission(string $permission): bool
    // {
    //     $permissionsArray = [];

    //     foreach ($this->roles as $role) {
    //         foreach ($role->permissions as $singlePermission) {
    //             $permissionsArray[] = $singlePermission->name;
    //         }
    //     }

    //     return collect($permissionsArray)->unique()->contains($permission);
    // }

    // public function uhasRole(string $role): bool
    // {
    //     return $this->uroles()->where('name', $role)->exists();
    // }
}
