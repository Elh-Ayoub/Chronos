<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home - {{env('APP_NAME')}}</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
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
            <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Home - {{$calendar->name}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
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
            <div class="container-fluid">
                <div class="row">
                <div class="col-md-3">
                    <div class="sticky-top mb-3">
                        <div class="card">
                            <div class="card-header">
                            <h4 class="card-title">Today Events</h4>
                            </div>
                            <div class="card-body">
                                <ul class="todo-list" data-widget="todo-list">
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                            <h3 class="card-title">About Events</h3>
                            </div>
                            <div class="card-body">
                            <a class="btn btn-primary mt-2" href="{{route('events.create.view', $calendar->id)}}"><i class="fas fa-plus"></i>Create event</a>
                            </div>
                            <div class="row justify-content-center mb-2">
                                <div class="icheck-primary d-inline ml-2 forHolidays">
                                    <input type="checkbox" id="showHolidays">
                                    <label id="label4showHolidays" for="showHolidays"></label>
                                </div>
                            </div>
                            @if($calendar->name !== 'Main Calendar')
                            <button class="btn btn-danger" data-toggle="modal" data-target="#delete-calendar">Delete calendar</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card card-primary">
                    <div class="card-body p-0">
                        <!-- THE CALENDAR -->
                        <div id="calendar" data-events="{{json_encode($events)}}"></div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </section>
        <div id="create-event-modal" class="modal fade">
            <div class="modal-dialog modal-lg">
                <form action="{{route('events.create', ['calendar_id' => $calendar->id])}}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        @include('layouts.eventForm', ['event' => NULL])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="delete-calendar" class="modal fade">
            <div class="modal-dialog">
                <form action="{{route('calendars.delete', $calendar->id)}}" method="POST" class="modal-content bg-danger">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h4 class="modal-title">Delete {{$calendar->name}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You're about to delete a Calendar. Are you sure ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </div>
                </form>
            </div>
        </div>
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
<script src="http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery-ui.custom.min.js"></script>
<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/fullcalendar.min.js'></script>
<script src="{{asset('js/getCountryCode.js')}}"></script>
<script src="{{asset('js/calendar.js')}}"></script>
<script>
    
</script>
</body>
</html>
