<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderStatus;
use App\Models\Document;
use App\Models\DocumentDetails;
use App\Models\User;
Use App\Models\OrderAccounters;
use App\Models\OrderAdditionalDocumentsRequest;
use App\Models\OrderNotifications;
use App\Models\OrderValidations;
use App\Models\Product;
use App\Models\ProductForm;
use App\Models\Setting;
use App\Models\Worker;
use App\Providers\OrderCreated;
use App\Providers\OrderToValidate;
use App\Providers\OrderUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

    protected $orderStatusProgress = [
        'CANCELLED' => 0,
        'PROCESS STARTED' => 10,
        'ADDITIONAL DOCUMENTS REQUIRED' => 30,
        'IN PROCESS' => 60,
        'PROCESS FINISHED' => 90,
        'ORDER COMPLETED' => 100
    ];

    protected $orderStatusColours = [
        'CANCELLED' => 'danger',
        'PROCESS STARTED' => 'info',
        'ADDITIONAL DOCUMENTS REQUIRED' => 'warning',
        'IN PROCESS' => 'dark',
        'PROCESS FINISHED' => 'success',
        'ORDER COMPLETED' => 'success'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if(Auth::user()->role == 'CUSTOMER'){
        $client = Client::join('users', 'clients.user_id', 'users.id')->select('users.*', 'clients.id as id_client')->whereRaw('users.id = ?', [Auth::user()->id])->first();
        $orders = Order::where('client_id', $client->id_client)->get();
        $length = count($orders);

        $data_order = [];
        $n = 0;

        if(count($orders) > 0) {
        foreach ($orders as $order) {
            $service = Product::find($order->product_id);
            $accounter = OrderAccounters::where('order_id', $order->id)->first();
            $search_status = OrderStatus::where('order_id', $order->id)->orderBy('id', 'desc')->first();
            $current_status = $search_status->status;
            $accounter_details = '';
            $validation_details = '';

            if($accounter == null){
                $accounter_details = 'Not defined';
            } else {
                $accounter_data = Worker::join('users', 'workers.user_id', '=', 'users.id')->select('users.*', 'workers.id as id_worker')->find($accounter->worker_id);
                $accounter_details = $accounter_data->name . ' ' . $accounter_data->lastname;
            }

            if($service->worker_id == 0){
                $validation_details = 'Not Required';
            } else {
                $validation = OrderValidations::where('order_id', $order->id)->first();
                if($validation == null){
                    $validation_details = 'Validation Required';
                } else {
                    $validation_details = $validation->status;
                }
            }

            $data_details = [
                'order_id' => $order->id,
                'name' => $service->name,
                'image' => $service->image,
                'accounter' => $accounter_details,
                'status' => $current_status,
                'progress' => $this->orderStatusProgress[$current_status],
                'status_color' => $this->orderStatusColours[$current_status],
                'validation' => $validation_details
            ];

            array_push($data_order, $data_details);

            if($n == $length - 1){
                return view('Orders.index', [
                    'clientOrders' => $data_order,
                    'current_page' => __('content.my_orders')
                ]);
            }
            $n++;
        }
      } else {
        return view('Orders.index', [
            'clientOrders' => [],
            'current_page' => __('content.my_orders')
        ]);
      }

    } else {
        return redirect()->back();
    }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($id)
    {
        if(Auth::user()->role == 'CUSTOMER'){
        $product = Product::find($id);
        if($product->status == 'active') {
            $reg_field = ProductForm::where('product_id', $id)->where('field_type', '!=', 'file')->get();
            $file_fields = ProductForm::where('product_id', $id)->where('field_type', '=', 'file')->get();

            $data = [
                "product" => $product,
                "regular_fields" => $reg_field,
                "file_fields" => $file_fields
            ];

            return view('Orders/create', [
                'data_product' => $data,
                'current_page' => __('content.my_orders')
            ]);
        } else {
            return redirect('/client-area/summery-client')->with('error', 'You cannot order this service. It is inactive.');
        }

    } else {
        return redirect()->back();
    }

    }

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

        $all_inputs = $request->all();
        $keys = array_keys($all_inputs);
        $client_id = Client::join('users', 'clients.user_id', 'users.id')->select('clients.*', 'users.name as client_name', 'users.lastname as client_lastname')->whereRaw('clients.user_id = ?', [Auth::user()->id])->get();

        $users_accounters = User::where('role', 'ADMINISTRATOR')->get();
        $users_super_admin = User::where('role', 'SUPER ADMINISTRATOR')->get();

        $fields_products = ProductForm::where('product_id', $id)->get();
        $product = Product::find($id);
        $validator_name = '';
        $validator_email = '';

        $data = [
            'client_id' => $client_id[0]->id,
            'product_id' => $id,
            'init_date' => now()
        ];

        //save main data order
        $order = Order::create($data);

        $file_name = '';
        $file_folder = 'public/customers/customer-' . Auth::user()->id . '/orders/order-'. $order->id;
        $file_path = '';
        $data_value = '';

        for ($i=0; $i < count($keys); $i++) {

            $key = (string) $keys[$i];
            $split_k = explode("-", $key);
            $str_id = (integer) $split_k[0];

            for ($n=0; $n < count($fields_products); $n++) {
                if($fields_products[$n]->id == $str_id) {

                    if(str_contains($key, 'data-file')) {

                        $fileValidate = request()->validate([
                            $key => ['max:20480'] //csv,txt,xlx,xls,pdf,doc,docx,jpg,png,jpeg,mp3,mp4,avi,wmv
                        ]);

                        $file_name = request()->file($key)->getClientOriginalName();

                        //validate file name
                        if(Document::where('name', $file_name)->exists()){
                            $file_name = date('Y-m-d_H-i-s') . "_" . $file_name;
                        }

                        $file_path = request()->file($key)->store($file_folder);
                        $data_value = $file_name;

                    } else {
                        $dataValidate = request()->validate([
                            $key => ['min:1', 'max:255']
                        ]);
                        $data_value = $all_inputs[$key];
                    }

                    $dataDetails = [
                        'order_id' => $order->id,
                        'productForm_id' => $fields_products[$n]->id,
                        'data' => $data_value
                    ];

                    // save the details
                    $detail = OrderDetails::create($dataDetails);

                    if(str_contains($key, 'data-file')){
                        $dataDocument = [
                            'name' => $file_name,
                            'path' => $file_path,
                            'type' => 'MAIN',
                            'order_id' => $order->id,
                        ];
                        // save the documents
                        $doc = Document::create($dataDocument);

                        //document details
                        $dataDocumentDetails = [
                            'document_id' => $doc->id,
                            'orderDetails_id' => $detail->id,
                        ];
                        // save the document details
                        $doc = DocumentDetails::create($dataDocumentDetails);
                    }

                    break;
                }
            }
        }

        $dataStatus = [
            'order_id' => $order->id,
            'status' => 'PROCESS STARTED'
        ];
        // save data order status
        $status = OrderStatus::create($dataStatus);


        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-info"><i class="fas fa-shopping-cart"></i></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.new_service_order_created') .' by <a href="#" data-abc="true">'. $client_id[0]->client_name . ' ' . $client_id[0]->client_lastname .'</a></h6>
                <p class="tl-date text-muted">
                    <a href="/management-area/client-service-order/details/' . $order->id . '" data-abc="true" class="text-info text-gradient font-weight-bolder text-xs">'. __('content.order') .' #'. $order->id .': ' . $product->name . '</a>
                </p>
                <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $order->created_at .'</small>
            </div>
        </div>';

        $long_notification = '<li>
            <a class="dropdown-item border-radius-md" href="/management-area/client-service-order/details/' . $order->id . '">
            <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-info  me-3 my-auto">
                <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.new_service_order_created') .'</span> by '. $client_id[0]->client_name . ' ' . $client_id[0]->client_lastname .'
                </h6>
                <p class="text-xs text-secondary mb-0 mt-0">
                    <span class="text-info text-gradient font-weight-bolder text-xs">'. __('content.order') .' #'. $order->id .': ' . $product->name . '</span>
                </p>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    '. $order->created_at .'
                </p>
                </div>
            </div>
            </a>
        </li>';

        foreach($users_super_admin as $user){
            $notification_data = [
                'data_short' => $short_notification,
                'data_long' => $long_notification,
                'user_id' => $user->id
            ];
            //notification db
            $new_notification = AppNotifications::create($notification_data);

            //send email notification
            OrderCreated::dispatch($user->name, $user->email, $client_id[0]->client_name . ' ' . $client_id[0]->client_lastname, $order->id, $product->name, $order->init_date, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
        }

        $n = 0;
        foreach($users_accounters as $user){

            $notification_data = [
                'data_short' => $short_notification,
                'data_long' => $long_notification,
                'user_id' => $user->id
            ];
            //notification db
            $new_notification = AppNotifications::create($notification_data);

            //send email notification
            OrderCreated::dispatch($user->name, $user->email, $client_id[0]->client_name . ' ' . $client_id[0]->client_lastname, $order->id, $product->name, $order->init_date, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
            $n++;
        }

        //validator
        if($product->worker_id != 0){
            $validator = Worker::join('users', 'workers.worker_id', 'users.id')->select('users.*')->find($product->worker_id);
            $validator_name = $validator->name . ' ' . $validator->lastname;
            $validator_email = $validator->email;

            $short_notification = '
            <div class="tl-item">
                <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-info"><i class="fas fa-shopping-cart"></i></span></a></div>
                <div class="tl-content">
                    <h6 class="m-0 p-0">'. __('content.mails.new_service_order_to_validate') .'</h6>
                    <p class="tl-date text-muted">
                        '. __('content.mails.must_validate_the_order') .' <a href="/management-area/client-service-order/details/' . $order->id . '" data-abc="true" class="text-info text-gradient font-weight-bolder text-xs">'. __('content.order') .' #'. $order->id .': ' . $product->name . '</a> '. __('content.created_by') . ' ' . __('content.the_client') .' <span class="text-dark text-gradient font-weight-bolder text-xs">'. $client_id[0]->client_name . ' ' . $client_id[0]->client_lastname .'</span>
                    </p>
                    <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $order->created_at .'</small>
                </div>
            </div>';

            $long_notification = '<li>
                <a class="dropdown-item border-radius-md" href="/management-area/client-service-order/details/' . $order->id . '">
                <div class="d-flex py-1">
                    <div class="avatar avatar-sm bg-gradient-info  me-3 my-auto">
                    <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                        <span class="font-weight-bold">'. __('content.mails.new_service_order_to_validate') .'</span>
                    </h6>
                    <p class="text-xs text-secondary mb-0 mt-0">
                        '. __('content.mails.must_validate_the_order') .' <span class="text-info text-gradient font-weight-bolder text-xs">'. __('content.order') .' #'. $order->id .': ' . $product->name . '</span> '. __('content.created_by') . ' ' . __('content.the_client') .' <span class="text-dark text-gradient font-weight-bolder text-xs">'. $client_id[0]->client_name . ' ' . $client_id[0]->client_lastname .'</span>
                    </p>
                    <p class="text-xs text-secondary mb-0">
                        <i class="fa fa-clock me-1"></i>
                        '. $order->created_at .'
                    </p>
                    </div>
                </div>
                </a>
            </li>';

            $notification_data = [
                'data_short' => $short_notification,
                'data_long' => $long_notification,
                'user_id' => $validator->id
            ];

            //notification
            $new_validator_notification = AppNotifications::create($notification_data);

            //send email notification to validator
            OrderToValidate::dispatch($validator_name, $validator_email, $client_id[0]->client_name . ' ' . $client_id[0]->client_lastname, $order->id, $product->name, $order->init_date, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
        }

        if($n == $users_accounters->count()){
            return redirect('/client-area/service-orders')->with('success', __('content.messages.success.order_created') );
        }

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
        if(Auth::user()->role == 'CUSTOMER'){

        $order = Order::find($id);
        $product = Product::find($order->product_id);
        $status = OrderStatus::where('order_id', $order->id)->orderBy('id', 'desc')->first();
        $accounter = OrderAccounters::where('order_id', $order->id)->get();
        $accounter_details = '';
        $validation_details = '';
        $additional_docs = OrderAdditionalDocumentsRequest::whereRaw('order_id = ? AND status = ?', [$order->id, 'PENDING'])->get();

        $product_details = OrderDetails::join('orders', 'order_details.order_id', 'orders.id')->join('product_forms', 'order_details.productForm_id', 'product_forms.id')->join('products', 'product_forms.product_id', 'products.id')->select('product_forms.*', 'order_details.*')->where('orders.id', $id)->get();

        if($accounter->isEmpty()){
            $accounter_details = 'Not defined';
        } else {
            $accounter_data = Worker::join('users', 'workers.user_id', '=', 'users.id')->select('users.*', 'workers.id as id_worker')->find($accounter->first()->worker_id);
            $accounter_details = $accounter_data->name . ' ' . $accounter_data->lastname;
        }

        if($product->worker_id == 0){
            $validation_details = 'Not Required';
        } else {
            $validation = OrderValidations::where('order_id', $order->id)->first();
            if($validation == null){
                $validation_details = 'Validation Required';
            } else {
                $validation_details = $validation->status;
            }
        }

        $documents_sent = Document::whereRaw('order_id = ? AND type != ?', [$id, 'RECEIVED'])->get();


        $documents_received = Document::whereRaw('order_id = ? AND type = ?', [$id, 'RECEIVED'])->get();


        $notifications_received = OrderNotifications::join('orders', 'order_notifications.order_id', '=', 'orders.id')
                                           ->join('order_notifications_received_bies', 'order_notifications_received_bies.order_notifications_id', '=', 'order_notifications.id')
                                           ->join('workers', 'order_notifications_received_bies.worker_id', '=', 'workers.id')
                                           ->join('users', 'workers.user_id', '=', 'users.id')
                                           ->select('order_notifications.*', 'users.name as worker_name', 'users.lastname as worker_lastname', 'users.photo as worker_photo')
                                           ->whereRaw('order_notifications.order_id = ? AND order_notifications.type = ?', [$order->id, 'RECEIVED'])
                                           ->get();

        $notifications_sent = OrderNotifications::whereRaw('order_id = ? AND type = ?', [$order->id, 'SEND'])->get();

        $final_document = Document::whereRaw('order_id = ? AND type = ?',[$id, 'FINAL DOCUMENT'])->first();

        $final_doc_name = '';
        $final_doc_id = 0;

        if($final_document != null){
            $final_doc_name = $final_document->name;
            $final_doc_id = $final_document->id;
        }

        $data_order = [
            "order_id" => $order->id,
            "order_started_at" => $order->init_date,
            "order_finished_at" => $order->end_date,
            "order_comments" => $order->comments,
            "order_final_document_id" => $final_doc_id,
            "order_final_document_name" => $final_doc_name,
            "service_name" => $product->name,
            "service_details" => $product->description,
            "service_data" => $product_details,
            "accounter" => $accounter_details,
            "validation" => $validation_details,
            "status" => $status->status,
            'progress' => $this->orderStatusProgress[$status->status],
            'status_color' => $this->orderStatusColours[$status->status],
            "documents" => [
                'sent' => $documents_sent,
                'received' => $documents_received
            ],
            "notifications" => [
                "sent" => $notifications_sent,
                "received" => $notifications_received
            ],
            'additional_documents' => $additional_docs
        ];

        return view('Orders/show', [
            'order' => $data_order,
            'current_page' => __('content.my_orders')
        ]);
    } else {
        return redirect()->back();
    }

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

    public function send_request_additional_documents(Request $request, $id)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){

        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $order = Order::join('products', 'orders.product_id', 'products.id')->select('orders.*', 'products.name as service_name')->find($id);
        $client = Client::join('users', 'clients.user_id', 'users.id')->select('users.*')->whereRaw('clients.id = ?', [$order->client_id])->first();


        if($request->has('fieldname_english')) {
            // add the product details to db
            $worker_id = Auth::user()->id;
            $length = count($request->input('fieldname_english'));
            $englishFileName = $request->input('fieldname_english');
            $spanishFileName = $request->input('fieldname_spanish');
            $typeField = $request->input('field_type');
            $dataDetails = [];

            for ($i=0; $i < $length; $i++) {
                $dataDetails = [
                    'order_id' => $id,
                    'worker_id' => $worker_id,
                    'fieldname_english' => $englishFileName[$i],
                    'fieldname_spanish' => $spanishFileName[$i],
                    'field_type' => $typeField[$i],
                    'status' => 'PENDING'
                ];
                //insert additional file fields
                $additional_docs = OrderAdditionalDocumentsRequest::create($dataDetails);

                if($i == ($length - 1)){
                    $status = 'ADDITIONAL DOCUMENTS REQUIRED';
                    $createStatus = OrderStatus::create([
                        'status' => $status,
                        'order_id' => $id
                    ]);
                    $lang = session()->get('language');
                    $comments = $lang == 'es' ? 'Debe enviar los documentos adicionales requeridos lo antes posible para continuar con la realización del servicio' : 'You must send the required additional documents as soon as possible to continue the service order process';

                    $short_notification = '
                    <div class="tl-item">
                        <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-warning"><i class="fas fa-file-contract"></i></span></a></div>
                        <div class="tl-content">
                            <h6 class="m-0 p-0">'. __('content.mails.client_must_send_documents') .'.</h6>
                            <p class="tl-date text-muted">
                                '. __('content.mails.the_service_order'). ' <a href="/client-area/service-orders/show-service-order/' . $order->id . '" data-abc="true"> #'. $order->id .': ' . $order->service_name . '</a> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-warning">' . $status . '</span>
                            </p>
                            <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $createStatus->created_at .'</small>
                        </div>
                    </div>
                    ';

                    $long_notification = '<li>
                        <a class="dropdown-item border-radius-md" href="/client-area/service-orders/show-service-order/' . $order->id . '">
                        <div class="d-flex py-1">
                            <div class="avatar avatar-sm bg-gradient-warning  me-3 my-auto">
                            <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-sm font-weight-normal mb-1">
                                <span class="font-weight-bold">'. __('content.mails.client_must_send_documents') .'</span>
                            </h6>
                            <p class="text-xs text-muted mb-0 mt-0">
                            '. __('content.mails.the_service_order'). ' <span class="font-weight-bold text-xs"> #'. $order->id .': ' . $order->service_name . '</span> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-warning">' . $status . '</span>
                            </p>
                            <p class="text-xs text-secondary mb-0">
                                <i class="fa fa-clock me-1"></i>
                                '. $createStatus->created_at .'
                            </p>
                            </div>
                        </div>
                        </a>
                    </li>';

                    $notification_data = [
                        'data_short' => $short_notification,
                        'data_long' => $long_notification,
                        'user_id' => $client->id
                    ];
                    //notification welcome to new client
                    $new_notification = AppNotifications::create($notification_data);

                    //email notification
                    OrderUpdated::dispatch($client->name, $client->email, $id, $order->service_name, $createStatus->updated_at, $status, $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
                    return redirect()->back()->with('success', __('content.messages.success.additional_documents_request') );
                }
            }
        } else {
            return redirect()->back()->with('error', __('content.messages.errors.add_required_fields') );
        }

    } else {
        return redirect()->back();
    }

    }

    public function send_additional_documents(Request $request, $id)
    {

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

    public function cancel($id)
    {
        if(Auth::user()->role == 'CUSTOMER' || Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $order = Order::join('products', 'orders.product_id', 'products.id')->select('orders.*', 'products.name as service_name')->find($id);

        $status = 'CANCELLED';
        $createStatus = OrderStatus::create([
            'status' => $status,
            'order_id' => $id
        ]);

        $lang = session()->get('language');
        $comments = '';

        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-danger"><i class="fas fa-file-contract"></i></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.service_order_updated') .'</h6>
                <p class="tl-date text-muted">
                    '. __('content.mails.the_service_order'). ' <a href="/client-area/service-orders/show-service-order/' . $order->id . '" data-abc="true"> #'. $order->id .': ' . $order->service_name . '</a> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-danger">' . $status . '</span>
                </p>
                <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $createStatus->created_at .'</small>
            </div>
        </div>
        ';

        $long_notification = '<li>
            <a class="dropdown-item border-radius-md" href="/client-area/service-orders/show-service-order/' . $order->id . '">
            <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-danger me-3 my-auto">
                <i class="fas fa-file-contract"></i>
                </div>
                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.service_order_updated') .'</span>
                </h6>
                <p class="text-xs text-muted mb-0 mt-0">
                '. __('content.mails.the_service_order'). ' <span class="font-weight-bold text-xs">#'. $order->id .': ' . $order->service_name . '</span> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-danger">' . $status . '</span>
                </p>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    '. $createStatus->created_at .'
                </p>
                </div>
            </div>
            </a>
        </li>';

        $order_accounter = OrderAccounters::where('order_id', $id)->first();
        if(Auth::user()->role != 'CUSTOMER'){
            $client = Client::join('users', 'clients.user_id', 'users.id')->select('users.*')->whereRaw('clients.id = ?', [$order->client_id])->first();
            $notification_data = [
                'data_short' => $short_notification,
                'data_long' => $long_notification,
                'user_id' => $client->id
            ];
            //notification welcome to new client
            $new_notification = AppNotifications::create($notification_data);

            //email notification
            OrderUpdated::dispatch($client->name, $client->email, $id, $order->service_name, $createStatus->updated_at, $status, $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

        } else{

            $short_notification = '
            <div class="tl-item">
                <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-danger"><i class="fas fa-file-contract"></i></span></a></div>
                <div class="tl-content">
                    <h6 class="m-0 p-0">'. __('content.mails.service_order_updated') .'</h6>
                    <p class="tl-date text-muted">
                        '. __('content.mails.the_service_order'). ' <a href="/management-area/client-service-order/details/' . $order->id . '" data-abc="true"> #'. $order->id .': ' . $order->service_name . '</a> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-danger">' . $status . '</span>
                    </p>
                    <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $createStatus->created_at .'</small>
                </div>
            </div>
            ';

            $long_notification = '<li>
                <a class="dropdown-item border-radius-md" href="/management-area/client-service-order/details/' . $order->id . '">
                <div class="d-flex py-1">
                    <div class="avatar avatar-sm bg-gradient-danger me-3 my-auto">
                    <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                        <span class="font-weight-bold">'. __('content.mails.service_order_updated') .'</span>
                    </h6>
                    <p class="text-xs text-muted mb-0 mt-0">
                    '. __('content.mails.the_service_order'). ' <span class="font-weight-bold text-xs">#'. $order->id .': ' . $order->service_name . '</span> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-danger">' . $status . '</span>
                    </p>
                    <p class="text-xs text-secondary mb-0">
                        <i class="fa fa-clock me-1"></i>
                        '. $createStatus->created_at .'
                    </p>
                    </div>
                </div>
                </a>
            </li>';

            $workers = null;

            if($order_accounter == null){
                $workers = User::whereRaw('role = ? AND role = ?', ['SUPER ADMINISTRATOR', 'ADMINISTRATOR']);
                foreach($workers as $worker){
                    $notification_data = [
                        'data_short' => $short_notification,
                        'data_long' => $long_notification,
                        'user_id' => $worker->id
                    ];
                    //notification welcome to new client
                    $new_notification = AppNotifications::create($notification_data);

                    //email notification
                    OrderUpdated::dispatch($worker->name, $worker->email, $id, $order->service_name, $createStatus->updated_at, $status, $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
                }
            } else{
                $worker = Worker::join('users', 'workers.user_id', 'users')->select('users.*')->find($order_accounter->worker_id);
                    $notification_data = [
                        'data_short' => $short_notification,
                        'data_long' => $long_notification,
                        'user_id' => $worker->id
                    ];
                    //notification welcome to new client
                    $new_notification = AppNotifications::create($notification_data);

                //email notification
                OrderUpdated::dispatch($worker->name, $worker->email, $id, $order->service_name, $createStatus->updated_at, $status, $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
            }
        }

        return redirect()->back()->with('success', __('content.messages.success.order_cancelled') );

    } else {
        return redirect()->back();
    }

    }


    public function request_documents($id)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $order = Order::find($id);
        return view('Orders.additional-documents', [
            'client_order_detail' => $order,
            'current_page' => __('content.request_documents')
        ]);
    } else {
        return redirect()->back();
    }

    }

    public function in_process($id)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $order = Order::join('products', 'orders.product_id', 'products.id')->select('orders.*', 'products.name as service_name')->find($id);
        $client = Client::join('users', 'clients.user_id', 'users.id')->select('users.*')->whereRaw('clients.id = ?', [$order->client_id])->first();

        $accounter = Worker::where('user_id', Auth::user()->id)->first();
        $data = [
            'order_id' => $id,
            'worker_id' => $accounter->id
        ];
        $registerAccounter = OrderAccounters::create($data);

        $status = 'IN PROCESS';
        $data_status = [
            'status' => $status,
            'order_id' => $id
        ];
        $createStatus = OrderStatus::create($data_status);

        $lang = session()->get('language');
        $comments = $lang == 'es' ? 'El contador ha iniciado el proceso del servicio.' : 'The accounter has started the service process.';

        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-dark"><i class="fas fa-file-contract"></i></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.service_order_updated') .'</h6>
                <p class="tl-date text-muted">
                    '. __('content.mails.the_service_order'). ' <a href="/client-area/service-orders/show-service-order/' . $order->id . '" data-abc="true"> #'. $order->id .': ' . $order->service_name . '</a> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-dark">' . $status . '</span>
                </p>
                <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $createStatus->created_at .'</small>
            </div>
        </div>
        ';

        $long_notification = '<li><a class="dropdown-item border-radius-md" href="/client-area/service-orders/show-service-order/' . $order->id . '">
            <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-dark  me-3 my-auto">
                <i class="fas fa-file-contract"></i>
                </div>
                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.service_order_updated') .'</span>
                </h6>
                <p class="text-xs text-muted mb-0 mt-0">
                '. __('content.mails.the_service_order'). ' <span class="font-weight-bold text-xs">#'. $order->id .': ' . $order->service_name . '</span> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-dark">' . $status . '</span>
                </p>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    '. $createStatus->created_at .'
                </p>
                </div>
            </div>
            </a>
        </li>';


        $client = Client::join('users', 'clients.user_id', 'users.id')->select('users.*')->whereRaw('clients.id = ?', [$order->client_id])->first();
        $notification_data = [
            'data_short' => $short_notification,
            'data_long' => $long_notification,
            'user_id' => $client->id
        ];
        //notification welcome to new client
        $new_notification = AppNotifications::create($notification_data);

        //email notification
        OrderUpdated::dispatch($client->name, $client->email, $id, $order->service_name, $createStatus->updated_at, $status, $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

        return redirect()->back()->with('success', __('content.messages.success.order_taken') );

    } else {
        return redirect()->back();
    }

    }

    public function finished($id){

        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $order = Order::join('products', 'orders.product_id', 'products.id')->select('orders.*', 'products.name as service_name')->find($id);
        $client = Client::join('users', 'clients.user_id', 'users.id')->select('users.*')->whereRaw('clients.id = ?', [$order->client_id])->first();

        $new_status = 'PROCESS FINISHED';
        $data = [
            'status' => $new_status,
            'order_id' => $id
        ];
        $status = OrderStatus::create($data);

        $lang = session()->get('language');
        $comments = $lang == 'es' ? 'El servicio esta casi completado!' : 'The service is almost completed!';

        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-secondary"><i class="fas fa-file-contract"></i></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.service_order_updated') .'</h6>
                <p class="tl-date text-muted">
                    '. __('content.mails.the_service_order'). ' <a href="/client-area/service-orders/show-service-order/' . $order->id . '" data-abc="true"> #'. $order->id .': ' . $order->service_name . '</a> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-secondary">' . $new_status . '</span>
                </p>
                <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $status->created_at .'</small>
            </div>
        </div>
        ';

        $long_notification = '<li><a class="dropdown-item border-radius-md" href="/client-area/service-orders/show-service-order/' . $order->id . '">
            <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-secondary  me-3 my-auto">
                <i class="fas fa-file-contract"></i>
                </div>
                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.service_order_updated') .'</span>
                </h6>
                <p class="text-xs text-muted mb-0 mt-0">
                '. __('content.mails.the_service_order'). ' <span class="font-weight-bold text-xs">#'. $order->id .': ' . $order->service_name . '</span> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-secondary">' . $new_status . '</span>
                </p>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    '. $status->created_at .'
                </p>
                </div>
            </div>
            </a>
        </li>';

        $notification_data = [
            'data_short' => $short_notification,
            'data_long' => $long_notification,
            'user_id' => $client->id
        ];
        //notification welcome to new client
        $new_notification = AppNotifications::create($notification_data);

        //email notification
        OrderUpdated::dispatch($client->name, $client->email, $id, $order->service_name, $status->updated_at, $new_status, $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

        return redirect()->back()->with('success', __('content.messages.success.order_finished') );

    } else {
        return redirect()->back();
    }

    }

    public function completed(Request $request, $id)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $new_status = 'ORDER COMPLETED';

        $attribute = request()->validate([
            "comments" => ['max:512']
        ]);

        $order = Order::join('products', 'orders.product_id', 'products.id')->select('orders.*', 'products.name as service_name')->find($id);
        $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client')->find($order->client_id);

        $file_name = '';
        $file_folder = 'public/customers/customer-' . $client->id . '/orders/order-'. $id;
        $file_path = '';
        $data_value = '';

        if($request->hasFile("final_file")){
            $fileValidate = request()->validate([
                "final_file" => ['required', 'max:20480']
            ]);
            $file_name = $request->file("final_file")->getClientOriginalName();

            //validate file name
            if(Document::where('name', $file_name)->exists()){
                $file_name = date('Y-m-d_H-i-s') . "_" . $file_name;
            }

            $file_path = $request->file("final_file")->store($file_folder);
            $data_value = $file_name;
            $dataDocument = [
                'name' => $file_name,
                'path' => $file_path,
                'type' => 'FINAL DOCUMENT',
                'order_id' => $id,
            ];
            // save the document
            $doc = Document::create($dataDocument);
        }

        $data = [
            'end_date' => now(),
            'comments' => $attribute['comments']
        ];
        // update order
        $order_update = Order::where('id', $id)->update($data);
        $dataStatus = [
            'order_id' => $id,
            'status' => $new_status
        ];
        // save data order status
        $status = OrderStatus::create($dataStatus);

        $lang = session()->get('language');
        $comments = $lang == 'es' ? 'El contador ha completado todo el proceso del servicio. Visite nuestra plataforma para conocer los detalles y resultados del proceso.' : 'The accounter has completed all steps of service process. Go to our platform to know the details and results of the process.';

        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-success"><i class="fas fa-file-contract"></i></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.new_service_order_completed') .'!</h6>
                <p class="tl-date text-muted">
                    '. __('content.mails.the_service_order'). ' <a href="/client-area/service-orders/show-service-order/' . $order->id . '" data-abc="true"> #'. $order->id .': ' . $order->service_name . '</a> ' . __('content.mails.has_been_completed_successfuly') . '
                </p>
                <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $status->created_at .'</small>
            </div>
        </div>
        ';

        $long_notification = '<li><a class="dropdown-item border-radius-md" href="/client-area/service-orders/show-service-order/' . $order->id . '">
            <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-success  me-3 my-auto">
                <i class="fas fa-file-contract"></i>
                </div>
                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.service_order_updated') .'</span>
                </h6>
                <p class="text-xs text-muted mb-0 mt-0">
                '. __('content.mails.the_service_order'). ' <span class="font-weight-bold text-xs">#'. $order->id .': ' . $order->service_name . '</span> ' . __('content.mails.has_been_completed_successfuly') . '
                </p>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    '. $status->created_at .'
                </p>
                </div>
            </div>
            </a>
        </li>';

        $notification_data = [
            'data_short' => $short_notification,
            'data_long' => $long_notification,
            'user_id' => $client->id
        ];
        //notification welcome to new client
        $new_notification = AppNotifications::create($notification_data);

        //email notification
        OrderUpdated::dispatch($client->name, $client->email, $id, $order->service_name, $status->updated_at, $new_status, $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

        return redirect()->back()->with('success', __('content.messages.success.order_completed'));

    } else {
        return redirect()->back();
    }

    }

    public function validated($id)
    {
        if(Auth::user()->role == 'VALIDATOR' || Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){

        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $order = Order::join('products', 'orders.product_id', 'products.id')->select('orders.*', 'products.name as service_name')->find($id);
        $client = Client::join('users', 'clients.user_id', 'users.id')->select('users.*')->whereRaw('clients.id = ?', [$order->client_id])->first();
        $workers = User::whereRaw('role = ? AND role = ?', ['SUPER ADMINISTRATOR', 'ADMINISTRATOR'])->get();

        $status = 'Validated';
        $validator_id = Worker::where('user_id', Auth::user()->id)->first();
        $createValidation = OrderValidations::create([
            'status' => $status,
            'order_id' => $id,
            'worker_id' => $validator_id->id
        ]);

        $lang = session()->get('language');
        $comments = $lang == 'es' ? 'La orden ha sido validada y esta lista para ser iniciada por un contador.' : 'The order has been validated and it is ready to be started for an accounter';


        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-success"><i class="fas fa-file-contract"></i></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.service_order_validated') .'!</h6>
                <p class="tl-date text-muted">
                    '. __('content.mails.the_service_order'). ' <a href="/client-area/service-orders/show-service-order/' . $order->id . '" data-abc="true"> #'. $order->id .': ' . $order->service_name . '</a> ' . __('content.mails.has_been_validated_and_ready') . '
                </p>
                <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $createValidation->created_at .'</small>
            </div>
        </div>';

        $long_notification = '<li><a class="dropdown-item border-radius-md" href="/client-area/service-orders/show-service-order/' . $order->id . '">
            <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-success  me-3 my-auto">
                <i class="fas fa-file-contract"></i>
                </div>

                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.service_order_validated') .'</span>
                </h6>
                <p class="text-xs text-muted mb-0 mt-0">
                '. __('content.mails.the_service_order'). '<span class="font-weight-bold text-xs">#'. $order->id .': ' . $order->service_name . '</span> ' . __('content.mails.has_been_validated_and_ready') . '
                </p>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    '. $createValidation->created_at .'
                </p>
                </div>
            </div>
            </a>
        </li>';

        $notification_data = [
            'data_short' => $short_notification,
            'data_long' => $long_notification,
            'user_id' => $client->id
        ];
        //notification welcome to new client
        $new_notification = AppNotifications::create($notification_data);

        //email notification to client
        OrderUpdated::dispatch($client->name, $client->email, $id, $order->service_name, $createValidation->updated_at, "VALIDATED", $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-success"><i class="fas fa-file-contract"></i></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.service_order_validated') .'!</h6>
                <p class="tl-date text-muted">
                    '. __('content.mails.the_service_order'). ' <a href="/management-area/client-service-order/details/' . $order->id . '" data-abc="true"> #'. $order->id .': ' . $order->service_name . '</a> ' . __('content.mails.has_been_validated_and_ready') . '
                </p>
                <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $createValidation->created_at .'</small>
            </div>
        </div>';

        $long_notification = '<li><a class="dropdown-item border-radius-md" href="/management-area/client-service-order/details/' . $order->id . '">
            <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-success  me-3 my-auto">
                <i class="fas fa-file-contract"></i>
                </div>

                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.service_order_validated') .'</span>
                </h6>
                <p class="text-xs text-muted mb-0 mt-0">
                '. __('content.mails.the_service_order'). '<span class="font-weight-bold text-xs">#'. $order->id .': ' . $order->service_name . '</span> ' . __('content.mails.has_been_validated_and_ready') . '
                </p>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    '. $createValidation->created_at .'
                </p>
                </div>
            </div>
            </a>
        </li>';

        foreach($workers as $worker){
            $notification_data = [
                'data_short' => $short_notification,
                'data_long' => $long_notification,
                'user_id' => $worker->id
            ];
            //notification welcome to new client
            $new_notification = AppNotifications::create($notification_data);

           //email notification to admins
           OrderUpdated::dispatch($worker->name, $worker->email, $id, $order->service_name, $createValidation->updated_at, "VALIDATED", $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

        }

        return redirect()->back()->with('success', __('content.messages.success.order_validated') );

    } else {
        return redirect()->back();
    }

    }

    public function all()
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $orders = Order::all();
        $length = $orders->count();

        $data_order = [];
        $n = 0;

        if($length == 0){
            return view('Orders.listAll', [
                'clientOrders' => [],
                'current_page' => __('content.orders')
            ]);
        } else {
            foreach ($orders as $order) {
                $service = Product::find($order->product_id);
                $accounter = OrderAccounters::where('order_id', $order->id)->first();
                $accounter_id = 0;
                $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client')->find($order->client_id);
                $search_status = OrderStatus::where('order_id', $order->id)->orderBy('id', 'desc')->first();
                $current_status = $search_status->status;
                $accounter_details = '';
                $validation_details = '';

                if($accounter == null){
                    $accounter_details = 'Not defined';
                } else {
                    $accounter_data = Worker::join('users', 'workers.user_id', '=', 'users.id')->select('users.*', 'workers.id as id_worker')->find($accounter->worker_id);
                    $accounter_id = $accounter_data->id;
                    $accounter_details = $accounter_data->name . ' ' . $accounter_data->lastname;
                }

                if($service->worker_id == 0){
                    $validation_details = 'Not Required';
                } else {
                    $validation = OrderValidations::where('order_id', $order->id)->first();
                    if($validation == null){
                        $validation_details = 'Validation Required';
                    } else {
                        $validation_details = $validation->status;
                    }
                }

                $data_details = [
                    'order_id' => $order->id,
                    'name' => $service->name,
                    'image' => $service->image,
                    'client_name' => $client->name . ' ' . $client->lastname,
                    'client_id' => $client->id_client,
                    'accounter' => $accounter_details,
                    'accounter_id' => $accounter_id,
                    'status' => $current_status,
                    'validation' => $validation_details,
                    'progress' => $this->orderStatusProgress[$current_status],
                    'status_color' => $this->orderStatusColours[$current_status]
                ];

                array_push($data_order, $data_details);

                if($n == $length - 1){
                    return view('Orders.listAll', [
                        'clientOrders' => $data_order,
                        'current_page' => __('content.orders')
                    ]);
                }
                $n++;
            }
        }

    } else {
        return redirect()->back();
    }

    }

    public function assigned()
    {
        if(Auth::user()->role == 'VALIDATOR'){
        $validator_id = Worker::join('users', 'workers.user_id', 'users.id')->select('users.*', 'workers.id as id_worker')->whereRaw('users.id = ?', [Auth::user()->id])->first();
        $orders = Order::join('products', 'orders.product_id', 'products.id')->select('orders.*')->whereRaw('products.worker_id = ?', [$validator_id->id_worker])->get();
        $length = count($orders);

        $data_order = [];
        $n = 0;

        if($length == 0) {
            return view('Orders.listValidate', [
                'clientOrders' => $data_order,
                'current_page' => __('content.assigned_services')
            ]);
        } else {
            foreach ($orders as $order) {
                $service = Product::find($order->product_id);
                $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client')->find($order->client_id );
                $search_status = OrderStatus::where('order_id', $order->id)->orderBy('id', 'desc')->first();
                $current_status = $search_status->status;
                $accounter = OrderAccounters::where('order_id', $order->id)->first();
                $validation = OrderValidations::where('order_id', $order->id)->first();
                $accounter_details = '';
                $validation_details = '';

                if($accounter == null){
                    $accounter_details = 'Not defined';
                } else {
                    $accounter_data = Worker::join('users', 'workers.user_id', '=', 'users.id')->select('users.*', 'workers.id as id_worker')->find($accounter->worker_id);
                    $accounter_details = $accounter_data->name . ' ' . $accounter_data->lastname;
                }

                if($validation == null){
                    $validation_details = 'Validation Required';
                } else {
                    $validation_details = $validation->status;
                }

                $data_details = [
                    'order_id' => $order->id,
                    'name' => $service->name,
                    'image' => $service->image,
                    'client_name' => $client->name . ' ' . $client->lastname,
                    'client_id' => $client->id_client,
                    'accounter' => $accounter_details,
                    'status' => $current_status,
                    'validation' => $validation_details,
                    'progress' => $this->orderStatusProgress[$current_status],
                    'status_color' => $this->orderStatusColours[$current_status]
                ];

                array_push($data_order, $data_details);

                if($n == $length - 1){
                    return view('Orders.listValidate', [
                        'clientOrders' => $data_order,
                        'current_page' => __('content.assigned_services')
                    ]);
                }
                $n++;
            }
        }

    } else {
        return redirect()->back();
    }

    }

    public function show_client_details($id)
    {
        if(Auth::user()->role == 'VALIDATOR' || Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){

        $order = Order::find($id);
        $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client')->find($order->client_id);
        $product = Product::find($order->product_id);

        $product_details = OrderDetails::join('orders', 'order_details.order_id', 'orders.id')->join('product_forms', 'order_details.productForm_id', 'product_forms.id')->join('products', 'product_forms.product_id', 'products.id')->select('product_forms.*', 'order_details.*')->where('orders.id', $id)->get();

        $status = OrderStatus::where('order_id', $order->id)->orderBy('id', 'desc')->first();
        $accounter = OrderAccounters::where('order_id', $order->id)->first();
        $accounter_details = '';
        $validation_details = '';
        $accounter_user_id = 0;
        $validator_id = 0;

        if($accounter == null){
            $accounter_details = 'Not defined';
        } else {
            $accounter_data = Worker::join('users', 'workers.user_id', '=', 'users.id')->select('users.*', 'workers.id as id_worker')->find($accounter->worker_id);
            $accounter_details = $accounter_data->name . ' ' . $accounter_data->lastname;
            $accounter_user_id = $accounter_data->id;
        }

        if($product->worker_id == 0){
            $validation_details = 'Not Required';
        } else {
            $validation = OrderValidations::where('order_id', $order->id)->first();
            if($validation == null){
                $validation_details = 'Validation Required';
            } else {
                $validation_details = $validation->status;
                $validator = Worker::join('users', 'workers.user_id', '=', 'users.id')->select('users.*', 'workers.id as id_worker')->find($validation->worker_id);
                $validator_id = $validator->id;
            }
        }

        $documents_sent = Document::whereRaw('order_id = ? AND type != ? AND type != ?', [$id, 'RECEIVED', 'FINAL DOCUMENT'])->get();
        $documents_received = Document::whereRaw('order_id = ? AND type = ? AND type = ?', [$id, 'RECEIVED', 'FINAL DOCUMENT'])->get();
        $notifications_received = OrderNotifications::join('orders', 'order_notifications.order_id', '=', 'orders.id')
                                           ->join('order_notifications_received_bies', 'order_notifications_received_bies.order_notifications_id', '=', 'order_notifications.id')
                                           ->join('workers', 'order_notifications_received_bies.worker_id', '=', 'workers.id')
                                           ->join('users', 'workers.user_id', '=', 'users.id')
                                           ->select('order_notifications.*', 'users.name as worker_name', 'users.lastname as worker_lastname', 'users.photo as worker_photo')
                                           ->whereRaw('order_notifications.order_id = ? AND order_notifications.type = ?', [$order->id, 'RECEIVED'])
                                           ->get();
        $notifications_sent = OrderNotifications::whereRaw('order_id = ? AND type = ?', [$order->id, 'SEND'])->get();
        $final_document = Document::whereRaw('order_id = ? AND type = ?',[$id, 'FINAL DOCUMENT'])->first();

        $final_doc_name = '';
        $final_doc_id = 0;

        if($final_document != null){
            $final_doc_name = $final_document->name;
            $final_doc_id = $final_document->id;
        }

        $data_order = [
            "client_name" => $client->name . ' ' . $client->lastname,
            "client_photo" => $client->photo,
            "order_id" => $order->id,
            "order_started_at" => $order->init_date,
            "order_finished_at" => $order->end_date,
            "order_comments" => $order->comments,
            "order_final_document_id" => $final_doc_id,
            "order_final_document_name" => $final_doc_name,
            "validation" => $validation_details,
            "validator_id" => $validator_id,
            "service_name" => $product->name,
            "service_details" => $product->description,
            "service_data" => $product_details,
            "accounter" => $accounter_details,
            "accounter_id" => $accounter_user_id,
            "status" => $status->status,
            'progress' => $this->orderStatusProgress[$status->status],
            'status_color' => $this->orderStatusColours[$status->status],
            "documents" => [
                'sent' => $documents_sent,
                'received' => $documents_received
            ],
            "notifications" => [
                "sent" => $notifications_sent,
                "received" => $notifications_received
            ]
        ];

        return view('Orders/show-client-order-detail', [
            'order' => $data_order,
            'current_page' => __('content.orders')
        ]);

    } else {
        return redirect()->back();
    }

    }

}
