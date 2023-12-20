<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use App\Providers\ProductCreated;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductForm;
use App\Models\Setting;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use LengthException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $products = Product::orderBy('id', 'asc')->get();
        $n = 0;
        $validator = 'Not Required';
        $data_product = [];

        foreach($products as $product){
            if($product->worker_id != 0) {
                $data_validator = Worker::join('users', 'workers.user_id', '=', 'users.id')->select('workers.*', 'users.name as name', 'users.lastname as lastname')->whereRaw('workers.id = ?', [$product->worker_id])->first();
                $validator = $data_validator->name . " " . $data_validator->lastname;
            }
             array_push($data_product, ['product' => $product, 'validator' => $validator]);
             $validator = 'Not Required';
             $n++;
        }

        if($n > 0){
            //show main view
            return view('Products.index', [
                'products' =>  $data_product,
                'current_page' => __('content.services')
            ]);
        } else{
            //show main view
            return view('Products.index', [
                'products' =>  [],
                'current_page' => __('content.services')
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

    public function create()
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        //get all validator
        $validators = Worker::join('users', 'workers.user_id', '=', 'users.id')->select('workers.*', 'users.name as name', 'users.lastname as lastname')->whereRaw('users.role = ?', ['VALIDATOR'])->get();
        //show the view with the form to create a new product
        return view('Products.create', [
            'validators' => $validators,
            'current_page' => __('content.services')
        ]);
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
    public function store(Request $request)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $product_id = 0;

        //the logic operations to store a new product in database
        $attributes = request()->validate([
            'name' => ['required', 'max:50'], //Rule::unique('products', 'name')
            'description' => ['required', 'max:255'],
            'indications' => ['max:255'],
            'image' => ['max:10240'],
            'type_service' => ['max:255']
        ]);

        $product_created_at = '';

        //validate if the user upload an image to this product
        if($request->hasFile('image')){
            $file_path = request()->file('image')->store('public/products');
            $attributes['image'] = $file_path;
        } else{
            $attributes['image'] = "";
        }

        $validator = 0;

        // add the main information of the product
        if($request->input('requirevalidator-options') == 'YES'){
            $validator = $request->input('validator_required');
        } else{
            $validator = 0;
        }

        if($request->has('fieldname_english')){
            // insert product here

            $product = Product::create([
                'type_service' => $attributes['type_service'],
                'name' => $attributes['name'],
                'description' => $attributes['description'],
                'worker_id' => $validator,
                'indications' => $attributes['indications'],
                'image' => $attributes['image'],
                'status' => 'active',
            ]);

            $product_id = $product->id;
            $product_created_at = $product->created_at;

            // add the product details to db
            $length = count($request->input('fieldname_english'));
            $englishFileName = $request->input('fieldname_english');
            $spanishFileName = $request->input('fieldname_spanish');
            $typeField = $request->input('field_type');
            $dataDetails = [];

            for ($i=0; $i < $length; $i++) {
                $dataDetails = [
                    'product_id' => $product_id,
                    'fieldname_english' => $englishFileName[$i],
                    'fieldname_spanish' => $spanishFileName[$i],
                    'field_type' => $typeField[$i]
                ];
                //insert product form
                $form_product = ProductForm::create($dataDetails);
            }

        } else {
            return redirect()->back()->with('error', __('content.messages.errors.add_required_fields') );
        }

        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $all_clients = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*')->get();
        $n = 0;

        $long_notification = '<li><a class="dropdown-item border-radius-md" href="/client-area/service-orders/create-service-order/'. $product_id .'">
            <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-info me-3 my-auto">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="d-flex flex-column justify-content-center">
                <h6 class="text-sm font-weight-normal mb-1">
                    '. __('content.mails.offer_new_service') . ': <span class="text-info text-gradient font-weight-bolder text-sm">' . $attributes['name'] . '</span>
                </h6>
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i>
                    ' . $product_created_at . '
                </p>
                </div>
            </div>
            </a>
        </li>';

        $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-info"><i class="fas fa-file-invoice"></i></span></a></div>
                <div class="tl-content">
                    <h6 class="m-0 p-0">'. __('content.mails.offer_new_service') . '!</h6>
                    <div class="tl-date text-muted">
                        <a href="/client-area/service-orders/create-service-order/'. $product_id .'" data-abc="true" class="text-info text-gradient font-weight-bolder text-sm">' . $attributes['name'] . '</a>
                    </div>
                <div class="text-xs text-muted mt-1"><i class="fa fa-clock me-1"></i> ' . $product_created_at . '</div>
            </div>
        </div>';

        foreach($all_clients as $client) {
            $notification_data = [
                'data_short' => $short_notification,
                'data_long' => $long_notification,
                'user_id' => $client->id
            ];
            //notification welcome to new client
            $new_notification = AppNotifications::create($notification_data);

            //email
            ProductCreated::dispatch($client->name, $client->email, $attributes['name'], $attributes['description'], config('app.url') . '/client-area/summery-client', $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
            $n++;
        }

        if($n == $all_clients->count()){
            return redirect()->route('services.index')->with('success', __('content.messages.success.service_created') );
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
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        $orders = Order::where('product_id', $id)->first();
        if($orders != null) {
            return redirect()->back()->with('error', __('content.messages.errors.service_deleted') );
        } else {
        //delete a service (product)
        $product = Product::where('id', $id)->delete();
        return redirect()->route('services.index')->with('success', __('content.messages.success.service_deleted') );
        }
    } else {
        return redirect()->back();
    }

    }

    public function active(Request $request, $id)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        //update status
         $product = Product::where('id', $id)->update([
            'status' => 'active'
         ]);
         return redirect()->route('services.index')->with('success', __('content.messages.success.service_active') );
        } else {
            return redirect()->back();
        }

        }

    public function inactive(Request $request, $id)
    {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR' || Auth::user()->role == 'ADMINISTRATOR'){
        //update status
        $product = Product::where('id', $id)->update([
            'status' => 'inactive'
        ]);
        return redirect()->route('services.index')->with('success', __('content.messages.success.service_inactive') );
    } else {
        return redirect()->back();
    }
    }


}
