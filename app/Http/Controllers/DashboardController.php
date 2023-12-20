<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderAccounters;
use App\Models\OrderStatus;
use App\Models\OrderValidations;
use App\Models\Product;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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

    public function client()
    {
        if(Auth::user()->role == 'CUSTOMER'){

        $n = 0;
        $order_by_status = [
            'CANCELLED' => 0,
            'PROCESS STARTED' => 0,
            'ADDITIONAL DOCUMENTS REQUIRED' => 0,
            'IN PROCESS' => 0,
            'PROCESS FINISHED' => 0,
            'ORDER COMPLETED' => 0
        ];

        $main_services = Product::where('type_service', 'MAIN')->get();
        $additional_services = Product::where('type_service', 'ADDITIONAL')->get();

        $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client')->whereRaw('users.id = ?', [Auth::user()->id])->first();
        $orders = Order::where('client_id', $client->id_client)->get();

        $status = '';
        $orders_length = $orders->count();

        if($orders_length > 0){

            foreach($orders as $order){
                $order_status = OrderStatus::where('order_id', $order->id)->orderBy('id', 'desc')->first();
                $order_by_status[$order_status->status] += 1;
                $n++;
            }

            if($n == $orders_length){
                return view('Dashboard.customer', [
                    "services" => [
                        "main_services" => $main_services,
                        "additional_services" => $additional_services,
                    ],
                    "order_status_count" => $order_by_status,
                    'current_page' => __('content.dashboard')
                ]);
            }

        } else{
            return view('Dashboard.customer', [
                "services" => [
                    "main_services" => $main_services,
                    "additional_services" => $additional_services,
                ],
                "order_status_count" => $order_by_status,
                'current_page' => __('content.dashboard')
            ]);
        }
    } else {
        return redirect()->back();
    }

}

    public function accounter()
    {
        if(Auth::user()->role == 'ADMINISTRATOR'){

        $data_months = [
            'jan' => 0,
            'feb' => 0,
            'mar' => 0,
            'apr' => 0,
            'may' => 0,
            'jun' => 0,
            'jul' => 0,
            'aug' => 0,
            'sep' => 0,
            'oct' => 0,
            'nov' => 0,
            'dec' => 0
        ];

        $accounter = Worker::join('users', 'workers.user_id', 'users.id')->select('users.*', 'workers.id as id_worker')->whereRaw('users.id = ?', [Auth::user()->id])->first();
        $orders = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])->first();

        if($orders == null){
            return view('Dashboard.admin', [
                'clientOrders' => [],
                'data_months' => $data_months,
                'current_page' => __('content.dashboard')
            ]);
        } else {

        $length = $orders->count();
        $data_order = [];
        $n = 0;

        $year = date("Y");

        $orders_jan = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '1')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_feb = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '2')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_mar = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '3')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_apr = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '4')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_may = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '5')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_jun = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '6')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_jul = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '7')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_aug = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '8')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_sep = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '9')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_oct = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '10')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_nov = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '11')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $orders_dec = OrderAccounters::join('orders', 'order_accounters.order_id', 'orders.id')
                       ->join('workers', 'order_accounters.worker_id', 'workers.id')
                       ->select('orders.*', 'workers.id as id_worker')
                       ->whereRaw('order_accounters.worker_id = ?', [$accounter->id_worker])
                       ->whereMonth('orders.created_at', '12')
                       ->whereYear('orders.created_at', $year)
                       ->first();

        $data_months['jan'] = $orders_jan == null ? 0 : $orders_jan->count();
        $data_months['feb'] = $orders_feb == null ? 0 : $orders_feb->count();
        $data_months['mar'] = $orders_mar == null ? 0 : $orders_mar->count();
        $data_months['apr'] = $orders_apr == null ? 0 : $orders_apr->count();
        $data_months['may'] = $orders_may == null ? 0 : $orders_may->count();
        $data_months['jun'] = $orders_jun == null ? 0 : $orders_jun->count();
        $data_months['jul'] = $orders_jul == null ? 0 : $orders_jul->count();
        $data_months['aug'] = $orders_aug == null ? 0 : $orders_aug->count();
        $data_months['sep'] = $orders_sep == null ? 0 : $orders_sep->count();
        $data_months['oct'] = $orders_oct == null ? 0 : $orders_oct->count();
        $data_months['nov'] = $orders_nov == null ? 0 : $orders_nov->count();
        $data_months['dec'] = $orders_dec == null ? 0 : $orders_dec->count();

        foreach ($orders as $order){
            $service = Product::find(1);
            $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client')->find(1);
            $search_status = OrderStatus::where('order_id', 1)->orderBy('id', 'desc')->first();
            $current_status = $search_status->status;
            $validation_details = '';

            if($service->worker_id == 0){
                $validation_details = 'Not Required';
            } else {
                $validation = OrderValidations::where('order_id', 1)->first();
                if($validation == null){
                    $validation_details = 'Validation Required';
                } else {
                    $validation_details = $validation->status;
                }
            }

            $data_details = [
                'order_id' => 1,
                'name' => $service->name,
                'image' => $service->image,
                'client_name' => $client->name . ' ' . $client->lastname,
                'client_id' => $client->id_client,
                'status' => $current_status,
                'validation' => $validation_details,
                'progress' => $this->orderStatusProgress[$current_status],
                'status_color' => $this->orderStatusColours[$current_status]
            ];

            array_push($data_order, $data_details);

            if($n == $length - 1){
                return view('Dashboard.admin', [
                    'clientOrders' => $data_order,
                    'data_months' => $data_months,
                    'current_page' => __('content.dashboard')
                ]);
            }
            $n++;
        }

      }

    } else {
        return redirect()->back();
    }

    }

    public function super_admin() {
        if(Auth::user()->role == 'SUPER ADMINISTRATOR'){
        $users_accounter = User::where('role', 'ADMINISTRATOR')->get()->count();
        $users_validators = User::where('role', 'VALIDATOR')->get()->count();
        $users_call_center = User::where('role', 'CALL CENTER')->get()->count();
        $users_customers = User::where('role', 'CUSTOMER')->get()->count();
        $users_blocked = User::where('status', 'blocked')->get()->count();

        $all_orders = Order::all()->count();

        return view('Dashboard.super_admin', [
            'data_counts' => [
                'accounters' => $users_accounter,
                'validators' => $users_validators,
                'call_centers' => $users_call_center,
                'customers' => $users_customers,
                'blocked_users' => $users_blocked,
                'orders' => $all_orders
            ],
            'current_page' => __('content.dashboard')
        ]);
    } else {
        return redirect()->back();
    }
}


}
