<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use App\Models\Client;
use App\Models\Document;
use App\Models\Notes;
use App\Models\Order;
use App\Models\OrderAccounters;
use App\Models\OrderNotifications;
use App\Models\OrderNotificationsReceivedBy;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use App\Providers\UserWelcome;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class ClientController extends Controller
{
    public function index(){
        if(Auth::user()->role == 'ADMINISTRATOR'){
        $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client')->get();
        return view('Clients.index', [
            'clients' => $client,
            'current_page' => __('content.users.clients')
        ]);
    } else {
        return redirect()->back();
    }
    }

    public function create(){
        if(Auth::user()->role == 'ADMINISTRATOR'){
        return view('Clients.create', [
            'current_page' => __('content.users.clients')
        ]);
    } else {
        return redirect()->back();
    }
    }

    public function store(Request $request)
    {
        if(Auth::user()->role == 'ADMINISTRATOR'){
        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'phone' => ['required', 'max:20'],
            'alt_phone' => ['max:20'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:20'],
            'location' => ['max:100']
        ]);
        $attributes['password'] = bcrypt($attributes['password'] ); //encryp password
        $role = 'CUSTOMER';

        $data = [
            'role' => $role,
            'name'    => $attributes['name'],
            'lastname' => $attributes['lastname'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'phone' => $attributes['phone'],
            'alt_phone' => $attributes['alt_phone'],
            'location' => $attributes['location'],
            'status' => 'active',
            'email_verified_at' => now()
        ];

        $user = User::create($data);
        $data_user = [
            'user_id' => $user->id,
        ];
        $client = Client::create($data_user);

        $long_notification = '
        <li><a class="dropdown-item border-radius-md" href="javascript:;">
        <div class="d-flex py-1">
            <div class="avatar avatar-sm bg-gradient-info  me-3  my-auto">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="d-flex flex-column justify-content-center">
            <h6 class="text-sm font-weight-normal mb-1">
                '. __('content.users.dear_user') . ', ' . $attributes['name'] . ' ' . $attributes['name'] . ' ' . __('content.mails.welcome_to') . ' ' . $setting->app_name . '
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
                <div class="">' . __('content.users.dear_user') . ', ' . $attributes['name'] . ' ' . $attributes['name'] . ': <span class="font-weight-bolder mb-0 text-capitalize">' . __('content.mails.welcome_to') . ' ' . $setting->app_name . '</span></div>
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

        return redirect()->route('clients.index')->with('success', __('content.messages.success.client_created') );
    } else {
        return redirect()->back();
    }

    }

    public function show(Client $client)
    {
        if(Auth::user()->role == 'ADMINISTRATOR' || Auth::user()->role == 'SUPER ADMINISTRATOR'){
        $id = $client->id;
        $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client', 'users.created_at as userCreated')->find($id);
        $orders = Order::join('products', 'orders.product_id', '=', 'products.id')->select('products.*', 'orders.id as orderID')->whereRaw('orders.client_id = ?', [$id])->get();

        $documents = Document::join('orders', 'documents.order_id', '=', 'orders.id')
                                  ->join('clients', 'orders.client_id', '=', 'clients.id')
                                  ->select('documents.*')
                                  ->whereRaw('orders.client_id = ?', [$id])->get();

        $notes = Notes::join('workers', 'notes.worker_id', '=', 'workers.id')
                      ->join('users', 'workers.user_id', '=', 'users.id')
                      ->select('notes.*', 'users.name as worker_name', 'users.lastname as worker_lastname', 'users.photo as worker_photo')
                      ->whereRaw('notes.client_id = ?', [$id])->get();

        $notifications_group = OrderNotifications::join('orders', 'order_notifications.order_id', '=', 'orders.id')
                                                  ->join('clients', 'orders.client_id', '=', 'clients.id')
                                                  ->join('products', 'orders.product_id', '=', 'products.id')
                                                  ->select('order_notifications.*', 'orders.id as id_order', 'products.name as service')
                                                  ->whereRaw('orders.client_id = ?', [$id])->get();

        $notifications = [];
        $notif_length = $notifications_group->count();

        $user_maker_fullname = "";
        $user_maker_photo = "";
        $n = 0;

        foreach($notifications_group as $notif){
            if($notif->type == 'SEND'){
                $user_maker_fullname = $client->name . " " . $client->lastname;
                $user_maker_photo = $client->photo;
            } else{
                $receiver_data = OrderNotificationsReceivedBy::join('workers', 'order_notifications_received_bies.worker_id', 'workers.id')
                                                             ->join('users', 'workers.user_id', 'users.id')
                                                             ->select('order_notifications_received_bies.*', 'users.name as worker_name', 'users.lastname as worker_lastname', 'users.photo as worker_photo')
                                                             ->whereRaw('order_notifications_received_bies.order_notifications_id = ?', [$notif->id])
                                                             ->first();

                $user_maker_fullname = $receiver_data == null ? '': $receiver_data->worker_name . " " . $receiver_data->worker_lastname;
                $user_maker_photo = $receiver_data == null ? '' : $receiver_data->worker_photo;
            }
            $notif_details = [
                "title" => $notif->title,
                "content" => $notif->content,
                "type" => $notif->type,
                "order_id" => $notif->order_id,
                "service" => $notif->service,
                "updated_at" => $notif->updated_at,
                "maker" => [
                    "full_name" => $user_maker_fullname,
                    "photo" => $user_maker_photo
                ]
            ];
            array_push($notifications, $notif_details);
            $n++;
        }

        if($n == $notif_length){
            $data_order = [
                "client" => $client,
                "orders" => $orders,
                "documents" => $documents,
                "notifications" => $notifications,
                "notes" => $notes
            ];
            return view('Clients.show', [
                'client_data' => $data_order,
                'current_page' => __('content.users.clients')
            ]);
        } else{
            $data_order = [
                "client" => $client,
                "orders" => $orders,
                "documents" => $documents,
                "notifications" => $notifications,
                "notes" => $notes
            ];
            return view('Clients.show', [
                'client_data' => $data_order,
                'current_page' => __('content.users.clients')
            ]);
        }
    } else {
        return redirect()->back();
    }
    }

    public function edit(Client $client){
        if(Auth::user()->role == 'ADMINISTRATOR'){
        $id = $client->id;
        $client_info = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client', 'users.created_at as userCreated')->find($id);
        return view('Clients.edit', [
            "client" => $client_info,
            'current_page' => __('content.users.clients')
        ]);
    } else {
        return redirect()->back();
    }
    }

    public function update(Request $request, $id)
    {
        if(Auth::user()->role == 'ADMINISTRATOR'){
        $client = Client::find($id);
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'phone' => ['required', 'max:20'],
            'alt_phone' => ['max:20'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($client->user_id)],
            'location' => ['max:100'],
            'password' => []
        ]);
        $role = 'CUSTOMER';

        $data = [
            'role' => $role,
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
                'role' => $role,
                'name'    => $attributes['name'],
                'lastname' => $attributes['lastname'],
                'email' => $attributes['email'],
                'password' => $attribute['password'],
                'phone' => $attributes['phone'],
                'alt_phone' => $attributes['alt_phone'],
                'location' => $attributes['location'],
            ];
        }

        User::where('id', $client->user_id)
        ->update($data);


        return redirect()->route('clients.index')->with('success', __('content.messages.success.client_updated') );
    } else {
        return redirect()->back();
    }

    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy(Request $request, $id)
    {
        if(Auth::user()->role == 'ADMINISTRATOR'){
        $orders = Order::where('client_id', $id)->count();
        if($orders != 0) {
            return redirect()->back()->with('error', __('content.messages.errors.client_deleted') );
        } else {
            //delete the user
            $client = Client::find($id);
            $user = User::where('id', $client->user_id)->delete();
            return redirect()->route('clients.index')->with('success',  __('content.messages.success.client_deleted') );
        }
    } else {
        return redirect()->back();
    }

    }


    public function block(Request $request, $id){

    }
}
