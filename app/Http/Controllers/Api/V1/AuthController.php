<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AuthController extends Controller
{
    //
        /**
     * Register a new user and return an API token for them.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // The "hashed" cast on User::password takes care of hashing this for us
        $user = User::create($request->validated());

        // "api" is just a label for the token, it shows up later in personal_access_tokens.name
        $token = $user->createToken('api')->plainTextToken;

        return (new UserResource($user))
            ->additional(['token' => $token])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Log in an existing user and return a fresh API token for them.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // authenticate() checks the email/password and throws a validation error if they don't match
        $user = $request->authenticate();

        $token = $user->createToken('api')->plainTextToken;

        return (new UserResource($user))
            ->additional(['token' => $token])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Revoke the token that was used to make this request.
     */
    public function logout(Request $request): JsonResponse
    {
        // currentAccessToken() only exists on requests authenticated via Sanctum
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Return the currently authenticated user.
     */
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
