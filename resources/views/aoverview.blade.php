@extends('admin_template') 
@section('content')

<div class="row">
  {{--
  <pre> {{ $user->email }} </pre>
  <pre> {{ print_r($studentuser) }} </pre>
  <pre> {{ print_r($homeworks) }} </pre>
  <pre> {{ print_r($pubdate) }} </pre>
  <pre> {{ print_r($schedule) }} </pre> --}}

  <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">
            Upcoming Events for this Month 
          </h3>
          <div class="box-tools pull-right">
                       
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
          .target.expanded{
            height: auto;                           
          }
        </style>
        <div class="box-body">
            
         
          
          <div class="row" style="min-height:200px">
            <div class="col-md-2" style="text-indent: 20px; text-align: right;">
            <h3>{{date("F")}}</h3>
            @if(isset($calendar))
              @foreach($calendar as $eventmonth) 
              @foreach($eventmonth as $key => $value) 
              @if($monthnow == $value->month)
              <div>{{date('d',strtotime($value->date))}} </div>
              
              
              
              @endif 
              @endforeach 
              @endforeach
            @endif
            </div>
            <div class="col-md-10">
              <h3>Events</h3>
              @if(isset($calendar))
              @foreach($calendar as $eventmonth) 
              @foreach($eventmonth as $key => $value) 
                @if($monthnow == $value->month)
             
              <div>{{$value->event}}</div>
              
                @endif
              @endforeach 
              @endforeach
              @endif
              @if(!isset($calendar[$monthnow]))
              There are no events set yet.
              @endif
            </div>
          </div>
          </div><!-- /.box-body -->
        <div class="box-footer" >      
          @push('scripts')              
          <script>
            $(document).ready(function() {
             
              $("#datepicker").datepicker({
                autoclose: true,
                dateFormat: "yy-mm-dd",
                onSelect: function (dateText) {
                  $(this).change();                           
                }
              })
              .change(function() {  
                  var output = this.value.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$1-$2");
                      console.log(this.value);
                      console.log(output);
                  window.location.href = "/admin/" + output;
              });
              $(".fa-calendar").click(function(){ $("#datepicker").datepicker("show"); });

              //replyslip showhide
               $(document).on("click", '#rsList' ,function(){ 
                  $(this).next().toggle(350);
                })                                   
              });

              $('.open-datetimepicker').click(function(event){
                event.preventDefault();
                $('#datepicker').click();
              });

          </script>
          @endpush
          <a href="/acalendar">
          <button type="button" class="btn  btn-primary center-block" >View more Calendar of events</button>
          </a>
        </div><!-- /.box-footer-->
      </div><!--./box-->
    </div><!--./col-->

    <div class="col-md-6">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Reply Slip Monitoring</h3>
              <div class="box-tools pull-right">
                <label  for="date"><input type="text" id="datepicker" class="form-control" name="date" style="position:absolute; right: 70px;width: 100px; border: 0; line-height: 10px; height: 1px;padding: 5px;">
                  <span class="fa fa-calendar"></span>
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
            #rsList :hover{
                  cursor:pointer;
            }
          </style>
          <div class="box-body" style="min-height:220px">
            <h5>
              <small class="label pull-right"></small>
            </h5>
            {{-- <div  style="min-height:200px"> --}}
            <table class="table table-striped table-hover" style="width: 100%;">
              <tr>
                <th>Date</th>
                <th>Event</th>
                <th>Scope</th>
              </tr>
              @foreach($replyslips as $replyslip)
              <tr id="rsList">
                <td>{{date('F d, Y',strtotime($replyslip->date))}}</td>
              <td><a href="/view/rs/{{$replyslip->id}}">{{$replyslip->title}}</a></td>
                <td>{{$replyslip->grade}}</td>
              </tr>
              <tr class="hidp" style="display:none;"> <!--hidden part-->
                {{-- <td colspan="1"> --}}
                  {{-- <a href="permissionForm.html"><button class="btn btn-primary btn-sm " style="margin-right: 5px;">Permission Slip</button></a> --}}
                  {{-- <a href="reply.html"><button class="btn btn-primary btn-sm ">Reply Slip</button></a>
                </td> --}}
                <td colspan="3">
                    <div><a href="/view/rs/{{$replyslip->id}}"><button class="btn btn-primary btn-sm ">Reply Slip</button></a></div>
                  <div class="col-md-3">
                    <h4 style="text-align: right;">Results</h4>
                  </div>
                  <div class="col-md-9">
                    <ul class="list-unstyled">       
                      @foreach($replyslip->replyoption as $option)
                      <li>
                      <p style="text-align: left;"><strong>( {{$option->replyans}} )</strong>  {{$option->choice}}</p>
                      </li>
                      @endforeach
                      
                    </ul>
                  </div> 
              </tr><!--./hidden part-->
              
              @endforeach
              
                  
              
            </table>
            {{-- </div> --}}
          </div><!-- /.box-body -->
          <div class="box-footer" >                    
                              <!-- <button type="button" class="btn  btn-primary" >june 9, 2009</button> -->
                              <a href="/areplyslips">
                              <button type="button" class="btn  btn-primary pull-right"  >View More</button>
                              </a>
          </div><!-- /.box-footer-->
        </div><!--boxboxprimary-->
      </div><!--col-md-6-->
    </div><!--./row-->

    <div class="row">
        <div class="col-md-6">
            <div class="box box-success hide">
              <div class="box-header with-border">
                <h3 class="box-title">Users with Active Accounts</h3>
                <p>This is the proportion of users who have activated their accounts broken out by user type.</p>     
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <div class="Chart">
                  <canvas id="bar-chart" width="800" height="450"></canvas>
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <div class="box box-success" style="background-color:rgba(218,213,213,0.5)">
              <div class="box-header with-border">
                <h3 class="box-title">Users with Active Accounts</h3>
                <p>This is the proportion of users who have activated their accounts broken out by user type.</p>     
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <h3 style="text-align:center;"><small><i><span style="color:red;">This feature is under development.</span></i></small></h3>
              </div>
              <!-- /.box-body -->
            </div>
            
          </div><!--col-->
          <!--End of Bar chart-->

            <!--start of inline Chart-->
        <div class="col-md-6"><!-- jQuery Knob -->         
          <div class="box box-warning">
            <div class="box-header with-border">
              <i class="fa fa-bar-chart-o"></i>
              <h3 class="box-title">Todays Attendance</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
            </div><!-- /.box-header -->            
            <div class="box-body">
              <div class="row">
                  <div class="col-xs-12 col-md-12 col-lg-12 text-center" >
                      <div style="display:inline;">
                        
                        <input type="text" class="knob" value="{{round($presentTotal/$studentsTotal * 100)}}" data-width="200" data-height="200" data-fgcolor="#00c0ef" style="border:0;color:#fff;">
                      </div>
              <div class="center-block"style="font-size: smaller; position: relative; text-align: center;  margin-bottom: 25px; width: 200px;height: 30px;overflow: hidden;
              text-overflow: ellipsis;white-space: nowrap;">
                      <strong>Present - {{$presentTotal}}</strong> </div>
                    </div>

                     
              </div><!-- /.row -->            
              <div class="row">
                <style>
                  .dot{  
                    height: 125px;                  
                    width: 125px;
                    background-color: white;
                    border: 10px solid #f39c12;
                    border-radius: 50%;
                    display: inline-block;
                    margin-bottom: 15px;
                  }
                </style>
                {{-- <div class="col-xs-6 col-md-6 col-lg-4 text-center">
                  <span class="dot">
                  <div style="font-size: x-large; position: relative; text-align: center; top: 30px; margin: 0px;"><strong>{{$presentTotal}}</strong></div>
                    <div style="font-size: smaller; position: relative; text-align: center; top: 35px;">Student Present</div>
                  </span>  
                </div><!-- ./col --> --}}
                <div class="col-xs-6 col-md-6 col-lg-4 text-center">
                    <div style="display:;">
                      {{-- <div style="font-size: x-large; position: relative; text-align: center; top: 30px; margin: 0px;"><strong>{{$presentTotal}}</strong></div> --}}
                      <input type="text" class="presentKnob" value="100" data-width="120" data-height="120" data-fgcolor="#3cba9f" style="border:0;color:#fff;">
                    </div>
                    <div class="center-block"style="font-size: smaller; position: relative; text-align: center;  margin-bottom: 25px; width: 200px;height: 30px;overflow: hidden;
              text-overflow: ellipsis;white-space: nowrap;">
                      <strong>Total Students</strong> 
                    </div>
                    
                  </div>
                    
                  <div class="col-xs-6 col-md-6 col-lg-4 text-center">
                      <div style="display:;">
                        {{-- <div style="font-size: x-large; position: relative; text-align: center; top: 30px; margin: 0px;"><strong>{{$presentTotal}}</strong></div> --}}
                        <input type="text" class="absentKnob" value="{{round($absentTotal/$studentsTotal * 100)}}" data-width="120" data-height="120" data-fgcolor="#dd4b39" style="border:0;color:#fff;">
                      </div>
                      <div class="center-block"style="font-size: smaller; position: relative; text-align: center;  margin-bottom: 25px; width: 200px;height: 30px;overflow: hidden;
                text-overflow: ellipsis;white-space: nowrap;">
                    <strong>Absent - {{$absentTotal}}</strong> 
                      </div>
                      
                    </div>

                    <div class="col-xs-6 col-md-6 col-lg-4 text-center">
                        <div style="display:;">
                          {{-- <div style="font-size: x-large; position: relative; text-align: center; top: 30px; margin: 0px;"><strong>{{$presentTotal}}</strong></div> --}}
                          <input type="text" class="lateKnob" value="{{round($lateTotal/$studentsTotal * 100)}}" data-width="120" data-height="120" data-fgcolor="#f39c12" style="border:0;color:#fff;">
                        </div>
                        <div class="center-block"style="font-size: smaller; position: relative; text-align: center;  margin-bottom: 25px; width: 200px;height: 30px;overflow: hidden;
                  text-overflow: ellipsis;white-space: nowrap;">
                          <strong>Late - {{$lateTotal}}</strong> 
                        </div>
                        
                      </div>
                      <div class="col-xs-12 col-md-12 col-lg-12 text-center">
                        <a href="/tattendance"><button class="btn btn-primary">Attendance</button></a>
                      </div>
                  </div><!-- ./col -->
                
           

              

                      
            </div> <!-- /.box-body -->
            <div class="box-footer">
            </div>           
          </div><!-- /.box -->         
        </div><!-- /.col -->       
      </div>

@push('scripts')
{{-- C:\Users\ginol\Dev\stpcentraldemo\stpcentral\public\bower_components\chart.js\Chart.min.js --}}
{{-- <script src="{{asset ("/bower_components/chart.js/Chart.min.js")}}"></script> --}}
<!--BarChart-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!-- jQuery Knob -->
<script src="{{asset ("/bower_components/jquery-knob/js/jquery.knob.js")}}"></script>
<!-- Sparkline -->
<script src="{{asset ("/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js")}}"></script>
<script>
////////////////////
 // Bar chart////////////
 //////////////////////
new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
      labels: ["Parents", "Students", "Teachers", "Admin"],
      datasets: [
        {
          label: "Estimated number of active user",
          backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9"],
          // data: [400,700,70,5]
          data: [
            {{$activeusers['parents']}},{{$activeusers['students']}},{{$activeusers['teachers']}},{{$activeusers['admin']}}
            ]
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Number of Active Users'
      }
    }
});

//end of barchart

  $(document).ready(function () {
    $('.sidebar-menu').tree()
    
  })

   $(function () {
    /* jQueryKnob */

    $(".knob").knob({
      "readOnly": true,
      'format' : function (value) {
     return value + '%';
    },
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv)  // Angle
              , sa = this.startAngle          // Previous start angle
              , sat = this.startAngle         // Start angle
              , ea                            // Previous end angle
              , eat = sat + a                 // End angle
              , r = true;

          this.g.lineWidth = this.lineWidth;

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3);

          // if (this.o.displayPrevious) {
          //   ea = this.startAngle + this.angle(this.value);
          //   this.o.cursor
          //   && (sa = ea - 0.3)
          //   && (ea = ea + 0.3);
          //   this.g.beginPath();
          //   this.g.strokeStyle = this.previousColor;
          //   this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
          //   this.g.stroke();
          // }

          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();

          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();

          return false;
        }
      }
       } );
    /* END JQUERY KNOB */

    $(".presentKnob").knob({
                  "readOnly": true,
                  'format' : function (value, replace) {
                    replace = {{$studentsTotal}};
                  return replace;
                  },
      });
      $(".absentKnob").knob({
                  "readOnly": true,
                  'format' : function (value,replace) {
                    replace ={{$absentTotal}};
                  return value + '%';
                  },
      });
      $(".lateKnob").knob({
                  "readOnly": true,
                  'format' : function (value,replace) {
                    replace = {{$absentTotal}};
                  return value + '%';
                  },
      });
  //    //INITIALIZE SPARKLINE CHARTS
  //   $(".sparkline").each(function () {
  //     var $this = $(this);
  //    $this.sparkline('html', $this.data());
  //  });

  //   /* SPARKLINE DOCUMENTATION EXAMPLES http://omnipotent.net/jquery.sparkline/#s-about */
  //   drawDocSparklines();
  //  drawMouseSpeedDemo();
   });
</script>



@endpush

@endsection