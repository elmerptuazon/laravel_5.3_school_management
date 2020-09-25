@extends('admin_template')

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Student Enrolled</h3>
                @if(session()->has('error'))
                    <div class="alert alert-danger taskAlert">
                        {{ session()->get('error') }}
                    </div>
                @elseif(session()->has('message'))
                    <div class="alert alert-success taskAlert">
                        {{ session()->get('message') }}
                    </div>
                @endif
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-hover table-striped">
                    <tbody id="enrolledStudentTemplateBoard">
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Grade and Section</th>
                            <th> </th>
                        </tr>
                        @foreach($students_list_groupby_grade as $grade_section)
                            @foreach($students_list as $student)
                                @if($student->grade == $grade_section->grade && $student->section == $grade_section->section)
                                    <tr>
                                        <td>{{$student->id}}</td>
                                        <td><a href="/aenrollment/e/{{$student->id}}">{{$student->lastname}}, {{$student->firstname}}</a></td>
                                        <td>{{$student->grade}}-{{$student->section}}</td>
                                        <td>
                                            <button onclick="location.href='/aenrollment/e/{{$student->id}}'" class="edit-btn btn btn-xs btn-primary">Edit</button>
                                            <button onclick="location.href='/fetch/delete/students/{{$student->id}}'" class="delete-btn btn btn-xs btn-danger">Delete</button>
                                        </td>
                                        <input type="hidden" idate="hiddenStudentId" value="##hiddenSid##">
                                        <input type="hidden" class="hiddenStudentName" value="##hiddenName##">
                                        <input type="hidden" class="studentGrdSec" value="##hiddenGradeSec##">
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box -->
    </div>

</div><!--./Row-->

@endsection