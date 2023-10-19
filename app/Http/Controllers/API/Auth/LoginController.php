<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $authCheck = auth()->attempt(
            $request->only('email', 'password')
        );

        if ($authCheck === false) {
            return response()->json([
                'status' => false,
                'message' => 'Input yang anda masukkan salah',
                'data' => [],
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login Berhasil',
            'data' => [
                'token_access' => $authCheck,
            ],
        ]);
    }
}
