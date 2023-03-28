<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSocial;
use Laravel\Socialite\Facades\Socialite;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class SocialLoginController extends Controller
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
        //$this->middleware('social');
    }

    public function redirect($service)
    {
        return Socialite::driver($service)->stateless()->redirect();
    }

    public function callBack($service)
    {

        try {
            $serviceUser = Socialite::driver($service)->stateless()->user();
        } catch (\Exception $exception) {
            return redirect(config('app.front_url') . '/auth/social-callback?error=Unable to login using '
                . $service . ' Please try again' . '&origin=login'
            );
        }

        //$serviceUser = Socialite::driver($service)->stateless()->user();
        $email = $serviceUser->getEmail();

        if ($service !== 'google' || $service !== 'github') {
            $email = $serviceUser->getId() . '@' . $service . '.local';
        }

        $user = $this->getExistingUser($serviceUser, $email, $service);
        $newUser = false;
        if (!$user) {
            $newUser = true;

            $user = User::create([
                'name' => $serviceUser->getName(),
                'email' => $email,
                'password' => '',
            ]);
        }

        if ($this->needsToCreateSocial($user, $service)) {
            UserSocial::create([
                'user_id' => $user->id,
                'social_id' => $serviceUser->getId(),
                'service' => $service,
            ]);
        }

        return redirect(config('app.front_url') . '/auth/social-callback?token=' . $this->auth->fromUser($user)
            . '&origin=' . ($newUser ? 'register' : 'login')
        );
    }

    public function needsToCreateSocial(User $user, $service)
    {
        return !$user->hasSocialLinked($service);
    }

    public function getExistingUser($serviceUser, $email, $service)
    {
        if ($service === 'google') {
            return User::where('email', $email)->orWhereHas('social', function ($q) use ($serviceUser, $service) {
                $q->where('social_id', $serviceUser->getId())->where('service', $service);
            })->first();
        } else {
            $userSocial = UserSocial::where('social_id', $serviceUser->getId())->first();

            return $userSocial ? $userSocial->user : null;
        }
    }
}
