<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRoute extends Model
{
    use HasFactory;

    public function permission(){
        return $this->belongsTo(Permission::class);
    }

    public function role_permissions(){
        return $this->hasOne(RolePermission::class, 'permission_id', 'id',);
    }
}
