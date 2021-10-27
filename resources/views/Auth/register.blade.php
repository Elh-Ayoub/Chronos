<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo_transparent.png')}}"/>
  <title>Register - {{env('APP_NAME')}}</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{ asset('css/auth.css')}}">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="{{route('dashboard')}}"><b>Chronos</b></a>
  </div>
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>
      <form method="POST" action="{{route('auth.register')}}" enctype="multipart/form-data">
      @csrf
      @if(Session::get('success'))
            <div class="alert alert-success text-center">
              {{Session::get('success')}}
            </div>
        @endif

        @if(Session::get('fail'))
            <div class="alert alert-danger text-center">
                @foreach(Session::get('fail') as $key => $err)
                <p class="fail">{{$key . ': ' . $err[0]}}</p>
                @endforeach
            </div>
        @endif
      <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Username" name="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Full name" name="full_name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="form-group row mb-3">
          <div class="col-sm-12">
          <select class="form-control custom-select" aria-label="Default select example" name="timezone"> 
            <option selected disabled>Select timezone</option>
            @foreach (config('timezones.timezones') as $key => $timezone)
               <option class="w-100 d-flex justify-content-around" value="{{$key}}"><span>{{$key}} </span>|<span> (GMT {{(($diff = (timezone_offset_get(timezone_open($key), date_create("now", timezone_open("UTC")))) )< 0) ? ("-" . gmdate("H:i", $diff*-1)) : ("+" . gmdate("H:i", $diff))}})</span></option>
            @endforeach
          </select>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block mb-2">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <div class="col-12 row justify-content-center">
        <a href="login" class="text-center">I already have a membership</a>
      </div>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
</body>
</html>
