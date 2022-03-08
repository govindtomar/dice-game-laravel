<?php
namespace App\Http\Controllers\API\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Role;
use App\Models\User;
use Auth;

class RolePermissionController extends ApiController
{
    public function __construct(){
        $this->middleware(['web', 'permission']);
    }

    public function routes()
    {
        try{
            // $role = Role::where('slug', $slug)->first();
            $user = User::with('roles')->find(Auth::id());
            $roles = [];
            foreach ($user->roles as $key => $role) {
                $roles[] = $role->id;
            }
            $permissions = RolePermission::with(['permission_route'])
                ->whereIn('role_id', $roles)->get();

            $permission_route = [];
            $in_arr = [];
            foreach ($permissions as $key => $permission) {
                if ($permission->status == 1) {
                    if(!in_array($permission->permission_route->url, $in_arr)){
                        $in_arr[] = $permission->permission_route->url;
                        $permission_route[] = $permission->permission_route->url;
                    }                        
                }
            // }
            }
            

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Role routes',
                'data' =>  $permission_route,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function role_permission_index($slug)
    {
        try{
            $role = Role::where('slug', $slug)->first();
            $this->role_id = $role->id;

            $permissions  = Permission::with(['permission_routes' => function($query){
                $query->with(['role_permissions'  => function($query){
                    $query->where('role_id', $this->role_id);
                }]);
            }])->get();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Role permission save successfully',
                'data' =>  $permissions,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }


    public function role_permission_update(Request $request)
    {
        // return $request->permission;
        try{
            foreach ($request->permission as $id => $status) {
                $role_per = RolePermission::where('id', $id)->first();
                $role_per->status = $status;
                $role_per->save();

                // $role_per = RolePermission::where('relation_key', $role_per->id)->first();
                // if ($role_per != null) {
                //     $role_per->status = $status;
                //     $role_per->save();
                // }
            }
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Role permission update successfully',
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function role_permission_add_new(Request $request){
        // return $request->all();
        $roles = Role::all();
        $permission = Permission::where('route', $request->permission)->first();

        foreach ($roles as $key => $role) {
            $role_permission = RolePermission::where('route', $permission->route.'.'.$request->view_delete)
                ->where('role_id', $role->id)->first();
            if ($role_permission == null) {
                $role_permission = new RolePermission;
                $role_permission->role_id = $role->id;
                $role_permission->permission_id = $permission->id;
                $role_permission->route = $permission->route.'.'.$request->view_delete;
                $role_permission->display = 1;
                $role_permission->display_name = $request->view_delete_name;
                $role_permission->save();
            }

            if (!empty($request->create_update)) {
                $new_role_permission = RolePermission::where('route', $permission->route.'.'.$request->create_update)
                    ->where('role_id', $role->id)->first();
                if ($new_role_permission == null) {
                    $new_role_permission = new RolePermission;
                    $new_role_permission->role_id = $role->id;
                    $new_role_permission->permission_id = $permission->id;
                    $new_role_permission->route = $permission->route.'.'.$request->create_update;
                    $new_role_permission->relation_key = $role_permission->id;
                    $new_role_permission->save();
                }
            }
        }
        return response()->json($role_permission);
    }

}