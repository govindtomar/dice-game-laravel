<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Validator;

class LoginController extends ApiController
{
    public function login(Request $request)
    {
        try{
        // return $request->all();
            $rules = array(
                'email' => ['required', 'max:255'],
                'password' => ['required', 'string'],
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->respondValidationError('Fields Validation Failed.', $validator);
            }

            if (! Auth::guard('web')->attempt($request->only(['email', 'password']))) {
                return $this->respond([
                    'status' => 'warning',
                    'status_code' => 201,
                    'message' => 'E-mail address and password is incorrect',
                ]);
            }

            $user = Auth::guard('web')->user();

            $token = $user->createToken(
                'web',
                [
                    'api',
                ]
            )->plainTextToken;

            $user = User::findOrFail(Auth::id());
            // $user = new UserResource($user);
            
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'User login successfully',
                'token_type' => 'Bearer',
                'access_token' => $token,
                'data' =>  $user,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}