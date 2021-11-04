@foreach ($events as $event)
<div id="event-details-{{$event->id}}" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h5 class="modal-title">{{$event->title}} @if($event->user_id !== Auth::id()) (Shared event) @endif</h5>
                <a class="btn btn-info btn-sm ml-2" href="{{route('events.chatroom', $event->id)}}"><i class="fas fa-comments"></i></a>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body w-75 m-auto">
                <p class="row justify-content-between text-md"><span class="text-bold">Title :</span><span>{{$event->title}}</span></p>
                <p class="row justify-content-between text-md"><span class="text-bold">Description :</span><span class="limit_description">{{($event->description) ? ($event->description) : ("No description")}}</span></p>
                <p class="row justify-content-between text-md"><span class="text-bold">Start at :</span><span id="event-start-at">{{$event->start}}</span></p>
                <p class="row justify-content-between text-md"><span class="text-bold">End at :</span><span id="event-end-at">{{($event->end) ? ($event->end) : ("No end date specified")}}</span></p>
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