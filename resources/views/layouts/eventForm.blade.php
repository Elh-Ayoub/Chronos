<div class="form-group">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" class="form-control" maxlength="100" value="{{($event) ? ($event->title) : ('')}}">
</div>
<div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" name="description" class="form-control" maxlength="300">{{($event) ? ($event->description) : ('')}}</textarea>
</div>
<div class="form-group d-flex justify-content-around">
    <div class="form-group d-flex">
        <label for="start" class="col-sm-4">Start date</label>
        <input type="datetime-local" id="start" name="start" class="form-control" value="{{($event) ? (date('Y-m-d\TH:i:s', strtotime($event->start))) : ('')}}">
    </div>
    <div class="form-group d-flex">
        <label for="end" class="col-sm-4">End date</label>
    <input type="datetime-local" id="end" name="end" class="form-control" value="{{($event && $event->end) ? (date('Y-m-d\TH:i:s', strtotime($event->end))) : ('')}}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-form-label">Background color</label>
    <div class="w-100 row justify-content-around flex-row">
        <div>
            <input class="color-select invisible" type="radio" name="backgroundColor" id="blue" autocomplete="off" value="#0073b7" {{($event) ? (($event->backgroundColor === '#0073b7') ? "checked" : "" ) : ('')}}>
            <label for="blue" class="btn btn-default text-center">
                Blue
                <br>
                <i class="fas fa-circle fa-2x text-primary"></i>
            </label>
        </div>
        <div>
            <input class="color-select invisible" type="radio" name="backgroundColor" id="yellow" autocomplete="off" value="#f39c12" {{($event) ? (($event->backgroundColor === '#f39c12') ? "checked" : "" ) : ('')}}>
            <label for="yellow" class="btn btn-default text-center">
            Yellow
                <br>
                <i class="fas fa-circle fa-2x text-warning"></i>
            </label>
        </div>
        <div>
            <input class="color-select invisible" type="radio" name="backgroundColor" id="green" autocomplete="off" value="#00a65a" {{($event) ? (($event->backgroundColor === '#00a65a') ? "checked" : "" ) : ('')}}>
            <label for="green" class="btn btn-default text-center">
            Green
                <br>
                <i class="fas fa-circle fa-2x text-success"></i>
            </label>
        </div>
        <div>
            <input class="color-select invisible" type="radio" name="backgroundColor" id="red" autocomplete="off" value="#f56954" {{($event) ? (($event->backgroundColor === '#f56954') ? "checked" : "" ) : ('')}}>
            <label for="red" class="btn btn-default text-center">
            Red
                <br>
                <i class="fas fa-circle fa-2x text-danger"></i>
            </label>
        </div>
        <div>
            <input class="color-select invisible" type="radio" name="backgroundColor" id="aqua" autocomplete="off" value="#00c0ef" {{($event) ? (($event->backgroundColor === '#00c0ef') ? "checked" : "" ) : ('')}}>
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
            <input class="color-select invisible" type="radio" name="borderColor" id="blue-border" autocomplete="off" value="#0073b7" {{($event) ? (($event->borderColor === '#0073b7') ? "checked" : "" ) : ('')}}>
            <label for="blue-border" class="btn btn-default text-center">
                Blue
                <br>
                <i class="fas fa-circle fa-2x text-primary"></i>
            </label>
        </div>
        <div>
            <input class="color-select invisible" type="radio" name="borderColor" id="yellow-border" autocomplete="off" value="#f39c12" {{($event) ? (($event->borderColor === '#f39c12') ? "checked" : "" ) : ('')}}>
            <label for="yellow-border" class="btn btn-default text-center">
            Yellow
                <br>
                <i class="fas fa-circle fa-2x text-warning"></i>
            </label>
        </div>
        <div>
            <input class="color-select invisible" type="radio" name="borderColor" id="green-border" autocomplete="off" value="#00a65a" {{($event) ? (($event->borderColor === '#00a65a') ? "checked" : "" ) : ('')}}>
            <label for="green-border" class="btn btn-default text-center">
            Green
                <br>
                <i class="fas fa-circle fa-2x text-success"></i>
            </label>
        </div>
        <div>
            <input class="color-select invisible" type="radio" name="borderColor" id="red-border" autocomplete="off" value="#f56954" {{($event) ? (($event->borderColor === '#f56954') ? "checked" : "" ) : ('')}}>
            <label for="red-border" class="btn btn-default text-center">
            Red
                <br>
                <i class="fas fa-circle fa-2x text-danger"></i>
            </label>
        </div>
        <div>
            <input class="color-select invisible" type="radio" name="borderColor" id="aqua-border" autocomplete="off" value="#00c0ef" {{($event) ? (($event->borderColor === '#00c0ef') ? "checked" : "" ) : ('')}}>
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
            <option selected>{{($event) ? ($event->allDay ? ($event->allDay) : ('false')) : ('Select one')}}</option>
            <option>true</option>
            <option></span>false</option>
        </select>
    </div>
    <div class="form-group d-flex align-items-center col-6 mt-4">
        <label for="inputcategory" class="mr-2">Category</label>
        <select id="inputcategory" name="category" class="form-control custom-select">
            <option selected>{{($event) ? ($event->category) : ('Select one')}}</option>
            <option>Arrangement</option>
            <option>Reminder</option>
            <option>Task</option>
        </select>
    </div>
</div>