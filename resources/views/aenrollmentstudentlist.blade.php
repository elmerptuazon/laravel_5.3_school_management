@extends('admin_template')

@section('content')

<div class="row">
        @if($isStudentExist == '' || @isStudentExist == null)
            <div class="alert alert-danger taskAlert">
                <p>Please check all details or Student ID already exists.</p>
            </div>
        @else
            <div class="alert alert-success taskAlert">
                <p>Student added successfully!</p>
            </div>
        @endif

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Student Enrolled</h3>
            </div>
            <div class="box-body">
                <table class="table table-hover table-striped">
                    <tbody>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Grade and Section</th>
                            <th> </th>
                        </tr>
                        @foreach($student_all_list as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td><a href="/aenrollment/e/{{$student->id}}">{{ ucwords($student->firstname) }} {{ ucwords($student->lastname) }}</a></td>
                            <td>{{ $student->grade }}-{{ $student->section }}</td>
                            <td>
                                <button onclick="location.href='/aenrollment/e/{{$student->id}}'" class="edit-btn btn btn-xs btn-primary">Edit</button>
                                <button class="delete-btn btn btn-xs btn-danger">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection