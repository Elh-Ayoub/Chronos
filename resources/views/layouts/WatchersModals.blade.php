@foreach ($watchers as $user)
<div id="update-invited-role-{{$user['user']->id}}" class="modal fade">
    <div class="modal-dialog">
        <form class="modal-content" action="{{route('sharing.edit.role', [$calendar->id, $user['user']->id])}}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h4 class="modal-title">Update {{$user['user']->username}} role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <select name="role" class="form-control custom-select">
                    <option>guest</option>
                    <option>admin</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning">Save</button>
            </div>
        </form>
    </div>
</div>
<div id="remove-invited-{{$user['user']->id}}" class="modal fade">
    <div class="modal-dialog">
        <form class="modal-content bg-danger" action="{{route('sharing.delete.user', [$calendar->id, $user['user']->id])}}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h4 class="modal-title">Update {{$user['user']->username}} role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You're about to remove a user from this calendar. Are you sure ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-outline-light">Delete</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@foreach ($invited as $user)
<div id="cancel-invitation-user-{{explode('@',$user['email'])[0]}}" class="modal fade">
    <div class="modal-dialog">
        <form class="modal-content bg-danger" action="{{route('sharing.cancel.user', [$calendar->id, $user['email']])}}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h4 class="modal-title">Cancel invitation for {{$user['email']}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You're about to cancel an invitation. Are you sure ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-outline-light">Delete</button>
            </div>
        </form>
    </div>
</div>
@endforeach