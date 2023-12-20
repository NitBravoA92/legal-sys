<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use App\Providers\ClientRegistered;

use App\Models\Client;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Notifications\NewClientRegistered;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Access\Events\Registered;
use Illuminate\Auth\Events\Registered as EventsRegistered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{

    public function create()
    {
        App::setLocale('es');
        $setting = Setting::find(1);
        return view('session.register', [
            "setting" => $setting
        ]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'role' => ['required', 'max:50'],
            'name' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'phone' => ['required', 'max:50'],
            'alt_phone' => ['max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:5', 'max:20'],
            'agreement' => ['accepted']
        ]);
        $attributes['password'] = bcrypt($attributes['password'] ); //encryp password
        $attributes['role'] = 'CUSTOMER';

        $user = User::create([
            "role" => $attributes['role'],
            "email" => $attributes['email'],
            "password" => $attributes['password'],
            'name' => $attributes['name'],
            'lastname' => $attributes['lastname'],
            'phone' => $attributes['phone'],
            'alt_phone' => $attributes['alt_phone'],
            "agreement" => $attributes['agreement'],
            'status' => 'active'
        ]);

        $data_client = [
            'user_id' => $user->id
        ];

        $client = Client::create($data_client);

        $users_Admins = User::whereRaw('role = ? AND role = ?', ['SUPER ADMINISTRATOR', 'ADMINISTRATOR'])->get();
        $lang = "es";

        $navbar = $lang == "es" ? "Nuevo Cliente Registrado!" : "New Client Registered!";
        $module = $lang == "es" ? "<span class='text-dark'>" . $user->name . " ". $user->lastname . "</span> se ha registrado en el sistema, el " . $user->crerated_at : "<span class='text-dark'>" . $user->name . " ". $user->lastname . "</span> has been registered on the system, at " . $user->crerated_at;

        $notification_data = [
            "navbar" => $navbar,
            "module" => $module
        ];

        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $long_notification = '
        <li>
        <a class="dropdown-item border-radius-md" href="javascript:;">
        <div class="d-flex py-1">
            <div class="avatar avatar-sm bg-gradient-info  me-3  my-auto">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="d-flex flex-column justify-content-center">
            <h6 class="text-sm font-weight-normal mb-1">
                '. __('content.users.dear_user') . ', ' . $attributes['name'] . ' ' . $attributes['lastname'] . ' <span class="font-weight-bolder mb-0 text-capitalize">' . __('content.mails.welcome_to') . ' ' . $setting->app_name . '</span>
            </h6>
            <p class="text-xs text-secondary mb-0">
                <i class="fa fa-clock me-1"></i>
                ' . $user->created_at . '
            </p>
            </div>
        </div>
        </a>
    </li>
        ';

        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-info"><i class="fas fa-handshake"></i></span></a></div>
            <div class="tl-content">
                <div class="">' . __('content.users.dear_user') . ', ' . $attributes['name'] . ' ' . $attributes['lastname'] . ' <span class="font-weight-bolder mb-0 text-capitalize">' . __('content.mails.welcome_to') . ' ' . $setting->app_name . '</span></div>
                <div class="text-xs text-muted mt-1"><i class="fa fa-clock me-1"></i> ' . $user->created_at . '</div>
            </div>
        </div>
        ';

        $notification_data = [
            'data_short' => $short_notification,
            'data_long' => $long_notification,
            'user_id' => $user->id
        ];
        //notification welcome to new client
        $new_notification = AppNotifications::create($notification_data);

        $long_notification = '
        <li>
        <a class="dropdown-item border-radius-md" href="javascript:;">
        <div class="d-flex py-1">
            <div class="avatar avatar-sm bg-gradient-info  me-3  my-auto">
                <i class="fas fa-user"></i>
            </div>
            <div class="d-flex flex-column justify-content-center">
            <h6 class="text-sm font-weight-normal mb-1">
                '. __('content.messages.new_client_registered') . ': <span class="font-weight-bolder mb-0 text-capitalize">' . $attributes['name'] . ' ' . $attributes['lastname'] . '</span>
            </h6>
            <p class="text-xs text-secondary mb-0">
                <i class="fa fa-clock me-1"></i>
                ' . $user->created_at . '
            </p>
            </div>
        </div>
        </a>
    </li>
        ';

        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-info"><i class="fas fa-user"></i></span></a></div>
            <div class="tl-content">
                <div class="">' . __('content.messages.new_client_registered') . ': <span class="font-weight-bolder mb-0 text-capitalize">' . $attributes['name'] . ' ' . $attributes['lastname']. '</span></div>
                <div class="text-xs text-muted mt-1"><i class="fa fa-clock me-1"></i> ' . $user->created_at . '</div>
            </div>
        </div>
        ';

        foreach ($users_Admins as $user_info) {
            $n=0;
            ClientRegistered::dispatch($user_info->email, $user->name, $user->email, $user->phone, $user->created_at, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
            //database notification
            $notification_data = [
                'data_short' => $short_notification,
                'data_long' => $long_notification,
                'user_id' => $user_info->id
            ];
            $new_notification = AppNotifications::create($notification_data);
        }

        //send email verification
        event(new EventsRegistered($user));

        return redirect()->route('verification.notice');

    }

    public function verify()
    {
        App::setLocale('es');
        $setting = Setting::find(1);
        return view('session.verify-email', [
            "setting" => $setting,
            "message" => null
        ]);
    }

    public function send_link(Request $request) {
        App::setLocale('es');
        $setting = Setting::find(1);
        $request->user()->sendEmailVerificationNotification();
        return view('session.verify-email', [
            "setting" => $setting,
            "message" => __('content.messages.verification_link_sent') . '!'
        ]);
    }

    public function user_verified(EmailVerificationRequest $request) {
        $request->fulfill();
        App::setLocale('es');
        $setting = Setting::find(1);

        return view('session.login-client-session', [
            "setting" => $setting,
            "message" => "Your user is verified successfully! Now you can sign in and enjoy our platform."
        ]);
    }

}
