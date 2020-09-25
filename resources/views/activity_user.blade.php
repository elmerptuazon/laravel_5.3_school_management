@extends('admin_template')

@section('content')

<div class="row">
                <div class="col-xs-8">
                    <div class="col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Parents and Student Log</h3>
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

                            </div>
                            <div class="box-body">
                                <style>
                                    .logView{
                                        padding: 10px;
                                        text-align: center;
                                    }
                                    .headerLog{
                                        padding: 10px;
                                        text-align: center;
                                    }
                                    /* .tableDivision{
                                    
                                    } */
                                    .logTable{
                                        width: 100%;
                                        white-space: nowrap;
                                    
                                    }
                                    @media screen and (max-width: 600px){
                                        .logTable{
                                            white-space: nowrap;
                                            width: 100%;
                                            overflow-x: scroll;
                                            display: block;
                                        }
                                    }
                                    .btn-box-tool{
                                        float: right;
                                    }
                                </style>
                                <div class="col-xs-12 col-md-12">
                                    <table class="table-bordered table-striped logTable">
                                        <thead>
                                            <tr>
                                                <th class="headerLog">User Name</th>
                                                <th class="headerLog">Child</th>
                                                <th class="headerLog">Activity</th>
                                                <th class="headerLog">Date Log</th>
                                                <th class="headerLog">Device Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($parent_data_set)
                                                @foreach($parent_data_set as $parent)
                                                <tr class="teacherTemplateSample">
                                                    <td class="logView">{{ucwords($parent['parent_name'])}}</td>
                                                    <td class="logView">{{ucwords($parent['student_firstname'])}} {{ucwords($parent['student_lastname'])}}</td>
                                                    <td class="logView">{{$parent['activity']}}</td>
                                                    <td class="logView">{{$parent['date']}}</td>
                                                    <td class="logView">{{$parent['device_type']}}</td>
                                                </tr>
                                                @endforeach
                                            @endisset
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="box-footer"></div>                        
                        </div>
                    </div>
                    <!-- Student/Teacher Log -->
                    <div class="col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Teacher Log</h3>
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table-bordered table-striped logTable">
                                        <tr>
                                            <th class="headerLog">User Name</th>
                                            <th class="headerLog">Activity</th>
                                            <th class="headerLog">Date Log</th>
                                            <th class="headerLog">Device Type</th>
                                        </tr>
                                            @isset($teacher_data_set)
                                                @foreach($teacher_data_set as $teacher)
                                                <tr class="teacherTemplateSample">
                                                    <td class="logView">{{ucwords($teacher['teacher_name'])}}</td>
                                                    <td class="logView">{{$teacher['activity']}}</td>
                                                    <td class="logView">{{$teacher['date']}}</td>
                                                    <td class="logView">{{$teacher['device_type']}}</td>
                                                </tr>
                                                @endforeach
                                            @endisset
                                    </table>
                                </div>
                            </div>
                            <div class="box-footer"></div>                        
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="col-xs-12">
                            <div class="box box-warning">
                                <style>
                                    .fromDate, .toDate{
                                        width: 75%;
                                        padding: 0px 5px;
                                        margin-left: 5px;
                                    }
                                    .buttonPosition{
                                        text-align: center;
                                        margin-top: 10px;   
                                    }
                                </style>
                                <div class="box-header">
                                    <!-- date-range start and end -->
                                    <h4>Total Visits</h4>
                                    <div class="row">
                                        <div class="col-xs-6">
                                        <label>Start  
                                            @if(isset($startdate))
                                            <input type="text" id="startdate" data-provide="datepicker" placeholder="{{$startdate}}" class="fromDate">
                                            @else
                                                <input type="text" id="startdate" data-provide="datepicker" class="fromDate">
                                            @endif
                                            
                                            </label> 
                                        </div>
                                        <div class="col-xs-6">
                                            <label>End  
                                                @if(isset($enddate))
                                                    <input type="text" id="enddate"  data-provide="datepicker" placeholder="{{$enddate}}" class="toDate">
                                                @else
                                                    <input type="text" id="enddate"  data-provide="datepicker" class="toDate">
                                                @endif
                                            
                                            </label>
                                        </div>
                                        <div class="col-xs-12 buttonPosition">
                                            <button class="btn btn-primary btn-sm" id="setDateButton" disabled>Set Date</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Last Visited Page</th>
                                                <th>Totals</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @isset($activity_totals)
                                            @foreach($activity_totals as $key=>$activity)
                                            <tr>
                                                <td>{{$key}}</td>
                                                @isset($activity)
                                                    <td>{{$activity}}<td>
                                                @endisset
                                            </tr>
                                            @endforeach
                                        @endisset
                                            <!-- <tr>
                                                <td>Dashboard</td>
                                                @isset($dashboard)
                                                    <td>{{$dashboard}}<td>
                                                @endisset
                                            </tr>
                                            <tr>
                                                <td>Replyslip</td>
                                                @isset($replyslip)
                                                    <td>{{$replyslip}}<td>
                                                @endisset
                                            </tr>
                                            <tr>
                                                <td>Notification</td>
                                                @isset($notification)
                                                    <td>{{$notification}}<td>
                                                @endisset
                                            </tr>
                                            <tr>
                                                <td>Chat</td>
                                                @isset($chat)
                                                    <td>{{$chat}}<td>
                                                @endisset
                                            </tr>
                                            <tr>
                                                <td>Subject</td>
                                                @isset($subject)
                                                    <td>{{$subject}}<td>
                                                @endisset
                                            </tr> -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                </div>

            </div>

            @push('scripts')
                <script>
                    $('#startdate, #enddate').on('change', function() {
                        if($('#startdate').val() != '' && $('#enddate').val() != '') {
                            $('#setDateButton').removeAttr("disabled");
                        }
                    })

                    $('#setDateButton').on('click', function() {
                        var startdate = new Date($('#startdate').val());
                        var enddate = new Date($('#enddate').val());

                        
                        var formatStartDate = startdate.getFullYear() + '-' + ("0" + (startdate.getMonth() + 1)).slice(-2) + '-' + ("0" + startdate.getDate()).slice(-2)
                        var formatEndDate = enddate.getFullYear() + '-' + ("0" + (enddate.getMonth() + 1)).slice(-2) + '-' + ("0" + enddate.getDate()).slice(-2)
                        
                        window.location.href = '/web/activity_user/count/'+formatStartDate+'/'+formatEndDate
                    })
                </script>
            @endpush
@endsection