<?php

namespace App\Http\Controllers;

use App;
use Auth;
use DB;
use PDF;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class TeacherFetchJSON extends NavigationController
{
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
            // ->where('tid', $inputData['task_teacher_id'])
            ->first();
            
            if($checkResult == NULL || $checkResult == '') {

                $addInputData = DB::table('task_student')->insert(
                    [
                        'sid'=>$val['ID'],
                        'taskid'=>$inputData['task_name'],
                        'tid'=>$inputData['task_teacher_id'],
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
                // ->where('tid', $inputData['task_teacher_id'])
                ->update([
                    'tid'=>$inputData['task_teacher_id'],
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

    $data['get_final_grade_list'] = $this->finalGradeListFromGradeSection($inputData['grade_clicked'], $inputData['section_clicked']);

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
                        'transmuted'=>$this->transmuteGradesHere($val[$i])
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
                        'transmuted'=>$this->transmuteGradesHere($val[$i]),
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

    public function editStudentScore(Request $request) {
        
        $inputData['edit_task_id'] = $request->input('edit_task_id');
        $inputData['edit_task_title'] = $request->input('edit_task_title');
        $inputData['edit_task_type'] = $request->input('edit_task_type');
        $inputData['edit_task_total_points'] = $request->input('edit_task_total_points');
        $inputData['edit_task_period'] = $request->input('edit_task_period');
        $inputData['edit_task_school_year'] = $request->input('edit_task_school_year');

        $editInputData = DB::table('tasks')
        ->where('id',$inputData['edit_task_id'])
        ->update([
            'task_type'=>$inputData['edit_task_type'],
            'task_title'=>$inputData['edit_task_title'],
            'task_total_points'=>$inputData['edit_task_total_points'],
            'period'=>$inputData['edit_task_period'],
            'school_year'=>$inputData['edit_task_school_year']
        ]);

        if($editInputData){
            $sucessfullyedited = "Task successfully edited";
        }elseif(!$editInputData){
            $notsuccess = "Please check all input details";
        };

        if(isset($sucessfullyedited)) {
            return redirect()->back()->with('message', $sucessfullyedited);
        }
        else {
            return redirect()->back()->with('error', $notsuccess);
        }

    }

    public function deleteStudentScore(Request $request) {
        
        $inputData['delete_task_grade'] = $request->input('delete_task_grade');
        $inputData['delete_grade'] = $request->input('delete_grade');
        $inputData['delete_subject'] = $request->input('delete_subject');
        $inputData['delete_section'] = $request->input('delete_section');

        $deleteInputData = DB::table('tasks')
        ->where('id',$inputData['delete_task_grade'])
        ->delete();

        $deleteStudentScoreData = DB::table('task_student')
        ->where('taskid',$inputData['delete_task_grade'])
        ->delete();

        if($deleteInputData){
            $sucessfullyedited = "Task successfully deleted";
        }elseif(!$deleteInputData){
            $notsuccess = "Task is not deleted";
        };

        if(isset($sucessfullyedited)) {
            if(Auth::user()->type == 't') {
                return redirect('tgrading/list/'.$inputData['delete_subject'].'/'.$inputData['delete_grade'].'-'.$inputData['delete_section'])->with('message', $sucessfullyedited);
            } elseif(Auth::user()->type == 'a') {
                return redirect('agrading/list/'.$inputData['delete_subject'].'/'.$inputData['delete_grade'].'-'.$inputData['delete_section'])->with('message', $sucessfullyedited);
            }
        }
        else {
            if(Auth::user()->type == 't') {
                return redirect('tgrading/list/'.$inputData['delete_subject'].'/'.$inputData['delete_grade'].'-'.$inputData['delete_section'])->with('error', $sucessfullyedited);
            } elseif(Auth::user()->type == 'a') {
                return redirect('agrading/list/'.$inputData['delete_subject'].'/'.$inputData['delete_grade'].'-'.$inputData['delete_section'])->with('error', $sucessfullyedited);
            }
            
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
            ->select('id', 'type_name', 'task_title')
            ->where('subject', $subj)
            ->get();

            $data['student_task_list'] = DB::table('tasks')
            ->select('id', 'task_total_points','task_type', 'task_title', 'period', 'school_year')
            ->where('id', $id)
            ->first();

            if(!isset($data['student_task_list'])) {
                return redirect('/tgrading/list/'.$subj.'/'.$grade.'-'.$section)->with('error', 'Task deleted. Please contact admin.');
            }

            $data['student_existing_score'] = DB::table('task_student')
            ->join('students', 'task_student.sid','=','students.id')
            ->select('task_student.score', 'task_student.sid', 'students.firstname', 'students.lastname', 'task_student.status')
            ->where('taskid', $id)
            // ->where('tid', $data['teacheruser']->id)
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
            return view('tgradingexist')->with($data);

        }
      }

      public function addStudentCharacterScore(Request $request) {
            $inputData['qualitative_scores_behavior'] = $request->input('qualitative_scores_behavior');
            $inputData['qualitative_scores_collaborative'] = $request->input('qualitative_scores_collaborative');
            $inputData['qualitative_scores_quality'] = $request->input('qualitative_scores_quality');
            $inputData['qualitative_scores_self'] = $request->input('qualitative_scores_self');
            $inputData['qualitative_school_year'] = $request->input('qualitative_school_year');
            $inputData['qualitative_period'] = $request->input('qualitative_period');
            $inputData['qualitative_student'] = $request->input('qualitative_student');

            //get only id
            $inputData['qualitative_student'] = explode("/",$inputData['qualitative_student']);
            
            if($inputData['qualitative_scores_behavior'][0]['behavior'] == null) {
                return redirect()->back()->with('error', 'Please click search first before submitting a grade');
                
            }
            $inputArray = [];

            foreach($inputData['qualitative_scores_behavior'] as $val) {
                array_push($inputArray, (object)[
                    'score'=>$val['behavior'],
                    'id'=>$val['behaviorid']
                ]);
                
            }
            foreach($inputData['qualitative_scores_collaborative'] as $val) {
                array_push($inputArray, (object)[
                    'score'=>$val['collaborative'],
                    'id'=>$val['collaborativeid']
                ]);
                
            }
            foreach($inputData['qualitative_scores_quality'] as $val) {
                array_push($inputArray, (object)[
                    'score'=>$val['quality'],
                    'id'=>$val['qualityid']
                ]);
                
            }
            foreach($inputData['qualitative_scores_self'] as $val) {
                array_push($inputArray, (object)[
                    'score'=>$val['self'],
                    'id'=>$val['selfid']
                ]);
                
            }
            // echo json_encode($inputData)."<br />";
            foreach($inputArray as $val) {
                
                $checkResult = DB::table('qualitative_scores')
                ->where('sid', $inputData['qualitative_student'])
                ->where('period',$inputData['qualitative_period'])
                ->where('school_year',$inputData['qualitative_school_year'])
                ->where('qid', $val->id)
                ->where('period', $inputData['qualitative_period'])
                ->where('school_year',$inputData['qualitative_school_year'])
                ->first();
                
                if($checkResult == '' || $checkResult == NULL) {
                    $addData = DB::table('qualitative_scores')->insertGetId(
                        [
                        'sid'=>$inputData['qualitative_student'][0],
                        'score'=>$val->score,
                        'period'=>$inputData['qualitative_period'],
                        'school_year'=>$inputData['qualitative_school_year'],
                        'qid'=>$val->id
                    ]
                );
               
                    if($addData){
                        $sucessfullyedited = "Character Development successfully added";
                    }elseif(!$addData){
                        $notsuccess = "Character Development was not successfully added";
                    };
                }
                
                else {
                    $addData = DB::table('qualitative_scores')
                    ->where('sid', $inputData['qualitative_student'])
                    ->where('period',$inputData['qualitative_period'])
                    ->where('school_year',$inputData['qualitative_school_year'])
                    ->where('qid',$val->id)
                    ->update([
                        'score'=>$val->score,
                    ]);
                    if($addData){
                        $sucessfullyedited = "Character Development successfully edited";
                    }elseif(!$addData){
                        $notsuccess = "Character Development was not successfully edited";
                    };
                }
            
            }

            if(isset($sucessfullyedited)) {
                return redirect()->back()->with('message', $sucessfullyedited);
            }
            else {
                return redirect()->back()->with('error', $notsuccess);
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
            ->select('id', 'type_name', 'task_title')
            ->where('subject', $data['subject'])
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

                $data['grading_teacher_edit'] = DB::table('config')
                ->select('status')
                ->where('config_name', 'grading_teacher_edit')
                ->first();

                // return response()->json($data);
                return view('tgradinglist')->with($data);
            }
            else {
                $data['grading_teacher_edit'] = DB::table('config')
                ->select('status')
                ->where('config_name', 'grading_teacher_edit')
                ->first();
            // echo json_encode($data);
                return view('tgradinglist')->with($data);
            }

        }

    }

    public function finalGradeComputation(Request $request) {
        $inputData['student'] = $request->input('student_id');
        $inputData['student_grade'] = $request->input('student_grade');
        $inputData['student_section'] = $request->input('student_section');

        $inputData['student_subjects_list'] = DB::table('tasks')
        ->select('task_subject','period','task_type')
        ->groupBy('task_subject','period','task_type')
        ->where('task_grade',$inputData['student_grade'])
        ->where('task_section',$inputData['student_section'])
        ->get();


        foreach($inputData['student_subjects_list'] as $val) {
            // $inputData['student_scores_list']['2019-2020'][$val->task_subject][$val->period.'_period'][$val->task_type] = DB::table('task_student')
            // ->leftJoin('tasks','task_student.taskid','=','tasks.id')
            // ->leftJoin('task_type','task_type.type_name','=','tasks.task_type')
            // ->select(DB::raw('SUM(task_student.score) as totalScores'),DB::raw('task_type.weight'),DB::raw('SUM(tasks.task_total_points) as totalPoints'))
            // ->where('task_student.sid',$inputData['student'])
            // ->where('tasks.task_subject',$val->task_subject)
            // ->where('task_student.period', $val->period)
            // ->where('task_student.school_year', '2019-2020')
            // ->where('tasks.task_type',$val->task_type)
            // ->groupBy('task_type.weight')
            // ->get();

            //***CHECK DATA IN DETAILS */
            $inputData['student_scores_list']['2019-2020'][$val->task_subject][$val->period.'_period'][$val->task_type] = DB::table('task_student')
            ->leftJoin('tasks','task_student.taskid','=','tasks.id')
            ->leftJoin('task_type','task_type.type_name','=','tasks.task_type')
            ->select('task_student.score','task_student.taskid','tasks.task_subject','task_type.weight','tasks.task_total_points','task_student.period')
            ->where('task_student.sid',$inputData['student'])
            ->where('tasks.task_subject',$val->task_subject)
            ->where('task_student.period', $val->period)
            ->where('task_student.school_year', '2019-2020')
            ->where('tasks.task_type',$val->task_type)
            ->get();
        }

        return response()->json($inputData);
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
            ->where('t_id', $data['teacheruser']->id)
            ->get();

            $data['grade_section'] = DB::table('teacher_subj')
            ->select('grade','section')
            ->where('t_id', $data['teacheruser']->id)
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
                            $data['student_with_subject_list'][$student_id_list->firstname.' '.$student_id_list->lastname][$final_scores->subject] = $this->transmuteGradesHere($final_scores->score);
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
                array_push($data['general_avg_list'][$student_id_list->firstname.' '.$student_id_list->lastname], $this->transmuteGradesHere($data['general_avg_per_period'][$student_id_list->firstname.' '.$student_id_list->lastname][$subject_name_gen_avg->subject][0]));
            }
        }

        foreach($data['student_id_list'] as $student_id_list) {
            $data['general_list_value'][$student_id_list->firstname.' '.$student_id_list->lastname] = round((array_sum($data['general_avg_list'][$student_id_list->firstname.' '.$student_id_list->lastname])/$data['students_subjects_fixed_count']),2);
        }
    }
   

    /**
     * END OF COMPUTING GENERAL AVERAGE
     */
            
            return view('tgradingview')->with($data);
        }else{
            Auth::logout();
            return redirect('/login');
    }

        
    }

    public function viewReportCard(Request $request, $grade='', $section='', $studentid='') {

        $data['grade_clicked'] = $grade;
        $data['section_clicked'] = $section;
        $data['studentid_clicked'] = $studentid;
        $data['student_firstname'] = '';
        $data['student_lastname'] = '';
        $data['student_age'] = '';
    
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set

        $data['page_title'] = 'Report Card';
        $currentIdent = $request->session()->get('currentIdent','default');
                $data['user'] = Auth::user();
                
        $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();
        
        if(Auth::user()->type == 't') {
            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->where('t_id', $data['teacheruser']->id)
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();
        } else if(Auth::user()->type == 'a') {
            $data['subjects'] = DB::table('teacher_subj')
            ->select('subj','grade','section')
            ->groupBy('subj','grade','section')
            ->orderBy('grade')
            ->orderBy('subj')
            ->get();
        }
        

        $data['grade_section'] = DB::table('teacher_subj')
        ->select('grade','section')
        ->where('t_id', $data['teacheruser']->id)
        ->groupBy('grade','section')
        ->get();
        
        //added by elmer

        //used convert to binary in order that groupBy will be case sensitive
        $data['student_list_grade'] = DB::table('students')
        ->select('grade as new_grade','section as new_section')
        // ->select(DB::raw('CONVERT(grade, BINARY) as new_grade, CONVERT(section,BINARY) as new_section'))
        ->groupBy('new_grade','new_section')
        ->whereNotIn('grade',['n'])
        ->get();

        $data['student_name_list'] = DB::table('students')
        ->select('id','firstname', 'lastname','grade','section')
        ->orderBy('lastname')
        ->orderBy('grade')
        ->orderBy('section')
        ->whereNotIn('grade',['n'])
        ->get();

        $data['student_name'] = DB::table('students')
        ->select('id','firstname', 'lastname','grade','section','gender')
        ->where('id', $studentid)
        ->take(1)
        ->get();
        
        foreach($data['student_name'] as $val) {
            $data['student_firstname'] = $val->firstname;
            $data['student_lastname'] = $val->lastname;
            $data['student_gender'] = $val->gender;
        }

    $data['studentid'] = DB::table('students')
    ->select('id')
    ->where('id', $studentid)
    ->where('grade', $grade)
    ->where('section', $section)
    ->first();
    
    $data['period_list'] = array('1','2','3','4');

        //START OF COMPUTING FINAL GRADE*********
        $data['subject_list'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $grade)
        ->where('section', $section)
        ->whereNotIn('subject',['homeroom'])
        ->orderBy('rankorder')
        ->get();

        $data['subject_list_count'] = DB::table('subjects')
        ->where('grade', $grade)
        ->where('section', $section)
        ->whereNotIn('subject',['homeroom'])
        ->count();

        $data['students_subjects_fixed_count'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $grade)
        ->where('section', $section)
        ->whereNotIn('subject',['homeroom', 'language', 'reading', 'music', 'arts', 'pe', 'health', 'penmanship'])
        ->count();

        $data['students_subjects_selected'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $grade)
        ->where('section', $section)
        ->orderBy('rankorder')
        ->whereNotIn('subject',['homeroom', 'language', 'reading', 'music', 'arts', 'pe', 'health', 'penmanship'])
        ->get();
        
        if($grade != '' && $studentid != '') {

        $data['task_for_checking'] = DB::table('tasks')
        ->select('id')
        ->where('task_grade', $grade)
        ->where('task_section', $section)
        ->first();
        
        if(!isset($data['task_for_checking'])) {
            return redirect()->back()->with('error', 'No grades been made. Please create task.');
        }
        
        $data['final_grade_list_from_new_db'] = DB::table('final_grade_list')
        ->where('grade', $grade)
        ->where('section', $section)
        ->where('sid', $studentid)
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
                array_push($data['list_of_final_grade'][$subject_list->subject], $data['subject_list_with_period'][$subject_list->subject][$i][0]);
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

    }

    //END OF COMPUTING FINAL GRADE

    /**
     * START OF TRANSMUTING GRADE
     */


  

    //TRANSMUTING GRADES WITH FINAL GRADES
    if(isset($data['subject_list_with_period'])) {
        foreach($data['subject_list_with_period'] as $subject=>$list) {
            for($i = 1; $i<=5; $i++) {
            if($i ==4){
                $data['transmuted_final_grades'][$subject][$i] = $this->transmuteGradesSpecial($list[$i][0]);   
            }else{
                $data['transmuted_final_grades'][$subject][$i] = $this->transmuteGradesHere($list[$i][0]);   
            }
            }
        }
    }

    //END OF TRANSMUTING GRADES WITH FINAL GRADES

    //START OF TRANSMUTING GENERAL AVERAGE
    if(isset($data['sum_up_general_average'])) {
        foreach($data['sum_up_general_average'] as $key=>$final) {
            if($key ==4){
            $data['transmuted_general_average'][$key] = $this->transmuteGradesSpecial($final[0]);
            }else{
            $data['transmuted_general_average'][$key] = $this->transmuteGradesHere($final[0]);
            }
        }
        $data['final_general_average'] = round(array_sum($data['transmuted_general_average']) / count($data['transmuted_general_average']));
    }
    // echo "<pre>";print_r($data['transmuted_general_average']);echo "</pre>"; die();
    //END OF TRANSMUTING GENERAL AVERAGE
    

    /**
     * END OF TRANSMUTING GRADE
     */
    
        $student_list = DB::table('students')
        ->select('id','lastname')
        ->where('grade', $grade)
        ->where('section', $section)
        ->orderBy('lastname')
        ->get();
        

        $period_list = array('1','2','3','4');
        $data['period_list_arr'] = array('1','2','3','4');    



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
        ->where('sid', $studentid)
        ->where('school_year', '2019-2020')
        ->get();
        
        foreach($student_list as $student) {
            foreach($data['task_qualitative_types'] as $types) {
                foreach($period_list as $period) {
                    $data['task_qualitative_score_array'][$student->id][$types->id][$period] = '';
                }
            }
        }

        foreach($student_list as $student) {
            foreach($data['task_qualitative_types'] as $types) {
                foreach($period_list as $period) {
                    foreach($data['task_qualitative_scores'] as $score) {
                        if($student->id === $score->sid) {
                            if($types->id == $score->qid) {
                                if($period == $score->period) {
                                    $data['task_qualitative_score_array'][$student->id][$types->id][$period] = $score->score;
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

    if($studentid != '') {
        $data['total_days_currentyear'][$studentid] = DB::table('attendance')
        ->select(DB::raw('COUNT(status) as student_total'))
        ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
        ->where('sid', $studentid)
        ->get();

        $data['total_present_currentyear'][$studentid] = DB::table('attendance')
        ->select(DB::raw('COUNT(status) as student_present'))
        ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
        ->where(function ($query) {
            $query->orWhere('status', 'present')
                  ->orWhere('status', 'late');
        })
        ->where('sid', $studentid)
        ->get();

        $data['total_absent_currentyear'][$studentid] = DB::table('attendance')
        ->select(DB::raw('COUNT(status) as student_absent'))
        ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
        ->where('sid', $studentid)
        ->where('status', 'absent')
        ->get();

        $data['list_of_months'] = DB::table('attendance')
        ->select(DB::raw('MONTH(date) as month_name'))
        ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
        ->groupBy(DB::raw('MONTH(date)'))
        ->where('sid', $studentid)
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
            $data['total_attendance'][$studentid][$month_no] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_total'))
            ->where(DB::raw('MONTH(date)'), $month_no)
            ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
            ->where('sid', $studentid)
            ->get();

            $data['total_present'][$studentid][$month_no] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_total'))
            ->where(DB::raw('MONTH(date)'), $month_no)
            ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
            ->where('sid', $studentid)
            ->where(function ($query) {
                $query->orWhere('status', 'present')
                      ->orWhere('status', 'late');
            })
            ->get();

            $data['total_absent'][$studentid][$month_no] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_total'))
            ->where(DB::raw('MONTH(date)'), $month_no)
            ->whereBetween('date',[$data['fixed_startdate']->school_date, $data['fixed_enddate']->school_date])
            ->where('sid', $studentid)
            ->where('status', 'absent')
            ->get();
        }
        
    }
    

    //END OF ATTENDANCE

    
        
        // return response()->json($data);
        return view('tgradingreportcard')->with($data);
        }else{
            Auth::logout();
            return redirect('/login');
        }

    
    }

    public function viewGradesInPdf(Request $request, $grade=0,$section=0) {
        $data['grade_clicked'] = $grade;
        $data['section_clicked'] = $section;
    
        $data['notifications'] = $this->notificationsListHeaderNav();
        $data['notificationsUnreadCount'] = $this->notificationsUnreadCount();
        if (Auth::check() ) {
                //check if session is set
                if(!$request->session()->has('currentIdent')){ Auth::logout();return redirect('/login'); }
                //end check is session is set

        $data['page_title'] = 'Report Card';
        $currentIdent = $request->session()->get('currentIdent','default');
                $data['user'] = Auth::user();
                
        $data['teacheruser'] = DB::table('teachers')->where('id', $currentIdent )->first();

        $data['subjects'] = DB::table('teacher_subj')
        ->select('subj','grade','section')
        ->where('t_id', $data['teacheruser']->id)
        ->get();

        $data['grade_section'] = DB::table('teacher_subj')
        ->select('grade','section')
        ->where('t_id', $data['teacheruser']->id)
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
            

            $period_list = array('1','2','3','4');
            $data['period_list'] = $period_list;

            $task_type_name = DB::table('task_type')
            ->select('type_name','task_title','weight')
            ->get();

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
            
            foreach($student_name as $student) {
                
                foreach($data['students_subjects'] as $subject) {

                    foreach($task_type_name as $task_type) {
                        $weight = $task_type->weight;
                        $totalScore = $task_type->type_name;
                        $totalPoints = $task_type->task_title;
                        $$totalScore = 0;
                        $$totalPoints = 0;
                        
                        foreach($period_list as $period) {
                            foreach($tasks_score_list as $score) {
                                //filter all tasks
                                if($student->id == $score->sid) {
                                    if($subject->subject == $score->task_subject) {
                                        
                                            if($task_type->type_name == $score->task_type){ 
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

                            $data['task_score_list'][$student->id][$subject->subject][$period][$task_type->type_name] = $compute_score_list;

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
                                                        array_push($arr, $kval[$task_type->type_name]);
                                                        
                                                    }
                                                    $data['final_scores'][$student->lastname.', '.$student->firstname][$key_subj][$key_period] = round(array_sum($arr),2);
                                                    
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

            $outputPDF = '
                <html>
                <head>
                    <meta http-equiv='.'Content-Type'.' content='.'text/html; charset=utf-8'.' />
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
                </head>
                <body style="font-family: Calibri, sans-serif; font-size: 11px;">
            ';


            foreach($data['final_scores'] as $student_name_key => $student_name_val) {
            
            $outputPDF .= '
                <div style="padding-bottom:50px;">
                <div style="margin-top=0px; padding-top=0px;">
                    <div style="display:inline-block; width:20%; margin:0px; padding:0px;">
                        
                    </div>
                    <div style="display:inline-block; width:70%; margin:0px 0px 0px 20px; padding:0px;">
                        <p style="text-align: center;"><strong>Marcelli School of Antipolo</strong></p>
                        <p style="text-align: center;"><strong> No. 6 Marigman Street,Brgy San Roque Antipolo City, 1870 Rizal</strong></p>
                        <p style="text-align: center;"><strong>Report Card</strong></p>
                    </div>
                </div>

                <div style="border-top: 1.5px solid black; border-bottom: 1.5px solid black; margin-top:-20px; padding: 0px;">
                    <table style="width:100%">
                        <tr>
                            <th>Student</th>
                            <td><strong>'.$student_name_key.'</strong></td>
                            <th></th>
                            <td></td>
                            <th><strong>Academic</strong></th>
                            <td><strong>2019-2020</strong></td>
                        </tr>
                        <tr>
                            <th>Grade</th>
                            <td><strong>'.$grade.'-'.$section.'</strong></td>
                            <th></th>
                            <td></td>
                            <th>Term</th>
                            <td><strong>All Quarters</strong></td>
                        </tr>
                        <tr>
                            <th>Age</th>
                            <td></td>
                            <th>LRN</th>
                            <td><strong>402910150056</strong></td>
                            <th>Gender</th>
                            <td></td>
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
              <tbody>';

              foreach($student_name_val as $subject_key => $subject_val) {
                    $outputPDF .= '<tr>
                                    <td style="text-indent: 5px;" class="tableWithBordersTD"><small>'.$subject_key.'</small></td>
                                    <td class="tableWithBordersTD centerValue">'.$subject_val['1'].'</td>
                                    <td class="tableWithBordersTD centerValue">'.$subject_val['2'].'</td>
                                    <td class="tableWithBordersTD centerValue">'.$subject_val['3'].'</td>
                                    <td class="tableWithBordersTD centerValue">'.$subject_val['4'].'</td>
                                    <td class="tableWithBordersTD centerValue">'.round(($subject_val['1']+$subject_val['2']+$subject_val['3']+$subject_val['4'])/4,2).'</td>
                                    </tr>';
                }

                $outputPDF .= '
              </tbody>
            </table>
            </div>
            <div style="padding-top:15px">
                <table class="tableWithBorders" style="width:100%;">
                <thead class="headerFontSize">
                    <tr>
                    <th style="font-size:13px; text-indent: 1px;" class="tableWithBordersTH">GRADE SCALE</th>
                    <th class="tableWithBordersTH centerValue">A</th>
                    <th class="tableWithBordersTH centerValue">P</th>
                    <th class="tableWithBordersTH centerValue">AP</th>
                    <th class="tableWithBordersTH centerValue">D</th>
                    <th class="tableWithBordersTH centerValue">B</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td class="tableWithBordersTD">Description</td>
                    <td class="tableWithBordersTD centerValue">Advance 90-above</td>
                    <td class="tableWithBordersTD centerValue">Proficient 85-89</td>
                    <td class="tableWithBordersTD centerValue">Approaching Proficiency 80-84</td>
                    <td class="tableWithBordersTD centerValue">Developing 75-79</td>
                    <td class="tableWithBordersTD centerValue">Beginning 74-below</td>
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
                    ';
                        foreach($data['task_qualitative_category'] as $category) {
                                $outputPDF .= '<tr>
                                    <td style="text-indent: 5px;" class="tableWithBordersTD"><strong>'.str_replace('_',' ',$category->category).'</strong></td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    </tr>';
                            foreach($data['task_qualitative_types'] as $types) {
                                if($category->category == $types->category) {
                                    
                                    $outputPDF .= '<tr><td style="text-indent: 30px;" class="tableWithBordersTD">'.$types->type_name.'</td>';

                                    foreach($data['task_qualitative_score_array'] as $student_id_key=>$student_period) {
                                        foreach($student_period as $key_qid=>$val_period) {
                                            foreach($val_period as $key_period=>$score) {
                                                foreach($period_list as $period) {
                                                    if($student_name_key == $student_id_key) {
                                                        if($types->id == $key_qid) {
                                                            if($period == $key_period) {
                                                                $outputPDF .= '<td class="tableWithBordersTD centerValue">'.$score.'</td>';
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                            }
                                        }
                                       
                                    }
                                    $outputPDF .= '</tr>';     

                                }
                                
                            }
                        }
                    

                        $outputPDF .= '

                </tbody>
            </table>
            </div>
            <div style="padding-top:15px">
            <table class="tableWithBorders" style="width:100%;">
                <thead>
                  <tr>
                    <th style="font-size:13px; text-indent: 1px;" class="tableWithBordersTH"><strong>Attendance Record</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Jun</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Jul</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Aug</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Sep</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Oct</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Nov</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Dec</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Jan</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Feb</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Mar</strong></th>
                    <th class="tableWithBordersTH centerValue"><strong>Total</strong></th>
                  </tr>
                </thead>
                <tbody class="bodyFontStyle">
                  <tr>
                    <td class="tableWithBordersTD">Days of School</td>
                    <td class="tableWithBordersTD centerValue">3</td>
                    <td class="tableWithBordersTD centerValue">24</td>
                    <td class="tableWithBordersTD centerValue">25</td>
                    <td class="tableWithBordersTD centerValue">25</td>
                    <td class="tableWithBordersTD centerValue">23</td>
                    <td class="tableWithBordersTD centerValue">21</td>
                    <td class="tableWithBordersTD centerValue">15</td>
                    <td class="tableWithBordersTD centerValue">22</td>
                    <td class="tableWithBordersTD centerValue">24</td>
                    <td class="tableWithBordersTD centerValue">26</td>
                    <td class="tableWithBordersTD centerValue">211</td>
                  </tr>
                  <tr>
                    <td class="tableWithBordersTD">Days Present</td>
                    <td class="tableWithBordersTD centerValue">3</td>
                    <td class="tableWithBordersTD centerValue">24</td>
                    <td class="tableWithBordersTD centerValue">25</td>
                    <td class="tableWithBordersTD centerValue">25</td>
                    <td class="tableWithBordersTD centerValue">23</td>
                    <td class="tableWithBordersTD centerValue">21</td>
                    <td class="tableWithBordersTD centerValue">15</td>
                    <td class="tableWithBordersTD centerValue">22</td>
                    <td class="tableWithBordersTD centerValue">24</td>
                    <td class="tableWithBordersTD centerValue">26</td>
                    <td class="tableWithBordersTD centerValue">211</td>
                  </tr>
                  <tr>
                    <td class="tableWithBordersTD">Days Absent</td>
                    <td class="tableWithBordersTD centerValue">0</td>
                    <td class="tableWithBordersTD centerValue">0</td>
                    <td class="tableWithBordersTD centerValue">0</td>
                    <td class="tableWithBordersTD centerValue">0</td>
                    <td class="tableWithBordersTD centerValue">0</td>
                    <td class="tableWithBordersTD centerValue">1</td>
                    <td class="tableWithBordersTD centerValue">0</td>
                    <td class="tableWithBordersTD centerValue">0</td>
                    <td class="tableWithBordersTD centerValue">0</td>
                    <td class="tableWithBordersTD centerValue">0</td>
                    <td class="tableWithBordersTD centerValue">1</td>
                  </tr>
                </tbody>
            </table>
            </div>
            </div>';
            }

            $outputPDF .= '</body></html>';
        // return response()->json($data);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($outputPDF);

        return $pdf->stream();

            
        }else{
            Auth::logout();
            return redirect('/login');
        }
        
    }

    public function viewAllTasksBySection(Request $request, $subj=NULL, $grade=NULL, $section=NULL, $period=NULL) {

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
            ->where('t_id', $data['teacheruser']->id)
            ->get();

            $data['grade_section'] = DB::table('teacher_subj')
            ->select('grade','section')
            ->where('t_id', $data['teacheruser']->id)
            ->groupBy('grade','section')
            ->get();

            //added by elmer
            
            
            $data['tasks_subject_list'] = DB::table('teacher_subj')
            ->select('subj')
            ->groupBy('subj')
            ->whereNotIn('subj',['homeroom'])
            ->where('t_id', $data['teacheruser']->id)
            ->get();

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
                    return redirect('/fetch/tasks/grades')->with('error', 'No grades exists. Please check details.');
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
                $data['student_list_for_final_grade'][$student_id_list->id] = [];

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

            /**
             * START TRANSMUTING FINAL GRADE
             */
            if(isset($data['student_list_for_final_grade'])) {
                foreach($data['student_list_for_final_grade'] as $studentid=>$final) {
                    $data['transmuting_final_grade'][$studentid] = $this->transmuteGradesHere($final);
                }
            }
            

             /**
              * END TRANSMUTING FINAL GRADE
              */

            // return response()->json($data);
            return view('tgradingviewalltasks')->with($data);
        }else{
            Auth::logout();
            return redirect('/login');
    }

    
}

//mobile json for grading

public function mobileViewTaskGrades(Request $request, $grade='', $section='', $sid='', $period='', $subj='') {

    $data['studentid'] = DB::table('students')
    ->select('id')
    ->where('id', $sid)
    ->where('grade', $grade)
    ->where('section', $section)
    ->first();

    if(!isset($data['studentid'])) {
        return response()->json('Cannot find student. Please check again.');
    } else {

        $data['get_task_id'] = DB::table('tasks')
        ->select('id','task_type','task_total_points', 'task_title')
        ->where('task_subject', $subj)
        ->where('period', $period)
        ->where('task_grade', $grade)
        ->where('task_section', $section)
        ->orderByRaw("FIELD(task_type , 'WW', 'PT', 'QA')")
        ->get();
        
        $data['array_task'] = [];
        foreach($data['get_task_id'] as $get_task_id) {
            $task_list = DB::table('task_student')
            ->select('score', 'status', 'taskid') 
            ->where('taskid', $get_task_id->id)
            ->where('sid', $sid)
            ->whereNotIn('status',['excused'])
            ->get();

            if(isset($task_list[0])) {
                array_push($data['array_task'], (object)array(
                    'id'=>$task_list[0]->taskid,
                    'score'=>$task_list[0]->score,
                    'status'=>$task_list[0]->status
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
                        'Task Total'=>$get_task_id->task_total_points
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
                'Task_Title'=>$merge_task_list->{'Task Title'},
                'Task_Score'=>$merge_task_list->{'Task Score'},
                'Task_Total'=>$merge_task_list->{'Task Total'}
            ));
        }

        $dataToSend['Total_Scores'] = [];
        $data['final_grade'] = null;
        foreach($data['task_type_list'] as $task_type_list) {
            array_push($dataToSend['Total_Scores'], array(
                $task_type_list->type_name.'Total'=>$data['task_type_sub_total'][$task_type_list->type_name]
            ));
        }
        $total = array_sum($data['total_score']);
        
        
        array_push($dataToSend['Total_Scores'], (object)array("Final_Tent_Grade"=>round($total,2),'Final_Transmuted_Grade'=>$this->transmuteGradesHere($total)));
        $data['final_grade'] = $total;

        $reportcard_view_teacher = DB::table('config')
        ->select('status')
        ->where('config_name', 'report_card_teacher')
        ->first();

        $reportcard_view_parent = DB::table('config')
        ->select('status')
        ->where('config_name', 'reportcard_view_parent')
        ->first();

        $dataToSend['reportcard_view_teacher'] = $reportcard_view_teacher->status;
        $dataToSend['reportcard_view_parent'] = $reportcard_view_parent->status;

        return response()->json($dataToSend);
    }
}

public function mobileViewFinalGrades(Request $request, $grade='', $section='', $sid='') {
    $data['studentid'] = DB::table('students')
    ->select('id')
    ->where('id', $sid)
    ->where('grade', $grade)
    ->where('section', $section)
    ->first();

    $dataToSend['reportcard_view_teacher'] = DB::table('config')
    ->select('status')
    ->where('config_name', 'report_card_teacher')
    ->first();

    $dataToSend['reportcard_view_parent'] = DB::table('config')
    ->select('status')
    ->where('config_name', 'reportcard_view_parent')
    ->first();

    $data['period_list'] = array('1','2','3','4');

    if(!isset($data['studentid'])) {
        return response()->json('Cannot find student. Please check Student ID.');
    } else {
        //START OF COMPUTING FINAL GRADE*********
        $data['subject_list'] = DB::table('subjects')
        ->select('subject')
        ->where('grade', $grade)
        ->where('section', $section)
        ->whereNotIn('subject',['homeroom'])
        ->orderBy('rankorder')
        ->get();

        $data['final_grade_list_from_db'] = DB::table('final_grade_list')
        ->where('sid', $sid)
        ->orderBy('subject')
        ->orderBy('period')
        ->get();

        $data['checker'] = DB::table('final_grade_list')
        ->where('sid', $sid)
        ->first();

        if(!isset($data['checker'])) {
            $noData = array(
                'results'=>'Not computed yet. Please contact admin.',
                'reportcard_view_teacher'=>$dataToSend['reportcard_view_teacher']->status,
                'reportcard_view_parent'=>$dataToSend['reportcard_view_parent']->status
            );
        
            return response()->json($noData);
        }

        foreach($data['subject_list'] as $subject_name) {
            foreach($data['final_grade_list_from_db'] as $list_db) {
                if($subject_name->subject == $list_db->subject) {
                    if($list_db->period == 4){
                        $data['final_grade'][$list_db->subject]['Q'.$list_db->period.' grade'] = $this->transmuteGradesSpecial($list_db->score);
                    }else{
                        $data['final_grade'][$list_db->subject]['Q'.$list_db->period.' grade'] = $this->transmuteGradesHere($list_db->score);
                    }
                }
            }
        }
        
        //END OF COMPUTING FINAL GRADE********************

        //GET FINAL GRADE AND GENERAL AVG

        $data['final_grade_and_general_avg_list'] = $this->finalGradeListFromGradeSection($grade,strtolower($section));

        for($i=1; $i<=4; $i++) {
            if($i == 4){
                $data['general_avg_of_student'][$i] = $this->transmuteGradesSpecial($data['final_grade_and_general_avg_list']['general_avg'][$sid][$i]);
            }else{
                $data['general_avg_of_student'][$i] = $this->transmuteGradesHere($data['final_grade_and_general_avg_list']['general_avg'][$sid][$i]);
            }
        }
        

        foreach($data['subject_list'] as $subject_name) {
            foreach($data['final_grade_and_general_avg_list']['final_grade'][$sid] as $subject=>$val) {
                if($subject_name->subject == $subject) {
                    $data['final_grade'][$subject_name->subject]['final_grade'] = $this->transmuteGradesHere($val[5]);
                }
            }
        }

        //END FINAL GRADE AND GENERAL AVG
        
        //START OF BEHAVIOR SCORES

        $data['get_category_titles'] = DB::table('qualitative_types')
        ->orderBy('category')
        ->get();

        $data['student_qualitative_scores'] = DB::table('qualitative_scores')
        ->select('score', 'period','qid')
        ->where('sid', $sid)
        ->orderBy('period')
        ->get();

        for($i = 1; $i<=4; $i++) {
            $data['category_names'][$i] = array();
        }
        
        
        foreach($data['get_category_titles'] as $category_titles) {
            for($i = 1; $i<=4; $i++) {
                array_push($data['category_names'][$i], array(
                    'id'=>$category_titles->id,
                    'category'=>$category_titles->category,
                    'title'=>$category_titles->type_name));
            }
        }
        
        for($i = 1; $i<=4; $i++) {    
            foreach($data['category_names'][$i] as $category_names) {
                $data['behavior'][$i][$category_names['category']][$category_names['title']] = '';
            }
        }

        for($i = 1; $i<=4; $i++) {    
            foreach($data['category_names'][$i] as $category_names) {
                foreach($data['student_qualitative_scores'] as $student_qualitative_scores) {
                    if($category_names['id'] == $student_qualitative_scores->qid) {
                        if($i == $student_qualitative_scores->period) {
                            $data['behavior'][$i][$category_names['category']][$category_names['title']] = $student_qualitative_scores->score;
                        }
                    }
                }
            }
        }
        //END OF BEHAVIOR SCORES
        
    }//END OF IF/ELSE FOR STUDENT ID

    /**
     * START OF ATTENDANCE
     */
    
        $data['total_days_currentyear'][$sid] = DB::table('attendance')
        ->select(DB::raw('COUNT(status) as student_total'))
        ->whereBetween('date',['2019-06-01','2020-03-31'])
        ->where('sid', $sid)
        ->whereYear('date', '2019')
        ->get();

        $data['total_present_currentyear'][$sid] = DB::table('attendance')
        ->select(DB::raw('COUNT(status) as student_present'))
        ->whereBetween('date',['2019-06-01','2020-03-31'])
        ->where(function ($query) {
            $query->orWhere('status', 'present')
                  ->orWhere('status', 'late');
        })
        ->where('sid', $sid)
        ->whereYear('date', '2019')
        ->get();

        $data['total_absent_currentyear'][$sid] = DB::table('attendance')
        ->select(DB::raw('COUNT(status) as student_absent'))
        ->whereBetween('date',['2019-06-01','2020-03-31'])
        ->where('sid', $sid)
        ->where('status', 'absent')
        ->get();
    

        for($i = 6; $i<=12; $i++) {
            $data['total_attendance_currentyear'][$sid][$i] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_total'))
            ->where(DB::raw('MONTH(date)'), $i)
            ->where(DB::raw('YEAR(date)'), '2019')
            ->where('sid', $sid)
            ->get();

            $data['attendance_list_present_currentyear'][$sid][$i] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_present'))
            ->where(DB::raw('MONTH(date)'), $i)
            ->where(DB::raw('YEAR(date)'), '2019')
            ->where('sid', $sid)
            ->where(function ($query) {
                $query->orWhere('status', 'present')
                      ->orWhere('status', 'late');
            })
            ->get();

            $data['attendance_list_absent_currentyear'][$sid][$i] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_absent'))
            ->where(DB::raw('MONTH(date)'), $i)
            ->where(DB::raw('YEAR(date)'), '2019')
            ->where('sid', $sid)
            ->where('status', 'absent')
            ->get();
        }
    

  
        for($i = 1; $i<=3; $i++) {
            $data['total_attendance_nextyear'][$sid][$i] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_total'))
            ->where(DB::raw('MONTH(date)'), $i)
            ->where(DB::raw('YEAR(date)'), '2020')
            ->where('sid', $sid)
            ->get();

            $data['attendance_list_present_nextyear'][$sid][$i] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_present'))
            ->where(DB::raw('MONTH(date)'), $i)
            ->where(DB::raw('YEAR(date)'), '2020')
            ->where('sid', $sid)
            ->where(function ($query) {
                $query->orWhere('status', 'present')
                      ->orWhere('status', 'late');
            })
            ->get();

            $data['attendance_list_absent_nextyear'][$sid][$i] = DB::table('attendance')
            ->select(DB::raw('COUNT(status) as student_absent'))
            ->where(DB::raw('MONTH(date)'), $i)
            ->where(DB::raw('YEAR(date)'), '2020')
            ->where('sid', $sid)
            ->where('status', 'absent')
            ->get();
        }

        $data['merging_attendance'] = array(
                'total_attendance_currentyear'=>$data['total_days_currentyear'][$sid],
                'total_present_currentyear'=>$data['total_present_currentyear'][$sid],
                'total_absent_currentyear'=>$data['total_absent_currentyear'][$sid],
                'total_attendance_per_month'=>$data['total_attendance_currentyear'],
                'total_present_per_month'=>$data['attendance_list_present_currentyear'][$sid],
                'total_absent_per_month'=>$data['attendance_list_absent_currentyear'][$sid],
                'total_attendance_next_year_per_month'=>$data['total_attendance_nextyear'][$sid],
                'total_present_next_year_per_month'=>$data['attendance_list_present_nextyear'][$sid],
                'total_absent_next_year_per_month'=>$data['attendance_list_absent_nextyear'][$sid]
            );
    


    /**
     * END OF ATTENDANCE
     */

    

    $data['list_of_final_grade_and_behavior_score'] = null;
    $data['list_of_final_grade_and_behavior_score'] = array_merge(['final_grade'=>$data['final_grade'],'general_average'=>$data['general_avg_of_student'],'behavior'=>$data['behavior'],'attendance'=>$data['merging_attendance']]);

    $data['list_of_final_grade_and_behavior_score']['reportcard_view_teacher'] = $dataToSend['reportcard_view_teacher']->status;
    $data['list_of_final_grade_and_behavior_score']['reportcard_view_parent'] = $dataToSend['reportcard_view_parent']->status;

    return response()->json($data['list_of_final_grade_and_behavior_score']);
}

public function mobileViewInGradeSectionTaskGrades(Request $request, $grade='', $section='', $period='', $subj='') {
    //start time of execution script
    $currentTime = microtime(true);

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

    $data['taskid_list'] = DB::table('tasks')
    ->where('task_grade', $grade)
    ->where('task_section', $section)
    ->where('period', $period)
    ->where('task_subject', $subj)
    ->orderByRaw("FIELD(task_type , 'WW', 'PT', 'QA')")
    ->get();

    $data['taskid_list_with_scores'] = [];
    foreach($data['student_id_list'] as $student_id) {
        foreach($data['taskid_list'] as $taskid_list) {
            $db_task_score_with_status = DB::table('task_student')
            ->select('score', 'taskid')
            ->where('sid', $student_id->id)
            ->where('taskid', $taskid_list->id)
            ->where('period', $period)
            ->whereNotIn('status',['excused'])
            ->first();

            $db_task_weight_list = DB::table('task_type')
            ->select('weight')
            ->where('type_name', $taskid_list->task_type)
            ->where('subject', $subj)
            ->first();

            if(isset($db_task_score_with_status)) {
                array_push($data['taskid_list_with_scores'], array(
                    'sid'=>$student_id->id,
                    'taskid'=>$db_task_score_with_status->taskid,
                    'type_name'=>$taskid_list->task_type,
                    'score'=>$db_task_score_with_status->score,
                    'total_points'=>$taskid_list->task_total_points,
                    'weight'=>$db_task_weight_list->weight
                ));
            }
        }
    }

    

    foreach($data['taskid_list'] as $taskid_list) {
        foreach($data['student_id_list'] as $student_id) {
            $data['task_taskid_student_list'][$taskid_list->id] = [];
            $data['task_avg_scores'][$taskid_list->id] = [];
        }
    }

    foreach($data['taskid_list'] as $taskid_list) {
        foreach($data['student_id_list'] as $student_id) {
            $db_task_score_for_avg = DB::table('task_student')
            ->select('score')
            ->where('sid', $student_id->id)
            ->where('taskid', $taskid_list->id)
            ->where('period', $period)
            ->whereNotIn('status',['excused'])
            ->first();

            if(isset($db_task_score_for_avg)) {
                array_push($data['task_taskid_student_list'][$taskid_list->id], $db_task_score_for_avg->score);
            }
        }
    }

    foreach($data['taskid_list'] as $taskid_list) {
      array_push($data['task_avg_scores'][$taskid_list->id], round((array_sum($data['task_taskid_student_list'][$taskid_list->id])/$data['grade_section_student_group_total_fixed']),2));
    }
   
    foreach($data['student_id_list'] as $student_id) {
        foreach($data['type_list_fixed'] as $type_list_fixed) {
            $data['merge_type_name_scores'][$student_id->id][$type_list_fixed->type_name] = [];
            $data['merge_type_name_total_points'][$student_id->id][$type_list_fixed->type_name] = [];
            $data['merge_type_name_weight'][$student_id->id][$type_list_fixed->type_name] = [];
            $data['sub_total_task_list'][$student_id->id][$type_list_fixed->type_name] = [];
            $data['sub_total_task_for_view'][$student_id->id][$type_list_fixed->type_name] = [];
        }
    }

    foreach($data['taskid_list_with_scores'] as $task_list_scores) {
        array_push($data['merge_type_name_scores'][$task_list_scores['sid']][$task_list_scores['type_name']], $task_list_scores['score']);
        array_push($data['merge_type_name_total_points'][$task_list_scores['sid']][$task_list_scores['type_name']], $task_list_scores['total_points']);

        $data['merge_type_name_weight'][$task_list_scores['sid']][$task_list_scores['type_name']] = $task_list_scores['weight'];
    }

    foreach($data['student_id_list'] as $student_id) {
        foreach($data['type_list_fixed'] as $type_list_fixed) {
            if(array_sum($data['merge_type_name_total_points'][$student_id->id][$type_list_fixed->type_name]) == 0) {
                $data['sub_total_task_list'][$student_id->id][$type_list_fixed->type_name] = [0];
                $data['sub_total_task_for_view'][$student_id->id][$type_list_fixed->type_name] = [0];
            } else {
                array_push($data['sub_total_task_list'][$student_id->id][$type_list_fixed->type_name], round((array_sum($data['merge_type_name_scores'][$student_id->id][$type_list_fixed->type_name])/array_sum($data['merge_type_name_total_points'][$student_id->id][$type_list_fixed->type_name]))*$data['merge_type_name_weight'][$student_id->id][$type_list_fixed->type_name],2));
                array_push($data['sub_total_task_for_view'][$student_id->id][$type_list_fixed->type_name], round((array_sum($data['merge_type_name_scores'][$student_id->id][$type_list_fixed->type_name])/array_sum($data['merge_type_name_total_points'][$student_id->id][$type_list_fixed->type_name]))*100,2));
            }
        }
    }

    foreach($data['student_id_list'] as $student_id) {
        $data['merge_type_name_scores'][$student_id->id] = [];
        $data['final_grade'][$student_id->id] = [];
    }

    foreach($data['student_id_list'] as $student_id) {
        foreach($data['type_list_fixed'] as $type_list_fixed) {
            array_push($data['merge_type_name_scores'][$student_id->id], $data['sub_total_task_list'][$student_id->id][$type_list_fixed->type_name][0]);
        }
    }

    foreach($data['student_id_list'] as $student_id) {
        array_push($data['final_grade'][$student_id->id], round(array_sum($data['merge_type_name_scores'][$student_id->id]),2));
    }

    $endtime = time('h:i:s');

    //calculate execution of script
    $data['howLong'] = microtime(true) - $currentTime;


    return response()->json($data);

}

public function mobileViewInGradeSectionFinalGrades(Request $request, $grade='', $section='', $period='') {
    $currenttime = microtime(true);
    
    $checkGradeSection = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->first();

    if(!isset($checkGradeSection)) {
        return redirect()->back()->with('error', 'Grade/Section does not exist. Please check again');
    }

    $data['student_id_list'] = DB::table('students')
    ->select('id', 'firstname', 'lastname')
    ->where('grade', $grade)
    ->where('section', $section)
    ->orderBy('lastname')
    ->get();

    $data['subject_list_grade_section'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->whereNotIn('subject',['homeroom'])
    ->orderBy('subject')
    ->get();

    $data['type_list_fixed'] = DB::table('task_type')
    ->select('type_name')
    ->orderByRaw("FIELD(type_name , 'WW', 'PT', 'QA')")
    ->get()
    ->unique('type_name');

    $data['taskid_list_from_gradesection_period'] = DB::table('tasks')
    ->select('id', 'task_subject', 'task_type', 'task_total_points')
    ->where('task_grade', $grade)
    ->where('task_section', $section)
    ->where('period', $period)
    ->orderBy('task_subject')
    ->orderByRaw("FIELD(task_type , 'WW', 'PT', 'QA')")
    ->get();

    foreach($data['student_id_list'] as $student_id_list) {
        foreach($data['subject_list_grade_section'] as $subject_list_grade_section) {
            foreach($data['type_list_fixed'] as $type_list_fixed) {
                $data['task_score_list_per_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name] = [];
                $data['task_total_points_list_per_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name] = [];
                $data['task_weight_list_per_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name] = 0;
                $data['sum_of_task_score_of_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name] = [];
            }
            $data['merge_type_name_to_subjects'][$student_id_list->id][$subject_list_grade_section->subject] = [];
            
        }
    }

    foreach($data['student_id_list'] as $student_id_list) {
        foreach($data['subject_list_grade_section'] as $subject_list_grade_section) {
            $data['final_grade'][ucwords($student_id_list->firstname).' '.ucwords($student_id_list->lastname)][$subject_list_grade_section->subject] = [];
        }
    }

    foreach($data['student_id_list'] as $student_id_list) {
        foreach($data['taskid_list_from_gradesection_period'] as $taskid_list_from_gradesection_period) {
            $get_score_from_task = DB::table('task_student')
            ->select('score')
            ->whereNotIn('status',['excused'])
            ->where('taskid', $taskid_list_from_gradesection_period->id)
            ->where('sid', $student_id_list->id)
            ->first();

            $get_weight_from_task = DB::table('task_type')
            ->select('weight')
            ->where('subject', $taskid_list_from_gradesection_period->task_subject)
            ->where('type_name', $taskid_list_from_gradesection_period->task_type)
            ->first();

            if(isset($get_score_from_task)) {
                array_push($data['task_score_list_per_student'][$student_id_list->id][$taskid_list_from_gradesection_period->task_subject][$taskid_list_from_gradesection_period->task_type], $get_score_from_task->score);
                array_push($data['task_total_points_list_per_student'][$student_id_list->id][$taskid_list_from_gradesection_period->task_subject][$taskid_list_from_gradesection_period->task_type], $taskid_list_from_gradesection_period->task_total_points);
                $data['task_weight_list_per_student'][$student_id_list->id][$taskid_list_from_gradesection_period->task_subject][$taskid_list_from_gradesection_period->task_type] = $get_weight_from_task->weight;
            }
        }
    }

    foreach($data['student_id_list'] as $student_id_list) {
        foreach($data['subject_list_grade_section'] as $subject_list_grade_section) {
            foreach($data['type_list_fixed'] as $type_list_fixed) {
                if(array_sum($data['task_total_points_list_per_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name]) == 0) {
                    $data['sum_of_task_score_of_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name] = [0];
                } else {
                    array_push($data['sum_of_task_score_of_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name], round((array_sum($data['task_score_list_per_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name])/array_sum($data['task_total_points_list_per_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name]))*$data['task_weight_list_per_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name],2));
                }
            }
        }
    }

    foreach($data['student_id_list'] as $student_id_list) {
        foreach($data['subject_list_grade_section'] as $subject_list_grade_section) {
            foreach($data['type_list_fixed'] as $type_list_fixed) {
                array_push($data['merge_type_name_to_subjects'][$student_id_list->id][$subject_list_grade_section->subject], $data['sum_of_task_score_of_student'][$student_id_list->id][$subject_list_grade_section->subject][$type_list_fixed->type_name][0]);
            }
        }
    }

    foreach($data['student_id_list'] as $student_id_list) {
        foreach($data['subject_list_grade_section'] as $subject_list_grade_section) {
            array_push($data['final_grade'][ucwords($student_id_list->firstname).' '.ucwords($student_id_list->lastname)][$subject_list_grade_section->subject], round(array_sum($data['merge_type_name_to_subjects'][$student_id_list->id][$subject_list_grade_section->subject]),2));
        }
    }

    //check execution time of script
    $data['howLong'] = microtime(true) - $currenttime;

    return response()->json($data);
}

public function mobileConvertPDFFinalGrades(Request $request, $grade='', $section='') {

    $data['grade_section_list'] = DB::table('students')
    ->select('id')
    ->where('grade', $grade)
    ->where('section', $section)
    ->orderBy('lastname')
    ->get();

    $data['subject_list'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->whereNotIn('subject',['homeroom'])
    ->orderBy('subject')
    ->get();

    $data['subject_list_count'] = DB::table('subjects')
    ->where('grade', $grade)
    ->where('section', $section)
    ->whereNotIn('subject',['homeroom'])
    ->count();

    $data['fixed_period_list'] = array('1','2','3','4');
    $data['fixed_period_list_for_general_average'] = array('1','2','3','4', '5');

    $data['type_list_fixed'] = DB::table('task_type')
    ->select('type_name')
    ->orderByRaw("FIELD(type_name , 'WW', 'PT', 'QA')")
    ->get()
    ->unique('type_name');

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['subject_list'] as $subject_list) {
            foreach($data['fixed_period_list'] as $period_list) {
                foreach($data['type_list_fixed'] as $type_list) {
                    $data['array_of_student_to_task_score_list'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name] = [];
                    $data['array_of_student_to_task_total_points_list'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name] = [];
                    $data['array_of_student_to_task_weight_list'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name] = [];
                    $data['array_of_computed_task_grade_with_weight'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name] = [];
                }
                $data['array_of_merged_score_from_type_names'][$grade_section_list->id][$subject_list->subject][$period_list] = [];
                $data['final_grade_per_period'][$grade_section_list->id][$subject_list->subject][$period_list] = [];
            }
            $data['array_sum_up_all_period'][$grade_section_list->id][$subject_list->subject] = [];
            $data['final_grade_of_sum_up_all_period'][$grade_section_list->id][$subject_list->subject] = [];
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['fixed_period_list'] as $period_list) {
            foreach($data['subject_list'] as $subject_list) {
                $data['array_of_average_score_per_period'][$grade_section_list->id][$period_list][$subject_list->subject] = [];
            }
            $data['merge_average_score_per_period'][$grade_section_list->id][$period_list] = [];
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['fixed_period_list_for_general_average'] as $period_list) {
            $data['final_average_score_per_period'][$grade_section_list->id][$period_list] = [];
        }
    }

    $data['get_taskid_list'] = DB::table('tasks')
    ->select('id', 'task_type', 'task_total_points', 'task_subject', 'period')
    ->where('task_grade', $grade)
    ->where('task_section', $section)
    ->orderBy('task_subject')
    ->orderBy('period')
    ->orderByRaw("FIELD(task_type , 'WW', 'PT', 'QA')")
    ->get();

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['get_taskid_list'] as $get_taskid_list) {
            $db_get_task_score = DB::table('task_student')
            ->select('score')
            ->where('taskid', $get_taskid_list->id)
            ->where('sid', $grade_section_list->id)
            ->where('period', $get_taskid_list->period)
            ->whereNotIn('status',['excused'])
            ->first();

            $db_get_task_weight = DB::table('task_type')
            ->select('weight')
            ->where('subject', $get_taskid_list->task_subject)
            ->where('type_name', $get_taskid_list->task_type)
            ->first();

            if(isset($db_get_task_score)) {
                array_push($data['array_of_student_to_task_score_list'][$grade_section_list->id][$get_taskid_list->task_subject][$get_taskid_list->period][$get_taskid_list->task_type], $db_get_task_score->score);
                array_push($data['array_of_student_to_task_total_points_list'][$grade_section_list->id][$get_taskid_list->task_subject][$get_taskid_list->period][$get_taskid_list->task_type], $get_taskid_list->task_total_points);
                $data['array_of_student_to_task_weight_list'][$grade_section_list->id][$get_taskid_list->task_subject][$get_taskid_list->period][$get_taskid_list->task_type] = $db_get_task_weight->weight;
            }
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['subject_list'] as $subject_list) {
            foreach($data['fixed_period_list'] as $period_list) {
                foreach($data['type_list_fixed'] as $type_list) {
                    if(array_sum($data['array_of_student_to_task_total_points_list'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name]) == 0) {
                        $data['array_of_computed_task_grade_with_weight'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name] = [0];
                    } else {
                        array_push($data['array_of_computed_task_grade_with_weight'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name], round((array_sum($data['array_of_student_to_task_score_list'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name])/array_sum($data['array_of_student_to_task_total_points_list'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name]))*$data['array_of_student_to_task_weight_list'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name],2));
                    }
                }
            }
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['subject_list'] as $subject_list) {
            foreach($data['fixed_period_list'] as $period_list) {
                foreach($data['type_list_fixed'] as $type_list) {
                    array_push($data['array_of_merged_score_from_type_names'][$grade_section_list->id][$subject_list->subject][$period_list], $data['array_of_computed_task_grade_with_weight'][$grade_section_list->id][$subject_list->subject][$period_list][$type_list->type_name][0]);
                }
            }
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['subject_list'] as $subject_list) {
            foreach($data['fixed_period_list'] as $period_list) {
                array_push($data['final_grade_per_period'][$grade_section_list->id][$subject_list->subject][$period_list], round(array_sum($data['array_of_merged_score_from_type_names'][$grade_section_list->id][$subject_list->subject][$period_list]),2));
            }
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['subject_list'] as $subject_list) {
            foreach($data['fixed_period_list'] as $period_list) {
                array_push($data['array_sum_up_all_period'][$grade_section_list->id][$subject_list->subject], $data['final_grade_per_period'][$grade_section_list->id][$subject_list->subject][$period_list][0]);
            }
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['subject_list'] as $subject_list) {
            array_push($data['final_grade_of_sum_up_all_period'][$grade_section_list->id][$subject_list->subject], round((array_sum($data['array_sum_up_all_period'][$grade_section_list->id][$subject_list->subject])/4),2));
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['fixed_period_list'] as $period_list) {
            foreach($data['subject_list'] as $subject_list) {
                array_push($data['array_of_average_score_per_period'][$grade_section_list->id][$period_list][$subject_list->subject], $data['final_grade_per_period'][$grade_section_list->id][$subject_list->subject][$period_list][0]);
            }
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['fixed_period_list'] as $period_list) {
            foreach($data['subject_list'] as $subject_list) {
                array_push($data['merge_average_score_per_period'][$grade_section_list->id][$period_list], $data['array_of_average_score_per_period'][$grade_section_list->id][$period_list][$subject_list->subject][0]);
            }
        }
    }

    foreach($data['grade_section_list'] as $grade_section_list) {
        foreach($data['fixed_period_list'] as $period_list) {
            array_push($data['final_average_score_per_period'][$grade_section_list->id][$period_list], round((array_sum($data['merge_average_score_per_period'][$grade_section_list->id][$period_list])/$data['subject_list_count']),2));
        }
    }

    return response()->json($data);
}

public function mobileCheckIfGradesViewableByTeachersOrParents(Request $request) {
    $dataToSend['reportcard_view_teacher'] = DB::table('config')
    ->select('status')
    ->where('config_name', 'report_card_teacher')
    ->first();

    $dataToSend['reportcard_view_parent'] = DB::table('config')
    ->select('status')
    ->where('config_name', 'reportcard_view_parent')
    ->first();

    $data['reportcard_view_teacher'] = $dataToSend['reportcard_view_teacher']->status;
    $data['reportcard_view_parent'] = $dataToSend['reportcard_view_parent']->status;

    return response()->json($data);
}

public static function transmuteGradesHere($rawGrade='') {

    switch(true) {
        case $rawGrade >= '0.00' && $rawGrade <= '3.99':
            return 60;
            break;
        case $rawGrade >= '4.00' && $rawGrade <= '7.99':
            return 61;
            break;
        case $rawGrade >= '8.00' && $rawGrade <= '11.99':
            return 62;
            break;
        case $rawGrade >= '12.00' && $rawGrade <= '15.99':
            return 63;
            break;
        case $rawGrade >= '16.00' && $rawGrade <= '19.99':
            return 64;
            break;
        case $rawGrade >= '20.00' && $rawGrade <= '23.99':
            return 65;
            break;
        case $rawGrade >= '24.00' && $rawGrade <= '27.99':
            return 66;
            break;
        case $rawGrade >= '28.00' && $rawGrade <= '31.99':
            return 67;
            break;
        case $rawGrade >= '32.00' && $rawGrade <= '35.99':
            return 68;
            break;
        case $rawGrade >= '36.00' && $rawGrade <= '39.99':
            return 69;
            break;
        case $rawGrade >= '40.00' && $rawGrade <= '43.99':
            return 70;
            break;
        case $rawGrade >= '44.00' && $rawGrade <= '47.99':
            return 71;
            break;
        case $rawGrade >= '48.00' && $rawGrade <= '51.99':
            return 72;
            break;
        case $rawGrade >= '52.00' && $rawGrade <= '55.99':
            return 73;
            break;
        case $rawGrade >= '56.00' && $rawGrade <= '59.99':
            return 74;
            break;
        case $rawGrade >= '60.00' && $rawGrade <= '61.59':
            return 75;
            break;
        case $rawGrade >= '61.60' && $rawGrade <= '63.19':
            return 76;
            break;
        case $rawGrade >= '63.20' && $rawGrade <= '64.79':
            return 77;
            break;
        case $rawGrade >= '64.80' && $rawGrade <= '66.39':
            return 78;
            break;
        case $rawGrade >= '66.40' && $rawGrade <= '67.99':
            return 79;
            break;
        case $rawGrade >= '68.00' && $rawGrade <= '69.59':
            return 80;
            break;
        case $rawGrade >= '69.60' && $rawGrade <= '71.19':
            return 81;
            break;
        case $rawGrade >= '71.20' && $rawGrade <= '72.79':
            return 82;
            break;
        case $rawGrade >= '72.80' && $rawGrade <= '74.39':
            return 83;
            break;
        case $rawGrade >= '74.40' && $rawGrade <= '75.99':
            return 84;
            break;
        case $rawGrade >= '76.00' && $rawGrade <= '77.99':
            return 85;
            break;
        case $rawGrade >= '77.60' && $rawGrade <= '79.19':
            return 86;
            break;
        case $rawGrade >= '79.20' && $rawGrade <= '80.79':
            return 87;
            break;
        case $rawGrade >= '80.80' && $rawGrade <= '82.39':
            return 88;
            break;
        case $rawGrade >= '82.40' && $rawGrade <= '83.99':
            return 89;
            break;
        case $rawGrade >= '84.00' && $rawGrade <= '85.59':
            return 90;
            break;
        case $rawGrade >= '85.60' && $rawGrade <= '87.19':
            return 91;
            break;
        case $rawGrade >= '87.20' && $rawGrade <= '88.79':
            return 92;
            break;
        case $rawGrade >= '88.80' && $rawGrade <= '90.39':
            return 93;
            break;
        case $rawGrade >= '90.40' && $rawGrade <= '91.99':
            return 94;
            break;
        case $rawGrade >= '92.00' && $rawGrade <= '93.59':
            return 95;
            break;
        case $rawGrade >= '93.60' && $rawGrade <= '95.19':
            return 96;
            break;
        case $rawGrade >= '95.20' && $rawGrade <= '96.79':
            return 97;
            break;
        case $rawGrade >= '96.80' && $rawGrade <= '98.39':
            return 98;
            break;
        case $rawGrade >= '98.40' && $rawGrade <= '99.99':
            return 99;
            break;
        case $rawGrade >= '100.00' && $rawGrade <= '100.00':
            return 100;
            break;
    }
}

/**
 * Special transmutation grades 
 * conversion is applicable for special cases
 */

public static function transmuteGradesSpecial($rawGrade='') {

    switch(true) {
        case $rawGrade >= '0.00' && $rawGrade <= '3.11':
            return 60;
            break;
        case $rawGrade >= '3.12' && $rawGrade <= '6.28':
            return 61;
            break;
        case $rawGrade >= '6.29' && $rawGrade <= '9.45':
            return 62;
            break;
        case $rawGrade >= '9.46' && $rawGrade <= '12.62':
            return 63;
            break;
        case $rawGrade >= '12.63' && $rawGrade <= '15.79':
            return 64;
            break;
        case $rawGrade >= '15.80' && $rawGrade <= '18.96':
            return 65;
            break;
        case $rawGrade >= '18.97' && $rawGrade <= '22.13':
            return 66;
            break;
        case $rawGrade >= '22.14' && $rawGrade <= '25.30':
            return 67;
            break;
        case $rawGrade >= '25.31' && $rawGrade <= '28.47':
            return 68;
            break;
        case $rawGrade >= '28.48' && $rawGrade <= '31.64':
            return 69;
            break;
        case $rawGrade >= '31.65' && $rawGrade <= '34.81':
            return 70;
            break;
        case $rawGrade >= '34.82' && $rawGrade <= '37.98':
            return 71;
            break;
        case $rawGrade >= '37.99' && $rawGrade <= '41.15':
            return 72;
            break;
        case $rawGrade >= '41.16' && $rawGrade <= '44.32':
            return 73;
            break;
        case $rawGrade >= '44.33' && $rawGrade <= '47.49':
            return 74;
            break;
        case $rawGrade >= '47.50' && $rawGrade <= '48.79':
            return 75;
            break;
        case $rawGrade >= '48.80' && $rawGrade <= '50.09':
            return 76;
            break;
        case $rawGrade >= '50.10' && $rawGrade <= '51.39':
            return 77;
            break;
        case $rawGrade >= '51.40' && $rawGrade <= '52.69':
            return 78;
            break;
        case $rawGrade >= '52.70' && $rawGrade <= '53.99':
            return 79;
            break;
        case $rawGrade >= '54.00' && $rawGrade <= '55.29':
            return 80;
            break;
        case $rawGrade >= '55.30' && $rawGrade <= '56.59':
            return 81;
            break;
        case $rawGrade >= '56.60' && $rawGrade <= '57.89':
            return 82;
            break;
        case $rawGrade >= '57.90' && $rawGrade <= '59.19':
            return 83;
            break;
        case $rawGrade >= '59.20' && $rawGrade <= '60.49':
            return 84;
            break;
        case $rawGrade >= '60.50' && $rawGrade <= '61.79':
            return 85;
            break;
        case $rawGrade >= '61.80' && $rawGrade <= '63.09':
            return 86;
            break;
        case $rawGrade >= '63.10' && $rawGrade <= '64.39':
            return 87;
            break;
        case $rawGrade >= '64.40' && $rawGrade <= '65.69':
            return 88;
            break;
        case $rawGrade >= '65.70' && $rawGrade <= '66.99':
            return 89;
            break;
        case $rawGrade >= '67.00' && $rawGrade <= '68.29':
            return 90;
            break;
        case $rawGrade >= '68.30' && $rawGrade <= '69.59':
            return 91;
            break;
        case $rawGrade >= '69.60' && $rawGrade <= '70.89':
            return 92;
            break;
        case $rawGrade >= '70.90' && $rawGrade <= '72.19':
            return 93;
            break;
        case $rawGrade >= '72.20' && $rawGrade <= '73.49':
            return 94;
            break;
        case $rawGrade >= '73.50' && $rawGrade <= '74.79':
            return 95;
            break;
        case $rawGrade >= '74.80' && $rawGrade <= '76.09':
            return 96;
            break;
        case $rawGrade >= '76.10' && $rawGrade <= '77.39':
            return 97;
            break;
        case $rawGrade >= '77.40' && $rawGrade <= '78.69':
            return 98;
            break;
        case $rawGrade >= '78.70' && $rawGrade <= '79.99':
            return 99;
            break;
        case $rawGrade == '80':
            return 100;
            break;
    }
}

public static function finalGradeListFromGradeSection($grade='', $section='') {

    $data['student_list_per_grade'] = DB::table('students')
    ->select('id', 'firstname', 'lastname', 'grade', 'section')
    ->where('grade', $grade)
    ->where('section', $section)
    ->orderBy('lastname')
    ->get();

    $data['subject_list_fixed'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->orderBy('rankorder')
    ->whereNotIn('subject',['homeroom'])
    ->get();

    $data['type_list_fixed'] = DB::table('task_type')
    ->select('type_name')
    ->orderByRaw("FIELD(type_name , 'WW', 'PT', 'QA')")
    ->get()
    ->unique('type_name');

    $data['students_subjects_fixed_count'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->whereNotIn('subject',['homeroom', 'language', 'reading', 'music', 'arts', 'pe', 'health', 'penmanship'])
    ->count();

    $data['students_subjects_selected'] = DB::table('subjects')
    ->select('subject')
    ->where('grade', $grade)
    ->where('section', $section)
    ->orderBy('rankorder')
    ->whereNotIn('subject',['homeroom', 'language', 'reading', 'music', 'arts', 'pe', 'health', 'penmanship'])
    ->get();

    $data['get_all_task_type'] = DB::table('task_type')
    ->orderByRaw("FIELD(type_name , 'WW', 'PT', 'QA')")
    ->orderBy('subject')
    ->whereNotIn('subject',['homeroom'])
    ->get();

    $data['get_all_task_total_points'] = DB::table('tasks')
    ->where('task_grade', $grade)
    ->where('task_section', $section)
    ->orderByRaw("FIELD(task_type , 'WW', 'PT', 'QA')")
    ->get();
    
    foreach($data['student_list_per_grade'] as $student_list) {
        $data['array_list_of_task_scores_per_studentid'][$student_list->id] = null;
        foreach($data['subject_list_fixed'] as $subject_list) {
            for($i=1; $i<=4; $i++) {
                foreach($data['type_list_fixed'] as $type_list_fixed) {
                    $data['array_list_of_task_scores_sorted_out'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name] = [];
                    $data['array_list_of_task_total_points_sorted_out'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name] = [];
                    $data['array_list_of_scores_being_computed_with_weight'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name] = [];
                }
                $data['array_list_of_scores_being_merge_by_task_type'][$student_list->id][$subject_list->subject][$i] = [];
                
            }
        }
    }
   
    foreach($data['student_list_per_grade'] as $student_list) {
        foreach($data['subject_list_fixed'] as $subject_list) {
            for($i=1; $i<=5; $i++) {
                $data['summing_up_list_of_scores_from_task_type'][$student_list->id][$subject_list->subject][$i] = 0;
            }
        }
    }
    

    foreach($data['student_list_per_grade'] as $student_list) {
        $get_all_scores = DB::table('task_student')
        ->select('id', 'sid', 'taskid', 'score', 'period')
        ->where('sid', $student_list->id)
        ->whereNotIn('status',['excused'])
        ->get();

        $data['array_list_of_task_scores_per_studentid'][$student_list->id] = $get_all_scores;
    }
    
    foreach($data['array_list_of_task_scores_per_studentid'] as $studentid=>$list) {
        foreach($list as $key=>$val) {
            foreach($data['get_all_task_total_points'] as $total_points) {
                if($total_points->id == $val->taskid) {
                    for($i=1; $i<=4; $i++) {
                        if($val->period == $i) {
                            array_push($data['array_list_of_task_scores_sorted_out'][$val->sid][$total_points->task_subject][$i][$total_points->task_type], $val->score);
                            array_push($data['array_list_of_task_total_points_sorted_out'][$val->sid][$total_points->task_subject][$i][$total_points->task_type], $total_points->task_total_points);
                        }
                    }
                }
            }
        }
    }
   
    foreach($data['student_list_per_grade'] as $student_list) {
        foreach($data['subject_list_fixed'] as $subject_list) {
            for($i=1; $i<=4; $i++) {
                foreach($data['type_list_fixed'] as $type_list_fixed) {
                    foreach($data['get_all_task_type'] as $weight) {

                        if($weight->subject == $subject_list->subject && $weight->type_name == $type_list_fixed->type_name) {
                            if(array_sum($data['array_list_of_task_total_points_sorted_out'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name]) == 0) {
                                $data['array_list_of_scores_being_computed_with_weight'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name] = [0];
                            } else {
                                array_push($data['array_list_of_scores_being_computed_with_weight'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name], round(((array_sum($data['array_list_of_task_scores_sorted_out'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name])/array_sum($data['array_list_of_task_total_points_sorted_out'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name]))*$weight->weight),2));
                            }
                        }
                    }
                }
            }
        }
    }

    foreach($data['student_list_per_grade'] as $student_list) {
        foreach($data['subject_list_fixed'] as $subject_list) {
            for($i=1; $i<=4; $i++) {
                foreach($data['type_list_fixed'] as $type_list_fixed) {
                    if(isset($data['array_list_of_scores_being_computed_with_weight'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name][0])) {
                        array_push($data['array_list_of_scores_being_merge_by_task_type'][$student_list->id][$subject_list->subject][$i], $data['array_list_of_scores_being_computed_with_weight'][$student_list->id][$subject_list->subject][$i][$type_list_fixed->type_name][0]);
                    }
                }
            }
        }
    }

    //FINAL GRADE LIST
    foreach($data['student_list_per_grade'] as $student_list) {
        foreach($data['subject_list_fixed'] as $subject_list) {
            for($i=1; $i<=4; $i++) {
                $data['summing_up_list_of_scores_from_task_type'][$student_list->id][$subject_list->subject][$i] = round(array_sum($data['array_list_of_scores_being_merge_by_task_type'][$student_list->id][$subject_list->subject][$i]),2);
            }
        }
    }
    
    /**
     * COMPUTING GENERAL AVG FOR MAPEH
     */

    $check_if_mapeh_exists = DB::table('subjects')
    ->where('subject', 'MAPEH')
    ->where('grade', $grade)
    ->where('section', $section)
    ->first();

    if(isset($check_if_mapeh_exists)) {
        $mapeh_list = array('music', 'arts', 'pe', 'health');

        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                foreach($mapeh_list as $list) {
                    $data['array_list_for_mapeh_general_avg'][$student_list->id][$i][$list] = 0;
                    
                }
                $data['array_list_of_merging_mapeh_general_avg'][$student_list->id][$i] = [];
                $data['summing_up_score_from_mapeh_to_general_avg'][$student_list->id][$i] = 0;
            }
        }

        foreach($data['summing_up_list_of_scores_from_task_type'] as $studentid=>$subject_list) {
            foreach($subject_list as $subject_name=>$period_list) {
                if($subject_name == 'music' || $subject_name == 'arts' || $subject_name == 'pe' || $subject_name == 'health') {
                    foreach($period_list as $period=>$val) {
                        $data['array_list_for_mapeh_general_avg'][$studentid][$period][$subject_name] = $val;                        
                    }
                }
                
            }
        }



        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                foreach($mapeh_list as $list) {
                    array_push($data['array_list_of_merging_mapeh_general_avg'][$student_list->id][$i], $data['array_list_for_mapeh_general_avg'][$student_list->id][$i][$list]);
                }
            }
        }

        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                $data['summing_up_score_from_mapeh_to_general_avg'][$student_list->id][$i] = round((array_sum($data['array_list_of_merging_mapeh_general_avg'][$student_list->id][$i])/4),2);
            }
        }

        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                $data['summing_up_list_of_scores_from_task_type'][$student_list->id]['MAPEH'][$i] = $data['summing_up_score_from_mapeh_to_general_avg'][$student_list->id][$i];
            }
        }
    }
     /**
      * END COMPUTING GENERAL AVG FOR MAPEH
      */

      /**
       * START OF COMPUTING GENERAL AVG FOR ENGLISH
       */
    
    $check_if_english_exists = DB::table('subjects')
    ->where('subject', 'english')
    ->where('grade', $grade)
    ->where('section', $section)
    ->first();

    if(isset($check_if_english_exists)) {
        $english_list = array('language', 'reading');

        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                foreach($english_list as $list) {
                    $data['array_list_for_english_general_avg'][$student_list->id][$i][$list] = 0;
                }
                $data['array_list_of_merging_english_general_avg'][$student_list->id][$i] = [];
                $data['summing_up_score_from_english_to_general_avg'][$student_list->id][$i] = 0;
            }
        }

        foreach($data['summing_up_list_of_scores_from_task_type'] as $studentid=>$subject_list) {
            foreach($subject_list as $subject_name=>$period_list) {
                if($subject_name == 'language' || $subject_name == 'reading') {
                    foreach($period_list as $period=>$val) {
                        $data['array_list_for_english_general_avg'][$studentid][$period][$subject_name] = $val;                        
                    }
                }
                
            }
        }

        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                foreach($english_list as $list) {
                    array_push($data['array_list_of_merging_english_general_avg'][$student_list->id][$i], $data['array_list_for_english_general_avg'][$student_list->id][$i][$list]);
                }
            }
        }

        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                $data['summing_up_score_from_english_to_general_avg'][$student_list->id][$i] = round((array_sum($data['array_list_of_merging_english_general_avg'][$student_list->id][$i])/2),2);
            }
        }

        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                $data['summing_up_list_of_scores_from_task_type'][$student_list->id]['english'][$i] = $data['summing_up_score_from_english_to_general_avg'][$student_list->id][$i];
            }
        }
    }
       /**
        * END OF COMPUTING GENERAL AVG FOR ENGLISH
        */

        /**
         * COMPUTING GENERAL AVERAGE WITH THE REST OF SUBJECT
         */

        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                foreach($data['students_subjects_selected'] as $subject_list) {
                    $data['subject_scores_per_period'][$student_list->id][$i][$subject_list->subject] = 0;
                }
                $data['array_general_average_the_rest_of_subject'][$student_list->id][$i] = [];
                $data['general_average_list_for_show'][$student_list->id][$i] = 0;
            }
        }
        
        foreach($data['summing_up_list_of_scores_from_task_type'] as $studentid=>$subject_list) {
            foreach($subject_list as $subject_name=>$period_list) {
                foreach($period_list as $period=>$val) {
                    
                    foreach($data['students_subjects_selected'] as $select_subj_list) {
                        if($select_subj_list->subject == $subject_name) {
                            $data['subject_scores_per_period'][$studentid][$period][$subject_name] = $val;
                        }
                    }
                      
                }
            }
        }

        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                foreach($data['students_subjects_selected'] as $subject_list) {
                    array_push($data['array_general_average_the_rest_of_subject'][$student_list->id][$i], $data['subject_scores_per_period'][$student_list->id][$i][$subject_list->subject]);
                }
            }
        }

        //GENERAL AVERAGE FINAL LIST
        foreach($data['student_list_per_grade'] as $student_list) {
            for($i=1; $i<=4; $i++) {
                $data['general_average_list_for_show'][$student_list->id][$i] = round((array_sum($data['array_general_average_the_rest_of_subject'][$student_list->id][$i])/$data['students_subjects_fixed_count']),2);
            }
        }



         /**
          * END OF COMPUTING GENERAL AVERAGE WITH THE REST OF SUBJECT
          */

          /**
           * START COMPUTING FINAL GRADE LIST
           */

        foreach($data['student_list_per_grade'] as $student_list) {
            foreach($data['subject_list_fixed'] as $subject_list) {
                $data['final_grade_listings'][$student_list->id][$subject_list->subject] = [];
                $data['final_grade_sum_up'][$student_list->id][$subject_list->subject] = 0;
            }
        }

        foreach($data['summing_up_list_of_scores_from_task_type'] as $studentid=>$subject_list) {
            foreach($subject_list as $subject_name=>$period_list) {
                for($i=1; $i<=4; $i++) {
                    array_push($data['final_grade_listings'][$studentid][$subject_name], $period_list[$i]);
                }
            }
        }
       
        foreach($data['student_list_per_grade'] as $student_list) {
            foreach($data['subject_list_fixed'] as $subject_list) {
                $finalsubjectgrade = 0;
                // $data['final_grade_sum_up'][$student_list->id][$subject_list->subject] = round((array_sum($data['final_grade_listings'][$student_list->id][$subject_list->subject])/4),2);
                for($i=1; $i<=4; $i++){
                    if($i == 4){
                        $data['final_grade_listings'][$student_list->id][$subject_list->subject][$i-1] = TeacherFetchJSON::transmuteGradesSpecial($data['final_grade_listings'][$student_list->id][$subject_list->subject][$i-1]);
                    }
                    $finalsubjectgrade =$finalsubjectgrade + $data['final_grade_listings'][$student_list->id][$subject_list->subject][$i-1];
                }
                
                $data['final_grade_sum_up'][$student_list->id][$subject_list->subject] = round($finalsubjectgrade/4,2);

            }
        }

           /**
            * END OF COMPUTING FINAL GRADE LIST
            */

            foreach($data['student_list_per_grade'] as $student_list) {
                foreach($data['subject_list_fixed'] as $subject_list) {
                    $data['summing_up_list_of_scores_from_task_type'][$student_list->id][$subject_list->subject][5] = $data['final_grade_sum_up'][$student_list->id][$subject_list->subject];
                }
            }

            //COMBINE FINAL GRADE AND GENERAL AVERAGE
            $data['merged_final_grade_and_general_average'] = array_merge(array('final_grade'=>$data['summing_up_list_of_scores_from_task_type'], 'general_avg'=>$data['general_average_list_for_show']));


          return $data['merged_final_grade_and_general_average'];

}

public function mobileVersionCheck(Request $request) {
    $data['mobile_version'] = DB::table('config')
    ->select('version')
    ->where('config_name', 'latestMobileVersion')
    ->first();

    return response()->json(array('mobile_version'=>$data['mobile_version']->version));
}

public function iOSVersionCheck(Request $request) {
    $data['mobile_version'] = DB::table('config')
    ->select('version')
    ->where('config_name', 'latestiOSMobileVersion')
    ->first();

    return response()->json(array('mobile_version'=>$data['mobile_version']->version));
}

}
