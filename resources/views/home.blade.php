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
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
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
                            <h3 class="card-title">About</h3>
                            </div>
                            @if(($calendar->user_id === Auth::id()) || App\Models\Sharing::where(['target'=>'calendar', 'target_id'=>$calendar->id, 'shared_to_email' => Auth::user()->email, 'shared_to_role' => 'admin'])->first())
                            <div class="d-flex justify-content-around mt-2 align-items-center flex-column">
                                <div class="d-flex mt-2">
                                    <a class="btn btn-primary mr-1" href="{{route('events.create.view', $calendar->id)}}"><i class="fas fa-plus pr-2"></i>Create event</a>
                                    <button class="btn btn-info ml-1" data-toggle="modal" data-target="#share-calendar"><i class="fas fa-share pr-2"></i>Share Calendar</button>  
                                </div>
                                <div class="d-flex mt-2">
                                    @if($calendar->name !== 'Main Calendar')
                                    <button class="btn btn-danger mr-1" data-toggle="modal" data-target="#delete-calendar"><i class="fas fa-times pr-2"></i>Delete calendar</button>
                                    <button class="btn btn-warning ml-1" data-toggle="modal" data-target="#edit-calendar"><i class="fas fa-pen pr-2"></i>Edit calendar</button>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif
                            <div class="d-flex justify-content-around mb-2 mt-2">
                                <div>
                                <div class="icheck-primary d-inline ml-2 forHolidays">
                                    <input type="checkbox" id="showHolidays">
                                    <label id="label4showHolidays" for="showHolidays"></label>
                                </div><br>
                                <div class="icheck-primary d-inline ml-2">
                                    <input type="checkbox" name="ShowCategories" id="showArrangements" checked>
                                    <label for="showArrangements">Arrangement</label>
                                </div><br>
                                <div class="icheck-primary d-inline ml-2">
                                    <input type="checkbox" name="ShowCategories" id="showReminders" checked>
                                    <label for="showReminders">Reminders</label>
                                </div><br>
                                <div class="icheck-primary d-inline ml-2">
                                    <input type="checkbox" name="ShowCategories" id="showTasks" checked>
                                    <label for="showTasks">Tasks</label>
                                </div><br>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">People who can see this calendar</h3>
                            </div>
                            <div class="card-body">
                                @foreach ($watchers as $user)
                                <div class="row justify-content-between align-items-center align-content-center mt-2 w-75 ml-auto mr-auto">
                                    <div class="row justify-content-lg-start align-items-center">
                                        <img src="{{$user['user']->profile_photo}}" class="img-sm img-circle mr-2 " alt="" style="border: 1px solid grey;">
                                        <span>{{($user['user']->username === Auth::user()->username) ? ("You") : ($user['user']->username)}}</span>
                                        <span class="small ml-1">({{$user['role']}})</span>  
                                    </div>
                                    @if($user['user']->id !== $calendar->user_id && ($calendar->user_id == Auth::id() || App\Models\Sharing::where(['target'=>'calendar', 'target_id'=>$calendar->id, 'shared_to_email' => Auth::user()->email, 'shared_to_role' => 'admin'])->first()))
                                    <div class="row">
                                        <button class="btn btn-warning btn-xs mr-1" data-toggle="modal" data-target="#update-invited-role-{{$user['user']->id}}"><i class="fas fa-pen"></i></button>
                                        <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#remove-invited-{{$user['user']->id}}"><i class="fas fa-trash"></i></button>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @if($invited && $invited !== [])
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">People invied to this calendar</h3>
                            </div>
                            <div class="card-body">
                                @foreach ($invited as $user)
                                <div class="row justify-content-around align-items-center mb-2">
                                    <div class="row justify-content-lg-start align-items-center">
                                     <span class="text-primary">{{$user['email']}}</span>
                                    <span class="ml-1"> as <span class="text-primary">{{$user['role']}}</span></span>   
                                    </div>                                    
                                    @if(($calendar->user_id === Auth::id()) || App\Models\Sharing::where(['target'=>'calendar', 'target_id'=>$calendar->id, 'shared_to_email' => Auth::user()->email, 'shared_to_role' => 'admin'])->first())
                                    <button class="btn btn-danger btn-xs " data-toggle="modal" data-target="#cancel-invitation-user-{{explode('@',$user['email'])[0]}}"><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card card-primary">
                    <div class="card-body p-0">
                        <!-- THE CALENDAR -->
                        <div id="calendar" data-timezone="{{Auth::user()->timezone}}" data-calid="{{$calendar->id}}" data-events="{{json_encode($events)}}"></div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </section>
        @include('Modals.events')
        @include('Modals.calendar')
        @include('Modals.WatchersInvited')
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
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script>
    function addEmailsection(){
        $('.emails-container').append('<div class="form-group d-flex align-items-center">'+
                            '<label class="mr-2">Email</label>'+
                            '<input type="text" name="email[]" class="form-control" maxlength="100"><button class="btn btn-danger" onClick="$(this).parent().remove();"><i class="fas fa-trash"></i></button></div>')
    }
    function addEmailRolesection(){
        $('.emails-container').append('<div class="form-group d-flex align-items-center">'+
                            '<input type="text" name="email[]" class="form-control" maxlength="100" placeholder="Email">'+
                           ' <select name="role[]" class="form-control custom-select w-25">'+
                                '<option>guest</option>'+
                                '<option>admin</option>'+
                           ' </select>'+
                            '<button class="btn btn-danger" onClick="$(this).parent().remove();"><i class="fas fa-trash"></i></button></div>')
    }
    $(".limit_description").text(function (i, t) {
    if(t.length > 80){
        return t.substring(0, 80) + "...";
    }else{
        return t;
    }
    
}); 
</script>
@if(Session::get('fail'))
<script>
  $(function() {
    toastr.error("{{Session::get('fail')}}")
  });
</script>
@endif
@if(Session::get('success'))
<script>
  $(function() {
    toastr.success("{{Session::get('success')}}")
  });
</script>
@endif
@if(Session::get('fail-arr'))
    @foreach(Session::get('fail-arr') as $key => $err)
    <script>
      $(function() {
        toastr.error("{{$err[0]}}");
      });
    </script>
    @endforeach
@endif
@if(Session::get('success-arr'))
    @foreach(Session::get('success-arr') as $key => $success)
    <script>
      $(function() {
        toastr.success("{{$success}}");
      });
    </script>
    @endforeach
@endif
</body>
</html>
