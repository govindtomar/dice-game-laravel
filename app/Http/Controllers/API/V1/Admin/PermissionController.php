<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\PermissionRoute;
use Validator;
use Route;


class PermissionController extends ApiController
{
    public function __construct(){
        $this->middleware(['web', 'permission']);
    }

    public function index(Request $request){
        try{
            $per_page = $request->per_page ? $request->per_page : 10;
            $permissions = Permission::paginate($per_page);
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Permission lists',
                'data' =>  $permissions,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function show(Request $request, $id){
        try{
            $permission = Permission::with(['permission_routes'])->find($id);
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Show permission',
                'data' =>  $permission,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    
    public function store(Request $request){
        try{
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Permission save successfully',
                'data' =>  $permission,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }



    public function update(Request $request){
        try{
        // return $request->all();
            $rules = array(
                'id' => ['required'],
                'name' => ['required', 'string'],
                // 'routes' => ['required'],
                // 'route' => ['required', 'string'],
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->respondValidationError('Fields Validation Failed.', $validator);
            }

            $permission = Permission::find($request->id);
            $permission->name = $request->name;
            $permission->save();

            foreach ($request->routes as $key => $value) {
                $pr = PermissionRoute::find($value['id']);
                if ($value['name'] != null) {
                    $pr->name = $value['name'];
                }                
                if ($value['url'] != null) {
                    $pr->url = $value['url'];
                }                
                $pr->save();
            }
            
            $this->update_config('permission', 'updated_'.\Carbon\Carbon::now());

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Permission update successfully',
                'data' =>  $permission,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }


    public function changeStatus(Request $request){
        try{
        // return $request->all();
            $rules = array(
                'id' => ['required'],
                'status' => ['required']
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->respondValidationError('Fields Validation Failed.', $validator);
            }

            $permission = Permission::find($request->id);
            $permission->status = $request->status;
            $permission->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Permission update successfully',
                'data' =>  $permission,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function delete(Request $request){
        try{
        // return $request->all();
            $rules = array(
                'id' => ['required'],
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->respondValidationError('Fields Validation Failed.', $validator);
            }

            // $permission = Permission::find($request->id);
            // $rps = PermissionRoute::where('permission_id', $permission->id)->get();
            // foreach ($rps as $key => $rp) {
            //     RolePermission::where('permission_id', $rp->id)->delete();
            // }
            // PermissionRoute::where('permission_id', $permission->id)->delete();
            // $permission->delete();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Permission delete successfully',
                // 'data' =>  $permission,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function update_permissions($permission, $perm){
        $roles = Role::all();
        foreach ($roles as $key => $role) {
            foreach ($routes as $key => $route) {
                $check_route = RolePermission::where('route', $permission->route.'.'.$key)->where('role_id', $role->id)->first();
                if ($check_route == null) {
                    $role_permission = new RolePermission;
                    $role_permission->role_id = $role->id;
                    $role_permission->permission_id = $permission->id;
                    $role_permission->route = $permission->route.'.'.$key;
                    if ($route == 'NULL') {
                        $role_permission->relation_key = $last_permission->id;
                    }else{
                        $role_permission->display = 1;
                        $role_permission->display_name = $route;
                    }
                    $role_permission->save();

                    $last_permission = $role_permission;
                }
            }
        }

    }
}
