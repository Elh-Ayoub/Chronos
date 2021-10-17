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
                                        <img src="{{$user['user']->profile_photo}}" class="img-sm img-circle mr-2 " alt="User-Image" style="border: 1px solid grey;">
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
                        <div id="calendar" data-events="{{json_encode($events)}}"></div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </section>
        @foreach ($events as $event)
        <div id="event-details-{{$event->id}}" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{$event->title}} @if($event->user_id !== Auth::id()) (Shared event) @endif</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body w-75 m-auto">
                       <p class="row justify-content-between text-md"><span class="text-bold">Title :</span><span>{{$event->title}}</span></p>
                       <p class="row justify-content-between text-md"><span class="text-bold">Description :</span><span>{{($event->description) ? ($event->description) : ("No description")}}</span></p>
                       <p class="row justify-content-between text-md"><span class="text-bold">Start at :</span><span>{{$event->start}}</span></p>
                       <p class="row justify-content-between text-md"><span class="text-bold">End at :</span><span>{{($event->end) ? ($event->end) : ("No end date specified")}}</span></p>
                       <p class="row justify-content-between text-md"><span class="text-bold">All day event :</span><span>{{($event->allDay) ? ($event->allDay) : ("false")}}</span></p>
                       <p class="row justify-content-between text-md"><span class="text-bold">Category :</span><span>{{$event->category}}</span></p>
                    </div>
                    @if($event->user_id === Auth::id() || $calendar->user_id === Auth::id() || (App\Models\Sharing::where(['target'=>'calendar', 'target_id'=>$calendar->id, 'shared_to_email' => Auth::user()->email, 'shared_to_role' => 'admin'])->first()))
                    <div class="modal-footer justify-content-around">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#invite-to-event-{{$event->id}}"><i class="fas fa-bullhorn pr-2"></i>Invite</button>
                        <div class="row">
                            <a href="{{route('events.edit.view', [$calendar->id, $event->id])}}" type="button" class="btn btn-warning mr-2"><i class="fas fa-pen pr-2"></i>Edit</a>
                            <form action="{{route('events.delete', $event->id)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-times pr-2"></i>Delete</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="modal-footer justify-content-around align-items-center">
                        <div class="row justify-content-lg-start">
                            <span class="text-bold mr-3">Created by:</span>
                            <img src="{{App\Models\User::find($event->user_id)->profile_photo}}" class="img-sm img-circle" alt="User-Image" style="border: 1px solid grey;">
                            <span>{{App\Models\User::find($event->user_id)->username}}</span>
                        </div>
                        @if((App\Models\Sharing::where(['target'=>'calendar', 'target_id'=>$calendar->id, 'shared_to_email' => Auth::user()->email, 'shared_to_role' => 'guest'])->first()))
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss</button> 
                        @else
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#remove-shared-event-{{$event->id}}"><i class="fas fa-times pr-2"></i>Remove shared event</button>    
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div id="invite-to-event-{{$event->id}}" class="modal fade">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{route('events.invite', $event->id)}}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Invite to {{$event->title}} event via email</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body emails-container">
                        <div class="form-group d-flex align-items-center">
                            <label class="mr-2">Email</label>
                            <input type="text" name="email[]" class="form-control" maxlength="100">
                        </div>
                    </div>
                    <div class="btn btn-outline-primary" onclick="addEmailsection()">Add</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info"><i class="fas fa-bullhorn pr-2"></i>Invite</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="remove-shared-event-{{$event->id}}" class="modal fade">
            <div class="modal-dialog">
                <form class="modal-content bg-danger" method="POST" action="{{route('events.invite.delete', $event->id)}}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body ">
                        <p>You're about to delete a shared event, if you continue you will not be able to use the same invitation to get this event again.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-light"><i class="fas fa-trash pr-2"></i>Remove</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
        <div id="create-event-modal" class="modal fade">
            @if(($calendar->user_id === Auth::id()) || App\Models\Sharing::where(['target'=>'calendar', 'target_id'=>$calendar->id, 'shared_to_email' => Auth::user()->email, 'shared_to_role' => 'admin'])->first())
            <div class="modal-dialog modal-lg">
                <form action="{{route('events.create', ['calendar_id' => $calendar->id])}}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create event</h5>
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
            @else
            <div class="modal-dialog">
                <div class="modal-content bg-danger">
                    <div class="modal-header">
                        <h5 class="modal-title">Error!</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Sorry! as a guest you can't create events.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Dismiss</button>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div id="share-calendar" class="modal fade">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{route('calendar.share', $calendar->id)}}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Share {{$calendar->name}}</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body emails-container">
                        <div class="form-group d-flex align-items-center">
                            <input type="text" name="email[]" class="form-control" maxlength="100" placeholder="Email">
                            <select name="role[]" class="form-control custom-select w-25">
                                <option>guest</option>
                                <option>admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="btn btn-outline-primary" onclick="addEmailRolesection()">Add</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info"><i class="fas fa-share pr-2"></i>share</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="edit-calendar" class="modal fade">
            <div class="modal-dialog">
                <form action="{{route('calendars.update', $calendar->id)}}" method="POST" class="modal-content">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h4 class="modal-title">Edit {{$calendar->name}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" maxlength="100" value="{{$calendar->name}}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" maxlength="200">{{$calendar->description}}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Save</button>
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
        @include('layouts.WatchersModals')
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
</body>
</html>
