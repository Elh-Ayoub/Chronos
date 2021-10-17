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