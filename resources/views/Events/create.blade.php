<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Calendars - {{env('APP_NAME')}}</title>
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
                    <li class="breadcrumb-item"><a href="#">{{$calendar->name}}</a></li>
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
                <form action="{{route('events.create', ['calendar_id' => $calendar->id])}}" class="card card-primary" method="POST">
                    @csrf
                    <div class="card-header">
                        <h3 class="card-title">Create event - {{$calendar->name}}</h3>
                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" maxlength="300"></textarea>
                        </div>
                        <div class="form-group d-flex justify-content-around">
                            <div class="form-group d-flex">
                                <label for="start" class="col-sm-4">Start date</label>
                                <input type="datetime-local" id="start" name="start" class="form-control" placeholder="start">
                            </div>
                            <div class="form-group d-flex">
                                <label for="end" class="col-sm-4">End date</label>
                            <input type="datetime-local" id="end" name="end" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Background color</label>
                            <div class="w-100 row justify-content-around flex-row">
                                <div>
                                    <input class="color-select invisible" type="radio" name="backgroundColor" id="blue" autocomplete="off" value="#0073b7">
                                    <label for="blue" class="btn btn-default text-center">
                                        Blue
                                        <br>
                                        <i class="fas fa-circle fa-2x text-primary"></i>
                                    </label>
                                </div>
                                <div>
                                    <input class="color-select invisible" type="radio" name="backgroundColor" id="yellow" autocomplete="off" value="#f39c12">
                                    <label for="yellow" class="btn btn-default text-center">
                                    Yellow
                                        <br>
                                        <i class="fas fa-circle fa-2x text-warning"></i>
                                    </label>
                                </div>
                                <div>
                                    <input class="color-select invisible" type="radio" name="backgroundColor" id="green" autocomplete="off" value="#00a65a">
                                    <label for="green" class="btn btn-default text-center">
                                    Green
                                        <br>
                                        <i class="fas fa-circle fa-2x text-success"></i>
                                    </label>
                                </div>
                                <div>
                                    <input class="color-select invisible" type="radio" name="backgroundColor" id="red" autocomplete="off" value="#f56954">
                                    <label for="red" class="btn btn-default text-center">
                                    Red
                                        <br>
                                        <i class="fas fa-circle fa-2x text-danger"></i>
                                    </label>
                                </div>
                                <div>
                                    <input class="color-select invisible" type="radio" name="backgroundColor" id="aqua" autocomplete="off" value="#00c0ef">
                                    <label for="aqua" class="btn btn-default text-center">
                                    Aqua
                                        <br>
                                        <i class="fas fa-circle fa-2x text-info"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Border Color</label>
                            <div class="w-100 row justify-content-around flex-row">
                                <div>
                                    <input class="color-select invisible" type="radio" name="borderColor" id="blue-border" autocomplete="off" value="#0073b7">
                                    <label for="blue-border" class="btn btn-default text-center">
                                        Blue
                                        <br>
                                        <i class="fas fa-circle fa-2x text-primary"></i>
                                    </label>
                                </div>
                                <div>
                                    <input class="color-select invisible" type="radio" name="borderColor" id="yellow-border" autocomplete="off" value="#f39c12">
                                    <label for="yellow-border" class="btn btn-default text-center">
                                    Yellow
                                        <br>
                                        <i class="fas fa-circle fa-2x text-warning"></i>
                                    </label>
                                </div>
                                <div>
                                    <input class="color-select invisible" type="radio" name="borderColor" id="green-border" autocomplete="off" value="#00a65a">
                                    <label for="green-border" class="btn btn-default text-center">
                                    Green
                                        <br>
                                        <i class="fas fa-circle fa-2x text-success"></i>
                                    </label>
                                </div>
                                <div>
                                    <input class="color-select invisible" type="radio" name="borderColor" id="red-border" autocomplete="off" value="#f56954">
                                    <label for="red-border" class="btn btn-default text-center">
                                    Red
                                        <br>
                                        <i class="fas fa-circle fa-2x text-danger"></i>
                                    </label>
                                </div>
                                <div>
                                    <input class="color-select invisible" type="radio" name="borderColor" id="aqua-border" autocomplete="off" value="#00c0ef">
                                    <label for="aqua-border" class="btn btn-default text-center">
                                    Aqua
                                        <br>
                                        <i class="fas fa-circle fa-2x text-info"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-around align-items-center">
                            <div class="form-group d-flex align-items-center col-4 mt-4">
                                <label for="inputallDay" class="w-auto col-4">All day event</label>
                                <select id="inputallDay" name="allDay" class="form-control custom-select">
                                    <option selected disabled>Select one</option>
                                    <option>true</option>
                                    <option></span>false</option>
                                </select>
                            </div>
                            <div class="form-group d-flex align-items-center col-6 mt-4">
                                <label for="inputcategory" class="mr-2">Category</label>
                                <select id="inputcategory" name="category" class="form-control custom-select">
                                    <option selected disabled>Select one</option>
                                    <option>Arrangement</option>
                                    <option>Reminder</option>
                                    <option>Task</option>
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-around bg-gray-light pt-2 pb-2 pr-0 pl-0 m-0 w-100">
                            <a type="button" class="btn btn-default mt-1 mb-1">Cancel</a>
                            <button type="submit" class="btn btn-primary mt-1 mb-1">Create</button>
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
