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
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;

    //var containerEl = document.getElementById('external-events');
    //var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');
    var calendarEl2 = document.getElementById('calendar-2');
    var events = $('#calendar').data('events');
    // events.foreach((event)=>{
      
    // })
    events.map(event => {
      event.start = new Date(Date.parse(event.start))
      if(event.end){
        event.end = new Date(Date.parse(event.end))
      }
   });
    //console.log(events)
    // events = JSON.parse(events);
    // initialize the external events
    // -----------------------------------------------------------------

    // new Draggable(containerEl, {
    //   itemSelector: '.external-event',
    //   eventData: function(eventEl) {
    //     return {
    //       title: eventEl.innerText,
    //       backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
    //       borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
    //       textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
    //     };
    //   }
    // });

    var calendar = new Calendar(calendarEl, {
      headerToolbar: {
        left  : 'prevYear prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay nextYear'
      },
      themeSystem: 'bootstrap',
      //Random default events

      events: 
        events
        
      ,
      editable  : false,
      droppable : false, // this allows things to be dropped onto the calendar !!!
      weekNumbers: true,
      weekNumberCalculation: 'ISO',
      nowIndicator: true,
      dateClick: function(info) {
        $('#create-event-modal').modal('show');
        var date = new Date(Date.parse(info.dateStr))
      //   console.log(curdate);
      //  $('#start').val(info.dateStr);
      }
    });

    calendar.render();
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
  