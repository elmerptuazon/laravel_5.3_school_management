<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class ViewAssets extends NavigationController
{
    //
    public function index(){
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        if (Auth::check() ) {
            $data['user'] = Auth::user();
            //query for the student info
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

            $data['filename'] = 'MTM_ApplicationForm-2014edited.pdf';

        return view('viewpdf')->with($data);
        }
    }

    public function test(Request $request, $id){
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        $data['uploadtype']='test';
        if (Auth::check() ) {
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

            $currentIdent = session('currentIdent');

            $data['test'] = DB::table('tests')
            ->select('tests.*','uploads.filename')
            ->leftJoin('uploads', 'tests.upload_id', '=', 'uploads.uid')
            ->where('tests.id', $id)
            ->first();

            // print_r($data['test']);

        if(Auth::user()->type == 's' or Auth::user()->type == 'p'){
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
        }
        elseif(Auth::user()->type == 't'){

            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->where('t_id', $data['teacheruser']->id)
           
            ->get();
        }
            // $data['filename'] = 'MTM_ApplicationForm-2014edited.pdf';

        return view('viewpdf')->with($data);

        }

    }

    public function activitysheet(Request $request, $id){
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
// echo $id; die();
        $data['uploadtype']='activitysheet';
        if (Auth::check() ) {
            if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }

            $currentIdent = session('currentIdent');

            $data['activitysheet'] = DB::table('activitysheets')
            ->select('activitysheets.*','uploads.filename')
            ->leftJoin('uploads', 'activitysheets.upload_id', '=', 'uploads.uid')
            ->where('activitysheets.id', $id)
            ->first();

            // print_r($data['test']);

        if(Auth::user()->type == 's' or Auth::user()->type == 'p'){
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
        }
        elseif(Auth::user()->type == 't'){

            $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->where('t_id', $data['teacheruser']->id)
           
            ->get();
        }
            // $data['filename'] = 'MTM_ApplicationForm-2014edited.pdf';

        return view('viewpdf')->with($data);

        }

    }

    public function handout(Request $request, $id){
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        // echo $id; die();
                $data['uploadtype']='handout';
                if (Auth::check() ) {
                    if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
        
                    $currentIdent = session('currentIdent');
        
                    $data['handout'] = DB::table('handouts')
                    ->select('handouts.*','uploads.filename')
                    ->leftJoin('uploads', 'handouts.upload_id', '=', 'uploads.uid')
                    ->where('handouts.id', $id)
                    ->first();
        
                    // print_r($data['test']);
        
                if(Auth::user()->type == 's' or Auth::user()->type == 'p'){
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
                }
                elseif(Auth::user()->type == 't'){
        
                    $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
                    $data['subjects'] = DB::table('teacher_subj')
                    ->select('subj','grade','section')
                    ->where('t_id', $data['teacheruser']->id)
                   
                    ->get();
                }
                    // $data['filename'] = 'MTM_ApplicationForm-2014edited.pdf';
        
                return view('viewpdf')->with($data);
        
                }
        
            }
        

    public function replyslip(Request $request, $id){
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        // echo $id; die();
                $data['uploadtype']='replyslip';
                if (Auth::check() ) {
                    if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
        
                    $currentIdent = session('currentIdent');
        
                    $data['replyslip'] = DB::table('replyslips')
                    ->select('replyslips.*','uploads.filename')
                    ->leftJoin('uploads', 'replyslips.upload_id', '=', 'uploads.uid')
                    ->where('replyslips.id', $id)
                    ->first();
        
                    // print_r($data['test']);
        
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
                }
                elseif(Auth::user()->type == 'x'){
        
                    $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
                    $data['subjects'] = DB::table('teacher_subj')
                    ->select('subj','grade','section')
                    ->where('t_id', $data['teacheruser']->id)
                   
                    ->get();
                }elseif(Auth::user()->type == 'a' or Auth::user()->type == 't'){
        
                    $data['teacheruser'] = DB::table('teachers')->where('id', session('currentIdent') )->first();
                    $data['subjects'] = DB::table('teacher_subj')
                    ->select('subj','grade','section')
                    ->where('t_id', $data['teacheruser']->id)
                   
                    ->get();

                    // $data['rsoptions']=DB::table('replyslips_opt')
                    // ->where('rid',$id)
                    // ->count();
                    $data['rsoptions']=DB::table('replyslips_opt')
                    ->where('rid',$id)
                    ->get();

                    $data['replyslip']->totalans = 0;
                    
                    $colors=['#00c0ef','#f56954','orange','purple','pink','olive','green','yellow'];
                    foreach($data['rsoptions'] as$key=> $option){
                       
                    $data['rsoptions'][$key]->total = DB::table('replyslips_ans')
                    ->where('oid',$option->oid)
                    ->count();
                    
                    //total answered
                    $data['replyslip']->totalans += $data['rsoptions'][$key]->total;
                    $data['rsoptions'][$key]->color = $colors[$key];
                    // ->get();
                    }
                    
                   
                    // if($data['replyslip']->grade == 'gs'){$q = "grade > 0 and grade <8";}
                    // if($data['replyslip']->grade == 'hs'){$q = "grade > 7 and grade < 13";}
                    // if($data['replyslip']->grade == 'na'){$q = "grade > 0 and grade < 13";}
                    // if($data['replyslip']->grade == 'gs'){$q1 =0; $q2=8;}
                    // if($data['replyslip']->grade == 'hs'){$q1 =7; $q2=13;}
                    // if($data['replyslip']->grade == 'na'){$q1 =0; $q2=13;}

                // $data['replyslip']->total = DB::table('students')
                    // ->whereRaw("grade > ? and grade < ?")
                    // ->whereRaw($q)
                    // ->count();
                    $data['replyslip']->total = DB::table('students')
                    ->count();

                    $data['replyslip']->totalunans = $data['replyslip']->total - $data['replyslip']->totalans;
                
                    return view('aviewrs')->with($data);
                }
                    // $data['filename'] = 'MTM_ApplicationForm-2014edited.pdf';
        
                return view('viewpdf')->with($data);
        
                }
        
            }

}
