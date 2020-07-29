@extends('layouts.site')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Добро пожаловать {{ auth()->user()->name }}!</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active">Панель управления</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
@endsection

@push('scripts')
    <!-- Chart JS -->
    <script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>
@endpush
