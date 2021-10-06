<style>body{font-family:'Open Sans',sans-serif; border-radius: 20px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; width: 100%; height: fit-content;}
</style>
<body>
    <div style="text-align: center; padding: 10px;">
        <h2><strong>Sharing Calendar</strong></h2>
        <p style="font-size: medium">Hello, a calendar was shared with you as {{$role}}.</p>
        <div class="modal-body" style="width: 75%; margin: auto;">
            <p style="display: flex; justify-content: space-between; font-size: medium;"><strong>Title :</strong><span>{{$calendar->name}}</span></p>
            <p style="display: flex; justify-content: space-between; font-size: medium;"><strong>Description :</strong><span>{{($calendar->description) ? ($calendar->description) : ("No description")}}</span></p>
        </div>
        <p>To add this calendar to your list:</p>
        <a href="{{route('calendar.invite.add', [$sharing_id, 'role' => $role])}}" style="padding: 10px 15px; background-color: #2d529d; color: white; text-align: center; border-radius: 15px; font-size: 18px; margin-top: 10px; text-decoration: none;">Add Calendar</a>    
    </div>
    <div style="padding: 10px;">
        <p class="text-bold">Invited by: </p>
        <p>Full name: <b>{{$user->full_name}}</b></p>
        <p>Email: <b>{{$user->email}}</b></p>
    </div>
    <div style="background-color: #2d529d; color: white; padding: 10px;">
        <p>Respectfully,<br><a href="https://github.com/Elh-Ayoub" style="color: white; text-decoration: none; cursor: pointer;">Ayoub El-Haddadi</a><br>Ucode<br></p>
    </div>
</body>