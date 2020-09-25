@extends('admin_template')

@section('table_assets')
<style>
    .tableWithBorders, .tableWithBordersTH, .tableWithBordersTD{
        border: 2px solid black;
        border-collapse: collapse;
        padding: 2px 5px 2px 5px;
    }

    .centerValue {
        text-align:center;
    }

    
    .new-page {
        page-break-after: always;
    }
        

</style>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
    padding: 0;
}

div{
        page-break-inside: avoid;
    }
.new-page {
    transform: scale(1);
    page-break-after: always;
}
</style>
@endsection

@section('content')
    
    <button class="btn btn-primary" type="button" id="pdf_click">
    <div style="display:inline-block;" class="overlay waitingPrint hide">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    Save as PDF</button>
    
    <div id="content" class="content" style="font-family: Calibri, sans-serif; font-size: 11px;">
        @foreach($student_name_list as $student_name_key => $student_name_val)

        <div class="new-page" style="padding-bottom:50px;">
            <div style="margin-top=0px; padding-top=0px; margin-bottom:3%;">
                <div style="display:inline-block; width:20%; vertical-align: bottom;">
                <img style="" src={{ asset ("uploads/marcelli-logo_75x75.png") }} />
                </div>
                <div style="display:inline-block; width:50%; margin:20px 0px 0px 0px; padding:0px; vertical-align: bottom;">
                    <p style="text-align: center;"><strong>Marcelli School of Antipolo</strong></p>
                    <p style="text-align: center;"><strong> No. 6 Marigman Street,Brgy San Roque Antipolo City, 1870 Rizal</strong></p>
                    <p style="text-align: center;"><strong>Report Card</strong></p>
                </div>
            </div>

            <div style="border-top: 1.5px solid black; border-bottom: 1.5px solid black; margin-top:-20px; padding: 0px;">
                <table style="width:100%">
                    <tr>
                        <th>Student</th>
                        <td><strong>{{ucwords($student_name_key)}}</strong></td>
                        <th></th>
                        <td></td>
                        <th><strong>Academic</strong></th>
                        <td><strong>2019-2020</strong></td>
                    </tr>
                    <tr>
                        <th>Grade</th>
                        <td><strong>{{$grade_clicked}}-{{$section_clicked}}</strong></td>
                        <th></th>
                        <td></td>
                        <th>Term</th>
                        <td><strong>All Quarters</strong></td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        @isset($age_list)
                            @foreach($age_list as $studentid=>$age)
                                @if($studentid == $student_name_val)
                                    <td><strong>{{$age}}</strong></td>
                                @endif
                            @endforeach
                        @endisset
                        <th>LRN</th>
                        @isset($lrn_list)
                            @foreach($lrn_list as $studentid=>$lrn)
                                @if($studentid == $student_name_val)
                                    <td><strong>{{$lrn}}</strong></td>
                                @endif
                            @endforeach
                        @endisset
                        <!-- <td><strong>402910150056</strong></td> -->
                        <th>Gender</th>
                        @foreach($student_list_by_user as $gender)
                            @if($gender->id == $student_name_val)
                                <td><strong>{{ucwords($gender->gender)}}</strong></td>
                            @endif
                        @endforeach
                    </tr>
                </table>  
            </div>
        <div style="padding-top:8px">
        <table class="tableWithBorders" style="width:100%;">
          <thead class="headerFontSize">
            <tr>
              <th style="font-size:13px; text-indent: 1px;" class="tableWithBordersTH">CURRICULAR SUBJECTS</th>
              <th class="tableWithBordersTH centerValue">Q1</th>
              <th class="tableWithBordersTH centerValue">Q2</th>
              <th class="tableWithBordersTH centerValue">Q3</th>
              <th class="tableWithBordersTH centerValue">Q4</th>
              <th class="tableWithBordersTH centerValue">Final</th>
            </tr>
          </thead>
          <tbody>

          @foreach($students_subjects as $subject)
            <tr>
                <td style="text-indent: 5px;" class="tableWithBordersTD"><small>{{strtoupper($subject->subject)}}</small></td>
                @isset($list_of_final_grade)
                @foreach($list_of_final_grade['final_grade'] as $studentsid=>$subject_list)
                    @if($studentsid == $student_name_val)
                        @foreach($subject_list as $subject_key=>$val)
                            @if($subject_key == $subject->subject)
                                @for($i=1;$i<=5;$i++)
                                    @if($val[$i] == 60)
                                        <td class="tableWithBordersTD centerValue"></td>
                                    @else
                                        <td class="tableWithBordersTD centerValue">{{$val[$i]}}</td>
                                    @endif
                                @endfor
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @endisset
            </tr>
          @endforeach
            <tr>
                <td style="text-indent: 5px;" class="tableWithBordersTD"><small>{{strtoupper('General Average')}}</small></td>
                @foreach($list_of_final_grade['general_average'] as $studentid=>$task_average)
                    @if($studentid == $student_name_val)
                        @for($i=1; $i<=5; $i++)
                            @if(isset($task_average[$i]))
                                @if($task_average[$i] == 60)
                                    <td class="tableWithBordersTD centerValue"></td>
                                @else
                                    <td class="tableWithBordersTD centerValue">{{$task_average[$i]}}</td>
                                @endif
                            @else
                                <td class="tableWithBordersTD centerValue">{{$transmuted_general_average[$studentid]['final_general_average']}}</td>
                            @endif
                        @endfor
                    @endif
                @endforeach
            </tr>
          </tbody>
        </table>
        </div>
        <div style="padding-top:8px">
            <table class="tableWithBorders" style="width:100%;">
            <thead class="headerFontSize">
                <tr>
                <th style="font-size:13px; text-indent: 1px;" class="tableWithBordersTH">GRADE SCALE</th>
                <th class="tableWithBordersTH centerValue">A</th>
                <th class="tableWithBordersTH centerValue">B+</th>
                <th class="tableWithBordersTH centerValue">B</th>
                <th class="tableWithBordersTH centerValue">B-</th>
                <th class="tableWithBordersTH centerValue">C+</th>
                <th class="tableWithBordersTH centerValue">C</th>
                <th class="tableWithBordersTH centerValue">C-</th>
                <th class="tableWithBordersTH centerValue">D</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td class="tableWithBordersTD">Description</td>
                <td class="tableWithBordersTD centerValue">Excellent<br /> 99-100</td>
                <td class="tableWithBordersTD centerValue">Highly<br /> Satisfactory<br /> 95-98</td>
                <td class="tableWithBordersTD centerValue">Satisfactory<br /> 90-94</td>
                <td class="tableWithBordersTD centerValue">Moderately<br /> Satisfactory<br /> 85-89</td>
                <td class="tableWithBordersTD centerValue">Fairly<br /> Satisfactory<br /> 80-84</td>
                <td class="tableWithBordersTD centerValue">Passed<br /> 75-79</td>
                <td class="tableWithBordersTD centerValue">Needs<br /> Improvement<br /> 70-74</td>
                <td class="tableWithBordersTD centerValue">Poor<br /> 65-69</td>
                </tr>
            </tbody>
            </table>
        </div>
        <div style="padding-top:8px">
        <table class="tableWithBorders" style="width:100%;">
            <thead>
              <tr>
                <th style="font-size:13px; text-indent: 1px;" class="tableWithBordersTH">Character Development</th>
                <th class="tableWithBordersTH centerValue">Q1</th>
                <th class="tableWithBordersTH centerValue">Q2</th>
                <th class="tableWithBordersTH centerValue">Q3</th>
                <th class="tableWithBordersTH centerValue">Q4</th>
              </tr>
            </thead>
            <tbody class="bodyFontStyle">
            @foreach($task_qualitative_score_array as $studentid=>$qualitative_score_list)
                @if($studentid== $student_name_val)
                @foreach($qualitative_score_list as $category_group_name=>$category_list_name)
                <tr>
                    <td style="text-indent: 5px;" class="tableWithBordersTD"><strong>{{str_replace('_',' ',$category_group_name)}}</strong></td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    </tr>
                    @foreach($category_list_name as $category_name=>$val)
                    <tr><td style="text-indent: 30px;" class="tableWithBordersTD">{{$category_name}}</td>
                    @for($i=1;$i<=4;$i++)
                            <td class="tableWithBordersTD centerValue">{{$val[$i]}}</td>
                    @endfor
                    @endforeach
                    </tr>
                @endforeach
                @endif
            @endforeach
            </tbody>
        </table>
        </div>
        <div style="padding-top:8px;" class="extendBottom">
        <table class="tableWithBorders" style="width:100%;">
            <thead>
              <tr>
                <th style="font-size:13px; text-indent: 1px;" class="tableWithBordersTH"><strong>Attendance Record</strong></th>
                
                <th class="tableWithBordersTH centerValue"><strong>Jul</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Aug</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Sep</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Oct</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Nov</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Dec</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Jan</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Feb</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Mar</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Apr</strong></th>
                <th class="tableWithBordersTH centerValue"><strong>Total</strong></th>
              </tr>
            </thead>
            <tbody class="bodyFontStyle">
              <tr>
                <td class="tableWithBordersTD">Days of School</td>
                @isset($total_attendance_currentyear)

                    @foreach($total_attendance_currentyear as $studentid=>$present)
                        @if($studentid == $student_name_key)
                            @for($i = 7; $i<=12; $i++)
                                <td class="tableWithBordersTD centerValue">{{$present[$i][0]->student_total}}</td>
                            @endfor
                        @endif
                    @endforeach

                    @foreach($total_attendance_nextyear as $studentid=>$present)
                        @if($studentid == $student_name_key)
                            @for($i = 1; $i<=4; $i++)
                            <td class="tableWithBordersTD centerValue">{{$present[$i][0]->student_total}}</td>
                            @endfor
                        @endif
                    @endforeach

                    @foreach($total_days_currentyear as $studentid=>$present)
                        @if($studentid == $student_name_key)
                        <td class="tableWithBordersTD centerValue">{{$present[0]->student_total}}</td>
                        @endif
                    @endforeach

                @endisset
                </tr>
              <tr>
                <td class="tableWithBordersTD">Days Present</td>
                @isset($attendance_list_present_currentyear)

                    @foreach($attendance_list_present_currentyear as $studentid=>$present)
                        @if($studentid == $student_name_key)
                            @for($i = 7; $i<=12; $i++)
                                <td class="tableWithBordersTD centerValue">{{$present[$i][0]->student_present}}</td>
                            @endfor
                        @endif
                    @endforeach

                    @foreach($attendance_list_present_nextyear as $studentid=>$present)
                        @if($studentid == $student_name_key)
                            @for($i = 1; $i<=4; $i++)
                            <td class="tableWithBordersTD centerValue">{{$present[$i][0]->student_present}}</td>
                            @endfor
                        @endif
                    @endforeach

                    @foreach($total_present_currentyear as $studentid=>$present)
                        @if($studentid == $student_name_key)
                        <td class="tableWithBordersTD centerValue">{{$present[0]->student_present}}</td>
                        @endif
                    @endforeach

                @endisset
                </tr>
              <tr>
                <td class="tableWithBordersTD">Days Absent</td>
                @isset($attendance_list_absent_currentyear)

                    @foreach($attendance_list_absent_currentyear as $studentid=>$absent)
                        @if($studentid == $student_name_key)
                            @for($i = 7; $i<=12; $i++)
                                <td class="tableWithBordersTD centerValue">{{$absent[$i][0]->student_absent}}</td>
                            @endfor
                        @endif
                    @endforeach

                    @foreach($attendance_list_absent_nextyear as $studentid=>$absent)
                        @if($studentid == $student_name_key)
                            @for($i = 1; $i<=4; $i++)
                            <td class="tableWithBordersTD centerValue">{{$absent[$i][0]->student_absent}}</td>
                            @endfor
                        @endif
                    @endforeach

                    @foreach($total_absent_currentyear as $studentid=>$absent)
                        @if($studentid == $student_name_key)
                        <td class="tableWithBordersTD centerValue">{{$absent[0]->student_absent}}</td>
                        @endif
                    @endforeach

                @endisset
                </tr>
            </tbody>
        </table>
        </div>
        </div>
        {{-- END OF STUDENT LOOP--}}

        <div class=newpage>&nbsp;</div>
        @endforeach
        
        
        
    </div>
    @push('scripts')
        <!-- 
        <script src="{{asset ("/filesaver/dist/FileSaver.min.js")}}"></script> -->
        <script src="{{asset ("/jquery_print/dist/jQuery.print.min.js")}}"></script>
        
        <script>
            $( document ).ready(function() {

                var grade = "{{$grade_clicked}}";
                var section = "{{$section_clicked}}";
                
                $('#pdf_click').on('click', function() {
                    
                    $('.waitingPrint').removeClass('hide');
                    setTimeout(
                        function() {
                            $('.waitingPrint').addClass('hide');
                            $.print('#content');
                        
                            }, 3000
                        );
                    
                })
            
            })
        </script>
    @endpush

@endsection