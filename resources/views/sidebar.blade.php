<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">

@if(Auth::user()->type == 's' || Auth::user()->type == 'p')
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset("/uploads/profile/".$studentuser->profilepic) }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p><a href="/profile">{{ ucwords($studentuser->firstname)}} {{ ucwords($studentuser->lastname)}}</a></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-danger"></i>{{ ucwords($studentuser->grade)}} - {{ ucwords($studentuser->section)}}</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        {{-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search..."/>
<span class="input-group-btn">
  <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
</span>
            </div>
        </form> --}}
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            {{-- <li class="header">HEADER</li> --}}
            <!-- Optionally, you can add icons to the links -->
            <li {{{ (Request::is('overview') ? 'class=active' : '') }}}><a href="/overview"><i class="fa fa-home"></i> <span>Overview</span></a></li>
            <li {{{ (Request::is('replyslips') ? 'class=active' : '') }}}><a href="/replyslips"><i class="fa fa-pencil-square-o"></i> <span>Reply Slips</span></a></li>
            <!-- added by elmer -->
            <li {{{ (Request::is('/chat') ? 'class=active' : '') }}}><a href="/chat"><i class="fa fa-comments"></i> <span>Class Chat</span></a></li>
            <!-- ended elmer -->
            <li class="treeview menu-open {{{ (Request::is('subjects/*') ? 'active' : '') }}}">
              <a href="#"><i class="fa fa-th"></i> <span>Subjects</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
              </a>
              <ul class="treeview-menu" style="display:block;">
                {{-- <li {{{ (Request::is('subjects/math') ? 'class=active' : '') }}}><a href="/subjects/math">Math</a></li>
                <li {{{ (Request::is('subjects/science') ? 'class=active' : '') }}}><a href="/subjects/science">Science</a></li> --}}

                @foreach($subjects as $subject)
              <li {{{ (Request::is('subjects/'.$subject->subject) ? 'class=active' : '') }}}><a href="/subjects/{{$urlsubj = str_replace(' ','_',$subject->subject)}}">{{UCWORDS($subject->subject)}}</a></li>
                @endforeach
                
              </ul>
            </li>
            <li class="treeview menu-open {{{ (Request::is('directory/*') ? 'active' : '') }}}">
                <a href="#"><i class="fa fa-th-list"></i> <span>Directory</span>
                  <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display:block">
                  <li {{{ (Request::is('directory/students') ? 'class=active' : '') }}}><a href="/directory/students">Students</a></li>
                  <li {{{ (Request::is('directory/teachers') ? 'class=active' : '') }}}><a href="/directory/teachers">Teachers</a></li>
                  
                  
                </ul>
              </li>
          </ul>
          <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
@endif

@if(Auth::user()->type == 't')
<section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset("/uploads/profile/".$teacheruser->profilepic) }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
            <p><a href="/tprofile/{{$teacheruser->id}}">{{ ucwords($teacheruser->firstname)}} {{ ucwords($teacheruser->lastname)}}</a></p>
                <!-- Status -->
                {{-- <a href="#"><i class="fa fa-circle text-danger"></i>{{ ucwords($studentuser->grade)}} - {{ ucwords($studentuser->section)}}</a> --}}
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            {{-- <li class="header">HEADER</li> --}}
            <!-- Optionally, you can add icons to the links -->
            <li {{{ (Request::is('toverview') ? 'class=active' : '') }}}><a href="/toverview"><i class="fa fa-home"></i> <span>Overview</span></a></li>
            <li {{{ (Request::is('treplyslips') ? 'class=active' : '') }}}><a href="/treplyslips"><i class="fa fa-pencil-square-o"></i> <span>Reply Slips</span></a></li>
            @if(isset($attendance) && $attendance !== '' )
        <li {{{ (Request::is('tattendance/*') ? 'class=active' : '') }}}><a href="/tattendance/{{$attendanceGrade or ''}}-{{$attendanceSection or ''}}"><i class="fa fa-pencil-square"></i> 
            <span>Attendance</span></a></li>
            @endif
            <!-- added by elmer -->
            <li {{{ (Request::is('/view/tattendance') ? 'class=active' : '') }}}><a href="/view/tattendance"><i class="fa fa-pencil-square"></i> <span>View Attendance</span></a></li>
            <li {{{ (Request::is('/chat/*') ? 'class=active' : '') }}}><a href="/chat"><i class="fa fa-comments"></i> <span>Class Chat</span></a></li>
            <!-- end by elmer -->
            <li class="treeview menu-open {{{ (Request::is('subjects/*') ? 'active' : '') }}}">
              <a href="#"><i class="fa fa-th"></i> <span>Subjects</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
              </a>
              <ul class="treeview-menu" style="display:block;">
                
              
                @foreach($subjects as $subject)
              <li  {{{ (Request::is('tclass/'.$subject->subj.'/'.$subject->grade.'-'.$subject->section) ? 'class=active' : '') }}} >
              <a href="/tclass/{{$urlsubj = str_replace(' ','_',$subject->subj)}}/{{$subject->grade}}-{{$subject->section}}">{{UCWORDS($subject->subj)}} {{UCWORDS($subject->grade)}} - {{UCWORDS($subject->section)}}</a>
            </li>
                @endforeach
                
              </ul>
            </li>
            <li class="treeview menu-open {{{ (Request::is('directory/*') ? 'active' : '') }}}">
                <a href="#"><i class="fa fa-th-list"></i> <span>Directory</span>
                  <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display:block">
                  <li {{{ (Request::is('directory/students') ? 'class=active' : '') }}}><a href="/adirectory/students">Students</a></li>
                  <li {{{ (Request::is('directory/teachers') ? 'class=active' : '') }}}><a href="/adirectory/teachers">Teachers</a></li>
                  
                  
                </ul>
              </li>

            <li class="treeview menu-open {{{ (Request::is('tgrading/*') ? 'active' : '') }}}">
              <a href="#"><i class="fa fa-th"></i> <span>Grading</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
              </a>
              <ul class="treeview-menu" style="display:block;">
                

                @foreach($subjects as $subject)
              <li {{{ (Request::is('tgrading/list/'.$subject->subj.'/'.$subject->grade.'-'.$subject->section) ? 'class=active' : '') }}}>
                <a href="/tgrading/list/{{$urlsubj = str_replace(' ','_',$subject->subj)}}/{{$subject->grade}}-{{$subject->section}}">{{UCWORDS($subject->subj)}} {{UCWORDS($subject->grade)}} - {{UCWORDS($subject->section)}}</a>
            </li>
                @endforeach
                
              </ul>
            </li>
            <li class="treeview menu-open {{{ (Request::is('directory/*') ? 'active' : '') }}}">
                <a href="#"><i class="fa fa-th-list"></i> <span>Final Grades</span>
                  <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display:block">
                  <li><a href="/fetch/final/view/grades">Pick Grade/Section</a></li>
                  <li><a href="/fetch/final/view/reportcard">Pick Student</a></li>
                  <li><a href="/fetch/tasks/grades">Get Graded Tasks</a></li>
                </ul>

              </li>
          </ul>
          <!-- /.sidebar-menu -->
    </section>

@endif



@if(Auth::user()->type == 'a')
<section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset("/uploads/profile/".$teacheruser->profilepic) }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p><a href="/tprofile/{{$teacheruser->id}}">{{ ucwords($teacheruser->firstname)}} {{ ucwords($teacheruser->lastname)}}</a></p>
                <!-- Status -->
                {{-- <a href="#"><i class="fa fa-circle text-danger"></i>{{ ucwords($studentuser->grade)}} - {{ ucwords($studentuser->section)}}</a> --}}
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            {{-- <li class="header">HEADER</li> --}}
            <!-- Optionally, you can add icons to the links -->
            <li {{{ (Request::is('aoverview') ? 'class=active' : '') }}}><a href="/aoverview"><i class="fa fa-home"></i> <span>Overview</span></a></li>
            <li {{{ (Request::is('areplyslips') ? 'class=active' : '') }}}><a href="/areplyslips"><i class="fa fa-pencil-square-o"></i> <span>Reply Slips</span></a></li>
            <li {{{ (Request::is('/activity_user/*') ? 'class=active' : '') }}}><a href="/activity_user"><i class="fa fa-cubes"></i> <span>Activity users</span></a></li>
            <li {{{ (Request::is('/chat/*') ? 'class=active' : '') }}}><a href="/chat"><i class="fa fa-comments"></i> <span>Class Chat</span></a></li>
            <li {{{ (Request::is('anotifications') ? 'class=active' : '') }}}><a href="/anotifications"><i class="fa fa-bell"></i> <span>Notifications</span></a></li>
            {{-- <li class="treeview menu-open {{{ (Request::is('subjects/*') ? 'active' : '') }}}">
              <a href="#"><i class="fa fa-th"></i> <span>Subjects</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
              </a> --}}
              {{-- <ul class="treeview-menu" style="display:block;">
                

                @foreach($subjects as $subject)
              <li {{{ (Request::is('tclass/'.$subject->subj.'/'.$subject->grade.'-'.$subject->section) ? 'class=active' : '') }}}>
                <a href="/tclass/{{$urlsubj = str_replace(' ','_',$subject->subj)}}/{{$subject->grade}}-{{$subject->section}}">{{UCWORDS($subject->subj)}} {{UCWORDS($subject->grade)}} - {{UCWORDS($subject->section)}}</a>
            </li>
                @endforeach
                
              </ul> --}}
            {{-- </li> --}}
            <li class="treeview menu-open {{{ (Request::is('directory/*') ? 'active' : '') }}}">
                <a href="#"><i class="fa fa-th-list"></i> <span>Directory</span>
                  <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display:block">
                  <li {{{ (Request::is('directory/students') ? 'class=active' : '') }}}><a href="/adirectory/students">Students</a></li>
                  <li {{{ (Request::is('directory/teachers') ? 'class=active' : '') }}}><a href="/adirectory/teachers">Teachers</a></li>
                  
                  
                </ul>
              </li>
              <li {{{ (Request::is('aincidents') ? 'class=active' : '') }}}><a href="/aincidents"><i class="fa fa-warning "></i> <span>Incidents</span></a></li>
              <li {{{ (Request::is('apaymentdues') ? 'class=active' : '') }}}><a href="/apaymentdues"><i class="fa fa-credit-card "></i> <span>Payment Dues</span></a></li>
             <li {{{ (Request::is('acalendar') ? 'class=active' : '') }}}><a href="/acalendar"><i class="fa fa-calendar"></i> <span>calendar of events</span></a></li>

             <li class="treeview menu-open {{{ (Request::is('aenrollment/*') ? 'active' : '') }}}">
                <a href="#"><i class="fa fa-th-list"></i> <span>Enrollment</span>
                  <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display:block">
                  <li {{{ (Request::is('aenrollment/add') ? 'class=active' : '') }}}><a href="/aenrollment/add">Enroll a Student</a></li>
                  <li {{{ (Request::is('aenrollment/students') ? 'class=active' : '') }}}><a href="/aenrollment/students">Enrollment List</a></li>
                  
                  
                </ul>
              </li>

              {{--<li class="treeview menu-open {{{ (Request::is('agrading/*') ? 'active' : '') }}}">--}}
              <li class="treeview menu-open">
              <a href="#"><i class="fa fa-th"></i> <span>Admin Grading</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
              </a>
              <ul class="treeview-menu" style="display:block;">
                

                @foreach($subjects as $subject)
              <li {{{ (Request::is('agrading/list/'.$subject->subj.'/'.$subject->grade.'-'.$subject->section) ? 'class=active' : '') }}}>
                <a href="/agrading/list/{{$subject->subj}}/{{$subject->grade}}-{{$subject->section}}">{{UCWORDS($subject->subj)}} {{UCWORDS($subject->grade)}} - {{UCWORDS($subject->section)}}</a>
            </li>
                @endforeach
                
              </ul>
            </li>
              <li class="treeview menu-open {{{ (Request::is('directory/*') ? 'active' : '') }}}">
                <a href="#"><i class="fa fa-th-list"></i> <span>Final Grades</span>
                  <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display:block">
                  <li><a href="/afetch/final/view/grades">Pick Grade/Section</a></li>
                  <li><a href="/fetch/final/view/reportcard">Pick Student</a></li>
                  <li><a href="/afetch/tasks/grades">Get Graded Tasks</a></li>
                </ul>

              </li>
            
          </ul>
          <!-- /.sidebar-menu -->
    </section>

@endif

</aside>