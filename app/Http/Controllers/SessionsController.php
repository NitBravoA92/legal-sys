<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SessionsController extends Controller
{
    public function create_client()
    {
        App::setLocale('es');
        $setting = Setting::find(1);
        return view('session.login-client-session', [
            "setting" => $setting,
            "message" => null
        ]);
    }
    public function create_management()
    {
        App::setLocale('es');
        $setting = Setting::find(1);
        return view('session.login-session', [
            "setting" => $setting
        ]);
    }

    public function store_clients()
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);
        $attributes['role'] = 'CUSTOMER';
        
        if(Auth::attempt($attributes))
        {
            if(Auth::user()->status == 'active'){
                session()->regenerate();
                session()->put('language', 'es');
                
                $setting = Setting::find(1);
                $logo = $setting->app_logo != '' ? Storage::url($setting->app_logo) : '';
                
                session()->put('setting', $setting);
                session()->put('language', 'en');
                session()->put('logo', $logo);

                $user_notifications = AppNotifications::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);                 
                session()->put('notifications', $user_notifications);

                $user_notifications_unred = AppNotifications::select("*")->where('user_id', Auth::user()->id)->whereNull('read_at')->get();                 
                $unred_notif = $user_notifications_unred == null ? 0 : $user_notifications_unred->count();
                session()->put('notifications_unred', $unred_notif);

                return redirect()->route('summery-client')->with(['success'=> __('content.messages.success.user_sign_in') ]);
            } else{
                Auth::logout();
                return back()->withErrors(['email'=> __('content.messages.errors.user_block_sign_in') ]);
            }
        }
        else{
            return back()->withErrors(['email'=> __('content.messages.errors.email_password_invalid') ]);
        } 
    }

    public function store_management()
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required' 
        ]);
        if(Auth::attempt($attributes))
        {
            if(Auth::user()->status == 'active'){
                session()->regenerate();
                session()->put('language', 'es');

                $setting = Setting::find(1);
                $logo = $setting->app_logo != '' ? Storage::url($setting->app_logo) : '';

                session()->put('setting', $setting);
                session()->put('logo', $logo);
                session()->put('language', 'en');

                $user_notifications = AppNotifications::where('user_id', Auth::user()->id)->paginate(10);                
                session()->put('notifications', $user_notifications);

                $user_notifications_unred = AppNotifications::select("*")->where('user_id', Auth::user()->id)->whereNull('read_at')->get();                 
                $unred_notif = $user_notifications_unred == null ? 0 : $user_notifications_unred->count();
                session()->put('notifications_unred', $unred_notif);
 
                if(Auth::user()->role == "CALL CENTER"){
                    return redirect()->route('clients-management.index')->with(['success'=> __('content.messages.success.user_sign_in') ]);
                } else if(Auth::user()->role == "ADMINISTRATOR"){
                    return redirect()->route('summery-accounter')->with(['success'=>__('content.messages.success.user_sign_in') ]);
                } else if(Auth::user()->role == "SUPER ADMINISTRATOR"){
                    return redirect()->route('summery-admin')->with(['success'=>__('content.messages.success.user_sign_in') ]);
                } else{
                    return redirect('/management-area/assigned-service-orders')->with(['success'=> __('content.messages.success.user_sign_in') ]);
                }
            } else {
                Auth::logout();
                return back()->withErrors(['email'=> __('content.messages.errors.user_block_sign_in') ]);
            }
        }
        else{
            return back()->withErrors(['email'=> __('content.messages.errors.email_password_invalid') ]);
        }
    }

    public function destroy()
    {
        $route_login = '';
        if(auth()->user()->role != 'CUSTOMER'){
            $route_login = '/management-area/login';
        } else{
            $route_login = '/client-area/login';
        } 
        Auth::logout();
        return redirect($route_login)->with(['success'=> __('content.messages.success.user_sign_out') ]);
    }
}
