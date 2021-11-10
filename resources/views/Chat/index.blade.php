<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{$event->title}} - Chat</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo_transparent.png')}}"/>
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
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
                    <h1 class="m-0">{{$chat->name}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">{{$event->title}} - Chat</a></li>
                    </ol>
                </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="d-flex align-items-start flex-wrap justify-content-start right-container">
                    <div class="col-4">
                        <div class="card" style="width: 18rem;">
                            {{-- <img class="card-img-top" src="{{$anime->image}}" alt="Card image cap"> --}}
                            <div class="card-body">
                                <h5 class="card-title text-bold text-lg text-center w-100 mb-2">{{$chat->name}}</h5>
                                <div class="w-100">
                                    <span class="text-bold text-muted mr-2">{{"Description: "}}</span><span>{{($event->description) ? ($event->description) : ('No description')}}</span>
                                </div>
                                <div class="w-100 mt-2">
                                    <span class="text-bold text-muted mr-2 ">Participants :</span>
                                    @foreach ($participants as $participant)
                                    <div class="row justify-content-lg-start align-items-center mt-1 mb-1 text-primary">
                                    @if($user = App\Models\User::where('email', $participant)->first())
                                        <img src="{{$user->profile_photo}}" class="img-sm img-circle mr-2 " alt="User-Image" style="border: 1px solid grey;">
                                        <span>{{($user->username === Auth::user()->username) ? ("You") : ($user->username)}}</span>
                                    @else
                                        <span>{{$participant}}</span>
                                    @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                          <div class="card-header p-2">
                            <h3>{{$event->title}}</h3>
                          </div>
                        </div>
                        <div class="card col-12 col-xl-12">
                            <div class="card-body position-relative messages-container" data-auth="{{Auth::id()}}" data-username="{{Auth::user()->username}}"  data-sound="{{asset('notification/notification.mp3')}}" style="max-height: 800px; overflow-y: scroll;">
                                @foreach ($messages as $message)
                                <div class="chat-messages p-1">
                                    @if(App\Models\User::find($message->author)->username === Auth::user()->username)
                                    <div class="chat-message-right pb-4">
                                        <div>
                                            <img src="{{App\Models\User::find($message->author)->profile_photo}}" class="rounded-circle" alt="User-Image" style="border: 1px solid grey;" width="40" height="40"><br>
                                        </div>
                                        <div class="flex-shrink-1 rounded py-2 px-3 mr-3" style="background: rgba(174, 198, 221, 0.616)">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="font-weight-bold mb-1 mr-2 text-center">{{(App\Models\User::find($message->author)->username === Auth::user()->username) ? ("You") : (App\Models\User::find($message->author)->username)}}</span>
                                                    <span class="text-muted small mt-2 convert_by_timezone" data-timezone="{{Auth::user()->timezone}}" data-timezone="{{Auth::user()->timezone}}" data-offset="{{(timezone_offset_get(timezone_open(Auth::user()->timezone), date_create($message->created_at, timezone_open("UTC"))))}}">{{$message->created_at}}</span>   
                                                </div>
                                                <a class="link-muted dropdown-toggle p-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item edit-msg-btn @if (filter_var($message->content, FILTER_VALIDATE_URL)) d-none @endif" id="edit-{{$message->id}}" onClick="edit_msg_btn({{$message->id}})" data-content="{{$message->content}}" data-action="{{route('update.message', $message->id)}}" href="#input_msg">Edit</a>
                                                    <a class="dropdown-item" id="delete-{{$message->id}}" onclick="delete_msg_btn({{$message->id}})" data-action="{{route('delete.message', $message->id)}}" href="#">Delete</a>
                                                </div>
                                            </div>
                                            @if (filter_var($message->content, FILTER_VALIDATE_URL))
                                              <p class="message-content"><a href="{{$message->content}}" target="_blank">{{($arr = explode('/', parse_url($message->content, PHP_URL_PATH)))  ? ($arr[count($arr) -1]) : ("File")}}</a></p> 
                                            @else
                                                <p class="message-content">{{$message->content}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @else
                                    <div class="chat-message-left pb-4">
                                        <div class="mr-2">
                                            <img src="{{App\Models\User::find($message->author)->profile_photo}}" class="rounded-circle" alt="User-Image" style="border: 1px solid grey;" width="40" height="40"><br>
                                        </div>
                                        <div class="flex-shrink-1 rounded py-2 px-3 mr-3" style="background: rgba(128, 128, 128, 0.534)">
                                            <div>
                                                <span class="font-weight-bold mb-1 mr-2 text-center">{{(App\Models\User::find($message->author)->username === Auth::user()->username) ? ("You") : (App\Models\User::find($message->author)->username)}}</span>
                                                <span class="text-muted small mt-2 convert_by_timezone" data-timezone="{{Auth::user()->timezone}}" data-offset="{{(timezone_offset_get(timezone_open(Auth::user()->timezone), date_create($message->created_at, timezone_open("UTC"))))}}">{{$message->created_at}}</span>
                                            </div>
                                            @if (filter_var($message->content, FILTER_VALIDATE_URL))
                                              <p class="message-content"><a href="{{$message->content}}">File</a></p> 
                                            @else
                                                <p class="message-content">{{$message->content}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            <form id="sendMessageForm" data-action="{{route('send.message', $chat->id)}}" class="form-msg d-flex align-items-center justify-content-center input-group" style="border: 1px solid grey">
                                @csrf
                                <div class="input-group-prepend">
                                    <label for="attach-file" class="ml-2 pr-1 mt-2"><i class="fas fa-paperclip attach-file-icon"></i></label>
                                    <input type="file" id="attach-file" name="attachedFile[]" class="d-none" multiple>
                                </div>
                                <input class="form-control content-msg" id="input_msg" type="text" name="content" placeholder="Type a message ..." style="border: none">
                                <button class="btn btn-secondary btn-send"><i class="fas fa-paper-plane"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
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
<script src="{{asset('dist/js/demo.js')}}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="{{asset('js/chat.js')}}"></script>
<script>
    // $('.convert_by_timezone').each(function(i, obj){
    //     var timezone = $(obj).data('timezone')
    //     var date = new Date(($(obj).html()))
    //     //console.log(date);
    //     var offset = $(obj).data('offset') / 3600
    //     //console.log(offset);
    //     //$(obj).html(date.getMonth() + "-" + date.getDay() + "-" + date.getFullYear() + " " + (date.getHours() + offset) + ":" + date.getMinutes()+ ":" + date.getSeconds()) 
    // })
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
