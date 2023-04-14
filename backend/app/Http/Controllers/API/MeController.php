<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class MeController extends Controller
{
    protected JWTAuth $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->user(),
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->auth->invalidate();

        return response()->json([
            'success' => true,
        ]);
    }
}
