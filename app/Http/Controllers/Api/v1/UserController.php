<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\v1\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // Validation data
        $validData = $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        // Check User auth
        if (! auth()->attempt($validData)){
            return response([
                'message' => 'Invalid email or password ',
                'status' => 'error'
            ],Response::HTTP_FORBIDDEN);
        }


        return new UserResource(auth()->user());
    }

    public function register(Request $request)
    {
        // Validation data
        $validData = $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // user create
        $user = User::create([
            'name' => $validData['name'],
            'email' => $validData['email'],
            'password' => bcrypt($validData['password']),
            'api_token' => Str::random(120)
        ]);

        auth()->login($user);

        // response
        return new UserResource($user);
    }

    public function changePassword(Request $request)
    {
        // Validation data
        $validData = $this->validate($request, [
            'password' => 'required|string',
            'new_password' => 'required|string|min:6'
        ]);

        // Check User password
        if (! Hash::check($validData['password'], $request->user()->password)) {
            return response([
                'message' => 'Invalid password',
                'status' => 'error'
            ],Response::HTTP_FORBIDDEN);
        }

        // Update user password and renew api_token
        auth()->user()->update([
            'password' => bcrypt($validData['new_password']),
            'api_token' => Str::random(120)
        ]);

        // response
        return new UserResource(auth()->user());
    }
}
