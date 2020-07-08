@extends('layouts.app')

@section('content')
<div class="container">
	<sign-component user_id={{ $user_id }}></sign-component>
</div>
@endsection

@section('view','sign.blade.php')