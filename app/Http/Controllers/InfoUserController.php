<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class InfoUserController extends Controller
{
    public function create()
    {
        return view('Users/user-profile', ['current_page' => __('content.users.profile_information')]);
    }

    public function store(Request $request)
    {
        $id = Auth::user()->id;
        $attributes = request()->validate([
            'role' => ['required', 'max:50'],
            'name' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            'phone' => ['required', 'max:50'],
            'alt_phone' => ['max:50'],
            'location' => ['max:70'],
            'about_me'    => ['max:150'],
            'photo' => ['mimes:jpeg,jpg,png,gif,svg|size:2048']
        ]);

        $file_name = "";
        $file_path = "";

        $attribute = "";

        if($request->get('email') != Auth::user()->email)
        {
            if(env('IS_DEMO') && $id == 1)
            {
                return redirect()->back()->withErrors(['msg2' => 'You are in a demo version, you can\'t change the email address.']);  
            }  
        }
        else{
            $attribute = request()->validate([
                'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            ]);
        }

        $data = [
            'role' => $attributes['role'],
            'name'    => $attributes['name'],
            'lastname' => $attributes['lastname'],
            'email' => $attribute['email'],
            'phone' => $attributes['phone'],
            'alt_phone' => $attributes['alt_phone'],
            'location' => $attributes['location'],
            'about_me'    => $attributes["about_me"]
        ];

        if($request->hasFile('photo')){
            $file_name = request()->file('photo')->getClientOriginalName();
            $file_path = request()->file('photo')->store('public/profiles/user-' . $id);
            $attributes['photo'] = $file_path;

            $data = [
                'role' => $attributes['role'],
                'name'    => $attributes['name'],
                'lastname' => $attributes['lastname'],
                'email' => $attribute['email'],
                'phone' => $attributes['phone'],
                'alt_phone' => $attributes['alt_phone'],
                'location' => $attributes['location'],
                'about_me'    => $attributes["about_me"],
                'photo' => $attributes['photo']
            ];
        }

        User::where('id', $id)
        ->update($data);
        return redirect()->back()->with('success', __('content.messages.success.profile_updated') );
    }
}
