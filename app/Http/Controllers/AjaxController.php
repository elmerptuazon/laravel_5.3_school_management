<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class AjaxController extends Controller
{
    public function __construct()
    {
        if (Auth::check() ) {
        //     if(Auth::user()->type == 'p'){
        //         // session(['currentIdent' => Auth::user()->ident]);
        //         // session(['currentIdent' => 1211009]);
            
 
        // //         // session(['currentIdent' => Auth::user()->ident]);
        // //         //SELECT id, firstname, lastname, grade, section from students where s_mom_id = :s_mom_id
        //         $result = DB::table('students')
        //         ->select('id','firstname','lastname','grade','section')
        //         ->where('s_mom_id', Auth::user()->ident)
        //         ->get();

        //         if($results->isEmpty()){
                
        //             $result = DB::table('students')
        //         ->select('id','firstname','lastname','grade','section')
        //         ->where('s_dad_id', Auth::user()->ident)
        //         ->get();

        //         }
        //         if ($result->count()) {
        //             // do something

        //             session(['children' => $result]);
                    
        //         }
        // //         print_r($result);
        //         session(['currentIdent' => $result[0]['id']]);
        //     }
        
         }
    }
    //
    public function ajaxHomeworkSubjects($subj,$date){
        // $date = $request->input('date');
        $subj = str_replace(' ', '_', trim($subj));
        if (Auth::check() ) {
            $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();
        $data['homeworks'] = DB::table('homeworks')
        ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
        ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
         ->where('homeworks.grade', $data['studentuser']->grade)

         ->where('homeworks.section', $data['studentuser']->section)
         ->where('homeworks.subject', $subj)

        ->where('homeworks.pubdate', $date)

        ->get();
        
        $data['date'] = $date;
        return view('layouts/ajaxHomeworkSubjects')->with($data);
        }
    }
    public function ajaxHWSubjectsLoadMore($subj,$totals,$page){

        // dont forget to validate, sanitize and authorize
        // $date = $request->input('date');
        $limit = 5;
        $totalpages = ceil($totals / $limit);
        $offset = ($page - 1)*$limit;

        $subj = str_replace(' ', '_', trim($subj));
        if (Auth::check() ) {
            $data['studentuser'] = DB::table('students')->where('id', session('currentIdent') )->first();

            $data['homeworks'] = DB::table('homeworks')
                ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
                ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
                ->where('homeworks.grade', $data['studentuser']->grade)
                ->where('homeworks.section', $data['studentuser']->section)
                ->where('homeworks.subject', $subj)
                ->orderBy('pubdate','desc')
                ->offset($offset)
                ->limit(5)
                ->get();
        
        
        return view('layouts/ajaxHWSubjectsLoadMore')->with($data);
        }
    }
    public function ajaxTeachersHomeworkSubjects($subj,$grade,$section,$date){
        // $date = $request->input('date');
        $subj = str_replace(' ', '_', trim($subj));
        if (Auth::check() ) {
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
        $data['homeworks'] = DB::table('homeworks')
        ->select('homeworks.*','teachers.title','teachers.firstname','teachers.lastname')
        ->leftJoin('teachers', 'homeworks.teacher_id', '=', 'teachers.id')
         ->where('homeworks.grade', $grade)

         ->where('homeworks.section', $section)
         ->where('homeworks.subject', $subj)

        ->where('homeworks.pubdate', $date)

        ->get();
        
        $data['date'] = $date;
        return view('layouts/ajaxHomeworkSubjects')->with($data);
        }
    }
    public function ajaxTeachersHWSubjectsLoadMore($subj,$grade,$section,$totals,$page){

        // dont forget to validate, sanitize and authorize
        // $date = $request->input('date');
        $subj = str_replace('_',' ',trim($subj));
        
        $limit = 5;
        $totalpages = ceil($totals / $limit);
        $offset = ($page - 1)*$limit;

        $subj = str_replace(' ', '_', trim($subj));
        if (Auth::check() ) {
            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();

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

                   $data['subjects'] = DB::table('teacher_subj')
                   ->select('subj','grade','section')
                   ->where('t_id', $data['teacheruser']->id)
                   // ->where('section', $data['studentuser']->section)
                   // ->groupBy('subject')
                   // ->orderBy('rankorder', 'desc')
                   ->get();
        return view('layouts/ajaxHWSubjectsLoadMore')->with($data);
        }
    }

    public function ajaxScheduleAll(){
        if (Auth::check() ) {
            // $data['studentuser'] = DB::table('students')->where('id',Auth::user()->ident )->first();
            $totalsched = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
            $schedlength = count($totalsched);
            $result=array();
            //SELECT subject, grade, section, schedule FROM subjects where grade = :grade and section = :section and $day = 1 order by rankorder desc
            if(Auth::user()->type =='s' || Auth::user()->type =='p'){
            $data['studentuser'] = DB::table('students')->where('id',session('currentIdent') )->first();                
            foreach($totalsched as $day){
                $data[$day] = DB::table('subjects')
                ->select('subject', 'grade', 'section', 'schedule')
                ->where('grade', $data['studentuser']->grade)
                ->where('section', $data['studentuser']->section)
                ->where($day, 1)
                ->orderBy('rankorder','desc')

                ->get();
            }
            }elseif(Auth::user()->type == 't'){
            $data['teacheruser'] = DB::table('teachers')->where('id',session('currentIdent') )->first();
                
                foreach($totalsched as $day){
                    $data[$day] = DB::table('teacher_subj')
                    ->select('teacher_subj.t_id','subjects.*')
                    ->leftJoin('subjects', function($join){
                        $join->on('teacher_subj.subj', '=', 'subjects.subject');
                        $join->on('teacher_subj.grade','=','subjects.grade'); 
                        $join->on('teacher_subj.section','=','subjects.section'); 
                        })
                        ->where('teacher_subj.t_id',$data['teacheruser']->id)
                    ->where($day, 1)
                    ->orderByRaw('schedule desc')
                    ->get();    
                }
        //     $data['schedule'] = DB::table('teacher_subj')
        // // ->select('teacher_subj.subj','teacher_subj.grade','teacher_subj.section','subjects.schedule')
        //     ->select('teacher_subj.t_id','subjects.*')
        // // ->leftJoin('subjects', 'teacher_subj.subj', '=', 'subjects.subject')
        //     ->leftJoin('subjects', function($join){
        //     $join->on('teacher_subj.subj', '=', 'subjects.subject');
        //     $join->on('teacher_subj.grade','=','subjects.grade'); 
        //     $join->on('teacher_subj.section','=','subjects.section'); 
        //     })
        //     ->where('teacher_subj.t_id',$data['teacheruser']->id)
        //     ->where($day, 1)
        //     ->orderByRaw('schedule desc')
        //     ->get();
            }
            // echo session('currentIdent');die();
        // echo "test";echo session('currentIdent');die();
            
            return view('layouts/ajaxScheduleAll')->with($data);

        }
    }

    public function ajaxAssignmentEdit(Request $request) {

        if (Auth::check()) {
            // $request->session()->flash('status', 'Task was successful!');
            $classArray = explode('_',$request->input('class'));
            $subject = $classArray[0];
            $grade = $classArray[1];
            $section = $classArray[2];

            $id = $request->input('hw');
                // print_r($request->input());
                $teacherid = Auth::user()->ident;
        
                $description=$request->input('description');
                $inputdate = date('Y-m-d H:i:s');   
                $pubdate = $request->input('pubdate');
 
                $description_clean = htmlspecialchars($description);
            

            DB::table('homeworks')
                ->where('id', $id)
                ->update([
                    'subject' => $subject,
                    'grade' => $grade,
                    'section' => $section,
                    'teacher_id' => $teacherid,
                    'description' => $description_clean,
                    'pubdate' => $pubdate
                ]);

                $data['homework'] = DB::table('homeworks')->where('id', $id)->first();

                // print_r($request->input());
                return view('layouts/ajaxAssignmentEdit')->with($data);
                // if($updated){
                //     echo "updated";
                // }
                // else{
                //     echo "look again";
                // }
        }
    }


    public function  ajaxAssignmentDel (Request $request){

        if(Auth::check()){
            $id = $request->input('hw');
            $result = DB::table('homeworks')->where('id', '=', $id)->delete();
            if($result){
            echo '
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Success!</h4>
                The assignment  '.$request->input('hw').' has been deleted.
              </div>
            ';
            }
            // return false;
        }
        
    }


    public function ajaxTestEdit(Request $request) {

        if (Auth::check()) {
            // $request->session()->flash('status', 'Task was successful!');
            $classArray = explode('_',$request->input('class'));
            $subject = $classArray[0];
            $grade = $classArray[1];
            $section = $classArray[2];

            $id = $request->input('test');
                // print_r($request->input());
                $teacherid = Auth::user()->ident;
        
                $title=$request->input('title');
                 
                $date = $request->input('pubdate');
                $period = $request->input('period');
                // $title = htmlspecialchars($description);
            // print_r($request->input());
            // echo "test!";

            DB::table('tests')
                ->where('id', $id)
                ->update([
                    'subject' => $subject,
                    'grade' => $grade,
                    'section' => $section,
                    'period' =>$period,
                    'title' => $title,
                    'date' => $date
                ]);

                $data['test'] = DB::table('tests')->where('id', $id)->first();

            // //     // print_r($request->input());
                return view('layouts/ajaxTestEdit')->with($data);
            //     // if($updated){
                //     echo "updated";
                // }
                // else{
                //     echo "look again";
                // }
        }
    }

    public function  ajaxTestDel (Request $request){

        if(Auth::check()){
            $id = $request->input('test');
            $result = DB::table('tests')->where('id', '=', $id)->delete();
            if($result){
            echo '
            <td colspan="4">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Success!</h4>
                The Test  '.$request->input('test').' has been deleted.
              </div>
              </td>
            ';
            }
            // return false;
        }
        
    }

    public function ajaxASEdit(Request $request) {

        if (Auth::check()) {
            // $request->session()->flash('status', 'Task was successful!');
            $classArray = explode('_',$request->input('class'));
            $subject = $classArray[0];
            $grade = $classArray[1];
            $section = $classArray[2];

            $id = $request->input('as');
                // print_r($request->input());
                $teacherid = Auth::user()->ident;
        
                $title=$request->input('title');
                 
                $date = $request->input('pubdate');
                $period = $request->input('period');
                // $title = htmlspecialchars($description);
            // print_r($request->input());
            // echo "test!";

            DB::table('activitysheets')
                ->where('id', $id)
                ->update([
                    'subject' => $subject,
                    'grade' => $grade,
                    'section' => $section,
                    'period' =>$period,
                    'title' => $title,
                    'date' => $date
                ]);

                $data['activitysheet'] = DB::table('activitysheets')->where('id', $id)->first();

            // //     // print_r($request->input());
                return view('layouts/ajaxASEdit')->with($data);
            //     // if($updated){
                //     echo "updated";
                // }
                // else{
                //     echo "look again";
                // }
        }
    }

    public function  ajaxASDel (Request $request){

        if(Auth::check()){
            $id = $request->input('as');
            $result = DB::table('activitysheets')->where('id', '=', $id)->delete();
            if($result){
            echo '
            <td colspan="4">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Success!</h4>
                The Activitysheet  '.$request->input('as').' has been deleted.
              </div>
              </td>
            ';
            }
            // return false;
        }
        
    }

    public function ajaxHOEdit(Request $request) {

        if (Auth::check()) {
            // $request->session()->flash('status', 'Task was successful!');
            $classArray = explode('_',$request->input('class'));
            $subject = $classArray[0];
            $grade = $classArray[1];
            $section = $classArray[2];

            $id = $request->input('ho');
                // print_r($request->input());
                $teacherid = Auth::user()->ident;
        
                $title=$request->input('title');
                 
                $date = $request->input('pubdate');
                $period = $request->input('period');
                // $title = htmlspecialchars($description);
            // print_r($request->input());
            // echo "test!";

            DB::table('handouts')
                ->where('id', $id)
                ->update([
                    'subject' => $subject,
                    'grade' => $grade,
                    'section' => $section,
                    'period' =>$period,
                    'title' => $title,
                    'date' => $date
                ]);

                $data['handout'] = DB::table('handouts')->where('id', $id)->first();

            // //     // print_r($request->input());
                return view('layouts/ajaxHOEdit')->with($data);
            //     // if($updated){
                //     echo "updated";
                // }
                // else{
                //     echo "look again";
                // }
        }
    }

    public function  ajaxHODel (Request $request){

        if(Auth::check()){
            $id = $request->input('ho');
            $result = DB::table('handouts')->where('id', '=', $id)->delete();
            if($result){
            echo '
            <td colspan="4">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Success!</h4>
                The Handout  '.$request->input('test').' has been deleted.
              </div>
              </td>
            ';
            }
            // return false;
        }
        
    }    
    
    public function  ajaxRSOptionEdit (Request $request){

        if(Auth::check()){
            $id = $request->input('oid');
            
            $option = $request->input('option');
            // echo $option;die();
            DB::table('replyslips_opt')
                ->where('oid', $id)
                ->update([
                    'choice' => $option
                ]);

                $data['option'] = DB::table('replyslips_opt')->select('*')->where('oid', $id)->first();
                
                $data['option']->total = DB::table('replyslips_ans')
                ->where('oid',$id)
                ->count();

                // print_r($request->input());
                return view('layouts/ajaxRSOptionEdit')->with($data);

            

        }
        
    }
    
    public function  ajaxRSOptionDel (Request $request){

        if(Auth::check()){
            $id = $request->input('oid');
            
            $result = DB::table('replyslips_opt')->where('oid', '=', $id)->delete();
            $delans = DB::table('replyslips_ans')->where('oid', '=', $id)->delete();
            // if($result){
            echo '
            
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Success!</h4>
                The Replyslip Option  '.$request->input('oid').' has been deleted.
              </div>
             
            ';
            // }
            

        }
        
    } 
    public function  ajaxRSEdit (Request $request){

        if(Auth::check()){
            $id = $request->input('rid');
            $title = $request->input('title');
            $scope = $request->input('scope');
            $date = $request->input('date');

            
            $option = $request->input('option');
            // echo $option;die();
            DB::table('replyslips')
                ->where('id', $id)
                ->update([
                    'title' => $title,
                    'grade' => $scope,
                    'section' => $scope,
                    'date' => $date
                ]);

                $data['replyslip'] = DB::table('replyslips')->select('*')->where('id', $id)->first();
                
                

                // print_r($request->input());
                return view('layouts/ajaxRSEdit')->with($data);

            

        }

    }
    public function  ajaxRSDel (Request $request){
        

        if(Auth::check()){
            // echo "test"; die();
            $id = $request->input('rid');
            
            $result = DB::table('replyslips')->where('id', '=', $id)->delete();
            $delans = DB::table('replyslips_ans')->where('rid', '=', $id)->delete();
            $delopt = DB::table('replyslips_opt')->where('rid', '=', $id)->delete();
            // if($result){
            echo '
            
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Success!</h4>
                The Replyslip  '.$request->input('oid').' has been deleted.
                <p>all related choices and answers have been deleted as well.
              </div>
             
            ';
            // }
            

        }
        
    }   
    public function  notifyUpdate (Request $request){
        
        if(Auth::check()){

            $userid = Auth::user()->id;
            $nid = $request->input('nid');

            $check = DB::table('notify_users')->where('nid',$nid)->where('uid', $userid)->first();
            if($check){
                $update = DB::table('notify_users')
                ->where('id', $check->id)
                ->update([
                    'viewed' => 1
                    ]);
            }
        }
        // echo "test";
        // DB::table('homeworks')
        // ->where('id', $id)
        // ->update([
        //     'subject' => $subject,
        //     'grade' => $grade,
        //     'section' => $section,
        //     'teacher_id' => $teacherid,
        //     'description' => $description_clean,
        //     'pubdate' => $pubdate
        // ]);

    }   




}
