<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Pelatihan;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

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
        $rolesID = $request->user() ? $request->user()->roles()->pluck('role_id')->all() : [null];
        $menus = Menu::select('id', 'order', 'route', 'icon', 'name')->whereNull('parent_id')->whereHas('roles', function ($query) use ($rolesID) {
            $query->whereIn('role_id', $rolesID);
        })->with('childs', function ($query) use ($rolesID) {
            $query->select('parent_id', 'id', 'order', 'route', 'icon', 'name')->whereHas('roles', function ($query2) use ($rolesID) {
                $query2->whereIn('role_id', $rolesID);
            });
        })->orderBy('order', 'asc')->get();
        $task = Pelatihan::whereHas('status', function ($query) use ($rolesID) {
            $query->whereIn('role_id', $rolesID);
        })->whereNotIn('status_id',  [1, 6])->count();
        return array_merge(parent::share($request), [
            'app' => [
                'name' => env('APP_NAME'),
            ],
            'base_url' => env('APP_URL'),
            'auth' => [
                'user' => $request->user(),
                'roles' => [
                    'id' => $rolesID,
                ],
                'menus' => $menus
            ],
            'task' => $task,
            'flash' => [
                'msg' => $request->session()->get('flash.msg'),
                'error' => $request->session()->get('flash.error'),
            ],
            // 'ziggy' => function () {
            //     return (new Ziggy)->toArray();
            // },
        ]);
    }
}
