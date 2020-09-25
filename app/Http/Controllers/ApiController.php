<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;

class ApiController extends Controller
{
    //
    public function soverviewAssignments(Request $request,$grade,$section){
    // $data['notifications'] = $this->notificationsListHeaderNav();
    // $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    $data['page_title'] = 'OVERVIEW';
   
            //check if session is set
            // if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
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
     ->whereRaw('grade= ? ', $grade )
     ->whereRaw('section= ? ', $section )
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
     ->whereRaw('grade= ? ', $grade )
     ->whereRaw('section= ? ', $section )
    ->orderByRaw('pubdate  DESC')
    ->first(); 
    $data['next'] = DB::table('homeworks')
    ->selectRaw('pubdate')
    ->whereRaw('pubdate > ?', $targetpubdate)
     ->whereRaw('grade= ? ', $grade )
     ->whereRaw('section= ? ', $section )
    ->orderByRaw('pubdate  ASC')
    ->first(); 

    
    $data['homeworks'] = DB::table('homeworks')
    ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
    ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
    // ->where('homeworks.grade', $data['studentuser']->grade)
    ->where('homeworks.grade', $grade)
    // ->where('homeworks.section', $data['studentuser']->section)
    ->where('homeworks.section', $section)
    ->where('homeworks.pubdate', $targetpubdate)
    ->get();
    $data['pubdate'] = $targetpubdate;

    //query for the schdule
    $day = strtolower(date('l'));
    $data['schedule'] = DB::table('subjects')
    ->select('subject','grade','section','schedule')
    // ->where('grade', $data['studentuser']->grade)
    ->where('grade', $grade)
    // ->where('section', $data['studentuser']->section)
    ->where('section', $section)
    ->where($day, 1)
    ->orderBy('schedule', 'asc')
    ->get();

    //get subjects for sidemenu
    // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
    $data['subjects'] = DB::table('subjects')
    ->select('subject')
    // ->where('grade', $data['studentuser']->grade)
    ->where('grade', $grade)
    // ->where('section', $data['studentuser']->section)
    ->where('section', $section)
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
            // $data['calendar'][$month->month] = DB::table('calendar')
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

    // return view('soverview')->with($data);

    return response()->json([
        'homeworks' => $data['homeworks']->toArray(),
        'prev' => $data['prev'],
                'next' => $data['next'],
        'schedule' => $data['schedule']->toArray(),
        'calendar' => $data['calendar'],
        'months' => $data['months']->toArray(),

    ]);

}

public function toverviewAssignments(Request $request,$tid){

    

    $data['teacheruser'] = DB::table('teachers')->where('id', $tid )->first();
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

// return view('toverview')->with($data);
return response()->json([
    'homeworks' => $data['homeworks']->toArray(),
    'prev' => $data['prev'],
            'next' => $data['next'],
    'schedule' => $data['schedule']->toArray(),
    'calendar' => $data['calendar'],
    'months' => $data['months']->toArray(),

]);

}

public function sReplyslips(Request $request, $grade = 0, $section=0, $sid=0 ) {
    // $data['notifications'] = $this->notificationsListHeaderNav();
    // $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    // if (Auth::check() ) {
            //check if session is set
            // if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            //end check is session is set

    // $data['page_title'] = 'Reply Slips';
    // $currentIdent = $request->session()->get('currentIdent','default');
            // $data['user'] = Auth::user();
            //query for the student info
            // $data['studentuser'] = DB::table('students')->where('id', $currentIdent )->first();
            //replyslip scope filter

            if($grade > 0 && $grade < 13){$scope[] = "na";}
            if($grade > 7 && $grade < 13){$scope[] = "hs";}
            if($grade > 0 && $grade < 8){$scope[] = "gs";}
            if($grade == 'k' ){$scope[] = "na";}
            if($grade == 'p' ){$scope[] = "na";}
            if($grade == 'n' ){$scope[] = "na";}
            $myArray = implode($scope, ',');
            // echo session('currentIdent');die();

            //query for reply slips
            $data['replyslips'] = DB::table('replyslips')
            ->select('replyslips.*','uploads.type as type','uploads.filename as filename','replyslips_ans.rid','replyslips_ans.sid','replyslips_opt.oid','replyslips_opt.choice')
            ->leftJoin('uploads', 'replyslips.upload_id', '=', 'uploads.uid')
            // ->leftJoin('replyslips_ans', 'replyslips_ans.rid', '=', 'replyslips.id')
            ->leftJoin('replyslips_ans', function ($join) use ($sid){
                    $join->on('replyslips_ans.rid', '=', 'replyslips.id')
                         ->where('replyslips_ans.sid', '=', $sid);
                    //      ->where('replyslips_ans.sid', '=', Auth::user()->ident);
                })
            ->leftJoin('replyslips_opt', 'replyslips_opt.oid', '=', 'replyslips_ans.oid')
             ->whereIn('replyslips.grade', $scope)
            //  ->where('replyslips_ans.sid', $data['studentuser']->id)
            // ->where('replyslips.publish', '1')
            ->orderBy('replyslips.date','desc')
           
            ->get();
            
            $data['scope']=$scope;
            // $data['testing']= session('currentIdent');
            
            //get subjects for sidemenu
    // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
    // $data['subjects'] = DB::table('subjects')
    // ->select('subject')
    // ->where('grade', $data['studentuser']->grade)
    // ->where('section', $data['studentuser']->section)
    // ->groupBy('subject')
    // ->orderBy('rankorder', 'desc')
    // ->get();
    
    // return view('sreplyslips')->with($data);
    // }else{
    //         Auth::logout();
    //         return redirect('/login');
    // }

    return response()->json([
        'replyslips' => $data['replyslips']->toArray()
        // 'tests' => $data['tests']->toArray(),
        // 'activitysheets' => $data['activitysheets'],
        // 'handouts' => $data['handouts']
        // 'months' => $data['months']->toArray(),

    ]);
}

public function notifications(Request $request, $uid=0){

   
    
        //check if session is set
        // if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
        // $currentIdent = session('currentIdent');
  
    // $data['user'] = Auth::user();
            //query for the student info
    
//     if(Auth::user()->type == 's' || Auth::user()->type == 'p'){
//      $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();

//     //get subjects for sidemenu
//     // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
//     $data['subjects'] = DB::table('subjects')
//     ->select('subject')
//     ->where('grade', $data['studentuser']->grade)
//     ->where('section', $data['studentuser']->section)
//     ->groupBy('subject')
//     ->orderBy('rankorder', 'desc')
//     ->get();
//     }elseif(Auth::user()->type == 't' || Auth::user()->type == 'a'){
//             $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

//             $data['subjects'] = DB::table('teacher_subj')
// ->select('subj','grade','section')
// ->where('t_id', $data['teacheruser']->id)
// // ->where('section', $data['studentuser']->section)
// // ->groupBy('subject')
// // ->orderBy('rankorder', 'desc')
// ->get();
//     }

    $data['notificationsList'] = DB::table('notify_users')
    ->select('*')
    ->leftJoin('notify', 'notify_users.nid', '=', 'notify.id')
    // ->where('viewed','0')
    // ->where('uid',Auth::user()->id)
    ->where('uid', $uid)
    ->orderBy('date','desc')
    ->get();

    
    // return $data['notificationsList'];
    return response()->json([
        'notifications' => $data['notificationsList']->toArray()
        // 'tests' => $data['tests']->toArray(),
        // 'activitysheets' => $data['activitysheets'],
        // 'handouts' => $data['handouts']
        // 'months' => $data['months']->toArray(),

    ]);

  }


public function subjects(Request $request, $subj, $grade, $section) {
    // $data['notifications'] = $this->notificationsListHeaderNav();
    // $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    $data['page_title'] = 'Subjects';
    $subj = str_replace('_',' ',trim($subj));
    $data['subject'] = $subj;//fur future - sanitazie subject
    
    
            //check if session is set
            // if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
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
    ->where('homeworks.grade', $grade)
    ->where('homeworks.section', $section)
    ->where('homeworks.subject', $subj)
    ->orderBy('pubdate', 'desc')
    ->limit('5')

    ->get();

    // $users = DB::table('users')->count();

    $data['totals']=DB::table('homeworks')
    ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
    ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
    ->where('homeworks.grade', $grade)
    ->where('homeworks.section', $section)
    ->where('homeworks.subject', $subj)
    ->count();
    
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

      //get subjects for sidemenu
    // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
    // $data['subjects'] = DB::table('subjects')
    // ->select('subject')
    // ->where('grade', $data['studentuser']->grade)
    // ->where('section', $data['studentuser']->section)
    // ->groupBy('subject')
    // ->orderBy('rankorder', 'desc')
    // ->get();

    return response()->json([
        'homeworks' => $data['homeworks']->toArray(),
        'tests' => $data['tests']->toArray(),
        'activitysheets' => $data['activitysheets'],
        'handouts' => $data['handouts'],
        'totals' => $data['totals']
        

    ]);

}

public function calendar(Request $request){


    //get calendar all
    $data['months'] = DB::table('calendar')
    ->select('month')
    ->groupBy('month')
    // ->orderBy('month(date)','asc')
    ->orderBy(DB::raw('Month(date)'))
    ->get();
    foreach($data['months'] as  $month){
            // $data['calendar'][$month->month] = DB::table('calendar')
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

    $arr = array();
           foreach($data['calendar'] as $eventmonth){
              foreach($eventmonth as $key => $value){ 
            
                $valuearray = array(
                    'title' => "$value->event",
                    'start' => "$value->date",
                    'end' => "$value->enddate",
                    'backgroundColor' => "$value->color",
                    'borderColor' => "$value->color"
                );
                array_push($arr, $valuearray);
                
          
        // {
        //     title: "{{$value->event}}",
        //     start:"{{$value->date}}",
        //     end:"{{$value->enddate}}",
        //     backgroundColor: "{{$value->color}}", 
        //     borderColor: "{{$value->color}}" 
        //   }
        }
        }

       return response()->json($arr);


    //    return response()->json([
    //     'homeworks' => $data['homeworks']->toArray(),
    //     'tests' => $data['tests']->toArray(),
    //     'activitysheets' => $data['activitysheets'],
    //     'handouts' => $data['handouts']
    //     // 'months' => $data['months']->toArray(),

    // ]);


}


// public function profile(Request $request, $sid=0) {
//     // $data['notifications'] = $this->notificationsListHeaderNav();
//     // $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
//     $data['page_title'] = 'Profile';
  
//             //check if session is set
//             // if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
//             //end check is session is set
//             // $currentIdent = $request->session()->get('currentIdent','default');
//             $data['user'] = Auth::user();

//             if($sid == 0){
//                     $profileId = $currentIdent;
//             }else{
//                     $profileId = $sid;
//             }
//             // if(Auth::user()->type =='t' or Auth::user()->type =='a'){
//             //         $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();
//             // }
//             //query for the student info
//             $data['studentuser'] = DB::table('students')->where('id', $profileId )->first();
//             //age calculation
//             $age = DateTime::createFromFormat('Y-m-d', $data['studentuser']->birthdate)
//             ->diff(new DateTime('now'))
//             ->y;
//             $data['studentuser']->age = $age;
//             //preferered contact
//             switch ($data['studentuser']->s_prefcontact) {
//                     case 'guardian':
//                         $data['studentuser']->prefcontactname = $data['studentuser']->s_guardianname;
//                         $data['studentuser']->prefcontactno = $data['studentuser']->s_guardiancellno;
//                         break;
//                     case 'dad':
//                     $data['studentuser']->prefcontactname = $data['studentuser']->s_dadname;
//                     $data['studentuser']->prefcontactno = $data['studentuser']->s_dadcellno;
//                         break;
//                     default:
//                     $data['studentuser']->prefcontactname = $data['studentuser']->s_momname;
//                     $data['studentuser']->prefcontactno = $data['studentuser']->s_momcellno;;
//                 }

    
//     // if(Auth::user()->type == 's' || Auth::user()->type == 'p'){
//     // //get subjects for sidemenu
  
//     // $data['subjects'] = DB::table('subjects')
//     // ->select('subject')
//     // ->where('grade', $data['studentuser']->grade)
//     // ->where('section', $data['studentuser']->section)
//     // ->groupBy('subject')
//     // ->orderBy('rankorder', 'desc')
//     // ->get();
//     // }elseif(Auth::user()->type == 't'){
//     //  //get subjects for sidemenu

//     //  $data['subjects'] = DB::table('teacher_subj')
//     //  ->select('subj','grade','section')
//     //  ->where('t_id', $data['teacheruser']->id)
//     //  // ->where('section', $data['studentuser']->section)
//     //  // ->groupBy('subject')
//     //  // ->orderBy('rankorder', 'desc')
//     //  ->get();       
//     // }

//     $data['attendance'] = DB::table('attendance')
//     ->select('*')
//     ->where('sid',$profileId)
//     ->whereIn('status',['absent','late'])
//     ->orderBy('date','desc')
//     ->get();

//     $data['incidents'] = DB::table('incidents')
//     ->select('*')
//     ->where('sid',$profileId)
//     ->orderBy('date','desc')
//     ->get();

//     $data['achievements'] = DB::table('achievements')
//     ->select('*')
//     ->where('sid',$profileId)
//     ->get();

//     $data['medical'] = DB::table('medical')
//     ->select('*')
//     ->where('sid',$profileId)
//     ->get();

//     $data['paymentdues'] = DB::table('payment_dues')
//     ->select('*')
//     ->where('sid',$profileId)
//     ->orderBy('date','desc')
//     ->get();

    
    
//     return response()->json([
//         'studentuser' => $data['studentuser'],
//         'attendance' => $data['attendance']->toArray(),
//         'incidents' => $data['incidents']->toArray(),
//         'achievements' => $data['achievements']->toArray(),
//         'medical' => $data['medical'],
//         'paymentdues' => $data['paymentdues']
//         // 'months' => $data['months']->toArray(),

//     ]);


// }

public function getChat(Request $request,$grade,$section,$lastTimeID = 0){

    $arr = array();
    $line = new \stdClass;
    $mid = $lastTimeID;

    // select * from chat where grade = ? and section = ? and mid > ? order by mid asc limit 50

    if($mid == 0){
    $data['chat'] = DB::table('chat')
    ->select('*')
    ->where('grade',$grade)
    ->where('section',$section)
    ->orderBy('mid','desc')
    ->limit(30)
    ->get()
    ->reverse()
    ->values()
    ->all();
    
    
    }else{
        $data['chat'] = DB::table('chat')
    ->select('*')
    ->where('grade',$grade)
    ->where('section',$section)
    ->where('mid','>',$mid)
    ->orderBy('mid','asc')
    ->limit(30)
    ->get();
    }

foreach($data['chat'] as $i => $chat){
    $data['chat'][$i]->mid = $chat->mid;
    $data['chat'][$i]->uid = $chat->uid;

        if($chat->utype == 's' or $chat->utype == 'p'){
        // $user = getStudentTeacher($utype, $uid);
        
        if($chat->utype == 's'){
        $userinfo = DB::table('students')
        ->select('*')
        ->where('id',$chat->uid)
        ->first();
        }
        if($chat->utype == 'p'){
            $userinfo = DB::table('students')
            ->select('*')
            ->where('s_mom_id',$chat->uid)
            ->where('grade',$chat->grade)
            ->where('section',$chat->section)
            ->first();

            if(!$userinfo){               
                $userinfo = DB::table('students')
                ->select('*')
                ->where('s_dad_id',$chat->uid)
                ->where('grade',$chat->grade)
                ->where('section',$chat->section)
                ->first();
            }

        }
    }elseif($chat->utype == 't' or $chat->utype == 'a'){
        $userinfo = DB::table('teachers')
        ->select('*')
        ->where('id',$chat->uid)
        ->first();
    }
    
    if($chat->utype == 's'){
    $data['chat'][$i]->username = UCWORDS($userinfo->firstname." ".$userinfo->lastname);
    $data['chat'][$i]->avatar = $userinfo->profilepic;
    $data['chat'][$i]->message = $chat->message;
    $data['chat'][$i]->chattime = date('d M, Y h:i a', $chat->timestamp);
    $data['chat'][$i]->grade = $userinfo->grade;
    $data['chat'][$i]->section = $userinfo->section;
    $data['chat'][$i]->secret = $chat->uid;
} 
elseif($chat->utype == 'p'){
    // for the mom
    if($chat->uid == $userinfo->s_mom_id){
        $data['chat'][$i]->username = UCWORDS($userinfo->s_momname);
        $data['chat'][$i]->avatar = $userinfo->s_mom_profilepic;
        $data['chat'][$i]->message = $chat->message;
        $data['chat'][$i]->chattime = date('d M, Y h:i a', $chat->timestamp);
        $data['chat'][$i]->grade = $userinfo->grade;
        $data['chat'][$i]->section = $userinfo->section;
        $data['chat'][$i]->secret = $chat->uid;
    }//for the dad
    elseif($chat->uid == $userinfo->s_dad_id){
        $data['chat'][$i]->username = UCWORDS($userinfo->s_dadname);
        $data['chat'][$i]->avatar = $userinfo->s_dad_profilepic;
        $data['chat'][$i]->message = $chat->message;
        $data['chat'][$i]->chattime = date('d M, Y h:i a', $chat->timestamp);
        $data['chat'][$i]->grade = $userinfo->grade;
        $data['chat'][$i]->section = $userinfo->section;
        $data['chat'][$i]->secret = $chat->uid;
    }
    
}
elseif($chat->utype == 't' or $chat->utype == 'a'){
    if($chat->uid == $userinfo->id){
        $data['chat'][$i]->username = UCWORDS($userinfo->firstname." ".$userinfo->lastname);
        $data['chat'][$i]->avatar = $userinfo->profilepic;
        $data['chat'][$i]->message = $chat->message;
        $data['chat'][$i]->chattime = date('d M, Y h:i a', $chat->timestamp);
        $data['chat'][$i]->grade = $grade;
        $data['chat'][$i]->section = $section;
        $data['chat'][$i]->secret = $chat->uid;
    }
}
        if(Auth::user()->ident == $chat->uid){ 
          $data['chat'][$i]->placement1 = 'right';
          $data['chat'][$i]->placement2 = 'right';
          $data['chat'][$i]->placement3 = 'left';
        }else{
        $data['chat'][$i]->placement1 = 'left';
        $data['chat'][$i]->placement2 = 'left';
        $data['chat'][$i]->placement3 = 'right';
        }
        $arr[] = $line;



    }
        


    return response()->json([
        'chat' => $data['chat'],
        // 'chat' => $arr
       

    ]);

}

public function postChat(Request $request){

    $classArray = explode('-',$request->input('gs'));
        $grade = $classArray[0];
        $section = $classArray[1];

    $ident = $request->input('ui');
    // $ident = Auth::user()->ident;
    // $message = strip_tags(stripslashes($request->input('chatmsg')));
    $message = $request->input('chatmsg');
    $utype = $request->input('utype');
    $timenow = time();
    echo "$message<br />";
echo "$ident<br />";
echo "$utype<br />";
echo "$grade<br />";
echo "$timenow<br />";
echo "$section<br />";
    // if(Auth::user()->type == 's'){
    // $studentuser = DB::table('students')->where('id', $ident )->first();
    // }
    // elseif(Auth::user()->type == 'p'){

    //     $result = DB::table('students')
    //             ->select('id','s_mom_name','s_mom_ident','s_mom_profilepic')
    //             ->where('s_mom_id', $ident)
    //             ->get();

    //             if($result->isEmpty()){               
    //                 $result = DB::table('students')
    //             ->select('id','s_dad_name','s_dad_ident','s_dad_profilepic')
    //             ->where('s_dad_id', $ident)
    //             ->get();
    //             }

    // }


    // $mid = DB::table('chat')->insertGetId(
        $mid = DB::table('chat')->insertGetId(
        [
            
            'message' => $message, 
            'uid' => $ident,
            'utype' => $utype,
            'grade' => $grade,
            'section' => $section,
            'timestamp' =>$timenow
        ]
    );

    // Auth::user()->ident
    return response()->json([
        'chat' => $mid
        // 'chat' => $arr
       

    ]);

}

public function getChatMobile(Request $request,$grade,$section,$lastTimeID = 0,$userid){

    

    $arr = array();
    $line = new \stdClass;
    $mid = $lastTimeID;


    // select * from chat where grade = ? and section = ? and mid > ? order by mid asc limit 50

    if($mid == 0){
    $data['chat'] = DB::table('chat')
    ->select('*')
    ->where('grade',$grade)
    ->where('section',$section)
    ->orderBy('mid','desc')
    ->limit(30)
    ->get()
    ->reverse()
    ->values()
    ->all();
    }else{
        $data['chat'] = DB::table('chat')
    ->select('*')
    ->where('grade',$grade)
    ->where('section',$section)
    ->where('mid','>',$mid)
    ->orderBy('mid','asc')
    ->limit(30)
    ->get();
    }
    
foreach($data['chat'] as $i => $chat){
    $data['chat'][$i]->mid = $chat->mid;
    $data['chat'][$i]->uid = $chat->uid;
    
    
        if($chat->utype == 's' or $chat->utype == 'p'){
        // $user = getStudentTeacher($utype, $uid);
        
        if($chat->utype == 's'){
        $userinfo = DB::table('students')
        ->select('*')
        ->where('id',$chat->uid)
        ->first();
        
        }
        if($chat->utype == 'p'){
            $userinfo = DB::table('students')
            ->select('*')
            ->where('s_mom_id',$chat->uid)
            ->where('grade',$chat->grade)
            ->where('section',$chat->section)
            ->first();
            
            if(!$userinfo){               
                $userinfo = DB::table('students')
                ->select('*')
                ->where('s_dad_id',$chat->uid)
                ->where('grade',$chat->grade)
                ->where('section',$chat->section)
                ->first();
            }

        }
        
    }elseif($chat->utype == 't' or $chat->utype == 'a'){
        $userinfo = DB::table('teachers')
        ->select('*')
        ->where('id',$chat->uid)
        ->first();
    }
    
    if($chat->utype == 's'){
    $data['chat'][$i]->username = UCWORDS($userinfo->firstname." ".$userinfo->lastname);
    $data['chat'][$i]->avatar = $userinfo->profilepic;
    $data['chat'][$i]->message = $chat->message;
    $data['chat'][$i]->chattime = date('d M, Y h:i a', $chat->timestamp);
    $data['chat'][$i]->grade = $userinfo->grade;
    $data['chat'][$i]->section = $userinfo->section;
    $data['chat'][$i]->secret = $chat->uid;

  
} 
elseif($chat->utype == 'p'){
    // for the mom

    if($chat->uid == $userinfo->s_mom_id){
        $data['chat'][$i]->username = UCWORDS($userinfo->s_momname);
        $data['chat'][$i]->avatar = $userinfo->s_mom_profilepic;
        $data['chat'][$i]->message = $chat->message;
        $data['chat'][$i]->chattime = date('d M, Y h:i a', $chat->timestamp);
        $data['chat'][$i]->grade = $userinfo->grade;
        $data['chat'][$i]->section = $userinfo->section;
        $data['chat'][$i]->secret = $chat->uid;
        
    }//for the dad
    elseif($chat->uid == $userinfo->s_dad_id){
        $data['chat'][$i]->username = UCWORDS($userinfo->s_dadname);
        $data['chat'][$i]->avatar = $userinfo->s_dad_profilepic;
        $data['chat'][$i]->message = $chat->message;
        $data['chat'][$i]->chattime = date('d M, Y h:i a', $chat->timestamp);
        $data['chat'][$i]->grade = $userinfo->grade;
        $data['chat'][$i]->section = $userinfo->section;
        $data['chat'][$i]->secret = $chat->uid;
       
    }
    
}
elseif($chat->utype == 't' or $chat->utype == 'a'){
    if($chat->uid == $userinfo->id){
        $data['chat'][$i]->username = UCWORDS($userinfo->firstname." ".$userinfo->lastname);
        $data['chat'][$i]->avatar = $userinfo->profilepic;
        $data['chat'][$i]->message = $chat->message;
        $data['chat'][$i]->chattime = date('d M, Y h:i a', $chat->timestamp);
        $data['chat'][$i]->grade = $grade;
        $data['chat'][$i]->section = $section;
        $data['chat'][$i]->secret = $chat->uid;
    }
    
}

        if($userid == $chat->uid){ 
          $data['chat'][$i]->placement1 = 'right';
          $data['chat'][$i]->placement2 = 'right';
          $data['chat'][$i]->placement3 = 'left';
        }else{
        $data['chat'][$i]->placement1 = 'left';
        $data['chat'][$i]->placement2 = 'left';
        $data['chat'][$i]->placement3 = 'right';
        }
        $arr[] = $line;
        
        
        
    }
    
    


    return response()->json([
        'chat' => $data['chat'],
        // 'chat' => $arr
       

    ]);

}

public function postChatMobile(Request $request){

    $classArray = explode('-',$request->input('gs'));
        $grade = $classArray[0];
        $section = $classArray[1];

    $ident = $request->input('ui');
    // $ident = Auth::user()->ident;
    // $message = strip_tags(stripslashes($request->input('chatmsg')));
    $message = $request->input('chatmsg');
    $utype = $request->input('utype');
    $timenow = time();
//     echo "$message<br />";
// echo "$ident<br />";
// echo "$utype<br />";
// echo "$grade<br />";
// echo "$timenow<br />";
// echo "$section<br />";
    

        $mid = DB::table('chat')->insertGetId(
        [
            
            'message' => $message, 
            'uid' => $ident,
            'utype' => $utype,
            'grade' => $grade,
            'section' => $section,
            'timestamp' =>$timenow
        ]
    );

    // Auth::user()->ident
    return response()->json([
        'chat' => $mid
        // 'chat' => $arr
       

    ]);

}


public function ajaxScheduleAll(Request $request,$id){
    $totalsched = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
            $schedlength = count($totalsched);
            $result=array();
            //SELECT subject, grade, section, schedule FROM subjects where grade = :grade and section = :section and $day = 1 order by rankorder desc
            // if(Auth::user()->type =='s' || Auth::user()->type =='p'){
            // $data['studentuser'] = DB::table('students')->where('id',$id )->first();
            $data = [];                
            $studentuser = DB::table('students')->where('id',$id )->first();                
            foreach($totalsched as $day){
                $data[$day] = DB::table('subjects')
                ->select('subject', 'grade', 'section', 'schedule')
                ->where('grade', $studentuser->grade)
                ->where('section', $studentuser->section)
                ->where($day, 1)
                ->orderBy('rankorder','desc')

                ->get();
            }
            // }elseif(Auth::user()->type == 't'){
            // $data['teacheruser'] = DB::table('teachers')->where('id',$id )->first();
                
            //     foreach($totalsched as $day){
            //         $data[$day] = DB::table('teacher_subj')
            //         ->select('teacher_subj.t_id','subjects.*')
            //         ->leftJoin('subjects', function($join){
            //             $join->on('teacher_subj.subj', '=', 'subjects.subject');
            //             $join->on('teacher_subj.grade','=','subjects.grade'); 
            //             $join->on('teacher_subj.section','=','subjects.section'); 
            //             })
            //             ->where('teacher_subj.t_id',$data['teacheruser']->id)
            //         ->where($day, 1)
            //         ->orderByRaw('schedule desc')
            //         ->get();    
            //     }
  
            // return view('layouts/ajaxScheduleAll')->with($data);
            return response()->json([
                // 'chat' => $data['chat']->toArray(),
                'schedule' => $data
                // 'chat' => $arr
               
        
            ]);
// }
}


public function ajaxHWDate(Request $request, $grade, $section, $date){

    $data['homeworks'] = DB::table('homeworks')
        ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
        ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
        ->where('homeworks.grade', $grade)
        ->where('homeworks.section', $section)
        ->where('homeworks.pubdate', $date)
        ->get();
        $data['pubdate'] = '';
        $data['prev'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate < ?', $date)
         ->whereRaw('grade= ? ', $grade )
         ->whereRaw('section= ? ', $section )
        ->orderByRaw('pubdate  DESC')
        ->first(); 
        $data['next'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate > ?', $date)
         ->whereRaw('grade= ? ', $grade )
         ->whereRaw('section= ? ', $section )
        ->orderByRaw('pubdate  ASC')
        ->first(); 

            // return view('layouts/ajaxScheduleAll')->with($data);
            return response()->json([
                // 'chat' => $data['chat']->toArray(),
                'homeworks' => $data['homeworks']->toArray(),
                'prev' => $data['prev'],
                'next' => $data['next']
                // 'chat' => $arr
               
        
            ]);
// }
}

public function ajaxTHWDate(Request $request, $tid, $date){

    $data['homeworks'] = DB::table('homeworks')
        ->select('*')
        // ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
        // ->where('homeworks.grade', $grade)
        // ->where('homeworks.section', $section)
        ->where('teacher_id', $tid)
        ->where('pubdate', $date)
        ->get();
        
        $data['pubdate'] = '';
        $data['prev'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate < ?', $date)
        //  ->whereRaw('grade= ? ', $grade )
        //  ->whereRaw('section= ? ', $section )
        ->where('teacher_id', $tid)
        ->orderByRaw('pubdate  DESC')
        ->first(); 
        $data['next'] = DB::table('homeworks')
        ->selectRaw('pubdate')
        ->whereRaw('pubdate > ?', $date)
        //  ->whereRaw('grade= ? ', $grade )
        //  ->whereRaw('section= ? ', $section )
         ->where('teacher_id', $tid)
        ->orderByRaw('pubdate  ASC')
        ->first(); 


//         $datenow = date('Y-m-d');
// $pubdate = DB::table('homeworks')
//     ->selectRaw('pubdate')
//     ->whereRaw('pubdate <= ?', $datenow)
//      ->whereRaw('teacher_id= ? ', $data['teacheruser']->id )
//     ->orderByRaw('pubdate  DESC')
//     ->first();
//     $data['prev'] = DB::table('homeworks')
//     ->selectRaw('pubdate')
//     ->whereRaw('pubdate < ?', $pubdate->pubdate)
//      ->whereRaw('teacher_id= ? ', $data['teacheruser']->id )

//     ->orderByRaw('pubdate  DESC')
//     ->first(); 
//     $data['next'] = DB::table('homeworks')
//     ->selectRaw('pubdate')
//     ->whereRaw('pubdate > ?', $pubdate->pubdate)
//      ->whereRaw('teacher_id= ? ', $data['teacheruser']->id )
//     ->orderByRaw('pubdate  ASC')
//     ->first(); 

// $data['homeworks'] = DB::table('homeworks')
// ->select('*')
// // ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
// // ->where('grade', $data['studentuser']->grade)
// ->where('teacher_id', $data['teacheruser']->id)
// ->where('pubdate', $pubdate->pubdate)
// ->get();
            // return view('layouts/ajaxScheduleAll')->with($data);
            return response()->json([
                // 'chat' => $data['chat']->toArray(),
                'homeworks' => $data['homeworks']->toArray(),
                'prev' => $data['prev'],
                'next' => $data['next']
                // 'chat' => $arr
               
        
            ]);
// }
}

public function profile(Request $request, $utype = 0, $sid=0 ) {
    
   
           
            //end check is session is set
            // $currentIdent = $request->session()->get('currentIdent','default');
            // $data['user'] = Auth::user();

            // if($sid == 0){
            //         $profileId = $currentIdent;
            // }else{
                    $profileId = $sid;
            // }

            if($utype =='t' or $utype =='a'){
                    $data['teacheruser'] = DB::table('teachers')->where('id', $profileId )->first();


                    // if(Auth::user()->type =='t' ){
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
                        
                        $data['classes'] = DB::table('teacher_subj')
                        ->select('subj','grade','section')
                        ->where('t_id', $data['teacheruser']->id)
                        // ->where('section', $data['studentuser']->section)
                        // ->groupBy('subject')
                        // ->orderBy('rankorder', 'desc')
                        ->get();
             
                    //  }
            }
            
            if($utype =='s' or $utype =='p'){
            //query for the student info
            $data['studentuser'] = DB::table('students')->where('id', $profileId )->first();
            
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

    
    // if(Auth::user()->type == 's' || Auth::user()->type == 'p'){
    // //get subjects for sidemenu
    // // SELECT subject FROM subjects where grade = :grade and section = :section group by subject order by rankorder desc
    // $data['subjects'] = DB::table('subjects')
    // ->select('subject')
    // ->where('grade', $data['studentuser']->grade)
    // ->where('section', $data['studentuser']->section)
    // ->groupBy('subject')
    // ->orderBy('rankorder', 'desc')
    // ->get();
    // }elseif(Auth::user()->type == 't'){
    //  //get subjects for sidemenu

    //  $data['subjects'] = DB::table('teacher_subj')
    //  ->select('subj','grade','section')
    //  ->where('t_id', $data['teacheruser']->id)
    //  // ->where('section', $data['studentuser']->section)
    //  // ->groupBy('subject')
    //  // ->orderBy('rankorder', 'desc')
    //  ->get();       
    // }

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

    $data['subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $data['studentuser']->grade)
        ->where('section', $data['studentuser']->section)
        ->groupBy('subject')
        ->orderBy('rankorder', 'desc')
        ->get();

    }
    
    // return view('sprofile')->with($data);
if ($utype == 's' || $utype == 'p'){
    return response()->json([
        // 'chat' => $data['chat']->toArray(),
        'studentuser' => $data['studentuser'],
        'attendance' => $data['attendance']->toArray(),
        'incidents' => $data['incidents']->toArray(),
        'achievements' => $data['achievements']->toArray(),
        'medical' => $data['medical'],
        'paymentdues' => $data['paymentdues']->toArray(),
        'subjects' => $data['subjects'],
        // 'chat' => $arr
       

    ]);
}elseif($utype == 't' || $utype == 'a'){
    return response()->json([
        // 'chat' => $data['chat']->toArray(),
        'teacheruser' => $data['teacheruser'],
        'subjects' => $data['subjects']->toArray(),
        'classes' => $data['classes']->toArray(),

    ]);
}
    
}

public function directory(Request $request, $sid) {
      
            //query for the student info
            $data['studentuser'] = DB::table('students')->where('id', $sid )->first();

   
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


    // return view('sdirectory')->with($data);
    return response()->json([
        // 'chat' => $data['chat']->toArray(),
        // 'studentuser' => $data['studentuser'],
        'classmates' => $data['classmates']->toArray(),
        'teachers' => $data['teachers']->toArray(),
       
        
        // 'chat' => $arr
       

    ]);

}

public function childList(Request $request, $pid) {
        
        // if (Auth::check() &&  Auth::user()->type=='p'){
        //121999 - mom
                // session(['currentIdent' => 1211009]);
                
                $result = DB::table('students')
                ->select('id','firstname','lastname','grade','section','profilepic')
                // ->where('s_mom_id', Auth::user()->ident)
                ->where('s_mom_id', $pid)
                ->get();

                if($result->isEmpty()){               
                    $result = DB::table('students')
                ->select('id','firstname','lastname','grade','section','profilepic')
                ->where('s_dad_id', $pid)
                ->get();
                }
                if ($result->count()) {
                    // do something
                    return response()->json([
                        // 'chat' => $data['chat']->toArray(),
                        // 'studentuser' => $data['studentuser'],
                        'children' => $result->toArray(),
                        // 'teachers' => $data['teachers']->toArray(),
                       
                        
                        // 'chat' => $arr
                       
                
                    ]);
                    
                }
                // return new Response(view(‘soverview’));
                // print_r(session('children'));echo session('currentIdent');die();
                return redirect('/overview');
            
            // }

}

public static function sendNotification($groupType = '', $title = '', $message = ''){

    $sendGroup = explode('-',$groupType);

    if($sendGroup[0] == 's') {
        $sendTo = 'grade'.$sendGroup[1];
    } else {
        $sendTo = $sendGroup[0];
    }

#API access key from Google API's Console
    
    // define( 'API_ACCESS_KEY', 'AAAAVbYk0JE:APA91bElMhR1t6JXeNfGvZlhg1id7-3pv8fVwOk5PUrp1pLkfuWmRrWGgH_zT1In6uvgaSMhOjM9xDpHMJ3RcYW6fniG2gN6xLLYKgp6u3dF_X7ZnnlJkKFZH5A929UtuEgFw_ApKTtN' );
    define( 'API_ACCESS_KEY', 'AIzaSyCgIEGLn57eqOxHa5jD7EqWkXD_D2CglBY' ); /* legacy server key of firebase */
    $registrationIds = $groupType;
#prep the bundle
    $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

     $msg = array
          (
		'body' 	=> $message,
		'title'	=> $title,
        'icon'	=> 'myicon',/*Default Icon*/
        'sound' => 'mySound'/*Default sound*/
          );

	$fields = array
			(
                // 'to'		=> $registrationIds,
                'to'		=> '/topics'.'/'. $sendTo,
                // 'to'		=> '/topics/grade4',
				'notification'	=> $msg
			);
	
	
	
#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
        curl_close( $ch );
        // 
#Echo Result Of FireBase Server
// echo json_encode($result);

}

public function tdirectory(Request $request, $tid){
    
    $data['teacheruser'] = DB::table('teachers')->where('id', $tid)->first();

         //get Teachers of specified grade and sectionsection
        //SELECT teacher_subj.*, teachers.* FROM `teacher_subj` left join teachers on teacher_subj.t_id = teachers.id WHERE teacher_subj.grade =:grade and teacher_subj.section = :section group by t_id
        $data['teachers'] = DB::table('teachers')
        ->select('*')
        ->get();
        foreach($data['teachers'] as $key => $teacher){
        $data['teachers'][$key]->class = DB::table('teacher_subj')
        ->select('*')
        ->where('t_id',$teacher->id)
        ->get();
        }
        // if(Auth::user()->type =='t'){
        $data['subjects'] = DB::table('teacher_subj')
    ->select('subj','grade','section')
    ->where('t_id', $data['teacheruser']->id)
    // ->where('section', $data['studentuser']->section)
    // ->groupBy('subject')
    // ->orderBy('rankorder', 'desc')
    ->get();
        // }

        // return view('adirectoryteachers')->with($data);
        return response()->json([
            // 'chat' => $data['chat']->toArray(),
            // 'studentuser' => $data['studentuser'],
            // 'classmates' => $data['classmates']->toArray(),
            'teachers' => $data['teachers']->toArray(),
           
            
            // 'chat' => $arr
           
    
        ]);
}

public function tclass(Request $request, $subj, $grade, $section) {
    
    $subj = str_replace('_',' ',trim($subj));
    $data['subject'] = $subj;
    $data['grade'] = $grade;
    $data['section'] = $section;

  
     

        
        // $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

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
->where('homeworks.subject', $subj)
->count();

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



return response()->json([
    'homeworks' => $data['homeworks']->toArray(),
    'tests' => $data['tests'],
    'activitysheets' => $data['activitysheets']->toArray(),
    'handouts' => $data['handouts'],
    'totals' => $data['totals']
    // 'classmates' => $data['classmates']->toArray(),
    // 'teachers' => $data['teachers']->toArray(),
    // 'chat' => $arr
]);

}

public function ajaxHWSubjectsLoadMore($subj,$grade,$section,$totals,$page){

    // dont forget to validate, sanitize and authorize
    // $date = $request->input('date');
    $limit = 5;
    $totalpages = ceil($totals / $limit);
    $offset = ($page - 1)*$limit;

    $subj = str_replace('_', ' ', trim($subj));
    
        // $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();

        $data['homeworks'] = DB::table('homeworks')
            ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
            ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
            ->where('homeworks.grade', $grade)
            ->where('homeworks.section', $section)
            ->where('homeworks.subject', $subj)
            ->orderBy('pubdate','desc')
            ->offset($offset)
            ->limit(5)
            ->get();
    
    
    // return view('layouts/ajaxHWSubjectsLoadMore')->with($data);
    return response()->json([
        'homeworks' => $data['homeworks']->toArray(),
        
    ]);
}


public function ajaxTeachersHWSubjectsLoadMore($subj,$grade,$section,$totals,$page){

    // dont forget to validate, sanitize and authorize
    // $date = $request->input('date');
    $subj = str_replace('_',' ',trim($subj));
    
    $limit = 5;
    $totalpages = ceil($totals / $limit);
    $offset = ($page - 1)*$limit;

    // $subj = str_replace(' ', '_', trim($subj));
   
        // $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['homeworks'] = DB::table('homeworks')
            ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
            ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
            ->where('homeworks.grade', $grade)
            ->where('homeworks.section', $section)
            ->where('homeworks.subject', $subj)
            ->orderBy('pubdate','desc')
            ->offset($offset)
            ->limit(5)
            ->get();
    
               //get subjects for sidemenu

            
               return response()->json([
                'homeworks' => $data['homeworks']->toArray(),
                
            ]);
    
}

public function attendance(Request $request, $tid, $grade=0, $section=0, $date = 0){

    
    if($date == 0){$date = date('Y-m-d');}
    
    $data['date'] = $date;
    $data['datePrev'] = date('Y-m-d',strtotime($date)-(60*60*24) );
    $data['dateNext'] = date('Y-m-d',strtotime($date)+(60*60*24) );;
    $data['grade'] = $grade;
    $data['section'] = $section;
    $data['gradesection'] = DB::table('students')->select('grade','section')->groupBy('grade','section')->orderByRaw('CAST(`grade` AS UNSIGNED) asc')->get();
    // order by CAST(`grade` AS UNSIGNED) ascs
   
        
    
        // $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', $tid )->first();

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
                // echo "<pre>";
                // print_r($data['students']);
                // echo "</pre>";
                // die();

    // return view('tclass')->with($data);
    return response()->json([
        'students' => $data['students']->toArray(),
        'gradesection' => $data['gradesection']->toArray(),
        'attendance' => $data['attendance'],
        'attendanceGrade' => $data['attendanceGrade'],
        'attendanceSection' => $data['attendanceSection'],
        'datePrev' => $data['datePrev'],
        'dateNext' => $data['dateNext'],
    ]);
    
  }


  public function attendancePost(Request $request){

    $date = $request->input('date');
    $grade = $request->input('grade');
    $section = $request->input('section');
    $tid = $request->input('tid');
    $time = date('H:i:s');
    
        //check if session is set
        // if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
    
        $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', $tid )->first();

//    echo "<pre>";
//                 print_r($request->input());
//                 echo "</pre>";
//                 die();
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


    //             $referer = $request->server('HTTP_REFERER');
    // return redirect()->to($referer);
            

  }

  public function attendancePostTest(Request $request){
// echo "test";die();
    $date = $request->input('date');
    $grade = $request->input('grade');
    $section = $request->input('section');
    $tid = $request->input('tid');
    $time = date('H:i:s');
    
        //check if session is set
        // if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
    
        $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', $tid )->first();

//    echo "<pre>";
//                 print_r($request->input());
//                 echo "</pre>";
//                 die();
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
                            $posted = DB::table('attendance')
                            ->where('id', $check1->id)
                            ->update([
                                'status' => $request->input('student-'.$student->id),
                                'time' => $time
                            ]);
                        }else{
                            //insert the fucker
                            $posted = DB::table('attendance')->insert(
                                [
                                    'date' => $date, 
                                    'status' => $request->input('student-'.$student->id),
                                    'time' => $time,
                                    'sid' => $student->id,
                                    'tid' =>$data['teacheruser']->id

                                ]
                            );

                        }

                        if($posted == true){
                            $jsonmessage = 'success';
                        }else{
                            $jsonmessage = 'error';
                        }
    
                    }
                }


    //             $referer = $request->server('HTTP_REFERER');
    // return redirect()->to($referer);
    return response()->json([
        'message' => $jsonmessage,
        // 'grade' => $request->input('grade'),
        // 'section' => $request->input('section'),
        // 'tid' => $request->input('tid'),
        // 'student-100000'=>$request->input('student-100000'),
        // 'student-100002'=>$request->input('student-100002'),
        // 'student-100001'=>$request->input('student-100001'),
        // 'date' => $request->input('student-'.$student->id)->toArray(),
        // 'date' => $request->input('student-'.$student->id)->toArray(),
        
    ]);

  }


public function assignmentPost(Request $request) {
    
    // echo "assignment has been posted<br />";
    // print_r($request->input());
    $classArray = explode('_',$request->input('class'));
    // print_r($classArray);echo $request->input('class'); die();
    $subject = $classArray[0];
    $grade = $classArray[1];
    $section = $classArray[2];
    $teacherid = $request->input('tid');
    
    $description=$request->input('description');
    $inputdate = date('Y-m-d H:i:s');   
    $pubdate = $request->input('pubdate');

    //print_r($request->input());

    // echo Auth::user()->ident;
    
    // whats needed is
    // pubdate: date , subject:varchar , grade:varchar, section:varchar, spacial:int, 

    $posted = DB::table('homeworks')->insert(
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
    if($posted == true){
        $jsonmessage = 'success';
    }else{
        $jsonmessage = 'error';
    }
    // // return redirect('/toverview');
    // $referer = $request->server('HTTP_REFERER');
    // return redirect()->to($referer);
    return response()->json([
        'message' => $jsonmessage,
        // 'grade' => $request->input('grade'),
        // 'section' => $request->input('section'),
        // 'tid' => $request->input('tid'),
        // 'student-100000'=>$request->input('student-100000'),
        // 'student-100002'=>$request->input('student-100002'),
        // 'student-100001'=>$request->input('student-100001'),
        // 'date' => $request->input('student-'.$student->id)->toArray(),
        // 'date' => $request->input('student-'.$student->id)->toArray(),
        
    ]);


}
  public function assignmentEdit(Request $request) {

    // if (Auth::check()) {
        // $request->session()->flash('status', 'Task was successful!');

        $classArray = explode('_',$request->input('class'));
        $subject = $classArray[0];
        $grade = $classArray[1];
        $section = $classArray[2];
        $teacherid = $request->input('tid');

        

        $id = $request->input('hw');
            
    
            $description=$request->input('description');
            $inputdate = date('Y-m-d H:i:s');   
            $pubdate = $request->input('pubdate');

            $description_clean = htmlspecialchars($description);
            // echo $subject.' - '; echo $grade.' - '; echo $section.' - ';
            // echo $teacherid.' - '; echo $pubdate.' - '; echo $inputdate.' - ';die();

            $posted = DB::table('homeworks')
            ->where('id', $id)
            ->update([
                'subject' => $subject,
                'grade' => $grade,
                'section' => $section,
                'teacher_id' => $teacherid,
                'description' => $description_clean,
                'pubdate' => $pubdate,
                'inputdate' => $inputdate
            ]);

            $data['homework'] = DB::table('homeworks')->where('id', $id)->first();

            if($posted == true){
                $jsonmessage = 'success';
            }else{
                $jsonmessage = 'error';
            }
            // print_r($request->input());
            return response()->json([
                'message' => $jsonmessage,
                // 'grade' => $request->input('grade'),
                // 'section' => $request->input('section'),
                // 'tid' => $request->input('tid'),
                // 'student-100000'=>$request->input('student-100000'),
                // 'student-100002'=>$request->input('student-100002'),
                // 'student-100001'=>$request->input('student-100001'),
                // 'date' => $request->input('student-'.$student->id)->toArray(),
                // 'date' => $request->input('student-'.$student->id)->toArray(),
                
            ]);
            // if($updated){
            //     echo "updated";
            // }
            // else{
            //     echo "look again";
            // }
    // }
}

public function  assignmentDel (Request $request){

   
        $id = $request->input('hw');
        $result = DB::table('homeworks')->where('id', '=', $id)->delete();
        if($result == true){
            $jsonmessage = 'success';
        }else{
            $jsonmessage = 'error';
        }

        return response()->json([
            'message' => $jsonmessage,
        ]);
    
    
}

public function testEdit(Request $request) {

    
        // $request->session()->flash('status', 'Task was successful!');
        $classArray = explode('_',$request->input('class'));
        $subject = $classArray[0];
        $grade = $classArray[1];
        $section = $classArray[2];

        $id = $request->input('testid');
            // print_r($request->input());
            $teacherid = $request->input('tid');
    
            $title=$request->input('title');
             
            $date = $request->input('pubdate');
            $period = $request->input('period');
            // $title = htmlspecialchars($description);
        // print_r($request->input());
        // echo "test!";

        $posted = DB::table('tests')
            ->where('id', $id)
            ->update([
                'subject' => $subject,
                'grade' => $grade,
                'section' => $section,
                'period' =>$period,
                'title' => $title,
                'date' => $date
            ]);

            // $data['test'] = DB::table('tests')->where('id', $id)->first();

            if($posted == true){
                $jsonmessage = 'success';
            }else{
                $jsonmessage = 'error';
            }
            // // return redirect('/toverview');
            // $referer = $request->server('HTTP_REFERER');
            // return redirect()->to($referer);
            return response()->json([
                'message' => $jsonmessage,
                // 'grade' => $request->input('grade'),
                // 'section' => $request->input('section'),
                // 'tid' => $request->input('tid'),
                // 'student-100000'=>$request->input('student-100000'),
                // 'student-100002'=>$request->input('student-100002'),
                // 'student-100001'=>$request->input('student-100001'),
                // 'date' => $request->input('student-'.$student->id)->toArray(),
                // 'date' => $request->input('student-'.$student->id)->toArray(),
                
            ]);
}

public function  testDel (Request $request){

    
        $id = $request->input('testid');
        $result = DB::table('tests')->where('id', '=', $id)->delete();
        if($result == true){
            $jsonmessage = 'success';
        }else{
            $jsonmessage = 'error';
        }

        return response()->json([
            'message' => $jsonmessage,
        ]);
    
    
}

public function asEdit(Request $request) {

   
        // $request->session()->flash('status', 'Task was successful!');
        $classArray = explode('_',$request->input('class'));
        $subject = $classArray[0];
        $grade = $classArray[1];
        $section = $classArray[2];

        $id = $request->input('asid');
            // print_r($request->input());
            $teacherid = $request->input('tid');
    
            $title=$request->input('title');
             
            $date = $request->input('pubdate');
            $period = $request->input('period');
            // $title = htmlspecialchars($description);
        // print_r($request->input());
        // echo "test!";

        $posted = DB::table('activitysheets')
            ->where('id', $id)
            ->update([
                'subject' => $subject,
                'grade' => $grade,
                'section' => $section,
                'period' =>$period,
                'title' => $title,
                'date' => $date
            ]);

            if($posted == true){
                $jsonmessage = 'success';
            }else{
                $jsonmessage = 'error';
            }
          
            return response()->json([
                'message' => $jsonmessage,
                
            ]);
    
}

public function  asDel (Request $request){

    
        $id = $request->input('asid');
        $result = DB::table('activitysheets')->where('id', '=', $id)->delete();
        if($result == true){
            $jsonmessage = 'success';
        }else{
            $jsonmessage = 'error';
        }

        return response()->json([
            'message' => $jsonmessage,
        ]);
    
    
}

public function hoEdit(Request $request) {

    
        // $request->session()->flash('status', 'Task was successful!');
        $classArray = explode('_',$request->input('class'));
        $subject = $classArray[0];
        $grade = $classArray[1];
        $section = $classArray[2];

        $id = $request->input('hoid');
            // print_r($request->input());
            $teacherid = $request->input('tid');
    
            $title=$request->input('title');
             
            $date = $request->input('pubdate');
            $period = $request->input('period');
            // $title = htmlspecialchars($description);
        // print_r($request->input());
        // echo "test!";

        $posted = DB::table('handouts')
            ->where('id', $id)
            ->update([
                'subject' => $subject,
                'grade' => $grade,
                'section' => $section,
                'period' =>$period,
                'title' => $title,
                'date' => $date
            ]);

            if($posted == true){
                $jsonmessage = 'success';
            }else{
                $jsonmessage = 'error';
            }
          
            return response()->json([
                'message' => $jsonmessage,
                
            ]);
    
}

public function  hoDel (Request $request){

    
        $id = $request->input('ho');
        $result = DB::table('handouts')->where('id', '=', $id)->delete();
        if($result == true){
            $jsonmessage = 'success';
        }else{
            $jsonmessage = 'error';
        }

        return response()->json([
            'message' => $jsonmessage,
        ]);
    
}


public function sdirectory(Request $request, $grade='', $section=0){
   
        //check if session is set
        // if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

    // $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
    if($grade=='' && $section==''){
        return response()->json([
        'message' => 'No grade and section chosen',
        // 'gradesection' => $data['gradesection']->toArray(),
        // 'attendance' => $data['attendance'],
        // 'attendanceGrade' => $data['attendanceGrade'],
        // 'attendanceSection' => $data['attendanceSection'],
        // 'datePrev' => $data['datePrev'],
        // 'dateNext' => $data['dateNext'],
        ]);
    }else{

        // SELECT * FROM students where grade = :grade and section = :section ORDER BY lastname desc
        $data['students'] = DB::table('students')
        ->select('id','firstname','lastname', 'grade','section','profilepic')
        ->where('grade', $grade)
        ->where('section', $section)
        ->orderBy('lastname', 'asc')
        ->get();

        return response()->json([
            'classmates' => $data['students'],
        ]);

    }
    
}

public function directorySearchStudent(Request $request, $term=0){

    
        // echo $term;
        $term = str_replace('_',' ',$term);
        // echo $term;die();
    
       

    $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

if($term){

    $searchableTerm = $this->fullTextWildcards($term);
// SELECT * FROM students where grade = :grade and section = :section ORDER BY lastname desc
$data['classmates'] = DB::table('students')
->selectRaw("*, MATCH (`firstname`,`lastname`) AGAINST (? IN BOOLEAN MODE) AS relevance_score", [$searchableTerm])
->whereRaw("MATCH (`firstname`,`lastname`) AGAINST (? IN BOOLEAN MODE)", $searchableTerm)
        ->orderByDesc('relevance_score')
->get();
}

// if(Auth::user()->type =='t'){
//     $data['subjects'] = DB::table('teacher_subj')
// ->select('subj','grade','section')
// ->where('t_id', $data['teacheruser']->id)
// // ->where('section', $data['studentuser']->section)
// // ->groupBy('subject')
// // ->orderBy('rankorder', 'desc')
// ->get();
// }
return response()->json([
    'classmates' => $data['classmates'],
]);
    
}

protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
 
        $words = explode(' ', $term);
 
        foreach($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if(strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }
 
        $searchTerm = implode( ' ', $words);
 
        return $searchTerm;
    }


public function gradeSectionList(Request $request)
{
    $data['gradesection'] = DB::table('students')
    ->select('grade','section')
    ->groupBy('grade','section')
    ->orderByRaw('CAST(`grade` AS UNSIGNED) asc')
    ->get();
    // return view('adirectorystudents')->with($data);
    return response()->json([
        'gradesection' => $data['gradesection'],
    ]);
}

//added by elmer
public function getDeviceId(Request $request, $id){

    $data['user_device_id'] = DB::table('user_device')
    ->where('userid', $id)
    ->first();

    if($data['user_device_id'] == null) {
        $data['device_get_id'] = 'none';
    } else {
        $data['device_id'] = DB::table('user_device')
        ->select('deviceid')
        ->where('userid', $id)
        ->first();

        if($data['device_id']->deviceid != null) {
            $data['device_get_id'] = $data['device_id']->deviceid;
        } else {
            $data['device_get_id'] = 'none';
        }
        
    }


    return response()->json($data['device_get_id']);
  }

  public function createDeviceId(Request $request, $id){
    $data['device_id'] = $request->input('deviceid');

    $data['create_user'] = DB::table('user_device')
    ->insert([
        'deviceid'=>$data['device_id'],
        'userid'=>$id
    ]);

    $data['response'] = array('isSend'=>'created', $data['device_id']);

    return response()->json($data);
  }

  public function updateDeviceId(Request $request, $id){

    $data['device_id'] = $request->input('deviceid');

    $data['update_user'] = DB::table('user_device')
    ->where('userid', $id)
    ->update([
        'deviceid'=>$data['device_id']
    ]);

    $data['response'] = array('isSend'=>'updated', $data['device_id']);

    return response()->json($data);
  }

  public function  notifyUpdate (Request $request){
        
    

        $userid = $request->input('uid');
        $nid = $request->input('nid');

        $check = DB::table('notify_users')->where('nid',$nid)->where('uid', $userid)->first();
        if($check){
            $update = DB::table('notify_users')
            ->where('id', $check->id)
            ->update([
                'viewed' => 1
                ]);
        }

        if($update){
        return response()->json([
            'message' => 'update success',
        ]);
    }

}   

public static function getLatestActivity($uid) {
    $getIDs = DB::table('activity_users')
            ->select('id')
            ->where('uid', $uid)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->skip(9)
            ->take(1)
            ->first();

    return $getIDs;
}

public static function storeActivityForWeb($user_uid, $user_utype, $activity) {
    $uid = $user_uid;
    $utype = $user_utype;
    $activity = $activity;
    $date = date('Y-m-d H:i:s');
    $device_type = 'w';


    $posted = DB::table('activity_users')->insertGetId(
        [
            'uid' => $uid,
            'utype' => $utype,
            'activity' => $activity,
            'date' => $date,
            'device_type' => $device_type,
        ]
    );

    $jsonmessage = $posted ? 'success' : 'error';

    // $sortID = self::getLatestActivity($uid);

    // if(isset($sortID->id)) {
    //     DB::table('activity_users')->where('uid', $uid)->where('id', '<', $sortID->id)->delete();

    //     return response()->json([
    //         'activity'=>$jsonmessage
    //     ]);

    // } else {
        return response()->json([
            'activity'=>$jsonmessage
        ]);
    // }        
}


public function storeActivity(Request $request) {
    $ID = $request->input('id');
    $uid = $request->input('uid');
    $utype = $request->input('utype');
    $activity = $request->input('activity');
    $date = date('Y-m-d H:i:s');
    $device_type = $request->input('device_type');


    $posted = DB::table('activity_users')->insertGetId(
        [
            'uid' => $uid,
            'utype' => $utype,
            'activity' => $activity,
            'date' => $date,
            'device_type' => $device_type,
        ]
    );

    $jsonmessage = $posted ? 'success' : 'error';

    // $sortID = $this->getLatestActivity($uid);

    // if(isset($sortID->id)) {
    //     DB::table('activity_users')->where('uid', $uid)->where('id', '<', $sortID->id)->delete();

    //     return response()->json([
    //         'activity'=>$jsonmessage
    //     ]);

    // } else {
        return response()->json([
            'activity'=>$jsonmessage
        ]);
    // }        

}


public static function getTeachersFromActivity() {
    $teachers_uid = DB::table('activity_users')
                                ->select('uid')
                                ->where('utype', 't')
                                ->groupBy('uid')
                                ->get();

    $_uid_set = [];

    foreach($teachers_uid as $val) {
        $get_uid = DB::table('users')->where('id', $val->uid)->first();
        array_push($_uid_set, $get_uid);
    }

    $teacher_uid_arr = [];
    $teacher_names_arr = [];

    foreach($teachers_uid as $teacher) {
        $get_activity_teacher = DB::table('activity_users')->where('uid', $teacher->uid)->orderBy('date', 'desc')->first();

        array_push($teacher_uid_arr, $get_activity_teacher);
    }

    foreach($_uid_set as $teacher) {
        $get_activity_teacher = DB::table('teachers')->where('id', $teacher->ident)->first();

        array_push($teacher_names_arr, [
            'details'=>$get_activity_teacher,
            'id'=>$teacher->id
            ]);
    }

    $data['teacher_data'] = [];
    foreach($teacher_uid_arr as $uid) {
        foreach($teacher_names_arr as $teacher) {
            if($uid->uid === $teacher['id']) {
                $type = null;
                if($uid->device_type == 'a') {
                    $type = 'Android';
                } else if ($uid->device_type == 'i') {
                    $type = 'iOs';
                } else {
                    $type = 'Web';
                }
                array_push($data['teacher_data'], array(
                    'id'=>$uid->id,
                    'teacher_name'=>$teacher['details']->firstname.' '.$teacher['details']->lastname,
                    'uid'=>$uid->uid,
                    'utype'=>$uid->utype,
                    'activity'=>$uid->activity,
                    'date'=>$uid->date,
                    'device_type'=>$type,
                ));
            }
        }
    }

    return response()->json($data);
}

public static function getParentsFromActivity() {
    $_uid = DB::table('activity_users')
                                ->select('uid')
                                ->where('utype', 'p')
                                ->groupBy('uid')
                                ->get();
    $_uid_set = [];

    foreach($_uid as $val) {
        $get_uid = DB::table('users')->where('id', $val->uid)->first();
        array_push($_uid_set, $get_uid);
    }

    $_uid_arr = [];
    $_names_arr = [];

    foreach($_uid as $val) {
        $get_activity = DB::table('activity_users')->where('uid', $val->uid)->orderBy('date', 'desc')->first();

        array_push($_uid_arr, $get_activity);
    }

    foreach($_uid_set as $val) {
        $get_activity = DB::table('students')->where('s_mom_id', $val->ident)->exists();

        if($get_activity) {
            $id = $val->id;
            $get_details = DB::table('students')->select(DB::raw("s_mom_id as id, s_momname as name, firstname, lastname"))->where('s_mom_id', $val->ident)->first();
        } else {
            $id = $val->id;
            $get_details = DB::table('students')->select(DB::raw("s_dad_id as id, s_dadname as name, firstname, lastname"))->where('s_dad_id', $val->ident)->first();
        }

        array_push($_names_arr, [
            'details'=>$get_details, 
            'id'=>$id
            ]);
    }

    $data['parent_data'] = [];
    foreach($_uid_arr as $uid) {
        foreach($_names_arr as $val) {
            if($uid->uid === $val['id']) {
                $type = null;
                if($uid->device_type == 'a') {
                    $type = 'Android';
                } else if ($uid->device_type == 'i') {
                    $type = 'iOs';
                } else {
                    $type = 'Web';
                }
                array_push($data['parent_data'], array(
                    'id'=>$uid->id,
                    'parent_name'=>$val['details']->name,
                    'student_firstname'=>$val['details']->firstname,
                    'student_lastname'=>$val['details']->lastname,
                    'uid'=>$uid->uid,
                    'utype'=>$uid->utype,
                    'activity'=>$uid->activity,
                    'date'=>$uid->date,
                    'device_type'=>$type,
                ));
            }
        }
    }

    return response()->json($data);
}

public static function getActivityCount($startdate, $enddate) {
    $data['dashboard'] = DB::table('activity_users')
        ->select('uid')
        ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
        ->where('activity', 'dashboard')
        ->groupBy('uid')
        ->count();
    $data['replyslip'] = DB::table('activity_users')
        ->select('uid')
        ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
        ->where('activity', 'replyslip')
        ->groupBy('uid')
        ->count();
    $data['notification'] = DB::table('activity_users')
        ->select('uid')
        ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
        ->where('activity', 'notification')
        ->groupBy('uid')
        ->count();
    $data['chat'] = DB::table('activity_users')
        ->select('uid')
        ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
        ->where('activity', 'chat')
        ->groupBy('uid')
        ->count();
    $data['subject'] = DB::table('activity_users')
        ->select('uid')
        ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
        ->where('activity', 'subject')
        ->groupBy('uid')
        ->count();

    return response()->json($data);
}


}
