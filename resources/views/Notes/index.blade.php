@extends('layouts.user_type.auth')
@section('content')
<div>
    <div class="row">
        <div class="col-12">

            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">{{ __('content.users.all_clients') }}</h5>
                        </div>
                        <div></div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">

                    <div class="table-responsive p-0 mt-2 mb-1">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('content.photo') }}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('content.full_name') }}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('content.email') }}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('content.phone') }}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('content.creation_date') }}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('content.action') }}
                                    </th>
                                </tr>
                            </thead> 
                            <tbody>
                                @foreach ($clients as $item)   
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $item->id_client }}</p>
                                            </td>
                                            <td>
                                                <div>
                                                    <img src="@if ($item->photo == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ Storage::url($item->photo) }}@endif" class="avatar avatar-sm me-3">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">{{ $item->name . __(' ') . $item->lastname }}</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">{{ $item->email }}</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">{{ $item->phone }}</p>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $item->created_at }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-sm bg-gradient-info text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.view_details') }}" href="{{ route('clients-management.show', $item->id_client) }}">
                                                    <i class="far fa-eye text-xs"></i>
                                                </a>
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection