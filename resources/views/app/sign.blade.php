@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Prueba de Vuejs</div>
                    <sign-component user_id={{ $user_id }}></sign-component>
            </div>
        </div>
    </div>
</div>
@endsection

@section('view','sign.blade.php')