@extends('admin_template') 
@section('content')

<div class="row">
  {{--
  <pre> {{ $user->email }} </pre>
  <pre> {{ print_r($studentuser) }} </pre>
  <pre> {{ print_r($homeworks) }} </pre>
  <pre> {{ print_r($pubdate) }} </pre>
  <pre> {{ print_r($schedule) }} </pre> --}}
  
{{-- START Assignments--}}
<div class="col-md-6">
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Assignments </h3>
    <label for="date"></label>
    <div class="box-tools pull-right">

      <input type="text" id="datepicker" class="form-control" name="date" style="width: 100px; border: 0; line-height: 12px; height: 1px;padding: 5px; display: inline;">
      <span class="fa fa-calendar" style="cursor: pointer;" title="choose date"></span>
      <button class="btn btn-box-tool" id='hideshow' title="add assignment">
        <i class="fa fa-plus-circle" style="font-size: 16px;"></i></button>
        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
          <i class="fa fa-minus"></i>
        </button>
        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i>
        </button>
    </div>
  </div>
      <!--Experiment Overlay Form Start-->
      <div class="myContainer">

        <div class="box " id="myForm">
          <style>
            #myForm,
            #tForm,
            #AForm,
            #HForm {
              display: none;
              margin: 20px;
              width: auto;
            }
          </style>

          <div class="box-header with-border">
            <h3 class="box-title">Post Assignment Form</h3>
            <!-- <button class="btn btn-box-tool pull-right" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
      <button class="btn btn-box-tool pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
          </div>
          <form role="form" method="post" enctype="multipart/form-data" action="/assignment/post" autocomplete="off">
            <div class="box-body">
              {!! csrf_field() !!}
              
              <div class="form-group col-md-12">
                <textarea id="editor1" name="description" rows="10" cols="80">
                  
                </textarea>
              </div>
              <div class="form-group col-md-6">
                  <label for="exampleInputSubject">Publish Date</label>
                  <input type="text" class="form-control" id="test2" name = "pubdate"placeholder="Enter Title" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="exampleInputSubject">Subject</label>
  
                  <select class="form-control" name="class">
                    @foreach($subjects as $subject)
                  <option  value="{{$subject->subj}}_{{$subject->grade}}_{{$subject->section}}">{{UCWORDS($subject->subj)}} {{UCWORDS($subject->grade)}} - {{UCWORDS($subject->section)}}</option>
                    @endforeach
                  </select>
                </div>
              <div class="form-group col-md-6">
                <button type="submit" class="btn btn-primary">Post Assignment</button>
              </div>
              
            </div>
            <div class="box-footer">
            </div>
          </form>
        </div>
      </div>
      <style>
        .target {
          overflow: hidden;
          height: 40px;
          position: relative;
          border: 0px solid #999;
          transition: .3s ease;
          cursor: pointer;

        }



        .read-more {
          position: relative;
          right: 0;
          bottom: 0;
        }

        .target.expanded {
          height: auto;

        }
      </style>
      <div class="box-body">

        <ul style="list-style: none;">
          @foreach ($homeworks as $homework)
          <li style="border-bottom: 1px solid #ccc;">
          <div id="HWcontent{{$homework->id}}">
            <h4 style="font-weight:bold;">{{ucwords($homework->subject)}}</h4>
            <span style="float:right; font-style:italic;">{{date("M d, Y", strtotime($homework->pubdate))}}</span>
            <span style="display:block;">{{ucwords($homework->grade)}} {{ucwords($homework->section)}}</span>

            <div class="target">
              {!!htmlspecialchars_decode($homework->description)!!}
              
              <br /><br />
              <div class="pull-right">
                <button type="button" id="editShow{{$homework->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> data-href="/delete.php?id=54" --}}
                
                <button type="button" id="delShow{{$homework->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-delete{{$homework->id}}"><i class="fa  fa-trash"></i></button>
              </div>
            </div>
          </div>
            <span class="read-more pull-right-container" style="display:block; width:100%;">
              <i class="fa  pull-right  fa-angle-up"></i>
            </span>
            
            {{--
            <button class="read-more">read</button> --}}

            {{-- START EDIT assignment --}}
            <div class="box box-danger" id="HW{{$homework->id}}">
                <style>
                  #HW{{$homework->id}} {
                    display: none;
                    margin: 20px;
                    width: auto;
                  }
                </style>
      
                <div class="box-header with-border">
                  <h3 class="box-title">EDIT Assignment </h3>
                  <!-- <button class="btn btn-box-tool pull-right" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
            <button class="btn btn-box-tool pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
                </div>
              <form role="form" method="POST" action="/ajaxAssignment/e/{{$homework->id}}" enctype="multipart/form-data"  name="editAssignment{{$homework->id}}" autocomplete="off">
                  <div class="box-body">
                    {!! csrf_field() !!}
                    <div class="form-group col-md-6">
                      
                    <input type="hidden" name="hw" value="{{$homework->id}}" >
                      <label for="exampleInputSubject">Publish Date</label>
                    <input type="text" class="form-control" id="datepicker{{$homework->id}}" name = "pubdate"placeholder="Enter Title" value="{{$homework->pubdate}}" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputSubject">Subject</label>
      
                      <select class="form-control" name="class">
                      <option value="{{$homework->subject}}_{{$homework->grade}}_{{$homework->section}}"selected>{{$homework->subject}} {{$homework->grade}} - {{$homework->section}}</option>
                        @foreach($subjects as $subject)
                      <option  value="{{$subject->subj}}_{{$subject->grade}}_{{$subject->section}}">{{UCWORDS($subject->subj)}} {{UCWORDS($subject->grade)}} - {{UCWORDS($subject->section)}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-12">
                    <textarea id="editorHW{{$homework->id}}" name="description" rows="10" cols="80">
                        {{htmlspecialchars_decode($homework->description)}}
                      </textarea>
                    </div>
      
                    <div class="form-group col-md-6">
                      <button type="buttom" class="btn btn-primary" id="editAjax{{$homework->id}}">Edit this Assignment</button>
                      
                    </div>
                    <div class="form-group col-md-6" id="chars">
                        <button type="button" class="btn" id="editCancel{{$homework->id}}">Cancelt</button>
                    </div>
                  </div>
                  <div class="box-footer">
                  </div>
                </form>
              </div>
              {{-- END EDIT assignment --}}

          {{-- START modal for delete start --}}
              <div class="modal fade" id="confirm-delete{{$homework->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              Confirm Delete Homework {{$homework->id}} - {{$homework->subject}} {{$homework->grade}} {{$homework->section}} 
                          </div>
                          <div class="modal-body">
                              <strong>Are you sure you want to delete this assignment?</strong> <p> {!! htmlspecialchars_decode(substr($homework->description, 0, 360)) !!}...</p>
                          </div>
                          <div class="modal-footer">
                              
                              <form role="form" method="post" action="/ajaxAssignment/x/{{$homework->id}}" enctype="multipart/form-data"  name="delAssignment{{$homework->id}}">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                {!! csrf_field() !!}
                                <input type="hidden" name="hw" value="{{$homework->id}}" />
                              <button type="submit" class="btn btn-danger btn-ok" id="delHW{{$homework->id}}" data-dismiss="modal">Delete</button>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
          {{--END modal for delete  --}}
          </li>
          @endforeach

        </ul>


      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        {{--
        <form action='#'>
          <input type='text' placeholder='New task' class='form-control input-sm' />
        </form> style="position: absolute; right: 10px;"--}} 

        @if($prev != '')
        <a href="/toverview/{{$prev->pubdate}}">
          <button type="button" class="btn  btn-primary">{{$prev->pubdate}}</button>
        </a>
        @endif @if($next!='')
        <a href="/toverview/{{$next->pubdate}}">
          <button type="button" class="btn  btn-primary" style="float:right;">{{$next->pubdate}}</button>
        </a>
        @endif
        {{-- <button type="button" id="loader" class="btn  btn-success" style="float:right;">?</button> --}}
      </div>
      <!-- /.box-footer-->
      <div class="overlay" style="display:none;" id="loaderTarget">
          <i class="fa fa-refresh fa-spin"></i>
        </div>
    </div>
</div>
{{-- END Assignments --}}

{{-- START Schedule --}}
<div class='col-md-6'>
    <!-- Box -->
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">Schedule</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i>
          </button>
          <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i>
          </button>
        </div>
      </div>
      <div class="box-body" id="schedule">
        <table class="table table-bordered table-striped table-hover">
          <tbody>
            <tr>
              <th style="width: 80px">day</th>
              <th>Subject</th>
              <th>Schedule</th>
              {{--
              <th style="width: 200px">Teacher</th> --}}
            </tr>
            @foreach($schedule as $sched)
            <tr>
              <td>{{$day}}</td>
              <td>{{$sched->subject}} {{$sched->grade}}-{{$sched->section}}</td>
              <td>
                {{$sched->schedule}}
              </td>
             
            </tr>
            @endforeach 

          </tbody>
        </table>

      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <button type="button" class="btn  btn-success center-block" id="showSched">Show All Schedule</button>
      </div>
    </div>
    <!-- /.box -->
</div>
  <!-- /.col -->
{{-- END Schedule --}}

</div>

<div class="row">

  <style>
    .dayClickWindow {
      width: 500px;
      height: 500px;
      border-radius: 15px;
      background-color: #000;
      position: absolute;
      left: 50%;
      top: 50%;
      margin-top: -250px;
      margin-left: -250px;
      display: none;
      z-index: 1;
    }
  </style>
  <div class='col-md-8'>
    <div class="box box-primary">
      <div class="box-body">
        <div class="dayClickWindow">
        </div>
        <!-- THE CALENDAR -->
        <div id="calendar"></div>

      </div>
      <!-- /.box-body -->
    </div>
  </div>
  <div class="col-md-4">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3>Calendar of Events</h3>
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
                  @if(isset($calendar))
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
                                {{-- <a class="label ed bg-green"><i class="fa fa-pencil"></i></a> --}}
                              </div>   
                            </div>
                          </li><!--./1data-->
  
                         
                              @endif
                            @endforeach 
                          @endforeach
                        </ul>
                      </div>
                      <div class="box-footer">
                          <!-- <button class="btn btn-primary btn-sm pull-right ed">Edit</button> -->
                      </div><!--./boxfooter-->
  
  
                  </div><!--./post-->
                  @endforeach
                  @endif



  </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $(document).ready(function () {
    
    // $("#hideshow").click(function () {
    //   $("#myForm").toggle(350);
    // });
    $(document).on("click", "#hideshow", function(){
          $("#myForm").toggle(350);
      });

    $(document).on("click", ".target", function(){
      // $(this).prev().toggleClass('expanded');
      if ($(this).height() > 40) {
        $(this).animate({height: 40}, 300);
        $(this).css("background-color", "#eee");
       
        $(this).next().children("i.fa-angle-down").removeClass('fa-angle-down').addClass('fa-angle-up');

      } else {
        $(this).animate({height: $(this).get(0).scrollHeight}, 300);
        $(this).css("background-color", "#eee");

        $(this).next().children("i.fa-angle-up").removeClass('fa-angle-up').addClass('fa-angle-down');
      }

    });


    $("#datepicker").datepicker({
      setDate: new Date(),
        autoclose: true,
        format: "yyyy-mm-dd",
        onSelect: function (dateText) {
          $(this).change();

        }
      })
      .change(function () {
        // console.log(dateText);
        // var targeturl = this.value.replace(/\//g,'-');
        var output = this.value.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$1-$2");
        // var targeturl = output.replace(/\//g,'-');

        console.log(this.value);
        console.log(output);
        window.location.href = "/toverview/" + output;

      });
    $(".fa-calendar").click(function () {
      $("#datepicker").datepicker("show");
    });

   
      $('#test2').datepicker({
        
        autoclose: true,
        format: 'yyyy-mm-dd'
      });

@foreach($homeworks as $homework)
$(document).on("click", "#editShow{{$homework->id}}", function(){
      $("#HW{{$homework->id}}").toggle(350);
});

    $("#editCancel{{$homework->id}}").click(function () {
      $("#HW{{$homework->id}}").toggle(350);
      // $("#editShow{{$homework->id}}").text('edit');
    });
      $('#datepicker{{$homework->id}}').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
      });
@endforeach

    $("#loader").click(function () {
      $("#loaderTarget").fadeIn(500);
    });
    $("#loaderTarget").click(function () {
      $("#loaderTarget").fadeOut(500);
    });
  
  });

  
@foreach($homeworks as $homework)
$('form[name="editAssignment{{$homework->id}}"]').submit(function (event) {


$("#loaderTarget").fadeIn(100);
// var nocache = new Date().getTime();  
for (instance in CKEDITOR.instances) {
CKEDITOR.instances[instance].updateElement();
}
var $form = $(this);
var serializedData = $('form[name="editAssignment{{$homework->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxAssignment/e/{{$homework->id}}",
serializedData,
function (data) {

  setTimeout(function () {
    $("#loaderTarget").fadeOut(100);
    $("#HWcontent{{$homework->id}}").html(data);
  }, 500);
  // $( "#HWcontent{{$homework->id}}" ).html( data );$("#loaderTarget").fadeOut(100);
  $("#HW{{$homework->id}}").toggle(350, function () {
    if ($("#editShow{{$homework->id}}").text() == 'edit') {
      $("#editShow{{$homework->id}}").text('cancel');
    } else {
      $("#editShow{{$homework->id}}").text('edit');
    }
  });

  setTimeout(function () {
    $('#HW{{$homework->id}} [class=target]').animate({
      height: $(this).get(0).scrollHeight
    }, 300);
    $('#HW{{$homework->id}} [class=target]').css("background-color", "#eee");
    // console.log($(this).prev().get(0).scrollHeight);
    // console.log($(this).prev().height());
    $('#HW{{$homework->id}} [class=target]').next().children("i.fa-angle-up").removeClass('fa-angle-up').addClass('fa-angle-down');
  }, 2000);
}
);

event.preventDefault();

});


$(document).on("click", "#delHW{{$homework->id}}", function (event) {
event.preventDefault();
// console.log('it has been clicked');

$("#loaderTarget").fadeIn(100);

var $form = $(this);
var serializedData = $('form[name="delAssignment{{$homework->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxAssignment/x/{{$homework->id}}",
serializedData,
function (data) {

setTimeout(function () {
$("#loaderTarget").fadeOut(100);
$("#HWcontent{{$homework->id}}").html(data);
}, 500);
// $( "#HWcontent{{$homework->id}}" ).html( data );$("#loaderTarget").fadeOut(100);


setTimeout(function () {
$('#HW{{$homework->id}} [class=target]').animate({
height: $(this).get(0).scrollHeight
}, 300);
$('#HW{{$homework->id}} [class=target]').css("background-color", "#eee");
// console.log($(this).prev().get(0).scrollHeight);
// console.log($(this).prev().height());
$('#HW{{$homework->id}} [class=target]').next().children("i.fa-angle-up").removeClass('fa-angle-up').addClass('fa-angle-down');
}, 2000);


}
);



});

@endforeach
  $('.open-datetimepicker').click(function (event) {
    event.preventDefault();
    $('#datepicker').click();
  });
</script>
@endpush 

@push('scripts')
<script type="text/javascript">
  // $('#showSched').click(function () {
  // $(document).on('click','#showSched',function () {
  //   var grade = "4";
  //   var section = "j";
  //   $.ajax({
  //     url: "/ajaxSched/" + grade + "-" + section,
  //     type: 'get',
  //     done: function (result) {
  //       $("#schedule").hide().html(result).fadeIn(700);
  //     }
  //   });
  // });
  $('#showSched').click( function(){
      var grade = "4";
      var section = "j";
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
          revertDuration: 0 //  original position after the drag
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
        // alert('clicked');
      },
      header: {
        left: 'prev,next today',
        center: 'title',
        //   right: 'month,agendaWeek,agendaDay'
        right: ''
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week: 'week',
        day: 'day'
      },
      selectable: true,
      selectHelper: true,
      eventRender: function (event, element) {
        element.popover({
          animation: true,
          delay: 300,
          content: '' + event.title,
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
      $('#add-new-event').css({
        "background-color": currColor,
        "border-color": currColor
      });
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
      event.css({
        "background-color": currColor,
        "border-color": currColor,
        "color": "#fff"
      }).addClass("external-event");
      event.html(val);
      $('#external-events').prepend(event);

      //Add draggable funtionality
      ini_events(event);

      //Remove event from text input
      $("#new-event").val("");
    });
  });
</script>


<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1', {
    toolbar: [
    // { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
    { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
    // { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
    { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
    // '/',
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
    { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
    '/',
    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    // { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
    { name: 'others', items: [ '-' ] },
    // { name: 'about', items: [ 'About' ] }
]
});

    @foreach($homeworks as $homework )
    CKEDITOR.replace('editorHW{{$homework->id}}', {
    toolbar: [
    // { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
    { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
    // { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
    { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
    // '/',
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
    { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
    '/',
    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    // { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
    { name: 'others', items: [ '-' ] },
    // { name: 'about', items: [ 'About' ] }
]
});
    @endforeach
    //bootstrap WYSIHTML5 - text editor
    // $('.textarea').wysihtml5()
  })
</script>
@endpush 
@endsection