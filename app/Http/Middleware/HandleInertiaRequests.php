<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                'has_slowtow_admin_role' => (Auth::check()) ? $request->user()->hasRole('slowtow-admin') : null,
                'has_company_admin_role' => (Auth::check()) ? $request->user()->hasRole('company-admin') : null,
                'has_slowtow_user_role' => (Auth::check()) ? $request->user()->hasRole('slowtow-user') : null,
                
            ],
            'ziggy' => function () {
                return (new Ziggy)->toArray();
            },
            'currentRoute' => fn () => Route::currentRouteName(),
            'success' => fn () => $request->session()->get('success'),
            'error' => fn () => $request->session()->get('error'),
            'slug' => fn () => $request->slug,//this guy handles every slug passed in url..
            
        ]);
    }
}
