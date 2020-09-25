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
                    <input id="task_student_grade" type="hidden" name="task_grade" value="{{$grade}}">             
                    <input id="task_student_section" type="hidden" name="task_section" value="{{$section}}">
                    <input id="task_student_subject" type="hidden" name="task_subject" value="{{$subject}}">
                    <input type="hidden" name="task_teacher_id" value="{{$teacheruser->id}}">                               
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
                    <button class="btn btn-sm btn-primary center-block" type="submit" name="submit" value="save">Create Task</button>
                  </div>
                </div>
              </div>
            </div>
            </form>
          </div>
        </div>

        <div class="modal fade" id="edit-modal">
          <div class="modal-dialog">
            <form method="post" action="{{ route('editScore') }}" id="edit_form" role="form">
            @csrf
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Edit Task</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                      <div class="form-group">
                        <label>Task Title</label>
                        <input type="text" class="form-control" style="height:30px;" name="edit_task_title" value="{{$student_task_list->task_title}}" required>
                      </div>
                      <div class="form-group">
                        <label>Task Type</label>
                        <select class="form-control" name="edit_task_type" required>
                          <option value="{{$student_task_list->task_type}}">{{$student_task_list->task_type}}</option>
                          @foreach($task_type_type_name as $tasks)
                            <option value="{{ $tasks->type_name }}">{{ $tasks->task_title }}({{ $tasks->type_name }})</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Total Points</label>
                        <input type="number" class="form-control" style="height:30px;" name="edit_task_total_points" value="{{$student_task_list->task_total_points}}" required>
                      </div>
                      <div class="form-group">
                        <label>Period</label>
                        <select class="form-control" name="edit_task_period" required>
                          <option value="{{$student_task_list->period}}">{{$student_task_list->period}}</option>
                          <option value="1">First Quarter</option>
                          <option value="2">Second Quarter</option>
                          <option value="3">Third Quarter</option>
                          <option value="4">Fourth Quarter</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>School Year</label>
                        <select class="form-control schoolYear" style="height:30px;" name="edit_task_school_year" required>
                        </select>
                      </div>
                    
                      <input type="hidden" name="edit_task_id" value="{{$student_task_list->id}}" />
                    </div>
                    
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                  <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                </div>
              </div>
            </form>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div class="modal modal-danger fade" id="delete-modal">
          <div class="modal-dialog">
          <form method="post" action="{{ route('deleteScore') }}" role="form">
          @csrf
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete Task</h4>
              </div>
              <div class="modal-body">
                    <input type="hidden" name="delete_grade" value="{{$grade}}">             
                    <input type="hidden" name="delete_section" value="{{$section}}">
                    <input type="hidden" name="delete_subject" value="{{$subject}}">
                    <input type="hidden" name="delete_task_grade" value="{{$student_task_list->id}}"  />
                    <h5>Are you sure to delete this "{{$student_task_list->task_title}}" task?</h5>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" class="btn btn-outline">Delete</button>
              </div>
            </div>
            </form>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <form method="post" id="addScoreForm" action="{{ route('aaddscore') }}" role="form">
        @csrf
          <div class="col-md-12">
              <div class="box box-default">
                <div class="box-header with-border">
                  <!-- <div class="row" style="margin-left: 25px; margin-right: 25px;"> -->
                  <input type="hidden" value="{{$grade}}" name="grade_view">
                  <input type="hidden" value="{{$section}}" name="section_view">
                    <h5 class="col-xs-12 col-md-5"><strong>Task Data List {{$subject}} {{$grade}} {{$section}}</strong></h5>
                        <div class="col-xs-12 col-md-6">
                          <select id="selectTasks" class="form-control" name="task_name">
                            <option value="{{$student_task_list->id}}">Current Selected: {{$student_task_list->task_title}}</option>
                            @foreach($tasktype_names as $tasks)
                              <option value="{{ $tasks->id }}">{{ $tasks->task_title }}({{ $tasks->task_type }})</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-xs-12 col-md-12">
                          <div class="col-xs-12 col-md-2" style="padding-left:0px;">
                            <ul style="list-style-type:none; padding-left:0px;">
                              <li>Name: &nbsp {{$student_task_list->task_title}}</li>
                              <li>Task Type: &nbsp {{$student_task_list->task_type}}</li>
                              <li>Total Points: &nbsp {{$student_task_list->task_total_points}}</li>
                              <li>Period: &nbsp {{$student_task_list->period}}</li>
                              <li>School Year: &nbsp {{$student_task_list->school_year}}</li>
                            </ul>
                          </div>
                          <div class="col-xs-12 col-md-2" style="padding-left:0px;">
                            <button class="btn btn-sm btn-success center-block" data-toggle="modal" data-target="#edit-modal" style="margin:10px;" type="button">Edit Task</button>
                            <button class="btn btn-sm btn-danger center-block" data-toggle="modal" data-target="#delete-modal" style="margin:10px;" type="button">Delete Task</button>
                          </div>
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
                
                  <div class="box-body">
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
                          @forelse($student_existing_score as $student)
                          
                          <tr>
                            <td> {{ $student->sid }}</td>
                            <td><a style="cursor: pointer;" href="/profile/student/{{$student->sid}}">{{ ucwords($student->firstname) }} {{ ucwords($student->lastname) }}</a></td>
                            <td>
                              <div class="row">
                                <div class="col-xs-12 col-md-6 " style="padding-right: 5px;">
                                  <input  min="0" max="{{$student_task_list->task_total_points}}" tabindex="{{ $loop->index + 1 }}" class="pull-right scoreExists" type="number" style="width: 45px;" name="task_score[{{ $loop->index }}][score]" value="{{$student->score or '0'}}" required>
                                  <input class="hide" type="text" style="width: 45px;" name="task_score[{{ $loop->index }}][name]" value="{{ $student->lastname }}">
                                  <input class="hide" id="studentID" type="text" style="width: 45px;" name="task_score[{{ $loop->index }}][ID]" value="{{ $student->sid }}">
                                  <input class="hide" id="teacherID" type="text" name="task_teacher_id" value="{{$teacheruser->id}}">
                                </div>
                                <div class="col-xs-12 col-md-6" style="padding-left: 0px;">
                                  <p class="totalScoreInner pull-left">{{$student_task_list->task_total_points}}</p>
                                </div>
                                
                              </div>
                            </td>
                            <td>
                              <select class="form-control" name="task_score[{{ $loop->index }}][status]">
                                <option value="{{$student->status or 'none'}}" selected> {{$student->status or 'none'}} </option>
                                <option value="submitted"> submitted </option>
                                <option value="excused"> excused </option>
                                <option value="incomplete"> incomplete </option>
                              </select>
                            </td>
                          </tr>
                          @empty
                            @foreach($students as $anotherStudent)
                            <tr>
                                <td>{{$anotherStudent->id}}</td>
                                <td><a style="cursor: pointer;" href="#">{{ ucwords($anotherStudent->firstname) }} {{ ucwords($anotherStudent->lastname) }}</a></td>
                                <td>
                                <div class="row">
                                    <div class="col-xs-12 col-md-6 " style="padding-right: 5px;">
                                    <input min="0" max="{{$student_task_list->task_total_points}}" tabindex="{{ $loop->index + 1 }}" class="pull-right scoreExists" type="number" style="width: 45px;" name="task_score[{{ $loop->index }}][score]" value="0" required>
                                    <input class="hide" type="text" style="width: 45px;" name="task_score[{{ $loop->index }}][name]" value="{{ $anotherStudent->lastname }}">
                                    <input class="hide" id="studentID" type="text" style="width: 45px;" name="task_score[{{ $loop->index }}][ID]" value="{{ $anotherStudent->id }}">
                                    <input class="hide" id="teacherID" type="text" name="task_teacher_id" value="{{$teacheruser->id}}">
                                    </div>
                                    <div class="col-xs-12 col-md-6" style="padding-left: 0px;">
                                    <p class="totalScoreInner pull-left">{{$student_task_list->task_total_points}}</p>
                                    </div>
                                    
                                </div>
                                </td>
                                <td>
                                <select class="form-control" name="task_score[{{ $loop->index }}][status]">
                                    <option value="none" selected> none </option>
                                    <option value="submitted"> submitted </option>
                                    <option value="excused"> excused </option>
                                    <option value="incomplete"> incomplete </option>
                                </select>
                                </td>
                            </tr>
                            @endforeach
                            @endforelse

                          
                          
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
                              <option value="0">---Student Select ---</option>
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
                              <option value="">---Select Period---</option>
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
                              <option value="">---Select Period---</option>
                            </select>
                          </div>
                        </div>
                        <div class="box-body">
                          <div class="row">
                            <div class="col-xs-12">
                              <div class="col-xs-5">Student ID:</div>
                              <div class="col-xs-7" id="studentId"></div>
                            </div>
                            <div class="col-xs-12">
                              <div class="col-xs-5">Name:</div>
                              <div class="col-xs-7" id="studentFullName"></div>
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
                                        <option value="">--Select Grade--</option>
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
                                      <option value="">--Select Grade--</option>
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
                                        <option value="">--Select Grade--</option>
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
                                            <option value="">--Select Grade--</option>
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
                            <button class="btn btn-sm btn-primary center-block" id="characeter_development_submission" type="submit">Submit</button>
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

  <!--End-->


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

    window.location.href = '/agrading/list/'+taskSubjectValue+'/'+taskGradeValue+'-'+taskSectionValue+'/tasklist/' + taskValue;
});

var yearToday = new Date().getFullYear();

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


})

$('#characeter_development_submission').on('click', function() {
  var period = $('#qualitative_period').val();
var schoolYear = $('#qualitative_school_year').val();

var strSplit = $('#studentLoader').val().split("/");


if(schoolYear == '' || period == '' || strSplit[0] == '') {
  alert('Please input Student, Period and School Year.')

  return false;
}

});

})

</script>
@endpush

@endsection