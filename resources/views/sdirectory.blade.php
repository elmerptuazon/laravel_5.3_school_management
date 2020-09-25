@extends('admin_template')

@section('content')

<div class="row">
    <style>
        #over{
          max-width:200px;
          float: left;
          margin-right: 15px;
        }
        .prof{
          max-width: 50px;
          max-height: 150px;
          border: 1px solid cornflowerblue;
        }
        #pic{
          
          object-fit: cover;
        }
        .t{
          font-size: 16px;
          text-overflow: ellipsis;
          overflow: hidden;
          display: block;
          white-space: nowrap;
        }
      </style> 
 @if(Request::is('directory/students')) @foreach($classmates as $classmate)
<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
  <div class="box box-danger " id="over">
    <div class="box-header with-border">
    </div>
    <div class="box-body" id="prof" style="text-align: center;">
      <div>
        <img src="{{ asset("/uploads/profile/".$classmate->profilepic) }}" class="img-circle" alt="User Image"
          style="max-width:100px;" />
      </div>
      <p class="t">
        <strong>{{UCWORDS($classmate->firstname)}} {{UCWORDS($classmate->lastname)}} </strong>
      </p>
      {{--
      <p class="t">
        <small>09-456-8569</small>
      </p> --}}

    </div>
    <!-- /.box-body -->

  </div>
  <!-- /.box -->
</div>
@endforeach @endif

@if(Request::is('directory/teachers')) @foreach($teachers as $teacher)
<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
  <div class="box box-danger " id="over">
    <div class="box-header with-border">
    </div>
    <a href="/tprofile/{{$teacher->id}}">
    <div class="box-body" id="prof" style="text-align: center;">
      <div>
        <img src="{{ asset("/uploads/profile/".$teacher->profilepic) }}" class="img-circle" alt="User Image"
          style="max-width:100px;" />
      </div>
    <p class="t"><a href="/tprofile/{{$teacher->id}}">
        <strong>{{UCWORDS($teacher->firstname)}} {{UCWORDS($teacher->lastname)}} </strong>
      </a>
      </p>
      
          <strong>{{UCWORDS($teacher->subj)}} {{UCWORDS($teacher->grade)}} {{UCWORDS($teacher->section)}}</strong>
      
      {{--
      <p class="t">
        <small>09-456-8569</small>
      </p> --}}

    </div>
    <!-- /.box-body -->
    </a>
  </div>
  <!-- /.box -->
</div>
@endforeach @endif

        {{-- <div class="cole-lg-2 col-md-2 col-sm-4 col-xs-6">
                
                <div class="info-box">
                  <span class="info-box-icon bg-aqua">
                        <div class=" image">
                                <img src="{{ asset("/bower_components/admin-lte/dist/img/user2-160x160.jpg") }}" class="img-circle" alt="User Image" style="max-width:80px;" />
                            </div>
                  </span>
                    
                  <div class="info-box-content">
                    <span class="info-box-text">Luc Ledda</span>
                    <span class="info-box-number">4 - J</span>
                  </div>
                
                  <!-- /.info-box-content -->
                </div>
            
                <!-- /.info-box -->
        </div> --}}

</div>

@endsection