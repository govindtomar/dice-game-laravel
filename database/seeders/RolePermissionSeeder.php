<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\PermissionRoute;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();
        $permissions = PermissionRoute::all();
        foreach ($roles as $key => $role) {
            foreach ($permissions as $key => $permission) {
                $rp = new RolePermission;
                $rp->role_id = $role->id;
                $rp->permission_id = $permission->id;
                $rp->route = $permission->route;
                if($role->id == 1){
                    $rp->status = 1;
                }
                $rp->save();
            }
        }
    }
}
