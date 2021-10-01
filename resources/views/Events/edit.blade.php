<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit event - {{env('APP_NAME')}}</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo_transparent.png')}}"/>
  <style>.color-select:checked + label{ background-color: rgb(124, 124, 124); border-radius: 10px;}</style>
</head>
<body class="hold-transition sidebar-collapse layout-top-nav">
<div class="wrapper">
    @include('layouts.navbar')
    @include('layouts.sidebar')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1 class="m-0">create </h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('user.calendars.show', $calendar->id)}}">{{$calendar->name}}</a></li>
                    <li class="breadcrumb-item"><a href="#">create event</a></li>
                    </ol>
                </div>
                </div>
                @if(Session::get('success'))
                    <div class="alert alert-success col-sm-3 ml-2 text-center" role="alert">
                        {{Session::get('success')}}
                    </div>
                @endif
                @if(Session::get('fail'))
                    <div class="alert alert-danger col-sm-3 ml-1" role="alert">
                        {{Session::get('fail')}}
                    </div>
                @endif
                @if(Session::get('fail-arr'))
                    <div class="alert alert-danger col-sm-3 ml-1" role="alert">
                        @foreach(Session::get('fail-arr') as $key => $err)
                        <p>{{$key . ': ' . $err[0]}}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <section class="content">
            <div class="container">
                <form action="{{route('events.update',  $event->id)}}" class="card card-primary" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="card-header">
                        <h3 class="card-title">Create event - {{$calendar->name}}</h3>
                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('layouts.eventForm', ['event' => $event])
                        <div class="row justify-content-around bg-gray-light pt-2 pb-2 pr-0 pl-0 m-0 w-100">
                            <a type="button" href="{{route('user.calendars.show', $calendar->id)}}" class="btn btn-default mt-1 mb-1">Cancel</a>
                            <button type="submit" class="btn btn-warning mt-1 mb-1">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
  @include('layouts.footer')
</div>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- jQuery UI -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar/main.js')}}"></script>
<script src="{{asset('dist/js/demo.js')}}"></script>
<script src="{{asset('js/calendar.js')}}"></script>
</body>
</html>
