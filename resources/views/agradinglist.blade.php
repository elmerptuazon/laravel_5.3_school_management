@extends('admin_template')

@section('content')

<div class="row">
        <div class="col-md-12">
          @if(session()->has('error'))
            <div class="alert alert-danger taskAlert">
                {{ session()->get('error') }}
            </div>
          @elseif(session()->has('message'))
            <div class="alert alert-success taskAlert">
                {{ session()->get('message') }}
            </div>
          @endif
          <div class="box box-success">
            <div class="box-header with-border">
              <h5>Add a Task</h5>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <form method="post" action="{{ route('aaddtask') }}" role="form">
            @csrf
            <div class="box-body">
              <div class="row">
                
                  <div class="col-xs-12 col-md-3">
                    <label for="AddTask">Select type of Task</label>
                    <select class="form-control" name="task_type_name" required>
                      <option value="">--- Select Task ---</option>
                      @foreach($task_type_type_name as $tasks)
                        <option value="{{ $tasks->type_name }}">{{ $tasks->task_title }}({{ $tasks->type_name }})</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-xs-12 col-md-offset-1 col-md-3" >
                      <label for="taskTitle">Title</label>
                      <input type="text" class="form-control" name="task_title" placeholder="Enter Title" required>                      
                  </div>
                  <div class="col-xs-12 col-md-offset-1 col-md-3">
                    <label for="totalPoints">Total Points</label>
                    <input type="text" class="form-control" name="task_total_points" required>
                    <input id="task_student_grade" type="hidden" class="hide" name="task_grade" value="{{$grade}}">             
                    <input id="task_student_section" type="hidden" class="hide" name="task_section" value="{{$section}}">
                    <input id="task_student_subject" type="hidden" class="hide" name="task_subject" value="{{$subject}}">
                    <input type="text" class="hidden" name="task_teacher_id" value="{{$teacheruser->id}}">                               
                  </div>
                  
              </div>
                <div class="row">
                  <div class="col-xs-12 col-md-3">
                    <label>School Period</label>
                    <select class="form-control" name="task_period">
                      <option value="1">First Quarter</option>
                      <option value="2">Second Quarter</option>
                      <option value="3">Third Quarter</option>
                      <option value="4">Fourth Quarter</option>
                    </select>
                  </div>
                  <div class="col-xs-12 col-md-offset-1 col-md-3">
                    <label>School Year</label>
                    <select class="form-control schoolYear" name="task_school_year">
                      
                    </select>
                  </div>
                  <div class="col-xs-12 col-md-offset-1 col-md-3 center" style="margin-top: 10px;">
                    <button class="btn btn-sm btn-primary center-block" id="create_task_submit" type="submit" name="submit" value="save">Create Task</button>
                  </div>
                </div>
              </div>
            </div>
            </form>
          </div>
        </div>

        <form method="post" id="addScoreForm" action="{{ route('aaddscore') }}" role="form">
        @csrf
          <div class="col-md-12">
              <div class="box box-default">
                <div class="box-header with-border">
                  <!-- <div class="row" style="margin-left: 25px; margin-right: 25px;"> -->
                  
                    <h5 class="col-xs-12 col-md-5"><strong>Task Data List {{$subject}} {{$grade}} {{$section}}</strong></h5>
                        <div class="col-xs-12 col-md-6">
                          <select id="selectTasks" class="form-control" name="task_name">
                            <option value="#">--- Select Task ---</option>
                            @foreach($tasktype_names as $tasks)
                              <option value="{{ $tasks->id }}">{{ $tasks->task_title }}({{ $tasks->task_type }}) {{$tasks->task_grade}}-{{$tasks->task_section}}</option>
                            @endforeach
                          </select>
                        </div>
                      
                      <div >
                        <p class="col-xs-12 col-md-12" ><strong class="center-block"></strong></p>
                      </div>
                      <!-- <div class="hiddenTotalPts">
                        <p class="col-xs-12 col-md-12"><strong id="totalPointsInTask" class="center-block"></strong></p>
                      </div> -->
                    <div class="box-tools pull-right">
                      <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                  <!-- </div> -->
                </div>
                <div class="selectTaskTitle">
                  <h1 style="text-align: center;">Select a Task</h1>
                </div>
                
                  <div class="box-body selectTask hide">
                      <div class="no-margin">   
                        <table class="table table-hover table-striped" style="width: 100%;">
                          <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>
                              <p>Score</p>
                              <p></p>                          
                            </th>
                            <th>Status</th>
                          </tr>
                          <!--hidden Form-->
                          @foreach($students as $student)
                          
                          <tr>
                            <td>{{ $student->id }}</td>
                            <td><a style="cursor: pointer;" href="#">{{ $student->lastname }} {{ $student->firstname }}</a></td>
                            <td>
                              <div class="row">
                                <div class="col-xs-12 col-md-6 " style="padding-right: 5px;">
                                  <input class="pull-right scoreExists" type="text" style="width: 45px;" name="task_score[{{ $loop->index }}][score]" value="">
                                  <input type="hidden" style="width: 45px;" name="task_score[{{ $loop->index }}][name]" value="{{ $student->lastname }}">
                                  <input id="studentID" type="hidden" style="width: 45px;" name="task_score[{{ $loop->index }}][ID]" value="{{ $student->id }}">
                                  <input id="teacherID" type="hidden" name="task_teacher_id" value="{{$teacheruser->id}}">
                                </div>
                                <div class="col-xs-12 col-md-6" style="padding-left: 0px;">
                                  <p class="totalScoreInner pull-left"></p>
                                </div>
                                
                              </div>
                            </td>
                            <td>
                              <select class="form-control" name="task_status">
                                <option value="none" selected> none </option>
                                <option value="submitted"> submitted </option>
                                <option value="excused"> excused </option>
                                <option value="incomplete"> incomplete </option>
                              </select>
                            </td>
                          </tr>
                          
                          @endforeach
                          
                        </table>
                      </div>
                      <div class="center">
                        <button class="btn btn-sm btn-primary center-block" type="submit" name="submit">Submit</button>
                      </div>
                  </div>
                <!-- bodyt end -->
              </div>
            </div>
            
          </form>
          <!-- Behavior Section -->
        <div class="col-md-12">
          <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Student Behavior Grading: {{$subject}} {{$grade}}-{{$section}}</h3>
             
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
              </div>
              <form method="post" action="{{ route('characterscore') }}" role="form">
                @csrf
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-3">
                      <div class="box box-primary">
                        <div class="box-header">
                          <h5>Select a Student</h5>
                          <!-- Get the List of Student -->
                          <div class="col-xs-12 col-md-12">
                            <select class="form-control" name="qualitative_student" id="studentLoader">
                              @empty($qualitative_student_name)
                              <option value="">Current: --Select Student--</option>
                              @endempty
                              @isset($qualitative_student_name)
                              <option value="{{$qualitative_student_name->id or ''}}">Current: {{ ucwords($qualitative_student_name->firstname) }} {{ ucwords($qualitative_student_name->lastname) }}</option>
                              @endisset
                              @foreach($students as $student)
                                <option value="{{$student->id}}/{{$student->lastname}} {{$student->firstname}}">{{ ucwords($student->firstname) }} {{ ucwords($student->lastname) }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="box-header">
                          <h5>Period</h5>
                          <div class="col-xs-12 col-md-12">
                            <select class="form-control" name="qualitative_period" id="qualitative_period">
                              <option value="{{$period_clicked or ''}}">Current: {{$period_clicked or '--Select Priod--'}}</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                            </select>
                          </div>
                        </div>
                        <div class="box-header">
                          <h5>School Year</h5>
                          <div class="col-xs-12 col-md-12">
                            <select class="form-control" name="qualitative_school_year" id="qualitative_school_year">
                              <option value="{{$schoolYear_clicked or ''}}">Current: {{$schoolYear_clicked or '--Select School Year--'}}</option>
                            </select>
                          </div>
                        </div>
                        <div class="box-body">
                          <div class="row">
                            <div class="col-xs-12">
                              <div class="col-xs-5">Student ID:</div>
                              <div class="col-xs-7" id="studentId">{{$qualitative_student_name->id or ''}}</div>
                            </div>
                            <div class="col-xs-12">
                              <div class="col-xs-5">Name:</div>
                              @empty($qualitative_student_name)
                              <div class="col-xs-7" id="studentFullName"></div>
                              @endempty
                              @isset($qualitative_student_name)
                              <div class="col-xs-7" id="studentFullName">{{ ucwords($qualitative_student_name->firstname) }} {{ ucwords($qualitative_student_name->lastname) }}</div>
                              @endisset
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="box-header">
                          <div class="col-xs-12 col-md-12">
                            <button id="search_qualitative_student" class="btn btn-sm btn-primary center-block" type="button">Search</button>
                          </div>
                        </div>
                      
                    </div>
                    <div class="col-md-9">
                      <div class="box box-success">
                        <div class="box-header">
                          <h4><strong>Character Development</strong></h4>
                        </div>
                        <div class="box-body">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="col-md-6">
                                <div class="col-md-12"><h5><strong>BEHAVIOR IN SCHOOL</strong></h5></div>
                                <div class="col-md-12">
                                  @foreach($qualitative_types_behavior as $types)
                                  <div class="col-md-12">
                                    <div class="col-md-6">{{$types->type_name}}</div>
                                    <div class="col-md-6">
                                      <select class="form-control" name="qualitative_scores_behavior[{{$loop->index}}][behavior]">
                                        <option value="{{$types->score or 'NA'}}">{{$types->score or '--Select Score--'}}</option>
                                        <option value="A">A</option>
                                        <option value="B+">B+</option>
                                        <option value="B">B</option>
                                        <option value="B-">B-</option>
                                        <option value="C+">C+</option>
                                        <option value="C">C</option>
                                        <option value="C-">C-</option>
                                        <option value="D">D</option>
                                      </select>
                                      <input type="hidden" value="{{$types->id}}" name="qualitative_scores_behavior[{{$loop->index}}][behaviorid]">
                                    </div>
                                  </div>
                                  @endforeach
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="col-md-12"><h5><strong>COLLABORATIVE PEER</strong></h5></div>
                                <div class="col-md-12">
                                @foreach($qualitative_types_collaborative as $types)
                                  <div class="col-md-12">
                                    <div class="col-md-6">{{$types->type_name}}</div>
                                    <div class="col-md-6">
                                      <select class="form-control" name="qualitative_scores_collaborative[{{$loop->index}}][collaborative]">
                                        <option value="{{$types->score or 'NA'}}">{{$types->score or '--Select Score--'}}</option>
                                        <option value="A">A</option>
                                        <option value="B+">B+</option>
                                        <option value="B">B</option>
                                        <option value="B-">B-</option>
                                        <option value="C+">C+</option>
                                        <option value="C">C</option>
                                        <option value="C-">C-</option>
                                        <option value="D">D</option>
                                      </select>
                                      <input type="hidden" value="{{$types->id}}" name="qualitative_scores_collaborative[{{$loop->index}}][collaborativeid]">
                                    </div>
                                  </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12">
                              <div class="col-md-6">
                                <div class="col-md-12"><h5><strong>QUALITY WORKER</strong></h5></div>
                                <div class="col-md-12">
                                  @foreach($qualitative_types_quality as $types)
                                    <div class="col-md-12">
                                      <div class="col-md-6">{{$types->type_name}}</div>
                                      <div class="col-md-6">
                                        <select class="form-control" name="qualitative_scores_quality[{{$loop->index}}][quality]">
                                          <option value="{{$types->score or 'NA'}}">{{$types->score or '--Select Score--'}}</option>
                                          <option value="A">A</option>
                                        <option value="B+">B+</option>
                                        <option value="B">B</option>
                                        <option value="B-">B-</option>
                                        <option value="C+">C+</option>
                                        <option value="C">C</option>
                                        <option value="C-">C-</option>
                                        <option value="D">D</option>
                                        </select>
                                        <input type="hidden" value="{{$types->id}}" name="qualitative_scores_quality[{{$loop->index}}][qualityid]">
                                      </div>
                                    </div>
                                    @endforeach
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="col-md-12"><h5><strong>SELF DIRECTED LEARNER</strong></h5></div>
                                <div class="col-md-12">
                                    @foreach($qualitative_types_self as $types)
                                      <div class="col-md-12">
                                        <div class="col-md-6">{{$types->type_name}}</div>
                                        <div class="col-md-6">
                                          <select class="form-control" name="qualitative_scores_self[{{$loop->index}}][self]">
                                            <option value="{{$types->score or 'NA'}}">{{$types->score or '--Select Score--'}}</option>
                                            <option value="A">A</option>
                                            <option value="B+">B+</option>
                                            <option value="B">B</option>
                                            <option value="B-">B-</option>
                                            <option value="C+">C+</option>
                                            <option value="C">C</option>
                                            <option value="C-">C-</option>
                                            <option value="D">D</option>
                                          </select>
                                          <input type="hidden" value="{{$types->id}}" name="qualitative_scores_self[{{$loop->index}}][selfid]">
                                        </div>
                                      </div>
                                    @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="box-footer">
                            <button class="btn btn-sm btn-primary center-block" id="character_development_submit" type="submit">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </form>
            </div>
          </div>
    
</div><!--./row-->
@push('scripts')
<script>
$(document).ready(function () {

 @foreach($students as $student)
//for add TEST (posting)
$(this).on("click","#modal1Toggle{{$student->id}}",function(){
  $("#modal1AddHomeWork{{$student->id}}").modal("show");    
})
$(this).on("click","#modal2Toggle{{$student->id}}",function(){
  $("#modal2AddHomeWork{{$student->id}}").modal("show");    
})
$(this).on("click","#modal3Toggle{{$student->id}}",function(){
  $("#modal3AddHomeWork{{$student->id}}").modal("show");    
})
$(this).on("click","#modal4Toggle{{$student->id}}",function(){
  $("#modal4AddHomeWork{{$student->id}}").modal("show");    
})
//for posting end
@endforeach

})

</script>
@endpush

@push('scripts')
<script>
$(document).ready(function () {

//fadeout&onchange of select added by elmer
$('div.taskAlert').fadeOut(3000);
var taskGradeValue = $('#task_student_grade').val();
var taskSectionValue = $('#task_student_section').val();
var taskSubjectValue = $('#task_student_subject').val();
$('#selectTasks').on('change', function() {
    var taskValue = $(this).val();
    localStorage.isClickedSearchQualitative = 0;
    window.location.href = '/agrading/list/'+taskSubjectValue+'/'+taskGradeValue+'-'+taskSectionValue+'/tasklist/' + taskValue;
});

var yearToday = new Date().getFullYear();
localStorage.isClickedSearchQualitative = 0;

$('.schoolYear,#qualitative_school_year').append($('<option>', {value:yearToday+'-'+(yearToday+1), text:yearToday+'-'+(yearToday+1)}));

$('#search_qualitative_student').on('click', function() {

  var period = $('#qualitative_period').val();
  var schoolYear = $('#qualitative_school_year').val();

  var strSplit = $('#studentLoader').val().split("/");


  if(schoolYear == '' || period == '' || strSplit[0] == '') {
    alert('Please input Student, Period and School Year.')

    return;
  } else {
    $('#studentId').text(strSplit[0]);
    $('#studentFullName').text(strSplit[1]);


    window.location.href = '/agrading/list/'+taskSubjectValue+'/'+taskGradeValue+'-'+taskSectionValue+'/qualitative/score/' + strSplit[0] + '/' + period + '/' + schoolYear;
  }


});

if(window.location.href.indexOf("qualitative/score") != -1) {
  localStorage.isClickedSearchQualitative = 1;
}

$('#character_development_submit').on('click', function() {

var period = $('#qualitative_period').val();
var schoolYear = $('#qualitative_school_year').val();

var strSplit = $('#studentLoader').val().split("/");


if(schoolYear == '' || period == '' || strSplit[0] == '' || localStorage.isClickedSearchQualitative == 0) {
  alert('Please input Student, Period and School Year.')

  return false;
}


})




// end elmer
})

</script>
@endpush

@endsection