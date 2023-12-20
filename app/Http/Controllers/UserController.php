<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderAccounters;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use App\Providers\UserActive;
use App\Providers\UserBlock;
use App\Providers\UserWelcome;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function index(){
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
        $users = User::whereRaw('role != ?', ['SUPER ADMINISTRATOR'])->orderBy('id', 'asc')->get();
        return view('users.index', ['users' => $users, 'current_page' => __('content.users.users')]);
    } else {
        return redirect()->back();
    }

    }

    public function create(){
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
        return view('users.create', ['current_page' => __('content.users.users')]);
    } else {
        return redirect()->back();
    }

    }

    public function store(Request $request)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $attributes = request()->validate([
            'role' => ['required', 'max:30'],
            'name' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'phone' => ['required', 'max:20'],
            'alt_phone' => ['max:20'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:20'],
            'location' => ['max:100']
        ]);
        $attributes['password'] = bcrypt($attributes['password'] ); //encryp password
        $user = User::create([
            'role' => $attributes['role'],
            'name' => $attributes['name'],
            'lastname' => $attributes['lastname'],
            'phone' => $attributes['phone'],
            'alt_phone' => $attributes['alt_phone'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'location' => $attributes['location'],
            'status' => 'active',
            'email_verified_at' => now()
        ]);

        $data_user = [
            'user_id' => $user->id,
        ];

        if($attributes['role'] == 'CUSTOMER'){
            $client = Client::create($data_user);
        } else {
            $worker = Worker::create($data_user);
        }

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
                <div class="">' . __('content.users.dear_user') . ', ' . $attributes['name'] . ' ' . $attributes['lastname'] . ': <span class="font-weight-bolder mb-0 text-capitalize">' . __('content.mails.welcome_to') . ' ' . $setting->app_name . '</span></div>
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

        //email notification welcome
        UserWelcome::dispatch($user->name . ' ' . $user->lastname, $user->email, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

        return redirect()->route('users.index')->with('success', __('content.messages.success.user_created') );
    } else {
        return redirect()->back();
    }

    }


    public function edit(User $user){
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
            return view('users.edit', compact('user'), ['current_page' => __('content.users.users')]);
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id){
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
        $attributes = request()->validate([
            'role' => ['required', 'max:30'],
            'name' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'phone' => ['required', 'max:20'],
            'alt_phone' => ['max:20'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($id)],
            'location' => ['max:100'],
            'password' => []
        ]);

        $data = [
            'role' => $attributes['role'],
            'name'    => $attributes['name'],
            'lastname' => $attributes['lastname'],
            'email' => $attributes['email'],
            'phone' => $attributes['phone'],
            'alt_phone' => $attributes['alt_phone'],
            'location' => $attributes['location']
        ];
        if($attributes['password'] != ''){
            $attribute = request()->validate([
                'password' => ['min:8', 'max:20']
            ]);
            $attribute['password'] = bcrypt($attribute['password']); //encryp the password

            $data = [
                'role' => $attributes['role'],
                'name'    => $attributes['name'],
                'lastname' => $attributes['lastname'],
                'email' => $attributes['email'],
                'password' => $attribute['password'],
                'phone' => $attributes['phone'],
                'alt_phone' => $attributes['alt_phone'],
                'location' => $attributes['location'],
            ];
        }
        User::where('id', $id)
        ->update($data);
        return redirect()->route('users.index')->with('success', __('content.messages.success.user_updated') );
    } else {
        return redirect()->back();
    }

    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy($id)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
            
        $orders = Order::join('clients', 'orders.client_id', 'clients.id')->join('users', 'clients.user_id', 'users.id')->whereRaw('users.id = ?', [$id])->first();
        $accounters = OrderAccounters::join('workers', 'order_accounters.worker_id', 'workers.id')->join('users', 'workers.user_id', 'users.id')->whereRaw('users.id = ?', [$id])->first();

        if($orders != null || $accounters != null) {
            return redirect()->back()->with('error', __('content.messages.errors.user_deleted') );
        } else {
        //delete the user
        $user = User::where('id', $id)->delete();
        return redirect()->route('users.index')->with('success', __('content.messages.success.user_deleted') );
        }

    } else {
        return redirect()->back();
    }

    }

    public function block($id){
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
        //block the user
        $user = User::where('id', $id)->update([
            'status' => 'blocked'
         ]);

         $user_data = User::find($id);

         $setting = Setting::find(1);
         $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

         $long_notification = '
         <li>
         <a class="dropdown-item border-radius-md" href="javascript:;">
         <div class="d-flex py-1">
             <div class="avatar avatar-sm bg-gradient-danger  me-3  my-auto">
                 <i class="fas fa-user-lock"></i>
             </div>
             <div class="d-flex flex-column justify-content-center">
             <h6 class="text-sm font-weight-normal mb-1">
                 '. __('content.users.dear_user') . ', ' . $user_data->name . ' ' . $user_data->lastname . ': <span class="font-weight-bolder mb-0 text-capitalize">' . __('content.mails.your_user_is_block') . '</span>
             </h6>
             <p class="text-xs text-secondary mb-0">
                 <i class="fa fa-clock me-1"></i>
                 ' . $user_data->updated_at . '
             </p>
             </div>
         </div>
         </a>
     </li>
         ';

         $short_notification = '
         <div class="tl-item">
             <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-danger"><i class="fas fa-user-lock"></i></span></a></div>
             <div class="tl-content">
                 <div class="">' . __('content.users.dear_user') . ', ' . $user_data->name . ' ' . $user_data->lastname . ': <span class="font-weight-bolder mb-0 text-capitalize">' . __('content.mails.your_user_is_block') . '</span></div>
                 <div class="text-xs text-muted mt-1"><i class="fa fa-clock me-1"></i> ' . $user_data->updated_at . '</div>
             </div>
         </div>
         ';

         $notification_data = [
             'data_short' => $short_notification,
             'data_long' => $long_notification,
             'user_id' => $user_data->id
         ];

         //notification welcome to new client
         $new_notification = AppNotifications::create($notification_data);

        //email notification
         UserBlock::dispatch($user_data->name, $user_data->email, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

         return redirect()->route('users.index')->with('success', __('content.messages.success.user_blocked') );
        } else {
            return redirect()->back();
        }
    }

    public function active($id){
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
        //unblock the user
        $user = User::where('id', $id)->update([
            'status' => 'active'
         ]);

         $user_data = User::find($id);

         $setting = Setting::find(1);
         $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

         $long_notification = '<li>
         <a class="dropdown-item border-radius-md" href="javascript:;">
         <div class="d-flex py-1">
             <div class="avatar avatar-sm bg-gradient-success  me-3  my-auto">
                 <i class="fas fa-user-check"></i>
             </div>
             <div class="d-flex flex-column justify-content-center">
             <h6 class="text-sm font-weight-normal mb-1">
                 '. __('content.users.dear_user') . ', ' . $user_data->name . ' ' . $user_data->lastname . ': <span class="font-weight-bolder mb-0 text-capitalize">' . __('content.mails.your_user_is_active') . '</span>
             </h6>
             <p class="text-xs text-secondary mb-0">
                 <i class="fa fa-clock me-1"></i>
                 ' . $user_data->updated_at . '
             </p>
             </div>
         </div>
         </a>
     </li>';

         $short_notification = '
         <div class="tl-item">
             <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-success"><i class="fas fa-user-check"></i></span></a></div>
             <div class="tl-content">
                 <div class="">' . __('content.users.dear_user') . ', ' . $user_data->name . ' ' . $user_data->lastname . ': <span class="font-weight-bolder mb-0 text-capitalize">' . __('content.mails.your_user_is_active') . '</span></div>
                 <div class="text-xs text-muted mt-1"><i class="fa fa-clock me-1"></i> ' . $user_data->updated_at . '</div>
             </div>
         </div>';

         $notification_data = [
             'data_short' => $short_notification,
             'data_long' => $long_notification,
             'user_id' => $user_data->id
         ];

         //notification welcome to new client
         $new_notification = AppNotifications::create($notification_data);

         //email notification
         UserActive::dispatch($user_data->name, $user_data->email, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
         return redirect()->route('users.index')->with('success', __('content.messages.success.user_active') );
        } else {
            return redirect()->back();
        }
    }
}
