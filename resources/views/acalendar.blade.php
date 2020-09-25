@extends('admin_template') 
@section('content')

<div class="row">
          @if(session()->has('error'))
            <div class="alert alert-danger taskAlert">
                {{ session()->get('error') }}
            </div>
          @elseif(session()->has('message'))
            <div class="alert alert-success taskAlert">
                {{ session()->get('message') }}
            </div>
          @endif
          @push('scripts')
          <script>
            $('div.taskAlert').fadeOut(3000);
            </script>
          @endpush

    <div class="col-md-4">
        <div class="box box-warning">
                <div class="box-header with-border"></div>
            {{-- <div class="box-body"> --}}
            {{-- {{print_r($calendar)}}  --}}
            {{-- {{print_r($months)}}  --}}
            {{-- {{print_r($calendar['april'][0]->event)}} --}}
        {{-- @foreach($months as $month)
        <h3>{{ucwords($month->month)}}</h3>
        <ul>
            @foreach($calendar as $eventmonth)
              @foreach($eventmonth as $key => $value) 
                
                @if($month->month == $value->month)
              <li>
                  {{$value->date}} - {{$value->event}} --}}
            {{-- {{$calendar->month}} --}}
            {{-- {{date('F', strtotime($event->date))}} - {{$event->date}} - {{$event->event}} --}}
                {{-- </li>
                @endif
            @endforeach
          @endforeach
        </ul>
        @endforeach --}}


        <div class="box-body">
            <p>Here you can Add, Delete or Change the specific Event that will be posted. </p>
            <button id="hideshow" class=" btn btn-sm btn-primary center-block" style="margin-bottom: 5px;">Add Event</button>
            <form method="post" action="" autocomplete="off">
                <div class="wrapper2">    
                    <ul class="ap" style="list-style-type: none;">

                    </ul>        
                </div>
            </form>

            <div class="myContainer">
                <!--toggle script-->
                @push('scripts')
                <script>
                  $(document).ready(function(){
                    $("#hideshow").on("click",function(){
                      $("#myForm").toggle(350);
                    });
                  });

                    $(document).on("click",'.ed',function(){ 
    $(this).parents('li.dat').next().toggle(350);
  }) 
  $(document).on("click",'.del',function(){ 
    $(this).parents('li.dat').nextAll().eq(1).toggle(350);
  }) 

  $(document).on("click",'.cn',function(){ 
    console.log('123');
    event.preventDefault();
    $(this).parents('li.ef').toggle(350);
  }) 

//loading animation//
  $(document).ready(function(){
    
    $(document).on('click','.sv', function(){
      // event.preventDefault();
      $('.overlay').fadeIn(350);
        setTimeout(function(){
          $('.overlay').fadeOut(350);
          // $(this).parents('li.ef')0.toggle(350);         
          console.log($(this));        
        },1000);        
        $(this).parents('li.ef').toggle(350); 
  });

$("#datepicker").datepicker({
    autoclose: true,
     format: "yyyy-mm-dd",
    //  onSelect: function (dateText) {
    //     $('#endDate').val('test');
    //     }
   });
  
$(document).on('change','#datepicker',function(){
  $('#endDate').val($('#datepicker').val());
});
//endDate auto choose same date
$('.open-datetimepicker').click(function(event){
    event.preventDefault();
    $('#datepicker').click();
});

});



                </script>
                @endpush
                <div class="box box-primary" id="myForm">
                    <style>
                        #myForm{
                          display: none;
                          margin: 20px;
                          width: auto;
                        }
                    </style>              
                    <div class="box-header with-border">
                      <h3 class="box-title">New Event</h3>
                    </div>
                    <form role="form" method="post" enctype="multipart/form-data" action="/acalendar/post" id="" autocomplete="off">
                      @csrf
                        <div class="box-body">
                            <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">Event</label>
                                <input class="col-md-8" type="text" name="event" class="form-control" id="exampleInputTitle" placeholder="Enter Title" required>
                            </div>
                           <div class="form-group col-md-12">
                                <label class="col-md-4"  for="exampleInputSubject" data-provide="datepicker">start Date</label>
                                <input class="col-md-8" id="datepicker"  type="text" name="date" class="form-control" required>
                            </div> 
                            <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">end Date</label>
                                <input class="col-md-8" type="text"  id="endDate" name="enddate" class="form-control" required>
                            </div> 
                            <div >
                                <h4 class="col-md-4" for="exampleInputSubject" style="margin-top: 0px; margin-bottom: 15px;">Time</h4>
                            </div>
                           
                            {{-- <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">From</label>
                                <input class="col-md-8" type="time" class="form-control" label="From">
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">To</label>
                                <input class="col-md-8" type="time" class="form-control" label="until">
                            </div> --}}
                            <div class="form-group col-md-12">
                                <select class="form-control select2" name="type">
                                    <option value = "activity">Activity</option>
                                    <option value = "noclass">No Classes</option>
                                    <option value = "extra">Extracurricular</option>
                                    <option value = "academic">Academic</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group col-md-12 clear-fix">
                                <button type="submit" class="btn btn-primary btn-sm center-block">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!--End of Upload Form-->

            <!--Sample event log form-->
            @foreach($months as $month)
                <div class="post">
                    <div class="box-header with-border">
                    <h4>{{UCWORDS($month->month)}}</h4>                           
                    </div><!--boxheader-->
                    <div class="box-body no-padding">
                      <ul class="en no-padding" style="list-style: none;">
                       
                          @foreach($calendar as $eventmonth)
                          @foreach($eventmonth as $key => $value) 
                            
                            @if($month->month == $value->month)
                        <li class="dat"><!--1 data-->
                          <div class="col-md-12">
                              
                              <div class="col-md-2 no-padding">
                                  @if($value->date != $value->enddate)
                              <p >{{date('M d', strtotime($value->date))}} - {{date('M d', strtotime($value->enddate))}}</p>
                                  @else
                                <p>{{date('M d', strtotime($value->date))}}</p>
                                @endif
                                </div>
                              <div class="col-md-8 no-padding text-center">
                              <p><strong>{{$value->event}}</strong></p>
                              </div>
                            {{-- <div class="col-md-3 no-padding">
                              <p>8am to 5pm</p>
                            </div> --}}
                            <div class="col-md-2 no-padding">
                              {{-- <button class=" ed btn btn-primary btn-xs">EDIT</button> --}}
                              <a class="label ed bg-green"><i class="fa fa-pencil"></i></a>
                              <a class="label del bg-red" data-toggle="modal" data-target="#confirm-delete{{$value->id}}"><i class="fa fa-trash"></i></a>
                            </div>   
                          </div>
                        </li><!--./1data-->

                        <!--edit form 1-->
                        <li class="ef" style="display: none;">
                          <form role="form" method="post" enctype="multipart/form-data" action="/acalendar/e" id="">
                            @csrf
                          <input type="hidden" name="cid" value="{{$value->id}}">
                          <div class="box-body">
                            <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">Title</label>
                            <input class="col-md-8" type="text" name="event" class="form-control" id="exampleInputTitle" placeholder="Enter Title" value = "{{$value->event}}">
                                {{-- <input class="col-md-8 " type="text" value="filipino week" class="form-control" id="exampleInputTitle" placeholder="Enter Title"> --}}
                            </div>
                           <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">start Date</label>
                                <input class="col-md-8 " type="text"  name="date" value="{{$value->date}}" class="form-control">
                            </div> 
                            <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">end Date</label>
                            <input class="col-md-8 " type="text" name="enddate" value="{{$value->enddate}}" class="form-control">
                            </div> 
                            {{-- <div >
                                <h4 class="col-md-4" for="exampleInputSubject" style="margin-top: 0px; margin-bottom: 15px;">Time</h4>
                            </div>
                           
                            <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">From</label>
                                <input class="col-md-8 " type="time" value="08:00" class="form-control" label="From">
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">To</label>
                                <input class="col-md-8 " type="time" value="17:00" class="form-control" label="until">
                            </div> --}}
                            <div class="form-group col-md-12">
                                <select class="form-control select2 input-sm" name="type">
                                    <option value="activity">Activity</option>
                                    <option value="noclass">No Classes</option>
                                    <option value="extra">Extracuricular</option>
                                    <option value="academic" >Academic</option>
                                <option value="{{$value->eventtype}}" selected >{{$value->eventtype}}</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                              <div class="col-md-2 pull-left">
                                <button  type="submit" class="sv btn btn-primary btn-xs">Save</button>
                              </div>
                              <div class="col-md-2 pull-right">
                                <button type="button" class="cn btn btn-primary btn-xs">Cancel</button>
                              </div>
                            </div>
                          </div><!--body-->
                        </form>
                        </li>
                        <li class="del" style="display:none;">

                          {{-- START modal for delete replyslip start --}}
<div class="modal fade" id="confirm-delete{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm Delete Calendar Event {{$value->id}} 
            </div>
            <div class="modal-body">
                <strong>Are you sure you want to delete this Option?</strong> <p> {!! htmlspecialchars_decode(substr($value->event, 0, 360)) !!}...</p>
              
            </div>
            <div class="modal-footer">
                
                
                <form role="form" method="post" action="/acalendar/x" enctype="multipart/form-data" id="specific{{$value->id}}" name="delRsForm">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="delRsActualCancel">Cancel</button>
                    {!! csrf_field() !!}
                    <input type="hidden" name="cid" value="{{$value->id}}" />

                    <button type="submit" class="btn btn-danger btn-ok" id="delRsActual"  >Delete</button>
                </form>
             

            </div>
        </div>
    </div>
</div>
{{--END modal for delete replyslip --}}
                        </li>
                            @endif
                          @endforeach 
                        @endforeach
                          
                        {{-- <!--./edit form 1-->

                        <li class="dat"><!--2 data-->
                          <div class="col-md-12">
                              <div class="col-md-2 no-padding">
                                  <p>13-2018</p>
                                </div>
                              <div class="col-md-5 no-padding text-center">
                                <p><strong>Sports Fest</strong></p>
                              </div>
                            <div class="col-md-3 no-padding">
                              <p>8am to 5pm</p>
                            </div>
                            <div class="col-md-2 no-padding">
                              <button class=" ed btn btn-primary btn-xs">EDIT</button>
                            </div>   
                          </div>
                        </li><!--./1data-->

                        <!--edit form 2-->
                        <li class="ef" style="display: none;">
                          <form>
                            <div class="box-body">
                              <div class="form-group col-md-12">
                                  <label class="col-md-4" for="exampleInputSubject">Title</label>
                                  <input class="col-md-8 " type="text" value="filipino week" class="form-control" id="exampleInputTitle" placeholder="Enter Title">
                              </div>
                            <div class="form-group col-md-12">
                                  <label class="col-md-4" for="exampleInputSubject">Date</label>
                                  <input class="col-md-8 " type="date" value="2018-08-09" class="form-control">
                              </div> 
                              <div >
                                  <h4 class="col-md-4" for="exampleInputSubject" style="margin-top: 0px; margin-bottom: 15px;">Time</h4>
                              </div>
                            
                              <div class="form-group col-md-12">
                                  <label class="col-md-4" for="exampleInputSubject">From</label>
                                  <input class="col-md-8 " type="time" value="08:00" class="form-control" label="From">
                              </div>
                              <div class="form-group col-md-12">
                                  <label class="col-md-4" for="exampleInputSubject">To</label>
                                  <input class="col-md-8 " type="time" value="17:00" class="form-control" label="until">
                              </div>
                              <div class="form-group col-md-12">
                                  <select class="form-control select2 input-sm">
                                      <option >Activity</option>
                                      <option>No Classes</option>
                                      <option>Extracuricular</option>
                                      <option selected >Academic</option>
                                  </select>
                              </div>
                              <div class="col-md-12">
                                <div class="col-md-2 pull-left">
                                  <button class=" sv btn btn-primary btn-xs">Save</button>
                                </div>
                                <div class="col-md-2 pull-right">
                                  <button class=" cn btn btn-primary btn-xs">Cancel</button>
                                </div>
                                </div>
                            </div>
                          </form>
                          </li>
                          <!--./edit form 2-->

                        <li class="dat"><!--3 data-->
                          <div class="col-md-12">
                              <div class="col-md-2 no-padding">
                                  <p>18-2018</p>
                                </div>
                              <div class="col-md-5 no-padding text-center">
                                <p><strong>lingo ng wika </strong></p>
                              </div>
                            <div class="col-md-3 no-padding">
                              <p>8am to 5pm</p>
                            </div>
                            <div class="col-md-2 no-padding">
                              <button class=" ed btn btn-primary btn-xs">EDIT</button>
                            </div>   
                          </div>
                        </li><!--./1data-->

                        <!--edit form 3 -->
                        <li class="ef" style="display: none;">
                          <form>
                          <div class="box-body">
                            <div class="form-group col-md-12">
                                <label class="col-md-4" for="exampleInputSubject">Title</label>
                                <input class="col-md-8 " type="text" value="filipino week" class="form-control" id="exampleInputTitle" placeholder="Enter Title">
                            </div>
                          <div class="form-group col-md-12">
                              <label class="col-md-4" for="exampleInputSubject">Date</label>
                              <input class="col-md-8 " type="date" value="2018-08-09" class="form-control">
                          </div> 
                          <div >
                              <h4 class="col-md-4" for="exampleInputSubject" style="margin-top: 0px; margin-bottom: 15px;">Time</h4>
                          </div>
                            
                          <div class="form-group col-md-12">
                            <label class="col-md-4" for="exampleInputSubject">From</label>
                            <input class="col-md-8 " type="time" value="08:00" class="form-control" label="From">
                          </div>
                          <div class="form-group col-md-12">
                          <label class="col-md-4" for="exampleInputSubject">To</label>
                            <input class="col-md-8 " type="time" value="17:00" class="form-control" label="until">
                          </div>
                          <div class="form-group col-md-12">
                            <select class="form-control select2 input-sm">
                              <option >Activity</option>
                              <option>No Classes</option>
                              <option>Extracuricular</option>
                              <option selected >Academic</option>
                            </select>
                            </div>
                              <div class="col-md-12">
                                <div class="col-md-2 pull-left">
                                  <button class=" sv btn btn-primary btn-xs">Save</button>
                                </div>
                                <div class="col-md-2 pull-right">
                                  <button class=" cn btn btn-primary btn-xs">Cancel</button>
                                </div>
                              </div>
                            </div>
                          </form>
                          </li>
                          <!--./edit form 3--> --}}
                                                
                      </ul>
                    </div>
                    <div class="box-footer">
                        <!-- <button class="btn btn-primary btn-sm pull-right ed">Edit</button> -->
                    </div><!--./boxfooter-->


                </div><!--./post-->
                @endforeach
            <!--./Sample event log form-->

          <!--loading animation-->
        </div><!--./boxbody-->
        <div class="overlay" style="display:none;" id="loaderTargetAsEdit">
          <i class="fa fa-refresh fa-spin"></i>
        </div>  




    {{-- </div> --}}
    </div>
    </div>
<div class='col-md-8'>
        <div class="box box-primary">
                <div class="box-body">
                    <div class="dayClickWindow">
                    </div>
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                  
                </div><!-- /.box-body -->
              </div>
</div>


</div>


@push('scripts')
<script type="text/javascript">
$(function () {

  /* initialize the external events
   -----------------------------------------------------------------*/
  function ini_events(ele) {
    ele.each(function () {

      // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
      // it doesn't need to have a start or end
      var eventObject = {
        title: $.trim($(this).text()) // use the element's text as the event title
      };

      // store the Event Object in the DOM element so we can get to it later
      $(this).data('eventObject', eventObject);

      // make the event draggable using jQuery UI
      $(this).draggable({
        zIndex: 1070,
        revert: true, // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
      });

    });
  }
  ini_events($('#external-events div.external-event'));

  /* initialize the calendar
   -----------------------------------------------------------------*/
  //Date for the calendar events (dummy data)
  var date = new Date();
  var d = date.getDate(),
          m = date.getMonth(),
          y = date.getFullYear();
          setTimeout(function(){
                loadCalendar();
              },100)
  function loadCalendar(){
  $('#calendar').fullCalendar({
    
    dayClick: function (data, event, view) {

       $(this).show('.dayClickWindow');
       alert('clicked');
    },   
    header: {
      left: 'prev,next today',
      center: 'title',
    //   right: 'month,agendaWeek,agendaDay'
    right:''
    },
    buttonText: {
      today: 'today',
      month: 'month',
      week: 'week',
      day: 'day'
    },
    selectable: true,
  selectHelper: true,
    eventRender: function(event, element){
      element.popover({
          animation:true,
          delay: 300,
          content: ''+event.title,
          trigger: 'hover',
          color: "#7BD148"
      });
    },
    //Random default events
    events: [
      @if(isset($calendar))
      @foreach($calendar as $eventmonth)
          @foreach($eventmonth as $key => $value) 
      {
        title: "{{$value->event}}",
        start:"{{$value->date}}",
        end:"{{$value->enddate}}",
        backgroundColor: "{{$value->color}}",
        borderColor:"{{$value->color}}"
        // backgroundColor: "#f56954", //red
        // borderColor: "#f56954" //red
      },
        @endforeach
      @endforeach
      @endif
      // {
      //   // title: 'All Day Event',
      //   title: 'All Day Event superkalifragilisticexpialidociouslysuperfkouslikabouts',
      //   // start: new Date(y, m, 1),
      //   start: '2018-04-03',
      //   backgroundColor: "#f56954", //red
      //   borderColor: "#f56954" //red
      // },
      // {
      //   title: 'Long Event',
      //   start: new Date(y, m, d - 5),
      //   end: new Date(y, m, d - 2),
      //   backgroundColor: "#f39c12", //yellow
      //   borderColor: "#f39c12" //yellow
      // },
      // {
      //   title: 'Meeting',
      //   start: new Date(y, m, d, 10, 30),
      //   allDay: false,
      //   backgroundColor: "#0073b7", //Blue
      //   borderColor: "#0073b7" //Blue
      // },
      // {
      //   title: 'Lunch',
      //   start: new Date(y, m, d, 12, 0),
      //   end: new Date(y, m, d, 14, 0),
      //   allDay: false,
      //   backgroundColor: "#00c0ef", //Info (aqua)
      //   borderColor: "#00c0ef" //Info (aqua)
      // },
      // {
      //   title: 'Birthday Party',
      //   start: new Date(y, m, d + 1, 19, 0),
      //   end: new Date(y, m, d + 1, 22, 30),
      //   allDay: false,
      //   backgroundColor: "#00a65a", //Success (green)
      //   borderColor: "#00a65a" //Success (green)
      // },
      // {
      //   title: 'Click for Google',
      //   start: new Date(y, m, 28),
      //   end: new Date(y, m, 29),
      //   url: 'http://google.com/',
      //   backgroundColor: "#3c8dbc", //Primary (light-blue)
      //   borderColor: "#3c8dbc" //Primary (light-blue)
      // }
    ],
    editable: false,
    droppable: false, // this allows things to be dropped onto the calendar !!!
    drop: function (date, allDay) { // this function is called when something is dropped

      // retrieve the dropped element's stored Event Object
      var originalEventObject = $(this).data('eventObject');

      // we need to copy it, so that multiple events don't have a reference to the same object
      var copiedEventObject = $.extend({}, originalEventObject);

      // assign it the date that was reported
      copiedEventObject.start = date;
      copiedEventObject.allDay = allDay;
      copiedEventObject.backgroundColor = $(this).css("background-color");
      copiedEventObject.borderColor = $(this).css("border-color");

      // render the event on the calendar
      // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
      $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

      // is the "remove after drop" checkbox checked?
      if ($('#drop-remove').is(':checked')) {
        // if so, remove the element from the "Draggable Events" list
        $(this).remove();
      }

    }        
  });
}

  /* ADDING EVENTS */
  var currColor = "#3c8dbc"; //Red by default
  //Color chooser button
  var colorChooser = $("#color-chooser-btn");
  $("#color-chooser > li > a").click(function (e) {
    e.preventDefault();
    //Save color
    currColor = $(this).css("color");
    //Add color effect to button
    $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
  });
  $("#add-new-event").click(function (e) {
    e.preventDefault();
    //Get value and make sure it is not null
    var val = $("#new-event").val();
    if (val.length == 0) {
      return;
    }

    //Create events
    var event = $("<div />");
    event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
    event.html(val);
    $('#external-events').prepend(event);

    //Add draggable funtionality
    ini_events(event);

    //Remove event from text input
    $("#new-event").val("");
  });
});
</script>
@endpush


@endsection