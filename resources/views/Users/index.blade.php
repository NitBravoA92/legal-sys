@extends('layouts.user_type.auth')
@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">{{ __('content.users.all_users') }}</h5>
                        </div>
                        <div>
                            <a href="{{ route('users.create') }}" class="btn bg-gradient-info btn-sm mb-0" type="button">+&nbsp; {{ __('content.users.new_user') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 p-3">
                    <div class="table-responsive p-0 mt-2 mb-1">
                        <table class="table align-items-center mb-0" id="user-table">
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
                                        {{ __('content.users.user_role') }}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('content.status') }}
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
                                @foreach ($users as $item)
                                    @if ($item->id != auth()->user()->id)   
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $item->id }}</p>
                                            </td>
                                            <td>
                                                <div>
                                                    <img src="@if ($item->photo == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ \Storage::url($item->photo) }}@endif" class="avatar avatar-sm me-3" alt="user photo">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">{{ $item->name . __(' ') . $item->lastname }}</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">{{ $item->email }}</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">{{ $item->role }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm @if ($item->status == 'active') bg-gradient-success @else bg-gradient-warning @endif">{{ $item->status }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $item->created_at }}</span>
                                            </td>
                                            <td class="d-flex justify-content-center align-middle">
                                                @if ($item->role == 'CUSTOMER')
                                                <a href="{{ route('clients.show', $item->client->id) }}" class="mx-1 px-2 btn btn-sm bg-gradient-info text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.view_details') }}">
                                                    <i class="far fa-eye text-xs"></i>
                                                </a>
                                                @endif
                                                <!-- edit user form -->
                                                <a href="{{ route('users.edit', $item->id) }}" class="mx-1 px-2 btn btn-sm bg-gradient-dark text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.edit') }}">
                                                    <i class="fas fa-user-edit text-xs"></i>
                                                </a>
                                                <!-- if user is active show the block form else show the active form -->
                                                @if ($item->status == 'active')
                                                <form action="{{ route('users.block', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-alert-block-user btn btn-sm bg-gradient-warning text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.block') }}">   
                                                          <i class="fas fa-ban text-xs"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @if ($item->status == 'blocked')
                                                <form action="{{ route('users.active', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-alert-active-user btn btn-sm bg-gradient-success text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.active') }}">   
                                                          <i class="fas fa-check text-xs"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                <!--  -->

                                                <!-- delete user form -->
                                                <form action="{{ route('users.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn-delete-data btn btn-sm bg-gradient-danger text-white mb-0 mx-1 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.delete') }}">
                                                        <i class="far fa-trash-alt text-xs"></i>
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endif
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