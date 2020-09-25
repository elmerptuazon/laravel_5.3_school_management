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

    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-primary">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="/uploads/profile/{{$studentuser->profilepic}}" alt="User profile picture">

          <h3 class="profile-username text-center">
            {{UCWORDS($studentuser->firstname)}} {{UCWORDS($studentuser->lastname)}}
          
          </h3>
          
          <span class="text-muted"style="text-align:center; display:block;" >{{$studentuser->grade}} {{UCWORDS($studentuser->section)}}</span>
          <span class="text-muted"style="text-align:center; display:block;" >{{$studentuser->age}} years old</span>
          

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
          <strong style="margin:20px"><i class="fa fa-book"></i> Preferred Contact</strong>
          <br><br>
          <p class="text-muted" style="margin-left:20px">
              ({{$studentuser->s_prefcontact}}) {{$studentuser->prefcontactname}}
          </p>
          <p class="text-muted">
              {{$studentuser->prefcontactno}}
            </p>

          <hr>

          <strong style="margin:20px"><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
          
          <p class="text-muted" style="margin:20px">{{$studentuser->s_address or "default"}}</p>

          <hr>
          <strong style="margin:20px"><i class="fa fa-birthday-cake"></i> Birthday</strong>

          <p class="text-muted">{{date('F d, Y',strtotime($studentuser->birthdate))}}</p>
          <hr>

        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
        @if($reportcard_view_parent->status == 0)
        <li class="active"><a href="#activity" data-toggle="tab">General Information</a></li>
        @else
        <li><a href="#activity" data-toggle="tab">General Information</a></li>
        @endif
          {{--<li class="active"><a href="#activity" data-toggle="tab">General Information</a></li>--}}
          <li><a href="#attendance" data-toggle="tab">Attendance</a></li>
          <li><a href="#achievements" data-toggle="tab">Achievements</a></li>
          <li><a href="#incidents" data-toggle="tab">Incidents</a></li>
          <li><a href="#medical" data-toggle="tab">Medical</a></li>
          <li><a href="#paymentdues" data-toggle="tab">Payment Dues</a></li>
          <li class="active"><a href="#taskgrades" data-toggle="tab">Task Grades</a></li>
          @if($reportcard_view_parent->status == 1)   
          <li><a href="#finalgrades" data-toggle="tab">View Final Grade</a></li>
          @else
          <li><a href='#' data-toggle='tab'><i><span style="color:red;">Computing of grades ongoing</span></i></a></li>
          @endif
        
          
        </ul>
        <div class="tab-content">
          <div class="tab-pane" id="activity">
            <!-- Post -->
            <div class="post">
              <div class="user-block">
                <div class="box-header">
                      
                </div>
                <style>
                    /* .nfo{
                      text-indent: 20px;  
                      clear: both;
                    } */
                    p{
                     display: inline-flex;
                      margin-left:15px;
                    }
                </style>
                <div  style="margin:0px 30px 30px 30px;">
                    <legend style="font-size: 20px; margin: 0px;"><i class="fa fa-user" ></i> Student Info</legend> <br>
                  <div id="q">
                    <style>
                      .lst{
                        list-style: none;
                      }
                      #q{
                        display: inline-flex;
                      }
                    </style>
                    {{-- <pre>
                      {{print_r($studentuser)}}
                    </pre> --}}
                      <ul>
                        <li class="lst"><strong>ID number:</strong></li>
                        <li class="lst"><strong>First Name:</strong></li>
                        <li class="lst"><strong>Last Name:</strong></li>
                        <li class="lst"><strong>Grade</strong></li>
                        <li class="lst"><strong>Section</strong></li>
                        <li class="lst"><strong>Age:</strong></li>
                        <li class="lst"><strong>Birthday:</strong></li>
                        <li class="lst"><strong>Telephone#:</strong></li>
                        <li class="lst"><strong>Cellphone#: </strong></li>
                        <li class="lst"><strong>Address:</strong></li>
                        <li class="lst"><strong>email:</strong></li>
                      </ul>
                      
                      <ul>
                      <li class="lst">{{$studentuser->id}}</li>
                          <li class="lst">{{ ucwords($studentuser->firstname) }} &nbsp;</li>
                          <li class="lst">{{ ucwords($studentuser->lastname) }} &nbsp;</li>
                          <li class="lst">{{$studentuser->grade}} &nbsp;</li>
                          <li class="lst">{{$studentuser->section}} &nbsp;</li>
                          <li class="lst">{{$studentuser->age}} &nbsp;</li>
                          <li class="lst">{{$studentuser->birthdate}} &nbsp;</li>
                          <li class="lst">{{$studentuser->s_landline}} &nbsp;</li>
                          <li class="lst">{{$studentuser->s_cellno }} &nbsp;</li>
                          <li class="lst">{{$studentuser->s_address or "default"}} &nbsp;</li>
                          <li class="lst">{{$studentuser->s_email}}&nbsp;</li>
                      </ul>
                  </div>
                  
                  <legend class="nfo" style="font-size: 20px;"><i class="fa fa-phone" ></i> Contact</legend>

                  <h3 style="text-indent: 25px; border-bottom: 1px solid whitesmoke;">Mother's Information</h3>
                  <div id="q"> 
                    <ul>          
                      
                        <li class="lst"><strong>Name: </strong></li>
                        <li class="lst"><strong>Cellphone Number: </strong></li>
                        <li class="lst"><strong>Email: </strong></li>
                        <li class="lst"><strong>Office Tel#: </strong></li>
                        <li class="lst"><strong>Office Address: </strong></li>
                    </ul>                 
                    <ul>
                      
                        <li class="lst">&nbsp; {{$studentuser->s_momname}}</li>
                        <li class="lst">&nbsp; {{$studentuser->s_momcellno}}</li>
                        <li class="lst">&nbsp; {{$studentuser->s_momemail}}</li>
                        <li class="lst">&nbsp; {{$studentuser->s_momofficetel}}</li>
                        <li class="lst">&nbsp;{{$studentuser->s_momofcaddress}}</li>
                    </ul>
                    </div>
                    <h3 style="text-indent: 25px; border-bottom: 1px solid whitesmoke;">Father's Information</h3>
                    <div id="q"> 
                      <ul>          
                        
                          <li class="lst"><strong>Name: </strong></li>
                          <li class="lst"><strong>Cellphone Number: </strong></li>
                          <li class="lst"><strong>Email: </strong></li>
                          <li class="lst"><strong>Office Tel#: </strong></li>
                          <li class="lst"><strong>Office Address: </strong></li>
                      </ul>                 
                      <ul>
                        
                          <li class="lst">&nbsp; {{$studentuser->s_dadname}}</li>
                          <li class="lst">&nbsp; {{$studentuser->s_dadcellno}}</li>
                          <li class="lst">&nbsp; {{$studentuser->s_dademail}}</li>
                          <li class="lst">&nbsp; {{$studentuser->s_dadofficetel}}</li>
                          <li class="lst">&nbsp; {{$studentuser->s_dadofcaddress}}</li>
                      </ul>
                  </div>
                  <h3 style="text-indent: 25px; border-bottom: 1px solid whitesmoke;">Guardian's Information</h3>
                  <div id="q"> 
                    <ul>          
                        <li class="lst"><strong>Name: </strong></li>
                        <li class="lst"><strong>Cellphone Number: </strong></li>
                        <li class="lst"><strong>Guardian Tel#: </strong></li>
                        <li class="lst"><strong>Email: </strong></li>
                        <li class="lst"><strong>Guardian's Relationship: </strong></li>
                    </ul>                 
                    <ul>
                        <li class="lst"> &nbsp;{{$studentuser->s_guardianname}}</li>
                          <li class="lst"> &nbsp;{{$studentuser->s_guardiancellno}}</li>
                        <li class="lst"> &nbsp;{{$studentuser->s_guardiantel}}</li>
                        <li class="lst"> &nbsp;{{$studentuser->s_guardianemail}}</li>
                        <li class="lst"> &nbsp;{{$studentuser->s_guardianrelation}}</li>
                    </ul>
                </div>
              </div>
                  <!-- <Legend class="nfo" style="font-size: 20px; margin: 0px;"><i class="fa fa-book" ></i> Classes and Subject</Legend>
                  <br>
                  <div id="q" style="text-indent: 20px;">
                    
                    <ul>
                        <li class="lst"><strong>Math</strong></li>
                        <li class="lst"><strong>Science</strong></li>
                        <li class="lst"><strong>English</strong></li>
                      </ul>
                      
                      <ul>
                          <li class="lst"> 3-J</li>
                          <li class="lst"> 4-E</li>
                          <li class="lst"> 3-J</li>
                      </ul>
                  </div> -->
                </div><!-- user block-->
                
              </div>       <!-- post-->   
            </div><!-- tab-pane--> 
            
            <div class="tab-pane" id="attendance">
                <ul>
                  @foreach($attendance as $att)
                <li>{{date('l F d, Y',strtotime($att->date))}} - {{$att->status}}</li>
                  @endforeach
                  <ul>
              </div>
              <div class="tab-pane" id="achievements">
                <ul>
                  @foreach($achievements as $achieve)
                <li>{{ucwords($achieve->title)}} - {{$achieve->details}}</li>
                  @endforeach
                  <ul>
              </div>

              <div class="tab-pane" id="incidents">
                  <ul>
                    @foreach($incidents as $incident)
                  <li>{{date('l F d, Y',strtotime($incident->date))}} - {{$incident->description}}</li>
                    @endforeach
                    <ul>
                </div>

                <div class="tab-pane" id="medical">
                    <div  style="margin:30px 30px 30px 30px;">
                    <legend style="font-size: 20px; margin: 0px;"><i class="fa fa-medkit" ></i> Medical Info</legend> <br>
                    <div id="q">
                        
                          <ul>
                            <li class="lst"><strong>Blood type:</strong></li>
                            <li class="lst"><strong>Allergy:</strong></li>
                            <li class="lst"><strong>Existing Conditions:</strong></li>
                            
                          </ul>
                          
                          <ul>

                          <li class="lst">{{$medical->bloodtype or ""}} &nbsp;</li>
                              <li class="lst">{{$medical->allergy or ""}} &nbsp;</li>
                              <li class="lst">{{$medical->existingconditions or ""}} &nbsp;</li>
                              
                          </ul>
                      </div>
                    </div>
                </div>

                <div class="tab-pane" id="paymentdues">
                    <ul>
                      @foreach($paymentdues as $dues)
                    <li>{{date('l F d, Y',strtotime($dues->date))}} - <strong>{{$dues->description}}</strong> <label class="label bg-yellow">{{$dues->status}}</label></li>
                      @endforeach
                      <ul>
                  </div>
                    
                  <div class="tab-pane active" id="taskgrades">
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
                                        <select id="selectPeriod" class="form-control" name="period">
                                          <option value="{{$period_clicked or ''}}">Current: {{$period_clicked or '--Select Period--'}}</option>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                        </select>
                                        </div>
                                        <input type="hidden" id="studentname_id" value="{{$studentuser->id}}">
                                        <div class="col-md-3 col-xs-3">
                                        <select id="selectSubj" class="form-control" name="subject">
                                          <option value="{{$subj_clicked or ''}}">Current: {{$subj_clicked or '--Select Subject--'}}</option>
                                          @foreach($students_subjects as $subject)
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
                                                  <th> </th>
                                              </tr>  
                                
                                            </thead>
                                             <tbody id="curriculumTransmutedGrade">
                                              <tr>
                                                <table class=" table-striped table-bordered" style="display: block; overflow-x: auto;">
                                                  <thead>
                                                    <tr style="clear: both; height: max-content;">
                                                      <th style="width: 10%; height: 255px; padding: 10px; white-space: nowrap;"><strong>Task Title</strong></th>
                                                      @isset($get_task_id)
                                                        @foreach($get_task_id as $task_title)
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
                                                      @isset($get_task_id)
                                                        @foreach($merge_task_list as $task_type_merged)
                                                          @foreach($task_type_list as $task_title)
                                                            @if($task_type_merged->{'Task Type'} == $task_title->type_name)
                                                              <th style="text-align:center;"><strong>{{ $task_title->weight }}% ({{$task_title->type_name}})</strong></th>
                                                            @endif
                                                          @endforeach
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
                                                      @isset($get_task_id)
                                                        @foreach($get_task_id as $task_title)
                                                          <th style="text-align:center;"><strong>{{$task_title->task_total_points}}</strong></th>
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
                                                    @foreach($student_name as $student)
                                                      <tr>
                                                      <td>{{ ucwords($student->firstname) }} {{ ucwords($student->lastname) }}</td>
                                                      @isset($merge_task_list)
                                                        @foreach($merge_task_list as $score)
                                                          <td style="text-align:center;">{{ $score->{'Task Score'} }}</td>
                                                        @endforeach
                                                      @endisset
                                                      @isset($task_type_sub_total)
                                                        @foreach($task_type_sub_total as $sub_total)
                                                          <td style="text-align:center;">{{$sub_total}}</td>
                                                        @endforeach
                                                      @endisset

                                                      @isset($get_final_grade_list)
                                                        <td style="text-align:center;">{{$get_final_grade_list->score}}%</td>
                                                      @endisset
                                                      @isset($get_final_grade_list)
                                                        <td style="text-align:center;">{{$get_final_grade_list->transmuted}}%</td>
                                                      @endisset
                                                      </tr>
                                                    @endforeach
                                                    
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

                  <div class="tab-pane" id="finalgrades">

                    <div style="padding-bottom:50px;">

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

                      <div style="border-top: 1.5px solid black; border-bottom: 1.5px solid black;">
                        <table style="width:100%">
                            <tr>
                                <th>Student</th>
                                <td><strong>{{ ucwords($studentuser->firstname) }} {{ ucwords($studentuser->lastname) }}</strong></td>
                                <th></th>
                                <td></td>
                                <th><strong>Academic</strong></th>
                                <td><strong>2019-2020</strong></td>
                            </tr>
                            <tr>
                                <th>Grade</th>
                                <td><strong>{{$studentuser->grade}}-{{$studentuser->section}}</strong></td>
                                <th></th>
                                <td></td>
                                <th>Term</th>
                                <td><strong>All Quarters</strong></td>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <td><strong>{{$studentuser->age}}</strong></td>
                                <th>LRN</th>
                                <td><strong>402910150056</strong></td>
                                <th>Gender</th>
                                <td><strong>{{$studentuser->gender}}</strong></td>
                            </tr>
                        </table>  
                      </div>
                      <div style="padding-top:15px">
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

                          @isset($transmuting_final_grade)
                            @foreach($transmuting_final_grade as $subject=>$list)
                            <tr>
                              <td style="text-indent: 5px;" class="tableWithBordersTD"><small>{{strtoupper($subject)}}</small></td>
                            @for($i = 1; $i<=5; $i++)
                              @if($list[$i] == 60)
                                <td class="tableWithBordersTD centerValue"></td>
                              @else
                                <td class="tableWithBordersTD centerValue">{{$list[$i]}}</td>
                              @endif
                            
                            @endfor
                            </tr>
                            @endforeach
                          @endisset

                          <tr>
                            <td style="text-indent: 5px;" class="tableWithBordersTD"><small>{{strtoupper('General Average')}}</small></td>
                              @isset($transmuting_general_average)
                                @foreach($transmuting_general_average as $final)
                                  @if($final == 60)
                                    <td class="tableWithBordersTD centerValue"></td>
                                  @else
                                    <td class="tableWithBordersTD centerValue">{{$final}}</td>
                                  @endif
                                
                                @endforeach
                              @endisset
                              <td class="tableWithBordersTD centerValue">{{$final_general_average}}</td>
                          </tr>
                    </div>
                    </tbody>
                  </table>
                  </div>
                  <div style="padding-top:15px">
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
                  <div style="padding-top:15px">
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
                        @foreach($task_qualitative_category as $category)
                        <tr>
                          <td style="text-indent: 5px;" class="tableWithBordersTD"><strong>{{str_replace('_',' ',$category->category)}}</strong></td>
                          <td> </td>
                          <td> </td>
                          <td> </td>
                          <td> </td>
                        </tr>

                          @foreach($task_qualitative_types as $types)
                            @if($category->category == $types->category)
                              <tr>
                              <td style="text-indent: 30px;" class="tableWithBordersTD">{{$types->type_name}}</td>

                              @foreach($task_qualitative_score_array as $student_id_key=>$student_period)
                                @foreach($student_period as $key_qid=>$val_period)
                                  @foreach($val_period as $key_period=>$score)
                                    @foreach($period_list as $period)
                                      @if($student_specific_name == $student_id_key)
                                        @if($types->id == $key_qid)
                                          @if($period == $key_period)
                                            <td class="tableWithBordersTD centerValue">{{$score}}</td>
                                          @endif
                                        @endif

                                      @endif

                                    @endforeach

                                  @endforeach

                                @endforeach

                              @endforeach

                            @endif

                          @endforeach


                        @endforeach
                  
                    
                    </tbody>
              </table>
            </div>
            <div style="padding-top:15px">
            <table class="tableWithBorders" style="width:100%;">
                <thead>
                  <tr>
                    <th style="font-size:13px; text-indent: 1px;" class="tableWithBordersTH"><strong>Attendance Record</strong></th>
                    @isset($month_name_list_sorted)
                      @foreach($month_name_list_sorted as $month_name)
                        <th class="tableWithBordersTH centerValue"><strong>{{$month_name}}</strong></th>
                      @endforeach
                    @endisset
                    
                    <th class="tableWithBordersTH centerValue"><strong>Total</strong></th>
                  </tr>
                </thead>
                <tbody class="bodyFontStyle">
                <tr>
                  <td class="tableWithBordersTD">Days of School</td>
                  @isset($total_attendance)
                    @foreach($month_number_list_sorted as $month_no)
                      @foreach($total_attendance[$studentuser->id][$month_no] as $attendance)
                        <td class="tableWithBordersTD centerValue">{{$attendance->student_total}}</td>
                      @endforeach
                    @endforeach
                  @endisset
                  

                  @isset($total_days_currentyear)
                    @foreach($total_days_currentyear as $total_days)
                      <td class="tableWithBordersTD centerValue">{{$total_days[0]->student_total}}</td>
                    @endforeach
                  @endisset
                </tr>
                <tr>
                  <td class="tableWithBordersTD">Days Present</td>
                  @isset($total_present)
                    @foreach($month_number_list_sorted as $month_no)
                      @foreach($total_present[$studentuser->id][$month_no] as $attendance)
                        <td class="tableWithBordersTD centerValue">{{$attendance->student_total}}</td>
                      @endforeach
                    @endforeach
                  @endisset

                  @isset($total_present_currentyear)
                    @foreach($total_present_currentyear as $total_days)
                      <td class="tableWithBordersTD centerValue">{{$total_days[0]->student_present}}</td>
                    @endforeach
                  @endisset
                  
                </tr>
                <tr>
                  <td class="tableWithBordersTD">Days Absent</td>
                  @isset($total_absent)
                    @foreach($month_number_list_sorted as $month_no)
                      @foreach($total_absent[$studentuser->id][$month_no] as $attendance)
                        <td class="tableWithBordersTD centerValue">{{$attendance->student_total}}</td>
                      @endforeach
                    @endforeach
                  @endisset

                  @isset($total_absent_currentyear)
                    @foreach($total_absent_currentyear as $total_days)
                      <td class="tableWithBordersTD centerValue">{{$total_days[0]->student_absent}}</td>
                    @endforeach
                  @endisset
                </tr>
                </tbody>
            </table>

            </div>

          </div>   <!--  tab-content--> 
                
          
        </div> <!-- nav-tabs-custom -->
        
      </div><!--  col-md-9  -->
      
    </div>
    <!-- /.col -->
  
  <!-- /.row -->
  @push('scripts')
  <script>
$('div.taskAlert').fadeOut(3000);
$('#exportPDF').on('click', function() {
  var strSplit = $('#selectGrade').val();

      window.location.href = '/fetch/student/grade/pdf/'+strSplit;
    

})

$('#searchSubmit').on('click', function() {
    var strPeriod = $('#selectPeriod').val();
    var strSubj = $('#selectSubj').val();
    var studentid = $('#studentname_id').val();

    if(strPeriod == '' || strSubj == '') {
      alert('Please select Period and Subject.')
      
    } else {
      window.location.href = '/profile/student/'+ studentid + '/' + strSubj+'/'+strPeriod;
    }

    
});

  </script>
  @endpush

@endsection