@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('checkPHP.blade.php') }}
        </div>
        <div class="card-body">
          <p>
            <?php
              if (extension_loaded('gd') && function_exists('gd_info')) {
                echo "PHP GD library is installed on your web server";
              }
              else {
                echo "PHP GD library is NOT installed on your web server";
              }
            ?>
          </p>
        </div>
        <div class="card-body">
          <p>gd_info</p>
          <?php
          print_r(gd_info());
          ?>
        </div>
        <div class="card-body">
          <?php phpinfo() ?>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection