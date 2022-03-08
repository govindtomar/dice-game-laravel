<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;

class LogoutController extends ApiController
{
    public function logout(Request $request){
        try{

            $user = $request->user();
            $user->currentAccessToken()->delete();
            // $user->tokens()->where('id', $tokenId)->delete();
            event(new \Illuminate\Auth\Events\Logout('sanctum', $user));

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Logout successfully'
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}