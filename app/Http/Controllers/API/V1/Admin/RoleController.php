<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Role;
use Validator;


class RoleController extends ApiController
{
    public function __construct(){
        $this->middleware(['web', 'permission']);
    }
    
    public function index(Request $request){
        try{
            $per_page = $request->per_page ? $request->per_page : 10;
            $roles = Role::where('id', '>', 1)->paginate($per_page);
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Role lists',
                'data' =>  $roles,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function show(Request $request, $id){
        try{
            if ($request->by == 'slug') {
                $role = Role::where('slug', $id)->first();
            }else{
                $role = Role::find($id);
            }            

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Show role',
                'data' =>  $role,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    
    public function store(Request $request){
        try{
        // return $request->all();
            $rules = array(
                'name' => ['required', 'string'],
                'slug' => ['required', 'string'],
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->respondValidationError('Fields Validation Failed.', $validator);
            }

            $role = new Role;
            $role->name = $request->name;
            $role->slug = $request->slug;
            $role->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Role save successfully',
                'data' =>  $role,
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
                'slug' => ['required', 'string'],
                // 'slug' => ['required', 'string'],
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->respondValidationError('Fields Validation Failed.', $validator);
            }

            $role = Role::find($request->id);
            $role->name = $request->name;
            $role->slug = $request->slug;
            $role->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Role update successfully',
                'data' =>  $role,
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

            $role = Role::find($request->id);
            $role->status = $request->status;
            $role->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Role update successfully',
                'data' =>  $role,
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

            $role = Role::find($request->id);
            $role->delete();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Role delete successfully',
                'data' =>  $role,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
