<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return $this->sendError(
                    'Invalid Credentials',
                    'Email or password is incorrect',
                    401
                );
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return $this->sendSuccess([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->fullname,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ], 'Login successful', 200);
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error', null, 422, $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->sendSuccess(null, 'Logged out successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user();
            return $this->sendSuccess($user, 'User retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }
}
