@extends('admin_template')

@section('content')

<div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Student Directory</h3>
          <div class="box-tools pull-right">            
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div><!--boxheader-->
        <div class="box-body">
            <div class="row">
                
               <div class="col-md-6">
                    <div class="form-group">
                        <select class="form-control select2" id="sDirClassSelect">
                            <option selected="selected"> Select Grade and Section </option>
                            @foreach($gradesection as $gs)
                            {{$gs->grade}}
                            <option value= "{{$gs->grade}}-{{$gs->section}}">Grade {{$gs->grade}}-{{$gs->section}}</option>
                            @endforeach
                            {{--<option value= "1-a">Grade 1-A</option>
                            <option value= "1-b">Grade 1-B</option>
                            <option value= "1-c">Grade 1-C</option>
                            <option value= "2-a">Grade 2-A</option>
                            <option value= "2-b">Grade 2-B</option>
                            <option value= "4-j">Grade 4-J</option>--}}
                            {{-- <option>Grade 3-A</option>
                            <option>Grade 3-B</option>
                            <option>Grade 3-C</option>
                            <option>Grade 4-A</option>
                            <option>Grade 4-B</option>
                            <option>Grade 4-C</option>
                            <option>Grade 5-A</option>
                            <option>Grade 5-B</option>
                            <option>Grade 5-C</option>
                            <option>Grade 6-A</option>
                            <option>Grade 6-B</option>
                            <option>Grade 6-C</option>
                            <option>Grade 7-A</option>
                            <option>Grade 7-B</option>
                            <option>Grade 7-C</option> --}}
                        </select>
                    </div>
                    @push('scripts')
                    <script>
                    $(document).on('change','#sDirClassSelect',function(){
                      window.location = "/adirectory/students/"+$(this).val();
                    });
                    </script>
                    @endpush
                </div> 
                <div class="col-md-6">
                  <div class="form-group">
                      <form action="#" method="get" name="studentSearch" autocomplete="off">
                          <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Search name of student" id="searchbox">
                            <span class="input-group-btn">
                                  <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                                  </button>
                                </span>
                          </div>
                        </form>
                        @push('scripts')
                        <script>
                        $(document).on('submit',"form[name='studentSearch']",function(){
                          event.preventDefault();
                          if($('#searchbox').val().length > 0){
                            window.location = "/adirectory/search/s/"+$('#searchbox').val();
                          }
                          
                        });
                        </script>
                        @endpush
                  </div>                                
                </div>
            </div><!--./row-->
{{--  Start Results of students list  --}}
@if(isset($classmates))
            <div class="row">
              @foreach($classmates as $classmate)
                <div class="container col-sm-6 col-md-3">
                    <div class="info-box">
                    <a href="/profile/student/{{$classmate->id}}">
                        <span class="info-box-icon bg-olive">
                            {{-- <div class=" image"><i class="fa fa-user"></i> --}}
                            <div class=" image"><img class="img-circle" src="/uploads/profile/{{$classmate->profilepic}}" style="max-width:80px;" />
                                {{-- <img src="{{ asset("/bower_components/admin-lte/dist/img/user2-160x160.jpg") }}" class="img-circle" alt="User Image" style="max-width:80px;" /> --}}
                                
                            </div>
                        </span>                 
                        <div class="info-box-content">
                            <span class="info-box-text">{{$classmate->firstname}}</span>
                            <span class="info-box-text">{{$classmate->lastname}}</span>
                            <span class="info-box-number">{{$classmate->grade}} {{$classmate->section}}</span>
                        </div>
                      </a>
                    </div>
                </div>  <!--container end-->
                @endforeach
            </div><!--./row-->
            @endif
{{--  END results of students list --}}

        </div><!--./box-body-->
        <div class="box-footer">
          
        </div><!--./boxfooter-->
      </div><!--./box-->
    </div><!--./col-->

    
  </div><!--./row-->

@endsection