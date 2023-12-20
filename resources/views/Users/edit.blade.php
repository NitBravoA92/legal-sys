@extends('layouts.user_type.auth')

@section('content')
<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('content.users.update_user') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                
                <form action="{{ route('users.update', $user->id) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('content.first_name') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ $user->name }}" type="text" placeholder="{{ __('content.first_name') }}" id="user-name" name="name">
                                        @error('name')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-lastname" class="form-control-label">{{ __('content.last_name') }}</label>
                                <div class="@error('user.lastname')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ $user->lastname }}" type="text" placeholder="{{ __('content.last_name') }}" id="user-lastname" name="lastname">
                                        @error('lastname')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-phone" class="form-control-label">{{ __('content.phone_number') }}</label>
                                <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="40770888444" id="user-phone" name="phone" value="{{ $user->phone }}">
                                        @error('phone')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-alt_phone" class="form-control-label">{{ __('content.alt_phone_num') }}</label>
                                <div class="@error('user.alt_phone')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="40770888444" id="user-alt_phone" name="alt_phone" value="{{ $user->alt_phone }}">
                                        @error('alt_phone')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">{{ __('content.email') }}</label>
                                <div class="@error('user.email')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ $user->email }}" type="email" placeholder="@example.com" id="user-email" name="email">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-password" class="form-control-label">{{ __('content.users.new_password') }}</label>
                                <div class="@error('user.password') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="password" placeholder="{{ __('content.users.password') }}" id="user-password" name="password" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-role" class="form-control-label">{{ __('content.users.user_role') }}</label>
                                <div class="@error('role')border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="user-role" name="role">
                                        <option value="ADMINISTRATOR" {{ ($user->role == 'ADMINISTRATOR' ? 'selected' : '') }}>ADMINISTRATOR</option>
                                        <option value="CUSTOMER" {{ ($user->role == 'CUSTOMER' ? 'selected' : '') }}>CUSTOMER</option>
                                        <option value="VALIDATOR" {{ ($user->role == 'VALIDATOR' ? 'selected' : '') }}>VALIDATOR</option>
                                        <option value="CALL CENTER" {{ ($user->role == 'CALL CENTER' ? 'selected' : '') }}>CALL CENTER</option>
                                    </select>
                                        @error('role')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-location" class="form-control-label">{{ __('content.location') }}</label>
                                <div class="@error('user.location') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="{{ __('content.location') }}" id="user-location" name="location" value="{{ $user->location }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('content.save_changes') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection