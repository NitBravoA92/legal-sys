<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
        // save setting changes
        $attributes = request()->validate([
            'app_name' => ['required', 'max:100'],
            'app_owner' => ['required', 'max:100'],
            'app_address' => ['required', 'max:255'],
            'app_email' => ['required', 'email', 'max:50'],
            'app_phone' => ['required', 'max:20'],
            'app_logo' => ['max:2048'],
            'about_us' => ['required', 'max:510']
        ]);
        $file_path = "";
        if(request()->hasFile('app_logo')){
            $file_folder = 'public/setting/logo/';
            $file_name = request()->file('app_logo')->getClientOriginalName();
            $file_path = request()->file('app_logo')->store($file_folder);
        }
        $attributes['app_logo'] = $file_path;

        Setting::where('id', 1)->update($attributes);
        $setting = Setting::find(1);
        $logo = $setting->app_logo != '' ? Storage::url($setting->app_logo) : '';
        session()->put('logo', $logo);
        session()->put('setting', $setting);
        $logo_config = $setting->app_logo != '' ? config('app.url') . Storage::url($setting->app_logo) : config('app.url') . '/assets/img/logos/system-logo.png';

        //config setting
        config(['app.name' => $setting->app_name]);
        config(['setting.name' => $setting->app_name]);
        config(['setting.owner' => $setting->app_owner]);
        config(['setting.address' => $setting->app_address]);
        config(['setting.email' => $setting->app_email]);
        config(['setting.phone' => $setting->app_phone]);
        config(['setting.logo' => $logo_config]);
        config(['setting.about_us' => $setting->about_us]);
        config(['setting.language' => 'es']);
        config(['setting.copyright' => $setting->app_name . '. Todos los derechos reservados']);

        return redirect()->back()->with('success', __('content.messages.success.setting_updated') );
    } else {
        return redirect()->back();
    }

    }

    public function terms_conditions(){
        $setting = Setting::find(1);
        return view('terms_conditions', [
            "setting" => $setting
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
