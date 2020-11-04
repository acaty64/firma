@extends('layouts.app')

@section('content')
<div class="container">
	<c1m-component user_id={{ $user_id }}></c1m-component>
</div>
@endsection

@section('view','index.blade.php')