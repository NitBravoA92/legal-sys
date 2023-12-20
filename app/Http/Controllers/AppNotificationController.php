<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppNotificationController extends Controller
{
    //
    public function client(){
        $user_notifications_update = AppNotifications::where('user_id', Auth::user()->id)->update([
            "read_at" => now()
        ]);

        $user_notifications = AppNotifications::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        session()->put('notifications', $user_notifications);
        session()->put('notifications_unred', 0);

        return view('Notifications.index', [
            'notifications' => $user_notifications,
            'current_page' => __('content.notifications')
        ]);
    }

    public function client_update(){
        $user_notifications = AppNotifications::select("*")->where('user_id', Auth::user()->id)->whereNull('read_at')->update([
            "read_at" => now()
        ]);

        $user_notifications = AppNotifications::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        session()->put('notifications', $user_notifications);

        $user_notifications_unred = AppNotifications::select("*")->where('user_id', Auth::user()->id)->whereNull('read_at')->get();
        $unred_notif = $user_notifications_unred == null ? 0 : $user_notifications_unred->count();
        session()->put('notifications_unred', $unred_notif);

        return [
            "result" => "success"
        ];
    }

    public function management(){
        $user_notifications_update = AppNotifications::where('user_id', Auth::user()->id)->update([
            "read_at" => now()
        ]);

        $user_notifications = AppNotifications::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        session()->put('notifications', $user_notifications);
        session()->put('notifications_unred', 0);

        return view('Notifications.index', [
            'notifications' => $user_notifications,
            'current_page' => __('content.notifications')
        ]);
    }

    public function management_update(){
        $user_notifications = AppNotifications::where('user_id', Auth::user()->id)->update([
            "read_at" => now()
        ]);

        $user_notifications = AppNotifications::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        session()->put('notifications', $user_notifications);

        $user_notifications_unred = AppNotifications::select("*")->where('user_id', Auth::user()->id)->whereNull('read_at')->get();
        $unred_notif = $user_notifications_unred == null ? 0 : $user_notifications_unred->count();
        session()->put('notifications_unred', $unred_notif);

        return [
            "result" => "success"
        ];
    }

}
