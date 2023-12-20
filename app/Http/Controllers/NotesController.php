<?php

namespace App\Http\Controllers;

use App\Models\AppNotifications;
use App\Models\Client;
use App\Models\Notes;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Models\Worker;
use App\Providers\NoteCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'CALL CENTER'){
        $clients = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client')->get();
        return view('Notes.index', [
            'clients' => $clients,
            'current_page' => __('content.notes')
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, $id)
    {
        if(Auth::user()->role == 'CALL CENTER'){
        $setting = Setting::find(1);
        $logo_email = $setting->app_logo == '' ? config('app.url') . "/assets/img/logos/system-logo.png" : config('app.url') . Storage::url($setting->app_logo);

        $attributes = request()->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:512']
        ]);
        $worker = Worker::join('users', 'workers.user_id', '=', 'users.id')->select('users.*', 'workers.id as id_worker')->whereRaw('users.id = ?', [Auth::user()->id])->first();
        $data_note = [
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'client_id' => $id,
            'worker_id' => $worker->id_worker,
        ];
        $note = Notes::create($data_note);

        $client = Client::join('users', 'clients.user_id', 'users.id')->select('users.*')->find($id);
        $users = User::whereRaw('role = ? AND role = ?', ['SUPER ADMINISTRATOR', 'ADMINISTRATOR'])->get();

    $long_notification = '
    <li>
        <a class="dropdown-item border-radius-md" href="/management-area/clients/'. $id .'">
        <div class="d-flex py-1">
            <div class="avatar avatar-sm bg-gradient-info  me-3  my-auto">
                <i class="fas fa-sticky-note"></i>
            </div>
            <div class="d-flex flex-column justify-content-center">
            <h6 class="text-sm font-weight-normal mb-1">
                '. __('content.mails.new_management_note_from') . ' ' . $worker->name . ' ' . $worker->lastname . '
            </h6>
            <p class="text-xs text-secondary mb-0">
                ' . $attributes['title'] . ' <br>
                <i class="fa fa-clock me-1"></i>
                ' . $note->created_at . '
            </p>
            </div>
        </div>
        </a>
    </li>';

    $short_notification = '
        <div class="tl-item">
            <div class="tl-dot"><a class="tl-author" href="#" data-abc="true"><span class="w-32 avatar circle bg-gradient-info"><i class="fas fa-sticky-note"></i></span></a></div>
                <div class="tl-content">
                    <div class="">'. __('content.mails.new_management_note_from') . ' ' . $worker->name . ' ' . $worker->lastname . '</div>
                    <div class="tl-date text-muted mt-1">
                        <a href="/management-area/clients/'. $id .'" data-abc="true">' . $attributes['title'] . '</a>
                    </div>
                <div class="text-xs text-muted mt-1"><i class="fa fa-clock me-1"></i> ' . $note->created_at . '</div>
            </div>
    </div>';

    foreach($users as $user){

        $notification_data = [
            'data_short' => $short_notification,
            'data_long' => $long_notification,
            'user_id' => $user->id
        ];
        //notification welcome to new client
        $new_notification = AppNotifications::create($notification_data);

        //email notification
        NoteCreated::dispatch($user->email, $worker->name . ' ' . $worker->lastname, $client->name . ' ' . $client->lastname, $note->created_at, $attributes['title'], $attributes['description'], $setting->app_name, $logo_email, $setting->about_us, $setting->app_address, $setting->app_email, $setting->app_phone);
    }

        return redirect()->back()->with('success', __('content.messages.success.note_created') );
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
        if(Auth::user()->role == 'CALL CENTER'){
        $client = Client::join('users', 'clients.user_id', '=', 'users.id')->select('users.*', 'clients.id as id_client')->find($id);
        $notes = Notes::where('client_id', $id)->orderBy('id', 'desc')->get();
        $orders = Order::where('client_id', $id)->get();

        return view('Notes.show-client', [
            "client_data" => [
                "client" => $client,
                "num_orders" => count($orders),
                "notes" => $notes
            ],
            'current_page' => __('content.notes')
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->role == 'CALL CENTER'){
            //delete the user
            $user = Notes::where('id', $id)->delete();
            return redirect()->back()->with('success', __('content.messages.success.note_deleted') );
        } else {
            return redirect()->back();
        }
    }


}
