<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\View;

class ResetController extends Controller
{
    public function createClient()
    {
        App::setLocale('es');
        $setting = Setting::find(1);
        return view('session/reset-password/sendEmail_client', [
            "setting" => $setting
        ]);
        
    }

    public function createManagement()
    {
        App::setLocale('es');
        $setting = Setting::find(1);
        return view('session/reset-password/sendEmail', [
            "setting" => $setting
        ]);
        
    }

    public function sendEmailClient(Request $request)
    {
        
        if(env('IS_DEMO'))
        {
            return redirect()->back()->withErrors(['msg2' => 'You are in a demo version, you can\'t recover your password.']);
        }
        else{
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                        ? back()->with(['success' => __($status)])
                        : back()->withErrors(['email' => __($status)]);
        }
        
    }

    public function sendEmailManagement(Request $request)
    {
        
        if(env('IS_DEMO'))
        {
            return redirect()->back()->withErrors(['msg2' => 'You are in a demo version, you can\'t recover your password.']);
        }
        else{
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                        ? back()->with(['success' => __($status)])
                        : back()->withErrors(['email' => __($status)]);
        }
        
    }

    public function resetPassClient($token)
    {
        App::setLocale('es');
        $setting = Setting::find(1);
        return view('session/reset-password/resetPasswordClient', ['token' => $token, "setting" => $setting]);
    }

    public function resetPassManagement($token)
    {
        App::setLocale('es');
        $setting = Setting::find(1);
        return view('session/reset-password/resetPassword', ['token' => $token, "setting" => $setting]);
    }

}
