<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use App\Models\Client;
use App\Models\Document;
use App\Models\Order;
use App\Models\OrderAccounters;
use App\Models\OrderAdditionalDocumentsRequest;
use App\Models\OrderStatus;
use App\Models\Setting;
use App\Models\User;
use App\Models\Worker;
use App\Providers\OrderUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

//to clients
    public function index()
    {
        if(Auth::user()->role == 'CUSTOMER'){
        $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('clients.id as id_client')->whereRaw('users.id = ?', [Auth::user()->id])->first();
        $documents = Document::join('orders', 'documents.order_id', '=', 'orders.id')
                       ->join('clients', 'orders.client_id', '=', 'clients.id')
                       ->join('products', 'orders.product_id', '=', 'products.id')
                       ->select('documents.*', 'orders.id as orderID', 'products.name as productName', 'products.image as productImage')
                       ->whereRaw('orders.client_id = ?', [$client->id_client])
                       ->get();
        return view('Documents.history', [
            "client_documents" => $documents,
            'current_page' => __('content.document_history')
        ]);
    } else {
        return redirect()->back();
    }

    }

// to accounters and super admin
    public function all()
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $documents = Document::join('orders', 'documents.order_id', '=', 'orders.id')
                       ->join('clients', 'orders.client_id', '=', 'clients.id')
                       ->join('products', 'orders.product_id', '=', 'products.id')
                       ->join('users', 'clients.user_id', '=', 'users.id')
                       ->select('documents.*', 'orders.id as orderID', 'products.name as productName', 'products.image as productImage', 'users.name as client_name', 'users.lastname as client_lastname', 'users.photo as client_photo')
                       ->get();

        return view('Documents.index', [
            "clients_documents" => $documents,
            'current_page' => __('content.documents')
        ]);
    } else {
        return redirect()->back();
    }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $id = (int) $request->input('gitgth02848g*%gfd');

        $order = Order::join('products', 'orders.product_id', 'products.id')->select('orders.*', 'products.name as service_name')->find($id);
        $client_INFO = Client::join('users', 'clients.user_id', 'users.id')->select('users.*')->find($order->client_id);

        if(Auth::user()->role == 'CUSTOMER' && Auth::user()->id == $client_INFO->id){

        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $new_status = 'IN PROCESS';

        $id_file = 0;
        $all_input_token = $request->input('data-token');

        $file_name = '';
        $file_folder = 'public/files/customers/customer-' . Auth::user()->id . '/orders/order-'. $id;
        $file_path = '';
        $data_value = 0;

        for ($i=0; $i < count($all_input_token); $i++) {
            $id_file = (int) $all_input_token[$i];
            $fileValidate = request()->validate([
                'additional-'.$id_file => ['required', 'max:20480']
            ]);

            $file_name = $request->file('additional-'.$id_file)->getClientOriginalName();

            //validate file name
            if(Document::where('name', $file_name)->exists()){
                $file_name = date('Y-m-d_H-i-s') . "_" . $file_name;
            }

            $file_path = $request->file('additional-'.$id_file)->store($file_folder);
            $data_value = $file_name;

            $dataDocument = [
                'name' => $file_name,
                'path' => $file_path,
                'type' => 'ADDITIONAL',
                'order_id' => $id,
            ];

            //here save the documents
            $doc = Document::create($dataDocument);

            //Update status request additional documents table
            $additional_doc_request = OrderAdditionalDocumentsRequest::find($id_file)->update([
                "status" => "UPLOADED"
            ]);
        }

        $dataStatus = [
            'order_id' => $id,
            'status' => $new_status
        ];
        //here save data order status
        $status = OrderStatus::create($dataStatus);

        $order_accounter = OrderAccounters::where('order_id', $id)->first();

        $lang = session()->get('language');
        $comments = $lang == 'es' ? 'El cliente ha enviado los documentos adicionales requeridos.' : 'The client has sent the required additional documents.';

        $worker = null;

        if($order_accounter == null){
            $worker = User::where('role', 'SUPER ADMINISTRATOR')->first();
        } else {
            $worker = Worker::join('users', 'workers.user_id', 'users.id')->select('users.*')->find($order_accounter->worker_id);
        }

        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-dark"><i class="fas fa-file-contract"></i></span></a></div>
            <div class="tl-content">
                <h6 class="m-0 p-0">'. __('content.mails.client_has_send_documents') .'.</h6>
                <p class="tl-date text-muted">
                    '. __('content.mails.the_service_order'). ' <a href="/management-area/client-service-order/details/' . $order->id . '" data-abc="true"> #'. $order->id .': ' . $order->service_name . '</a> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-dark">' . $new_status . '</span>
                </p>
                <small class="text-xs text-muted"><i class="fa fa-clock me-1"></i> '. $status->created_at .'</small>
            </div>
        </div>
        ';

        $long_notification = '
        <li><a class="dropdown-item border-radius-md" href="/management-area/client-service-order/details/' . $order->id . '">
            <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-dark  me-3 my-auto">
                <i class="fas fa-file-contract"></i>
                </div>
                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    <span class="font-weight-bold">'. __('content.mails.client_has_send_documents') .'</span>
                </h6>
                <p class="text-xs text-muted mb-0 mt-0">
                '. __('content.mails.the_service_order'). ' <span class="font-weight-bold text-xs">#'. $order->id .': ' . $order->service_name . '</span> ' . __('content.mails.has_been_updated_to') . ': <span class="badge badge-sm bg-gradient-dark">' . $new_status . '</span>
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
            'user_id' => $worker->id
        ];
        //notification welcome to new client
        $new_notification = AppNotifications::create($notification_data);

        //email notification to client and administrator
        OrderUpdated::dispatch($worker->name, $worker->email, $id, $order->service_name, $status->updated_at, $new_status, $comments, $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);

        return redirect()->back()->with('success', __('content.messages.success.additional_documents_sent') );

    } else {
        return redirect()->back();
    }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDocumentRequest  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
    }

    public function download($id)
    {
        if(Auth::check()){
            if(Auth::user()->role != 'CALL CENTER' && Auth::user()->role != 'VALIDATOR'){
                $file = Document::find($id);
                return Storage::download($file->path);
            } else {
                return redirect()->back()->with('error', __('content.messages.errors.download_files_permissions') );
            }
        } else {
            return redirect()->back();
        }
    }
}

