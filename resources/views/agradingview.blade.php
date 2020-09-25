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
                                        
                                        <div class="col-md-12 col-xs-12" style="margin-top:10px;">
                                            <button id="exportPDF" class="btn btn-primary btn-sm">Export to PDF</button>
                                        </div>
                                        </div>
                                        <div class="col-xs-12">
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
                                                      @foreach($students_subjects as $subject)
                                                        <th><strong>{{$subject->subject}}</strong></th>
                                                      @endforeach
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    @isset($final_scores)
                                                      @foreach($final_scores as $key=>$scores)
                                                      
                                                        
                                                      
                                                        <tr>
                                                          <td>{{$key}}</td>
                                                          @foreach($scores as $subject_score)

                                                          <td>{{$subject_score}}</td>
                                                          @endforeach
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

<script>

$(document).ready(function () {
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

});

</script>

@endpush

@endsection
