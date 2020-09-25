@extends('admin_template')

@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="box box-primary" style="margin-bottom: 0px;">
            <div class="box-header with-border" >
                <div class="col-md-4">
                    <div class="row">    
                        <div class="col-xs-7 col-md-5" style="margin-right: 0px;">
                            <div>
                                <select class="form-control select2" style="width: 100%;" name="class" id="class">
                                    @if(Auth::user()->type =='a' || Auth::user()->user == 'tjslipio')

                                        @foreach($gradesection as $gs)
                                        <option value="{{$gs->grade}}-{{$gs->section}}"> {{$gs->grade}} - {{$gs->section}} </option>

                                        @endforeach
                                        <option value = "{{$grade}}-{{$section}}" selected> {{$grade}} - {{$section}} </option>
                                    @else
                                    <option value = "{{$grade}}-{{$section}}" selected> {{$grade}} - {{$section}} </option>
                                    @endif
                                </select>
                            </div>  
                        </div>
                        {{-- <div class="col-xs-7 col-md-5" style="margin-left: 0px;">
                            <div>
                                <select class="form-control select2" style="width: 100%;">
                                    <option selected="selected"> Class</option>
                                    <option>Grade 4 - A</option>
                                    <option>Grade 4 - B</option>
                                    <option>Grade 4 - C</option>
                                    <option>Grade 3 - A</option>
                                    <option>Grade 3 - B</option>
                                    <option>Grade 3 - C</option>
                                </select>
                            </div>                           
                        </div> --}}

                               

                    </div><!--row-->
                    </div>
                <div class="col-xs-4 col-md-2 box-tools pull-right" style="top: 10px;">
                        <input type="text" id="datepicker" class="form-control" name="date" style="width: 100px; border: 0; line-height: 12px; height: 1px;padding: 5px; display: inline;">
                        <span class="fa fa-calendar" style="cursor: pointer;" title="choose date"></span>    
                  <button class="btn btn-box-tool pull-right" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                  <button class="btn btn-box-tool pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <!-- <label  for="date" class="pull-right">
                      <input type="text" id="datepicker" class="form-control" name="date" style="position:absolute; right: 70px;width: 100px; border: 0; line-height: 10px; height: 1px;padding: 5px;">
                      <span class="fa fa-calendar"></span>
                    </label>             -->
                </div>
            </div><!--./box-header-->
            <style>
label[disabled]{
  pointer-events:none;
}
              </style>
            <form role="form" method="post" enctype="multipart/form-data" action="/tattendance/submit" id="attendanceForm" autocomplete="off">
            <div class="box box-body" style="margin-bottom: 0px; border-top: 3px;">
            <input type="hidden" name="date" value="{{$date}}">
            <input type="hidden" name="grade" value="{{$grade}}">
            <input type="hidden" name="section" value="{{$section}}">
                @csrf
                <div>
                    <h3 style="margin-top: 10px; display: inline-block;">Subject and Class</h3>
                <h3 class="pull-right" style="display: inline-block; margin-bottom: 10px; margin-top: 10px; padding-right: 20px;">{{date('l F d, Y', strtotime($date))}}</h3>
                </div>
                <div class="row" style=" text-indent: 35px;">
                    <div class="col-xs-2 col-md-2" style="display: inline-block;">
                        {{-- <div style="width: 120px;"><strong>ID#</strong></div> --}}
                        <div ><strong>ID#</strong></div>
                    </div>
                    <div class="col-xs-4 col-md-4" style="display: inline-block;">
                        {{-- <div style="width: 200px;"><strong>Name</strong></div> --}}
                        <div ><strong>Name</strong></div>
                    </div>
                    <div class="col-xs-4 col-md-6" style="display: inline-block;">
                        {{-- <div style="width: 200px;"><strong>Attenadance</strong></div> --}}
                        <div style="width: 200px;"><strong>Attendance</strong></div>
                    </div>
                </div>

                @foreach($students as $student)
            <!--===============-->    
            <!--1 student form-->
            <!--===============-->    
            <div class="container-fluid" style="margin-bottom: 10px;">
                <div class="row row-bordered" >
                    <div class="col-md-2" style=" display: inline-block;">
                    <div>{{$student->id}}</div>
                    </div>
                    <div class="col-md-4" style=" display: inline-block;">
                        <div>{{UCWORDS($student->firstname)}} {{UCWORDS($student->lastname)}} </div>
                    </div>
                    <div class="col-xs-9 col-md-4">
                        
                        
                        <div class="  dis btn-group" id="one123" style="display: inline-block;" data-toggle="buttons">
                        <label class="dis btn btn-sm {{$student->status == 'present'?'btn-success':'btn-success'}} {{($student->status != 'present') &&($student->status != '')  ? 'bg-gray':''}}" {{$student->status != ''?'disabled':''}}>
                                <input type="radio" name="student-{{$student->id}}" id="option1" value="present" {{$student->status == 'present'?'disabled':''}}> Present
                              </label>
                              <label class="dis btn btn-sm {{$student->status == 'absent'?'btn-danger':'btn-danger'}} {{($student->status != 'absent') &&($student->status != '')?'bg-gray':''}}" {{$student->status != ''?'disabled':''}}>
                                <input type="radio" name="student-{{$student->id}}" id="option1" value="absent" {{$student->status == 'absent'?'disabled':''}}> &nbsp;Absent&nbsp;
                                </label>
                                <label class="dis btn btn-sm {{$student->status == 'absent'?'btn-warning':'btn-warning'}} {{($student->status != 'late') &&($student->status != '')?'bg-gray':''}}" {{$student->status != ''?'disabled':''}}>
                                <input type="radio" name="student-{{$student->id}}" id="option1" value="late" {{$student->status == 'late'?'disabled':''}}> Late &nbsp;&nbsp;    
                                  </label>
                            {{-- <button type="button" id="stud123" class="dis btn btn-sm btn-success">Present</button>
                            <button type="button" id="stud123" class="dis btn btn-sm btn-danger">Absent</button>
                            <button type="button" id="stud123" class="dis btn btn-sm btn-warning">Late</button> --}}
                        </div>
                    </div>
                    <button type="button" class="col-xs-3 col-md-2 btn btn-primary btn-sm" id="ed">Edit</button>
                </div>
                @push('scripts')
                <script>
                    // $('#one123 > .btn-sm').click(function() {
                    //   $('#one123 > .btn-sm').removeClass('bg-maroon');
                    //   $(this).addClass('bg-maroon');
                    // });

                    // $('#ed1').click(function(){
                    //   $('#one123 #stud123').removeAttr('disabled');
                    // });
                </script>
                @endpush
            </div><!--container-->
            
            
            
            <!--===============-->    
            <!-- student form end-->
            <!--===============--> 

            @endforeach

            

           
          
            </div>
            <!--./box-boxbody-->

            
            <div class="box-footer" style="margin-top: 0px;">                    
                <!-- <button type="button" class="btn  btn-primary" >june 9, 2009</button> -->
            <button type="submit" class="btn  btn-primary center-block" id="sbmit1" disabled >Save</button>
            <a href="/tattendance/{{$grade}}-{{$section}}/{{$datePrev}}"><button type="button"  class="btn  btn-warning center-block pull-left" >&laquo;{{date('l F d,Y',strtotime($datePrev))}}</button></a>
            <a href="/tattendance/{{$grade}}-{{$section}}/{{$dateNext}}"> <button  type="button" class="btn  btn-warning center-block pull-right" >{{date('l F d,Y',strtotime($dateNext))}}&raquo;</button></a>
            </div><!-- /.box-footer-->
        </form>
            @push('scripts')
            <script>
              $(document).on('click','#sbmit1',function(){
                // alert('test');
                // $('.btn-sm').addClass('disabled');
                // $('input[type="radio"]').addClass('disabled');
                // $(".btn-group label").attr("disabled", true);
                // $(".btn-group :input").attr("disabled", true);
              });

              $('#ed').click(function(){
                $(this).parent().find(".btn-group label").attr("disabled", false);
                $(this).parent().find(".btn-group :input").attr("disabled", false)
              });
            </script>
  @endpush
  @push('scripts')
  <script> 

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
        var gs = $('#class').val();

        console.log(this.value);
        console.log(output);
        window.location.href = "/tattendance/"+gs +'/' + output;

      });
    $(".fa-calendar").click(function () {
      $("#datepicker").datepicker("show");
    });

    $('.btn-sm').click(function() {
      if($(this).hasClass('btn-success')){
          $(this).siblings().removeClass('bg-blue').addClass('bg-gray');
          $(this).addClass('bg-blue');
        //   $(this).addClass('active');
        //   $(this).find('input').attr('checked', true);
        
      }else if($(this).hasClass('btn-danger')){
          $(this).siblings().removeClass('bg-blue').addClass('bg-gray');
          $(this).addClass('bg-blue');
        //   $(this).children('input').addAttr('checked');
      }else if($(this).hasClass('btn-warning')){
          $(this).siblings().removeClass('bg-blue').addClass('bg-gray');
          $(this).addClass('bg-blue');
        //   $(this).children('input').addAttr('checked');
      }
      $('#sbmit1').attr("disabled", false);
        
    });

    $(document).on('click','#ed',function(){
      event.preventDefault();
        $(this).parent().find('label').removeAttr('disabled');
        $(this).parent().find('input').removeAttr('disabled');
        $(this).parent().find('.btn-sm').removeClass('bg-gray');
        
      // $('#one234 #stud234').removeAttr('disabled');
    });

    $(document).on('submit','#attendanceForm',function(){

        $('#attendanceLoader').fadeIn(350);
    });

    $(document).on('change','#class',function(){
        gs = $(this).val();
        window.location = "/tattendance/"+gs;
    });

  </script>

  
  @endpush
  <div class="overlay" style="display:none;" id="attendanceLoader">
        <i class="fa fa-refresh fa-spin"></i>
</div>
        </div>
    </div>
  </div><!--./row-->

@endsection