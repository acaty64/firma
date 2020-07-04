<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
				@foreach (['danger', 'warning', 'success', 'info'] as $key)
					@if(Session::has($key))
					     <p class="alert alert-{{ $key }}">{{ Session::get($key) }}</p>
					@endif
				@endforeach
				@if ($errors->any())
					<div class="alert alert-danger">
					    <p>
					      Por favor corrija los siguientes errores:
					    </p>
					    <ul>
					        @foreach ($errors->all() as $error)
					            <li>{{ $error }}</li>
					        @endforeach
					    </ul>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>