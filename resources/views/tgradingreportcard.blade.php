@extends('admin_template')

@section('table_assets')
<style>

@media 
only screen and (max-width: 720px),(min-device-width: 360px) and (max-device-width: 1024px){
  .table-inner{
    table-layout: fixed;
    min-width:1390px;
  }
  .table-outter{
    overflow-x: auto;
    border: 1px solid gray;
    width: auto;
    margin-left: 10px;
    margin-right: 10px;
  }
  .contact{
    float: left;
  } 
  .address{
    float: left;
  }
  /* .schoolLogo{
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: auto; */
    /* width: 25%; */
  /* } */
  .counsle{
    float: left;
  }
  .studentInfo, .sy{
    margin-left: 15px;
    clear: both;
  }
}
/* @media end.. */

img{
  display: block;
  margin-left: auto;
  margin-right: auto;
  /* width: 50% */
}
th{
text-align: center;
}
td{
text-align: center;
}
tr.trBody:nth-child(even), tr.gradingChart:nth-child(even), tr.commentsChart:nth-child(even){
background-color: #f2f2f2;
}         
/* .studentInfo{
float: right;
} */
.studNfo{
margin: 0px;
padding: 0px;
}     
.genderStyle{
  margin-left: 0px;
  padding-left: 29px;
}
.genderTypeStyle{
  padding: 0px;
  padding-left: 21px;
}
table{
  width: 100%;
  padding: 0px;
}
table, tr, td,{
  border: 1px solid black;
  font-size: 12px;

}
.bodyFontStyle{
  font-size: x-small;
}
.currSubj{
  float: left;
  padding-top: 5px;
  padding-bottom: 5px;
  padding-left: 15px;
}
.rcTableStyle, tr{
  border: 1px solid black;
  margin-top: 10px;
}

.customAttendancePageBreaker {
  page-break-inside: avoid;
}

.currSubjBorder, .currSubjHeader{
  border: 1px solid black;
}
.currSubjHeader{
  width: 15%;
}
.currSubjIndent{
  text-indent: 10px;
}
@media print{
    .selectStudent {
        display:none;
    }

  /* *{
    margin: 0px important!;
    padding: 0px important!;
  } */
  #rcTableStyle {
     transform: scale(.7); 
    }
  #informationColumn{
    /* width: 50%; */
    display: inline-block;
    /* padding: 0px;
    margin: 0px; */
    /* height: 90px; */
  }

  #reportCardPrintBtn{
    display: none;
  }
 
  .genderStyle{
    margin-left: 0px;
    padding-left: 33px;
  }
  .genderTypeStyle{
    padding: 0px;
    padding-left: 15px;
  }
 
  table{
    width: 100%;
  }
  .headerFontSize{
    font-size: x-small;
  }
  .box-header{
    padding: 0px 10px;
  }
  table, th, td, tr{
    padding: 0px important!;
    margin: 0px important!;
  }
}
  



</style>

@endsection

@section('content')
          @if(session()->has('error'))
            <div class="alert alert-danger taskAlert">
                {{ session()->get('error') }}
            </div>
          @elseif(session()->has('message'))
            <div class="alert alert-success taskAlert">
                {{ session()->get('message') }}
            </div>
          @endif
<div class="tab-pane" id="studentReportCard">
<div class="row selectStudent">
        <div class="col-md-4 col-xs-4">
            <label>Select Student</label>
        </div>
        <div class="col-md-4 col-xs-4">
            <form>
            <select id="selectGrade" class="form-control">
            <option value="{{$studentid_clicked or ''}}-{{$grade_clicked or ''}}-{{$section_clicked or ''}}">Current: {{ ucwords($student_firstname) }} {{ ucwords($student_lastname) }}</option>
                @foreach($student_list_grade as $grade)
                    <optgroup label="{{$grade->new_grade}}-{{$grade->new_section}}">
                        @foreach($student_name_list as $student)
                            @if($student->grade == $grade->new_grade && $student->section == $grade->new_section)
                                <option value="{{$student->id}}-{{$grade->new_grade}}-{{$grade->new_section}}">{{ ucwords($student->firstname) }} {{ ucwords($student->lastname) }}</option>
                            @endif
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            </form>
        </div>

</div>
                        <div class="row">
                            
                            <!-- <div class="rotateTargetHeader"> -->
                            <!-- <div id="schoolLogo">
                              <img class="schoolLogo"src="img/marcelli-logo.png"alt="schoolLogo">
                            </div> -->
                            <div id="informationColumn">
                              <div class="box-header">
                                <div class="col-md-12">
                                  
                                  <div id="schoolLogo" class="col-md-1 col-xs-3"> 
                                    <img style="margin-top:20px;" src={{ asset ("uploads/marcelli-logo_75x75.png") }} />
                                  </div>
                                  <div class="col-md-9 col-xs-9">
                                    <h4 class="schoolBoard col-xs-12" style="text-align: center;">Marcelli School of Antipolo</h4>
                                    <h4 class="schoolAddress col-xs-12" style="text-align: center;"> No. 6 Marigman Street,Brgy San Roque Antipolo City, 1870 Rizal</h4>
                                    <h4 class="schoolBoard col-xs-12" style="text-align: center;">Report Card</h4>
                                  </div>
                                  
                                </div>
                                <div class="col-xs-12" style="border-top: 1.5px solid black; padding: 0px;">

                                  <div class="col-xs-6 position-left" style="padding: 0px;"> 
                                    <div class="col-xs-12">
                                      <div class="col-xs-3" style="padding-left: 0px;">Student</div>
                                      <div class="col-xs-9 studNfo">{{ ucwords($student_firstname) }} {{ ucwords($student_lastname) }}</div>
                                    </div>
                                    <div class="col-xs-12">
                                      <div class="col-xs-3" style="padding-left: 0px;">Grade</div>
                                      <div class="col-xs-9 studNfo">Grade {{$grade_clicked or ''}}-{{$section_clicked or ''}}</div>
                                    </div>
                                  </div>

                                  <div class="col-xs-6">
                                    <div class="col-xs-12">
                                      <div class="col-xs-offset-5 col-xs-3" style="padding: 0px">Academic</div>
                                      <div class="col-xs-offset-1 col-xs-3" style="padding: 0px">2019-2020</div>
                                    </div>
                                    <!-- <div class="col-xs-12" >
                                      <div class="col-xs-offset-5 col-xs-3" style="padding: 0px">Term</div>
                                      <div class="col-xs-offset-1 col-xs-3" style="padding: 0px">Quarter 4</div>
                                    </div> -->
                                  </div>

                                  <div class="col-xs-12" style="border-bottom: 1.5px solid black;padding: 0px;">
                                    <div class="col-xs-4 ageDiv">
                                      <div class="col-xs-4" style="padding: 0px;">Age</div>
                                      <div class="col-xs-8" style="padding: 0px; padding-left: 15px;">12</div>
                                    </div>
                                    <div class="col-xs-4 lrnDiv">
                                      <div class="col-xs-6">LRN</div>
                                      <div class="col-xs-6">402910150056</div>
                                    </div>
                                    <div class="col-xs-4 genderDiv">
                                      <div class="col-xs-offset-2 col-xs-2 genderStyle" >Gender</div>
                                      <div class="col-xs-offset-4 col-xs-3 genderTypeStyle">{{$student_gender or ''}}</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-12">
                            <table class="table-hover table-striped rcTableStyle" >
                              <thead class="headerFontSize">
                                <tr>
                                  <th class="currSubj">CURRICULAR SUBJECTS</th>
                                  <th class="currSubjHeader ">Q1</th>
                                  <th class="currSubjHeader ">Q2</th>
                                  <th class="currSubjHeader ">Q3</th>
                                  <th class="currSubjHeader ">Q4</th>
                                  <th class="currSubjHeader ">Final</th>
                                </tr>
                              </thead>
                              <tbody class="bodyFontStyle">
                              @isset($transmuted_final_grades)
                                @foreach($transmuted_final_grades as $subject=>$list)
                                <tr>
                                    <td class="currSubj"><small>{{strtoupper($subject)}}</small></td>
                                @for($i = 1; $i<=5; $i++)
                                    @if($list[$i] == 60)
                                    <td class="currSubjBorder"></td>
                                    @else
                                    <td class="currSubjBorder">{{$list[$i]}}</td>
                                    @endif
                                @endfor
                                </tr>
                                @endforeach
                              @endisset

                              <tr>
                                      <td class="currSubj"><small>{{strtoupper('General Average')}}</small></td>
                              @isset($transmuted_general_average)
                                @foreach($transmuted_general_average as $final)
                                  @if($final == 60)
                                    <td class="currSubjBorder"></td>
                                  @else
                                    <td class="currSubjBorder">{{$final}}</td>
                                  @endif
                                @endforeach
                              <td class="currSubjBorder">{{$final_general_average}}</td>
                              @endisset
                              </tr>
                              
                              </tbody>
                            </table>

                            <table class="rcTableStyle table-striped">
                              <thead class="headerFontSize">
                                <tr>
                                  <th>GRADE SCALE</th>
                                  <th class="currSubjBorder">A</th>
                                  <th class="currSubjBorder">B+</th>
                                  <th class="currSubjBorder">B</th>
                                  <th class="currSubjBorder">B-</th>
                                  <th class="currSubjBorder">C+</th>
                                  <th class="currSubjBorder">C</th>
                                  <th class="currSubjBorder">C-</th>
                                  <th class="currSubjBorder">D</th>
                                </tr>
                              </thead>
                              <tbody class="bodyFontStyle">
                                <tr>
                                  <td class="currSubjBorder">Description</td>
                                  <td class="currSubjBorder">Excellent<br /> 99-100</td>
                                  <td class="currSubjBorder">Highly<br /> Satisfactory<br /> 95-98</td>
                                  <td class="currSubjBorder">Satisfactory<br /> 90-94</td>
                                  <td class="currSubjBorder">Moderately<br /> Satisfactory<br /> 85-89</td>
                                  <td class="currSubjBorder">Fairly<br /> Satisfactory<br /> 80-84</td>
                                  <td class="currSubjBorder">Passed<br /> 75-79</td>
                                  <td class="currSubjBorder">Needs<br /> Improvement<br /> 70-74</td>
                                  <td class="currSubjBorder">Poor<br /> 65-69</td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="rcTableStyle">
                                <thead class="headerFontSize">
                                  <tr>
                                    <th class="currSubj">Character Development</th>
                                    <th class="currSubjHeader">Q1</th>
                                    <th class="currSubjHeader">Q2</th>
                                    <th class="currSubjHeader">Q3</th>
                                    <th class="currSubjHeader">Q4</th>
                                  </tr>
                                </thead>
                                <tbody class="bodyFontStyle">
                                    @foreach($task_qualitative_category as $category)
                                        <tr>
                                            <td class="currSubj"><strong>{{str_replace('_',' ',$category->category)}}</strong></td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                        </tr>
                                        @foreach($task_qualitative_types as $qualitative_types)
                                            @if($category->category == $qualitative_types->category)
                                                <tr>
                                                    <td class="currSubj currSubjIndent">{{$qualitative_types->type_name}}</td>
                                                    @isset($task_qualitative_score_array)
                                                    @foreach($task_qualitative_score_array as $student_id_key=>$student_period)
                                                        @foreach($student_period as $key_qid=>$val_period)
                                                          @foreach($val_period as $key_period=>$score)
                                                            @foreach($period_list_arr as $period)
                                                              @if($studentid_clicked == $student_id_key)
                                                                @if($qualitative_types->id == $key_qid)
                                                                  @if($period == $key_period)
                                                                    <td class="currSubjBorder currSubjIndent">{{$score}}</td>
                                                                  @endif
                                                                @endif
                                                              @endif
                                                            @endforeach
                                                          @endforeach
                                                        @endforeach
                                                    @endforeach
                                                    @endisset
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                  

                                </tbody>
                            </table>
                            <table class="rcTableStyle customAttendancePageBreaker">
                                <thead>
                                  <tr class="headerFontSize">
                                    <th class="headerFontSize currSubj"><strong>Attendance Record</strong></th>
                                    @isset($month_name_list_sorted)
                                      @foreach($month_name_list_sorted as $month_name)
                                        <th class="headerFontSize currSubjBorder"><strong>{{$month_name}}</strong></th>
                                      @endforeach
                                    @endisset
                                    
                                    <th class="headerFontSize currSubjBorder"><strong>Total</strong></th>
                                  </tr>
                                </thead>
                                <tbody class="bodyFontStyle">
                                  <tr>
                                    <td class="currSubj">Days of School</td>
                                    @isset($total_attendance)
                                      @foreach($month_number_list_sorted as $month_no)
                                        @foreach($total_attendance[$studentid_clicked][$month_no] as $attendance)
                                          <td class="currSubjBorder">{{$attendance->student_total}}</td>
                                        @endforeach
                                      @endforeach
                                    @endisset
                                    

                                    @isset($total_days_currentyear)
                                      @foreach($total_days_currentyear as $total_days)
                                        <td class="currSubjBorder">{{$total_days[0]->student_total}}</td>
                                      @endforeach
                                    @endisset
                                  </tr>
                                  <tr>
                                    <td class="currSubj">Days Present</td>
                                    @isset($total_present)
                                      @foreach($month_number_list_sorted as $month_no)
                                        @foreach($total_present[$studentid_clicked][$month_no] as $attendance)
                                          <td class="currSubjBorder">{{$attendance->student_total}}</td>
                                        @endforeach
                                      @endforeach
                                    @endisset

                                    @isset($total_present_currentyear)
                                      @foreach($total_present_currentyear as $total_days)
                                        <td class="currSubjBorder">{{$total_days[0]->student_present}}</td>
                                      @endforeach
                                    @endisset
                                    
                                  </tr>
                                  <tr>
                                    <td class="currSubj">Days Absent</td>
                                    @isset($total_absent)
                                      @foreach($month_number_list_sorted as $month_no)
                                        @foreach($total_absent[$studentid_clicked][$month_no] as $attendance)
                                          <td class="currSubjBorder">{{$attendance->student_total}}</td>
                                        @endforeach
                                      @endforeach
                                    @endisset

                                    @isset($total_absent_currentyear)
                                      @foreach($total_absent_currentyear as $total_days)
                                        <td class="currSubjBorder">{{$total_days[0]->student_absent}}</td>
                                      @endforeach
                                    @endisset
                                  </tr>
                                </tbody>
                            </table>
                          </div>
                        </div>
                        @if(Auth::user()->type == 'a')
                        <div style="text-align: center; margin-top: 15px;">
                        
                        <button class="btn btn-primary btn-sm" id="reportCardPrintBtn" style="width: 15%; font-size:15px;">
                        <div style="display:inline-block;" class="overlay waitingPrint hide">
                          <i class="fa fa-refresh fa-spin"></i>
                        </div>
                        Print</button>
                        
                        </div>
                        @endif
                      </div>

@push('scripts')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script> -->
<script src="{{asset ("/jquery_print/dist/jQuery.print.min.js")}}"></script>
<script>

$(document).ready(function () {
    $('div.taskAlert').fadeOut(3000);
    $('#selectGrade').on('change', function() {
        var taskValue = $(this).val();
        var strSplit = taskValue.split('-');
        
        window.location.href = '/fetch/final/view/reportcard/'+strSplit[1] + '-' + strSplit[2] + '/' + strSplit[0];
    });

    $('#reportCardPrintBtn').on('click',function(){
        // alert('print');`
        $('.waitingPrint').removeClass('hide');
        setTimeout(
          function() {
            $('.waitingPrint').addClass('hide');
            $.print('#studentReportCard');
           
            }, 3000
          );
          
        
    })

});

</script>

@endpush

@endsection
