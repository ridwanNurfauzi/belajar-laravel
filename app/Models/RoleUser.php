<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RoleUser extends Model
{
    use HasFactory;

    protected $table = 'role_user';

    public static function hasRole(string $e)
    {
        if (Auth::user())
            return (Role::all()->find(RoleUser::all()->where('user_id', Auth::user()->id)
                    ->first()->role_id)
                    ->name == $e);
        else
            return 0;
    }
}
