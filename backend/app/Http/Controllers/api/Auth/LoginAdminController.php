<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class LoginAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $auth;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return \response()->json([
                'success' => false,
                'errors' => [
                    'You\'ve been locked out'
                ],
            ]);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        //attempt login with token
        if ($request->input('token')) {
            $this->auth->setToken($request->input('token'));

            $user = $this->auth->authenticate();

            $userDB = User::where(['id' => $user['id']])->first();

            if ($userDB['role_id'] === 2 || $userDB['role_id'] === 1) {
                return \response()->json([
                    'success' => true,
                    'data' => $request->user(),
                    'token' => $request->input('token'),
                ], 200);
            } else {
                return \response()->json([
                    'success' => false,
                    'errors' => [
                        'text' => [
                            'Access Denied You don\'t have permission to access'
                        ]
                    ]
                ], 403);
            }
        }

        try {
            if (!$token = $this->auth->attempt($request->only('email', 'password'))) {
                return \response()->json([
                    'success' => false,
                    'errors' => [
                        'text' => [
                            'Invalid email address or password'
                        ]
                    ]
                ], 422);
            }
        } catch (JWTException $exception) {
            return \response()->json([
                'success' => false,
                'errors' => [
                    'text' => [
                        'Invalid email address or password'
                    ]
                ]
            ], 422);
        }

        $user = User::where(['id' => $request->user()['id']])->first();

        if ($user['role_id'] === 2 || $user['role_id'] === 1) {
            return \response()->json([
                'success' => true,
                'data' => $request->user(),
                'token' => $token,
            ], 200);
        } else {
            return \response()->json([
                'success' => false,
                'errors' => [
                    'text' => [
                        'Access Denied You don\'t have permission to access'
                    ]
                ]
            ], 422);
        }
    }
}
