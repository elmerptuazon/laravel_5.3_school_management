<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('auth/login');
    return redirect()->action('LoginWhereTo@index');
});

Route::get('/reportcardmanualfix/{grade}', function ($grade, $section = 'a') {
    $data['student_id_list'] = DB::table('students')
    ->select('id', 'firstname', 'lastname')
    ->where('grade', $grade)
    ->where('section', $section)
    ->orderBy('lastname')
    ->get();

    $data['student_count'] = DB::table('students')
    ->where('grade', $grade)
    ->where('section', $section)
    ->count();
    $data['subject_list_grade_section'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->whereNotIn('subject',['homeroom'])
    ->orderBy('subject')
    ->get();

    
    foreach($data['student_id_list'] as $student){
        // echo $student->id." ";

        $url = 'http://main.stpcentral.net/api/grade/list/finalgrade/'.$grade.'-a/'.$student->id;
        $json = json_decode(file_get_contents($url), true);
        // echo"<pre>";print_r($data['subject_list_grade_section']);echo"</pre>";die();
        foreach($data['subject_list_grade_section'] as $subject){
            // echo $subject->subject;
            // return json_encode($json['final_grade'][$subject->subject]);
            // return response()->json($json['final_grade'][$subject->subject]);
            $subject_finalgrades['final_grade'][$student->id][$subject->subject] = $json['final_grade'][$subject->subject];
            
            for($i=1; $i<=5; $i++) {

                
                if($i == 5){
                    $scoring = $json['final_grade'][$subject->subject]['final_grade'];
                }else{
                    $scoring = $json['final_grade'][$subject->subject]['Q'.$i.' grade'];
                }

                $addInputData = DB::table('reportcard_manual')->insert(
                    [
                        'sid'=>$student->id,
                        'subj_label'=>$subject->subject,
                        'score'=>$scoring,
                        'period'=>$i,
                        'school_year'=>'2019-2020'
                    ]
                );
            }

        }


        $subject_finalgrades['general_average'][$student->id] = $json['general_average'];
        $subject_finalgrades['general_average'][$student->id]['5'] =  round(array_sum($subject_finalgrades['general_average'][$student->id]) / count($subject_finalgrades['general_average'][$student->id])  );
        for($i=1; $i<=5; $i++) {
            $addInputData2 = DB::table('reportcard_manual')->insert(
                [
                    'sid'=>$student->id,
                    'subj_label'=>'general_average',
                    'score'=>$subject_finalgrades['general_average'][$student->id][$i],
                    'period'=>$i,
                    'school_year'=>'2019-2020'
                ]
            );
        }

    }
    echo"<pre>";print_r($subject_finalgrades);echo"</pre>";  
    die();


    // $url = 'http://main.stpcentral.net/api/grade/list/finalgrade/6-a/2012002';
    // $json = json_decode(file_get_contents($url), true);
    // $json = file_get_contents($url);
    // return $json['final_grade']['filipino'];
    // foreach($json['final_grade']['filipino'] as $key=>$value){
        // echo $key." ".$value." <br />";
    // };
});

Route::get('admin', function () {
    return view('admin_template');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('test', 'TestController@index');
Route::get('/loginwhereto', 'LoginWhereTo@index');
Route::get('/accountswitch/{id}', 'LoginWhereTo@accountSwitcher');

Route::get('directory/staff', 'StudentController@staffDirectory');

Route::get('overview', 'StudentController@index');
Route::get('overview/{date}', 'StudentController@overviewDate');
Route::get('replyslips', 'StudentController@replyslips');
Route::get('subjects/{subj}', 'StudentController@subjects');
Route::get('subjects/{subj}/{date}', 'StudentController@subjectsDate');
Route::get('directory/{dir}', 'StudentController@directory');
Route::get('profile', 'StudentController@profile');
Route::get('profile/{sid}', 'StudentController@profile');
Route::get('profile/student/{sid}', 'StudentController@profile');



Route::get('toverview', 'TeacherController@index');
Route::get('toverview/{date}', 'TeacherController@overviewDate');
Route::get('tprofile/{tid}', 'TeacherController@profile');
Route::get('tclass/{subj}/{grade}-{section}', 'TeacherController@subjects');
Route::get('treplyslips', 'TeacherController@replyslips');

Route::get('tattendance', 'TeacherController@attendance');
Route::get('tattendance/{grade}-{section}', 'TeacherController@attendance');
Route::get('tattendance/{grade}-{section}/{date}', 'TeacherController@attendance');

Route::get('tgrading/list/{subj}/{grade}-{section}', 'TeacherController@gradingList');


Route::post('tattendance/submit', 'TeacherController@attendanceSubmit');

Route::post('upload', 'TeacherController@upload');

Route::post('grading', 'TeacherController@postGrades');
Route::get('reportcard/{sid}', 'TeacherController@reportCard');

Route::get('aoverview', 'AdminController@index');
Route::get('adirectory/search/s/{searchterm}', 'AdminController@directorySearchStudent');
Route::get('adirectory/search/t/{searchterm}', 'AdminController@directorySearchTeacher');

Route::get('aenrollment/students', 'AdminController@enrollmentList');
Route::get('aenrollment/add', 'AdminController@enrollmentAdd');
Route::get('aenrollment/e/{sid}', 'AdminController@enrollmentEdit');

Route::get('adirectory/students', 'AdminController@sdirectory');
Route::get('adirectory/students/{grade}-{section}', 'AdminController@sdirectory');
Route::get('areplyslips', 'AdminController@replyslips');
Route::get('areplyslip/input/{id}', 'AdminController@replyslipInput');
Route::get('areplyslip/input/{id}/{grade}-{section}', 'AdminController@replyslipInput');
Route::post('areplyslip/input/submit/{id}', 'AdminController@replyslipInputSubmit');
Route::post('upload/replyslip', 'AdminController@uploadReplyslip');

Route::get('adirectory/teachers', 'AdminController@tdirectory');
Route::get('acalendar', 'AdminController@calendar');
Route::post('acalendar/post', 'AdminController@calendarPost');
Route::post('acalendar/e', 'AdminController@calendarEdit');
Route::post('acalendar/x', 'AdminController@calendarDel');
Route::get('aincidents', 'AdminController@incidents');
Route::post('aincident/post', 'AdminController@incidentPost');
Route::post('aincident/e', 'AdminController@incidentEdit');
Route::post('aincident/x', 'AdminController@incidentDel');
Route::get('apaymentdues', 'AdminController@paymentDues');
Route::post('apaymentdues/post', 'AdminController@paymentDuesPost');
Route::post('apaymentdues/e', 'AdminController@paymentDuesEdit');
Route::post('apaymentdues/x', 'AdminController@paymentDuesDel');

Route::get('anotifications', 'AdminController@notifications');
Route::get('notifications', 'StudentController@notifications');
Route::post('notification/post', 'AdminController@notificationPost');

Route::get('ajax/{subj}/{date}', 'AjaxController@ajaxHomeworkSubjects');
Route::get('ajax/{subj}/{totals}/{page}', 'AjaxController@ajaxHWSubjectsLoadMore');
Route::get('ajaxt/{subj}/{grade}-{section}/{date}', 'AjaxController@ajaxTeachersHomeworkSubjects');
Route::get('ajaxt/{subj}/{grade}-{section}/{totals}/{page}', 'AjaxController@ajaxTeachersHWSubjectsLoadMore');
Route::get('ajaxSched/{grade}-{section}', 'AjaxController@ajaxScheduleAll');

Route::post('ajaxAssignment/e/{id}', 'AjaxController@ajaxAssignmentEdit');
Route::post('ajaxAssignment/x/{id}', 'AjaxController@ajaxAssignmentDel');
Route::post('ajaxTest/e/{id}', 'AjaxController@ajaxTestEdit');
Route::post('ajaxTest/x/{id}', 'AjaxController@ajaxTestDel');
Route::post('ajaxAS/e/{id}', 'AjaxController@ajaxASEdit');
Route::post('ajaxAS/x/{id}', 'AjaxController@ajaxASDel');
Route::post('ajaxHO/e/{id}', 'AjaxController@ajaxHOEdit');
Route::post('ajaxHO/x/{id}', 'AjaxController@ajaxHODel');

Route::post('ajaxRSOption/e/{id}', 'AjaxController@ajaxRSOptionEdit');
Route::post('ajaxRSOption/x/{id}', 'AjaxController@ajaxRSOptionDel');
Route::post('ajaxRS/x/{id}', 'AjaxController@ajaxRSDel');
Route::post('ajaxRS/e/{id}', 'AjaxController@ajaxRSEdit');

Route::post('ajaxNotify/update', 'AjaxController@notifyUpdate');

Route::get('view/pdf', 'ViewAssets@index');
Route::get('view/test/{id}', 'ViewAssets@test');
Route::get('view/as/{id}', 'ViewAssets@activitysheet');
Route::get('view/ho/{id}', 'ViewAssets@handout');
Route::get('view/rs/{id}', 'ViewAssets@replyslip');

Route::get('chat/{grade}-{section}', 'StudentController@chat');
Route::get('chat', 'StudentController@chat');

Route::post('optionsadd', 'AdminController@addOptionRs');



Route::post('assignment/post', 'TeacherController@assignmentPost');

Route::get('api/test', 'NavigationController@testing');
Route::get('api/overview/assignments/{grade}-{section}', 'ApiController@soverviewAssignments')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/toverview/assignments/{tid}', 'ApiController@toverviewAssignments')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/subjects/{subj}/{grade}-{section}', 'ApiController@subjects')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/calendar', 'ApiController@calendar')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::get('api/profile/{utype}-{sid}', 'ApiController@profile')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/directory/{sid}', 'ApiController@directory')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/sched/{id}', 'ApiController@ajaxScheduleAll')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/hw/{grade}-{section}/{date}', 'ApiController@ajaxHWDate')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/thw/{tid}/{date}', 'ApiController@ajaxTHWDate')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/replyslips/{grade}-{section}/{sid}', 'ApiController@sReplyslips')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
// Route::get('api/notifications/{uid}', 'ApiController@notifications')->middleware('\Barryvdh\Cors\HandleCors::class');
Route::get('api/notifications/{uid}', 'ApiController@notifications')->middleware('\Barryvdh\Cors\HandleCors::class', 'auth:api');
Route::get('api/pclist/{pid}', 'ApiController@childList')->middleware('\Barryvdh\Cors\HandleCors::class', 'auth:api');
Route::get('api/subjhwload/{subj}/{grade}-{section}/{totals}/{page}', 'ApiController@ajaxHWSubjectsLoadMore')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');

Route::get('api/tdirectory/{tid}', 'ApiController@tdirectory')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/tclass/{subj}/{grade}-{section}', 'ApiController@tclass')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');

Route::get('api/chat/{grade}-{section}/{lastTimeID}', 'ApiController@getChat')->middleware(\Barryvdh\Cors\HandleCors::class);
// Route::post('api/chat/post', 'ApiController@postChat')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/chat/post', 'ApiController@postChat');
Route::get('api/mchat/{grade}-{section}/{lastTimeID}/{userid}', 'ApiController@getChatMobile')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/mchat/post', 'ApiController@postChatMobile')->middleware(\Barryvdh\Cors\HandleCors::class);



Route::get('api/tattendance/{tid}/{grade}-{section}', 'ApiController@attendance')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::get('api/tattendance/{tid}/{grade}-{section}/{date}', 'ApiController@attendance')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
Route::post('api/tattendance/post', 'ApiController@attendancePostTest');


Route::post('api/assignment/post', 'ApiController@assignmentPost')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/assignment/e', 'ApiController@assignmentEdit')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/assignment/d', 'ApiController@assignmentDel')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/test/e', 'ApiController@testEdit')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/test/d', 'ApiController@testDel')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/as/e', 'ApiController@asEdit')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/as/e', 'ApiController@asEdit')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/ho/e', 'ApiController@hoEdit')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('api/ho/d', 'ApiController@hoDel')->middleware(\Barryvdh\Cors\HandleCors::class);

Route::get('api/sdirectory/students', 'ApiController@sdirectory')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::get('api/sdirectory/students/{grade}-{section}', 'ApiController@sdirectory')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::get('api/sdirectory/search/s/{searchterm}', 'ApiController@directorySearchStudent')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::get('api/sdirectory/gradesectionlist', 'ApiController@gradeSectionList')->middleware(\Barryvdh\Cors\HandleCors::class);

Route::post('api/notificationsView/update', 'ApiController@notifyUpdate')->middleware(\Barryvdh\Cors\HandleCors::class);


Route::get('profile/pic/updater', 'AdminController@profilePicUpdater');

Auth::routes();


//added by elmer
Route::post('/aenroll/primarydetails', 'AdminController@enrollmentAddPrimaryDetails')->name('primarydetails')->middleware(\Barryvdh\Cors\HandleCors::class);
//sample addtask and tasklist 
Route::post('/fetch/add/task', 'TeacherFetchJSON@addTeacherTask')->name('addtask')->middleware(\Barryvdh\Cors\HandleCors::class);
//fetch scores of student
Route::post('/fetch/score/students', 'TeacherFetchJSON@addStudentScore')->name('addscore')->middleware(\Barryvdh\Cors\HandleCors::class);
//fetch character scores
Route::post('/fetch/character/score/students', 'TeacherFetchJSON@addStudentCharacterScore')->name('characterscore')->middleware(\Barryvdh\Cors\HandleCors::class);
//show grades of task/character and can be edited/create
Route::get('/tgrading/list/{subj}/{grade}-{section}/tasklist/{id}', 'TeacherFetchJSON@showStudentList')->name('showstudentlist');
Route::get('/tgrading/list/{subj}/{grade}-{section}/qualitative/score/{id}/{period}/{schoolYear}', 'TeacherFetchJSON@qualitativeStudentScore')->name('qualitativescore');
//view of all task of student. this is not being used
Route::post('/fetch/final/grade/student', 'TeacherFetchJSON@finalGradeComputation')->middleware(\Barryvdh\Cors\HandleCors::class);
//viewing of grades from section and grade
Route::get('/fetch/final/view/grades','TeacherFetchJSON@viewFinalGradeFromSection')->name('viewfinalgradefromsection');
Route::get('/fetch/final/view/grades/{grade}-{section}/{period}','TeacherFetchJSON@viewFinalGradeFromSection');
//view grades via student ID
Route::get('/fetch/final/view/reportcard','TeacherFetchJSON@viewReportCard');
Route::get('/fetch/final/view/reportcard/{grade}-{section}/{studentid}','TeacherFetchJSON@viewReportCard');
//generate pdf using grade and section
Route::get('/fetch//grades/pdf/{grade}-{section}','TeacherFetchJSON@viewGradesInPdf');
//View students who are enrolled
Route::get('/fetch/enrolled/students','AdminController@viewEnrolledStudents');
//post edited student
Route::post('/aenrollment/update/edited/student', 'AdminController@postEditedStudent')->name('posteditstudents')->middleware(\Barryvdh\Cors\HandleCors::class);
//view all tasks using period subj and section
Route::get('/fetch/tasks/grades','TeacherFetchJSON@viewAllTasksBySection');
Route::get('/fetch/tasks/grades/{subj}/{grade}-{section}/{period}','TeacherFetchJSON@viewAllTasksBySection');
//Delete students who are enrolled
Route::get('/fetch/delete/students/{sid}','AdminController@DeleteEnrolledStudents');
//view attendance using date range
Route::get('/view/tattendance', 'TeacherController@viewAttendanceInDateRange');
Route::get('/view/tattendance/{grade}-{section}/{startdate}/{enddate}', 'TeacherController@viewAttendanceInDateRange');
//get device id
Route::get('/fetch/device/{id}', 'ApiController@getDeviceId');
Route::post('/post/device/create/{id}', 'ApiController@createDeviceId')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('/post/device/update/{id}', 'ApiController@updateDeviceId')->middleware(\Barryvdh\Cors\HandleCors::class);

//admin show student list
Route::get('/agrading/list/{subj}/{grade}-{section}/tasklist/{id}', 'AdminController@showStudentList');
Route::get('/agrading/list/{subj}/{grade}-{section}', 'AdminController@gradingList');
Route::get('/agrading/list/{subj}/{grade}-{section}/qualitative/score/{id}/{period}/{schoolYear}', 'AdminController@qualitativeStudentScore');
//admin fetch score
Route::post('/afetch/score/students', 'AdminController@addStudentScore')->name('aaddscore')->middleware(\Barryvdh\Cors\HandleCors::class);
//admin add task
Route::post('/afetch/add/task', 'AdminController@addTeacherTask')->name('aaddtask')->middleware(\Barryvdh\Cors\HandleCors::class);
//viewing of grades from section and grade
Route::get('/afetch/final/view/grades','AdminController@viewFinalGradeFromSection2')->name('viewfinalgradefromsection2');
Route::get('/afetch/final/view/grades/{grade}-{section}/{period}','AdminController@viewFinalGradeFromSection2');
//admin generate pdf using grade and section
Route::get('/afetch//grades/pdf/{grade}-{section}','AdminController@viewGradesInPdfManual')->middleware(\Barryvdh\Cors\HandleCors::class);
//admin view all tasks using period subj and section
Route::get('/afetch/tasks/grades','AdminController@viewAllTasksBySection');
Route::get('/afetch/tasks/grades/{subj}/{grade}-{section}/{period}','AdminController@viewAllTasksBySection');
//get task only for specific student
Route::get('profile/student/{sid}/{subj}/{period}', 'StudentController@profile');
//control editing of grades of teacher via admin
Route::post('/config/setting/grading_teacher_edit', 'TeacherController@gradingteacheredit')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('/api/mobile/config/setting/grading_teacher_edit', 'TeacherController@gradingteacheredit');
//control viewing of reportcard to teachers via admin
Route::post('/config/setting/reportcard_view_teacher', 'TeacherController@viewingreportcardtoteacher')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('/api/mobile/config/setting/reportcard_view_teacher', 'TeacherController@viewingreportcardtoteacher')->middleware(\Barryvdh\Cors\HandleCors::class);
//control viewing of reportcard to parent
Route::post('/config/setting/reportcard_view_parent', 'TeacherController@viewingreportcardtoparent')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('/api/mobile/config/setting/reportcard_view_parent', 'TeacherController@viewingreportcardtoparent')->middleware(\Barryvdh\Cors\HandleCors::class);
//edit task for teacher
Route::post('/fetch/score/edit/students', 'TeacherFetchJSON@editStudentScore')->name('editScore')->middleware(\Barryvdh\Cors\HandleCors::class);
//delete task for teacher
Route::post('/fetch/score/delete/students', 'TeacherFetchJSON@deleteStudentScore')->name('deleteScore')->middleware(\Barryvdh\Cors\HandleCors::class);
//give task grade for 1 student to mobile
Route::get('/api/grade/list/task/{grade}-{section}/{sid}/{period}/{subj}', 'TeacherFetchJSON@mobileViewTaskGrades')->middleware(\Barryvdh\Cors\HandleCors::class);
//give final grade for 1 student to mobile
Route::get('/api/grade/list/finalgrade/{grade}-{section}/{sid}', 'TeacherFetchJSON@mobileViewFinalGrades')->middleware(\Barryvdh\Cors\HandleCors::class);
//give task grade for grade/section to mobile
Route::get('/api/mchat/grade/section/task/{grade}-{section}/{period}/{subj}', 'TeacherFetchJSON@mobileViewInGradeSectionTaskGrades')->middleware(\Barryvdh\Cors\HandleCors::class);
//give final grade for grade/section to mobile
Route::get('/api/mchat/grade/section/finalgrade/{grade}-{section}/{period}', 'TeacherFetchJSON@mobileViewInGradeSectionFinalGrades')->middleware(\Barryvdh\Cors\HandleCors::class);
//convert PDF final grade for grade/section to mobile
Route::get('/api/mchat/grade/section/finalgrade/pdf/{grade}-{section}', 'TeacherFetchJSON@mobileConvertPDFFinalGrades')->middleware(\Barryvdh\Cors\HandleCors::class);
//edit teacher profile view in admin
Route::get('/tprofile/edit/{tid}', 'AdminController@editprofileteacher')->middleware(\Barryvdh\Cors\HandleCors::class);
//edit teacher profile post in admin
Route::post('/tprofile/edit/send', 'AdminController@posteditprofileteacher')->name('teachereditpost')->middleware(\Barryvdh\Cors\HandleCors::class);
//delete teacher profile post in admin
Route::post('/tprofile/delete/teacher', 'AdminController@deleteprofileteacher')->name('teacherdeletepost')->middleware(\Barryvdh\Cors\HandleCors::class);
//compute final grades for all students
Route::post('/tprofile/compute/final/all', 'AdminController@computeFinalGradesForAllStudents')->name('computeallgrades')->middleware(\Barryvdh\Cors\HandleCors::class);
//check if grade can be viewed for teachers or parents
Route::get('/api/check/grade/view', 'TeacherFetchJSON@mobileCheckIfGradesViewableByTeachersOrParents')->middleware(\Barryvdh\Cors\HandleCors::class);
//check mobile latest version
Route::get('/api/check/mobile/latest/version', 'TeacherFetchJSON@mobileVersionCheck')->middleware(\Barryvdh\Cors\HandleCors::class);
//start date for school year for students
Route::get('/admin/schoolyear/startdate/{startdate}', 'AdminController@schoolYearStartDate')->middleware(\Barryvdh\Cors\HandleCors::class);
//end date for school year for students
Route::get('/admin/schoolyear/enddate/{enddate}', 'AdminController@schoolYearEndDate')->middleware(\Barryvdh\Cors\HandleCors::class);
//check mobile latest version for iOS
Route::get('/api/check/mobile/latest/ios/version', 'TeacherFetchJSON@iOSVersionCheck')->middleware(\Barryvdh\Cors\HandleCors::class);
//select quarter for parents view of report card
Route::get('/admin/quarter/select/{period_select}', 'AdminController@quarterSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
//select first quarter for grade view on parents view
Route::post('/admin/quarter/select/first/period', 'AdminController@firstQuarterSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('/api/mobile/admin/quarter/select/first/period', 'AdminController@firstQuarterSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
//select 2nd quarter for grade view on parents view
Route::post('/admin/quarter/select/second/period', 'AdminController@secondQuarterSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('/api/mobile/admin/quarter/select/second/period', 'AdminController@secondQuarterSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
//select 3rd quarter for grade view on parents view
Route::post('/admin/quarter/select/third/period', 'AdminController@thirdQuarterSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('/api/mobile/admin/quarter/select/third/period', 'AdminController@thirdQuarterSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
//select 4th quarter for grade view on parents view
Route::post('/admin/quarter/select/fourth/period', 'AdminController@fourthQuarterSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('/api/mobile/admin/quarter/select/fourth/period', 'AdminController@fourthQuarterSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
//select final grades view on parents view
Route::post('/admin/quarter/select/finalgrade/period', 'AdminController@finalGradeViewSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
Route::post('/api/mobile/admin/quarter/select/finalgrade/period', 'AdminController@finalGradeViewSelectForParents')->middleware(\Barryvdh\Cors\HandleCors::class);
//create api posting nofiticatioin for mobile use
Route::post('api/admin/mobile/notif/send', 'AdminController@notificationPostForMobile')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
//notif testing for mobile
Route::post('api/admin/notif/test', 'AdminController@notificationTestingForMobile')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
//get data from config for mobile use
Route::get('/api/config/fetch/data', 'AdminController@getConfigDatas')->middleware(\Barryvdh\Cors\HandleCors::class);
//insert activity users
Route::post('/api/post/activity', 'ApiController@storeActivity')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
//view teachers from activity users
Route::get('/api/activity/teachers', 'ApiController@getTeachersFromActivity')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
//view parents from activity  
Route::get('/api/activity/parents', 'ApiController@getParentsFromActivity')->middleware(\Barryvdh\Cors\HandleCors::class, 'auth:api');
//view activity user
Route::get('/activity_user', 'AdminController@showActivityUser');
//count activity user
Route::get('api/activity_user/count/{startdate}/{enddate}', 'ApiController@getActivityCount');
Route::get('web/activity_user/count/{startdate}/{enddate}', 'AdminController@showActivityCount');