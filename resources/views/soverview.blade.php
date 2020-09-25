@extends('admin_template')

@section('content')

    <div class="row">
     {{-- <pre> {{ $user->email }} </pre>
     <pre> {{ print_r($studentuser) }} </pre>
     <pre> {{ print_r($homeworks) }} </pre>
     <pre> {{ print_r($pubdate) }} </pre>
     <pre> {{ print_r($schedule) }} </pre> --}}
     
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Assignments for today</h3>
                    <div class="box-tools pull-right">
                            
                                    
                                    <label  for="date"><input type="text" id="datepicker" class="form-control" name="date" style="position:absolute; right: 70px;width: 100px; border: 0; line-height: 10px; height: 1px;padding: 5px;">
                                       <span class="fa fa-calendar" style="cursor: pointer;"></span>
                                    </label>
                                
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>

                <style>
                        
                        .target{
                            overflow: hidden;
                            height:40px;
                            position: relative;
                            border:0px solid #999;
                            transition: .3s ease;
                            cursor: pointer;
                        
                        }
                        
                        
                        
                        .read-more{
                            position: relative;
                            right:0;
                            bottom:0;
                        }
                        .target.expanded {
                            height: auto;
                           
                        }
                        </style>
                <div class="box-body">
                    {{-- @foreach($tasks as $task)
                        <h5>
                            {{ $task['name'] }}
                            <small class="label label-{{$task['color']}} pull-right">{{$task['progress']}}%</small>
                        </h5>
                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-{{$task['color']}}" style="width: {{$task['progress']}}%"></div>
                        </div>
                    @endforeach --}}
                    <ul style="list-style: none;">
                      @foreach ($homeworks as $homework)
                        <li style="border-bottom: 1px solid #ccc;">
                        <h4 style="font-weight:bold;"><a href="{{'/subjects/'.$homework->subject}}">{{ucwords($homework->subject)}}</a></h4><span style="float:right; font-style:italic;">{{date("M d, Y", strtotime($homework->pubdate))}}</span>
                                <span style="display:block;">{{ucwords($homework->firstname)}} {{ucwords($homework->lastname)}}</span>
                                
                        <div class="target">{!! htmlspecialchars_decode($homework->description)!!}</div>
                                    
                                    <span class="read-more pull-right-container" style="display:block; width:100%;">
                                            <i class="fa  pull-right  fa-angle-up"></i>
                                          </span>
                                          {{-- <button class="read-more">read</button> --}}
                        </li>
                        @endforeach
                        {{-- <li style="border-bottom: 1px solid #ccc;">
                        <h4 style="font-weight:bold;">Science</h4><span style="float:right; font-style:italic;">{{date("M d, Y")}}</span>
                                <span style="display:block;">Tchr. Beth Jimenez</span>
                                <p class="target">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                                    <span class="read-more pull-right-container" style="display:block; width:100%;">
                                            <i class="fa  pull-right  fa-angle-up"></i>
                                          </span>
                        </li>
                        <li style="border-bottom: 1px solid #ccc;">
                                <h4 style="font-weight:bold;">Filipino</h4><span style="float:right; font-style:italic;">{{date("M d, Y")}}</span>
                                        <span style="display:block;">Tchr. Beth Jimenez</span>
                                        <p class="target">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                                            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                                            <span class="read-more pull-right-container" style="display:block; width:100%;">
                                                    <i class="fa  pull-right  fa-angle-up"></i>
                                                  </span>
                                </li>
                                <li style="border-bottom: 1px solid #ccc;">
                                        <h4 style="font-weight:bold;">Homeroom</h4><span style="float:right; font-style:italic;">{{date("M d, Y")}}</span>
                                                <span style="display:block;">Tchr. Beth Jimenez</span>
                                                <p class="target">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                                                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                                                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                                                    <span class="read-more pull-right-container" style="display:block; width:100%;">
                                                            <i class="fa  pull-right  fa-angle-up"></i>
                                                          </span>
                                        </li> --}}
                    </ul>
                    

                </div><!-- /.box-body -->
                <div class="box-footer">
                    {{-- <form action='#'>
                        <input type='text' placeholder='New task' class='form-control input-sm' />
                    </form> style="position: absolute; right: 10px;"--}}
                    @push('scripts')
                    <script>
                            $(document).ready(function() {
                                $('.target').click(function(){
                                    // $(this).prev().toggleClass('expanded');
                                    if($(this).height() >40){
                                        $(this).animate({height: 40}, 300 );
                                        $(this).css("background-color", "#eee");
                                        // console.log($(this).prev().height());
                                        // $('.fa .pull-right .fa-angle-down').removeClass('fa-angle-down');
                                        // $('.fa .pull-right').addClass('fa-angle-up');
                                        // $(this).next().children("i.fa-angle-down").addClass('fa-angle-up');
                                        $(this).next().children("i.fa-angle-down").removeClass('fa-angle-down').addClass('fa-angle-up');
                                        
                                                                                
                                        // console.log($(this).next().children(".fa-angle-down"));
                                    }
                                    else{
                                    $(this).animate({height: $(this).get(0).scrollHeight}, 300 );
                                    $(this).css("background-color", "#eee");
                                    // console.log($(this).prev().get(0).scrollHeight);
                                    // console.log($(this).prev().height());
                                    $(this).next().children("i.fa-angle-up").removeClass('fa-angle-up').addClass('fa-angle-down');
                                    }
                                    
                                   
                                    
                                });
                                // $("#datepicker").datepicker({autoclose: true});
                                // $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
                                
                                $("#datepicker").datepicker({
                                    autoclose: true,
                                    dateFormat: "yy-mm-dd",
                                         onSelect: function (dateText) {
                                            $(this).change();
                                            
                                        }
                                })
                                .change(function() {
                                    // console.log(dateText);
                                    // var targeturl = this.value.replace(/\//g,'-');
                                    var output = this.value.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$1-$2");
                                    // var targeturl = output.replace(/\//g,'-');
                                    
                                    console.log(this.value);
                                    console.log(output);
                                 window.location.href = "/overview/" + output;

                                });
                                $(".fa-calendar").click(function(){ $("#datepicker").datepicker("show"); }); 
                                
//   $('.fa-calendar').click(function() {
//     $("#datepicker").focus();
//   });
//Date picker
// $('#datepicker').datepicker({
//       autoclose: true
//     })
                            });
                            // $(document).ready(function() {
                            //     $('.read-more').click(function(){
                            //         // $(this).prev().toggleClass('expanded');
                            //         if($(this).prev().height() >40){
                            //             $(this).prev().animate({height: 40}, 300 );
                            //             console.log($(this).prev().height());
                            //         }
                            //         else{
                            //         $(this).prev().animate({height: $(this).prev().get(0).scrollHeight}, 300 );
                            //         console.log($(this).prev().get(0).scrollHeight);
                            //         console.log($(this).prev().height());
                            //         }
                            //     });
                            // });
 $('.open-datetimepicker').click(function(event){
    event.preventDefault();
    $('#datepicker').click();
});

                            </script>
                    @endpush
                @if($prev != '')
                <a href="/overview/{{$prev->pubdate}}">
                <button type="button" class="btn  btn-primary">{{$prev->pubdate}}</button>
                </a>
                @endif
                @if($next!='')
                <a href="/overview/{{$next->pubdate}}">
                    <button type="button" class="btn  btn-primary" style="float:right;" >{{$next->pubdate}}</button>
                </a>
                @endif
                </div><!-- /.box-footer-->
            </div>
        </div>
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Schedule</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body" id="schedule">
                        <table class="table table-bordered table-striped table-hover">
                                <tbody><tr>
                                  <th style="width: 80px">day</th>
                                  <th>Subject</th>
                                  <th>Schedule</th>
                                  {{-- <th style="width: 200px">Teacher</th> --}}
                                </tr>
                                @foreach($schedule as $sched)
                                <tr>
                                  <td>{{date("l")}}</td>
                                  <td>{{$sched->subject}}</td>
                                  <td>
                                    {{$sched->schedule}}
                                  </td>
                                  {{-- <td>Tchr. Beth Jimenez</td> --}}
                                </tr>
                                @endforeach
                                {{-- <tr>
                                        <td>{{date("l")}}</td>
                                        <td>Science</td>
                                        <td>
                                          8:00 am - 9:00 am
                                        </td>
                                        <td>Tchr. Beth Jimenez</td>
                                      </tr>
                                      <tr>
                                            <td>{{date("l")}}</td>
                                            <td>English</td>
                                            <td>
                                              8:00 am - 9:00 am
                                            </td>
                                            <td>Tchr. Beth Jimenez</td>
                                          </tr>
                                          <tr>
                                                <td>{{date("l")}}</td>
                                                <td>Filpino</td>
                                                <td>
                                                  8:00 am - 9:00 am
                                                </td>
                                                <td>Tchr. Beth Jimenez</td>
                                              </tr> --}}
                                
                              </tbody></table>
                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                        <button type="button" class="btn  btn-success center-block" id="showSched">Show All Schedule</button>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->

</div>
<div class="row">

 <style>
 .dayClickWindow
    {
    width:500px;
    height:500px;
    border-radius:15px;
    background-color:#000;
    position:absolute;
    left:50%;
    top:50%;
    margin-top:-250px;
    margin-left:-250px;
    display:none;
    z-index:1;
}
 </style>
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
<div class="col-md-4">
        <div class="box box-warning">
                <div class="box-header with-border">
                  <h2>Calendar of Events</h2>
                </div>
            <div class="box-body">
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

                    <!--Sample event log form-->
                    @foreach($months as $month)
                    <div class="post">
                        <div class="box-header with-border">
                        <h4>{{UCWORDS($month->month)}}</h4>                           
                        </div><!--boxheader-->
                        <div class="box-body no-padding">
                          <ul class="en no-padding" style="list-style: none;">
                           @if(isset($calendar))
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
                                  {{-- <a class="label ed bg-green"><i class="fa fa-pencil"></i></a> --}}
                                </div>   
                              </div>
                            </li><!--./1data-->
    
                            <!--edit form 1-->
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
                                    <button  class="sv btn btn-primary btn-xs">Save</button>
                                  </div>
                                  <div class="col-md-2 pull-right">
                                    <button type="button" class="cn btn btn-primary btn-xs">Cancel</button>
                                  </div>
                                </div>
                              </div><!--body-->
                            </form>
                            </li>
                                @endif
                              @endforeach 
                            @endforeach
                            @endif
                          </ul>
                        </div>
                        <div class="box-footer">
                            <!-- <button class="btn btn-primary btn-sm pull-right ed">Edit</button> -->
                        </div><!--./boxfooter-->
    
    
                    </div><!--./post-->
                    @endforeach



    </div>
    </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    $('#showSched').click( function(){
      var grade = "{{$studentuser->grade}}";
      var section = "{{$studentuser->section}}";
      $.ajax({
          url: "/ajaxSched/"+grade+"-"+section,
          type: 'get',
                                    
          success: function(result){
          $("#schedule").hide().html(result).fadeIn(700);
           }
      });
    });
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
            borderColor: "{{$value->color}}" 
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