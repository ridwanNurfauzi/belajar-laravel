<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    use HasFactory;
    public $guarded = [];
}
