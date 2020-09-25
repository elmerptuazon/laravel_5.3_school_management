<?php

namespace App\Http\Controllers;
// ini_set('max_execution_time', 300); //use this only to test export pdf. disable when deployed
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\TeacherFetchJSON;
use App\Http\Controllers\ApiController;


class AdminController extends NavigationController
{
    //
    public function index(Request $request){
        
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();
            
        $day = strtolower(date('l'));
        $data['day']=$day;
        $data['monthnow'] = strtolower(date('F'));
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
        }

        $data['replyslips'] = DB::table('replyslips')
                // ->select('replyslips.*','uploads.type as type','uploads.filename as filename','replyslips_ans.rid','replyslips_ans.sid','replyslips_opt.oid','replyslips_opt.choice')
                ->select('replyslips.*','uploads.type as type','uploads.filename as filename')
                ->leftJoin('uploads', 'replyslips.upload_id', '=', 'uploads.uid')
               
               ->orderBy('id','desc')
            //    ->limit(1)
                ->get();

        foreach($data['replyslips'] as $key=>$replyslip){
            $data['replyslips'][$key]->replyoption = DB::table('replyslips_opt')
            ->select('*')
            ->where('rid',$replyslip->id)
            ->get();

            
        }

        // $users = DB::table('users')->count();
        foreach($data['replyslips'] as$key=> $replyslip){
                // $data['replyslips'][$key]->replyans = DB::table('replyslips_ans')
                foreach($replyslip->replyoption as $key2=> $option){
                // echo "test"; die();
        $data['replyslips'][$key]->replyoption[$key2]->replyans = DB::table('replyslips_ans')
            ->where('oid',$option->oid)
            ->count();
            // ->get();
        }

        }

        // print_r($data['replyslips']);die();
        $data['activeusers']['admin'] = 3;
        $data['activeusers']['teachers'] = 8;
        $data['activeusers']['parents'] = 5;
        $data['activeusers']['students'] = 20;

        // $date=date('Y-m-d');
        $date=date("Y-m-d");
        $data['attendance'] = DB::table('attendance')
        ->select('attendance.*','students.firstname','students.lastname','students.grade','students.section')
        ->leftJoin('students', 'attendance.sid', '=', 'students.id')
        ->where('attendance.date',$date)
        ->where('attendance.status','present')
        ->count();
        $data['presentTotal'] = DB::table('attendance')->where('attendance.date',$date)->where('attendance.status','present')->count();
        $data['absentTotal'] = DB::table('attendance')->where('attendance.date',$date)->where('attendance.status','absent')->count();
        $data['lateTotal'] = DB::table('attendance')->where('attendance.date',$date)->where('attendance.status','late')->count();
        $data['studentsTotal'] = DB::table('students')->count();

        


        // return view('aoverview')->with($data);
        return view('aoverview')->with($data);

    }else{
        Auth::logout();
        return redirect('/login');
    }

    }

    public function sdirectory(Request $request, $grade='', $section=0){

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['page_title'] = 'DIRECTORY';
        
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();

if($grade=='' && $section==''){
    
}else{
    
   // SELECT * FROM students where grade = :grade and section = :section ORDER BY lastname desc
   $data['classmates'] = DB::table('students')
   ->select('id','firstname','lastname', 'grade','section','profilepic')
   ->where('grade', $grade)
   ->where('section', $section)
   ->orderBy('lastname', 'asc')
   ->get();
    
   $data['subjects'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        ->get();
}

if(Auth::user()->type =='t'){
    $data['subjects'] = DB::table('teacher_subj')
->select('subj','grade','section')
->where('t_id', $data['teacheruser']->id)
// ->where('section', $data['studentuser']->section)
// ->groupBy('subject')
// ->orderBy('rankorder', 'desc')
->get();
}

$data['gradesection'] = DB::table('students')->select('grade','section')->groupBy('grade','section')->orderByRaw('CAST(`grade` AS UNSIGNED) asc')->get();

        return view('adirectorystudents')->with($data);
        }
    }

//     SELECT *, 
// MATCH(first_name, last_name, email) AGAINST ('term' IN BOOLEAN MODE) AS relevance_score
// FROM users
// WHERE MATCH(first_name, last_name, email) AGAINST ('term' IN BOOLEAN MODE);

public function directorySearchStudent(Request $request, $term=0){

    $data['notifications'] = $this->notificationsListHeaderNav();
    $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    $data['page_title'] = 'DIRECTORY';
        // echo $term;
        $term = str_replace('_',' ',$term);
        // echo $term;die();
    if (Auth::check() ) {
        //check if session is set
        if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

    $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
    $data['subjects'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        ->get();

if($term){

    $searchableTerm = $this->fullTextWildcards($term);
// SELECT * FROM students where grade = :grade and section = :section ORDER BY lastname desc
$data['classmates'] = DB::table('students')
->selectRaw("*, MATCH (`firstname`,`lastname`) AGAINST (? IN BOOLEAN MODE) AS relevance_score", [$searchableTerm])
->whereRaw("MATCH (`firstname`,`lastname`) AGAINST (? IN BOOLEAN MODE)", $searchableTerm)
        ->orderByDesc('relevance_score')
->get();
}

if(Auth::user()->type =='t'){
    $data['subjects'] = DB::table('teacher_subj')
->select('subj','grade','section')
->where('t_id', $data['teacheruser']->id)
// ->where('section', $data['studentuser']->section)
// ->groupBy('subject')
// ->orderBy('rankorder', 'desc')
->get();
}

$data['gradesection'] = DB::table('students')->select('grade','section')->groupBy('grade','section')->orderByRaw('CAST(`grade` AS UNSIGNED) asc')->get();
    return view('adirectorystudents')->with($data);
    }
}

public function directorySearchTeacher(Request $request, $term=0){

    $data['notifications'] = $this->notificationsListHeaderNav();
    $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    $data['page_title'] = 'DIRECTORY';
    // echo $term;
    $term = str_replace('_',' ',$term);
    // echo $term;die();
if (Auth::check() ) {
    //check if session is set
    if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

$data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
$data['subjects'] = DB::table('teacher_subj')
->select('subj','grade','section')
->get();

if($term){

$searchableTerm = $this->fullTextWildcards($term);
// SELECT * FROM students where grade = :grade and section = :section ORDER BY lastname desc
$data['teachers'] = DB::table('teachers')
->selectRaw("*, MATCH (`firstname`,`lastname`) AGAINST (? IN BOOLEAN MODE) AS relevance_score", [$searchableTerm])
->whereRaw("MATCH (`firstname`,`lastname`) AGAINST (? IN BOOLEAN MODE)", $searchableTerm)
    ->orderByDesc('relevance_score')
->get();

foreach($data['teachers'] as $key => $teacher){
    $data['teachers'][$key]->class = DB::table('teacher_subj')
    ->select('*')
    ->where('t_id',$teacher->id)
    ->get();
}
}

if(Auth::user()->type =='t'){
    $data['subjects'] = DB::table('teacher_subj')
->select('subj','grade','section')
->where('t_id', $data['teacheruser']->id)
// ->where('section', $data['studentuser']->section)
// ->groupBy('subject')
// ->orderBy('rankorder', 'desc')
->get();
}

return view('adirectoryteachers')->with($data);
}
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

    public function tdirectory(Request $request){

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['page_title'] = 'DIRECTORY';

        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        ->groupBy('subj','grade','section')
        ->orderBy('grade')
        ->orderBy('subj')
        ->get();

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
        if(Auth::user()->type =='t'){
            $data['subjects'] = DB::table('teacher_subj')
    ->select('subj','grade','section')
    ->where('t_id', $data['teacheruser']->id)
    // ->where('section', $data['studentuser']->section)
    // ->groupBy('subject')
    // ->orderBy('rankorder', 'desc')
    ->get();
        }

        return view('adirectoryteachers')->with($data);
        }
    }

    public function calendar(Request $request){

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        $data['page_title']= "Calendar of Events";

        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
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

        return view('acalendar')->with($data);
    }else{
        Auth::logout();
        return redirect('/login');
    }


    }

    public function calendarPost(Request $request){
        //date month event fromtime totime allday eventtype

        // echo "<pre>";
        // print_r($request->input());
        // echo "</pre>";


        // echo "test";

        $date = $request->input('date');
        $endDate = $request->input('enddate');
        $month = strtolower(date('F', strtotime($date)));
        // echo $month;die();
        $event = $request->input('event');
        $eventtype = $request->input('type');
        

        DB::table('calendar')->insert(
            [
                'date' => $date, 
                'enddate' => $endDate,
                'month' => $month,
                'event' => $event,
                'eventtype' => $eventtype
            ]
        );
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);

    }


    public function  calendarEdit (Request $request){
        
// echo "test123";die();
        if(Auth::check()){
            // echo "test"; die();
            $id = $request->input('cid');
            $date = $request->input('date');
            $endDate = $request->input('enddate');
            $event = $request->input('event');
        if($event == '') {
            
            return redirect()->back()->with('error', 'Not recorded. Please complete all details.');
        } else if($date == '') {
            
            return redirect()->back()->with('error', 'Not recorded. Please complete all details.');
        } else if($endDate == '') {
            
            return redirect()->back()->with('error', 'Not recorded. Please complete all details.');
        }
        $month = strtolower(date('F', strtotime($date)));
        // echo $month;die();
        
        $eventtype = $request->input('type');   
            
            DB::table('calendar')
                ->where('id', $id)
                ->update([
                    'date' => $date,
                    'enddate' => $endDate,
                    'month' => $month,
                    'event' => $event,
                    'eventtype' => $eventtype
                ]);
       
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);

        }
        
    }   
    public function  calendarDel (Request $request){
        

        if(Auth::check()){
            // echo "test"; die();
            $id = $request->input('cid');
            
            $result = DB::table('calendar')->where('id', '=', $id)->delete();
           
       
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);

        }
        
    }   

    public function addOptionRs(Request $request){

        // print_r($request->input());

        foreach($request['choices'] as $choice){

            // echo "<br /> choice : $cho";
            $uid = DB::table('replyslips_opt')->insertGetId(
                [
                    'rid' => $request['rs'], 
                    'choice' => $choice
                ]
            );
        }

        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);

    }

    public function replyslips(Request $request){

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();

        $data['replyslips'] = DB::table('replyslips')
        // ->select('replyslips.*','uploads.type as type','uploads.filename as filename','replyslips_ans.rid','replyslips_ans.sid','replyslips_opt.oid','replyslips_opt.choice')
        ->select('replyslips.*','uploads.type as type','uploads.filename as filename')
        ->leftJoin('uploads', 'replyslips.upload_id', '=', 'uploads.uid')
       
       ->orderBy('id','desc')
    //    ->limit(1)
        ->get();

foreach($data['replyslips'] as $key=>$replyslip){
    $data['replyslips'][$key]->replyoption = DB::table('replyslips_opt')
    ->select('*')
    ->where('rid',$replyslip->id)
    ->get();

    
}

// $users = DB::table('users')->count();
foreach($data['replyslips'] as$key=> $replyslip){
        // $data['replyslips'][$key]->replyans = DB::table('replyslips_ans')
        foreach($replyslip->replyoption as $key2=> $option){
        // echo "test"; die();
$data['replyslips'][$key]->replyoption[$key2]->replyans = DB::table('replyslips_ans')
    ->where('oid',$option->oid)
    ->count();
    // ->get();
}

}

return view('areplyslips')->with($data);

}else{
    Auth::logout();
    return redirect('/login');
}

    }

    public function replyslipInput(Request $request,$id,$grade=0,$section=0){

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

$data['rid'] = $id;
$data['grade'] = $grade;
$data['section'] = $section;
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
        $data['subjects'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        ->get();

        $data['replyslip'] = DB::table('replyslips')
        ->select('replyslips.*','uploads.filename')
        ->leftJoin('uploads', 'replyslips.upload_id', '=', 'uploads.uid')
        ->where('replyslips.id', $id)
        ->first();

        $data['rsoptions']=DB::table('replyslips_opt')
                    ->where('rid',$id)
                    ->get();

                    $data['replyslip']->totalans = 0;
                    
                    $colors=['green','red','orange','blue','pink','olive','purple','yellow'];

                    foreach($data['rsoptions'] as$key=> $option){
                       
                    $data['rsoptions'][$key]->total = DB::table('replyslips_ans')
                    ->where('oid',$option->oid)
                    ->count();
                    
                    //total answered
                    $data['replyslip']->totalans += $data['rsoptions'][$key]->total;
                    $data['rsoptions'][$key]->color = $colors[$key];
                    
                    // ->get();
                    }
                    if($data['replyslip']->grade == 'gs'){$q = "grade > 0 and grade <8";}
                    if($data['replyslip']->grade == 'hs'){$q = "grade > 7 and grade < 13";}
                    if($data['replyslip']->grade == 'na'){$q = "grade > 0 and grade < 13";}
                    // if($data['replyslip']->grade == 'gs'){$q1 =0; $q2=8;}
                    // if($data['replyslip']->grade == 'hs'){$q1 =7; $q2=13;}
                    // if($data['replyslip']->grade == 'na'){$q1 =0; $q2=13;}

                $data['replyslip']->total = DB::table('students')
                    // ->whereRaw("grade > ? and grade < ?")
                   
                    ->whereRaw($q)
                    ->count();
                    $data['replyslip']->totalunans = $data['replyslip']->total - $data['replyslip']->totalans;



        if($grade !==0 && $section !==0){
            $data['students'] = DB::table('students')
           
            ->select('students.*','replyslips_ans.oid','replyslips_ans.rid')
            ->leftJoin('replyslips_ans', function($join) use($id){
                $join->on('replyslips_ans.sid', '=', 'students.id')
                ->where('replyslips_ans.rid', '=', $id );                                                                
                                
            })
            ->where('students.grade',$grade)
            ->where('students.section',$section)
            ->orderBy('students.lastname', 'asc')
            ->get();

            // foreach($data['students'] as $key=> $student){
            //     $data['students'][$key]->chosenOption = DB::table('replyslips_ans')
            //     ->select('oid')
            //     ->where('rid', $id)
            //     ->where('sid', $student->id)
            //     ->first('oid');
            // }

        }
        //added by elmer

        $data['grade_list'] = DB::table('students')
        ->select('grade')
        ->groupBy('grade')
        ->get();

        return view('areplyslipInput')->with($data);
    }else{
        Auth::logout();
        return redirect('/login');
    }

    }

    public function replyslipInputSubmit(Request $request, $id) {
        $grade = $request->input('grade');
        $section = $request->input('section');
        $rid = $request->input('rid');
        
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $students = DB::table('students')
        ->select('id')
        ->where('students.grade',$grade)
        ->where('students.section',$section)
        ->orderBy('students.lastname', 'asc')
        ->get();

            foreach($students as $student){
                //check if radio button is set
                if($request->input('reply-'.$student->id) !== null or $request->input('reply-'.$student->id) != ''){
                    // echo "<br />qweqwe| ".$request->input('reply-'.$student->id);

                    //check if ans already existing
                    $check1 = DB::table('replyslips_ans')
                    ->select('id')
                    ->where('sid',$student->id)
                    ->where('rid',$rid)
                    ->first();
                    //edit if existing
                    if($check1){
                        DB::table('replyslips_ans')
                        ->where('id', $check1->id)
                        ->update([
                            'oid' => $request->input('reply-'.$student->id)
                        ]);
                    }else{
                        //insert the fucker
                        DB::table('replyslips_ans')->insert(
                            [
                                'rid' => $rid, 
                                'oid' => $request->input('reply-'.$student->id),
                                'sid' => $student->id
                            ]
                        );
                    }

                }
            }

        // echo"<pre>";
        // print_r($request->input());
        // echo "</pre>";
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);
    }else{
        Auth::logout();
        return redirect('/login');
    }

    }

    public function uploadReplyslip(Request $request){


        // $extension = $request->file('thefile')->extension();
// $classArray = explode('_',$request->input('class'));
$subject = $request->input('utype');//sanitize for spaces eg christian living
$grade = $request->input('scope');
$section = $request->input('scope');


$title = $request->input('title');
// $period = $request->input('period');
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
            $type = 'rs';
    
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
            

                if($uploadtype == 'rs'){
                    $testid=DB::table('replyslips')->insertGetId(
                        [
                        'title' => $title, 
                        'upload_id' => $uid,
                        'publish' => 1,
                        'teacher_id' => $currentIdent,
                        'subject' => $subject,
                        'section' =>$section,
                        'grade' => $grade,
                        'date' => $date,
                        'period' => 0
        
                        ]
                    );
                }



            }
        }

        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);
    }else{
        Auth::logout();
        return redirect('/login');
    }

    }

    public function incidents(Request $request){

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        $data['page_title'] = 'Incidents';
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();

            $data['incidents'] = DB::table('incidents')
            ->select('*')
            ->orderBy('date','desc')
            ->get();

foreach($data['incidents'] as $key=> $incident) {
                if($incident->severity == 'warning'){$data['incidents'][$key]->color = 'info';}
                elseif($incident->severity == 'minor'){$data['incidents'][$key]->color = 'warning';}
                elseif($incident->severity == 'danger'){$data['incidents'][$key]->color = 'danger';}
                elseif($incident->severity == 'major'){$data['incidents'][$key]->color = 'danger';}
               
            }
        
            $data['gradesection'] = DB::table('students')->select('grade','section')->groupBy('grade','section')->orderByRaw('CAST(`grade` AS UNSIGNED) asc')->get();


        return view('aincidents')->with($data);
        // return view('aincidents');

        }else{
        Auth::logout();
        return redirect('/login');
        }

    }

    public function incidentPost(Request $request){

         
        $data['page_title'] = 'Incidents';
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        if(is_array($classArray = explode('-',$request->input('class')))){
            $grade = $classArray[0];//sanitize for spaces eg christian living
            $section = $classArray[1];
        }else{
            return redirect()->$request->server('HTTP_REFERER');
        }
        // echo $request->input('class');
        // $classArray = explode('-',$request->input('class'));
        // print_r($classArray);die();

  
        $date = $request->input('date');
        $sid    = $request->input('sid');
        $lastname   =   $request->input('lastname');
       $firstname   =   $request->input('firstname');
      
        $title  =   $request->input('title');
        $description    =   $request->input('description');
        $severity   =   $request->input('severity');
        $reported_by    =   $request->input('reported_by');
        $inputted_by    =   $currentIdent;

// print_r($request->input());die();
// [date] =>  [sid] => [firstname] => [lastname] => [class] =>  [reported_by] => [message] =>

        DB::table('incidents')->insert(
            [
                'date' => $date, 
                'sid' => $sid, 
                'lastname' => $lastname, 
                'firstname' => $firstname, 
                'grade' => $grade,
                'section' => $section,
                'title' => $title,
                'description' => $description,
                'severity' => $severity,
                'reported_by' => $reported_by,
                'inputted_by' => $inputted_by
            ]
        );

        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);
    }else{
        Auth::logout();
        return redirect('/login');
    }

    }


    public function incidentEdit(Request $request){
        $data['page_title'] = 'Incidents';
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
            // echo "test";
            // print_r($request->input());

            // iid] => 4 [date] => 2018-06-11 [sid] => 888 [firstname] => jacob [lastname] => ledda [class] => 2-b [severity] => warning [reported_by] => Ms. Beth Jimenez [title] => Not reading [description] => not reading 

            if(is_array($classArray = explode('-',$request->input('class')))){
                $grade = $classArray[0];//sanitize for spaces eg christian living
                $section = $classArray[1];
            }else{
                return redirect()->$request->server('HTTP_REFERER');
            }

            $id = $request->input('iid');
            $date = $request->input('date');
            $sid = $request->input('sid');
            $firstname = $request->input('firstname');
            $lastname = $request->input('lastname');
            $grade = $grade;
            $section = $section;
            $title = $request->input('title');
            $description = $request->input('description');
            $severity = $request->input('severity');
            $reported_by = $request->input('reported_by');
            

        DB::table('incidents')
                ->where('id', $id)
                ->update([
                    'date' => $date,
                    'sid' => $sid,
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'grade' => $grade,
                    'section' => $section,
                    'title' => $title,
                    'description' => $description,
                    'severity' => $severity,
                    'reported_by' => $reported_by
                ]);
       
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);
            }

    }

    public function  incidentDel (Request $request){
        

        if(Auth::check()){
            // echo "test"; die();
            $id = $request->input('iid');
            
            $result = DB::table('incidents')->where('id', '=', $id)->delete();
           
       
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);

        }
        
    }  


    private function base64url_encode($data) { 
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
      } 
      
      private function base64url_decode($data) { 
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 

      }

      public function selectGetAllGradeSection(){

        $result = DB::table('students')->select('grade,section')->groupBy('grade,section')->get();
        return $result;

      }

      public function notifications(Request $request){

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        $data['page_title'] = 'NOTIFICATIONS';
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();
        
        $data['user'] = Auth::user();

        $data['grade_section'] = DB::table('students')
        ->select('grade')
        ->groupBy('grade')
        ->whereNotIn('grade',['k','p','n'])
        ->get();

        $data['notificationsList'] = DB::table('notify')
        ->select('*')
        ->leftJoin('notify_users', 'notify_users.nid', '=', 'notify.id')
        // ->where('viewed','0')
        ->where('uid',Auth::user()->id)
        ->orderBy('date','desc')
        ->get();


        return view('anotifications')->with($data);
        // return view('aincidents');

        }else{
        Auth::logout();
        return redirect('/login');
        }

      }



      public function notificationPost(Request $request){
        
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $title = $request->input('title');
        $message = $request->input('message');

        $data['page_title'] = 'Incidents';
        if (Auth::check() && $title != '' && $message != '') {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
        
        $tid = session('currentIdent');
        $date = time();
        
        $group = $request->input('notification_select_sendgroup');
        $sendGroup = explode('-',$group);
        
        $nid=DB::table('notify')->insertGetId(
            [
                'date' => $date, 
                'title' => $title, 
                'message' => $message, 
                'tid' => $tid
            ]
        );
        
        if($sendGroup[0] == 'all') {
            $users = DB::table('users')->get();
        } elseif($sendGroup[0] == 'parents') {
            $sendGroup[0] = substr($sendGroup[0], 0, 1);
            $users = DB::table('users')
            ->where('type', $sendGroup[0])
            ->get();
        } elseif($sendGroup[0] == 'teachers') {
            $sendGroup[0] = substr($sendGroup[0], 0, 1);
            $users = DB::table('users')
            ->where('type', $sendGroup[0])
            ->get();
        } else {
            $usersident = [];
            $users = [];

            if($sendGroup[0] == 'kinder') {
                $sendGroup[1] = substr($sendGroup[0], 0, 1);
            } elseif($sendGroup[0] == 'nursery') {
                $sendGroup[1] = substr($sendGroup[0], 0, 1);
            } elseif($sendGroup[0] == 'prep') {
                $sendGroup[1] = substr($sendGroup[0], 0, 1);
            }

            $studentGrade_Section = DB::table('students')
            ->select('id')
            ->where('grade', $sendGroup[1])
            ->get();
            
           
            foreach($studentGrade_Section as $student){
                $student_mom_dad = DB::table('students')
                ->select('id','s_mom_id','s_dad_id')
                ->where('id', $student->id)
                ->get();

                if($student_mom_dad[0]->id != null) {
                    array_push($usersident, $student_mom_dad[0]->id);
                }
                
                if($student_mom_dad[0]->s_mom_id != null) {
                    array_push($usersident, $student_mom_dad[0]->s_mom_id);
                }
                
                if($student_mom_dad[0]->s_dad_id != null) {
                    array_push($usersident, $student_mom_dad[0]->s_dad_id);
                }
            }
            
            
            foreach($usersident as $users_list) {
                
                $user = DB::table('users')
                ->select('id')
                ->where('ident', $users_list)
                ->first();
                
                if($user != null) {
                    array_push($users, $user->id);
                    
                }
                
            }
        }

        if($sendGroup[0] == 'kinder') {
            $sendGroup[0] = substr($sendGroup[0], 0, 1);
        } elseif($sendGroup[0] == 'nursery') {
            $sendGroup[0] = substr($sendGroup[0], 0, 1);
        } elseif($sendGroup[0] == 'prep') {
            $sendGroup[0] = substr($sendGroup[0], 0, 2);
        }

        
        
        $viewed = '0';
        $push = '0';
        
        if($sendGroup[0] == 's') {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
       
            }
            
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
        } elseif($sendGroup[0] == 'pr') {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
       
            }
            
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
        } elseif($sendGroup[0] == 'k') {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
       
            }
           
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
        } elseif($sendGroup[0] == 'n') {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
       
            }
            
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
        } else {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user->id, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
            
            }
            
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
           
        }

        if(Auth::user()->type == 'a') {
            $isAdminIncluded = DB::table('notify_users')
            ->select('uid')
            ->where('nid', $nid)
            ->where('uid', Auth::user()->id)
            ->first();

            if(isset($isAdminIncluded)) {

            } else {
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => Auth::user()->id, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
            }

            
            $isTeacherAdminIncluded = DB::table('notify_users')
            ->select('uid')
            ->where('nid', $nid)
            ->where('uid', '31')
            ->first();

            if(isset($isTeacherAdminIncluded)) {

            } else {
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => '31', 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
            }
        }    
        
        
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);

        }else{
        Auth::logout();
        return redirect('/login');
        }
      }

      public function profilePicUpdater(){

        if (Auth::check() && Auth::user()->type =='a') {
            
            $students = DB::table('students')
            ->select('id','gender')
            ->get();

            foreach($students as $student){

                if($student->gender == 'm'){
                    // echo "<br > $student->id m";
                    DB::table('students')
                ->where('id', $student->id)
                ->update([
                    'profilepic' => 'profile_m.png'
                ]);

                }elseif($student->gender == 'f'){
                    // echo "<br > $student->id f";
                    DB::table('students')
                ->where('id', $student->id)
                ->update([
                    'profilepic' => 'profile_f.png'
                ]);
                }

            }

        }

      }

      public function paymentDues(Request $request){

        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        $data['page_title'] = 'Incidents';
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();

            $data['paymentdues'] = DB::table('payment_dues')
            ->select('*')
            ->orderBy('date','desc')
            ->get();

        foreach($data['paymentdues'] as $key=> $due) {
                if($due->status == 'paid'){$data['paymentdues'][$key]->color = 'info';}
                elseif($due->status == 'unpaid'){$data['paymentdues'][$key]->color = 'warning';}
                elseif($due->status == 'reminder'){$data['paymentdues'][$key]->color = 'danger';}
               
            }
        
            $data['gradesection'] = DB::table('students')->select('grade','section')->groupBy('grade','section')->orderByRaw('CAST(`grade` AS UNSIGNED) asc')->get();


        return view('apaymentdues')->with($data);
        // return view('aincidents');

        }else{
        Auth::logout();
        return redirect('/login');
        }

    }

    public function paymentDuesPost(Request $request){

         
        $data['page_title'] = 'Payments';
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        if(is_array($classArray = explode('-',$request->input('class')))){
            $grade = $classArray[0];//sanitize for spaces eg christian living
            $section = $classArray[1];
        }else{
            return redirect()->$request->server('HTTP_REFERER');
        }
        // echo $request->input('class');
        // $classArray = explode('-',$request->input('class'));
        // print_r($classArray);die();

  
        $date = $request->input('date');
        $sid    = $request->input('sid');
        $lastname   =   $request->input('lastname');
       $firstname   =   $request->input('firstname');
       $grade   =   $grade;
       $section   =   $section;
      
        // $title  =   $request->input('title');
        $description    =   $request->input('description');
        $amount   =   $request->input('amount');
        $status    =   $request->input('status');
        $inputted_by    =   $currentIdent;

// print_r($request->input());die();
// [date] =>  [sid] => [firstname] => [lastname] => [class] =>  [reported_by] => [message] =>

        DB::table('payment_dues')->insert(
            [
                'date' => $date, 
                'sid' => $sid, 
                'lastname' => $lastname, 
                'firstname' => $firstname, 
                'grade' => $grade,
                'section' => $section,
                // 'title' => $title,
                'description' => $description,
                'amount' => $amount,
                'status' => $status,
                // 'inputted_by' => $inputted_by
            ]
        );

        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);
    }else{
        // Auth::logout();
        return redirect('/login');
    }

    }


    public function paymentDuesEdit(Request $request){
        $data['page_title'] = 'Incidents';
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $currentIdent = session('currentIdent');
        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
            echo "test";
            print_r($request->input());

            // iid] => 4 [date] => 2018-06-11 [sid] => 888 [firstname] => jacob [lastname] => ledda [class] => 2-b [severity] => warning [reported_by] => Ms. Beth Jimenez [title] => Not reading [description] => not reading 

            if(is_array($classArray = explode('-',$request->input('class')))){
                $grade = $classArray[0];//sanitize for spaces eg christian living
                $section = $classArray[1];
            }else{
                return redirect()->$request->server('HTTP_REFERER');
            }

            $id = $request->input('pdid');
            $date = $request->input('date');
            $sid    = $request->input('sid');
            $lastname   =   $request->input('lastname');
           $firstname   =   $request->input('firstname');
           $grade   =   $grade;
           $section   =   $section;
          
            // $title  =   $request->input('title');
            $description    =   $request->input('description');
            $amount   =   $request->input('amount');
            $status    =   $request->input('status');
            $inputted_by    =   $currentIdent;
            

        DB::table('payment_dues')
                ->where('id', $id)
                ->update([
                    'date' => $date,
                    'sid' => $sid,
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'grade' => $grade,
                    'section' => $section,
                    'amount' => $amount,
                    'description' => $description,
                    'status' => $status
                    // 'reported_by' => $reported_by
                ]);
       
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);
            }

    }

    public function  paymentDuesDel (Request $request){
        

        if(Auth::check()){
            // echo "test"; die();
            $id = $request->input('pdid');
            if(Auth::user()->type =='a'){
            $result = DB::table('payment_dues')->where('id', '=', $id)->delete();
        }
       
        $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);

        }
        
    }
    
    public function enrollmentList(Request $request){
        $data['page_title'] = 'Enrollment List';
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();

        $data['classmates'] = DB::table('students')
        ->select('id','firstname','lastname', 'grade','section','profilepic')
        ->orderBy('grade')
        ->orderBy('lastname')
        ->get();


        return view('aenrollment')->with($data);
        }
    }
    public function enrollmentAdd(Request $request){
        $data['page_title'] = 'Enrollment';
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();


        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        $data['subjects'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        ->groupBy('subj','grade','section')
        ->orderBy('grade')
        ->orderBy('subj')
        ->get();

        return view('aenrollmentadd')->with($data);

        }

    }

    public function enrollmentAddPrimaryDetails(Request $request) {
        $allInput['page_title'] = 'Enrollment';
        $allInput['notifications'] = $this->notificationsListHeaderNav();
        $allInput['notificationsUnreadCount'] = $this->notificationsUnreadCount();


        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $allInput['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
            $allInput['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->get();

            $allInput['studentdetails'] = $request->all();

            $student_fileName = '';
            $student_mom_fileName = '';
            $student_dad_fileName = '';
            if($student_profile_pic = Input::file('primarydetails_profilepic')) {
                $student_profile_pic = Input::file('primarydetails_profilepic');
                //get file ext and create file
                $student_profile_pic_ext = Input::file('primarydetails_profilepic')->getClientOriginalExtension();
                $student_fileName = 'profileimg_'.$allInput['studentdetails']['primarydetails_studentid'].'.'.$student_profile_pic_ext;
                //move uploaded img
                $student_profile_pic->move('uploads/profile',$student_fileName);
            }
            if($student_mom_profile_pic = Input::file('parentsdetails_momprofilepic')) {
                //get mom upload img
                $student_mom_profile_pic = Input::file('parentsdetails_momprofilepic');
                //get file ext and create file
                $student_mom_profile_pic_ext = Input::file('parentsdetails_momprofilepic')->getClientOriginalExtension();
                $student_mom_fileName = 'profileimgmom_'.$allInput['studentdetails']['primarydetails_studentid'].'.'.$student_mom_profile_pic_ext;
                //move uploaded img
                $student_mom_profile_pic->move('uploads/profile',$student_mom_fileName);
            }
            if($student_dad_profile_pic = Input::file('parentsdetails_dadprofilepic')) {
                //get dad upload img
                $student_dad_profile_pic = Input::file('parentsdetails_dadprofilepic');
                //get file ext and create file
                $student_dad_profile_pic_ext = Input::file('parentsdetails_dadprofilepic')->getClientOriginalExtension();
                $student_dad_fileName = 'profileimgdad_'.$allInput['studentdetails']['primarydetails_studentid'].'.'.$student_dad_profile_pic_ext;
                //move uploaded img
                $student_dad_profile_pic->move('uploads/profile',$student_dad_fileName);
            }

            $checkStudentID = DB::table('students')
            ->select('id')
            ->where('id', $allInput['studentdetails']['primarydetails_studentid'])
            ->first();

            if($checkStudentID != null) { 
                $allInput['isStudentExist'] = "";
            } else {
                $tasksGradeSection = DB::table('tasks')
                ->select('id')
                ->where('task_grade', $allInput['studentdetails']['primarydetails_grade'])
                ->where('task_section', $allInput['studentdetails']['primarydetails_section'])
                ->get();

                foreach($tasksGradeSection as $tasks) {
                   $checkStudentHasTasks = DB::table('task_student')
                   ->select('id')
                   ->where('taskid', $tasks->id)
                   ->where('sid', $allInput['studentdetails']['primarydetails_studentid'])
                   ->first();

                   $currentTasksDetails = DB::table('task_student')
                    ->select('tid','period', 'school_year')
                    ->where('taskid', $tasks->id)
                    ->first();
                        
                    if($currentTasksDetails != null) {
                        if($checkStudentHasTasks == null) {
                            $insertTasks = DB::table('task_student')
                            ->where('taskid', $tasks->id)
                            ->where('sid', $allInput['studentdetails']['primarydetails_studentid'])
                            ->insert([
                                'sid'=>$allInput['studentdetails']['primarydetails_studentid'],
                                'taskid'=> $tasks->id,
                                'score'=>0,
                                'tid'=>$currentTasksDetails->tid,
                                'status'=>'excused',
                                'period'=>$currentTasksDetails->period,
                                'school_year'=>$currentTasksDetails->school_year
                            ]);
                        }
                    }
                }

                $addStudentInfo = DB::table('students')->insert(
                        [
                            'id'=>$allInput['studentdetails']['primarydetails_studentid'] == null ? 'NA' : $allInput['studentdetails']['primarydetails_studentid'],
                            'firstname'=>$allInput['studentdetails']['primarydetails_firstname'] == null ? 'NA' : $allInput['studentdetails']['primarydetails_firstname'],
                            'lastname'=>$allInput['studentdetails']['primarydetails_lastname'] == null ? 'NA' : $allInput['studentdetails']['primarydetails_lastname'],
                            'grade'=>$allInput['studentdetails']['primarydetails_grade'] == null ? '0' : $allInput['studentdetails']['primarydetails_grade'],
                            'section'=>$allInput['studentdetails']['primarydetails_section'] == null ? '0' : $allInput['studentdetails']['primarydetails_section'],
                            'birthdate'=>$allInput['studentdetails']['primarydetails_dob'] == null ? '0000-00-00' : $allInput['studentdetails']['primarydetails_dob'],
                            'profilepic'=>$student_fileName == null ? 'profile.png' : $student_fileName,
                            'gender'=>$allInput['studentdetails']['primarydetails_gender'] == null ? 'NA' : $allInput['studentdetails']['primarydetails_gender'],
                            's_cellno'=>$allInput['studentdetails']['contactdetails_mobile'] == null ? '0' : $allInput['studentdetails']['contactdetails_mobile'],
                            's_landline'=>$allInput['studentdetails']['contactdetails_landline'] == null ? '0' : $allInput['studentdetails']['contactdetails_landline'],
                            's_email'=>$allInput['studentdetails']['contactdetails_email'] == null ? 'NA' : $allInput['studentdetails']['contactdetails_email'],
                            's_address'=>$allInput['studentdetails']['contactdetails_address'] == null ? 'NA' : $allInput['studentdetails']['contactdetails_address'],
                            's_mom_id'=>$allInput['studentdetails']['parentsdetails_momid'] == null ? 0 : $allInput['studentdetails']['parentsdetails_momid'],
                            's_momname'=>$allInput['studentdetails']['parentsdetails_momname'] == null ? 'NA' : $allInput['studentdetails']['parentsdetails_momname'],
                            's_mom_profilepic'=>$student_mom_fileName == null ? 'profile_f.png' : $student_mom_fileName,
                            's_momofficetel'=>$allInput['studentdetails']['parentsdetails_momofficetel'] == null ? '0' : $allInput['studentdetails']['parentsdetails_momofficetel'],
                            's_momcellno'=>$allInput['studentdetails']['parentsdetails_mommobile'] == null ? '0' : $allInput['studentdetails']['parentsdetails_mommobile'],
                            's_momemail'=>$allInput['studentdetails']['parentsdetails_momemail'] == null ? 'NA' : $allInput['studentdetails']['parentsdetails_momemail'],
                            's_momofcaddress'=>$allInput['studentdetails']['parentsdetails_momofficeaddress'] == null ? 'NA' : $allInput['studentdetails']['parentsdetails_momofficeaddress'],
                            's_dadname'=>$allInput['studentdetails']['parentsdetails_dadname'] == null ? 'NA' : $allInput['studentdetails']['parentsdetails_dadname'],
                            's_dad_id'=>$allInput['studentdetails']['parentsdetails_dadid'] == null ? 0 : $allInput['studentdetails']['parentsdetails_dadid'],
                            's_dad_profilepic'=>$student_dad_fileName == null ? 'profile_m.png' : $student_dad_fileName,
                            's_dadofficetel'=>$allInput['studentdetails']['parentsdetails_dadofficetel'] == null ? '0' : $allInput['studentdetails']['parentsdetails_dadofficetel'],
                            's_dadcellno'=>$allInput['studentdetails']['parentsdetails_dadmobile'] == null ? '0' : $allInput['studentdetails']['parentsdetails_dadmobile'],
                            's_dademail'=>$allInput['studentdetails']['parentsdetails_dademail'] == null ? 'NA' : $allInput['studentdetails']['parentsdetails_dademail'],
                            's_dadofcaddress'=>$allInput['studentdetails']['parentsdetails_dadofficeaddress'] == null ? 'NA' : $allInput['studentdetails']['parentsdetails_dadofficeaddress'],
                            's_guardianname'=>$allInput['studentdetails']['otherdetails_guardianname'] == null ? 'NA' : $allInput['studentdetails']['otherdetails_guardianname'] ,
                            's_guardiantel'=>$allInput['studentdetails']['otherdetails_guardiantel'] == null ? '0' : $allInput['studentdetails']['otherdetails_guardiantel'],
                            's_guardiancellno'=>$allInput['studentdetails']['otherdetails_guardianmobile'] == null ? '0' : $allInput['studentdetails']['otherdetails_guardianmobile'],
                            's_guardianrelation'=>$allInput['studentdetails']['otherdetails_guardianrelation'] == null ? 'NA' : $allInput['studentdetails']['otherdetails_guardianrelation'],
                            's_guardianemail'=>$allInput['studentdetails']['otherdetails_guardianemail'] == null ? 'NA' : $allInput['studentdetails']['otherdetails_guardianemail'],
                            's_prefcontact'=>$allInput['studentdetails']['contactdetails_preferredcontact'] == null ? 'mom' : $allInput['studentdetails']['contactdetails_preferredcontact']
                        ]
                    );

                    if($addStudentInfo){
                        $allInput['isStudentExist'] = "Student successfully added";
                    }elseif(!$addStudentInfo){
                        $allInput['isStudentExist'] = "";
                    };
                }
        
            

        //s_mom_id,s_mom_profilepic

        $allInput['student_all_list'] = DB::table('students')->orderBy('id', 'desc')->get();


        if($allInput['isStudentExist'] != "") {
            return view('aenrollmentstudentlist')->with($allInput);
        }
        else {
            return view('aenrollmentstudentlist')->with($allInput);
        }

        }
        
    }

    public function enrollmentEdit(Request $request, $sid){
        $data['page_title'] = 'Edit Section **Please click on the information';
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();


        if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

        $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
        $data['subjects'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        ->get();

        $data['senrollmentinfo'] = DB::table('students')
        ->select('*')
        ->where('id', $sid)
        ->first();
        
        // return response()->json($data);
        return view('aenrollmentedit')->with($data);

        }

    }

    public function postEditedStudent(Request $request){
        
        $data['all_data'] = $request->all();
        
        $getImg = DB::table('students')
        ->select('profilepic', 's_mom_profilepic', 's_dad_profilepic')
        ->where('id', $data['all_data']['student_id'])
        ->first();
        $isImgChanged = false;
        //get student upload img
        $student_fileName = $getImg->profilepic;
        $student_mom_fileName = $getImg->s_mom_profilepic;
        $student_dad_fileName = $getImg->s_dad_profilepic;
        if($student_profile_pic = Input::file('student_student_profile_pic')) {
            $student_profile_pic = Input::file('student_student_profile_pic');
            //get file ext and create file
            $student_profile_pic_ext = Input::file('student_student_profile_pic')->getClientOriginalExtension();
            $student_fileName = 'profileimg_'.$data['all_data']['student_id'].'.'.$student_profile_pic_ext;
            //move uploaded img
            $student_profile_pic->move('uploads/profile',$student_fileName);
            $isImgChanged = true;
        }
        if($student_mom_profile_pic = Input::file('student_mprofile_pic')) {
            //get mom upload img
            $student_mom_profile_pic = Input::file('student_mprofile_pic');
            //get file ext and create file
            $student_mom_profile_pic_ext = Input::file('student_mprofile_pic')->getClientOriginalExtension();
            $student_mom_fileName = 'profileimgmom_'.$data['all_data']['student_id'].'.'.$student_mom_profile_pic_ext;
            //move uploaded img
            $student_mom_profile_pic->move('uploads/profile',$student_mom_fileName);
            $isImgChanged = true;
        }
        if($student_dad_profile_pic = Input::file('student_dprofile_pic')) {
            //get dad upload img
            $student_dad_profile_pic = Input::file('student_dprofile_pic');
            //get file ext and create file
            $student_dad_profile_pic_ext = Input::file('student_dprofile_pic')->getClientOriginalExtension();
            $student_dad_fileName = 'profileimgdad_'.$data['all_data']['student_id'].'.'.$student_dad_profile_pic_ext;
            //move uploaded img
            $student_dad_profile_pic->move('uploads/profile',$student_dad_fileName);
            $isImgChanged = true;
        }

        $updatingStudent = DB::table('students')
        ->where('id', $data['all_data']['student_id'])
        ->update([
            'firstname'=>$data['all_data']['student_firstname'],
            'lastname'=>$data['all_data']['student_lastname'],
            'grade'=>$data['all_data']['student_grade'],
            'section'=>$data['all_data']['student_section'],
            'birthdate'=>$data['all_data']['student_birthdate'],
            'gender'=>$data['all_data']['student_gender'],
            's_cellno'=>$data['all_data']['student_mobile_no'],
            's_landline'=>$data['all_data']['student_landline'],
            //'s_email'=>$data['all_data']['student_id'],
            's_address'=>$data['all_data']['student_address'],
            's_mom_id'=>$data['all_data']['student_mid'],
            's_momname'=>$data['all_data']['student_mname'],
            's_mom_profilepic'=>$student_mom_fileName,
            's_momofficetel'=>$data['all_data']['student_moffice_tel'],
            's_momcellno'=>$data['all_data']['student_mmobile_no'],
            's_momemail'=>$data['all_data']['student_memail'],
            's_momofcaddress'=>$data['all_data']['student_moffice_address'],
            's_dadname'=>$data['all_data']['student_dname'],
            's_dad_id'=>$data['all_data']['student_did'],
            's_dad_profilepic'=>$student_dad_fileName,
            's_dadofficetel'=>$data['all_data']['student_doffice_tel'],
            's_dadcellno'=>$data['all_data']['student_dmobile_no'],
            's_dademail'=>$data['all_data']['student_demail'],
            's_dadofcaddress'=>$data['all_data']['student_doffice_address'],
            's_guardianname'=>$data['all_data']['student_gname'],
            's_guardiantel'=>$data['all_data']['student_gtel_no'],
            's_guardiancellno'=>$data['all_data']['student_gmobile_no'],
            's_guardianrelation'=>$data['all_data']['student_grelation'],
            's_guardianemail'=>$data['all_data']['student_gemail'],
            's_prefcontact'=>$data['all_data']['student_preferred_contact'],
            'profilepic'=>$student_fileName

        ]);

        if($updatingStudent || $isImgChanged == true){
            $sucessfullyedited = "Student successfully edited";
        }elseif(!$updatingStudent){
            $notsuccess = "Student was not successfully edited or please change something.";
        };

        if(isset($sucessfullyedited)) {
            return redirect()->back()->with('message', $sucessfullyedited);
        }
        else {
            return redirect()->back()->with('error', $notsuccess);
        }

    }

    public function viewEnrolledStudents(Request $request) {
        $allInput['page_title'] = 'View Students';
        $allInput['notifications'] = $this->notificationsListHeaderNav();
        $allInput['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        if (Auth::check()) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $allInput['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        //addded by elmer
        
        $allInput['students_list_groupby_grade'] = DB::table('students')
        ->select('grade', 'section')
        ->groupBy('grade', 'section')
        ->get();

        $allInput['students_list'] = DB::table('students')
        ->select('id', 'firstname', 'lastname', 'grade', 'section')
        ->orderBy('lastname')
        ->get();
            
        return view('aviewenrolledstudents')->with($allInput);

        }
    }

    public function DeleteEnrolledStudents(Request $request, $sid=0) {
        $allInput['page_title'] = 'View Students';
        $allInput['notifications'] = $this->notificationsListHeaderNav();
        $allInput['notificationsUnreadCount'] = $this->notificationsUnreadCount();

        if (Auth::check()) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            $allInput['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

        //addded by elmer

        $student_info = DB::table('students')
        ->select('profilepic', 's_mom_profilepic', 's_dad_profilepic')
        ->where('id', $sid)
        ->first();

        $image_path = "/uploads/profile/".$student_info->profilepic;

        if($student_info->profilepic == 'profile_f.png' || $student_info->profilepic == 'profile_m.png' || $student_info->profilepic == 'profile.png') {

        } else {
            $image_path = "/uploads/profile/".$student_info->profilepic;
            File::delete($image_path);
        }

        if($student_info->s_mom_profilepic == 'profile_f.png' || $student_info->s_mom_profilepic == 'profile_m.png' || $student_info->s_mom_profilepic == 'profile.png') {

        } else {
            $image_path = "/uploads/profile/".$student_info->s_mom_profilepic;
            File::delete($image_path);
        }

        if($student_info->s_dad_profilepic == 'profile_f.png' || $student_info->s_dad_profilepic == 'profile_m.png' || $student_info->s_dad_profilepic == 'profile.png') {

        } else {
            $image_path = "/uploads/profile/".$student_info->s_dad_profilepic;
            File::delete($image_path);
        }

        $allInput['deleteStudent'] = DB::table('students')
        ->where('id', $sid)
        ->delete();

        if($allInput['deleteStudent']){
            $sucessfullyedited = "Student successfully deleted.";
        }elseif(!$allInput['deleteStudent']){
            $notsuccess = "Student was not successfully deleted.";
        };

        if(isset($sucessfullyedited)) {
            return redirect()->back()->with('message', $sucessfullyedited);
        }
        else {
            return redirect()->back()->with('error', $notsuccess);
        }
        
        // return view('aviewenrolledstudents')->with($allInput);

        }
    }

    public function showStudentList(Request $request,$subj=0,$grade=0,$section=0,$id=0){
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

           


            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
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
            ->groupBy('type_name','task_title')
            ->get();

            $data['student_task_list'] = DB::table('tasks')
            ->select('id', 'task_total_points','task_type', 'task_title', 'period', 'school_year')
            ->where('id', $id)
            ->first();

            if(!isset($data['student_task_list'])) {
                return redirect('/agrading/list/'.$subj.'/'.$grade.'-'.$section)->with('error', 'Task deleted. Please contact admin.');
            }

            $data['student_existing_score'] = DB::table('task_student')
            ->join('students', 'task_student.sid','=','students.id')
            ->select('task_student.score', 'task_student.sid', 'students.firstname', 'students.lastname', 'task_student.status')
            ->where('taskid', $id)
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


            // echo json_encode($data);
            return view('agradingexist')->with($data);

        }
      }

      public function gradingList(Request $request,$subj=0,$grade=0,$section=0){
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
                // print_r($assignmentscore->score);die();
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
                // print_r($assignmentscore->score);die();
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
                // print_r($assignmentscore->score);die();
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
                // print_r($assignmentscore->score);die();
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


            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();

            //added by elmer
            $data['tasktype_names'] = DB::table('tasks')
            ->select('id', 'task_type', 'task_title', 'task_grade', 'task_section')
            ->where('task_grade','=',$grade)
            ->where('task_section','=', $section)
            ->where('task_subject','=', $subj)
            ->get();
            
            $data['task_type_type_name'] = DB::table('task_type')
            ->select('type_name', 'task_title')
            ->groupBy('type_name', 'task_title')
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

                // echo json_encode($data);
                return view('agradinglist')->with($data);
            

        }
      }

      public function qualitativeStudentScore(Request $request,$subj=0,$grade=0,$section=0,$id=0, $period=0, $schoolYear='') {
        $subj = str_replace('_',' ',trim($subj));
        $data['subject'] = $subj;
        $data['grade']= $grade;
        $data['section']= $section;
        $data['period_clicked'] = $period;
        $data['schoolYear_clicked'] = $schoolYear;
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

       


        $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();

        //added by elmer

        $data['tasktype_names'] = DB::table('tasks')
        ->select('id', 'task_type', 'task_title', 'task_subject', 'task_grade', 'task_section')
        ->where('task_grade','=',$grade)
        ->where('task_section','=', $section)
        ->where('task_subject','=', $subj)
        ->get();

        // $data['tasktype_names'] = DB::table('tasks')
        // ->select('id', 'task_type', 'task_title', 'task_subject', 'task_grade', 'task_section')
        // ->orderBy('task_grade')
        // ->get();
        
        $data['task_type_type_name'] = DB::table('task_type')
        ->select('id', 'type_name', 'task_title')
        ->where('subject', $subj)
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

        $data['qualitative_student_name'] = DB::table('students')
        ->select('firstname','lastname','id')
        ->where('id', $id)
        ->first();

        $data['qualitative_student_scores_exist'] = DB::table('qualitative_scores')
        ->where('sid', $id)
        ->where('period', $period)
        ->where('school_year', $schoolYear)
        ->first();

        if($data['qualitative_student_scores_exist'] != '' || $data['qualitative_student_scores_exist'] != NULL) {

            $data['qualitative_types_behavior'] = DB::table('qualitative_types')
            ->leftJoin('qualitative_scores','qualitative_types.id','=','qualitative_scores.qid')
            ->select('qualitative_types.id','qualitative_types.type_name','qualitative_scores.score')
            ->where('qualitative_types.category','Behavior_in_school')
            ->where('qualitative_scores.sid',$id)
            ->where('qualitative_scores.period', $period)
            ->where('school_year', $schoolYear)
            ->get();

            $data['qualitative_types_quality'] = DB::table('qualitative_types')
            ->leftJoin('qualitative_scores','qualitative_types.id','=','qualitative_scores.qid')
            ->select('qualitative_types.id','qualitative_types.type_name','qualitative_scores.score')
            ->where('qualitative_types.category','Quality_worker')
            ->where('qualitative_scores.sid',$id)
            ->where('qualitative_scores.period', $period)
            ->where('school_year', $schoolYear)
            ->get();

            $data['qualitative_types_collaborative'] = DB::table('qualitative_types')
            ->leftJoin('qualitative_scores','qualitative_types.id','=','qualitative_scores.qid')
            ->select('qualitative_types.id','qualitative_types.type_name','qualitative_scores.score')
            ->where('qualitative_types.category','Collaborative_peer')
            ->where('qualitative_scores.sid',$id)
            ->where('qualitative_scores.period', $period)
            ->where('school_year', $schoolYear)
            ->get();

            $data['qualitative_types_self'] = DB::table('qualitative_types')
            ->leftJoin('qualitative_scores','qualitative_types.id','=','qualitative_scores.qid')
            ->select('qualitative_types.id','qualitative_types.type_name','qualitative_scores.score')
            ->where('qualitative_types.category','Self_directed_learner')
            ->where('qualitative_scores.sid',$id)
            ->where('qualitative_scores.period', $period)
            ->where('school_year', $schoolYear)
            ->get();

            // return response()->json($data);
            return view('agradinglist')->with($data);
        }
        else {
        // echo json_encode($data);
            return view('agradinglist')->with($data);
        }

    }

}

public function addStudentScore(Request $request) {
        
    $inputData['task_name'] = $request->input('task_name');
    $inputData['task_score'] = $request->input('task_score');
    $inputData['task_teacher_id'] = $request->input('task_teacher_id');
    $inputData['grade_clicked'] = $request->input('grade_view');
    $inputData['section_clicked'] = $request->input('section_view');
    
    //Getting period and year of current tasks
    $inputData['task_period_year'] = DB::table('tasks')
    ->select('period','school_year')
    ->where('id',$inputData['task_name'])
    ->first();
    
    foreach($inputData['task_score'] as $key => $val) {
        $checkResult = DB::table('task_student')
        ->select('score')
        ->where('sid',$val['ID'])
        ->where('taskid',$inputData['task_name'])
        ->first();
        
        if($checkResult == NULL || $checkResult == '') {

            $addInputData = DB::table('task_student')->insert(
                [
                    'sid'=>$val['ID'],
                    'taskid'=>$inputData['task_name'],
                    'score'=>$val['score'],
                    'status'=>$val['status'],
                    'period'=>$inputData['task_period_year']->period,
                    'school_year'=>$inputData['task_period_year']->school_year
                ]
            );
            if($addInputData){
                $sucessfullyedited = "Student successfully added";
            }elseif(!$addInputData){
                $notsuccess = "Student not successfully added";
            };
            
        } else {
            if($val['score'] == NULL || $val['score'] == '' || $val['status'] == NULL || $val['status'] == '') {
                $val['score'] = 0;
                $val['status'] = 'none';
            }
            $addInputData = DB::table('task_student')
            ->where('sid',$val['ID'])
            ->where('taskid',$inputData['task_name'])
            ->update([
                'tid'=>null,
                'score'=>$val['score'],
                'status'=>$val['status']
                ]);
            
            if($addInputData){
                $sucessfullyedited = "Student successfully edited";
            }elseif(!$addInputData){
                $notsuccess = "Please check all input details";
            };
            
            
        };
    };

    
    /***
     * START OF COMPUTING FINAL GRADE
     */

    $data['get_final_grade_list'] = TeacherFetchJSON::finalGradeListFromGradeSection($inputData['grade_clicked'], $inputData['section_clicked']);

    
    /**
     * END OF COMPUTING FINAL GRADE
     */

    foreach($data['get_final_grade_list']['final_grade'] as $studentid=>$subject_list) {
        foreach($subject_list as $subject=>$val) {
            for($i=1; $i<=4; $i++) {
                $check_if_data_exists = DB::table('final_grade_list')
                ->where('sid', $studentid)
                ->where('subject', $subject)
                ->where('period', $i)
                ->first();

                if(isset($check_if_data_exists)) {
                    //UPDATE
                    $update_final_grade = DB::table('final_grade_list')
                    ->where('sid', $studentid)
                    ->where('subject', $subject)
                    ->where('period', $i)
                    ->update([
                        'score'=>$val[$i],
                        'transmuted'=>TeacherFetchJSON::transmuteGradesHere($val[$i])
                    ]);

                } else {
                    //INSERT
                    $insert_final_grade = DB::table('final_grade_list')
                    ->insert([
                        'sid'=>$studentid,
                        'subject'=>$subject,
                        'grade'=>$inputData['grade_clicked'],
                        'section'=>$inputData['section_clicked'],
                        'score'=>$val[$i],
                        'transmuted'=>TeacherFetchJSON::transmuteGradesHere($val[$i]),
                        'period'=>$i,
                        'school_year'=>'2019-2020'
                    ]);
                }
            }
        }
    }


        if(isset($sucessfullyedited)) {
            return redirect()->back()->with('message', $sucessfullyedited);
        }
        else {
            return redirect()->back()->with('error', $notsuccess);
        }

}

public function addTeacherTask(Request $request) {
    $data['task_type_name'] = $request->input('task_type_name');
    $data['task_title'] = $request->input('task_title');
    $data['task_grade'] = $request->input('task_grade');
    $data['task_section'] = $request->input('task_section');
    $data['task_subject'] = $request->input('task_subject');
    $data['task_teacher_id'] = $request->input('task_teacher_id');
    $data['task_total_points'] = $request->input('task_total_points');
    $data['task_period'] = $request->input('task_period');
    $data['task_school_year'] = $request->input('task_school_year');

    $addTask = DB::table('tasks')->insertGetId(
        [
            'task_type'=>$data['task_type_name'],
            'task_grade'=>$data['task_grade'],
            'task_section'=>$data['task_section'],
            'task_subject'=>$data['task_subject'],
            'task_title'=>$data['task_title'],
            'task_total_points'=>$data['task_total_points'],
            'period'=>$data['task_period'],
            'school_year'=>$data['task_school_year']

        ]
    );

    $data['new_task'] = DB::table('tasks')->where('id',$addTask)->first();

    if($data['new_task'] == NULL || $data['new_task'] == '') {
        return redirect()->back()->with('error', 'Please input all details.');
    }
    else {
        return redirect()->back()->with('message', 'Task Added!');
    }
    
}

public function viewFinalGradeFromSection(Request $request, $grade=NULL, $section=NULL, $period=NULL) {

    $data['grade_clicked'] = $grade;
    $data['section_clicked'] = $section;
    $data['period_clicked'] = $period;


    $data['notifications'] = $this->notificationsListHeaderNav();
    $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            //end check is session is set

    $data['page_title'] = 'Final Grades';
    $currentIdent = $request->session()->get('currentIdent','default');
            $data['user'] = Auth::user();
            
    $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();

    $data['subjects'] = DB::table('teacher_subj')
    ->select('subj','grade','section')
    ->groupBy('subj','grade','section')
    ->orderBy('grade')
    ->orderBy('subj')
    ->get();

    $data['grade_section'] = DB::table('teacher_subj')
    ->select('grade','section')
    ->groupBy('grade','section')
    ->get();

    //added by elmer
    
    $student_name = DB::table('students')
    ->select('id','firstname', 'lastname')
    ->where('grade', $grade)
    ->where('section', $section)
    ->orderBy('lastname')
    ->get();

    $data['students_subjects'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->groupBy('subject')
    ->get();
    

    $period_list = array($period);
    $data['period_list'] = $period_list;
    
    $task_type_name = [];
    foreach($data['students_subjects'] as $subj) {
        $task_type_new = DB::table('task_type')
        ->select('type_name','task_title','weight')
        ->where('subject', $subj->subject)
        ->first();

        if(isset($task_type_new)) {
            $task_type_new_2 = DB::table('task_type')
            ->select('type_name','task_title','weight', 'subject')
            ->where('subject', $subj->subject)
            ->get();
            
            foreach($task_type_new_2 as $val) {
                array_push($task_type_name, array(
                    'weight'=>$val->weight,
                    'type_name'=>$val->type_name,
                    'task_title'=>$val->task_title,
                    'subject'=>$val->subject
                ));
            }
            
        }
        
    }

    
    // $task_type_name = DB::table('task_type')
    // ->select('type_name','task_title','weight', 'subject')
    // ->get();

    $tasks_score_list = DB::table('tasks')
    ->leftJoin('task_student','tasks.id', '=', 'task_student.taskid')
    ->where('tasks.school_year', '2019-2020')
    ->whereNotIn('task_student.status',['excused'])
    ->select('task_student.score','tasks.task_type','tasks.task_total_points','tasks.task_subject', 'task_student.sid', 'task_student.period')
    ->orderBy('tasks.period')
    ->get();

    
    foreach($student_name as $student) {
        
        foreach($data['students_subjects'] as $subject) {

            foreach($task_type_name as $task_type) { 
                      
                $weight = $task_type['weight'];
                $totalScore = $task_type['type_name'];
                $totalPoints = $task_type['task_title'];
                $$totalScore = 0;
                $$totalPoints = 0;
                
                foreach($period_list as $period) {
                    foreach($tasks_score_list as $score) {
                        //filter all tasks
                        if($student->id == $score->sid) {
                            if($subject->subject == $score->task_subject) {
                                
                                    if($task_type['type_name'] == $score->task_type){ 

                                        if($period == $score->period) {

                                        $$totalScore = $$totalScore + $score->score;
                                        $$totalPoints = $$totalPoints + $score->task_total_points;
                                        
                                    }
                                    
                                }
                            
                            }
                        }


                    }
                    if($$totalPoints == 0) {
                        $compute_scores = 0;
                    } else {
                        $compute_scores = $$totalScore/$$totalPoints;
                    }
                    $compute_score_list = round(($compute_scores * $weight),2);

                    $data['task_score_list'][$student->id][$subject->subject][$period][$task_type['type_name']] = $compute_score_list;

                    $$totalScore = 0;
                    $$totalPoints = 0;
                }
                

            }

        }
        
    }

    
    $arr = [];
    foreach($student_name as $student) {
        
        foreach($data['students_subjects'] as $subject_val) {
            
                foreach($period_list as $period_val) {
                
                    foreach($data['task_score_list'] as $studentid=>$subject) {
        
                        foreach($subject as $key_subj=>$period) {
                            
                            foreach($period as $key_period=>$kval) {

                                if($student->id == $studentid) {
                                    if($subject_val->subject == $key_subj) {
                                        if($period_val == $key_period) {
                                            
                                            foreach($task_type_name as $task_type) {
                                                if($task_type['subject'] == $key_subj) {
                                                    array_push($arr, $kval[$task_type['type_name']]);
                                                }
                                                
                                                
                                            }
                                            $data['final_scores'][$student->lastname.', '.$student->firstname][$key_subj] = round(array_sum($arr),2);
                                            
                                        }
                                    }
                                }
                                //reset array
                                $arr = [];
                    
                            }

                            
        
                        }
                        
                    }
                
                }

                
        }
    }
    
    
    
    // return response()->json($data);
    return view('agradingview')->with($data);
}else{
    Auth::logout();
    return redirect('/login');
}


}

public function viewFinalGradeFromSection2(Request $request, $grade=NULL, $section=NULL, $period=NULL) {

    $data['grade_clicked'] = $grade;
    $data['section_clicked'] = $section;
    $data['period_clicked'] = $period;


    $data['notifications'] = $this->notificationsListHeaderNav();
    $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            //end check is session is set

    $data['page_title'] = 'Final Grades';
    $currentIdent = $request->session()->get('currentIdent','default');
            $data['user'] = Auth::user();
            
    $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();

    $data['subjects'] = DB::table('teacher_subj')
    ->select('subj','grade','section')
    ->groupBy('subj','grade','section')
    ->orderBy('grade')
    ->orderBy('subj')
    ->get();

    $data['grade_section'] = DB::table('teacher_subj')
    ->select('grade','section')
    ->groupBy('grade','section')
    ->get();

    //added by elmer

    $data['student_id_list'] = DB::table('students')
    ->select('id', 'firstname', 'lastname')
    ->where('grade', $grade)
    ->where('section', $section)
    ->orderBy('lastname')
    ->get();

    $data['students_subjects_fixed_count'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->whereNotIn('subject',['homeroom', 'language', 'reading', 'music', 'arts', 'pe', 'health', 'penmanship'])
    ->count();

    $data['students_subjects_for_general_avg'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->whereNotIn('subject',['homeroom', 'language', 'reading', 'music', 'arts', 'pe', 'health', 'penmanship'])
    ->orderBy('rankorder')
    ->get();

    $data['subject_list_grade_section'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->whereNotIn('subject',['homeroom'])
    ->orderBy('rankorder')
    ->get();

    $data['final_grade_list_from_new_db'] = DB::table('final_grade_list')
    ->where('grade', $grade)
    ->where('section', $section)
    ->where('period', $period)
    ->whereNotIn('subject',['homeroom'])
    ->orderBy('subject')
    ->get();

    foreach($data['student_id_list'] as $student_id_list) {
        foreach($data['subject_list_grade_section'] as $subject_list_grade_section) {
           $data['student_with_subject_list'][$student_id_list->firstname.' '.$student_id_list->lastname][$subject_list_grade_section->subject] = '';
           
        }
    }
   

    foreach($data['student_id_list'] as $student_id_list) {
        foreach($data['subject_list_grade_section'] as $subject_name) {
            foreach($data['final_grade_list_from_new_db'] as $final_scores) {
                if($student_id_list->id == $final_scores->sid && $subject_name->subject == $final_scores->subject) {
                        $data['student_with_subject_list'][$student_id_list->firstname.' '.$student_id_list->lastname][$final_scores->subject] = TeacherFetchJSON::transmuteGradesHere($final_scores->score);
                }
                
            }
        }
    }

    /**
     * COMPUTE GENERAL AVERAGE
     */

    if($data['students_subjects_fixed_count'] != 0) {

        foreach($data['student_id_list'] as $student_id_list) {
            foreach($data['students_subjects_for_general_avg'] as $subject_name_gen_avg) {
                $data['general_avg_per_period'][$student_id_list->firstname.' '.$student_id_list->lastname][$subject_name_gen_avg->subject] = [];
            }
            $data['general_avg_list'][$student_id_list->firstname.' '.$student_id_list->lastname]= [];
            $data['general_list_value'][$student_id_list->firstname.' '.$student_id_list->lastname]= 0;
        }

        foreach($data['student_id_list'] as $student_id_list) {
            foreach($data['students_subjects_for_general_avg'] as $subject_name_gen_avg) {
                foreach($data['final_grade_list_from_new_db'] as $final_scores) {
                    if($student_id_list->id == $final_scores->sid && $subject_name_gen_avg->subject == $final_scores->subject) {
                        array_push($data['general_avg_per_period'][$student_id_list->firstname.' '.$student_id_list->lastname][$subject_name_gen_avg->subject], $final_scores->score);
                    }
                }
            }
        }

        foreach($data['student_id_list'] as $student_id_list) {
            foreach($data['students_subjects_for_general_avg'] as $subject_name_gen_avg) {
                if($period == 4){
                    array_push($data['general_avg_list'][$student_id_list->firstname.' '.$student_id_list->lastname], TeacherFetchJSON::transmuteGradesSpecial($data['general_avg_per_period'][$student_id_list->firstname.' '.$student_id_list->lastname][$subject_name_gen_avg->subject][0]));

                }else{

                    array_push($data['general_avg_list'][$student_id_list->firstname.' '.$student_id_list->lastname], TeacherFetchJSON::transmuteGradesHere($data['general_avg_per_period'][$student_id_list->firstname.' '.$student_id_list->lastname][$subject_name_gen_avg->subject][0]));
                }
            }
        }

        foreach($data['student_id_list'] as $student_id_list) {
            $data['general_list_value'][$student_id_list->firstname.' '.$student_id_list->lastname] = round((array_sum($data['general_avg_list'][$student_id_list->firstname.' '.$student_id_list->lastname])/$data['students_subjects_fixed_count']),2);
        }
    }
   
    /**
     * END OF COMPUTING GENERAL AVERAGE
     */
    
    // return response()->json($data);
    return view('agradingview2')->with($data);
}else{
    Auth::logout();
    return redirect('/login');
}


}

public function viewGradesInPdf(Request $request, $grade='',$section='') {
    $data['grade_clicked'] = $grade;
    $data['section_clicked'] = $section;

    $data['notifications'] = $this->notificationsListHeaderNav();
    $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            //end check is session is set

    $data['page_title'] = 'Review Grades for PDF';
    $currentIdent = $request->session()->get('currentIdent','default');
            $data['user'] = Auth::user();
            
    $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();

    // $data['subjects'] = DB::table('teacher_subj')
    // ->select('subj','grade','section')
    // ->where('t_id', $data['teacheruser']->id)
    // ->get();
    $data['subjects'] = DB::table('teacher_subj')
    ->select('subj','grade','section')
    ->groupBy('subj','grade','section')
    ->orderBy('grade')
    ->orderBy('subj')
    ->get();

    $data['grade_section'] = DB::table('teacher_subj')
    ->select('grade','section')
    ->where('t_id', $data['teacheruser']->id)
    ->groupBy('grade','section')
    ->get();

    //added by elmer
    
    
        $student_name = DB::table('students')
        ->select('students.id','students.firstname', 'students.lastname', 'students_lrn.lrn', 'students_lrn.age_sy')
        ->join('students_lrn', 'students.id', '=', 'students_lrn.sid')
        ->where('grade', $grade)
        ->where('section', $section)
        ->orderBy('lastname')
        ->get();

        $data['student_list_by_user'] = DB::table('students')
        ->where('grade', $grade)
        ->where('section', $section)
        ->orderBy('lastname')
        ->get();
        
        $data['students_subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $grade)
        ->where('section', $section)
        ->whereNotIn('subject',['homeroom'])
        ->orderBy('rankorder')
        ->get();

        $period_list = array('1','2','3','4');
        $data['period_list'] = $period_list;

        $data['get_all_taskid_as_list_checker'] = DB::table('tasks')
        ->where('task_grade', $grade)
        ->where('task_section', $section)
        ->first();

        $checker_of_final_grade= DB::table('final_grade_list')
        ->where('grade', $grade)
        ->where('section', $section)
        ->whereNotIn('subject',['homeroom'])
        ->orderBy('subject')
        ->orderBy('period')
        ->first();

        if(!isset($checker_of_final_grade)) {
            return redirect('/afetch/final/view/grades')->with('error', 'No grades exists. Please contact admin.');
        }

        foreach($student_name as $student) {
            $data['student_name_list'][$student->lastname.', '.$student->firstname] = $student->id;
        }
         
        /***
         * COMPUTE BIRHDAY
         */
        $data['fixed_year'] = date('Y');

        // foreach($data['student_list_by_user'] as $student_user) {
        //     if(isset($student_user->birthdate)) {
        //         $data['age_list'][$student_user->id] = $data['fixed_year'] - date('Y', strtotime($student_user->birthdate));
        //     } else {
        //         $data['age_list'][$student_user->id] = 0;
        //     }
        // }

        foreach($student_name as $student){
            if($student->age_sy == null){
                $studage = '';
            }else{
                $studage = $student->age_sy;
            }
            $data['age_list'][$student->id] = $studage;
        }

        foreach($student_name as $student){
            $data['lrn_list'][$student->id] = $student->lrn;
        }
         /***
          * END COMPUTE BIRTHDAY
          */



        /**
         * START OF COMPUTING FINAL GRADE
         */
        // original elmer computation of final grades
        // $data['list_of_final_grade'] = TeacherFetchJSON::finalGradeListFromGradeSection($grade, $section);


        // final grades from prepopulated final_grades table
        // $data['list_of_final_grade']['final_grade'] = 
        // $data['list_of_final_grade']['final_grade'][$student_user->id]

        // echo "<pre>";print_r($data['students_subjects']);echo "</pre>";die();
/**
 * Gino fix start
 */
// for final_grade
        foreach ($data['student_list_by_user'] as $student_user){
            foreach ($data['students_subjects'] as $student_subject){
                for($i = 1; $i<=4; $i++) {
                $result = DB::table('final_grade_list')
                ->where('subject', $student_subject->subject)
                ->where('sid', $student_user->id)
                ->where('period', $i)
                ->get();

                $data['list_of_final_grade']['final_grade'][$student_user->id][$student_subject->subject][$i] = $result[0]->transmuted;

                // echo "<pre>";print_r($data['list_of_final_grade']);echo "</pre>";

                }
               
            }
       
        }
        // echo "<pre>";print_r($data['student_list_by_user']);echo "</pre>";die();

// for general_avg
    // foreach($data['student_list_per_grade'] as $student_list) {
    //     for($i=1; $i<=4; $i++) {
    //     foreach($data['students_subjects_selected'] as $subject_list) {
    //         array_push($data['array_general_average_the_rest_of_subject'][$student_list->id][$i], $data['subject_scores_per_period'][$student_list->id][$i][$subject_list->subject]);
    //     }
    //     }
    // }
$test =[];

    foreach ($data['student_list_by_user'] as $student_user){
        // foreach ($data['students_subjects'] as $student_subject){
            for($i = 1; $i<=4; $i++) {
                $avgs = [];
            $result1 = DB::table('final_grade_list')
            // ->where('subject', $student_subject->subject)
            ->where('sid', $student_user->id)
            ->where('period', $i)
            ->get();
            
                foreach($result1 as $thegrade){
                    array_push($avgs, $thegrade->transmuted);
                }
                $sum[$i] = array_sum($avgs)/count($avgs);
                unset($avgs);
                
            }

        // $data['list_of_final_grade']['general_avg'][$student_user->id] = $sum[$i];
        // echo count($avgs); die();
            // $test2 = array_sum($test);
            echo $student_user->id;
            echo "<pre>";print_r($sum);echo "</pre>";die();
            // echo "<pre>";print_r($data['list_of_final_grade']);echo "</pre>";die();

            

        // }
    }

//GENERAL AVERAGE FINAL LIST
// foreach($data['student_list_per_grade'] as $student_list) {
//     for($i=1; $i<=4; $i++) {
//         $data['general_average_list_for_show'][$student_list->id][$i] = round((array_sum($data['array_general_average_the_rest_of_subject'][$student_list->id][$i])/$data['students_subjects_fixed_count']),2);
//     }
// }

/**
 * gino fix end
 */
        // echo "<pre>";print_r($data['list_of_final_grade']);echo "</pre>";die();

        /**
         * END OF COMPUTING FINAL GRADE
         */ 

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

         foreach($data['list_of_final_grade']['final_grade'] as $studentid=>$subject_list) {
            foreach($subject_list as $subject_key=>$val) {
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
                        $data['list_of_final_grade']['final_grade'][$studentid][$subject_key][$i] = [''];
                    }
                }
            }
         }

        /**
         * START OF TRANSMUTING GRADE AND FINAL GRADE
         */
// foremer elmer transmuted grades
        // foreach($data['list_of_final_grade']['final_grade'] as $studentid=>$subject_list) {
        //     foreach($subject_list as $subject_key=>$val) {
        //         for($i=1;$i<=5;$i++) {
        //             if($i == 4){
        //                 // $data['transmuted_final_grade'][$studentid][$subject_key][$i] = TeacherFetchJSON::transmuteGradesSpecial($val[$i]);
        //                 $data['transmuted_final_grade'][$studentid][$subject_key][$i] = TeacherFetchJSON::transmuteGradesSpecial($val[$i]);
        //             }else{
        //             $data['transmuted_final_grade'][$studentid][$subject_key][$i] = TeacherFetchJSON::transmuteGradesHere($val[$i]);
        //             }
        //         }
        //     }
        // }
/**
 * Start Gino Fix transmuted grades
 */
        foreach($data['list_of_final_grade']['final_grade'] as $studentid=>$subject_list) {
            foreach($subject_list as $subject_key=>$val) {
                for($i=1;$i<=5;$i++) {
                    if($i == 4){
                        // $data['transmuted_final_grade'][$studentid][$subject_key][$i] = TeacherFetchJSON::transmuteGradesSpecial($val[$i]);
                        $data['transmuted_final_grade'][$studentid][$subject_key][$i] = TeacherFetchJSON::transmuteGradesSpecial($val[$i]);
                    }else{
                    $data['transmuted_final_grade'][$studentid][$subject_key][$i] = TeacherFetchJSON::transmuteGradesHere($val[$i]);
                    }
                }
            }
        }


        echo"<pre>";print_r($data['transmuted_final_grade']);echo"</pre>";die();
       
        /**
         * END OF TRANSMUTING GRADE AND FINAL GRADE
         */

         //APPLY CONFIG FROM ADMIN FOR QUARTER SELECTION
         foreach($data['list_of_final_grade']['general_avg'] as $studentid=>$task_average) {
            for($i=1; $i<=4; $i++) {
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
                    $data['list_of_final_grade']['general_avg'][$studentid][$i] = '';
                }
            }
        }

        /**
         * START TRANSMUTING GENERAL AVERAGE
         */

        foreach($data['list_of_final_grade']['general_avg'] as $studentid=>$task_average) {
            for($i=1; $i<=4; $i++) {
                if($i == 4){
                    // $data['transmuted_final_grade'][$studentid][$subject_key][$i] = TeacherFetchJSON::transmuteGradesSpecial($val[$i]);
                $data['transmuted_general_average'][$studentid][$i] = TeacherFetchJSON::transmuteGradesSpecial($task_average[$i]);
                }else{
                $data['transmuted_general_average'][$studentid][$i] = TeacherFetchJSON::transmuteGradesHere($task_average[$i]);    
                }
            }
            $data['transmuted_general_average'][$studentid]['final_general_average'] = round(array_sum($data['transmuted_general_average'][$studentid]) / count($data['transmuted_general_average'][$studentid])); 
        }

        /**
         * END OF TRANSMUTING GENERAL AVERAGE
         */

        
        /**
         * START GETTING ALL QUALITATIVE SCORES
         */
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
                    $data['task_qualitative_score_array'][$student->id][$types->category][$types->type_name][$period] = '';
                }
            }
        }


        foreach($student_name as $student) {
            foreach($data['task_qualitative_scores'] as $scores) {
                foreach($data['task_qualitative_types'] as $category) {
                    if($scores->sid == $student->id) {
                        if($scores->qid == $category->id) {
                            $data['task_qualitative_score_array'][$scores->sid][$category->category][$category->type_name][$scores->period] = $scores->score;
                        }
                    }  
                }
            }
        }

        /**
         * END GETTING ALL QUALITATIVE SCORES
         */
        
        
        /**
         *  START OF GETTING ATTENDANCE OF STUDENT
         */    
                
        foreach($student_name as $student) {
            $data['total_days_currentyear'][$student->lastname.', '.$student->firstname] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_total'))
            ->whereBetween('date',['2019-06-01','2020-03-31'])
            ->where('sid', $student->id)
            ->get();
    
            $data['total_present_currentyear'][$student->lastname.', '.$student->firstname] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_present'))
            ->whereBetween('date',['2019-06-01','2020-03-31'])
            ->where(function ($query) {
                $query->orWhere('status', 'present')
                      ->orWhere('status', 'late');
            })
            ->where('sid', $student->id)
            ->get();
    
            $data['total_absent_currentyear'][$student->lastname.', '.$student->firstname] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_absent'))
            ->whereBetween('date',['2019-06-01','2020-03-31'])
            ->where('sid', $student->id)
            ->where('status', 'absent')
            ->get();
        }
    
        foreach($student_name as $student) {
            for($i = 6; $i<=12; $i++) {
                $data['total_attendance_currentyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                ->select(DB::raw('COUNT(status) as student_total'))
                ->where(DB::raw('MONTH(date)'), $i)
                ->where(DB::raw('YEAR(date)'), '2019')
                ->where('sid', $student->id)
                ->get();
    
                $data['attendance_list_present_currentyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                ->select(DB::raw('COUNT(status) as student_present'))
                ->where(DB::raw('MONTH(date)'), $i)
                ->where(DB::raw('YEAR(date)'), '2019')
                ->where('sid', $student->id)
                ->where(function ($query) {
                    $query->orWhere('status', 'present')
                          ->orWhere('status', 'late');
                })
                ->get();
    
                $data['attendance_list_absent_currentyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                ->select(DB::raw('COUNT(status) as student_absent'))
                ->where(DB::raw('MONTH(date)'), $i)
                ->where(DB::raw('YEAR(date)'), '2019')
                ->where('sid', $student->id)
                ->where('status', 'absent')
                ->get();
            }
        }
    
        foreach($student_name as $student) {
            for($i = 1; $i<=3; $i++) {
                $data['total_attendance_nextyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                ->select(DB::raw('COUNT(status) as student_total'))
                ->where(DB::raw('MONTH(date)'), $i)
                ->where(DB::raw('YEAR(date)'), '2020')
                ->where('sid', $student->id)
                ->get();
    
                $data['attendance_list_present_nextyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                ->select(DB::raw('COUNT(status) as student_present'))
                ->where(DB::raw('MONTH(date)'), $i)
                ->where(DB::raw('YEAR(date)'), '2020')
                ->where('sid', $student->id)
                ->where(function ($query) {
                    $query->orWhere('status', 'present')
                          ->orWhere('status', 'late');
                })
                ->get();
    
                $data['attendance_list_absent_nextyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                ->select(DB::raw('COUNT(status) as student_absent'))
                ->where(DB::raw('MONTH(date)'), $i)
                ->where(DB::raw('YEAR(date)'), '2020')
                ->where('sid', $student->id)
                ->where('status', 'absent')
                ->get();
            }
        }

        /***
         * END OF GETTING ATTENDANCE OF STUDENT
         */
        


    if(isset($data['get_all_taskid_as_list_checker'])) {
    // echo "<pre>";
    //     print_r($data);
    // echo "</pre>";die();
        
        return view('adminviewpdf')->with($data);
    } else {
        return redirect()->back()->with('error', 'No task grade or subject exists.');
    }
        
    }else{
        Auth::logout();
        return redirect('/login');
    }
    
}

public function viewAllTasksBySection(Request $request, $subj=null, $grade=null, $section=null, $period=null) {

    $data['grade_clicked'] = $grade;
    $data['section_clicked'] = $section;
    $data['period_clicked'] = $period;
    $data['subj_clicked'] = $subj;


    $data['notifications'] = $this->notificationsListHeaderNav();
    $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            //end check is session is set

    $data['page_title'] = 'Task List';
    $currentIdent = $request->session()->get('currentIdent','default');
            $data['user'] = Auth::user();
            
    $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();

    $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();

    $data['tasks_subject_list'] = DB::table('subjects')
    ->select('subject')
    ->groupBy('subject')
    ->get();

    $data['grade_section'] = DB::table('teacher_subj')
    ->select('grade','section')
    ->groupBy('grade','section')
    ->get();

    //added by elmer
    $data['student_id_list'] = DB::table('students')
    ->select('id', 'firstname', 'lastname')
    ->where('grade', $grade)
    ->where('section', $section)
    ->orderBy('lastname')
    ->get();

    $data['type_list_fixed'] = DB::table('task_type')
    ->select('type_name')
    ->orderByRaw("FIELD(type_name , 'WW', 'PT', 'QA')")
    ->get()
    ->unique('type_name');
    
    $data['grade_section_student_group_total_fixed'] = DB::table('students')
    ->where('grade', $grade)
    ->where('section', $section)
    ->count();
    
    $check_if_data_exists = DB::table('tasks')
    ->where('task_grade', $grade)
    ->where('task_section', $section)
    ->where('period', $period)
    ->where('task_subject', $subj)
    ->first();
    
    if($grade != '') {
        if(!isset($check_if_data_exists)) {
            return redirect('/afetch/tasks/grades')->with('error', 'No grades exists. Please check details.');
        }
    }
   
    $data['taskid_list'] = DB::table('tasks')
    ->where('task_grade', $grade)
    ->where('task_section', $section)
    ->where('period', $period)
    ->where('task_subject', $subj)
    ->orderByRaw("FIELD(task_type , 'WW', 'PT', 'QA')")
    ->get();

    $data['get_all_task_type'] = DB::table('task_type')
    ->whereNotIn('subject',['homeroom'])
    ->orderBy('subject')
    ->orderByRaw("FIELD(type_name , 'WW', 'PT', 'QA')")
    ->get();
    
    $data['get_final_grade_list'] = DB::table('final_grade_list')
    ->where('grade', $grade)
    ->where('section', $section)
    ->where('period', $period)
    ->where('subject', $subj)
    ->orderBy('subject')
    ->get();

    $data['taskid_list_with_weight'] = [];
    foreach($data['taskid_list'] as $taskid_list) {
        $arr_find_weight = DB::table('task_type')
        ->select('weight')
        ->where('subject', $subj)
        ->where('type_name', $taskid_list->task_type)
        ->first();

        array_push($data['taskid_list_with_weight'], $arr_find_weight->weight);
    }

    foreach($data['taskid_list'] as $taskid) {
        $data['array_of_task_list_on_taskid'][$taskid->id] = '';
    }

    foreach($data['taskid_list'] as $taskid) {
        $get_all_task_per_taskid = DB::table('task_student')
        ->where('taskid', $taskid->id)
        ->whereNotIn('status',['excused'])
        ->get();

        $data['array_of_task_list_on_taskid'][$taskid->id] = $get_all_task_per_taskid;
    }

    foreach($data['student_id_list'] as $student_id_list) {
        $data['student_list_for_final_grade'][$student_id_list->id] = '';

        foreach($data['taskid_list'] as $taskid) {
            $data['student_list_per_grade_section'][$student_id_list->id][$taskid->id] = 0;
            $data['task_average_score_list_per_task'][$taskid->id] = [];
            $data['sum_up_task_average_scores'][$taskid->id] = 0;
        }

        foreach($data['type_list_fixed'] as $type_list) {
            $data['student_taskid_with_type_name'][$student_id_list->id][$type_list->type_name] = [];
            $data['student_taskid_for_total_points'][$student_id_list->id][$type_list->type_name] = [];
            $data['student_list_grade_per_task_type'][$student_id_list->id][$type_list->type_name] = 0;
        }
    }

    foreach($data['taskid_list'] as $taskid) {
        foreach($data['array_of_task_list_on_taskid'][$taskid->id] as $task_list) {
            $data['student_list_per_grade_section'][$task_list->sid][$task_list->taskid] = $task_list->score;
            array_push($data['student_taskid_with_type_name'][$task_list->sid][$taskid->task_type], $task_list->score);
            array_push($data['student_taskid_for_total_points'][$task_list->sid][$taskid->task_type], $taskid->task_total_points);
            array_push($data['task_average_score_list_per_task'][$taskid->id], $task_list->score);
        }
    }

    foreach($data['get_final_grade_list'] as $final_grade_list) {
        $data['student_list_for_final_grade'][$final_grade_list->sid] = $final_grade_list->score;
    }

    foreach($data['student_id_list'] as $student_id_list) {
        foreach($data['type_list_fixed'] as $type_list) {
            if(array_sum($data['student_taskid_for_total_points'][$student_id_list->id][$type_list->type_name]) == 0) {
                $data['student_list_grade_per_task_type'][$student_id_list->id][$type_list->type_name] = 0;
            } else {
                $data['student_list_grade_per_task_type'][$student_id_list->id][$type_list->type_name] = round(((array_sum($data['student_taskid_with_type_name'][$student_id_list->id][$type_list->type_name])/array_sum($data['student_taskid_for_total_points'][$student_id_list->id][$type_list->type_name]))*100),2);
            }
        }
    }

    if(isset($data['task_average_score_list_per_task'])) {
        foreach($data['task_average_score_list_per_task'] as $taskid=>$val) {
            $data['sum_up_task_average_scores'][$taskid] = round((array_sum($val)/$data['grade_section_student_group_total_fixed']),2);
        }
    }

    if(isset($data['student_list_for_final_grade'])) {
        foreach($data['student_list_for_final_grade']  as $studentid=>$task_list) {
            $data['transmuted_grades_list'][$studentid] = TeacherFetchJSON::transmuteGradesHere($task_list);
        }
    }


    return view('agradingviewalltasks')->with($data);
    
}else{
    Auth::logout();
    return redirect('/login');
}


}

public function editprofileteacher(Request $request, $tid=0) {
    $data['notifications'] = $this->notificationsListHeaderNav();
    $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    if (Auth::check() ) {
            //check if session is set
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
            //end check is session is set

        $data['page_title'] = 'EDIT PROFILE TEACHER';
        $currentIdent = $request->session()->get('currentIdent','default');
                $data['user'] = Auth::user();
                
        $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();

        $data['teacherdetails'] = DB::table('teachers')->where('id', $tid )->first();

        $data['subjects'] = DB::table('teacher_subj')
                ->select('subj','grade','section')
                ->groupBy('subj','grade','section')
                ->orderBy('grade')
                ->orderBy('subj')
                ->get();

        $data['tasks_subject_list'] = DB::table('subjects')
        ->select('subject')
        ->groupBy('subject')
        ->get();

        $data['grade_section'] = DB::table('teacher_subj')
        ->select('grade','section')
        ->groupBy('grade','section')
        ->get();

        $data['teacher_clicked_id'] = $tid;

        $data['all_teacher_edit_data'] = $request->all();

    }

    return view('ateacheredit')->with($data);
}

public function posteditprofileteacher(Request $request) {
    $data['edit_teacher_data_list'] = $request->all();

    $check_if_id_exists = DB::table('teachers')
    ->where('id', $data['edit_teacher_data_list']['teacher_id_fixed'])
    ->first();

    if(!isset($check_if_id_exists)) {
        return redirect('/adirectory/teachers')->with('error', 'Cannot read teacher info. Please contant admin.');
    }

    $teacher_filename = $check_if_id_exists->profilepic;
    if($teacher_profile_pic = Input::file('teacher_profile_pic')) {
        $teacher_profile_pic = Input::file('teacher_profile_pic');
        //get file ext and create file
        $teacher_profile_pic_extension = Input::file('teacher_profile_pic')->getClientOriginalExtension();
        $teacher_filename = 'profileimg_'.$data['edit_teacher_data_list']['teacher_id_fixed'].'.'.$teacher_profile_pic_extension;
        //move uploaded img
        $teacher_profile_pic->move('uploads/profile',$teacher_filename);
    }

    $update_teacher_data = DB::table('teachers')
    ->where('id', $data['edit_teacher_data_list']['teacher_id_fixed'])
    ->update([
        'firstname'=>$data['edit_teacher_data_list']['teacher_firstname']  == null ? 'NA' : $data['edit_teacher_data_list']['teacher_firstname'],
        'lastname'=>$data['edit_teacher_data_list']['teacher_lastname']  == null ? 'NA' : $data['edit_teacher_data_list']['teacher_lastname'],
        'birthdate'=>$data['edit_teacher_data_list']['teacher_dob']  == null ? '0000-00-00' : $data['edit_teacher_data_list']['teacher_dob'],
        't_cellno'=>$data['edit_teacher_data_list']['teacher_cellphone']  == null ? '0' : $data['edit_teacher_data_list']['teacher_cellphone'],
        't_landline'=>$data['edit_teacher_data_list']['teacher_landline']  == null ? '0' : $data['edit_teacher_data_list']['teacher_landline'],
        't_email'=>$data['edit_teacher_data_list']['teacher_email']  == null ? 'NA' : $data['edit_teacher_data_list']['teacher_email'],
        't_address'=>$data['edit_teacher_data_list']['teacher_address']  == null ? 'NA' : $data['edit_teacher_data_list']['teacher_address'],
        'profilepic'=>$teacher_filename == null ? 'profile.png' : $teacher_filename,
    ]);
    
    if(!$update_teacher_data) {
        return redirect('/tprofile/'.$data['edit_teacher_data_list']['teacher_id_fixed'])->with('error', 'Failed update of teacher.');
    } else {
        return redirect('/tprofile/'.$data['edit_teacher_data_list']['teacher_id_fixed'])->with('message', 'Edited of teacher profile.');
    }
}

public function deleteprofileteacher(Request $request) {
    $data['delete_teacher_data_list'] = $request->all();

    $delete_teacher = DB::table('teachers')
    ->where('id', $data['delete_teacher_data_list']['teacher_id_delete'])
    ->delete();

    $delete_teacher_subjects = DB::table('teacher_subj')
    ->where('t_id', $data['delete_teacher_data_list']['teacher_id_delete'])
    ->delete();
    
    if($delete_teacher) {
        return redirect('/adirectory/teachers')->with('message', 'Teacher and their subjects handled are deleted.');
    } else {
        return redirect('/adirectory/teachers')->with('error', 'Teacher not delete. Please contact admin.');
    }
    
}

public function computeFinalGradesForAllStudents(Request $request) {

    $data['get_all_grade_and_section'] = DB::table('students')
    ->select('grade','section')
    ->whereNotIn('grade',['n'])
    ->groupBy('grade','section')
    ->orderBy('grade', 'section')
    ->get();

    foreach($data['get_all_grade_and_section'] as $grade_section) {
        $data['grade_section_final_grades'][$grade_section->grade][strtolower($grade_section->section)] = TeacherFetchJSON::finalGradeListFromGradeSection($grade_section->grade,strtolower($grade_section->section));
    }

    $isUpdated = false;
    foreach($data['get_all_grade_and_section'] as $grade_section) {
        foreach($data['grade_section_final_grades'][$grade_section->grade][strtolower($grade_section->section)]['final_grade'] as $studentid=>$subject_list) {
            foreach($subject_list as $subject_name=>$val) {
                for($i=1; $i<=4; $i++) {
                    $check_if_exist = DB::table('final_grade_list')
                    ->where('sid', $studentid)
                    ->where('subject', $subject_name)
                    ->where('period', $i)
                    ->first();

                    if(isset($check_if_exist)) {
                        //UPDATE
                        $update_final_grade_table = DB::table('final_grade_list')
                        ->where('sid', $studentid)
                        ->where('subject', $subject_name)
                        ->where('period', $i)
                        ->update([
                            'score'=>$val[$i],
                            'transmuted'=>TeacherFetchJSON::transmuteGradesHere($val[$i])
                        ]);

                        $isUpdated = true;
                    } else {
                        //INSERT
                        $insert_final_grade_table = DB::table('final_grade_list')
                        ->insert([
                            'sid'=>$studentid,
                            'subject'=>$subject_name,
                            'grade'=>$grade_section->grade,
                            'section'=>strtolower($grade_section->section),
                            'score'=>$val[$i],
                            'transmuted'=>TeacherFetchJSON::transmuteGradesHere($val[$i]),
                            'period'=>$i,
                            'school_year'=>'2019-2020'
                        ]);

                        $isUpdated = true;
                    }
                }
                
            }
        }   
    }

    if($isUpdated) {
        return redirect()->back()->with('message', 'Recompute final grades successful.');
    } else {
        return redirect()->back()->with('error', 'Recompute failed. Please contact developer team.');
    }
   
}


public static function computeFinalGradePerGradeSection($grade='', $section='') {

    $student_name = DB::table('students')
        ->select('id','firstname', 'lastname')
        ->where('grade', $grade)
        ->where('section', $section)
        ->orderBy('lastname')
        ->get();

        $data['student_list_by_user'] = DB::table('students')
        ->where('grade', $grade)
        ->where('section', $section)
        ->orderBy('lastname')
        ->get();
        
        $data['students_subjects'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $grade)
        ->where('section', $section)
        ->whereNotIn('subject',['homeroom'])
        ->groupBy('subject')
        ->get();

        $data['students_subjects_fixed_count'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $grade)
        ->where('section', $section)
        ->whereNotIn('subject',['homeroom'])
        ->count();

        $data['type_list_fixed'] = DB::table('task_type')
        ->select('type_name')
        ->orderByRaw("FIELD(type_name , 'WW', 'PT', 'QA')")
        ->get()
        ->unique('type_name');

        $period_list = array('1','2','3','4');
        $data['period_list'] = $period_list;

        $data['get_all_taskid_as_list_checker'] = DB::table('tasks')
        ->where('task_grade', $grade)
        ->where('task_section', $section)
        ->first();

        $data['get_all_taskid_as_list'] = DB::table('tasks')
        ->where('task_grade', $grade)
        ->where('task_section', $section)
        ->orderBy('task_subject')
        ->orderBy('period')
        ->orderByRaw("FIELD(task_type , 'WW', 'PT', 'QA')")
        ->get();

        $data['get_all_task_weight'] = DB::table('task_type')
        ->whereNotIn('subject',['homeroom'])
        ->orderBy('subject')
        ->orderByRaw("FIELD(type_name , 'WW', 'PT', 'QA')")
        ->get();


    foreach($student_name as $student) {
        $data['student_name_array_with_task_score'][$student->id] = [];
        $data['student_name_array_in_cleaner_list_score'][$student->id] = [];
        $data['student_name_array_with_total_points_and_weight'][$student->id] = [];
        $data['student_name_list'][$student->lastname.', '.$student->firstname] = $student->id;
    }

    foreach($data['students_subjects'] as $student_subjects) {
        $data['student_name_array_with_subject_weight'][$student->id] = [];
    }

    foreach($student_name as $student) {
        $get_task_score_from_student = DB::table('task_student')
        ->where('sid', $student->id)
        ->whereNotIn('status',['excused'])
        ->orderBy('period')
        ->get();

        array_push($data['student_name_array_with_task_score'][$student->id], $get_task_score_from_student);
    }

    foreach($data['student_name_array_with_task_score'] as $taskid_list) {
        foreach($taskid_list as $val) {
            foreach($val as $val2) {
                array_push($data['student_name_array_in_cleaner_list_score'][$val2->sid], array(
                    'taskid'=>$val2->taskid,
                    'sid'=>$val2->sid,
                    'score'=>$val2->score,
                    'period'=>$val2->period
                ));
            }

        }
    }

    foreach($student_name as $student) {
        foreach($data['get_all_taskid_as_list'] as $taskid_list) {
            foreach($data['get_all_task_weight'] as $task_weight) {
                foreach($data['student_name_array_in_cleaner_list_score'][$student->id] as $task_score) {
                    if($student->id == $task_score['sid'] && $task_score['taskid'] == $taskid_list->id && $task_weight->subject == $taskid_list->task_subject && $task_weight->type_name == $taskid_list->task_type) {
                        array_push($data['student_name_array_with_total_points_and_weight'][$task_score['sid']], array(
                            'subject'=>$taskid_list->task_subject,
                            'type'=>$taskid_list->task_type,
                            'score'=>$task_score['score'],
                            'total_points'=>$taskid_list->task_total_points,
                            'weight'=>$task_weight->weight,
                            'period'=>$task_score['period']
                        ));
                    }
                }
            }
        }
    }

    foreach($student_name as $student) {
        foreach($data['students_subjects'] as $student_subjects) {
            foreach($period_list as $period) {
                foreach($data['type_list_fixed'] as $type_list) {
                    $data['initial_array_for_score_list'][$student->id][$student_subjects->subject][$period][$type_list->type_name] = [];
                    $data['initial_array_for_total_points_list'][$student->id][$student_subjects->subject][$period][$type_list->type_name] = [];
                    $data['initial_array_for_task_weight'][$student->id][$student_subjects->subject][$period][$type_list->type_name] = 0;
                    $data['array_in_summing_up_task_scores_total_points_and_weight'][$student->id][$student_subjects->subject][$period][$type_list->type_name] = [];
                }
                $data['array_in_summing_up_grades_per_subject_period'][$student->id][$student_subjects->subject][$period] = [];
                
            }
            $data['array_in_summing_final_grade_per_subject'][$student->id][$student_subjects->subject] = [];
            $data['array_list_of_final_grade_per_subject'][$student->id][$student_subjects->subject] = [];
        }
    }

    foreach($student_name as $student) {
        foreach($period_list as $period) {
            foreach($data['students_subjects'] as $student_subjects) {
                $data['array_in_task_avg_list'][$student->id][$period][$student_subjects->subject] = [];
            }
            $data['array_in_summing_up_grades_per_period_on_all_subject'][$student->id][$period] = [];
            
        }
    }

    foreach($student_name as $student) {
        foreach($data['students_subjects'] as $student_subjects) {
            for($i=1;$i<=4;$i++) {
                $data['array_list_of_final_grade_per_period'][$student->id][$student_subjects->subject][$i] = [];
            }
        }
    }

    foreach($student_name as $student) {
        for($i=1; $i<=5; $i++) {
            $data['array_list_for_task_average'][$student->id][$i] = [];
        }
    }

    foreach($student_name as $student) {
        foreach($data['student_name_array_with_total_points_and_weight'][$student->id] as $task_score) {
            array_push($data['initial_array_for_score_list'][$student->id][$task_score['subject']][$task_score['period']][$task_score['type']], $task_score['score']);
            array_push($data['initial_array_for_total_points_list'][$student->id][$task_score['subject']][$task_score['period']][$task_score['type']], $task_score['total_points']);
            $data['initial_array_for_task_weight'][$student->id][$task_score['subject']][$task_score['period']][$task_score['type']] = $task_score['weight'];
        }
    }

    foreach($student_name as $student) {
        foreach($data['students_subjects'] as $student_subjects) {
            foreach($period_list as $period) {
                foreach($data['type_list_fixed'] as $type_list) {
                    if(array_sum($data['initial_array_for_total_points_list'][$student->id][$student_subjects->subject][$period][$type_list->type_name]) == 0) {
                        $data['array_in_summing_up_task_scores_total_points_and_weight'][$student->id][$student_subjects->subject][$period][$type_list->type_name] = [0];
                    } else {
                        array_push($data['array_in_summing_up_task_scores_total_points_and_weight'][$student->id][$student_subjects->subject][$period][$type_list->type_name], round(((array_sum($data['initial_array_for_score_list'][$student->id][$student_subjects->subject][$period][$type_list->type_name])/array_sum($data['initial_array_for_total_points_list'][$student->id][$student_subjects->subject][$period][$type_list->type_name]))*$data['initial_array_for_task_weight'][$student->id][$student_subjects->subject][$period][$type_list->type_name]),2));
                    }
                }
            }
        }
    }

    foreach($student_name as $student) {
        foreach($data['students_subjects'] as $student_subjects) {
            foreach($period_list as $period) {
                foreach($data['type_list_fixed'] as $type_list) {
                    array_push($data['array_in_summing_up_grades_per_subject_period'][$student->id][$student_subjects->subject][$period], $data['array_in_summing_up_task_scores_total_points_and_weight'][$student->id][$student_subjects->subject][$period][$type_list->type_name][0]);
                }
            }
        }
    }

    foreach($student_name as $student) {
        foreach($data['students_subjects'] as $student_subjects) {
            foreach($period_list as $period) {
                array_push($data['array_list_of_final_grade_per_period'][$student->id][$student_subjects->subject][$period], round(array_sum($data['array_in_summing_up_grades_per_subject_period'][$student->id][$student_subjects->subject][$period]),2));
            }
        }
    }


    return $data['array_list_of_final_grade_per_period'];
}

public function schoolYearStartDate(Request $request, $startdate='') {
  
    $data['submit_startdate'] = DB::table('config')
    ->select('version')
    ->where('config_name', 'startdate_school_year')
    ->first();

    if(isset($data['submit_startdate'])) {
        //UPDATE
        $data['submit_startdate'] = DB::table('config')
        ->where('config_name', 'startdate_school_year')
        ->update([
            'school_date'=>$startdate
        ]);

    } else {
        //INSERT
        $data['submit_startdate'] = DB::table('config')
        ->where('config_name', 'startdate_school_year')
        ->insert([
            'school_date'=>$startdate
        ]);
    }
    
    return redirect()->back()->with('message', 'School starts at '.$startdate);
}

public function schoolYearEndDate(Request $request, $enddate='') {
  
    $data['submit_startdate'] = DB::table('config')
    ->select('version')
    ->where('config_name', 'enddate_school_year')
    ->first();

    if(isset($data['submit_startdate'])) {
        //UPDATE
        $data['submit_startdate'] = DB::table('config')
        ->where('config_name', 'enddate_school_year')
        ->update([
            'school_date'=>$enddate
        ]);

    } else {
        //INSERT
        $data['submit_startdate'] = DB::table('config')
        ->where('config_name', 'enddate_school_year')
        ->insert([
            'school_date'=>$enddate
        ]);
    }
    
    return redirect()->back()->with('message', 'School starts at '.$enddate);
}

public function quarterSelectForParents(Request $request, $period_select='') {
  
    $checkIfDataExists = DB::table('config')
    ->select('remarks')
    ->where('config_name', 'quarter_select')
    ->first();

    if(isset($checkIfDataExists)) {
        DB::table('config')
        ->where('config_name', 'quarter_select')
        ->update([
            'remarks'=>$period_select
        ]);
    } else {
        DB::table('config')
        ->where('config_name', 'quarter_select')
        ->insert([
            'remarks'=>$period_select
        ]);
    }
    
    return redirect()->back()->with('message', 'Quarter selection success.');
}

public function firstQuarterSelectForParents(Request $request) {
    $data['first_quarter_value'] = $request->input('first_quarter_value');

    if($data['first_quarter_value'] == 0) {  
        DB::table('config')
        ->where('config_name', 'first_quarter_select')
        ->update(['status'=>1]);
        
    } else if($data['first_quarter_value'] == 1) {
        DB::table('config')
        ->where('config_name', 'first_quarter_select')
        ->update(['status'=>0]);
    }

    return response()->json(array(
        'isSend'=>2,
        'previousVal'=>$data['first_quarter_value']
    ));
}

public function secondQuarterSelectForParents(Request $request) {
    $data['second_quarter_value'] = $request->input('second_quarter_value');

    if($data['second_quarter_value'] == 0) {  
        DB::table('config')
        ->where('config_name', 'second_quarter_select')
        ->update(['status'=>1]);
        
    } else if($data['second_quarter_value'] == 1) {
        DB::table('config')
        ->where('config_name', 'second_quarter_select')
        ->update(['status'=>0]);
    }

    return response()->json(array(
        'isSend'=>2,
        'previousVal'=>$data['second_quarter_value']
    ));
}

public function thirdQuarterSelectForParents(Request $request) {
    $data['third_quarter_value'] = $request->input('third_quarter_value');

    if($data['third_quarter_value'] == 0) {  
        DB::table('config')
        ->where('config_name', 'third_quarter_select')
        ->update(['status'=>1]);
        
    } else if($data['third_quarter_value'] == 1) {
        DB::table('config')
        ->where('config_name', 'third_quarter_select')
        ->update(['status'=>0]);
    }

    return response()->json(array(
        'isSend'=>2,
        'previousVal'=>$data['third_quarter_value']
    ));
}

public function fourthQuarterSelectForParents(Request $request) {
    $data['fourth_quarter_value'] = $request->input('fourth_quarter_value');

    if($data['fourth_quarter_value'] == 0) {  
        DB::table('config')
        ->where('config_name', 'fourth_quarter_select')
        ->update(['status'=>1]);
        
    } else if($data['fourth_quarter_value'] == 1) {
        DB::table('config')
        ->where('config_name', 'fourth_quarter_select')
        ->update(['status'=>0]);
    }

    return response()->json(array(
        'isSend'=>2,
        'previousVal'=>$data['fourth_quarter_value']
    ));
}

public function finalGradeViewSelectForParents(Request $request) {
    $data['final_grade_value'] = $request->input('final_grade_value');

    if($data['final_grade_value'] == 0) {  
        DB::table('config')
        ->where('config_name', 'final_grade_select')
        ->update(['status'=>1]);
        
    } else if($data['final_grade_value'] == 1) {
        DB::table('config')
        ->where('config_name', 'final_grade_select')
        ->update(['status'=>0]);
    }

    return response()->json(array(
        'isSend'=>2,
        'previousVal'=>$data['final_grade_value']
    ));
}


public function notificationPostForMobile(Request $request) {


        $title = $request->input('title');
        $message = $request->input('message');
        $tid = $request->input('teacherid');
        $type = $request->input('type');
        $date = time();
        $group = $request->input('notification_select_sendgroup');
        $sendGroup = explode('-',$group);
        
        $nid=DB::table('notify')->insertGetId(
            [
                'date' => $date, 
                'title' => $title, 
                'message' => $message, 
                'tid' => $tid
            ]
        );
        
        if($sendGroup[0] == 'all') {
            $users = DB::table('users')->get();
        } elseif($sendGroup[0] == 'parents') {
            $sendGroup[0] = substr($sendGroup[0], 0, 1);
            $users = DB::table('users')
            ->where('type', $sendGroup[0])
            ->get();
        } elseif($sendGroup[0] == 'teachers') {
            $sendGroup[0] = substr($sendGroup[0], 0, 1);
            $users = DB::table('users')
            ->where('type', $sendGroup[0])
            ->get();
        } else {
            $usersident = [];
            $users = [];

            if($sendGroup[0] == 'kinder') {
                $sendGroup[1] = substr($sendGroup[0], 0, 1);
            } elseif($sendGroup[0] == 'nursery') {
                $sendGroup[1] = substr($sendGroup[0], 0, 1);
            } elseif($sendGroup[0] == 'prep') {
                $sendGroup[1] = substr($sendGroup[0], 0, 1);
            }

            $studentGrade_Section = DB::table('students')
            ->select('id')
            ->where('grade', $sendGroup[1])
            ->get();
            
           
            foreach($studentGrade_Section as $student){
                $student_mom_dad = DB::table('students')
                ->select('id','s_mom_id','s_dad_id')
                ->where('id', $student->id)
                ->get();

                if($student_mom_dad[0]->id != null) {
                    array_push($usersident, $student_mom_dad[0]->id);
                }
                
                if($student_mom_dad[0]->s_mom_id != null) {
                    array_push($usersident, $student_mom_dad[0]->s_mom_id);
                }
                
                if($student_mom_dad[0]->s_dad_id != null) {
                    array_push($usersident, $student_mom_dad[0]->s_dad_id);
                }
            }
            
            
            foreach($usersident as $users_list) {
                
                $user = DB::table('users')
                ->select('id')
                ->where('ident', $users_list)
                ->first();
                
                if($user != null) {
                    array_push($users, $user->id);
                    
                }
                
            }
        }

        if($sendGroup[0] == 'kinder') {
            $sendGroup[0] = substr($sendGroup[0], 0, 1);
        } elseif($sendGroup[0] == 'nursery') {
            $sendGroup[0] = substr($sendGroup[0], 0, 1);
        } elseif($sendGroup[0] == 'prep') {
            $sendGroup[0] = substr($sendGroup[0], 0, 2);
        }

        
        
        $viewed = '0';
        $push = '0';
        
        if($sendGroup[0] == 's') {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
       
            }
            
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
        } elseif($sendGroup[0] == 'pr') {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
       
            }
            
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
        } elseif($sendGroup[0] == 'k') {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
       
            }
           
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
        } elseif($sendGroup[0] == 'n') {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
       
            }
            
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
        } else {
            foreach($users as $user){
                DB::table('notify_users')->insert(
                    [
                        'nid' => $nid, 
                        'uid' => $user->id, 
                        'viewed' => $viewed, 
                        'push' => $push
                    ]
                );
            
            }
            
            $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
           
        }

        if($type == 'a') {
            $getAdminID = DB::table('users')->select('id')->where('ident', $tid)->first();
            DB::table('notify_users')->insert(
                [
                    'nid' => $nid, 
                    'uid' => $getAdminID->id, 
                    'viewed' => $viewed, 
                    'push' => $push
                ]
            );

            DB::table('notify_users')->insert(
                [
                    'nid' => $nid, 
                    'uid' => '31', 
                    'viewed' => $viewed, 
                    'push' => $push
                ]
            );
        }    
        
        return response()->json([
            'message' => 'success',]);
        
}

public function notificationTestingForMobile(Request $request) {


    $data['title'] = $request->input('title');
    $data['message'] = $request->input('message');
    $data['tid'] = $request->input('teacherid');
    $data['type'] = $request->input('type');
    $data['date'] = time();
    $data['group'] = $request->input('notification_select_sendgroup');
    $sendGroup = explode('-',$data['group']);
    
    // $nid=DB::table('notify')->insertGetId(
    //     [
    //         'date' => $date, 
    //         'title' => $title, 
    //         'message' => $message, 
    //         'tid' => $tid
    //     ]
    // );
    
    if($sendGroup[0] == 'all') {
        $users = DB::table('users')->get();
    } elseif($sendGroup[0] == 'parents') {
        $sendGroup[0] = substr($sendGroup[0], 0, 1);
        $users = DB::table('users')
        ->where('type', $sendGroup[0])
        ->get();
    } elseif($sendGroup[0] == 'teachers') {
        $sendGroup[0] = substr($sendGroup[0], 0, 1);
        $users = DB::table('users')
        ->where('type', $sendGroup[0])
        ->get();
    } else {
        $usersident = [];
        $users = [];

        if($sendGroup[0] == 'kinder') {
            $sendGroup[1] = substr($sendGroup[0], 0, 1);
        } elseif($sendGroup[0] == 'nursery') {
            $sendGroup[1] = substr($sendGroup[0], 0, 1);
        } elseif($sendGroup[0] == 'prep') {
            $sendGroup[1] = substr($sendGroup[0], 0, 1);
        }

        $studentGrade_Section = DB::table('students')
        ->select('id')
        ->where('grade', $sendGroup[1])
        ->get();
        
       
        foreach($studentGrade_Section as $student){
            $student_mom_dad = DB::table('students')
            ->select('id','s_mom_id','s_dad_id')
            ->where('id', $student->id)
            ->get();
            
            // echo json_encode('Iterate student and their parents ID success. ID is '.$student->id).'<br />';

            if($student_mom_dad[0]->id != null) {
                array_push($usersident, $student_mom_dad[0]->id);
            }
            
            if($student_mom_dad[0]->s_mom_id != null) {
                array_push($usersident, $student_mom_dad[0]->s_mom_id);
            }
            
            if($student_mom_dad[0]->s_dad_id != null) {
                array_push($usersident, $student_mom_dad[0]->s_dad_id);
            }
        }
        
        
        foreach($usersident as $users_list) {
            
            $user = DB::table('users')
            ->select('id')
            ->where('ident', $users_list)
            ->first();
            
            if($user != null) {
                array_push($users, $user->id);
                
            }
            
        }
    }

    if($sendGroup[0] == 'kinder') {
        $sendGroup[0] = substr($sendGroup[0], 0, 1);
    } elseif($sendGroup[0] == 'nursery') {
        $sendGroup[0] = substr($sendGroup[0], 0, 1);
    } elseif($sendGroup[0] == 'prep') {
        $sendGroup[0] = substr($sendGroup[0], 0, 2);
    }

    
    
    $viewed = '0';
    $push = '0';
    
    if($sendGroup[0] == 's') {
        // foreach($users as $user){
        //     DB::table('notify_users')->insert(
        //         [
        //             'nid' => $nid, 
        //             'uid' => $user, 
        //             'viewed' => $viewed, 
        //             'push' => $push
        //         ]
        //     );
   
        // }
        // echo json_encode('Send notification to student and their parents.')."<br />";
        // $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
    } elseif($sendGroup[0] == 'pr') {
        // foreach($users as $user){
        //     DB::table('notify_users')->insert(
        //         [
        //             'nid' => $nid, 
        //             'uid' => $user, 
        //             'viewed' => $viewed, 
        //             'push' => $push
        //         ]
        //     );
   
        // }
        // echo json_encode('Send notification to prep and their parents.')."<br />";
        // $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
    } elseif($sendGroup[0] == 'k') {
        // foreach($users as $user){
        //     DB::table('notify_users')->insert(
        //         [
        //             'nid' => $nid, 
        //             'uid' => $user, 
        //             'viewed' => $viewed, 
        //             'push' => $push
        //         ]
        //     );
   
        // }
        // echo json_encode('Send notification to kinder and their parents.')."<br />";
        // $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
    } elseif($sendGroup[0] == 'n') {
        // foreach($users as $user){
        //     DB::table('notify_users')->insert(
        //         [
        //             'nid' => $nid, 
        //             'uid' => $user, 
        //             'viewed' => $viewed, 
        //             'push' => $push
        //         ]
        //     );
   
        // }
        // echo json_encode('Send notification to nursery and their parents.')."<br />";
        // $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
    } else {
        // foreach($users as $user){
        //     DB::table('notify_users')->insert(
        //         [
        //             'nid' => $nid, 
        //             'uid' => $user->id, 
        //             'viewed' => $viewed, 
        //             'push' => $push
        //         ]
        //     );
        
        // }
        // echo json_encode('Send notification to all ');
        // echo json_encode($data['group']);
        // echo json_encode($sendGroup)."<br />";
        // $callSendNotifClass = \App\Http\Controllers\ApiController::sendNotification($group, $title, $message);
       
    }

    if($data['type'] == 'a') {
        // $getAdminID = DB::table('users')->select('id')->where('ident', $tid)->first();
        // DB::table('notify_users')->insert(
        //     [
        //         'nid' => $nid, 
        //         'uid' => $getAdminID->id, 
        //         'viewed' => $viewed, 
        //         'push' => $push
        //     ]
        // );
        // echo json_encode('Send the notification data to database for admin and tjslipio.')."<br />";
        // DB::table('notify_users')->insert(
        //     [
        //         'nid' => $nid, 
        //         'uid' => '31', 
        //         'viewed' => $viewed, 
        //         'push' => $push
        //     ]
        // );
    } 
    
    // if(isset($usersident)) {
    // echo json_encode('******these are USER IDENT iterated during the process*****').'<br />';
    // print_r($usersident);
    // echo json_encode('******these are USERS iterated during the process*****').'<br />';
    // print_r($users);
    // }
    // echo json_encode('******this is a sendgroup I used for database this is not used on FCM*****').'<br />';
    // print_r($sendGroup);
    // echo "<br />";
    // echo json_encode('******details sent by developer below*****').'<br />';
    // print_r($data);
    // die();
    return response()->json([
        'message' => 'success',]);
    
}

public function getConfigDatas(Request $request) {
    $configData = DB::table('config')->get();

    return response()->json($configData);
}

public function showActivityUser(Request $request) {
        $data['page_title'] = 'Activity Users';
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();


        if (Auth::check() ) {
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->get();

            $api_teacher = ApiController::getTeachersFromActivity();

            $api_parent = ApiController::getParentsFromActivity();

            $data['teacher_data_set'] = $api_teacher->original['teacher_data'];

            $data['parent_data_set'] = $api_parent->original['parent_data'];

            return view('activity_user')->with($data);

        }
    }

    public function showActivityCount(Request $request, $startdate, $enddate) {

        $data['page_title'] = 'Activity Users';
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    
    
        if (Auth::check() ) {
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
    
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->get();
    
            $api_teacher = ApiController::getTeachersFromActivity();
    
            $api_parent = ApiController::getParentsFromActivity();
    
            $data['teacher_data_set'] = $api_teacher->original['teacher_data'];
    
            $data['parent_data_set'] = $api_parent->original['parent_data'];
    
            $data['startdate'] = $startdate;
            $data['enddate'] = $enddate;
            // $startdate='2020-01-01';
            // $enddate='2020-03-20';
            $activityTypes = DB::table('activity_users')
            ->selectRaw("DISTINCT BINARY(activity) as activity")
            // ->distinct('activity')
            ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
            //  ->where('activity', 'dashboard')
            // ->groupBy(DB::raw('BINARY `activity`'))
            // ->count();
            ->get();
            // echo "<pre>";print_r($activityTypes);echo "</pre>";
            foreach($activityTypes as $activity){
                // if($activity->activity == null)$activity->activity = '-NA-';
                // echo $activity->activity . "<br />";
                $data['activity_totals'][$activity->activity] = DB::table('activity_users')
                ->select('uid')
                ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
                ->where('activity', $activity->activity)
                ->groupBy('uid')
                ->count();
            }
            // echo "<pre>";print_r($data);echo "</pre>";
            // die();

            // $data['dashboard'] = DB::table('activity_users')
            //             ->select('uid')
            //             ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
            //             ->where('activity', 'dashboard')
            //             ->groupBy('uid')
            //             ->count();
            // $data['replyslip'] = DB::table('activity_users')
            //             ->select('uid')
            //             ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
            //             ->where('activity', 'replyslip')
            //             ->groupBy('uid')
            //             ->count();
            // $data['notification'] = DB::table('activity_users')
            //             ->select('uid')
            //             ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
            //             ->where('activity', 'notification')
            //             ->groupBy('uid')
            //             ->count();
            // $data['chat'] = DB::table('activity_users')
            //             ->select('uid')
            //             ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
            //             ->where('activity', 'chat')
            //             ->groupBy('uid')
            //             ->count();
            // $data['subject'] = DB::table('activity_users')
            //             ->select('uid')
            //             ->whereBetween('date', [$startdate." 00:00:00", $enddate." 23:59:59"])
            //             ->where('activity', 'subject')
            //             ->groupBy('uid')
            //             ->count();
    
            return view('activity_user')->with($data);
    
        }
    }

    public function viewGradesInPdfManual(Request $request, $grade='',$section='') {
        $data['grade_clicked'] = $grade;
        $data['section_clicked'] = $section;
    
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
    if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set
    
        $data['page_title'] = 'Review Grades for PDF';
        $currentIdent = $request->session()->get('currentIdent','default');
                $data['user'] = Auth::user();
                
        $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();
    
        // $data['subjects'] = DB::table('teacher_subj')
        // ->select('subj','grade','section')
        // ->where('t_id', $data['teacheruser']->id)
        // ->get();
        $data['subjects'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        ->groupBy('subj','grade','section')
        ->orderBy('grade')
        ->orderBy('subj')
        ->get();
    
        $data['grade_section'] = DB::table('teacher_subj')
        ->select('grade','section')
        ->where('t_id', $data['teacheruser']->id)
        ->groupBy('grade','section')
        ->get();
    
        //added by elmer
        
        
            $student_name = DB::table('students')
            ->select('students.id','students.firstname', 'students.lastname', 'students_lrn.lrn', 'students_lrn.age_sy')
            ->join('students_lrn', 'students.id', '=', 'students_lrn.sid')
            ->where('grade', $grade)
            ->where('section', $section)
            ->orderBy('lastname')
            ->get();
    
            $data['student_list_by_user'] = DB::table('students')
            ->where('grade', $grade)
            ->where('section', $section)
            ->orderBy('lastname')
            ->get();
            
            $data['students_subjects'] = DB::table('subjects')
            ->select('subject')
            ->where('grade', $grade)
            ->where('section', $section)
            ->whereNotIn('subject',['homeroom'])
            ->orderBy('rankorder')
            ->get();
    
            $period_list = array('1','2','3','4');
            $data['period_list'] = $period_list;
    
            $data['get_all_taskid_as_list_checker'] = DB::table('tasks')
            ->where('task_grade', $grade)
            ->where('task_section', $section)
            ->first();
    
            // $checker_of_final_grade= DB::table('final_grade_list')
            // ->where('grade', $grade)
            // ->where('section', $section)
            // ->whereNotIn('subject',['homeroom'])
            // ->orderBy('subject')
            // ->orderBy('period')
            // ->first();
    
            // if(!isset($checker_of_final_grade)) {
            //     return redirect('/afetch/final/view/grades')->with('error', 'No grades exists. Please contact admin.');
            // }
    
            foreach($student_name as $student) {
                $data['student_name_list'][$student->lastname.', '.$student->firstname] = $student->id;
            }
             
            /***
             * COMPUTE BIRHDAY
             */
            $data['fixed_year'] = date('Y');
    
            // foreach($data['student_list_by_user'] as $student_user) {
            //     if(isset($student_user->birthdate)) {
            //         $data['age_list'][$student_user->id] = $data['fixed_year'] - date('Y', strtotime($student_user->birthdate));
            //     } else {
            //         $data['age_list'][$student_user->id] = 0;
            //     }
            // }
    
            foreach($student_name as $student){
                $data['age_list'][$student->id] = $student->age_sy;
            }
    
            foreach($student_name as $student){
                $data['lrn_list'][$student->id] = $student->lrn;
            }
             /***
              * END COMPUTE BIRTHDAY
              */
    
    
    
            /**
             * START OF COMPUTING FINAL GRADE
             */
            // original elmer computation of final grades
            // $data['list_of_final_grade'] = TeacherFetchJSON::finalGradeListFromGradeSection($grade, $section);
    
    
            // final grades from prepopulated final_grades table
            // $data['list_of_final_grade']['final_grade'] = 
            // $data['list_of_final_grade']['final_grade'][$student_user->id]
    
            // echo "<pre>";print_r($data['students_subjects']);echo "</pre>";die();
    /**
     * Gino fix start
     */
    // for final_grade
            foreach ($data['student_list_by_user'] as $student_user){
                foreach ($data['students_subjects'] as $student_subject){
                    for($i = 1; $i<=5; $i++) {
                    $result = DB::table('reportcard_manual')
                    ->where('subj_label', $student_subject->subject)
                    ->where('sid', $student_user->id)
                    ->where('period', $i)
                    ->get();
    
                    $data['list_of_final_grade']['final_grade'][$student_user->id][$student_subject->subject][$i] = $result[0]->score;
    
                    
                    }
                
                }

                /**
                 *  query for general average
                 */
                for($i = 1; $i<=5; $i++) {
                    $result1 = DB::table('reportcard_manual')
                    ->where('subj_label', 'general_average')
                    ->where('sid', $student_user->id)
                    ->where('period', $i)
                    ->get();
    
                    $data['list_of_final_grade']['general_average'][$student_user->id][$i] = $result1[0]->score;
    
                    
                    }
            
            }
        // echo "<pre>";print_r($data['list_of_final_grade']);echo "</pre>";die();
       
    
    
            
            /**
             * START GETTING ALL QUALITATIVE SCORES
             */
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
                        $data['task_qualitative_score_array'][$student->id][$types->category][$types->type_name][$period] = '';
                    }
                }
            }
    
    
            foreach($student_name as $student) {
                foreach($data['task_qualitative_scores'] as $scores) {
                    foreach($data['task_qualitative_types'] as $category) {
                        if($scores->sid == $student->id) {
                            if($scores->qid == $category->id) {
                                $data['task_qualitative_score_array'][$scores->sid][$category->category][$category->type_name][$scores->period] = $scores->score;
                            }
                        }  
                    }
                }
            }
    
            /**
             * END GETTING ALL QUALITATIVE SCORES
             */
            
            
            /**
             *  START OF GETTING ATTENDANCE OF STUDENT
             */    
                    
            foreach($student_name as $student) {
                $data['total_days_currentyear'][$student->lastname.', '.$student->firstname] = DB::table('attendance')
                ->select(DB::raw('COUNT(status) as student_total'))
                ->whereBetween('date',['2019-07-07','2020-04-31'])
                ->where('sid', $student->id)
                ->get();
        
                $data['total_present_currentyear'][$student->lastname.', '.$student->firstname] = DB::table('attendance')
                ->select(DB::raw('COUNT(status) as student_present'))
                ->whereBetween('date',['2019-07-07','2020-04-31'])
                ->where(function ($query) {
                    $query->orWhere('status', 'present')
                          ->orWhere('status', 'late');
                })
                ->where('sid', $student->id)
                ->get();
        
                $data['total_absent_currentyear'][$student->lastname.', '.$student->firstname] = DB::table('attendance')
                ->select(DB::raw('COUNT(status) as student_absent'))
                ->whereBetween('date',['2019-07-07','2020-04-31'])
                ->where('sid', $student->id)
                ->where('status', 'absent')
                ->get();
            }
        
            foreach($student_name as $student) {
                for($i = 7; $i<=12; $i++) {
                    $data['total_attendance_currentyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                    ->select(DB::raw('COUNT(status) as student_total'))
                    ->where(DB::raw('MONTH(date)'), $i)
                    ->where(DB::raw('YEAR(date)'), '2019')
                    ->where('sid', $student->id)
                    ->get();
        
                    $data['attendance_list_present_currentyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                    ->select(DB::raw('COUNT(status) as student_present'))
                    ->where(DB::raw('MONTH(date)'), $i)
                    ->where(DB::raw('YEAR(date)'), '2019')
                    ->where('sid', $student->id)
                    ->where(function ($query) {
                        $query->orWhere('status', 'present')
                              ->orWhere('status', 'late');
                    })
                    ->get();
        
                    $data['attendance_list_absent_currentyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                    ->select(DB::raw('COUNT(status) as student_absent'))
                    ->where(DB::raw('MONTH(date)'), $i)
                    ->where(DB::raw('YEAR(date)'), '2019')
                    ->where('sid', $student->id)
                    ->where('status', 'absent')
                    ->get();
                }
            }
        
            foreach($student_name as $student) {
                for($i = 1; $i<=4; $i++) {
                    $data['total_attendance_nextyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                    ->select(DB::raw('COUNT(status) as student_total'))
                    ->where(DB::raw('MONTH(date)'), $i)
                    ->where(DB::raw('YEAR(date)'), '2020')
                    ->where('sid', $student->id)
                    ->get();
        
                    $data['attendance_list_present_nextyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                    ->select(DB::raw('COUNT(status) as student_present'))
                    ->where(DB::raw('MONTH(date)'), $i)
                    ->where(DB::raw('YEAR(date)'), '2020')
                    ->where('sid', $student->id)
                    ->where(function ($query) {
                        $query->orWhere('status', 'present')
                              ->orWhere('status', 'late');
                    })
                    ->get();
        
                    $data['attendance_list_absent_nextyear'][$student->lastname.', '.$student->firstname][$i] = DB::table('attendance')
                    ->select(DB::raw('COUNT(status) as student_absent'))
                    ->where(DB::raw('MONTH(date)'), $i)
                    ->where(DB::raw('YEAR(date)'), '2020')
                    ->where('sid', $student->id)
                    ->where('status', 'absent')
                    ->get();
                }
            }
    
            /***
             * END OF GETTING ATTENDANCE OF STUDENT
             */
            
    
    
        if(isset($data['get_all_taskid_as_list_checker'])) {
        // echo "<pre>";
        //     print_r($data);
        // echo "</pre>";die();
            
            return view('adminviewpdf2')->with($data);
        } else {
            return redirect()->back()->with('error', 'No task grade or subject exists.');
        }
            
 
}else{
            Auth::logout();
            return redirect('/login');
    }
    }       




}
