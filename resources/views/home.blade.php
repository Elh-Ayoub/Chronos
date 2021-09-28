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
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo_transparent.png')}}"/>
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
                    <h1 class="m-0"> Home - Main Calendar</h1>
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
                            <h4 class="card-title">Draggable Events</h4>
                            </div>
                            <div class="card-body">
                            <!-- the events -->
                            <div id="external-events">
                                <div class="external-event bg-success">Lunch</div>
                                <div class="external-event bg-warning">Go home</div>
                                <div class="external-event bg-info">Do homework</div>
                                <div class="external-event bg-primary">Work on UI design</div>
                                <div class="external-event bg-danger">Sleep tight</div>
                                <div class="checkbox">
                                <label for="drop-remove">
                                    <input type="checkbox" id="drop-remove">
                                    remove after drop
                                </label>
                                </div>
                            </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <div class="card">
                            <div class="card-header">
                            <h3 class="card-title">Create Event</h3>
                            </div>
                            <div class="card-body">
                            <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                                <ul class="fc-color-picker" id="color-chooser">
                                <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                                </ul>
                            </div>
                            <div class="input-group">
                                <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                                <div class="input-group-append">
                                <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#modal-create-event"><i class="fas fa-plus"></i>Create event</button>
                            </div>
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
        <div class="modal fade" id="modal-create-event">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create event</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('events.create', ['calendar_id' => $calendar->id])}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" maxlength="200"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="start">Start date</label>
                            <input type="datetime-local" id="start" name="start" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="end">End date</label>
                            <input type="datetime-local" id="end" name="end" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group row">
                            <label for="inputbackgroundColor" class="col-sm-2 col-form-label">Background color</label>
                            <div id="inputbackgroundColor" name="backgroundColor" class="form-control">
                                <label for="blue" class="text-primary">Blue</label>
                                <input type="radio" id="blue" name="backgroundColor" value="#0073b7">
                                <label for="yellow" class="text-warning">Yellow</label>
                                <input type="radio" id="yellow" name="backgroundColor" value="#f39c12">
                                <label for="green" class="text-success">Green</label>
                                <input type="radio" id="green" name="backgroundColor" value="#00a65a">
                                <label for="red" class="text-danger">Red</label>
                                <input type="radio" id="red" name="backgroundColor" value="#f56954">
                                <label for="aqua" class="text-info">Aqua</label>
                                <input type="radio" id="aqua" name="backgroundColor" value="#00c0ef">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputborderColor" class="col-sm-2 col-form-label">Border Color</label>
                            <div id="inputborderColor" name="borderColor" class="form-control">
                                <label for="blue" class="text-primary">Blue</label>
                                <input type="radio" id="blue" name="borderColor" value="#0073b7">
                                <label for="yellow" class="text-warning">Yellow</label>
                                <input type="radio" id="yellow" name="borderColor" value="#f39c12">
                                <label for="green" class="text-success">Green</label>
                                <input type="radio" id="green" name="borderColor" value="#00a65a">
                                <label for="red" class="text-danger">Red</label>
                                <input type="radio" id="red" name="borderColor" value="#f56954">
                                <label for="aqua" class="text-info">Aqua</label>
                                <input type="radio" id="aqua" name="borderColor" value="#00c0ef">
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="inputallDay" class="col-sm-2 col-form-label">All day event</label>
                            <div class="col-sm-10">
                                <select id="inputallDay" name="allDay" class="form-control custom-select">
                                    <option selected disabled>Select one</option>
                                    <option><span class="text-primary"><i class="fas fa-square"></i></span>true</option>
                                    <option><span class="text-warning"><i class="fas fa-square"></i></span>false</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" id="category" name="category" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="url">include URL</label>
                            <input type="text" id="url" name="url" class="form-control" maxlength="100">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
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
<script src="{{asset('js/calendar.js')}}"></script>
</body>
</html>
