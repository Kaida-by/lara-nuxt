<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SocialMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $services = ['google', 'github', 'vkontakte'];
        $enabledServices = [];

        foreach ($services as $service) {
            if (config('services' . $service)) {
                $enabledServices[] = $service;
            }
        }

        if (!in_array(strtolower($request->service), $enabledServices)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid social service'
                ], 403);
            } else {
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
