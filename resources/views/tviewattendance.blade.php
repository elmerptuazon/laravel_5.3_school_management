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
          padding: 20px;
          margin: 20px;
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

                      <div>
                        <div class="box-body" style="border: 1px solid whitesmoke;">
                          <ul id="studentListLoaded" class=" list-group">
                            <!-- Template  -->
                            <li class="studentListTemplate list-group-item">
                              <!-- <div class="row">
                                  <p class="studentid col-xs-4">##studentid##</p>
                                  <p class="studentName col-xs-4">##studentName##</p>
                                </div> -->
                              <!-- <div class=" studentid">##studenId##</div> -->
                              <!-- <div class=" studentName">##StudentName##</div> -->
                              <ul class="list-unstyled">
                                <li>
                                  <div class="box">
                                    <div class="box-body">
                                      <div class="row">
                                      <label>Grade</label>
                                        <div style=" display:inline-block;">
                                        
                                          <select id="selectGrade" class="form-control">
                                            <option value="{{$grade_clicked=='' ? '' : $grade_clicked}}-{{$section_clicked or ''}}">Current: {{$grade_clicked=='' ? '--Select ' : $grade_clicked}}-{{$section_clicked or ''}}</option>
                                            @foreach($gradesection as $grade)
                                              <option value="{{ $grade->grade }}-{{ $grade->section }}">{{ $grade->grade }}-{{$grade->section}}</option>
                                            @endforeach
                                          </select>
                                        </div>   
                                        <div style="display:inline-block;">
                                        <label>Start Date</label>
                                          <input id="startdateselect" autocomplete="off" class="datepicker" type="text" value=""/>
                                        <label>End Date</label>
                                          <input placeholder="" id="enddateselect" autocomplete="off" type="text" class="datepicker" value=""/>
                                        </div>
                                        <div style="margin-left: 20px; display:inline-block;">
                                          <button id="searchAttendance" class="btn btn-primary btn-sm">Search</button>
                                        </div>
                                        <div style="margin: 20px 0px 0px 20px; display:block;">
                                          <button class="btn btn-primary btn-sm exportBtn">Export to Excel</button>
                                        </div>  

                                        <div class="col-xs-12 tableDiv">
                                          
                                          <table class="table table-hover table-striped tableDiv" >
                                            <thead>
                                              <tr>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                              </tr>
                                              <tr id="curriculum">
                                                <th style="text-align: center"  >
                                                  <strong> Marcelli School of Antipolo</strong>
                                                </th>
                                              </tr>
                                              <tr>
                                                <th> </th>
                                              </tr>
                                              <tr>
                                                <th class="assignmentScore" style="text-align:center;">
                                                  <strong>Attendance</strong>
                                                </th>
                                              </tr>
                                              <tr>
                                                <th> </th>
                                              </tr>
                                              <tr>
                                                <th>
                                                  <strong>Grade: {{$grade_clicked=='' ? '--Select ' : $grade_clicked}}-{{$section_clicked or ''}}</strong>
                                                </th>
                                              </tr>
                                              <tr>
                                                <th>
                                                  <strong>Start Date: {{$startdate_clicked or ''}}</strong>
                                                </th>
                                              </tr>
                                              <tr>
                                                <th>
                                                  <strong>End Date: {{$enddate_clicked or ''}}</strong>
                                                </th>
                                              </tr>
                                              <tr>
                                                  <!-- <th>
                                                      <strong><i class="fa fa-arrow-left"></i></strong>
                                                  </th> -->
                                                  <th>
                                                      <strong >Month</strong>
                                                  </th>
                                                  <!-- <th>
                                                      <strong><i class="fa fa-arrow-right"></i></strong>
                                                  </th> -->
                                              </tr>
                                            </thead>
                                            <tbody id="curriculumTransmutedGrade">
                                              <tr>
                                                <table class=" table-striped table-bordered" style="display: block; overflow-x: auto;">
                                                  <thead>
                                                      <tr style="clear: both; height: max-content;">
                                                          <th></th>
                                                          @isset($month_selected)
                                                            @foreach($month_selected as $month)
                                                              <th><p class="verti">{{$month->MonthName}}<br />{{$month->MonthDay}}</p></th>
                                                            @endforeach
                                                          @endisset

                                                          @isset($total_absence)
                                                          <th><p class="verti">Total Absent</p></th>
                                                          @endisset
                                                          @isset($total_present)
                                                          <th><p class="verti">Total Present<br/><small><i>including late</i></small></p></th>
                                                          @endisset

                                                          @isset($total_late)
                                                          <th><p class="verti">Total Late</p></th>
                                                          @endisset
                                                      </tr>
                                                      
                                                  </thead>
                                                  <tbody>
                                                      @isset($grade_selected)
                                                        @foreach($grade_selected as $grade)
                                                          <tr>
                                                              <td><p style="margin:10px;">{{ ucwords($grade->firstname) }} {{ ucwords($grade->lastname) }}</p></td>
                                                                @isset($date_rearrange)
                                                                @foreach($date_rearrange as $studentId=>$val)
                                                                    @foreach($val as $MonthName=> $jval)
                                                                      @foreach($jval as $MonthDay=>$kval)
                                                                        @if($grade->id == $studentId)    
                                                                          <td><p style="text-align:center;">{{$kval}}<p></td>
                                                                        @endif
                                                                      @endforeach
                                                                    @endforeach
                                                                  @endforeach
                                                                  @endisset

                                                                  @isset($total_absence)
                                                                    @foreach($total_absence as $studentId=>$absent)
                                                                      @if($grade->id == $studentId)
                                                                        <td><p style="text-align:center;">{{$absent}}<p></td>
                                                                      @endif
                                                                    @endforeach
                                                                  @endisset

                                                                  @isset($total_present)
                                                                    @foreach($total_present as $studentId=>$present)
                                                                      @if($grade->id == $studentId)
                                                                        <td><p style="text-align:center;">{{$present}}<p></td>
                                                                      @endif
                                                                    @endforeach
                                                                  @endisset
                                                                  
                                                                  @isset($total_late)
                                                                    @foreach($total_late as $studentId=>$late)
                                                                      @if($grade->id == $studentId)
                                                                        <td><p style="text-align:center;">{{$late}}<p></td>
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

                        </div>
                      </div>
                      <!-- </div> -->



@push('scripts')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script> -->
<script src="{{asset ("/filesaver/dist/FileSaver.min.js")}}"></script>

<script>
$(document).ready(function () {

  var dates_list = {};

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
          dates_list.startdate = formatStartDate;
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
          dates_list.enddate = formatEndDate;
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


      // onclick export a file from html table to excel file.
      $(this).on('click', '.exportBtn', function () {

          var grade = $('#selectGrade').val().split('-');
          var startdate = $('#startdateselect').val();
          var enddate = $('#enddateselect').val();

          if(grade[0] == '' || startdate == '' || enddate == '') {
            alert('Please select Grade and Date.');
          } else {
            targetTable = $(this).parent().siblings('.tableDiv').html();
            // console.log(targetTable);
            // window.open('data:application/vnd.ms-excel,' + encodeURIComponent(targetTable));
            // preventDefault();
            toExcel();
          };
        
      })

      function toExcel() {
        var grade = $('#selectGrade').val();
        var startdate = $('#startdateselect').val();
        var enddate = $('#enddateselect').val();

        var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
        //name of worksheet
        tab_text = tab_text + '<x:Name>Test Sheet </x:Name>';

        tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
        tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';

        tab_text = tab_text + '<table border="1px">';
        tab_text = tab_text + $('.tableDiv').html();
        tab_text = tab_text + '</table></body></html>';

        var blob = new Blob([tab_text], {type:'application/vnd.ms-excel;charset=utf-8'});
        var fileName = grade + '[' + startdate + '][' + enddate + ']' + '.xls';
        saveAs(blob, fileName);

        // var data_type = 'data:application/vnd.ms-excel';
        // window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        // console.log($('.tableDiv').html())
      }

      $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD') + '-' + picker.endDate.format('YYYY/MM/DD'));
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('#searchAttendance').on('click', function() {
          var grade = $('#selectGrade').val().split('-');
          var startdate = $('#startdateselect').val();
          var enddate = $('#enddateselect').val();

          if(grade[0] == '' || startdate == '' || enddate == '') {
            alert('Please select Grade and Date.');
            window.location.reload();
          } else {
            window.location.href = "/view/tattendance/" + grade[0] + '-' + grade[1] + '/' + dates_list.startdate + '/' + dates_list.enddate;
          };
          
        });


    })

</script>


@endpush
@endsection