@extends('layouts.app')

@section('content')
<div class="container">
	<ce-component user_id={{ $user_id }}></ce-component>
</div>
@endsection

@section('view','certificate.blade.php')