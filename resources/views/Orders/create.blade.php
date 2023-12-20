
@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h5 class="mb-0">{{ __('content.create_service_order') }}</h5>
            </div>
            <div class="card-body pt-4 p-3">

                <form action="{{ url('/client-area/service-orders/create-service-order/') . '/' . explode('/', Request::path())[3] }}" method="POST" role="form text-left" enctype="multipart/form-data">
                    @csrf

                    <div class="row mt-4 mb-4">
                        <div class="col-12">
                            <h6 class="mb-0 text-center service-title text-upp">{{ $data_product['product']->name }}</h6>
                        </div>
                    </div>

                    <div class="row mt-4 mb-4">
                        <div class="col-12">
                            <div class="d-flex flex-row flex-nowrap justify-content-between">
                                <small class="text-danger">** {{ __($data_product['product']->indications) }}</small>
                            </div>
                            <hr class="mt-1 mb-3" />
                        </div>
                    </div>

                    <div class="row mt-4 mb-3">
                        @foreach ($data_product['regular_fields'] as $field)
                            <div class="col-md-6">

                            @if ($field->field_type == 'radio')
                                <div class="form-group">
                                    <label for="data-field-radio-{{ __( $field->id ) }}" class="form-control-label">
                                        @if (session()->get('language') == 'es')
                                            {{ __($field->fieldname_spanish) }}
                                        @else
                                            {{ __($field->fieldname_english) }}
                                        @endif
                                    </label>
                                    <div class="form-check">
                                        <input type="radio" name="{{ __( $field->id ) }}-data-regular" id="data-option-yes-{{ __( $field->id ) }}" class="form-check-input" value="YES">
                                        <label for="data-option-yes-{{ __( $field->id ) }}" class="custom-control-label text-upp">{{ __('content.yes_answer') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="{{ __( $field->id ) }}-data-regular" id="data-option-no-{{ __( $field->id ) }}" class="form-check-input" value="NO" checked>
                                        <label for="data-option-no-{{ __( $field->id ) }}" class="custom-control-label text-upp">{{ __('content.no_answer') }}</label>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="data-field-{{ __( $field->id ) }}" class="form-control-label">
                                        @if (session()->get('language') == 'es')
                                            {{ __($field->fieldname_spanish) }}
                                        @else
                                            {{ __($field->fieldname_english) }}
                                        @endif
                                    </label>
                                    <div class="">
                                        <input class="form-control" type="{{ __( $field->field_type ) }}" id="data-field-{{ __( $field->id ) }}" name="{{ __( $field->id ) }}-data-regular" required>
                                    </div>
                                </div>
                            @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-4 mb-4">
                        <div class="col-12">
                            <div class="d-flex flex-row flex-nowrap justify-content-between">
                                <h6 class="mb-0 d-flex flex-row align-items-end"><span class="align-bottom">{{ __('content.file_attach') }}</span></h6>
                            </div>
                            <hr class="mt-1 mb-3" />
                        </div>
                    </div>

                    <div class="row">
                    @foreach ($data_product['file_fields'] as $field)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data-file-field-{{ __( $field->id ) }}" class="form-control-label">
                                    @if (session()->get('language') == 'es')
                                        {{ __($field->fieldname_spanish) }}
                                    @else
                                        {{ __($field->fieldname_english) }}
                                    @endif
                                </label>
                                <div class="">
                                    <input class="form-control" type="file" id="data-file-field-{{ __( $field->id ) }}" name="{{ __( $field->id ) }}-data-file" required>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('content.create_service_order') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
