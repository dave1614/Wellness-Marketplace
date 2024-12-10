<?php

namespace App\Http\Middleware;

use App\Functions\UsefulFunctions;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

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
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $functions = new UsefulFunctions();
        $user = $request->user();
        $notifications = [];
        $unread_notis = 0;

        if (!is_null($user)) {
            $user = User::find($user->id);

            // $notifications = $user->notifications()->limit(6)->get();
            
            // if ($notifications->count() > 0) {
            //     foreach ($notifications as $row) {
            //         $date = date('j M Y', strtotime($row->created_at));
            //         $time = date('h:i:s a', strtotime($row->created_at));

            //         $row->social_time = $functions->getSocialMediaTime($date, $time);
            //     }
            // }

            $unread_notis = $user->notifications()->where('read_at', null)->get()->count();
            // dd($unread_notis);
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    'data' => $request->session()->get('data'),
                ];
            },
            'unread_notis' => $unread_notis,
            // 'notifications_details' => [
            //     'data' => $notifications,
            //     'unread_notis' => $unread_notis,
            // ],
        ];
    }
}
