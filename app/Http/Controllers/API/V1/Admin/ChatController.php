<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Validator;
use Auth;


class ChatController extends ApiController
{
    public function __construct(){
        $this->middleware(['web', 'permission']);
    }
    
    public function index(Request $request){
        try{
            $users = User::whereNotIn('id', [1, Auth::id()])->get();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'User lists',
                'data' =>  $users,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

}
