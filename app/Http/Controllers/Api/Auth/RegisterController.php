<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors(),
                ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            $request['password'] = Hash::make($request['password']);

            $user = User::create($request->except('password_confirmation'));

            return response()->json([
                'data' => $user,
                'message' => 'User has been created.'
            ], HttpResponse::HTTP_CREATED);
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
