$(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (https://fullcalendar.io/docs/event-object)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    $.ajax('http://ip-api.com/json')
    .then(
      function success(response) {
          $('#label4showHolidays').html("Show holidays in <span id='countryCode'>" + response.country + "</span>")
      },
      function fail(data, status) {
          console.log('Request failed.  Returned status of',status);
      }
    );
    var events = renderEvent();
    $("#showHolidays").change(function() {
      if(this.checked) {
          var countryCode = getCountryCode($('#countryCode').html())
          holidays = {googleCalendarId: countryCode}
          renderCalendar(holidays)
      }
      else{
        var events = $('#calendar').data('events');
        events.map(event => {
          event.start = new Date(Date.parse(event.start))
          if(event.end){
            event.end = new Date(Date.parse(event.end))
          }
        })
        renderCalendar(events);
      }
    })
    $('.time-remaining').each(function(i, obj){
      var endDate = new Date($(obj).data('end'))
      var allday = $(obj).data('allday')
      console.log(allday)
      if(allday !== true){
        var today = new Date()
        var diff2dates = endDate - new Date(today.toString());
        var diffStr
        if(diff2dates > 0){
          diffStr = 'remaining'
        }else{
          diffStr = 'ago'
        }
        let diffInMilliSeconds = Math.abs(diff2dates) / 1000;
        // calculate days
        const days = Math.floor(diffInMilliSeconds / 86400);
        diffInMilliSeconds -= days * 86400;
        // calculate hours
        const hours = Math.floor(diffInMilliSeconds / 3600) % 24;
        diffInMilliSeconds -= hours * 3600;
        // calculate minutes
        const minutes = Math.floor(diffInMilliSeconds / 60) % 60;
        diffInMilliSeconds -= minutes * 60;
        //
        var finalStr = ((days !== 0) ? (days + "d ") : ('')) + ((hours !== 0) ? (hours + "h ") : ('')) + ((minutes !== 0) ? (minutes + "min") : (''))
        $(obj).html(finalStr + " " + diffStr);
      }else{
        $(obj).html("All day event");
      }
    })
    renderCalendar(events);
    // $('#calendar').fullCalendar()
    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    // Color chooser button
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      // Save color
      currColor = $(this).css('color')
      // Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      // Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      // Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.text(val)
      $('#external-events').prepend(event)

      // Add draggable funtionality
      ini_events(event)

      // Remove event from text input
      $('#new-event').val('')
    })
})

function renderEvent(){
  var events = $('#calendar').data('events');
  events.map(event => {
    event.start = new Date(Date.parse(event.start))
    if(event.end){
      event.end = new Date(Date.parse(event.end))
    }
    var date = new Date()
    if(event.start.getDay() == date.getDay() || (event.start <= date && event.end >= date)){
      $('.todo-list').append('<li> <div class="icheck-primary d-inline ml-2"><input type="checkbox" id="' + event.title + '">' +
      '<label for="' + event.title + '"></label>'
      +'</div> <span class="text">' + event.title + '</span>'
      +'<small class="badge text-white" style="background: ' + event.backgroundColor + '; border: 2px solid ' + event.borderColor + ';"><i class="far fa-clock"></i>'
      +'<span class="time-remaining" data-end="' + event.end + '" data-allday="' + event.allDay + '"></span>'
      +'</small> </li>')
    }
  });
  return events;
}
function renderCalendar(events){
  var Calendar = FullCalendar.Calendar;
  var Draggable = FullCalendar.Draggable;
  var calendarEl = document.getElementById('calendar');
  var calendar = new Calendar(calendarEl, {
    headerToolbar: {
      left  : 'prevYear prev,next today',
      center: 'title',
      right : 'dayGridMonth,timeGridWeek,timeGridDay nextYear'
    },
    googleCalendarApiKey: "AIzaSyCvmQT3WE0c7490FH6hYTIn1kWOw8U0vgk",
    themeSystem: 'bootstrap',
    events: events,

    editable  : false,
    droppable : false,
    weekNumbers: true,
    weekNumberCalculation: 'ISO',
    nowIndicator: true,
    dateClick: function(info) {
      $('#create-event-modal').modal('show');
      var date = new Date(Date.parse(info.dateStr))
    },
    eventClick: function(info){
      // alert('Event: ' + info.event.title + "desc: " + info.event.id);
      $('#event-details-' + info.event.id).modal('show');
    },
  });
  calendar.render();
}

$('#event-search').keyup(function(){
  var value = this.value.toUpperCase()
  let events = renderEvent();
  if(value.length > 0){
  $('#search-results').empty() 
  events.map(event =>{
    if(event.title.toUpperCase().indexOf(value) > -1){
      $('#search-results').append('<li class="list-group-item dropdown-item" onClick="showEvent(' + event.id +')">' + event.title +'</li>')
    }
  });
  }else{
    $('#search-results').empty() 
  }
})
function showEvent(id){
  $('#event-details-' + id ).modal('show')
}
