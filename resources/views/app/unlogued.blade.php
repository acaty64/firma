@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>
                <div class="col-md-2">
                    {{-- <a href="{{ route('login')  }}">Login</a> --}}
                </div>
                <div class="card-body">
                    <p>
                        Debe acceder por este enlace: 
                        <a href="{{ env("APP_URL_USER") }}">{{ env("APP_URL_USER") }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
