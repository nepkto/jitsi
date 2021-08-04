@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>

                <div class="card-body">
                    <li><a href="{{ url('c1') }}">Class 1 (admin@admin.com1)</a></li>
                    <li><a href="{{ url('c2') }}">Class 2 (admin@admin.com2)</a></li>
                    <li><a href="{{ url('c3') }}">Class 3 (admin@admin.com3)</a></li>
                    <li><a href="{{ url('c4') }}">Class 4 (admin@admin.com4)</a></li>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
