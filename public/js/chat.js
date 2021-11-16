  // Enable pusher logging - don't include this in production
  $(".messages-container").animate({ scrollTop: $('.messages-container').prop("scrollHeight")}, 2000);
  var PUSHER_APP_KEY = 'c3e36fa068d72596a233'
  var getUrl = window.location;
  var baseUrl = getUrl .protocol + "//" + getUrl.host;
  Pusher.logToConsole = true;
  var pusher = new Pusher(PUSHER_APP_KEY, {
    cluster: 'eu'
  });
  var channel = pusher.subscribe('chat');
  channel.bind('messages', function(data) {
    var auth_id = $('.messages-container').data('auth');
    var auth_username = $('.messages-container').data('username');
    var notif_url = $('.messages-container').data('sound');
    const audio = new Audio(notif_url);
    var username = data.author.username;
    var date = new Date((data.message.created_at))
    var created_at = moment(date).format('YYYY-MM-DD HH:MM:SS');
    if(data.author.username === auth_username){
        username = "You"
    }
    var msgContent, display_none;
    if(isValidURL(data.message.content)) {
        let arr = data.message.content.split('/')
        display_none = "d-none"
        msgContent = '<a href="'+data.message.content +'" target="_blank">' + arr[arr.length - 1] +'</a>'
    }else{
        display_none = ""
        msgContent = data.message.content
    }
    if(auth_id == data.message.author){
        $('.messages-container').append('<div class="chat-message-right pb-4">'+
            '<div>'+
                '<img src="' + data.author.profile_photo +'" class="rounded-circle" alt="User-Image" style="border: 1px solid grey;" width="40" height="40"><br>'+
            '</div>'+
            '<div class="flex-shrink-1 rounded py-2 px-3 mr-3" style="background: rgba(174, 198, 221, 0.616)">'+
                '<div class="d-flex justify-content-between">' +
                    '<div>'+
                        '<span class="font-weight-bold mb-1 mr-2 text-center">' + username +'</span>'+
                        '<span class="text-muted small mt-2">' + created_at +'</span>'+
                    '</div>'+
                    '<a class="link-muted dropdown-toggle p-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>'+
                    '<div class="dropdown-menu">'+
                        '<a class="dropdown-item edit-msg-btn ' + display_none + '" id="edit-' + data.message.id +'" onClick="edit_msg_btn(' +data.message.id + ')" data-content="' + data.message.content +'" data-action="' + (baseUrl+ "/message/" + data.message.id) +'" href="#input_msg">Edit</a>'+
                        '<a class="dropdown-item" href="#" id="delete-' + data.message.id +'" onclick="delete_msg_btn(' +data.message.id + ')" data-action="' + (baseUrl+ "/message/" + data.message.id) +'">Delete</a>'+
                    '</div>'+
                '</div>'+
                '<p class="message-content">' + msgContent +'</p>'+
            '</div>'+
        '</div>')
    }else{
        audio.play();
        $('.messages-container').append('<div class="chat-message-left pb-4">'+
            '<div class="mr-2">'+
                '<img src="' + data.author.profile_photo +'" class="rounded-circle" alt="User-Image" style="border: 1px solid grey;" width="40" height="40"><br>'+
            '</div>'+
            '<div class="flex-shrink-1 rounded py-2 px-3 mr-3" style="background: rgba(128, 128, 128, 0.534)">'+
                '<div>'+
                    '<span class="font-weight-bold mb-1 mr-2 text-center">' + username +'</span>'+
                    '<span class="text-muted small mt-2">' + created_at +'</span>'+
                '</div>'+
                '<p class="message-content">' + msgContent +'</p>'+
            '</div>'+
        '</div>')
    }
  });
var content
var files = []
var url
$('.content-msg').keyup(function(){
    content = this.value
})

$('#attach-file').change(function(e){
    if(e.target.files.length > 0){
        $('.attach-file-icon').css('color', 'red')
    }
    files = e.target.files
})

$("#sendMessageForm").submit(function(e) {
    e.preventDefault();
    url = $('.form-msg').data('action')
    var formData = new FormData();
    if(content == undefined){
        content = null
    }
    if(files == undefined){
        files = null
    }
    formData.append("content", content);
    Array.from(files).forEach((file) => {
        formData.append("attachedFile[]", file);
    });
    $.ajax({
        method: "POST",
        url: url,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: false,
        processData: false,
    }).done(function( msg ) {
        $('.content-msg').val('')
        $('#attach-file').val('')
        $('.attach-file-icon').css('color', 'black')
        content = null
        file = null
    });
});

function edit_msg_btn(id){
    let edited_content = $("#edit-" + id).data('content')
    url = $("#edit-" + id).data('action')
    $('.content-msg').val(edited_content)
    content = edited_content
    $('.btn-send').html("<i class='fas fa-pen'></i>")
    $('.btn-send').removeClass('btn-secondary')
    $('.btn-send').addClass('btn-warning btn-edit')
    $('.form-msg').attr('onsubmit', 'submitMessageUpdater();return false');
}
function delete_msg_btn(id){
    url = $("#delete-" + id).data('action');
    $.ajax({
        method: "DELETE",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function( msg ) {
        location.reload();
    });
}
function submitMessageUpdater(){
    $.ajax({
        method: "PATCH",
        url: url,
        data: { content: content },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function( msg ) {
        $('.content-msg').val('')
        url = $('.form-msg').data('action')
        $('.btn-send').addClass('btn-secondary')
        $('.btn-send').removeClass('btn-warning btn-edit')
        location.reload();
    });
}

function isValidURL(str){
    let url;
    
    try {
        url = new URL(str);
    } catch (_) {
        return false;  
    }
    return url.protocol === "http:" || url.protocol === "https:";
}
var check = true
$("#edit-chat-name").on('click', function(){
    let current_val = $('#chat-name').html()
    if(check){
        $('#chat-name').html('<input class="form-control w-auto" id="input-chat-name" type="text" value="' + current_val +'">')
        $(this).html('save')
        $(this).addClass('btn btn-warning ml-2 save-changes')
        $(this).attr("onclick","saveChanges()")
        check = false
    }
    
})
function saveChanges(){
    var url = $('.save-changes').data('action')
    $.ajax({
        method: "PATCH",
        url: url,
        data: { name: $("#input-chat-name").val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function( msg ) {
        location.reload();
    });
}
