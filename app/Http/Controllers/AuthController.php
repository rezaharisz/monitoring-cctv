<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        return $this->authService->register($request);
    }

    public function detail()
    {
        return $this->authService->detail();
    }

    public function update(Request $request)
    {
        return $this->authService->update($request);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $rules = [
            "username" => "required|string",
            "password" => "required|string",
            "device_token" => "required|string",
        ];

        $messages = [
            "username.required" => "Username harus diisi",
            "password.required" => "Password harus diisi",
            "device_token.required" => "Device Token harus diisi"
        ];

        $validate = Validator::make($request->all(), $rules, $messages);
        if ($validate->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validate->errors()->first(),
            ], 400);
        }

        $credentials = request(['username', 'password']);

        if (!$token = auth()->guard("api")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('username', $request->username)->first();
        if ($user->device_token && $user->device_token != $request->device_token) {
            return response()->json([
                "status" => "error",
                "message" => "Akun anda masih aktif di perangkat lain, silahkan lakukan logout terlebih dahulu"
            ], 400);
        }

        // jika belum pernah di set device token, set dengan device token pertama login
        if (!$user->device_token) {
            // cek dulu apakah token sudah dipakai orang lain atau belum
            $existingToken = User::where("device_token", $request->device_token)->first();
            if ($existingToken) {
                return response()->json([
                    "status" => "error",
                    "message" => "Device anda masih aktif menggunakan akun lama dengan username '" . $existingToken->username . "' silahkan lakukan login ulang kemudian logout menggunakan akun tersebut"
                ], 400);
            }
            $user->update(["device_token" => $request->device_token]);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        User::where("id", auth()->user()->id)->update(["device_token" => null]);
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 // default ttl sudah diubah ke 1 tahun
        ]);
    }
}
