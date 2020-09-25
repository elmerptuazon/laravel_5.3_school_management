<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use Auth;
use DB;
use DateTime;
use App\Http\Controllers\ApiController;




class TeacherController extends NavigationController
{
    //
    public function index(Request $request){

        ApiController::storeActivityForWeb(Auth::user()->id, Auth::user()->type, 'replyslip');
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
        //SELECT * from homeworks where teacher_id = :t_id and pubdate = 
    // ( SELECT pubdate from homeworks where pubdate <= :datenow and teacher_id = :t_id order by pubdate DESC limit 1 ) 
    // ORDER by grade DESC, section Desc
    $datenow = date('Y-m-d');
    $pubdate = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate <= ?', $datenow)
         ->whereRaw('teacher_id= ? ', $data['teacheruser']->id )
        ->orderByRaw('pubdate  DESC')
        ->first();
        if(!$pubdate) {
            $targetpubdate = $datenow;
        } else {
            $targetpubdate = $pubdate->pubdate;
        }
        
        $data['prev'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate < ?', $targetpubdate)
         ->whereRaw('teacher_id= ? ', $data['teacheruser']->id )

        ->orderByRaw('pubdate  DESC')
        ->first(); 
        $data['next'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate > ?', $targetpubdate)
         ->whereRaw('teacher_id= ? ', $data['teacheruser']->id )
        ->orderByRaw('pubdate  ASC')
        ->first(); 

    $data['homeworks'] = DB::table('homeworks')
    ->select('*')
    // ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
    // ->where('grade', $data['studentuser']->grade)
    ->where('teacher_id', $data['teacheruser']->id)
    ->where('pubdate', $targetpubdate)
    ->get();

    $data['subjects'] = DB::table('teacher_subj')
    ->select('subj','grade','section')
    ->where('t_id', $data['teacheruser']->id)
    // ->where('section', $data['studentuser']->section)
    // ->groupBy('subject')
    // ->orderBy('rankorder', 'desc')
    ->get();
    foreach($data['subjects'] as $subject){
        if($subject->subj == 'homeroom'){
           $data['attendance'] = 1;
           $data['attendanceGrade'] = $subject->grade;
           $data['attendanceSection'] = $subject->section;
        }else{
            $data['attendance'] = '';
        }
    }

    //SELECT teacher_subj.*, subjects.* from teacher_subj left join subjects on teacher_subj.subj = subjects.subject and teacher_subj.grade = subjects.grade and teacher_subj.section = subjects.section where teacher_subj.t_id = 10022 and subjects.monday = 1
    $day = strtolower(date('l'));
    $data['schedule'] = DB::table('teacher_subj')
    // ->select('teacher_subj.subj','teacher_subj.grade','teacher_subj.section','subjects.schedule')
    ->select('teacher_subj.t_id','subjects.*')
    // ->leftJoin('subjects', 'teacher_subj.subj', '=', 'subjects.subject')
    ->leftJoin('subjects', function($join){
        $join->on('teacher_subj.subj', '=', 'subjects.subject');
        $join->on('teacher_subj.grade','=','subjects.grade'); 
        $join->on('teacher_subj.section','=','subjects.section'); 
    })
    ->where('teacher_subj.t_id',$data['teacheruser']->id)
    ->where($day, 1)
    ->orderByRaw('schedule desc')
    ->get();

    $data['day']=$day;
    //get calendar all
    $data['months'] = DB::table('calendar')
    ->select('month')
    ->groupBy('month')
    // ->orderBy('month(date)','asc')
    ->orderBy(DB::raw('Month(date)'))
    ->get();
    foreach($data['months'] as  $month){
            $data['calendar'][$month->month] = DB::table('calendar')
             ->select('*')
             ->where('month',$month->month)
                    ->orderBy('date','desc')
                    ->get();

                    foreach($data['calendar'][$month->month] as $key=> $colorevent) {
                        if($colorevent->eventtype == 'noclass'){$data['calendar'][$month->month][$key]->color = '#dd4b39';}
                        elseif($colorevent->eventtype == 'activity'){$data['calendar'][$month->month][$key]->color = '#3c8dbc';}
                        elseif($colorevent->eventtype == 'academic'){$data['calendar'][$month->month][$key]->color = '#00a65a';}
                        elseif($colorevent->eventtype == 'extra'){$data['calendar'][$month->month][$key]->color = '#f39c12';}
                    }

    }
    $data['grading_teacher_edit'] = DB::table('config')
        ->select('status')
        ->where('config_name', 'grading_teacher_edit')
        ->first();

    return view('toverview')->with($data);
}else{
    Auth::logout();
    return redirect('/login');
    }
    }

    public function overviewDate(Request $request ,$date) {
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        // dont forget sanitaize and validate date for production later
        $data['page_title'] = 'OVERVIEW - '.$date;
        if (Auth::check() ) {
               //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set

                $data['user'] = Auth::user();
                //query for the student info
                $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
                        //query for homework in overview
    
        $data['homeworks'] = DB::table('homeworks')
        ->select('*')
        // ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
        // ->where('grade', $data['studentuser']->grade)
        ->where('teacher_id', $data['teacheruser']->id)
        ->where('homeworks.pubdate', $date)
        ->get();
        $data['pubdate'] = '';
        $data['prev'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate < ?', $date)
        ->whereRaw('teacher_id= ? ', $data['teacheruser']->id )
        ->orderByRaw('pubdate  DESC')
        ->first(); 
        $data['next'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate > ?', $date)
        ->whereRaw('teacher_id= ? ', $data['teacheruser']->id )
        ->orderByRaw('pubdate  ASC')
        ->first(); 

        //query for the schdule
        $day = strtolower(date('l', strtotime($date)));
        // $data['schedule'] = DB::table('subjects')
        // ->select('subject','grade','section','schedule')
        // ->where('grade', 4)
        // ->where('section', 'j')
        // ->where($day, 1)
        // ->orderBy('schedule', 'asc')
        // ->get();

        $data['schedule'] = DB::table('teacher_subj')
        // ->select('teacher_subj.subj','teacher_subj.grade','teacher_subj.section','subjects.schedule')
        ->select('teacher_subj.t_id','subjects.*')
        // ->leftJoin('subjects', 'teacher_subj.subj', '=', 'subjects.subject')
        ->leftJoin('subjects', function($join){
            $join->on('teacher_subj.subj', '=', 'subjects.subject');
            $join->on('teacher_subj.grade','=','subjects.grade'); 
            $join->on('teacher_subj.section','=','subjects.section'); 
        })
        ->where('teacher_subj.t_id',$data['teacheruser']->id)
        ->where($day, 1)
        ->orderByRaw('schedule desc')
        ->get();
        $data['day']=$day;
        // foreach($classes as $class){
        //     $data['schedule1'] = DB::table('subjects')
        //     ->select('subject','grade','section','schedule')
        //     ->where('subject',$class->subj)
        //     ->where('grade', $class->grade)
        //     ->where('section', $class->section)
        //     ->where($day, 1)
        //     ->orderBy('schedule', 'asc')
        //     ->get();
        // }
//   echo "<pre>";
//   print_r($data['schedule'] );
//   echo "</pre>";
//   echo "<hr />";
// echo "<pre>";
//   print_r($classes );
//   echo "</pre>";
//   echo "<hr />";
        //get subjects for sidemenu
        $data['subjects'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        ->where('t_id', $data['teacheruser']->id)
        // ->where('section', $data['studentuser']->section)
        // ->groupBy('subject')
        // ->orderBy('rankorder', 'desc')
        ->get();
        foreach($data['subjects'] as $subject){
            if($subject->subj == 'homeroom'){
               $data['attendance'] = 1;
               $data['attendanceGrade'] = $subject->grade;
               $data['attendanceSection'] = $subject->section;
            }else{
                $data['attendance'] = '';
            }
        }
        //get calendar all
        $data['months'] = DB::table('calendar')
        ->select('month')
        ->groupBy('month')
        // ->orderBy('month(date)','asc')
        ->orderBy(DB::raw('Month(date)'))
        ->get();
        foreach($data['months'] as  $month){
                $data['calendar'][$month->month] = DB::table('calendar')
                 ->select('*')
                 ->where('month',$month->month)
                        ->orderBy('date','desc')
                        ->get();

                        foreach($data['calendar'][$month->month] as $key=> $colorevent) {
                            if($colorevent->eventtype == 'noclass'){$data['calendar'][$month->month][$key]->color = '#dd4b39';}
                            elseif($colorevent->eventtype == 'activity'){$data['calendar'][$month->month][$key]->color = '#3c8dbc';}
                            elseif($colorevent->eventtype == 'academic'){$data['calendar'][$month->month][$key]->color = '#00a65a';}
                            elseif($colorevent->eventtype == 'extra'){$data['calendar'][$month->month][$key]->color = '#f39c12';}
                        }
        }

        return view('toverview')->with($data);
        
        }else{
        Auth::logout();
        return redirect('/login');
        }

    }


    public function profile(Request $request, $tid=0) {

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        $data['page_title'] = 'PROFILE';
        if (Auth::check()) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set
                $currentIdent = $request->session()->get('currentIdent','default');
                $data['user'] = Auth::user();
                if($tid == 0){
                    $profileId = $currentIdent;
            }else{
                    $profileId = $tid;
            }
                if(Auth::user()->type =='t' or Auth::user()->type =='a'){
                    $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();
            }

            
                
                //query for the student info
                $data['studentuser'] = DB::table('students')->where('id', $currentIdent )->first();
                $data['teacherprofile'] = DB::table('teachers')->where('id', $profileId )->first();

                $age = DateTime::createFromFormat('Y-m-d', $data['teacherprofile']->birthdate)
                ->diff(new DateTime('now'))
                ->y;
                $data['teacherprofile']->age = $age;
                // $data['teacher_subjects'] = DB::table('subjects')
                // ->
        
        //get subjects for sidemenu
        // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
        // $data['subjects'] = DB::table('subjects')
        // ->select('subject')
        // ->where('grade', $data['studentuser']->grade)
        // ->where('section', $data['studentuser']->section)
        // ->groupBy('subject')
        // ->orderBy('rankorder', 'desc')
        // ->get();
            if(Auth::user()->type =='t' ){
           //get subjects for sidemenu
           $data['subjects'] = DB::table('teacher_subj')
           ->select('subj','grade','section')
           ->where('t_id', $data['teacherprofile']->id)
           // ->where('section', $data['studentuser']->section)
           // ->groupBy('subject')
           // ->orderBy('rankorder', 'desc')
           ->get();
           foreach($data['subjects'] as $subject){
            if($subject->subj == 'homeroom'){
               $data['attendance'] = 1;
               $data['attendanceGrade'] = $subject->grade;
               $data['attendanceSection'] = $subject->section;
            }else{
                $data['attendance'] = '';
            }
        }
           
           $data['classes'] = DB::table('teacher_subj')
           ->select('subj','grade','section')
           ->where('t_id', $data['teacherprofile']->id)
           // ->where('section', $data['studentuser']->section)
           // ->groupBy('subject')
           // ->orderBy('rankorder', 'desc')
           ->get();

        }elseif(Auth::user()->type =='s'   ||Auth::user()->type =='p'    ){
            $data['subjects'] = DB::table('subjects')
            ->select('subject')
            ->where('grade', $data['studentuser']->grade)
            ->where('section', $data['studentuser']->section)
            ->groupBy('subject')
            ->orderBy('rankorder', 'desc')
            ->get();

            $data['classes'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->where('t_id', $data['teacherprofile']->id)
            // ->where('section', $data['studentuser']->section)
            // ->groupBy('subject')
            // ->orderBy('rankorder', 'desc')
            ->get();
        }elseif(Auth::user()->type =='a') {
            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();

           foreach($data['subjects'] as $subject){
            if($subject->subj == 'homeroom'){
               $data['attendance'] = 1;
               $data['attendanceGrade'] = $subject->grade;
               $data['attendanceSection'] = $subject->section;
            }else{
                $data['attendance'] = '';
            }
        }

        $data['classes'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        
        // ->where('section', $data['studentuser']->section)
        // ->groupBy('subject')
        // ->orderBy('rankorder', 'desc')
        ->get();

        

    
        }
        $data['grading_teacher_edit'] = DB::table('config')
        ->select('status')
        ->where('config_name', 'grading_teacher_edit')
        ->first();

        $data['reportcard_view_parent'] = DB::table('config')
        ->select('status')
        ->where('config_name', 'reportcard_view_parent')
        ->first();

        $data['reportcard_view_teacher'] = DB::table('config')
        ->select('status')
        ->where('config_name', 'report_card_teacher')
        ->first();

        $data['school_start_date'] = DB::table('config')
        ->select('school_date')
        ->where('config_name', 'startdate_school_year')
        ->first();

        $data['school_end_date'] = DB::table('config')
        ->select('school_date')
        ->where('config_name', 'enddate_school_year')
        ->first();

        $data['teacher_clicked_id'] = $tid;


        $data['first_quarter_selected'] = DB::table('config')
        ->select('status')
        ->where('config_name', 'first_quarter_select')
        ->first();

        $data['second_quarter_selected'] = DB::table('config')
        ->select('status')
        ->where('config_name', 'second_quarter_select')
        ->first();

        $data['third_quarter_selected'] = DB::table('config')
        ->select('status')
        ->where('config_name', 'third_quarter_select')
        ->first();

        $data['fourth_quarter_selected'] = DB::table('config')
        ->select('status')
        ->where('config_name', 'fourth_quarter_select')
        ->first();

        $data['final_grade_selected'] = DB::table('config')
        ->select('status')
        ->where('config_name', 'final_grade_select')
        ->first();

        }
        return view('tprofile')->with($data);
    }

    public function gradingteacheredit(Request $request) {
        $data['config_teacher_grading'] = $request->input('grading_teacher_edit');

        if($data['config_teacher_grading'] == 0) {
            DB::table('config')
            ->where('config_name', 'grading_teacher_edit')
            ->update(['status'=>1]);
        } else if($data['config_teacher_grading'] == 1) {
            DB::table('config')
            ->where('config_name', 'grading_teacher_edit')
            ->update(['status'=>0]);
        }

        return response()->json(array(
            'isSend'=>1,
            'previousVal'=>$data['config_teacher_grading'],
            'message' => 'success'
        ));
    }

    public function viewingreportcardtoteacher(Request $request) {
        $data['config_reportcard_view_teacher'] = $request->input('reportcard_view_teacher');

        if($data['config_reportcard_view_teacher'] == 0) {  
            DB::table('config')
            ->where('config_name', 'report_card_teacher')
            ->update(['status'=>1]);
            
        } else if($data['config_reportcard_view_teacher'] == 1) {
            DB::table('config')
            ->where('config_name', 'report_card_teacher')
            ->update(['status'=>0]);
            
        }

        return response()->json(array(
            'isSend'=>2,
            'previousVal'=>$data['config_reportcard_view_teacher']
        ));
    }

    public function viewingreportcardtoparent(Request $request) {
        $data['config_view_grades_parent'] = $request->input('reportcard_view_parent');

        if($data['config_view_grades_parent'] == 0) {
            DB::table('config')
            ->where('config_name', 'reportcard_view_parent')
            ->update(['status'=>1]);
        } else if($data['config_view_grades_parent'] == 1) {
            DB::table('config')
            ->where('config_name', 'reportcard_view_parent')
            ->update(['status'=>0]);
        }

        return response()->json(array(
            'isSend'=>1,
            'previousVal'=>$data['config_view_grades_parent']
        ));
    }

    public function assignmentPost(Request $request) {
        if (Auth::check()) {
        // echo "assignment has been posted<br />";
        // print_r($request->input());
        $classArray = explode('_',$request->input('class'));
        $subject = $classArray[0];
        $grade = $classArray[1];
        $section = $classArray[2];
        $teacherid = Auth::user()->ident;
        
        $description=$request->input('description');
        $inputdate = date('Y-m-d H:i:s');   
        $pubdate = $request->input('pubdate');

        //print_r($request->input());

        // echo Auth::user()->ident;
        
        // whats needed is
        // pubdate: date , subject:varchar , grade:varchar, section:varchar, spacial:int, 

        DB::table('homeworks')->insert(
            [
                'subject' => $subject, 
                'grade' => $grade,
                'section' => $section,
                'teacher_id' => $teacherid,
                'description' => $description,
                'pubdate' => $pubdate,
                'inputdate' => $inputdate

            ]
        );

        // return redirect('/toverview');
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);
    }

    }

    public function assignmentEdit(Request $request) {
        if (Auth::check()) {
            // $request->session()->flash('status', 'Task was successful!');
            echo "test test test";
                print_r($request->input());
            

        }
    }

    public function replyslips(Request $request) {
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set

        $data['page_title'] = 'REPLYSLIPS';
        $currentIdent = $request->session()->get('currentIdent','default');
                $data['user'] = Auth::user();
                //query for the student info
                $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();
                //replyslip scope filter
                // if($data['studentuser']->grade > 0 && $data['studentuser']->grade < 13){$scope[] = "na";}
                // if($data['studentuser']->grade > 7 && $data['studentuser']->grade < 13){$scope[] = "hs";}
                // if($data['studentuser']->grade > 0 && $data['studentuser']->grade < 8){$scope[] = "gs";}
                // $myArray = implode($scope, ',');
                //query for reply slips
                $data['replyslips'] = DB::table('replyslips')
                // ->select('replyslips.*','uploads.type as type','uploads.filename as filename','replyslips_ans.rid','replyslips_ans.sid','replyslips_opt.oid','replyslips_opt.choice')
                ->select('replyslips.*','uploads.type as type','uploads.filename as filename')
                ->leftJoin('uploads', 'replyslips.upload_id', '=', 'uploads.uid')
                // ->leftJoin('replyslips_ans', 'replyslips_ans.rid', '=', 'replyslips.id')
                // ->leftJoin('replyslips_ans', function ($join) {
                //         $join->on('replyslips_ans.rid', '=', 'replyslips.id')
                //              ->where('replyslips_ans.sid', '=', session('currentIdent'));
                //         //      ->where('replyslips_ans.sid', '=', Auth::user()->ident);
                //     })
                // ->leftJoin('replyslips_opt', 'replyslips_opt.oid', '=', 'replyslips_ans.oid')
                //  ->whereIn('replyslips.grade', $scope)
                //  ->where('replyslips_ans.sid', $data['studentuser']->id)
                // ->where('replyslips.publish', '1')
               ->orderBy('id','desc')
                ->get();
                
                // $data['scope']=$scope;
                $data['testing']= session('currentIdent');
                
           //get subjects for sidemenu

           $data['subjects'] = DB::table('teacher_subj')
           ->select('subj','grade','section')
           ->where('t_id', $data['teacheruser']->id)
           // ->where('section', $data['studentuser']->section)
           // ->groupBy('subject')
           // ->orderBy('rankorder', 'desc')
           ->get();
           foreach($data['subjects'] as $subject){
            if($subject->subj == 'homeroom'){
               $data['attendance'] = 1;
               $data['attendanceGrade'] = $subject->grade;
               $data['attendanceSection'] = $subject->section;
            }else{
                $data['attendance'] = '';
            }
        }
        // print_r($data['replyslips']);
        return view('sreplyslips')->with($data);
        }else{
                Auth::logout();
                return redirect('/login');
        }
    }
    public function subjects(Request $request, $subj, $grade, $section) {
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        $data['page_title'] = 'SUBJECTS';
        $subj = str_replace('_',' ',trim($subj));
        $data['subject'] = $subj;
        $data['grade'] = $grade;
        $data['section'] = $section;

        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

            $currentIdent = session('currentIdent');
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

            $subject=$subj;
            $grade = $grade;
            $section = $section;// sanitize and validate for future deployment

        //     SELECT homeworks.*, teachers.title as tcher_title, teachers.firstname as tchr_fname,teachers.lastname as tchr_lname
        //   FROM homeworks left join teachers on homeworks.teacher_id = teachers.id where homeworks.grade =:grade and homeworks.section =:section and
        //   homeworks.subject =:subj order by homeworks.pubdate desc limit 15
// get homeworks for subject        
$data['homeworks'] = DB::table('homeworks')
->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
->where('homeworks.grade', $grade)
->where('homeworks.section', $section)
->where('homeworks.subject', $subj)
->orderBy('pubdate', 'desc')
->limit('5')

->get();

// echo "<pre>";
// print_r($data['homeworks'] );
// echo "</pre>";
// echo "<hr />";

// $users = DB::table('users')->count();

$data['totals']=DB::table('homeworks')
->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
->where('homeworks.grade', $grade)
->where('homeworks.section', $section)
->where('homeworks.subject', $subj)->count();

// echo "<pre>";
// print_r($data['totals'] );
// echo "</pre>";
// echo "<hr />";
//get tests for the subject
// SELECT tests.*, uploads.type as type, uploads.filename as filename FROM tests left join uploads on tests.upload_id = uploads.uid
//   where tests.subject= :subject and tests.grade = :grade and tests.section =:section order by date desc
  $data['tests'] = DB::table('tests')
  ->select('tests.*','uploads.type as type','uploads.filename as filename','teachers.title as tchrtitle','teachers.firstname','teachers.lastname')
  ->leftJoin('uploads','tests.upload_id','=','uploads.uid')
  ->leftJoin('teachers', 'tests.teacher_id', '=', 'teachers.id')
  ->where('tests.subject',$subj)
  ->where('tests.grade',$grade)
  ->where('tests.section',$section)
  ->orderBy('date','desc')
  ->get();

//   echo "<pre>";
//   print_r($data['tests'] );
//   echo "</pre>";
//   echo "<hr />";
  //get activitysheets
  $data['activitysheets'] = DB::table('activitysheets')
  ->select('activitysheets.*','uploads.type as type','uploads.filename as filename','teachers.title as tchrtitle','teachers.firstname','teachers.lastname')
  ->leftJoin('uploads','activitysheets.upload_id','=','uploads.uid')
  ->leftJoin('teachers', 'activitysheets.teacher_id', '=', 'teachers.id')
  ->where('activitysheets.subject',$subj)
  ->where('activitysheets.grade',$grade)
  ->where('activitysheets.section',$section)
  ->orderBy('date','desc')
  ->get();

// echo "<pre>";
//   print_r($data['activitysheets'] );
//   echo "</pre>";
//   echo "<hr />";
  //get handouts
  $data['handouts'] = DB::table('handouts')
  ->select('handouts.*','uploads.type as type','uploads.filename as filename','teachers.title as tchrtitle','teachers.firstname','teachers.lastname')
  ->leftJoin('uploads','handouts.upload_id','=','uploads.uid')
  ->leftJoin('teachers', 'handouts.teacher_id', '=', 'teachers.id')
  ->where('handouts.subject',$subj)
  ->where('handouts.grade',$grade)
  ->where('handouts.section',$section)
  ->orderBy('date','desc')
  ->get();


//   echo "<pre>";
//   print_r($data['handouts'] );
//   echo "</pre>";
//   echo "<hr />";

            //get subjects for sidemenu

     $data['subjects'] = DB::table('teacher_subj')
    ->select('subj','grade','section')
    ->where('t_id', $data['teacheruser']->id)
    // ->where('section', $data['studentuser']->section)
    // ->groupBy('subject')
    // ->orderBy('rankorder', 'desc')
    ->get();
    foreach($data['subjects'] as $subject){
        if($subject->subj == 'homeroom'){
           $data['attendance'] = 1;
           $data['attendanceGrade'] = $subject->grade;
           $data['attendanceSection'] = $subject->section;
        }else{
            $data['attendance'] = '';
        }
    }


        return view('tclass')->with($data);
}else{
        Auth::logout();
        return redirect('/login');
    }
}

public function upload(Request $request) {

//         $file = $request->file('thefile');
//         $path = $request->file('thefile')->path();

// $extension = $request->file('thefile')->extension();
$classArray = explode('_',$request->input('class'));
$subject = $classArray[0];//sanitize for spaces eg christian living
$grade = $classArray[1];
$section = $classArray[2];
$title = $request->input('title');
$period = $request->input('period');
$file = $request->file('thefile');
$date = date('Y-m-d');
$uploadtype = $request->input('utype');

if (Auth::check() ) {
    //check if session is set
    if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

    $currentIdent = session('currentIdent');
    $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

    
    if ($request->file('thefile')->isValid()) {
        $extension = $file->getClientOriginalExtension(); // getting image extension
        $path = $file->getClientOriginalName();
        $mime = $file->getClientMimeType();
        $size = $file->getClientsize();
        $newfilename = $currentIdent.'_'.date('Y-m-d').'_'.time();
        $type = 'pdf';

        $encoded2 = $this->base64url_encode($newfilename);
        $filename =$encoded2.'.'.$extension;
        
        //step 1 insert to uploads table
        $uid = DB::table('uploads')->insertGetId(
            [
                'type' => $type, 
                'filename' => $filename,
                'teacher_id' => $currentIdent
            ]
        );
        if($uid > 0){
            //step 2 - move the file to the uploaded folder
            $file->move(public_path('uploads/pdf'), $filename);
        //step 3insert into test table
        if($uploadtype == 'test'){
            $testid=DB::table('tests')->insertGetId(
                [
                'title' => $title, 
                'upload_id' => $uid,
                'publish' => 1,
                'teacher_id' => $currentIdent,
                'subject' => $subject,
                'section' =>$section,
                'grade' => $grade,
                'date' => $date,
                'period' => $period

                ]
            );
        }elseif($uploadtype == 'activitysheet'){
            $asid=DB::table('activitysheets')->insertGetId(
                [
                'title' => $title, 
                'upload_id' => $uid,
                'publish' => 1,
                'teacher_id' => $currentIdent,
                'subject' => $subject,
                'section' =>$section,
                'grade' => $grade,
                'date' => $date,
                'period' => $period

                ]
            );
        }elseif($uploadtype == 'handout'){
            $hoid=DB::table('handouts')->insertGetId(
                [
                'title' => $title, 
                'upload_id' => $uid,
                'publish' => 1,
                'teacher_id' => $currentIdent,
                'subject' => $subject,
                'section' =>$section,
                'grade' => $grade,
                'date' => $date,
                'period' => $period

                ]
            );
        }
        }

        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);





    }




}


// $get = $file->getClientMimeType();
//                 $size = $file->getClientsize();
// $tid = 10022;
// if ($request->file('thefile')->isValid()) {
//     //
//     echo "uploaded";
    
    
//                 $extension = $file->getClientOriginalExtension(); // getting image extension
//                 $path = $file->getClientOriginalName();
//                 $get = $file->getClientMimeType();
//                 $size = $file->getClientsize();
//                 $newfilename = date('Y-m-d').'_'.time().'_'.$tid;
//                 $filename =str_random(28).'.'.$extension;
//                 $encoded = base64_encode($newfilename);
//                 $filename1 =$encoded.'.'.$extension;
//                 $decoded = base64_decode($encoded).'.'.$extension;

//                 $encoded2 = $this->base64url_encode($newfilename);
//                 $filename2 =$encoded2.'.'.$extension;
//                 $decoded2 = $this->base64url_decode($encoded).'.'.$extension;


//                 echo $path;
//                 echo $newfilename." +++++++  ";
//                 echo $filename1." +++++++  ";
//                 echo $decoded;
//                 echo "<br /><hr>";

//                 echo $encoded2." +++++++  ";
//                 echo $filename2." +++++++  ";
//                 echo $decoded2." ???????????  ";
                
//                 echo base64_decode($encoded2).'.'.$extension;
//                 echo "<br /><hr>";
//                 // echo " ".$path;
//                 // echo " ".$get;
//                 echo " ".($size / 1000000). " mb ";
//                 print_r($file);
//                 //  $file->move(public_path('uploads/pdf'), $filename);
//                 // echo mime_content_type('php.gif')
//                 // $file->store('uploads/pdf/', $path);
//                 // $path = Storage::putFile('uploads', $file);
//     // echo "<br /> $path";
//     // echo "<br /> $extension";
//         }
        
    }

    private function base64url_encode($data) { 
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
      } 
      
      private function base64url_decode($data) { 
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 

      }

      public function attendance(Request $request, $grade=0, $section=0, $date = 0){

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        
        $data['page_title'] = 'ATTENDANCE';
        if($date == 0){$date = date('Y-m-d');}
        
        $data['date'] = $date;
        $data['datePrev'] = date('Y-m-d',strtotime($date)-(60*60*24) );
        $data['dateNext'] = date('Y-m-d',strtotime($date)+(60*60*24) );;
        $data['grade'] = $grade;
        $data['section'] = $section;
        // if(Auth::user()->user == 'tjslipio') {

        // } else {
            
        // }
        $data['gradesection'] = DB::table('students')->select('grade','section')->groupBy('grade','section')->orderByRaw('CAST(`grade` AS UNSIGNED) asc')->get();
        // order by CAST(`grade` AS UNSIGNED) ascs
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
        
            $currentIdent = session('currentIdent');
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

            // $date= date('Y-m-d');
            // $date= '2018-04-13';
            // $date= '2018-06-06';
            $data['students'] = DB::table('students')
            ->select('students.id','students.firstname','students.lastname', 'students.grade','students.section','attendance.date','attendance.status','attendance.time','attendance.tid')
            ->leftJoin('attendance', function ($join) use($date){
                        $join->on('attendance.sid', '=', 'students.id')
                             ->where('attendance.date', '=', $date);
                        //      ->where('replyslips_ans.sid', '=', Auth::user()->ident);
                    })
            ->where('students.grade', $grade)
            ->where('students.section', $section)
            ->orderBy('students.lastname', 'asc')
            ->get();

            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->where('t_id', $data['teacheruser']->id)
            // ->where('section', $data['studentuser']->section)
            // ->groupBy('subject')
            // ->orderBy('rankorder', 'desc')
            ->get();
            
            foreach($data['subjects'] as $subject){
                if($subject->subj == 'homeroom'){
                   $data['attendance'] = 1;
                   $data['attendanceGrade'] = $subject->grade;
                   $data['attendanceSection'] = $subject->section;
                }else{
                    $data['attendance'] = 'x';
                }
            }
                    

        // return view('tclass')->with($data);
        return view('attendance')->with($data);
        }
      }

    public function viewAttendanceInDateRange(Request $request, $grade='', $section='', $startdate='', $enddate='', $date=0){

        $data['startdate_clicked'] = $startdate;
        $data['enddate_clicked'] = $enddate;

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        
        $data['page_title'] = 'View of Attendance';
        if($date == 0){$date = date('Y-m-d');}
        
        $data['date'] = $date;
        $data['datePrev'] = date('Y-m-d',strtotime($date)-(60*60*24) );
        $data['dateNext'] = date('Y-m-d',strtotime($date)+(60*60*24) );;
        $data['grade_clicked'] = $grade;
        $data['section_clicked'] = $section;
        $data['gradesection'] = DB::table('students')->select('grade','section')->groupBy('grade','section')->orderByRaw('CAST(`grade` AS UNSIGNED) asc')->get();
        // order by CAST(`grade` AS UNSIGNED) ascs
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
        
            $currentIdent = session('currentIdent');
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

            // $date= date('Y-m-d');
            // $date= '2018-04-13';
            // $date= '2018-06-06';
            $data['students'] = DB::table('students')
            ->select('students.id','students.firstname','students.lastname', 'students.grade','students.section','attendance.date','attendance.status','attendance.time','attendance.tid')
            ->leftJoin('attendance', function ($join) use($date){
                        $join->on('attendance.sid', '=', 'students.id')
                             ->where('attendance.date', '=', $date);
                        //      ->where('replyslips_ans.sid', '=', Auth::user()->ident);
                    })
            ->where('students.grade', $grade)
            ->where('students.section', $section)
            ->orderBy('students.lastname', 'asc')
            ->get();

            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->where('t_id', $data['teacheruser']->id)
            // ->where('section', $data['studentuser']->section)
            // ->groupBy('subject')
            // ->orderBy('rankorder', 'desc')
            ->get();
            
            foreach($data['subjects'] as $subject){
                if($subject->subj == 'homeroom'){
                   $data['attendance'] = 1;
                   $data['attendanceGrade'] = $subject->grade;
                   $data['attendanceSection'] = $subject->section;
                }else{
                    $data['attendance'] = 'x';
                }
            }

            //added by elmer
                    
            $data['grade_selected'] = DB::table('students')
            ->select('id', 'firstname', 'lastname')
            ->where('grade', $grade)
            ->where('section', $section)
            ->orderBy('lastname')
            ->get();

            $data['month_selected'] = DB::table('attendance')
            ->select(DB::raw('MONTHNAME(date) as MonthName, DAY(date) as MonthDay'))
            ->whereBetween('date', [$startdate, $enddate])
            ->groupBy(DB::raw('MonthName, MonthDay'))
            ->orderBy('date')
            ->get();

            $data['month_selected_per_student'] = DB::table('attendance')
            ->select(DB::raw('MONTHNAME(date) as MonthName, DAY(date) as MonthDay, sid, status'))
            ->whereBetween('date', [$startdate, $enddate])
            ->orderBy('date')
            ->get();

            $data['checker'] = DB::table('attendance')
            ->select(DB::raw('MONTHNAME(date) as MonthName, DAY(date) as MonthDay, sid, status'))
            ->whereBetween('date', [$startdate, $enddate])
            ->orderBy('date')
            ->first();

            if(isset($data['checker'])) {

                foreach($data['grade_selected'] as $grade) {
                    foreach($data['month_selected'] as $month_list) {
                        $data['date_rearrange'][$grade->id][$month_list->MonthName][$month_list->MonthDay ] = '';
                    }
                }

                foreach($data['grade_selected'] as $grade) {
                    foreach($data['month_selected_per_student'] as $month_list) {
                        if($grade->id == $month_list->sid) {
                            $data['date_rearrange'][$grade->id][$month_list->MonthName][$month_list->MonthDay ] = $month_list->status;
                        }
                    }
                }

                foreach($data['date_rearrange'] as $studentId=>$val) {
                    $data['total_absence'][$studentId] = 0;
                    $data['total_present'][$studentId] = 0;
                    $data['total_late'][$studentId] = 0;

                    foreach($val as $MonthName=> $jval) {
                        foreach($jval as $MonthDay=>$kval) {
                            if($kval == 'present' || $kval == 'late') {
                                $data['total_present'][$studentId] = $data['total_present'][$studentId] + 1;
                            } 
                            
                            if($kval == 'absent') {
                                $data['total_absence'][$studentId] = $data['total_absence'][$studentId] + 1;
                            }

                            if($kval == 'late') {
                                $data['total_late'][$studentId] = $data['total_late'][$studentId] + 1;
                            }
                        }
                    }
                }

            }

            

        // return response()->json($data);
        return view('tviewattendance')->with($data);
        }
    }
      

      public function attendanceSubmit(Request $request){

        $date = $request->input('date');
        $grade = $request->input('grade');
        $section = $request->input('section');
        $time = date('H:i:s');
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
        
            $currentIdent = session('currentIdent');
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();


    $students = DB::table('students')
    ->select('id')
    ->where('students.grade',$grade)
    ->where('students.section',$section)
    ->orderBy('students.lastname', 'asc')
    ->get();

                    foreach($students as $student){
                        //check if radio button is set
                        if($request->input('student-'.$student->id) !== null or $request->input('student-'.$student->id) != ''){
                            // echo "<br />qweqwe| ".$request->input('reply-'.$student->id);
        
                            //check if ans already existing
                            $check1 = DB::table('attendance')
                            ->select('id')
                            ->where('sid',$student->id)
                            ->where('date',$date)
                            ->first();
                            //edit if existing
                            if($check1){
                                DB::table('attendance')
                                ->where('id', $check1->id)
                                ->update([
                                    'status' => $request->input('student-'.$student->id),
                                    'time' => $time
                                ]);
                            }else{
                                //insert the fucker
                                DB::table('attendance')->insert(
                                    [
                                        'date' => $date, 
                                        'status' => $request->input('student-'.$student->id),
                                        'time' => $time,
                                        'sid' => $student->id,
                                        'tid' =>$data['teacheruser']->id

                                    ]
                                );
                            }
        
                        }
                    }


                    $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);
                }

      }

      public function gradingList(Request $request,$subj=0,$grade='',$section=''){
        ApiController::storeActivityForWeb(Auth::user()->id, Auth::user()->type, 'subject');
        $subj = str_replace('_',' ',trim($subj));
        $data['subject'] = $subj;
        $data['grade']= $grade;
        $data['section']= $section;
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        
        $data['page_title'] = 'Grading';

        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
        
            $currentIdent = session('currentIdent');
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();


            $data['students'] = DB::table('students')
            // ->select('students.id','students.firstname','students.lastname', 'students.grade','students.section','attendance.date','attendance.status','attendance.time','attendance.tid')
            ->select('students.id','students.firstname','students.lastname', 'students.grade','students.section')
            // ->leftJoin('attendance', function ($join) use($date){
            //             $join->on('attendance.sid', '=', 'students.id')
            //                  ->where('attendance.date', '=', $date);
            //             //      ->where('replyslips_ans.sid', '=', Auth::user()->ident);
            //         })
            ->where('students.grade', $grade)
            ->where('students.section', $section)
            ->orderBy('students.lastname', 'asc')
            ->get();

            $subject_id = DB::table('subjects')
            ->select('id')
            ->where('subject',$subj)
            ->where('grade',$grade)
            ->where('section',$section)
            ->first();
            $subj_id = $subject_id->id;

            $periodCount = DB::table('grading_cumulative')
            ->select('period')
            ->where('sid', 1211009)
            ->groupBy('period')
            ->get()->toArray();
            // print_r($periodCount);
                
            foreach($data['students'] as $key=>$student){
                $data['students'][$key]->firstqtr = new \stdClass();
                $data['students'][$key]->secondqtr = new \stdClass();
                $data['students'][$key]->thirdqtr = new \stdClass();
                $data['students'][$key]->fourthqtr = new \stdClass();
                // echo$student->id;
                // cumulative grades for the 1st quarter
                $assignmentscore = DB::table('grading_cumulative')
                ->select('score') 
                //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                // ->where('sid', $student->id)
                ->where('sid', $student->id)
                ->where('subj_id', $subj_id)
                ->where('type_id','1')
                ->where('period', '1')
                ->first();
                if($assignmentscore !== NULL){
                    $data['students'][$key]->firstqtr->assignments = $assignmentscore->score; 
                }else{
                    $data['students'][$key]->firstqtr->assignments = 0;
                }

                    $quizscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','2')
                    ->where('period', '1')
                    ->first();
                    if($quizscore !== NULL){
                        $data['students'][$key]->firstqtr->quiz = $quizscore->score; 
                    }else{
                        $data['students'][$key]->firstqtr->quiz = 0;
                    }

                    $recitationscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','3')
                    ->where('period', '1')
                    ->first();
                    if($quizscore !== NULL){
                        $data['students'][$key]->firstqtr->recitation = $recitationscore->score; 
                    }else{
                        $data['students'][$key]->firstqtr->recitation = 0;
                    }

                    $projectscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','4')
                    ->where('period', '1')
                    ->first();
                    if($projectscore !== NULL){
                        $data['students'][$key]->firstqtr->projects = $projectscore->score; 
                    }else{
                        $data['students'][$key]->firstqtr->projects = 0;
                    }

                    $examscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','5')
                    ->where('period', '1')
                    ->first();
                    if($examscore !== NULL){
                        $data['students'][$key]->firstqtr->exams = $examscore->score; 
                    }else{
                        $data['students'][$key]->firstqtr->exams = 0;
                    }

                    $finalscore = DB::table('grading_final')
                    ->select('final_grade') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id',$subj_id)
                    ->where('period', '1')
                    ->first();
                    if($finalscore !== NULL){
                        $data['students'][$key]->firstqtr->finalgrade = $finalscore->final_grade; 
                    }else{
                        $data['students'][$key]->firstqtr->finalgrade = 0;
                    }



                // cumulative grades for the 2nd quarter
                $assignmentscore = DB::table('grading_cumulative')
                ->select('score') 
                //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                // ->where('sid', $student->id)
                ->where('sid', $student->id)
                ->where('subj_id', $subj_id)
                ->where('type_id','1')
                ->where('period', '2')
                ->first();
                if($assignmentscore !== NULL){
                    $data['students'][$key]->secondqtr->assignments = $assignmentscore->score; 
                }else{
                    $data['students'][$key]->secondqtr->assignments = 0;
                }

                    $quizscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','2')
                    ->where('period', '2')
                    ->first();
                    if($quizscore !== NULL){
                        $data['students'][$key]->secondqtr->quiz = $quizscore->score; 
                    }else{
                        $data['students'][$key]->secondqtr->quiz = 0;
                    }

                    $recitationscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','3')
                    ->where('period', '2')
                    ->first();
                    if($quizscore !== NULL){
                        $data['students'][$key]->secondqtr->recitation = $recitationscore->score; 
                    }else{
                        $data['students'][$key]->secondqtr->recitation = 0;
                    }

                    $projectscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','4')
                    ->where('period', '2')
                    ->first();
                    if($projectscore !== NULL){
                        $data['students'][$key]->secondqtr->projects = $projectscore->score; 
                    }else{
                        $data['students'][$key]->secondqtr->projects = 0;
                    }

                    $examscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','5')
                    ->where('period', '2')
                    ->first();
                    if($examscore !== NULL){
                        $data['students'][$key]->secondqtr->exams = $examscore->score; 
                    }else{
                        $data['students'][$key]->secondqtr->exams = 0;
                    }

                    $finalscore = DB::table('grading_final')
                    ->select('final_grade') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id',$subj_id)
                    ->where('period', '2')
                    ->first();
                    if($finalscore !== NULL){
                        $data['students'][$key]->secondqtr->finalgrade = $finalscore->final_grade; 
                    }else{
                        $data['students'][$key]->secondqtr->finalgrade = 0;
                    }
                
                
                // cumulative grades for the 3rd quarter
                $assignmentscore = DB::table('grading_cumulative')
                ->select('score') 
                //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                // ->where('sid', $student->id)
                ->where('sid', $student->id)
                ->where('subj_id', $subj_id)
                ->where('type_id','1')
                ->where('period', '3')
                ->first();
                if($assignmentscore !== NULL){
                    $data['students'][$key]->thirdqtr->assignments = $assignmentscore->score; 
                }else{
                    $data['students'][$key]->thirdqtr->assignments = 0;
                }

                    $quizscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','2')
                    ->where('period', '3')
                    ->first();
                    if($quizscore !== NULL){
                        $data['students'][$key]->thirdqtr->quiz = $quizscore->score; 
                    }else{
                        $data['students'][$key]->thirdqtr->quiz = 0;
                    }

                    $recitationscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','3')
                    ->where('period', '3')
                    ->first();
                    if($quizscore !== NULL){
                        $data['students'][$key]->thirdqtr->recitation = $recitationscore->score; 
                    }else{
                        $data['students'][$key]->thirdqtr->recitation = 0;
                    }

                    $projectscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','4')
                    ->where('period', '3')
                    ->first();
                    if($projectscore !== NULL){
                        $data['students'][$key]->thirdqtr->projects = $projectscore->score; 
                    }else{
                        $data['students'][$key]->thirdqtr->projects = 0;
                    }

                    $examscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','5')
                    ->where('period', '3')
                    ->first();
                    if($examscore !== NULL){
                        $data['students'][$key]->thirdqtr->exams = $examscore->score; 
                    }else{
                        $data['students'][$key]->thirdqtr->exams = 0;
                    }

                    $finalscore = DB::table('grading_final')
                    ->select('final_grade') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id',$subj_id)
                    ->where('period', '3')
                    ->first();
                    if($finalscore !== NULL){
                        $data['students'][$key]->thirdqtr->finalgrade = $finalscore->final_grade; 
                    }else{
                        $data['students'][$key]->thirdqtr->finalgrade = 0;
                    }
                
                // cumulative grades for the 4th quarter
                $assignmentscore = DB::table('grading_cumulative')
                ->select('score') 
                //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                // ->where('sid', $student->id)
                ->where('sid', $student->id)
                ->where('subj_id', $subj_id)
                ->where('type_id','1')
                ->where('period', '4')
                ->first();
                if($assignmentscore !== NULL){
                    $data['students'][$key]->fourthqtr->assignments = $assignmentscore->score; 
                }else{
                    $data['students'][$key]->fourthqtr->assignments = 0;
                }

                    $quizscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','2')
                    ->where('period', '4')
                    ->first();
                    if($quizscore !== NULL){
                        $data['students'][$key]->fourthqtr->quiz = $quizscore->score; 
                    }else{
                        $data['students'][$key]->fourthqtr->quiz = 0;
                    }

                    $recitationscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','3')
                    ->where('period', '4')
                    ->first();
                    if($quizscore !== NULL){
                        $data['students'][$key]->fourthqtr->recitation = $recitationscore->score; 
                    }else{
                        $data['students'][$key]->fourthqtr->recitation = 0;
                    }

                    $projectscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','4')
                    ->where('period', '4')
                    ->first();
                    if($projectscore !== NULL){
                        $data['students'][$key]->fourthqtr->projects = $projectscore->score; 
                    }else{
                        $data['students'][$key]->fourthqtr->projects = 0;
                    }

                    $examscore = DB::table('grading_cumulative')
                    ->select('score') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id', $subj_id)
                    ->where('type_id','5')
                    ->where('period', '4')
                    ->first();
                    if($examscore !== NULL){
                        $data['students'][$key]->fourthqtr->exams = $examscore->score; 
                    }else{
                        $data['students'][$key]->fourthqtr->exams = 0;
                    }

                    $finalscore = DB::table('grading_final')
                    ->select('final_grade') 
                    //  ->leftJoin('grading_ctype', 'grading_cumulative.type_id', '=', 'grading_ctype.CT_id')
                    ->where('sid', $student->id)
                    ->where('subj_id',$subj_id)
                    ->where('period', '4')
                    ->first();
                    if($finalscore !== NULL){
                        $data['students'][$key]->fourthqtr->finalgrade = $finalscore->final_grade; 
                    }else{
                        $data['students'][$key]->fourthqtr->finalgrade = 0;
                    }



                    
            }
            //  echo $data['students'][0]->grading[0]->sid;
            //  echo $student->id;
           

// SELECT * FROM `grading_cumulative` left join gading_ctype on grading_cumulative.type_id = gading_ctype.CT_id left join subjects on gading_ctype.subj_id = subjects.id

            


            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->where('t_id', $data['teacheruser']->id)
            // ->where('section', $data['studentuser']->section)
            // ->groupBy('subject')
            // ->orderBy('rankorder', 'desc')
            ->get();

            //added by elmer
            $data['tasktype_names'] = DB::table('tasks')
            ->select('id', 'task_type', 'task_title')
            ->where('task_grade','=',$grade)
            ->where('task_section','=', $section)
            ->where('task_subject','=', $subj)
            ->get();
            
            $data['task_type_type_name'] = DB::table('task_type')
            ->select('type_name', 'task_title')
            ->where('subject','=', $subj)
            ->get();


            $data['qualitative_types_behavior'] = DB::table('qualitative_types')
            ->select('id','type_name')
            ->where('category','Behavior_in_school')
            ->get();

            $data['qualitative_types_quality'] = DB::table('qualitative_types')
            ->select('id','type_name')
            ->where('category','Quality_worker')
            ->get();

            $data['qualitative_types_collaborative'] = DB::table('qualitative_types')
            ->select('id','type_name')
            ->where('category','Collaborative_peer')
            ->get();

            $data['qualitative_types_self'] = DB::table('qualitative_types')
            ->select('id','type_name')
            ->where('category','Self_directed_learner')
            ->get();

            $data['grading_teacher_edit'] = DB::table('config')
            ->select('status')
            ->where('config_name', 'grading_teacher_edit')
            ->first();

                // echo json_encode($data);
                return view('tgradinglist')->with($data);
            

        }
      }

      public function postGrades(Request $request){

        if (Auth::check()) {
            $classArray = explode('_',$request->input('class'));
        $subject = $classArray[0];
        $grade = $classArray[1];
        $section = $classArray[2];
        $teacherid = Auth::user()->ident;

        $sid = $request->input('sid');
        $period = $request->input('period');
        $sy = $request->input('sy');

        // $request->input('type_id');
        $subjectId = DB::table('subjects')
        ->select('id')
        ->where('subject',$subject)
        ->where('grade',$grade)
        ->where('section',$section)
        ->first();
        $subj_id = $subjectId->id;
        $assignments = $request->input('assignments');
        $quiz = $request->input('quiz');
        $recitation = $request->input('recitation');
        $projects = $request->input('projects');
        $exams = $request->input('exams');

        $checkAssignment = DB::table('grading_cumulative')
        ->select('CG_id')
        ->where('sid', $sid)
        ->where('period', $period)
        ->where('subj_id',$subj_id)
        ->where('type_id',1)
        ->first();

        if($checkAssignment){
            $posted = DB::table('grading_cumulative')
                ->where('CG_id', $checkAssignment->CG_id)
                ->update([
                    'score' => $assignments
                ]);
        }else{
            $posted = DB::table('grading_cumulative')
                ->insert([
                    'sid' => $sid, 
                    'score' => $assignments,
                    'type_id' => 1,
                    'subj_id' => $subj_id,
                    'school_year' => $sy,
                    'period' => $period
                ]);
        }

        $checkQuiz = DB::table('grading_cumulative')
        ->select('CG_id')
        ->where('sid', $sid)
        ->where('period', $period)
        ->where('subj_id',$subj_id)
        ->where('type_id',2)
        ->first();

        if($checkQuiz){
            $postedquiz = DB::table('grading_cumulative')
                ->where('CG_id', $checkQuiz->CG_id)
                ->update([
                    'score' => $quiz
                ]);
        }else{
            $postedquiz = DB::table('grading_cumulative')
                ->insert([
                    'sid' => $sid, 
                    'score' => $quiz,
                    'type_id' => 2,
                    'subj_id' => $subj_id,
                    'school_year' => $sy,
                    'period' => $period
                ]);
        }

        $checkRecitation = DB::table('grading_cumulative')
        ->select('CG_id')
        ->where('sid', $sid)
        ->where('period', $period)
        ->where('subj_id',$subj_id)
        ->where('type_id',3)
        ->first();

        if($checkRecitation){
            $postedrecitation = DB::table('grading_cumulative')
                ->where('CG_id', $checkRecitation->CG_id)
                ->update([
                    'score' => $recitation
                ]);
        }else{
            $postedrecitation = DB::table('grading_cumulative')
                ->insert([
                    'sid' => $sid, 
                    'score' => $recitation,
                    'type_id' => 3,
                    'subj_id' => $subj_id,
                    'school_year' => $sy,
                    'period' => $period
                ]);
        }

        $checkProjects = DB::table('grading_cumulative')
        ->select('CG_id')
        ->where('sid', $sid)
        ->where('period', $period)
        ->where('subj_id',$subj_id)
        ->where('type_id',4)
        ->first();

        if($checkProjects){
            $postedprojects = DB::table('grading_cumulative')
                ->where('CG_id', $checkProjects->CG_id)
                ->update([
                    'score' => $projects
                ]);
        }else{
            $postedprojects = DB::table('grading_cumulative')
                ->insert([
                    'sid' => $sid, 
                    'score' => $projects,
                    'type_id' => 4,
                    'subj_id' => $subj_id,
                    'school_year' => $sy,
                    'period' => $period
                ]);
        }

        $checkExams = DB::table('grading_cumulative')
        ->select('CG_id')
        ->where('sid', $sid)
        ->where('period', $period)
        ->where('subj_id',$subj_id)
        ->where('type_id',5)
        ->first();

        if($checkExams){
            $postedexams = DB::table('grading_cumulative')
                ->where('CG_id', $checkExams->CG_id)
                ->update([
                    'score' => $exams
                ]);
        }else{
            $postedexams = DB::table('grading_cumulative')
                ->insert([
                    'sid' => $sid, 
                    'score' => $exams,
                    'type_id' => 5,
                    'subj_id' => $subj_id,
                    'school_year' => $sy,
                    'period' => $period
                ]);
        }

        //check for final grade and compute
        $checkFinalGrade = DB::table('grading_final')
        ->select('FG_id')
        ->where('sid', $sid)
        ->where('period', $period)
        ->where('subj_id',$subj_id)
        ->first();
        //check for weights
        $weights = DB::table('grading_ctype')
        ->select('*')
        ->where('subj_id',$subj_id)
        ->get();
        foreach($weights as $wt){
            if($wt->name == 'assignments'){$assignments_wt = $wt->weight;}
            if($wt->name == 'quiz'){$quiz_wt = $wt->weight;}
            if($wt->name == 'recitation'){$recitation_wt = $wt->weight;}
            if($wt->name == 'projects'){$projects_wt = $wt->weight;}
            if($wt->name == 'exams'){$exams_wt = $wt->weight;}
        }
        //actual computation
        $assignments_fg = $assignments * ($assignments_wt/100);
        $quiz_fg = $quiz * ($quiz_wt/100);
        $recitation_fg = $recitation * ($recitation_wt/100);
        $projects_fg = $projects * ($projects_wt/100);
        $exams_fg = $exams * ($exams_wt/100);
        $final = $assignments_fg + $quiz_fg + $recitation_fg + $projects_fg + $exams_fg;

        if($checkFinalGrade){
            $postedfinal = DB::table('grading_final')
                ->where('FG_id', $checkFinalGrade->FG_id)
                ->update([
                    'final_grade' => $final
                ]);
        }else{
            $postedfinal = DB::table('grading_final')
                ->insert([
                    'sid' => $sid, 
                    'final_grade' => $final,
                    'subj_id' => $subj_id,
                    'school_year' => $sy,
                    'period' => $period
                ]);
        }

         
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);

        }

      }


      
      public function reportCard(Request $request, $sid){
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        $data['page_title'] = 'Grading';

        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
        
            $currentIdent = session('currentIdent');
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->where('t_id', $data['teacheruser']->id)
            // ->where('section', $data['studentuser']->section)
            // ->groupBy('subject')
            // ->orderBy('rankorder', 'desc')
            ->get();

            $studentinfo = DB::table('students')
            ->select ('grade','section')
            ->where('id',$sid)
            ->first();

            if($studentinfo !== null){
                $studentSubjects = DB::table('subjects')
                ->select('id as targetid','subject','rankorder','grade','section')
                ->where('grade',$studentinfo->grade)
                ->where('section',$studentinfo->section)
                ->get();
            }else{
            }
        //     echo "<pre>";
        //     print_r($studentSubjects);
        // echo "</pre>";

            foreach($studentSubjects as $sub){
                // echo $sub->subject;
                // $data[$sub->subject]->first = new \stdClass();
                // $data["$sub->subject"]->second = new \stdClass();
                // $data["$sub->subject"]->third = new \stdClass();
                // $data["$sub->subject"]->fourth = new \stdClass();
                // $data["$sub"] = 'test';
                // echo $sub->targetid."||test";
                $data[str_replace(' ','_',$sub->subject.'_1st')] = DB::table('grading_final')
                ->select('grading_final.final_grade','grading_final.subj_id','subjects.subject','subjects.grade','subjects.section') 
                ->leftJoin('subjects', 'subjects.id', '=', 'grading_final.subj_id')
                ->where('sid', $sid)
                // ->where('subj_id', "$sub->targetid")
                ->where('subject', $sub->subject)
                ->where('grade', $sub->grade)
                ->where('section', $sub->section)
                ->where('period', '1')
                ->where('school_year', '2018-2019')
                ->first();
                $data[str_replace(' ','_',$sub->subject.'_2nd')] = DB::table('grading_final')
                ->select('grading_final.final_grade','grading_final.subj_id','subjects.subject','subjects.grade','subjects.section') 
                ->leftJoin('subjects', 'subjects.id', '=', 'grading_final.subj_id')
                ->where('sid', $sid)
                ->where('subject', $sub->subject)
                ->where('grade', $sub->grade)
                ->where('section', $sub->section)
                ->where('period', '2')
                ->where('school_year', '2018-2019')
                ->first();
                $data[str_replace(' ','_',$sub->subject.'_3rd')] = DB::table('grading_final')
                ->select('grading_final.final_grade','grading_final.subj_id','subjects.subject','subjects.grade','subjects.section') 
                ->leftJoin('subjects', 'subjects.id', '=', 'grading_final.subj_id')
                ->where('sid', $sid)
                ->where('subject', $sub->subject)
                ->where('grade', $sub->grade)
                ->where('section', $sub->section)
                ->where('period', '3')
                ->where('school_year', '2018-2019')
                ->first();
                $data[str_replace(' ','_',$sub->subject.'_4th')] = DB::table('grading_final')
                ->select('grading_final.final_grade','grading_final.subj_id','subjects.subject','subjects.grade','subjects.section') 
                ->leftJoin('subjects', 'subjects.id', '=', 'grading_final.subj_id')
                ->where('sid', $sid)
                ->where('subject', $sub->subject)
                ->where('grade', $sub->grade)
                ->where('section', $sub->section)
                ->where('period', '4')
                ->where('school_year', '2018-2019')
                ->first();
            }

        return view('reportcard')->with($data);

        }

      }


}
