<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Users\ChangePasswordRequest;
use App\Http\Requests\Api\v1\Users\LoginRequest;
use App\Http\Requests\Api\v1\Users\RegisterRequest;
use App\Http\Resources\v1\User as UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use function auth;
use function bcrypt;
use function response;

class UserController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return UserResource
     */
    public function login(LoginRequest $request): UserResource
    {
        $validData = $request->all();

        // Check User auth
        if (! auth()->attempt($validData)){
            return response()->error('Invalid email or password', Response::HTTP_FORBIDDEN);
        }

        $token = $request->user()->createToken('MyAuthApp')->plainTextToken;

        return new UserResource(auth()->user(), $token);
    }

    /**
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function register(RegisterRequest $request): UserResource
    {
        $validData = $request->all();

        // user create
        $user = User::create([
            'name' => $validData['name'],
            'email' => $validData['email'],
            'password' => bcrypt($validData['password']),
        ]);

        auth()->login($user);

        $token = $request->user()->createToken('MyAuthApp')->plainTextToken;

        // response
        return new UserResource($user, $token);
    }

    /**
     * @param ChangePasswordRequest $request
     * @return UserResource
     */
    public function changePassword(ChangePasswordRequest $request): UserResource
    {
        // Validation data
        $validData = $request->all();

        // Check User password
        if (! Hash::check($validData['password'], $request->user()->password)) {
            return response()->error('Invalid password', Response::HTTP_FORBIDDEN);
        }

        // Update user password and renew api_token
        auth()->user()->update([
            'password' => bcrypt($validData['new_password']),
        ]);

        // Revoke all tokens...
        auth()->user()->tokens()->delete();

        $token = $request->user()->createToken('MyAuthApp')->plainTextToken;

        // response
        return new UserResource(auth()->user(), $token);
    }
}
