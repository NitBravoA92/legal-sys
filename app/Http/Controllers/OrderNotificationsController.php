<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use App\Models\Client;
use App\Models\Document;
use App\Models\Order;
use App\Models\OrderAccounters;
use App\Models\OrderNotifications;
use App\Models\OrderNotificationsFilesAttach;
use App\Models\OrderNotificationsReceivedBy;
use App\Models\Setting;
use App\Models\User;
use App\Models\Worker;
use App\Providers\OrderMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderNotificationsController extends Controller
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
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, $id)
    {

        if(Auth::user()->role == 'CUSTOMER'){

        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $attributes = request()->validate([
            'title' => ['required', 'max:255'],
            'content' => ['required', 'max:1000'],
        ]);

        $status_notif = $request->has('_index_tokens_') ? 'file attached' : 'simple';

        $dataNotification = [
            'title' => $attributes['title'],
            'type' => 'SEND',
            'content' => $attributes['content'],
            'status' => $status_notif,
            'order_id' => $id
        ];

        $notification = OrderNotifications::create($dataNotification);

        //save files if exist
        if($request->has('_index_tokens_')){
            $files_indexes = $request->input('_index_tokens_');
            $file_name = '';
            $file_folder = 'public/files/customers/customer-' . Auth::user()->id . '/orders/order-'. $id;
            $file_path = '';

            for ($i=0; $i < count($files_indexes); $i++) {
                $id_file = (string) $files_indexes[$i];

                //validate file
                $fileValidate = request()->validate([
                    'addic_doc_final_'.$id_file => ['max:20480']
                ]);

                //get the name of the file
                $file_name = $request->file('addic_doc_final_'.$id_file)->getClientOriginalName();

                //validate file name
                if(Document::where('name', $file_name)->exists()){
                    $file_name = date('Y-m-d_H-i-s') . "_" . $file_name;
                }

                //store the file
                $file_path = $request->file('addic_doc_final_'.$id_file)->store($file_folder);

                //prepare data to save in db
                $dataDocument = [
                    'name' => $file_name,
                    'path' => $file_path,
                    'type' => 'ADDITIONAL',
                    'order_id' => $id,
                ];

                // save the documents
                $doc = Document::create($dataDocument);

                // store the details
                OrderNotificationsFilesAttach::create([
                    'order_notifications_id' => $notification->id,
                    'document_id' => $doc->id
                ]);
            }
        }

        $order = Order::join('products', 'orders.product_id', 'products.id')->join('clients', 'orders.client_id', 'clients.id')->join('users', 'clients.user_id', 'users.id')->select('products.*', 'users.name as client_name', 'users.lastname as client_lastname', 'users.photo as client_photo')->find($id);
        $order_accounter = OrderAccounters::where('order_id', $id)->first();
        $photo = '';

        if($order->client_photo == ''){
            $photo = env('APP_URL') . '/assets/img/user_avatar/default-photo.png';
        } else{
            $photo = env('APP_URL') . Storage::url($order->client_photo);
        }

        $short_notification = '
        <div class="tl-item active">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-info"><img src="' . $photo . '" alt="."></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.new_message_from') .' <a href="/management-area/client-service-order/details/' . $id . '" data-abc="true">'. $order->client_name . ' ' . $order->client_lastname .'</a></h6>
                <small class="text-xs text-muted m-0 p-0"><i class="fa fa-clock me-1"></i> ' . $notification->created_at . '</small>
            </div>
        </div>';

        $long_notification = '<li><a class="dropdown-item border-radius-md" href="/management-area/client-service-order/details/' . $id . '">
            <div class="d-flex py-1">
                <div class="my-auto">
                <img src="' . $photo . '" class="avatar avatar-sm me-3">
                </div>
                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.new_message_from') .'</span> '. $order->client_name . ' ' . $order->client_lastname .'
                </h6>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    ' . $notification->created_at . '
                </p>
                </div>
            </div>
            </a>
        </li>
        ';

        if($order_accounter == null){
            $users = User::where('role', 'SUPER ADMINISTRATOR')->get();
            foreach($users as $user){
                $notification_data = [
                    'data_short' => $short_notification,
                    'data_long' => $long_notification,
                    'user_id' => $user->id
                ];
                //notification welcome to new client
                $new_notification = AppNotifications::create($notification_data);

                //email notification
                OrderMessageSent::dispatch($user->email, $order->client_name . ' ' . $order->client_lastname, $attributes['title'], $attributes['content'], $notification->created_at, $id, $order->name, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
            }
            if($order->worker_id != 0){
                $validator = Worker::join('users', 'workers.user_id', 'users.id')->select('users.*')->whereRaw('workers.id = ?', [$order->worker_id])->first();
                $notification_data = [
                    'data_short' => $short_notification,
                    'data_long' => $long_notification,
                    'user_id' => $validator->id
                ];
                //notification welcome to new client
                $new_notification = AppNotifications::create($notification_data);

                //email notification
                OrderMessageSent::dispatch($validator->email, $order->client_name . ' ' . $order->client_lastname, $attributes['title'], $attributes['content'], $notification->created_at, $id, $order->name, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
            }
        } else{
            $worker = Worker::join('users', 'workers.user_id', 'users.id')->select('users.*')->find($order_accounter->worker_id);
            $notification_data = [
                'data_short' => $short_notification,
                'data_long' => $long_notification,
                'user_id' => $worker->id
            ];
            //notification welcome to new client
            $new_notification = AppNotifications::create($notification_data);

            //email notification
            OrderMessageSent::dispatch($worker->email, $order->client_name . ' ' . $order->client_lastname, $attributes['title'], $attributes['content'], $notification->created_at, $id, $order->name, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
        }
        return redirect('/client-area/service-orders/show-service-order/'.$id)->with('success', __('content.messages.success.order_notification_sent') );

    } else {
        return redirect()->back();
    }

    }

    public function store_by_management(Request $request, $id)
    {

        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR' || Auth::user()->role == 'VALIDATOR'){

        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $attributes = request()->validate([
            'title' => ['required', 'max:255'],
            'message' => ['required'],
        ]);

        $status_notif = $request->has('_index_tokens_') ? 'file attached' : 'simple';

        $dataNotification = [
            'title' => $attributes['title'],
            'type' => 'RECEIVED',
            'content' => $attributes['message'],
            'status' => $status_notif,
            'order_id' => $id
        ];

        $notification = OrderNotifications::create($dataNotification);
        $order = Order::join('products', 'orders.product_id', 'products.id')->join('clients', 'orders.client_id', 'clients.id')->join('users', 'clients.user_id', 'users.id')->select('products.*', 'users.name as client_name', 'users.lastname as client_lastname', 'users.photo as client_photo', 'users.id as id_user', 'users.email as client_email')->find($id);
        $sender = Worker::join('users', 'workers.user_id', 'users.id')->select('users.*', 'workers.id as id_worker')->whereRaw('users.id = ?', [Auth::user()->id])->first();

        $received_by = OrderNotificationsReceivedBy::create([
            "order_notifications_id" => $notification->id,
            "worker_id" => $sender->id_worker
        ]);

        //save files if exist
        if($request->has('_index_tokens_')){
            $files_indexes = $request->input('_index_tokens_');
            $file_name = '';
            $file_folder = 'public/files/customers/customer-' . $order->id_user . '/orders/order-'. $id;
            $file_path = '';

            for ($i=0; $i < count($files_indexes); $i++) {
                $id_file = (string) $files_indexes[$i];

                //validate file
                $fileValidate = request()->validate([
                    'addic_doc_final_'.$id_file => ['max:20480']
                ]);

                //get the name of the file
                $file_name = $request->file('addic_doc_final_'.$id_file)->getClientOriginalName();

                //validate file name
                if(Document::where('name', $file_name)->exists()){
                    $file_name = date('Y-m-d_H-i-s') . "_" . $file_name;
                }

                //store the file
                $file_path = $request->file('addic_doc_final_'.$id_file)->store($file_folder);

                //prepare data to save in db
                $dataDocument = [
                    'name' => $file_name,
                    'path' => $file_path,
                    'type' => 'ADDITIONAL',
                    'order_id' => $id,
                ];

                // save the documents
                $doc = Document::create($dataDocument);

                // store the details
                OrderNotificationsFilesAttach::create([
                    'order_notifications_id' => $notification->id,
                    'document_id' => $doc->id
                ]);
            }
        }

        //notification
        $photo = '';
        if($sender->photo == ''){
            $photo = env('APP_URL') . '/assets/img/user_avatar/default-photo.png';
        } else{
            $photo = env('APP_URL') . Storage::url($sender->photo);
        }

        $short_notification = '<div class="tl-item active">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-info"><img src="' . $photo . '" alt="."></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.new_message_from') .' <a href="/management-area/client-service-order/details/' . $id . '" data-abc="true">'. $sender->name . ' ' . $sender->lastname .'</a></h6>
                <small class="text-xs text-muted m-0 p-0"><i class="fa fa-clock me-1"></i> ' . $notification->created_at . '</small>
            </div>
        </div>';

        $long_notification = '<li><a class="dropdown-item border-radius-md" href="/management-area/client-service-order/details/' . $id . '">
            <div class="d-flex py-1">
                <div class="my-auto">
                <img src="' . $photo . '" class="avatar avatar-sm me-3">
                </div>
                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.new_message_from') .'</span> '. $sender->name . ' ' . $sender->lastname .'
                </h6>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    ' . $notification->created_at . '
                </p>
                </div>
            </div>
            </a>
        </li>
        ';

        $notification_data = [
            'data_short' => $short_notification,
            'data_long' => $long_notification,
            'user_id' => $order->id_user
        ];
        //notification welcome to new client
        $new_notification = AppNotifications::create($notification_data);

        //email notification
        OrderMessageSent::dispatch($order->client_email, $sender->name . ' ' . $sender->lastname, $attributes['title'], $attributes['message'], $notification->created_at, $id, $order->name, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

        return redirect()->back()->with('success', __('content.messages.success.order_notification_sent'));

    } else {
        return redirect()->back();
    }


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
