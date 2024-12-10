<?php

namespace App\Http\Controllers;

use App\Functions\UsefulFunctions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{

    public $functions;

    public function __construct()
    {
        $this->functions = new UsefulFunctions();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $props['currentRouteName'] = 'Notifications';
        $user = Auth::user();
        $user = User::find($user->id);
        $notifications = $user->notifications()->paginate(10);
        if ($notifications->count() > 0) {
            foreach ($notifications as $row) {
                $date = date('j M Y', strtotime($row->created_at));
                $time = date('h:i:s a', strtotime($row->created_at));

                $row->social_time = $this->functions->getSocialMediaTime($date, $time);
            }
        }

        $props['notifications'] = $notifications;
        return Inertia::render('Notifications/Index', $props);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DatabaseNotification $notification)
    {

        $user = Auth::user();
        if ($notification->notifiable_id == $user->id) {
            $notification->markAsRead();

            $props['currentRouteName'] = 'Notification';
            $notification->date = date('j M Y', strtotime($notification->created_at));
            $notification->time = date('h:i:s a', strtotime($notification->created_at));

            $notification->read_at_str = date('j M Y h:i:s a', strtotime($notification->read_at));
            // return $notification;
            $props['notification'] = $notification;
            return Inertia::render('Notifications/Show', $props);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
