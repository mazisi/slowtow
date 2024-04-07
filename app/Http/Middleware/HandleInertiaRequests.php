<?php

namespace App\Http\Middleware;

use App\Models\Licence;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class HandleInertiaRequests extends Middleware
{
    /**
     * Author: Mazisi Msebele
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

    public function isProduction()
    {
        if (app()->environment(['production'])) {
            return  env('BLOB_FILE_PATH');
        }
        return env('APP_URL');
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
            //Global Licence.
            'viewed_licence' => Licence::whereSlug(request('slug'))->first(['id','status','trading_name','type','is_new_app']),
            'currentRoute' => fn () => Route::currentRouteName(),
            'success' => fn () => $request->session()->get('success'),
            'error' => fn () => $request->session()->get('error'),
            'blob_file_path' => fn () => $this->isProduction(),
            'slug' => fn () => $request->slug,
            'message' => fn () => $request->session()->get('message'),
            
        ]);
    }
}
