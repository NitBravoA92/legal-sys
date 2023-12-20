
@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h5 class="mb-0">{{ __('content.additional_documents_status') }}</h5>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('clients-service-orders.request-documents', explode('/', Request::path())[3]) }}" method="POST" role="form text-left" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-4 mb-4">
                        <div class="col-12">
                            <div class="d-flex flex-row flex-nowrap justify-content-between">
                                <h6 class="mb-0 d-flex flex-row align-items-end"><span class="align-bottom">{{ __('content.required_documents') }}</span></h6>
                                <button type="button" id="addNewDocFieldGroup" class="btn bg-gradient-success mb-0 pt-2 pb-2 ps-3 pe-3 fs-8"><i class="fas fa-plus text-white"></i> {{ __('content.add_field') }}</button>
                            </div>
                            <hr class="mt-1 mb-3" />
                        </div>
                    </div>
                    <div class="row mt-4 mb-4" id="documentFieldGroupContainer">

                    </div>
                
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('content.send_document_request') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ env('APP_URL') }}/assets/js/file-input-generator.js"></script>

@endsection
