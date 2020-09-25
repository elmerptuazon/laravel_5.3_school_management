@extends('admin_template')

@section('table_assets')

<style>
  .verti {
      /* writing-mode: tb-rl; */
          /* transform: rotate(180deg); */
          /* color: blue; */
          clear: both;
          white-space: nowrap;
          /* height:250px;
          width: 35px; */
          display: inline-table;
          /* height:fit-content;*/
          padding: 5px;
          -webkit-transform:rotate(-90deg); 
          -moz-transform: rotate(-90deg); 
          -ms-transform: rotate(-90deg); 
          -o-transform: rotate(-90deg);
          transform: rotate(-90deg); 
          mso-rotate:90; 
          -webkit-transform-origin: 50% 50%; 
          -moz-transform-origin: 50% 50%; 
          -ms-transform-origin: 50% 50%; 
          -o-transform-origin: 50% 50%; 
          transform-origin: 50% 50%; 
          /* position:absolute;  */
          filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
          /* border: 1px solid black; */
          height: auto;
          width: auto;
    }
</style>

@endsection

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
    <div class="box-body" style="border: 1px solid whitesmoke;">
                          <ul id="studentListLoaded" class=" list-group" >
                            <!-- Template  -->
                            <li class="studentListTemplate list-group-item" >
                              
                           
                              <ul class="list-unstyled">
                            
                                <li>
                                  <div class="box">
                                    <div class="box-body">
                                      <div class="row">
                                      
                                      <div class="col-xs-12">
                                      
                                        <div class="col-md-3 col-xs-3">
                                        <select id="selectGrade" class="form-control" name="grade_section">
                                          <option value="{{$grade_clicked or ''}}-{{$section_clicked or ''}}">Current: {{$grade_clicked or '--Select '}}-{{$section_clicked or 'Grade--'}}</option>
                                          @foreach($grade_section as $subject)
                                            <option value="{{ $subject->grade }}-{{$subject->section}}">{{ $subject->grade }}-{{$subject->section}}</option>
                                          @endforeach
                                        </select>
                                        </div>
                                        <div class="col-md-3 col-xs-3">
                                        <select id="selectPeriod" class="form-control" name="period">
                                          <option value="{{$period_clicked or ''}}">Current: {{$period_clicked or '--Select Period--'}}</option>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                        </select>
                                        </div>
                                        <div class="col-md-3 col-xs-3">
                                        <select id="selectSubj" class="form-control" name="subject">
                                          <option value="{{$subj_clicked or ''}}">Current: {{$subj_clicked or '--Select Subject--'}}</option>
                                          @foreach($tasks_subject_list as $subject)
                                            <option value="{{ $subject->subject }}">{{ $subject->subject }}</option>
                                          @endforeach
                                        </select>
                                        </div>
                                        <div class="col-md-3 col-xs-3">
                                          <button id="searchSubmit" class="btn btn-info btn-sm">Search</button>
                                        </div>
                                    
                                        </div>
                                        <div class="col-xs-12 tableDiv">
                                          <table class="table table-hover table-striped">
                                            <thead>
                                              <tr>
                                                  <th> </th>
                                                  
                                              </tr>
                                              <tr>

                                                <th  style="text-align: center" colspan="9"><strong > Marcelli School of Antipolo</strong></th>
                                              </tr>
                                              <tr>
                                                  <th colspan="9"> </th>
                                              </tr>
                                              <tr >
                                                <th class="assignmentScore" style="text-align: center" colspan="9"><strong >List of tasks</strong></th>
                                              </tr>
                                              <tr>
                                                  <th> </th>
                                              </tr>
                                              <tr>
                                                <th><strong>Subject: {{$subj_clicked}}</strong> </th>
                                              </tr>
                                              <tr>
                                                  <th> </th>
                                              </tr>
                                              <tr>
                                                  <th><strong>Grading Term : Quarter {{$period_clicked}}</strong></th>                                                
                                              </tr>  
                                              <tr>
                                                  <th> </th>
                                              </tr>  
                                              <tr>
                                                  <th><strong>Grade : {{$grade_clicked}}-{{$section_clicked}}</strong></th>
                                              </tr>  
                                              <tr>
                                                  <th> </th>
                                              </tr>  
                                
                                            </thead>
                                             <tbody id="curriculumTransmutedGrade">
                                              <tr>
                                                <table class=" table-striped table-bordered" style="display: block; overflow-x: auto;">
                                                  <thead>
                                                    <tr style="clear: both; height: max-content;">
                                                      <th style="width: 10%; height: 255px; padding: 10px; white-space: nowrap;"><strong>Task Title</strong></th>
                                                      @isset($taskid_list)
                                                        @foreach($taskid_list as $task_title)
                                                          <th><p class="verti">{{$task_title->task_title}}</p></th>
                                                        @endforeach
                                                        <th><p class="verti">WW SUM</p></th>
                                                        <th><p class="verti">PT SUM</p></th>
                                                        <th><p class="verti">QA SUM</p></th>
                                                        <th><p class="verti">Tentative Grade</p></th>
                                                        <th><p class="verti">Transmuted Grade</p></th>
                                                      @endisset
                                                    </tr>
                                                    <tr>
                                                      <th><strong>Task Type-Percentage</strong></th>
                                                      @isset($taskid_list_with_weight)
                                                        @foreach($taskid_list_with_weight as $task_title)
                                                            <th style="text-align:center;"><strong>{{$task_title}}%</strong></th>
                                                        @endforeach
                                                        <th style="text-align:center;"><strong>WW</strong></th>
                                                        <th style="text-align:center;"><strong>PT</strong></th>
                                                        <th style="text-align:center;"><strong>QA</strong></th>
                                                        <th style="text-align:center;"><strong>Tent. Grade</strong></th>
                                                        <th style="text-align:center;"><strong>Transmuted Grade</strong></th>
                                                      @endisset
                                                    </tr>
                                                    <tr>
                                                      <th><strong>Task Total Points</strong></th>
                                                      @isset($taskid_list)
                                                        @foreach($taskid_list as $task_title)
                                                          <th style="text-align:center;"><strong>{{$task_title->task_total_points}} ({{$task_title->task_type}})</strong></th>
                                                        @endforeach
                                                      @endisset
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                    </tr>
                                                    <tr>
                                                      <th><strong>Task Total Avg(per task)</strong></th>
                                                      @isset($sum_up_task_average_scores)
                                                        @foreach($sum_up_task_average_scores as $taskid=>$val)
                                                          <th style="text-align:center;"><strong>{{$val}}</strong></th>
                                                        @endforeach
                                                      @endisset
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                   @foreach($student_id_list as $student)
                                                      <tr>
                                                      <td>{{ ucwords($student->firstname) }} {{ ucwords($student->lastname) }}</td>
                                                            
                                                       @isset($student_list_per_grade_section)
                                                              @foreach($student_list_per_grade_section as $studentid=>$task_list)
                                                                @if($studentid == $student->id)
                                                                  @foreach($task_list as $key=>$val)
                                                                  <td style="text-align:center;">{{$val}}</td>
                                                                  @endforeach
                                                                @endif
                                                              @endforeach
                                                            @endisset

                                                             @isset($student_list_grade_per_task_type)
                                                              @foreach($student_list_grade_per_task_type as $studentid=>$task_list)
                                                                @if($studentid == $student->id)
                                                                    <td style="text-align:center;">{{$task_list['WW']}}</td>
                                                                    <td style="text-align:center;">{{$task_list['PT']}}</td>
                                                                    <td style="text-align:center;">{{$task_list['QA']}}</td>
                                                                @endif
                                                              @endforeach
                                                            @endisset

                                                             @isset($student_list_for_final_grade)
                                                              @foreach($student_list_for_final_grade as $studentid=>$task_list)
                                                                @if($studentid == $student->id)
                                                                    <td style="text-align:center;">{{$task_list}}</td>
                                                                @endif
                                                              @endforeach
                                                            @endisset

                                                            @isset($transmuted_grades_list)
                                                              @foreach($transmuted_grades_list as $studentid=>$task_list)
                                                                @if($studentid == $student->id)
                                                                    <td style="text-align:center;">{{$task_list}}</td>
                                                                @endif
                                                              @endforeach
                                                            @endisset
                                                      </tr>

                                                    @endforeach
                                                    
                                                  </tbody>
                                                </table>
                                              </tr>
                                            </tbody>
                                          </table>
                                          
                                        </div>
                                        <div class="col-xs-12">
                                          <button class="btn btn-primary btn-sm exportExcel" style="width: 15%;">Export to Excel</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>  
                                </li>
                              </ul>
                            </li>
                            </ul>
                        </div>
</div>

@push('scripts')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script> -->
<script src="{{asset ("/filesaver/dist/FileSaver.min.js")}}"></script>
<script>

$(document).ready(function () {
  $('#searchSubmit').on('click', function() {
    var strPeriod = $('#selectPeriod').val();
    var strSplit = $('#selectGrade').val().split("-");
    var strSubj = $('#selectSubj').val();
    
    if(strPeriod == '' || strSplit[0] == '' || strSubj == '') {
      alert('Please select Period, Grade and Subject.')
      
    } else {
      window.location.href = '/afetch/tasks/grades/'+ strSubj + '/' + strSplit[0]+'-'+strSplit[1] + '/' + strPeriod;
    }

    
});

      $(this).on('click', '.exportExcel', function () {
        var grade = $('#selectGrade').val();
        var period = $('#selectPeriod').val();
        var subj = $('#selectSubj').val();

        if(grade == '-' || period == '' || subj == '') {
          alert('Please select Period, Grade and Subject.')
        } else {
          targetTable = $(this).parent().siblings('.tableDiv').html();
          toExcel();
        }
      })

      function toExcel() {
        var grade = $('#selectGrade').val();
        var period = $('#selectPeriod').val();
        var subj = $('#selectSubj').val();

        var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
        //name of worksheet
        tab_text = tab_text + '<x:Name>Test Sheet</x:Name>';

        tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
        tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';

        tab_text = tab_text + '<table border="10px">';
        tab_text = tab_text + $('.tableDiv').html();
        tab_text = tab_text + '</table>';

        var blob = new Blob([tab_text], {type:'application/vnd.ms-excel;charset=utf-8'});
        var fileName = grade + '[' + period + 'term]' + subj + '.xls';

        saveAs(blob, fileName);
        // window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

      }

});

</script>

@endpush

@endsection
