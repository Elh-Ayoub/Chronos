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
    var timezone = $('#calendar').data('timezone')
    $("#showHolidays").change(function() {
      if(this.checked) {
          $('input[name=ShowCategories]').prop('checked', false);
          var countryCode = getCountryCode($('#countryCode').html())
          holidays = {googleCalendarId: countryCode}
          renderCalendar(holidays)
      }
      else{
        $('input[name=ShowCategories]').prop('checked', true);
        var events = $('#calendar').data('events');
        events.map(event => {
          event.start = new Date(Date.parse(event.start))
          if(event.end){
            event.end = new Date(Date.parse(event.end))
          }
        })
        events = limitCategories(events);
        renderCalendar(events);
      }
    })
    $("input[name=ShowCategories]").change(function(){
      $('#showHolidays').prop('checked', false);
      var events = $('#calendar').data('events');
        events.map(event => {
          event.start = new Date(Date.parse(event.start))
          if(event.end){
            event.end = new Date(Date.parse(event.end))
          }
        })
        events = limitCategories(events);
        renderCalendar(events);
    })
    function Calculate_remainig_time(){
      $('.time-remaining').each(function(i, obj){
        var endDate = new Date($(obj).data('end'))
        var allday = $(obj).data('allday')
        if(allday !== true){
          var today = new Date().toLocaleString({ timeZone: timezone }).replace(',', '')
          var diff2dates = endDate - new Date(today);
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
    }
    Calculate_remainig_time()
    renderCalendar(events);


function renderEvent(events = null){
  if(!events){
    var events = $('#calendar').data('events');
  }
  $('.todo-list').empty()
  events.map(event => {
    event.start = new Date(Date.parse(event.start))
    if(event.end){
      event.end = new Date(Date.parse(event.end))
    }
    var date = new Date()
    if(moment(event.start).format('YYYY-MM-DD') == moment(date).format('YYYY-MM-DD') || (event.start <= date && event.end >= date)){
      $('.todo-list').append('<li> <div class="icheck-primary d-inline ml-2"><input type="checkbox" id="' + event.title + '">' +
      '<label for="' + event.title + '"></label>'
      +'</div> <span class="text">' + event.title + '</span>'
      +'<small class="badge text-white" style="background: ' + event.backgroundColor + '; border: 2px solid ' + event.borderColor + ';"><i class="far fa-clock"></i>'
      +'<span class="time-remaining" data-end="' + event.end + '" data-allday="' + event.allDay + '"></span>'
      +'</small> </li>')
    }
  });
  events = limitCategories(events);
  Calculate_remainig_time()
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

    editable  : true,
    droppable : false,
    weekNumbers: true,
    weekNumberCalculation: 'ISO',
    nowIndicator: true,
    dateClick: function(info) {
      $('#create-event-modal').modal('show');
      var date = new Date(Date.parse(info.dateStr))
    },
    eventClick: function(info){
      $('#event-details-' + info.event.id + ' .modal-body #event-start-at').text(new Date(info.event.start).toLocaleString())
      if(!info.event.allDay){
        $('#event-details-' + info.event.id + ' .modal-body #event-end-at').text(new Date(info.event.end).toLocaleString())
      }
      $('#event-details-' + info.event.id).modal('show');
      console.log(info.event);
    },
    eventDrop: function(info){
      update_event(info.event)
    },
    eventResize: function(info){
      update_event(info.event)
    }
  });
  calendar.render();
}

$('#event-search').keyup(function(){
  var value = this.value.toUpperCase()
  var events = $('#calendar').data('events');
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

function limitCategories(events){
  let categories = ['Arrangement', 'Reminder', 'Task'];
  let newEvents = new Array()
  events.map(event => {
    if(event.category == 'Arrangement' && $('#showArrangements').is(":checked")){
      newEvents.push(event)
    }
    if(event.category == 'Reminder' && $('#showReminders').is(":checked")){
      newEvents.push(event)
    }
    if(event.category == 'Task' && $('#showTasks').is(":checked")){
      newEvents.push(event)
    }
  })
  return newEvents;
}

function update_event(event){
  var getUrl = window.location;
  var baseUrl = getUrl .protocol + "//" + getUrl.host;
  var url = baseUrl + "/api/events/"+event.id
  $.ajax({
    method: "PATCH",
    url: url,
    data: { 
      title: event.title,
      start: new Date(event.start).toLocaleString().replace(',', ''),
      end: (event.end) ? (new Date(event.end).toLocaleString().replace(',', '')) : (null),
      backgroundColor: event.backgroundColor,
      borderColor: event.borderColor,
      allDay: event.allDay,
      name: null,
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }).done(function(msg){
    fetsh4events()
    // location.reload();
  });
}

function fetsh4events(){
  cal_id = $('#calendar').data('calid')
  var n_events
  var getUrl = window.location;
  var baseUrl = getUrl .protocol + "//" + getUrl.host;
  var url = baseUrl + "/api/calendar/"+cal_id+"/events"
  $.ajax({
    method: "GET",
    url: url,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }).done(function(events){
    n_events = events
    renderEvent(n_events)
  });
  return n_events
}
})

function showEvent(id){
  $('#event-details-' + id ).modal('show')
}