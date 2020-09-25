@extends('admin_template')

@section('content')


<div class="row">
    <div class="box-body" style="border: 1px solid whitesmoke;">
          @if(session()->has('error'))
            <div class="alert alert-danger taskAlert">
                {{ session()->get('error') }}
            </div>
          @elseif(session()->has('message'))
            <div class="alert alert-success taskAlert">
                {{ session()->get('message') }}
            </div>
          @endif
                          <ul id="studentListLoaded" class=" list-group" >
                            <!-- Template  -->
                            <li class="studentListTemplate list-group-item" >
                              
                           
                              <ul class="list-unstyled">
                            
                                <li>
                                  <div class="box">
                                    <div class="box-body">
                                      <div class="row">
                                      
                                      <div class="col-xs-12">
                                      
                                        <div class="col-md-4 col-xs-4">
                                        <select id="selectGrade" class="form-control" name="grade_section">
                                          <option value="{{$grade_clicked or ''}}-{{$section_clicked or ''}}">Current: {{$grade_clicked or '--Select '}}-{{$section_clicked or 'Grade--'}}</option>
                                          @foreach($grade_section as $subject)
                                            <option value="{{ $subject->grade }}-{{$subject->section}}">{{ $subject->grade }}-{{$subject->section}}</option>
                                          @endforeach
                                        </select>
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                        <select id="selectPeriod" class="form-control" name="period">
                                          <option value="{{$period_clicked or ''}}">Current: {{$period_clicked or '--Select Period--'}}</option>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                        </select>
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                          <button id="searchSubmit" class="btn btn-info btn-sm">Search</button>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                          <div class="row">
                                            <div class="col-md-2 col-xs-12" style="margin-top:10px; margin-right:0px; padding:0px;">
                                                <button id="exportPDF" class="btn btn-primary btn-sm">Export to PDF</button>
                                            </div>
                                            <div class="col-md-10 col-xs-12" style="margin-top:10px; padding:0px;">
                                                <button id="exportExcel" class="btn btn-primary btn-sm">Export to Excel</button>
                                            </div>
                                          </div>
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
                                                <th class="assignmentScore" style="text-align: center" colspan="9"><strong >Summary of Grades - Transmuted Grade</strong></th>
                                              </tr>
                                              <tr>
                                                  <th> </th>
                                              </tr>
                                              <tr>
                                                <th><strong>Academic Year : 2019</strong> </th>
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
                                                  <th><strong>Grade : {{$grade_clicked}}</strong></th>
                                              </tr>  
                                              <tr>
                                                  <th> </th>
                                              </tr>  
                                              <tr>
                                                <th><strong>Class: Grade {{$grade_clicked}}</strong></th>
                                              </tr>  
                                            </thead>
                                             <tbody id="curriculumTransmutedGrade">
                                              <tr>
                                                <table class="table table-striped">
                                                  <thead>
                                                    <tr>
                                                      <th><strong>Name of Students</strong></th>
                                                      @foreach($subject_list_grade_section as $subject)
                                                        <th><strong>{{$subject->subject}}</strong></th>  
                                                      @endforeach
                                                      <th><strong>Quarterly Remark</strong></th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                  @isset($student_with_subject_list)
                                                    @foreach($student_with_subject_list as $student_name=>$subject)
                                                    <tr>
                                                          <td>{{ucwords($student_name)}}</td>
                                                    @foreach($subject as $score)
                                                      <td>{{$score}}</td>
                                                    @endforeach

                                                    @isset($general_list_value)
                                                      @foreach($general_list_value as $gen_avg_studentname=>$gen_avg)
                                                        @if($gen_avg_studentname == $student_name)
                                                          <td>{{$gen_avg}} | {{round($gen_avg)}}</td>
                                                        @endif
                                                      @endforeach
                                                    @endisset

                                                    </tr>
                                                    @endforeach
                                                  @endisset

                                                  </tbody>
                                                </table>
                                              </tr>
                                            </tbody>
                                          </table>
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
<script src="{{asset ("/filesaver/dist/FileSaver.min.js")}}"></script>
<script>

$(document).ready(function () {
  $('div.taskAlert').fadeOut(3000);

  $('#searchSubmit').on('click', function() {
    var strPeriod = $('#selectPeriod').val();
    var strSplit = $('#selectGrade').val().split("-");

    if(strPeriod == '' || strSplit == '') {
      alert('Please select Grade and Period.')
      
    } else {
      window.location.href = '/afetch/final/view/grades/'+strSplit[0]+'-'+strSplit[1] + '/' + strPeriod;
    }

    
});

$('#exportPDF').on('click', function() {
  var strSplit = $('#selectGrade').val().split("-");

  if(strSplit[0] == '') {
      alert('Please select a Grade.')
      
    } else {
      window.location.href = '/afetch//grades/pdf/'+strSplit[0]+'-'+strSplit[1]
    }

})

$(this).on('click', '#exportExcel', function () {
        
        targetTable = $(this).parent().siblings('.tableDiv').html();
        toExcel();
      
    })

    function toExcel() {
      var grade = "{{$grade_clicked}}";
      var section = "{{$section_clicked}}";
      var period = "{{$period_clicked}}";
      

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
      var fileName = grade + '-' + section + '[' + period + 'term].xls';

      saveAs(blob, fileName);
      // window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

    }

});

</script>

@endpush

@endsection
