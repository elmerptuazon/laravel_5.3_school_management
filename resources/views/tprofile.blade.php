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
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-primary">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="{{ asset("/uploads/profile/".$teacherprofile->profilepic) }}" alt="User profile picture">

        <h3 class="profile-username text-center">{{ ucwords($teacherprofile->firstname) }} {{ ucwords($teacherprofile->lastname) }}</h3>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <!-- About Me Box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">About Me</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          {{-- <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

          <p class="text-muted">
            B.S. in Computer Science from the University of Tennessee at Knoxville
          </p>

          <hr> --}}
          @if(Auth::user()->type == 't' || Auth::user()->type == 'a')
          <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

          <p class="text-muted">{{$teacherprofile->t_address}}</p>
          
          <hr>
          @endif
          <strong><i class="fa fa-birthday-cake"></i> Birthday</strong>

        <p class="text-muted">{{date('F d,Y',strtotime($teacherprofile->birthdate))}}</p>
          <hr>
          @if(Auth::user()->type == 'a')
            <div class="row">
              <div class="col-md-3 col-xs-3">
                <button class="btn btn-primary" id="edit_profile_teacher" type="button">Edit</button>
              </div>
              <div class="col-md-3 col-xs-3">
                <form method="post" action="{{ route('teacherdeletepost') }}">
                  @csrf
                  <input type="hidden" value="{{$teacherprofile->id}}" name="teacher_id_delete" />
                  <button class="btn btn-danger" id="delete_profile_teacher" type="submit" name="submit">Delete</button>
            </form>
              </div>
            </div>
          <hr>
          @endif
          
          @push('scripts')
            <script>
              $('#delete_profile_teacher').on('click', function() {
                var teacher_fullname = "{{ ucwords($teacherprofile->firstname)}}"+' ' + "{{ucwords($teacherprofile->lastname)}}";
                if(confirm('Are you sure you want to delete '+ teacher_fullname + '?') == true) {

                } else {
                  return false;
                }
              })
            </script>
          @endpush
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            @if(Auth::user()->type != 'a')
            <li class="active"><a href="#activity" data-toggle="tab">General Information</a></li>
            @else
            <li><a href="#activity" data-toggle="tab">General Information</a></li>
            <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
            @endif

          {{--<li class="{{Auth::user()->type =='t' ? 'active' : ''}}"><a href="#activity" data-toggle="tab">General Information</a></li>
          <li class="{{Auth::user()->type =='a' ? 'active' : 'hide'}}"><a href="#settings" data-toggle="tab">Settings</a></li>
          --}}
          
        </ul>
        <div class="tab-content">
          @if(Auth::user()->type != 'a')
          <div class="tab-pane active" id="activity">
          @else
          <div class="tab-pane" id="activity">
          @endif
          {{--<div class="tab-pane {{Auth::user()->type =='t' ? 'active' : ''}}" id="activity">--}}
            <!-- Post -->
            <div class="post">
              <div class="user-block">
                <div class="box-header">
                      
                </div>
                <style>
                    .nfo{
                      text-indent: 10px;  
                      /* clear: both; */
                    }
                    p{
                     display: inline-block;
                      margin-left:15px;
                    }
                </style>
                <div class="container">
                    <legend style="font-size: 20px; text-indent: 10px; margin-bottom: 1px;"><i class="fa fa-user" ></i> Teacher</legend> <br>
                  <div id="q">
                    <style>
                      .lst{
                        list-style: none;
                        text-indent: 0px;
                      }
                      #q{
                        display: inline-flex;
                        text-indent: 30px;;
                      }
                    </style>
                      <ul>
                        <li class="lst"><strong>Name:</strong></li>
                        <li class="lst"><strong>Last Name:</strong></li>
                        <li class="lst"><strong>Age:</strong></li>
                        <li class="lst"><strong>Birthday:</strong></li>
                        <li class="lst"><strong>Identification Number:</strong></li>
                        @if(Auth::user()->type == 't' || Auth::user()->type == 'a')
                        <li class="lst"><strong>Address: </strong></li>
                        @endif
                      </ul>
                      <ul>
                          <li class="lst">{{ ucwords($teacherprofile->firstname) }}</li>
                          <li class="lst">{{ ucwords($teacherprofile->lastname) }}</li>
                          <li class="lst">{{$teacherprofile->age}}</li>
                          <li class="lst">{{date('F d,Y',strtotime($teacherprofile->birthdate))}}</li>
                          <li class="lst">{{$teacherprofile->id}}</li>
                          @if(Auth::user()->type == 't' || Auth::user()->type == 'a')
                          <li class="lst">{{$teacherprofile->t_address}}</li>
                          @endif
                      </ul>
                  </div>
                  
                  <legend class="nfo" style="font-size: 20px;"><i class="fa fa-phone" ></i> Contact</legend>
                  <div id="q">
                    <ul>
                      @if(Auth::user()->type == 't' || Auth::user()->type == 'a')
                        <li class="lst"><strong>Land Line:</strong></li>
                        <li class="lst"><strong>Cell Number:</strong></li>
                        <li class="lst"><strong>Email Address</strong></li>
                      @endif
                      </ul>
                      
                      <ul>
                        @if(Auth::user()->type == 't' || Auth::user()->type == 'a')
                          <li class="lst">{{$teacherprofile->t_landline}}</li>
                          <li class="lst">{{$teacherprofile->t_cellno}}</li>
                          <li class="lst">{{$teacherprofile->t_email}}</li>
                        @endif
                      </ul>
                  </div>
                  <Legend class="nfo" style="font-size: 20px; margin-bottom: 20px;"><i class="fa fa-book" ></i> Classes and Subject</Legend>
                  
                  <div id="q">

                    <ul>
                      @if(Auth::user()->type=='t')
                      @foreach($classes as $subj)
                    <li class="lst"><a href="/tclass/{{$subj->subj}}/{{$subj->grade}}-{{$subj->section}}"><strong>{{ucwords($subj->subj)}} {{$subj->grade}} - {{ucwords($subj->section)}}</strong></a> </li>
                      
                      @endforeach
                      @endif
                    </ul>                          
                    
                  </div>
                </div>
                
              </div>          
            </div>
          </div>            
          <!-- /.tab-pane -->

          <div class="tab-pane {{Auth::user()->type =='a' ? 'active' : 'hide'}}" id="settings">
            <!-- Post -->
            <div class="post">
              <div class="user-block">
          
               
               
                  
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grading Permission Control</h3>
                    </div>

                    <div class="box-body" style="border: 1px solid whitesmoke;">
                      <div class="container example">
                        <div class="row">
                          <div class="col-sm-12">
                              <h3>Control Switch<small>(GRADING)</small></h3>
                          </div>
                        </div>
                        <h5><b>Teachers</b></h5>
                        <div class="row">
                            <div class="col-md-12 controlItem">
                                <div class="col-sm-4" style="text-indent: 10px;">
                                    <h4>Grading Control<small><i>(teachers)</i></small></h4>
                                </div>
                                <div class="col-md-offset-3 col-sm-4">
                                    <form method="post" rol="form" id="grading_teacher_edit_form">
                                      @csrf
                                      <button type="button" class="btn btn-lg btn-toggle gradingToggleSwitch {{$grading_teacher_edit->status == 1 ? 'active' : ''}}" data-toggle="button" aria-pressed="true" autocomplete="off">
                                          <input type="hidden" name="grading_teacher_edit" value="{{$grading_teacher_edit->status}}" />
                                          <div class="handle"></div>
                                      </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                      </div>
                      
                      <div class="container example" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                            <h4>Report Card View<small><i>(teachers)</i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4">
                                        <form method="post" rol="form" id="reportcard_view_teacher_form">
                                          @csrf
                                          <button type="button" class="btn btn-lg btn-toggle reportCardTeacherToggle {{$reportcard_view_teacher->status == 0 ? '' : 'active'}}" data-toggle="button" aria-pressed="true" autocomplete="off">
                                              <input type="hidden" name="reportcard_view_teacher" value="{{$reportcard_view_teacher->status}}" />
                                              <div class="handle"></div>
                                          </button>
                                        </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- START OF COMPUTING FINAL GRADE FOR ALL GRADE SECTION -->
                            <div class="container example" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                            <h4>Compute Final Grade<small><i>(teachers)<span style="color:red;">**5-10mins to process</span></i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4" style="padding-left:5%;">
                                        <form method="post" role="form" action="{{route('computeallgrades')}}">
                                          @csrf
                                          <button class="btn btn-danger" id="compute_final_grade_all_student" type="submit" name="submit">Start</button>
                                        </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END OF COMPUTING FINAL GRADE FOR ALL GRADE SECTION -->
                        
                      <div class="container example" style="margin-top: 10px;">
                      <h5><b>Parents</b></h5>
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                            <h4>Report Card View<small><i>(parents)</i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4">
                                        <form method="post" rol="form" id="reportcard_view_parent_form">
                                          @csrf
                                          <button type="button" class="btn btn-lg btn-toggle reportCardToggleSwitch {{$reportcard_view_parent->status == 1 ? 'active' : ''}}" data-toggle="button" aria-pressed="true" autocomplete="off">
                                              <input type="hidden" name="reportcard_view_parent" value="{{$reportcard_view_parent->status}}" />
                                              <div class="handle"></div>
                                          </button>
                                        </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container example" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                            <h4>1st Quarter Grade View<small><i>(parents)</i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4">
                                        <form method="post" rol="form" id="first_quarter_form">
                                          @csrf
                                          <button type="button" class="btn btn-lg btn-toggle firstQuarterToggleSwitch {{$first_quarter_selected->status == 1 ? 'active' : ''}}" data-toggle="button" aria-pressed="true" autocomplete="off">
                                              <input type="hidden" name="first_quarter_value" value="{{$first_quarter_selected->status}}" />
                                              <div class="handle"></div>
                                          </button>
                                        </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container example" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                            <h4>2nd Quarter Grade View<small><i>(parents)</i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4">
                                        <form method="post" rol="form" id="second_quarter_form">
                                          @csrf
                                          <button type="button" class="btn btn-lg btn-toggle secondQuarterToggleSwitch {{$second_quarter_selected->status == 1 ? 'active' : ''}}" data-toggle="button" aria-pressed="true" autocomplete="off">
                                              <input type="hidden" name="second_quarter_value" value="{{$second_quarter_selected->status}}" />
                                              <div class="handle"></div>
                                          </button>
                                        </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <div class="container example" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                            <h4>3rd Quarter Grade View<small><i>(parents)</i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4">
                                        <form method="post" rol="form" id="third_quarter_form">
                                          @csrf
                                          <button type="button" class="btn btn-lg btn-toggle thirdQuarterToggleSwitch {{$third_quarter_selected->status == 1 ? 'active' : ''}}" data-toggle="button" aria-pressed="true" autocomplete="off">
                                              <input type="hidden" name="third_quarter_value" value="{{$third_quarter_selected->status}}" />
                                              <div class="handle"></div>
                                          </button>
                                        </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container example" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                            <h4>4th Quarter Grade View<small><i>(parents)</i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4">
                                        <form method="post" rol="form" id="fourth_quarter_form">
                                          @csrf
                                          <button type="button" class="btn btn-lg btn-toggle fourthQuarterToggleSwitch {{$fourth_quarter_selected->status == 1 ? 'active' : ''}}" data-toggle="button" aria-pressed="true" autocomplete="off">
                                              <input type="hidden" name="fourth_quarter_value" value="{{$fourth_quarter_selected->status}}" />
                                              <div class="handle"></div>
                                          </button>
                                        </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container example" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                            <h4>Include Final Grade View<small><i>(parents)</i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4">
                                        <form method="post" rol="form" id="final_grade_form">
                                          @csrf
                                          <button type="button" class="btn btn-lg btn-toggle finalGradeToggleSwitch {{$final_grade_selected->status == 1 ? 'active' : ''}}" data-toggle="button" aria-pressed="true" autocomplete="off">
                                              <input type="hidden" name="final_grade_value" value="{{$final_grade_selected->status}}" />
                                              <div class="handle"></div>
                                          </button>
                                        </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container example" style="margin-top: 10px;">
                              <h5><b>Students</b></h5>
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                            <h4>School Starts<small><i>(students)(format: YYYY-MM-DD)</i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4" style="padding-left:0px;">
                                          @if(isset($school_start_date->school_date))
                                            <input placeholder="{{$school_start_date->school_date}}" id="startdateselect" class="datepicker" value="{{$school_start_date->school_date}}"/>
                                          @else
                                            <input id="startdateselect" class="datepicker" value=""/>
                                          @endif
                                          
                                        </div>
                                    </div>
                            </div>
                    </div>

                        <div class="container example" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12 controlItem">
                                        <div class="col-sm-4" style="text-indent: 10px;">
                                        <h4>School Ends<small><i>(students)(format: YYYY-MM-DD)</i></small></h4>
                                        </div>
                                        <div class="col-md-offset-3 col-sm-4" style="padding-left:0px;">
                                          @if(isset($school_end_date->school_date))
                                            <input placeholder="{{$school_end_date->school_date}}" id="enddateselect" class="datepicker" value="{{$school_end_date->school_date}}"/>
                                          @else
                                            <input id="enddateselect" class="datepicker" value=""/>
                                          @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>
                  
              </div>          
            </div>
          </div> 
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

@push('scripts')
  <script>
    $(document).ready(function () {

      $('.firstQuarterToggleSwitch').on('click',function(){
        var serializedData = $('#first_quarter_form').serialize();
        if($('.firstQuarterToggleSwitch').hasClass('active') == true){
            alert('Parents cannot view the grades of 1st quarter');
           
            $.post(
            "/admin/quarter/select/first/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }else{
            alert('Parents will see the grades of 1st quarter.');
            $.post(
            "/admin/quarter/select/first/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }
    })

    $('.secondQuarterToggleSwitch').on('click',function(){
        var serializedData = $('#second_quarter_form').serialize();
        if($('.secondQuarterToggleSwitch').hasClass('active') == true){
            alert('Parents cannot view the grades of 2nd quarter.');
           
            $.post(
            "/admin/quarter/select/second/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }else{
            alert('Parents will see the grades of 2nd quarter.');
            $.post(
            "/admin/quarter/select/second/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }
    })

    $('.thirdQuarterToggleSwitch').on('click',function(){
        var serializedData = $('#third_quarter_form').serialize();
        if($('.thirdQuarterToggleSwitch').hasClass('active') == true){
            alert('Parents cannot view the grades of 3rd quarter.');
           
            $.post(
            "/admin/quarter/select/third/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }else{
            alert('Parents will see the grades of 3rd quarter.');
            $.post(
            "/admin/quarter/select/third/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }
    })

    $('.fourthQuarterToggleSwitch').on('click',function(){
        var serializedData = $('#fourth_quarter_form').serialize();
        if($('.fourthQuarterToggleSwitch').hasClass('active') == true){
            alert('Parents cannot view the grades of 4th quarter.');
           
            $.post(
            "/admin/quarter/select/fourth/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }else{
            alert('Parents will see the grades of 4th quarter.');
            $.post(
            "/admin/quarter/select/fourth/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }
    })

    $('.finalGradeToggleSwitch').on('click',function(){
        var serializedData = $('#final_grade_form').serialize();
        if($('.finalGradeToggleSwitch').hasClass('active') == true){
            alert('Parents cannot view the computed final grades.');
           
            $.post(
            "/admin/quarter/select/finalgrade/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }else{
            alert('Parents will see the computed final grades.');
            $.post(
            "/admin/quarter/select/finalgrade/period",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }
    })

      $('#startdateselect').datepicker({startDate: new Date( 2016, 12 - 1, 31 )}).change(function() {
        var selectedDate = $('#startdateselect').val();
        var dateNow = new Date(selectedDate);
        // 01, 02, 03, ... 29, 30, 31
        var dd = (dateNow.getDate() < 10 ? '0' : '') + dateNow.getDate();
        // 01, 02, 03, ... 10, 11, 12
        var MM = ((dateNow.getMonth() + 1) < 10 ? '0' : '') + (dateNow.getMonth() + 1);
        // 1970, 1971, ... 2015, 2016, ...
        var yyyy = dateNow.getFullYear();
        var formatStartDate = yyyy + '-' + MM + '-' + dd;

        var checkDate = checkDateIfCompared(selectedDate, 'start');
        if(checkDate == false) {
          window.location.reload();
        } else {
          window.location.href = "/admin/schoolyear/startdate/"+formatStartDate;
        }

        
      });

      $('#enddateselect').datepicker({startDate: new Date(2016, 12 - 1, 31)}).change(function() {
        var selectedDate = $('#enddateselect').val();
        var dateNow = new Date(selectedDate);
        // 01, 02, 03, ... 29, 30, 31
        var dd = (dateNow.getDate() < 10 ? '0' : '') + dateNow.getDate();
        // 01, 02, 03, ... 10, 11, 12
        var MM = ((dateNow.getMonth() + 1) < 10 ? '0' : '') + (dateNow.getMonth() + 1);
        // 1970, 1971, ... 2015, 2016, ...
        var yyyy = dateNow.getFullYear();
        var formatEndDate = yyyy + '-' + MM + '-' + dd;

        var checkDate = checkDateIfCompared(selectedDate, 'end');

        if(checkDate == false) {
          window.location.reload();
        } else {
          window.location.href = "/admin/schoolyear/enddate/"+formatEndDate;
        }

      });

      function checkDateIfCompared(custom_val='', custom_type='') {
        var date_general = new Date(custom_val);

        var startdate_value = new Date($('#startdateselect').val());
        var enddate_value = new Date($('#enddateselect').val());

        if(custom_val != '' && custom_type == 'start') {
          if(startdate_value > enddate_value) {
            alert('Start date cannot be higher than End date');
            return false;
          }
        }

        if(custom_val != '' && custom_type == 'end') {
          if(enddate_value < startdate_value) {
            alert('End date cannot be less than Start date');
            return false;
          }
        }

      }

      $('.gradingToggleSwitch').on('click',function(){
        var serializedData = $('#grading_teacher_edit_form').serialize();
        if($('.gradingToggleSwitch').hasClass('active') == true){
            alert('Teachers cannot create/edit grades.');
           
            $.post(
            "/config/setting/grading_teacher_edit",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }else{
            alert('Teachers can now create/edit grades.');
            $.post(
            "/config/setting/grading_teacher_edit",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }
    })

    $('.reportCardToggleSwitch').on('click',function(){
        var serializedData = $('#reportcard_view_parent_form').serialize();
        if($('.reportCardToggleSwitch').hasClass('active') == true){
            alert('Parents will see the grades.');
           
            $.post(
            "/config/setting/reportcard_view_parent",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }else{
            alert('Parents cannot view the grades.');
            $.post(
            "/config/setting/reportcard_view_parent",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }
    })

    $('.reportCardTeacherToggle').on('click',function(){
        var serializedData = $('#reportcard_view_teacher_form').serialize();
        
        if($('.reportCardTeacherToggle').hasClass('active') == false){
            alert('Teachers cannot view grades.');
            
            $.post(
            "/config/setting/reportcard_view_teacher",
            serializedData,
            function (data) {
                window.location.reload();
            });
        }else{
            alert('Teachers will see the grades.');
            
            $.post(
            "/config/setting/reportcard_view_teacher",
            serializedData,
            function (data) {
                window.location.reload();
            });
            
        }
    })

    const TEACHERID = "{{$teacher_clicked_id}}";
    $('#edit_profile_teacher').on('click', function() {
      window.location.href = "/tprofile/edit/"+TEACHERID;
    })

    $('#compute_final_grade_all_student').on('click', function() {
      if(confirm('This will recompute all final grades on all students. This will take at least 5-10mins to finish. Do you want to proceed?')) {

      } else {
        return false;
      }
    })
      
    });
  </script>
@endpush

@endsection