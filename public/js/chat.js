  // Enable pusher logging - don't include this in production
  $(".messages-container").animate({ scrollTop: $('.messages-container').prop("scrollHeight")}, 2000);
  Pusher.logToConsole = true;
  var pusher = new Pusher('c3e36fa068d72596a233', {
    cluster: 'eu'
  });
  var channel = pusher.subscribe('chat');
  channel.bind('messages', function(data) {
    var auth_id = $('.messages-container').data('auth');
    var auth_username = $('.messages-container').data('username');
    var notif_url = $('.messages-container').data('sound');
    const audio = new Audio(notif_url);
    audio.play();
    console.log(notif_url);
    var username = data.author.username;
    var date = Date.parse(data.message.created_at)
    var created_at = moment(date).format('YYYY-MM-DD HH:MM:SS');
    if(data.author.username === auth_username){
        username = "You"
    }
    if(auth_id == data.message.author){
        $('.messages-container').append('<div class="chat-message-right pb-4">'+
            '<div>'+
                '<img src="' + data.author.profile_photo +'" class="rounded-circle" alt="User-Image" style="border: 1px solid grey;" width="40" height="40"><br>'+
            '</div>'+
            '<div class="flex-shrink-1 rounded py-2 px-3 mr-3" style="background: rgba(174, 198, 221, 0.616)">'+
                '<div>'+
                    '<span class="font-weight-bold mb-1 mr-2 text-center">' + username +'</span>'+
                    '<span class="text-muted small mt-2">' + created_at +'</span>'+
                '</div>'+
                '<p>' + data.message.content +'</p>'+
            '</div>'+
        '</div>')
    }else{
        $('.messages-container').append('<div class="chat-message-left pb-4">'+
            '<div class="mr-2">'+
                '<img src="' + data.author.profile_photo +'" class="rounded-circle" alt="User-Image" style="border: 1px solid grey;" width="40" height="40"><br>'+
            '</div>'+
            '<div class="flex-shrink-1 rounded py-2 px-3 mr-3" style="background: rgba(128, 128, 128, 0.534)">'+
                '<div>'+
                    '<span class="font-weight-bold mb-1 mr-2 text-center">' + username +'</span>'+
                    '<span class="text-muted small mt-2">' + created_at +'</span>'+
                '</div>'+
                '<p>' + data.message.content +'</p>'+
            '</div>'+
        '</div>')
    }
  });
var content
$('.content-msg').keyup(function(){
    content = this.value
})
function submitMessageSender(){
    url = $('.form-msg').data('action')
    $.ajax({
        method: "POST",
        url: url,
        data: { content: content },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function( msg ) {
        $('.content-msg').val('')
    });
}
