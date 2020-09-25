<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;
use App;
use PDF;
use App\Http\Controllers\TeacherFetchJSON;
use App\Http\Controllers\ApiController;


class StudentController extends NavigationController
{

        public function __construct()
        {
            $this->middleware('auth');
        }

    public function index(Request $request) {
        ApiController::storeActivityForWeb(Auth::user()->id, Auth::user()->type, 'overview');
        // $data['tasks'] = [
        //         [
        //                 'name' => 'Design New Dashboard',
        //                 'progress' => '87',
        //                 'color' => 'danger'
        //         ],
        //         [
        //                 'name' => 'Create Home Page',
        //                 'progress' => '76',
        //                 'color' => 'warning'
        //         ],
        //         [
        //                 'name' => 'Some Other Task',
        //                 'progress' => '32',
        //                 'color' => 'success'
        //         ],
        //         [
        //                 'name' => 'Start Building Website',
        //                 'progress' => '56',
        //                 'color' => 'info'
        //         ],
        //         [
        //                 'name' => 'Develop an Awesome Algorithm',
        //                 'progress' => '10',
        //                 'color' => 'success'
        //         ]
        // ];
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['page_title'] = 'OVERVIEW';
        if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set
            
        $data['user'] = Auth::user();
        //query for the student info
        $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();
                // echo session('currentIdent');die('thats the current ident');
        //query for homework in overview
        $datenow = date('Y-m-d');
        $pubdate = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate <= ?', $datenow)
         ->whereRaw('grade= ? ', $data['studentuser']->grade )
         ->whereRaw('section= ? ', $data['studentuser']->section )
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
         ->whereRaw('grade= ? ', $data['studentuser']->grade )
         ->whereRaw('section= ? ', $data['studentuser']->section )
        ->orderByRaw('pubdate  DESC')
        ->first(); 
        $data['next'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate > ?', $targetpubdate)
         ->whereRaw('grade= ? ', $data['studentuser']->grade )
         ->whereRaw('section= ? ', $data['studentuser']->section )
        ->orderByRaw('pubdate  ASC')
        ->first(); 
        
        $data['homeworks'] = DB::table('homeworks')
        ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
        ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
        ->where('homeworks.grade', $data['studentuser']->grade)
        ->where('homeworks.section', $data['studentuser']->section)
        ->where('homeworks.pubdate', $targetpubdate)
        ->get();
        $data['pubdate'] = $targetpubdate;
        
        //query for the schdule
        $day = strtolower(date('l'));
        $data['schedule'] = DB::table('subjects')
        ->select('subject','grade','section','schedule')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->where($day, 1)
        ->orderBy('rankorder', 'desc')
        ->get();

        //get subjects for sidemenu
        // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
        $data['subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->groupBy('subject')
        ->orderBy('rankorder', 'desc')
        ->get();

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

        return view('soverview')->with($data);
        }
        else{
                Auth::logout();
                return redirect('/login');
        }
    }
    public function overviewDate(Request $request ,$date) {
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['page_title'] = 'OVERVIEW - '. date('M d, Y',strtotime($date));
        if (Auth::check() ) {
               //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set

                $data['user'] = Auth::user();
                //query for the student info
                $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();
                        //query for homework in overview
    
        $data['homeworks'] = DB::table('homeworks')
        ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
        ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
        ->where('homeworks.grade', $data['studentuser']->grade)
        ->where('homeworks.section', $data['studentuser']->section)
        ->where('homeworks.pubdate', $date)
        ->get();
        $data['pubdate'] = '';
        $data['prev'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate < ?', $date)
         ->whereRaw('grade= ? ', $data['studentuser']->grade )
         ->whereRaw('section= ? ', $data['studentuser']->section )
        ->orderByRaw('pubdate  DESC')
        ->first(); 
        $data['next'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate > ?', $date)
         ->whereRaw('grade= ? ', $data['studentuser']->grade )
         ->whereRaw('section= ? ', $data['studentuser']->section )
        ->orderByRaw('pubdate  ASC')
        ->first(); 

        //query for the schdule
        $day = strtolower(date('l'));
        $data['schedule'] = DB::table('subjects')
        ->select('subject','grade','section','schedule')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->where($day, 1)
        ->orderBy('schedule', 'asc')
        ->get();
  

        //get subjects for sidemenu
        $data['subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->groupBy('subject')
        ->orderBy('rankorder', 'desc')
        ->get();
       
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

        return view('soverview')->with($data);
        
        }else{
        Auth::logout();
        return redirect('/login');
        }
}

    public function replyslips(Request $request) {
        ApiController::storeActivityForWeb(Auth::user()->id, Auth::user()->type, 'replyslip');
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set

        $data['page_title'] = 'Reply Slips';
        $currentIdent = $request->session()->get('currentIdent','default');
                $data['user'] = Auth::user();
                //query for the student info
                $data['studentuser'] = DB::table('students')->where('id', $currentIdent )->first();
                //replyslip scope filter
                if($data['studentuser']->grade > 0 && $data['studentuser']->grade < 13){$scope[] = "na";}
                if($data['studentuser']->grade > 7 && $data['studentuser']->grade < 13){$scope[] = "hs";}
                if($data['studentuser']->grade > 0 && $data['studentuser']->grade < 8){$scope[] = "gs";}
                if($data['studentuser']->grade == 'k' ){$scope[] = "na";}
                if($data['studentuser']->grade == 'p' ){$scope[] = "na";}
                if($data['studentuser']->grade == 'n' ){$scope[] = "na";}
                $myArray = implode($scope, ',');
                // echo session('currentIdent');die();

                //query for reply slips
                $data['replyslips'] = DB::table('replyslips')
                ->select('replyslips.*','uploads.type as type','uploads.filename as filename','replyslips_ans.rid','replyslips_ans.sid','replyslips_opt.oid','replyslips_opt.choice')
                ->leftJoin('uploads', 'replyslips.upload_id', '=', 'uploads.uid')
                // ->leftJoin('replyslips_ans', 'replyslips_ans.rid', '=', 'replyslips.id')
                ->leftJoin('replyslips_ans', function ($join) {
                        $join->on('replyslips_ans.rid', '=', 'replyslips.id')
                             ->where('replyslips_ans.sid', '=', session('currentIdent'));
                        //      ->where('replyslips_ans.sid', '=', Auth::user()->ident);
                    })
                ->leftJoin('replyslips_opt', 'replyslips_opt.oid', '=', 'replyslips_ans.oid')
                 ->whereIn('replyslips.grade', $scope)
                //  ->where('replyslips_ans.sid', $data['studentuser']->id)
                // ->where('replyslips.publish', '1')
                ->orderBy('replyslips.date','desc')
               
                ->get();

                $data['scope']=$scope;
                $data['testing']= session('currentIdent');
                
                //get subjects for sidemenu
        // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
        $data['subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->groupBy('subject')
        ->orderBy('rankorder', 'desc')
        ->get();
        
        return view('sreplyslips')->with($data);
        }else{
                Auth::logout();
                return redirect('/login');
        }
    }
    public function subjects(Request $request, $subj) {
        ApiController::storeActivityForWeb(Auth::user()->id, Auth::user()->type, 'subject');
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['page_title'] = 'Subjects';
        $subj = str_replace('_',' ',trim($subj));
        $data['subject'] = $subj;//fur future - sanitazie subject
        
        if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set

                $data['user'] = Auth::user();
                //id source of data is from session
                $currentIdent = session('currentIdent');
                //query for the student info
                // $data['studentuser'] = DB::table('students')->where('id', $data['user']->ident )->first();
                $data['studentuser'] = DB::table('students')->where('id', $currentIdent )->first();
        // get homeworks for subject        
        $data['homeworks'] = DB::table('homeworks')
        ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
        ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
        ->where('homeworks.grade', $data['studentuser']->grade)
        ->where('homeworks.section', $data['studentuser']->section)
        ->where('homeworks.subject', $subj)
        ->orderBy('pubdate', 'desc')
        ->limit('5')

        ->get();

        // $users = DB::table('users')->count();

        $data['totals']=DB::table('homeworks')
        ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
        ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
        ->where('homeworks.grade', $data['studentuser']->grade)
        ->where('homeworks.section', $data['studentuser']->section)
        ->where('homeworks.subject', $subj)->count();
        
        //get tests for the subject
        // SELECT tests.*, uploads.type as type, uploads.filename as filename FROM tests left join uploads on tests.upload_id = uploads.uid
        //   where tests.subject= :subject and tests.grade = :grade and tests.section =:section order by date desc
          $data['tests'] = DB::table('tests')
          ->select('tests.*','uploads.type as type','uploads.filename as filename','teachers.title as tchrtitle','teachers.firstname','teachers.lastname')
          ->leftJoin('uploads','tests.upload_id','=','uploads.uid')
          ->leftJoin('teachers', 'tests.teacher_id', '=', 'teachers.id')
          ->where('tests.subject',$subj)
          ->where('tests.grade',$data['studentuser']->grade)
          ->where('tests.section',$data['studentuser']->section)
          ->orderBy('date','desc')
          ->get();


          //get activitysheets
          $data['activitysheets'] = DB::table('activitysheets')
          ->select('activitysheets.*','uploads.type as type','uploads.filename as filename','teachers.title as tchrtitle','teachers.firstname','teachers.lastname')
          ->leftJoin('uploads','activitysheets.upload_id','=','uploads.uid')
          ->leftJoin('teachers', 'activitysheets.teacher_id', '=', 'teachers.id')
          ->where('activitysheets.subject',$subj)
          ->where('activitysheets.grade',$data['studentuser']->grade)
          ->where('activitysheets.section',$data['studentuser']->section)
          ->orderBy('date','desc')
          ->get();

          //get handouts
          $data['handouts'] = DB::table('handouts')
          ->select('handouts.*','uploads.type as type','uploads.filename as filename','teachers.title as tchrtitle','teachers.firstname','teachers.lastname')
          ->leftJoin('uploads','handouts.upload_id','=','uploads.uid')
          ->leftJoin('teachers', 'handouts.teacher_id', '=', 'teachers.id')
          ->where('handouts.subject',$subj)
          ->where('handouts.grade',$data['studentuser']->grade)
          ->where('handouts.section',$data['studentuser']->section)
          ->orderBy('date','desc')
          ->get();

          //get subjects for sidemenu
        // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
        $data['subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->groupBy('subject')
        ->orderBy('rankorder', 'desc')
        ->get();

        return view('ssubjects')->with($data);
        }else{
                Auth::logout();
                return redirect('/login');
        }

    }
    public function profile(Request $request, $sid=0, $subj=null, $period=null) {
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['page_title'] = 'Profile';
        $data['subj_clicked'] = $subj;
        $data['period_clicked'] = $period;
        if (Auth::check()) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set
                $currentIdent = $request->session()->get('currentIdent','default');
                $data['user'] = Auth::user();

                if($sid == 0){
                        $profileId = $currentIdent;
                }else{
                        $profileId = $sid;
                }
                if(Auth::user()->type =='t' or Auth::user()->type =='a'){
                        $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();
                        $data['subjects'] = DB::table('teacher_subj')
                        ->select('subj','grade','section')
                        ->get();
                }
                //query for the student info
                $data['studentuser'] = DB::table('students')->where('id', $profileId )->first();
                if($data['studentuser'] == null) {
                    return redirect()->back()->with('error', 'Student ID does not exists. Please change it.');
                }
                //age calculation
                $age = DateTime::createFromFormat('Y-m-d', $data['studentuser']->birthdate)
                ->diff(new DateTime('now'))
                ->y;
                
                $data['studentuser']->age = $age;
                //preferered contact
                switch ($data['studentuser']->s_prefcontact) {
                        case 'guardian':
                            $data['studentuser']->prefcontactname = $data['studentuser']->s_guardianname;
                            $data['studentuser']->prefcontactno = $data['studentuser']->s_guardiancellno;
                            break;
                        case 'dad':
                        $data['studentuser']->prefcontactname = $data['studentuser']->s_dadname;
                        $data['studentuser']->prefcontactno = $data['studentuser']->s_dadcellno;
                            break;
                        default:
                        $data['studentuser']->prefcontactname = $data['studentuser']->s_momname;
                        $data['studentuser']->prefcontactno = $data['studentuser']->s_momcellno;;
                    }

        
        if(Auth::user()->type == 's' || Auth::user()->type == 'p'){
        //get subjects for sidemenu
        // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
        $data['subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->groupBy('subject')
        ->orderBy('rankorder', 'desc')
        ->get();
        }elseif(Auth::user()->type == 't'){
         //get subjects for sidemenu

         $data['subjects'] = DB::table('teacher_subj')
         ->select('subj','grade','section')
         ->where('t_id', $data['teacheruser']->id)
         // ->where('section', $data['studentuser']->section)
         // ->groupBy('subject')
         // ->orderBy('rankorder', 'desc')
         ->get();       
        }

        $data['attendance'] = DB::table('attendance')
        ->select('*')
        ->where('sid',$profileId)
        ->whereIn('status',['absent','late'])
        ->orderBy('date','desc')
        ->get();

        $data['incidents'] = DB::table('incidents')
        ->select('*')
        ->where('sid',$profileId)
        ->orderBy('date','desc')
        ->get();

        $data['achievements'] = DB::table('achievements')
        ->select('*')
        ->where('sid',$profileId)
        ->get();

        $data['medical'] = DB::table('medical')
        ->select('*')
        ->where('sid',$profileId)
        ->first();

        $data['paymentdues'] = DB::table('payment_dues')
        ->select('*')
        ->where('sid',$profileId)
        ->orderBy('date','desc')
        ->get();

        //added by elmer 

        $student_name = DB::table('students')
            ->select('id','firstname', 'lastname')
            ->where('id', $profileId)
            ->get();

        $data['student_name'] = DB::table('students')
            ->select('id','firstname', 'lastname')
            ->where('id', $profileId)
            ->get();

            $data['student_specific_name'] = $data['studentuser']->lastname.', '.$data['studentuser']->firstname;
            
            $data['students_subjects'] = DB::table('subjects')
            ->select('subject')
            ->where('grade', $data['studentuser']->grade)
            ->where('section', $data['studentuser']->section)
            ->whereNotIn('subject',['homeroom'])
            ->groupBy('subject')
            ->get();

            $student_subj = DB::table('subjects')
            ->select('subject')
            ->where('grade', $data['studentuser']->grade)
            ->where('section', $data['studentuser']->section)
            ->where('subject', $data['subj_clicked'])
            ->groupBy('subject')
            ->get();
            

            $period_list = array('1','2','3','4');
            $data['period_list'] = $period_list;
            $period_specific_list = array($data['period_clicked']);
            $subject_specific_list = array($data['subj_clicked']);

            /**
             * NEW TASK GRADING CALCULATION
             * 
             */
            
            $data['get_task_id'] = DB::table('tasks')
            ->select('id','task_type','task_total_points', 'task_title')
            ->where('task_subject', $subj)
            ->where('period', $period)
            ->where('task_grade', $data['studentuser']->grade)
            ->where('task_section', $data['studentuser']->section)
            ->orderByRaw("FIELD(task_type , 'WW', 'PT', 'QA')")
            ->get();
            
            $data['array_task'] = [];
            foreach($data['get_task_id'] as $get_task_id) {
                $task_list = DB::table('task_student')
                ->select('score', 'status', 'taskid') 
                ->where('taskid', $get_task_id->id)
                ->where('sid', $profileId)
                ->whereNotIn('status',['excused'])
                ->get();

                $get_task_id_from_tasks = DB::table('tasks')
                ->select('task_total_points')
                ->where('id', $get_task_id->id)
                ->get();
    
                if(isset($task_list[0])) {
                    array_push($data['array_task'], (object)array(
                        'id'=>$task_list[0]->taskid,
                        'score'=>$task_list[0]->score,
                        'status'=>$task_list[0]->status,
                        'total_points'=>$get_task_id_from_tasks[0]->task_total_points
                    ));
                } else {
                    array_push($data['array_task'], (object)array(
                        'id'=>$get_task_id->id,
                        'score'=>0,
                        'status'=>'none',
                        'total_points'=>0
                    ));
                }
                
            }
            
            $data['merge_task_list'] = [];
            
            foreach($data['get_task_id'] as $get_task_id) {
                foreach($data['array_task'] as $array_task) {
                   if($get_task_id->id == $array_task->id) {
                        array_push($data['merge_task_list'], (object)array(
                            'Task Type'=>$get_task_id->task_type,
                            'Task Title'=>$get_task_id->task_title,
                            'Task Score'=>$array_task->score,
                            'Task Status'=>$array_task->status,
                            'Task Total'=>$array_task->total_points
                        ));
                   }
                    
                }
            }
            
            
            $data['task_type_list'] = DB::table('task_type')
            ->select('type_name', 'weight')
            ->where('subject', $subj)
            ->get();
    
            foreach($data['task_type_list'] as $task_type_list) {
                $data['task_type_name_score'][$task_type_list->type_name] = [];
                $data['task_type_name_total_points'][$task_type_list->type_name] = [];
            }
            
            foreach($data['task_type_list'] as $task_type_list) {
                foreach($data['merge_task_list'] as $merge_task_list) {
                    if($task_type_list->type_name == $merge_task_list->{'Task Type'}) {
                        array_push($data['task_type_name_score'][$merge_task_list->{'Task Type'}], $merge_task_list->{'Task Score'});
                        array_push($data['task_type_name_total_points'][$merge_task_list->{'Task Type'}], $merge_task_list->{'Task Total'});
                    }
                }
            }
    
            $data['total_score'] = [];
            foreach($data['task_type_list'] as $task_type_list) {
                if(array_sum($data['task_type_name_total_points'][$task_type_list->type_name]) == 0) {
                    $data['task_type_sub_total'][$task_type_list->type_name] = 0;
                    $data['task_calculate'][$task_type_list->type_name] = 0;
                } else {
                    $data['task_type_sub_total'][$task_type_list->type_name] = round((array_sum($data['task_type_name_score'][$task_type_list->type_name]) / array_sum($data['task_type_name_total_points'][$task_type_list->type_name])) * 100, 2).'%';
                    $data['task_calculate'][$task_type_list->type_name] = round((array_sum($data['task_type_name_score'][$task_type_list->type_name]) / array_sum($data['task_type_name_total_points'][$task_type_list->type_name])) * $task_type_list->weight, 2);
                }
                array_push($data['total_score'], $data['task_calculate'][$task_type_list->type_name]);
            }
            
           
        
    
            foreach($data['task_type_list'] as $task_type_list) {
                $dataToSend[$task_type_list->type_name] = [];
            }
    
            
            foreach($data['merge_task_list'] as $merge_task_list) {
                array_push($dataToSend[$merge_task_list->{'Task Type'}], (object)array(
                    'Task Title'=>$merge_task_list->{'Task Title'},
                    'Task Score'=>$merge_task_list->{'Task Score'},
                    'Task Total'=>$merge_task_list->{'Task Total'}
                ));
            }
          
    
            $dataToSend['Total Scores'] = [];
            $data['final_grade'] = null;
            foreach($data['task_type_list'] as $task_type_list) {
                array_push($dataToSend['Total Scores'], array(
                    $task_type_list->type_name.'Total'=>$data['task_type_sub_total'][$task_type_list->type_name]
                ));
            }
            $total = array_sum($data['total_score']);

            $data['get_final_grade_list'] = DB::table('final_grade_list')
            ->select('score', 'transmuted')
            ->where('sid', $profileId)
            ->where('subject', $subj)
            ->where('period', $period)
            ->where('grade', $data['studentuser']->grade)
            ->where('section', $data['studentuser']->section)
            ->first();

             /**
              * END OF GRADING CALCULATION
              */

              /**
               * 
               * START OF REPORT CARD GRADE
               */

              $data['subject_list'] = DB::table('subjects')
              ->select('subject')
              ->where('grade', $data['studentuser']->grade)
              ->where('section', $data['studentuser']->section)
              ->whereNotIn('subject',['homeroom'])
              ->orderBy('rankorder')
              ->get();

            $data['students_subjects_fixed_count'] = DB::table('subjects')
            ->select('subject')
            ->where('grade', $data['studentuser']->grade)
            ->where('section', $data['studentuser']->section)
            ->whereNotIn('subject',['homeroom', 'language', 'reading', 'music', 'arts', 'pe', 'health', 'penmanship'])
            ->count();

            $data['students_subjects_selected'] = DB::table('subjects')
            ->select('subject')
            ->where('grade', $data['studentuser']->grade)
            ->where('section', $data['studentuser']->section)
            ->orderBy('rankorder')
            ->whereNotIn('subject',['homeroom', 'language', 'reading', 'music', 'arts', 'pe', 'health', 'penmanship'])
            ->get();

              $data['subject_list_count'] = DB::table('subjects')
              ->where('grade', $data['studentuser']->grade)
              ->where('section', $data['studentuser']->section)
              ->whereNotIn('subject',['homeroom'])
              ->count();

              $data['check_if_task_exists'] = DB::table('tasks')
              ->where('task_grade', $data['studentuser']->grade)
              ->where('task_section', $data['studentuser']->section)
              ->first();

             
            if(!empty($data['check_if_task_exists'])) {
                
                $data['final_grade_list_from_new_db'] = DB::table('final_grade_list')
                ->where('grade', $data['studentuser']->grade)
                ->where('section', $data['studentuser']->section)
                ->where('sid', $data['studentuser']->id)
                ->whereNotIn('subject',['homeroom'])
                ->orderBy('subject')
                ->orderBy('period')
                ->get();

                foreach($data['subject_list'] as $subject_list) {
                    for($i = 1; $i<=5; $i++) {
                        $data['subject_list_with_period'][$subject_list->subject][$i] = [];
                    }
                }

                for($i = 1; $i<=4; $i++) {
                    foreach($data['subject_list'] as $subject_list) {
                        $data['period_list_with_subject'][$i][$subject_list->subject] = [];
                    }
                    $data['list_of_general_average'][$i] = [];
                    $data['sum_up_general_average'][$i] = [];
                }

                foreach($data['subject_list'] as $subject_list) {
                    $data['list_of_final_grade'][$subject_list->subject] = [];
                    $data['array_sum_of_final_grade'][$subject_list->subject] = [];
                }

                foreach($data['subject_list'] as $subject_list) {
                    foreach($data['final_grade_list_from_new_db'] as $grade_list_new_db) {
                        if($subject_list->subject == $grade_list_new_db->subject) {
                            array_push($data['subject_list_with_period'][$grade_list_new_db->subject][$grade_list_new_db->period], $grade_list_new_db->score);
                        }
                    }
                }

                foreach($data['subject_list'] as $subject_list) {
                    for($i = 1; $i<=4; $i++) {
                        if($i == 4){
                            array_push($data['list_of_final_grade'][$subject_list->subject], TeacherFetchJSON::transmuteGradesSpecial($data['subject_list_with_period'][$subject_list->subject][$i][0]));
                        }else{
                            array_push($data['list_of_final_grade'][$subject_list->subject], $data['subject_list_with_period'][$subject_list->subject][$i][0]);
                        }
                    }
                }

                foreach($data['subject_list'] as $subject_list) {
                    array_push($data['array_sum_of_final_grade'][$subject_list->subject], round((array_sum($data['list_of_final_grade'][$subject_list->subject])/4),2));
                }

                foreach($data['students_subjects_selected'] as $subject_list) {
                    foreach($data['final_grade_list_from_new_db'] as $grade_list_new_db) {
                        if($subject_list->subject == $grade_list_new_db->subject) {
                    array_push($data['period_list_with_subject'][$grade_list_new_db->period][$grade_list_new_db->subject], $grade_list_new_db->score);
                        }
                    }
                }

                for($i = 1; $i<=4; $i++) {
                    foreach($data['subject_list'] as $subject_list) {
                        if(isset($data['period_list_with_subject'][$i][$subject_list->subject][0])) {
                            array_push($data['list_of_general_average'][$i], $data['period_list_with_subject'][$i][$subject_list->subject][0]);
                        }
                    }
                }

                for($i = 1; $i<=4; $i++) {
                    if($data['students_subjects_fixed_count'] == 0) {
                        $data['sum_up_general_average'][$i] = [0];
                    } else {
                        array_push($data['sum_up_general_average'][$i], round((array_sum($data['list_of_general_average'][$i])/$data['students_subjects_fixed_count']),2));
                    }
                }

                foreach($data['array_sum_of_final_grade'] as $subject_name=>$grade_list_new_db) {
                    array_push($data['subject_list_with_period'][$subject_name][5], $grade_list_new_db[0]);
                }
                //APPLY CONFIG WHEN ADMIN CHOOSE WHICH QTR TO SHOW TO PARENTS
                $checkfirstqtr = DB::table('config')
                ->select('status')
                ->where('config_name', 'first_quarter_select')
                ->first();

                $checksecondqtr = DB::table('config')
                ->select('status')
                ->where('config_name', 'second_quarter_select')
                ->first();
                
                $checkthirdqtr = DB::table('config')
                ->select('status')
                ->where('config_name', 'third_quarter_select')
                ->first();

                $checkfourthqtr = DB::table('config')
                ->select('status')
                ->where('config_name', 'fourth_quarter_select')
                ->first();

                $checkfinalqtr = DB::table('config')
                ->select('status')
                ->where('config_name', 'final_grade_select')
                ->first();

                foreach($data['subject_list_with_period'] as $subj_name=>$period_custom) {
                    for($i = 1; $i<=5; $i++) {
                        if($checkfirstqtr->status == 1 && $i == 1) {
                            //do not touch
                        } elseif($checksecondqtr->status == 1 && $i == 2) {
                            //do not touch
                        } elseif($checkthirdqtr->status == 1 && $i == 3) {
                            //do not touch
                        } elseif($checkfourthqtr->status == 1 && $i == 4) {
                            //do not touch
                        } elseif($checkfinalqtr->status == 1 && $i == 5) {
                            //do not touch
                        } else {
                            $data['subject_list_with_period'][$subj_name][$i] = [''];
                        }
                    }
                }
             
                /**
                 * START OF TRANSMUTING FINALS GRADES
                 */
                    foreach($data['subject_list_with_period'] as $subj_name=>$period_custom) {
                        
                        for($i = 1; $i<=5; $i++) {
                            if($i == 4){
                                $data['transmuting_final_grade'][$subj_name][$i] = TeacherFetchJSON::transmuteGradesSpecial($period_custom[$i][0]);
                            }else{
                                $data['transmuting_final_grade'][$subj_name][$i] = TeacherFetchJSON::transmuteGradesHere($period_custom[$i][0]);
                            }
                        }
                    }
                
                 /**
                  * END OF TRANSMUTING FINAL GRADES
                  */

                //APPLY CONFIG FROM ADMIN FOR QUARTER SELECTION
                foreach($data['sum_up_general_average'] as $key=>$final) {
                    if($checkfirstqtr->status == 1 && $key == 1) {
                        //do not touch
                    } elseif($checksecondqtr->status == 1 && $key == 2) {
                        //do not touch
                    } elseif($checkthirdqtr->status == 1 && $key == 3) {
                        //do not touch
                    } elseif($checkfourthqtr->status == 1 && $key == 4) {
                        //do not touch
                    } elseif($checkfinalqtr->status == 1 && $key == 5) {
                        //do not touch
                    } else {
                        $data['sum_up_general_average'][$key][0] = '';
                    }
                }

                  /**
                   * START OF TRANSMUTING GENERAL AVERAGE
                   */
                    $l=0;
                  foreach($data['sum_up_general_average'] as $key=>$final) {
                      $l++;
                      if($l == 4){
                        $data['transmuting_general_average'][$key] = TeacherFetchJSON::transmuteGradesSpecial($final[0]);

                      }else{

                          $data['transmuting_general_average'][$key] = TeacherFetchJSON::transmuteGradesHere($final[0]);
                      }
                  }
                  $data['final_general_average'] = round(array_sum($data['transmuting_general_average']) / count($data['transmuting_general_average'])); 
                //   print_r(round($data['final_general_average']/count($data['transmuting_general_average']))); die();

                   /**
                    * END OF TRANSMUTING GENERAL AVERAGE
                    */
            }
               /**
                * 
                * END OF REPORT CARD GRADE
                */
            

            $tasks_score_list = DB::table('tasks')
            ->leftJoin('task_student','tasks.id', '=', 'task_student.taskid')
            ->where('tasks.school_year', '2019-2020')
            ->whereNotIn('task_student.status',['excused'])
            ->select('task_student.score','tasks.task_type','tasks.task_total_points','tasks.task_subject', 'task_student.sid', 'task_student.period')
            ->orderBy('tasks.period')
            ->get();

            $data['task_qualitative_category'] = DB::table('qualitative_types')
            ->select('category')
            ->groupBy('category')
            ->get();
    
            $data['task_qualitative_types'] = DB::table('qualitative_types')
            ->select('id', 'type_name', 'category')
            ->orderBy('id')
            ->get();
            
            $data['task_qualitative_scores'] = DB::table('qualitative_scores')
            ->select('score', 'qid', 'period', 'sid')
            ->where('school_year', '2019-2020')
            ->get();

            
            //creating new array for qualitative types for each student

            foreach($student_name as $student) {
                foreach($data['task_qualitative_types'] as $types) {
                    foreach($period_list as $period) {
                        $data['task_qualitative_score_array'][$student->lastname.', '.$student->firstname][$types->id][$period] = '';
                    }
                }
            }
    
            foreach($student_name as $student) {
                foreach($data['task_qualitative_types'] as $types) {
                    foreach($period_list as $period) {
                        foreach($data['task_qualitative_scores'] as $score) {
                            if($student->id === $score->sid) {
                                if($types->id == $score->qid) {
                                    if($period == $score->period) {
                                        $data['task_qualitative_score_array'][$student->lastname.', '.$student->firstname][$types->id][$period] = $score->score;
                                    }
                                }
                            }
                            
                        }
                        
                    }
                }
            }

        }

        //START OF ATTENDANCE

    $data['fixed_startdate'] = DB::table('config')
    ->select('school_date')
    ->where('config_name', 'startdate_school_year')
    ->first();

    $data['fixed_enddate'] = DB::table('config')
    ->select('school_date')
    ->where('config_name', 'enddate_school_year')
    ->first();

    $data['month_array_name'] = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    if(isset($data['studentuser']->id)) {
        $data['total_days_currentyear'][$data['studentuser']->id] = DB::table('attendance')
        ->select(DB::raw('COUNT(status) as student_total'))
        ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
        ->where('sid', $data['studentuser']->id)
        ->get();

        $data['total_present_currentyear'][$data['studentuser']->id] = DB::table('attendance')
        ->select(DB::raw('COUNT(status) as student_present'))
        ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
        ->where(function ($query) {
            $query->orWhere('status', 'present')
                  ->orWhere('status', 'late');
        })
        ->where('sid', $data['studentuser']->id)
        ->get();

        $data['total_absent_currentyear'][$data['studentuser']->id] = DB::table('attendance')
        ->select(DB::raw('COUNT(status) as student_absent'))
        ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
        ->where('sid', $data['studentuser']->id)
        ->where('status', 'absent')
        ->get();

        $data['list_of_months'] = DB::table('attendance')
        ->select(DB::raw('MONTH(date) as month_name'))
        ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
        ->groupBy(DB::raw('MONTH(date)'))
        ->where('sid', $data['studentuser']->id)
        ->get();

        $create_startdate = date_create($data['fixed_startdate']->school_date);
        $startdate_list = date_format($create_startdate, "m");

        $create_enddate = date_create($data['fixed_enddate']->school_date);
        $enddate_list = date_format($create_enddate, "m");

        $data['month_name_list_sorted'] = [];
        $data['month_number_list_sorted'] = [];
        foreach($data['month_array_name'] as $key=>$month_array) {
            if((($key+1) <= 9 ? '0'.($key+1) : ($key+1)) >= $startdate_list) {
                array_push($data['month_name_list_sorted'], $month_array);
                array_push($data['month_number_list_sorted'], (($key+1) <= 9 ? '0'.($key+1) : ($key+1)));
            }
        }

        foreach($data['month_array_name'] as $key=>$month_array) {
            if((($key+1) <= 9 ? '0'.($key+1) : ($key+1)) <= $enddate_list) {
                array_push($data['month_name_list_sorted'], $month_array);
                array_push($data['month_number_list_sorted'], (($key+1) <= 9 ? '0'.($key+1) : ($key+1)));
            }
        }

        foreach($data['month_number_list_sorted'] as $month_no) {
            $data['total_attendance'][$data['studentuser']->id][$month_no] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_total'))
            ->where(DB::raw('MONTH(date)'), $month_no)
            ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
            ->where('sid', $data['studentuser']->id)
            ->get();

            $data['total_present'][$data['studentuser']->id][$month_no] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_total'))
            ->where(DB::raw('MONTH(date)'), $month_no)
            ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
            ->where('sid', $data['studentuser']->id)
            ->where(function ($query) {
                $query->orWhere('status', 'present')
                      ->orWhere('status', 'late');
            })
            ->get();

            $data['total_absent'][$data['studentuser']->id][$month_no] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_total'))
            ->where(DB::raw('MONTH(date)'), $month_no)
            ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
            ->where('sid', $data['studentuser']->id)
            ->where('status', 'absent')
            ->get();
        }
        
    }
    

    //END OF ATTENDANCE
  
       $data['reportcard_view_parent'] = DB::table('config')
       ->select('status')
       ->where('config_name', 'reportcard_view_parent')
       ->first();

        //  echo"<pre>";print_r($data);echo"</pre>";die();
        return view('sprofile')->with($data);
    }

    
    public function directory(Request $request) {
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['page_title'] = 'DIRECTORY';
        if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set

                $data['user'] = Auth::user();
                //query for the student info
                $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();
                
                // $age = date_diff(date_create($bdate), date_create('now'))->y;
        //get subjects for sidemenu
        // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
        $data['subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->groupBy('subject')
        ->orderBy('rankorder', 'desc')
        ->get();
       
        // SELECT * FROM students where grade = :grade and section = :section ORDER BY lastname desc
        $data['classmates'] = DB::table('students')
        ->select('firstname','lastname','profilepic')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->orderBy('lastname', 'asc')
        ->get();

        //get Teachers of specified grade and sectionsection
        //SELECT teacher_subj.*, teachers.* FROM `teacher_subj` left join teachers on teacher_subj.t_id = teachers.id WHERE teacher_subj.grade =:grade and teacher_subj.section = :section group by t_id
        $data['teachers'] = DB::table('teacher_subj')
        ->select('teacher_subj.*','teachers.*')
        ->leftJoin('teachers', 'teacher_subj.t_id', '=', 'teachers.id')
        ->where('teacher_subj.grade', $data['studentuser']->grade)
        ->where('teacher_subj.section', $data['studentuser']->section)
        // ->groupBy('teacher_subj.t_id')
        // ->orderBy('lastname', 'asc')
        ->get();


        return view('sdirectory')->with($data);
}else{
        Auth::logout();
        return redirect('/login');
}
    }

    public function staffDirectory(Request $request) {
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['page_title'] = 'DIRECTORY';
        if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set

                $data['user'] = Auth::user();
                //query for the student info
                $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();
                
                // $age = date_diff(date_create($bdate), date_create('now'))->y;

if(Auth::user()->type =='s' or Auth::user()->type =='p'){
        //get subjects for sidemenu
        // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
        $data['subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->groupBy('subject')
        ->orderBy('rankorder', 'desc')
        ->get();
}elseif(Auth::user()->type =='t'){
        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
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
       
}elseif(Auth::user()->type =='a') {
    $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
    //get subjects for sidemenu
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
}

        return view('staffdirectory')->with($data);
}else{
        Auth::logout();
        return redirect('/login');
}
    }


    public function notifications(Request $request){

        ApiController::storeActivityForWeb(Auth::user()->id, Auth::user()->type, 'notification');
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        $data['page_title'] = 'NOTIFICATIONS';
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $currentIdent = session('currentIdent');
      
        $data['user'] = Auth::user();
                //query for the student info
        
        if(Auth::user()->type == 's' || Auth::user()->type == 'p'){
         $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();

        //get subjects for sidemenu
        // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
        $data['subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->groupBy('subject')
        ->orderBy('rankorder', 'desc')
        ->get();
        }elseif(Auth::user()->type == 't'){
                $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

                $data['subjects'] = DB::table('teacher_subj')
    ->select('subj','grade','section')
    ->where('t_id', $data['teacheruser']->id)
    // ->where('section', $data['studentuser']->section)
    // ->groupBy('subject')
    // ->orderBy('rankorder', 'desc')
    ->get();
        }else if(Auth::user()->type == 'a') {
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();
        }
        $data['grade_section'] = DB::table('students')
        ->select('grade')
        ->groupBy('grade')
        ->whereNotIn('grade',['k','p','n'])
        ->get();

        $data['notificationsList'] = DB::table('notify_users')
        ->select('*')
        ->leftJoin('notify', 'notify_users.nid', '=', 'notify.id')
        // ->where('viewed','0')
        ->where('uid',Auth::user()->id)
        ->orderBy('date','desc')
        ->get();
        // return response()->json($data);
        return view('anotifications')->with($data);
        }else{
        Auth::logout();
        return redirect('/login');
        }

      }

     
      public function chat(Request $request, $grade='', $section='') {

        ApiController::storeActivityForWeb(Auth::user()->id, Auth::user()->type, 'chat');
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['page_title'] = 'CLASS CHAT';
        if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set
    
        $data['user'] = Auth::user();
        //query for the student info
        $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();
        
        if(Auth::user()->type == 's' || Auth::user()->type == 'p'){
                $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();
                $data['grade'] = $data['studentuser']->grade;
                $data['section'] = $data['studentuser']->section;
               //get subjects for sidemenu
               // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
               $data['subjects'] = DB::table('subjects')
               ->select('subject')
               ->where('grade', $data['studentuser']->grade)
               ->where('section', $data['studentuser']->section)
               ->groupBy('subject')
               ->orderBy('rankorder', 'desc')
               ->get();
               $data['grade_list'] = DB::table('subjects')
                ->select('grade','section')
                ->groupBy('grade','section')
                ->get();
               }elseif(Auth::user()->type == 't' || Auth::user()->type == 'a'){
                       if($grade === 0 or $section === 0){
                        return redirect('/toverview');     
                       }
                       $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
                       $data['grade'] = $grade;
                       $data['section'] = $section;
                       $data['subjects'] = DB::table('teacher_subj')
           ->select('subj','grade','section')
           ->where('t_id', $data['teacheruser']->id)
           // ->where('section', $data['studentuser']->section)
           // ->groupBy('subject')
           // ->orderBy('rankorder', 'desc')
           ->get();

           $data['grade_list'] = DB::table('subjects')
           ->select('grade','section')
           ->groupBy('grade','section')
           ->get();
               }
               
        // return view('anotifications')->with($data);
        return view('chat')->with($data);
        }
        echo "no auth";
    }

}
