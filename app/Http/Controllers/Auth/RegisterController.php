<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;


class RegisterController extends ApiController
{
    // public function __construct(FirebaseAuth $auth) {
    //    $this->middleware('guest');
    //    $this->auth = $auth;
    // }

    public function register(Request $request)
    {
        try{
            $rules = array(
                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'email' => ['bail', 'required', 'email:strict', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
                // 'confirm_password' => ['required', 'same:password'],
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->respondValidationError('Fields Validation Failed.', $validator);
            }

            $user = new User();
            $user->fill([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->save();

            event(new \Illuminate\Auth\Events\Registered($user));

            if (! Auth::guard('web')->attempt($request->only(['email', 'password']))) {
                return $this->respond([
                    'status' => 'warning',
                    'status_code' => 201,
                    'message' => 'User not register successfully',
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

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'User register successfully',
                'token_type' => 'Bearer',
                'access_token' => $token,
                'data' =>  $user,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}