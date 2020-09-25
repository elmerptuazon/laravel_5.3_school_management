@extends('admin_template')

@section('content')

<div class="row">
          @if(session()->has('error'))
            <div class="alert alert-danger taskAlert">
                {{ session()->get('error') }}
            </div>
          @elseif(session()->has('message'))
            <div class="alert alert-success taskAlert">
                {{ session()->get('message') }}
            </div>
          @endif

          @push('scripts')
          <script>
            $('div.taskAlert').fadeOut(3000);
            </script>
          @endpush
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border" style="margin: 5px 5px;">
              <h3 class="box-title"></h3>
              <div class="box-tools">          
                <!-- <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
              </div>
            </div><!--boxheader-->
            <div class="box-body">
              <div class="row">
                <div style="margin-bottom: 15px;" class="col-lg-4 col-lg-offset-4">
                  <form action="#" method="get" name="teacherSearch" autocomplete="off">
                    <div class="input-group">
                      <input type="text" name="q" class="form-control" placeholder="Search Teacher Name..." id="searchbox">
                      <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                        </button>
                      </span>
                    </div>
                  </form> 
                  @push('scripts')
                      <script>
                      $(document).on('submit',"form[name='teacherSearch']",function(){
                        event.preventDefault();
                        if($('#searchbox').val().length >= 3){
                          window.location = "/adirectory/search/t/"+$('#searchbox').val();
                        }
                        
                      });
                      </script>
                      @endpush
                  
                  
{{-- 
                  <form action="#" method="get" name="studentSearch">
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
                      @endpush --}}

                </div>
              </div><!--./row-->
    @foreach($teachers as $teacher)
                <!--userX-->
                <div class="col-md-3">
                    <div class="box ">
                        <div class="box-body box-profile">
                        <a href="/tprofile/{{$teacher->id}}">
                            <img class="profile-user-img img-responsive img-circle" src="{{ asset("/uploads/profile/".$teacher->profilepic) }}" alt="User profile picture">
                            <h3 class="profile-username text-center" style="margin-bottom: 1px;">{{ ucwords($teacher->firstname) }} {{ ucwords($teacher->lastname) }}</h3>
                            <p class="text-muted text-center" style="margin-bottom: 1px;"><i class="fa fa-mobile"></i> {{$teacher->t_cellno}}</p>
                            <div class="text-center" style="margin-bottom: 1px;">
                                @foreach($teacher->class as $tsubject)
                            <a href="/adirectory/students/{{$tsubject->grade}}-{{$tsubject->section}}">{{$tsubject->subj}}({{$tsubject->grade}})</a> 
                            &nbsp;
                                @endforeach
                                
                            </div>
                            <div class="text-center">
                                <button class="btn btn-sm btn-primary" style="margin-top: 10px;" disabled>Message</button>
                            </div>
                        </a>
                        </div><!-- /.box-body -->
                    </div>
                </div>
    @endforeach
                <!--./userX-->
 
            </div>
          </div><!--./box-->
        </div><!--./col-->
    
      </div><!--./row-->

@endsection