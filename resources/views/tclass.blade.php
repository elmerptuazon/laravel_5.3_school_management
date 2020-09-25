@extends('admin_template')

@section('content')
{{-- @foreach($subjects as $subj)
    {{$subj->subj}}
@endforeach --}}
    <div class="row">
        {{-- START Assignments --}}
        <div class="col-md-6">
            <div class="box box-primary" >
                <div class="box-header with-border">
                <h3 class="box-title">{{UCWORDS($subject)}} Assignments </h3>
                    <div class="box-tools pull-right">
                            
                                    
                                    <input type="text" id="datepicker" class="form-control" name="date" style="width: 100px; border: 0; line-height: 12px; height: 1px;padding: 5px; display: inline;">
                                       <span class="fa fa-calendar"></span>
                                    
                                    <button class="btn btn-box-tool" id='hideshow' title="add assignment">
                                            <i class="fa fa-plus-circle" style="font-size: 16px;"></i></button>
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                {{-- <div class="myContainer"> --}}

                        <div class="box " id="myForm">
                          <style>
                            #myForm,
                            #testUpload,
                            #asUpload,
                            #hoUpload {
                              display: none;
                              margin: 20px;
                              width: auto;
                            }
                          </style>
                
                          <div class="box-header with-border">
                            <h3 class="box-title">Post Assignment Form</h3>
                          
                          </div>
                          <form role="form" method="post" enctype="multipart/form-data" action="/assignment/post" autocomplete="off">
                            <div class="box-body">
                              {!! csrf_field() !!}
                              <div class="form-group col-md-6">
                                <label for="exampleInputSubject">Publish Date</label>
                                <input type="text" class="form-control" id="test2" name = "pubdate"placeholder="Enter Title" required>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="exampleInputSubject">Subject</label>
                
                                <select class="form-control" name="class">
                                    <option value="{{$subject}}_{{$grade}}_{{$section}}"selected>{{$subject}} {{$grade}} - {{$section}}</option>
                                  @foreach($subjects as $subj)
                                <option  value="{{$subj->subj}}_{{$subj->grade}}_{{$subj->section}}">
                                    {{UCWORDS($subj->subj)}} {{UCWORDS($subj->grade)}} - {{UCWORDS($subj->section)}}
                                </option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="form-group col-md-12">
                                <textarea id="editor1" name="description" rows="10" cols="80">
                                  
                                </textarea>
                              </div>
                
                              <div class="form-group col-md-6">
                                <button type="submit" class="btn btn-primary">Post Assignment</button>
                              </div>
                              
                            </div>
                            <div class="box-footer">
                            </div>
                          </form>
                        </div>
                      {{-- </div> --}}
                <style>
                        .target{
                            overflow: hidden;
                            height:40px;
                            position: relative;
                            border:0px solid #999;
                            transition: .3s ease;
                            cursor: pointer;
                        
                        }

                        </style>
                <div id="div1" class="box-body" >
                   
                    <ul style="list-style: none;" id="hw">
                        
                        @foreach($homeworks as $homework)
                        <li style="border-bottom: 1px solid #ccc;">
                        <div id="HWcontent{{$homework->id}}">
                            <h4 style="font-weight:bold;">{{UCWORDS($homework->subject)}} {{$homework->grade .$homework->section}}</h4>
                            <span style="float:right; font-style:italic;">{{date("l M d, Y", strtotime($homework->pubdate))}}</span>
                            <span style="display:block;">{{$homework->title}} {{UCWORDS($homework->firstname)}} {{UCWORDS($homework->lastname)}}</span>
                            <div class="target">
                                {!! htmlspecialchars_decode($homework->description)!!}
                                <br /><br />
                                <div class="pull-right">
                                  <button type="button" id="editShow{{$homework->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                                  {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> data-href="/delete.php?id=54" --}}
                                  
                                  <button type="button" id="delShow{{$homework->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-delete{{$homework->id}}"><i class="fa  fa-trash"></i></button>
                                </div> 
                            </div>
                        </div>
                            <span class="read-more pull-right-container" style="display:block; width:100%;">
                                <i class="fa  pull-right  fa-angle-up"></i>
                            </span>
            {{-- START EDIT assignment --}}
            <div class="box box-danger" id="HW{{$homework->id}}">
                <style>
                  #HW{{$homework->id}} {
                    display: none;
                    margin: 20px;
                    width: auto;
                  }
                </style>
      
                <div class="box-header with-border">
                  <h3 class="box-title">EDIT Assignment </h3>
                  <!-- <button class="btn btn-box-tool pull-right" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
            <button class="btn btn-box-tool pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
                </div>
              <form role="form" method="post" action="/ajaxAssignment/e/{{$homework->id}}" enctype="multipart/form-data"  name="editAssignment{{$homework->id}}" autocomplete="off">
                  <div class="box-body">
                    {!! csrf_field() !!}
                    <div class="form-group col-md-6">
                      
                    <input type="hidden" name="hw" value="{{$homework->id}}" >
                      <label for="exampleInputSubject">Publish Date</label>
                    <input type="text" class="form-control" id="datepicker{{$homework->id}}" name = "pubdate"placeholder="Enter Title" value="{{$homework->pubdate}}" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputSubject">Subject</label>
      
                      <select class="form-control" name="class">
                      <option value="{{$homework->subject}}_{{$homework->grade}}_{{$homework->section}}"selected>{{$homework->subject}} {{$homework->grade}} - {{$homework->section}}</option>
                      {{-- <option value="{{$subject}}_{{$grade}}_{{$section}}"selected>{{$subject}} {{$grade}} - {{$section}}</option> --}}
                        @foreach($subjects as $subj)
                      <option  value="{{$subj->subj}}_{{$subj->grade}}_{{$subj->section}}">{{UCWORDS($subj->subj)}} {{UCWORDS($subj->grade)}} - {{UCWORDS($subj->section)}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-12">
                    <textarea id="editorHW{{$homework->id}}" name="description" rows="10" cols="80">
                        {{htmlspecialchars_decode($homework->description)}}
                      </textarea>
                    </div>
      
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary" id="editAjax{{$homework->id}}">Edit this Assignment</button>
                      
                    </div>
                    <div class="form-group col-md-6" id="chars">
                        <button type="button" class="btn" id="editCancel{{$homework->id}}">Cancelt</button>
                    </div>
                  </div>
                  <div class="box-footer">
                  </div>
                </form>
              </div>
              {{-- END EDIT assignment --}}
              
          {{-- START modal for delete start --}}
              <div class="modal fade" id="confirm-delete{{$homework->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              Confirm Delete Homework {{$homework->id}} - {{$homework->subject}} {{$homework->grade}} {{$homework->section}} 
                          </div>
                          <div class="modal-body">
                              <strong>Are you sure you want to delete this assignment?</strong> <p> {!! htmlspecialchars_decode(substr($homework->description, 0, 360)) !!}...</p>
                          </div>
                          <div class="modal-footer">
                              
                              <form role="form" method="post" action="/ajaxAssignment/x/{{$homework->id}}" enctype="multipart/form-data"  name="delAssignment{{$homework->id}}" >
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                {!! csrf_field() !!}
                                <input type="hidden" name="hw" value="{{$homework->id}}" />
                              <button type="submit" class="btn btn-danger btn-ok" id="delHW{{$homework->id}}" data-dismiss="modal">Delete</button>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
          {{--END modal for delete  --}}
                            
                        </li>
                        @endforeach
                        
                    </ul>
                    
                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                    {{-- <form action='#'>
                        <input type='text' placeholder='New task' class='form-control input-sm' />
                    </form> style="position: absolute; right: 10px;"--}}

                <button type="button" class="btn  btn-primary center-block" id="hwLoader">load more</button>
                <button type="button" class="btn  btn-primary center-block hidden" id="hwShowAll" onclick="location.href='/tclass/{{$subject}}/{{$grade}}-{{$section}}';">More Homework</button>
                    {{-- <button type="button" class="btn  btn-primary pull-right"  >{{date("M d, Y")}}</button> --}}
                </div><!-- /.box-footer-->
                <div class="overlay" style="display:none;" id="loaderTarget">
                        <i class="fa fa-refresh fa-spin"></i>
                      </div>
            </div>
        </div>
        {{-- END Assignments --}}


        {{-- START Tests --}}
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Tests</h3>
                    <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" id='hideshowTest' title="upload test">
                                    <i class="fa fa-cloud-upload" style="font-size: 16px;"></i></button>
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                  
                    </div>
                </div>
                {{-- START Test UPLOAD --}}
                <div class="box " id="testUpload">

                        <div class="box-header with-border">
                        <h3 class="box-title">Upload a Test  for {{$subject}}</h3>

                        </div>
                        <form role="form" method="post" enctype="multipart/form-data" action="/upload" autocomplete="off">
                          <div class="box-body">
                            {!! csrf_field() !!}
                            <div class="form-group col-md-12">
                              <label for="title">TItle</label>
                              <input type="text" class="form-control" id="testTitle" name = "title" placeholder="Enter Title" required>
                              <input type="hidden" class="form-control"  name = "utype" value="test">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputSubject">Class</label>
              
                              <select class="form-control" name="class">

                                {{-- @foreach($subjects as $subj) --}}
                              {{-- <option  value="{{$subj->subj}}_{{$subj->grade}}_{{$subj->section}}">
                                  {{UCWORDS($subj->subj)}} {{UCWORDS($subj->grade)}} - {{UCWORDS($subj->section)}}
                              </option> --}}
                              {{-- @endforeach --}}
                              <option  value="{{$subject}}_{{$grade}}_{{$section}}" selected>
                                {{UCWORDS($subject)}} {{UCWORDS($grade)}} - {{UCWORDS($section)}}
                            </option>
                                
                              </select>
                            </div>
                          <div class="form-group col-md-6">
                            <label for="period">Period</label>
                            <select class="form-control" name="period" required>
                                <option></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                          </div>
                                       
              
                            
                            <div class="form-group col-md-12">
                                    <div class="form-group">
                                            <label for="exampleInputFile">File input</label>
                                            <input type="file" id="testInputFile" name="thefile" class="btn btn-default col-md-12" required>

                                            @push('scripts')
                                            <script>
                                                $(document).on('click','#testInputFile',function(){
                                                    $("#loaderTarget").fadeIn(500);
                                                })
                                                .on('change','#testInputFile',function(){
                                                    $("#loaderTarget").fadeOut(500);
                                                    if($("#testTitle").val().length){
                                                    $("#testSubmit").removeAttr('disabled');
                                                    }
                                                }) .on('blur','#testInputFile',function(){
                                                    setTimeout(function(){
                                                        if($("#testInputFile").val().length == 0){$("#loaderTarget").fadeOut(500); }
                                                    }, 20000);
                                                   
                                                });
                                                $(document).on('submit',"form[action='/upload']",function(){
                                                    $("#loaderTarget").fadeIn(500);
                                                });
                                                $(document).on('change','#testTitle',function(){
                                                    if($("#testInputFile").val().length){
                                                    $("#testSubmit").removeAttr('disabled');
                                                    }
                                                })
                                            </script>
                                            @endpush
                          
                                            <p class="help-block">Upload PDF files only.  limit of 10mb filesize</p>
                                          </div>    
                            </div>
                            <div class="form-group col-md-12">
                                    
                                    <button type="submit" class="btn btn-primary pull-right" id="testSubmit" disabled><i class="fa fa-upload"></i> <strong>Click to Upload</strong></button>
                                  </div>   
                            
                          </div>
                          <div class="box-footer">
                          </div>
                        </form>
                        <div class="overlay" style="display:none;" id="loaderTarget">
                                <i class="fa fa-refresh fa-spin"></i>
                              </div>
                </div>
                {{-- END Test UPLOAD --}}

                <div class="box-body">
                        <table class="table table-bordered table-striped table-hover">
                                <tbody><tr>
                                  <th style="width:100px;">date</th>
                                  <th>Title</th>
                                  
                                  <th style="width:50px;">Period</th>
                                  <th style="width: 200px">Options</th>
                                </tr>
                                @foreach($tests as $test)
                                <tr id="TESTcontent{{$test->id}}">
                                  <td>{{date("M d, Y", strtotime($test->date))}}</td>
                                <td><a href="{{asset('view/test/'.$test->id)}}">{{ $test->title}}</a>  : {{$test->grade}} - {{ucwords($test->section)}}</td>
                                <td>{{$test->period}}</td>
                                <td><div class="pull-left">
                                    <button type="button" id="editTestShow{{$test->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                                    {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> data-href="/delete.php?id=54" --}}
                                    
                                    <button type="button" id="delTestShow{{$test->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-test-delete{{$test->id}}"><i class="fa  fa-trash"></i></button>
                                  </div> </td>  
                                </tr>
                                <tr><td colspan="5">
{{-- START EDIT Test --}}
<div class="box box-danger" id="TEST{{$test->id}}">
        <style>
          #TEST{{$test->id}} {
            display: none;
            margin: 20px;
            width: auto;
          }
        </style>

        <div class="box-header with-border">
          <h3 class="box-title">EDIT Test </h3>
          <!-- <button class="btn btn-box-tool pull-right" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
    <button class="btn btn-box-tool pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
        </div>
      <form role="form" method="post" action="/ajaxTest/e/{{$test->id}}" enctype="multipart/form-data"  name="editTest{{$test->id}}" autocomplete="off">
          <div class="box-body">
            {!! csrf_field() !!}
            <div class="form-group col-md-4">
              
            <input type="hidden" name="test" value="{{$test->id}}" > 
              <label for="exampleInputSubject">Publish Date</label>
            <input type="text" class="form-control" id="datepicker{{$test->id}}" name = "pubdate"placeholder="Enter Title" value="{{$test->date}}" required>
            </div>
            <div class="form-group col-md-4">
              <label for="exampleInputSubject">Subject</label>

              <select class="form-control" name="class">
              <option value="{{$test->subject}}_{{$test->grade}}_{{$test->section}}"selected>{{$test->subject}} {{$test->grade}} - {{$test->section}}</option>
                @foreach($subjects as $subj)
              <option  value="{{$subj->subj}}_{{$subj->grade}}_{{$subj->section}}">{{UCWORDS($subj->subj)}} {{UCWORDS($subj->grade)}} - {{UCWORDS($subj->section)}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-4">
              
                    <input type="hidden" name="test" value="{{$test->id}}" >
                      <label for="exampleInputSubject">Period</label>
                    
                    <select class="form-control" name="period">
                    <option value="{{$test->period}}" selected>{{$test->period}}</option>
                    <option value="1" >1</option>
                    <option value="2" >2</option>
                    <option value="3" >3</option>
                    <option value="4" >4</option>
                    
                    </select>
                    </div>
            <div class="form-group col-md-12">
                    <input type="text" class="form-control"name = "title" placeholder="Enter Title" value="{{$test->title}}">
            </div>

            <div class="form-group col-md-6">
              <button type="submit" class="btn btn-primary" id="editAjax{{$test->id}}">Edit this Test</button>
              
            </div>
            <div class="form-group col-md-6" id="chars">
                <button type="button" class="btn" id="editTestCancel{{$test->id}}">Cancel</button>
            </div>
          </div>
          <div class="box-footer">
          </div>
        </form>
      </div>
      {{-- END EDIT Test --}}
                                </td></tr>
            
            {{-- START modal for delete start --}}
              <div class="modal fade" id="confirm-test-delete{{$test->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                Confirm Delete Test {{$test->id}} - {{$test->subject}} {{$test->grade}} {{$test->section}} 
                            </div>
                            <div class="modal-body">
                                <strong>Are you sure you want to delete this TEST?</strong> <p> {!! htmlspecialchars_decode(substr($test->title, 0, 360)) !!}...</p>
                            </div>
                            <div class="modal-footer">
                                
                                <form role="form" method="post" action="/ajaxTest/x/{{$test->id}}" enctype="multipart/form-data"  name="delTest{{$test->id}}">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                  {!! csrf_field() !!}
                                  <input type="hidden" name="test" value="{{$test->id}}" />
                                <button type="submit" class="btn btn-danger btn-ok" id="delTEST{{$test->id}}" data-dismiss="modal">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            {{--END modal for delete  --}}
            <tr id="editTestSuccess" style="display:none">
                    <td colspan="4">
                  <div class="alert alert-success alert-dismissible center-block" style="z-index: 1; width:auto;">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <h4><i class="icon fa fa-ban"></i> Success!</h4>
                      The Test {{ $test->title}} has been edited.
                    </div>
                  </td>
                </tr>
                                @endforeach
                                
                                
                              </tbody></table>
                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                        {{-- <button type="button" class="btn  btn-success center-block">Show All Schedule</button> --}}
                </div>
                <div class="overlay" style="display:none;" id="loaderTargetTest">
                        <i class="fa fa-refresh fa-spin"></i>
                      </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
        {{-- END Tests --}}

    {{-- </div>

    <div class="row"> --}}
{{-- START Activitysheets --}}
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Activity Sheets</h3>
                    <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" id='hideshowAS' title="upload test">
                                    <i class="fa fa-cloud-upload" style="font-size: 16px;"></i></button>
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>

        {{-- START Activitysheet UPLOAD --}}
                <div class="box " id="asUpload">

                        <div class="box-header with-border">
                        <h3 class="box-title">Upload an ActivitySheet  for {{$subject}}</h3>

                        </div>
                        <form role="form" method="post" enctype="multipart/form-data" action="/upload" autocomplete="off">
                          <div class="box-body">
                            {!! csrf_field() !!}
                            <div class="form-group col-md-12">
                              <label for="title">TItle</label>
                              <input type="text" class="form-control" id="asTitle" name = "title"placeholder="Enter Title" required>
                              <input type="hidden" class="form-control"  name = "utype" value="activitysheet">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputSubject">Class</label>
              
                              <select class="form-control" name="class">

                                {{-- @foreach($subjects as $subj) --}}
                              {{-- <option  value="{{$subj->subj}}_{{$subj->grade}}_{{$subj->section}}">
                                  {{UCWORDS($subj->subj)}} {{UCWORDS($subj->grade)}} - {{UCWORDS($subj->section)}}
                              </option> --}}
                              {{-- @endforeach --}}
                              <option  value="{{$subject}}_{{$grade}}_{{$section}}" selected>
                                {{UCWORDS($subject)}} {{UCWORDS($grade)}} - {{UCWORDS($section)}}
                            </option>
                                
                              </select>
                            </div>
                          <div class="form-group col-md-6">
                            <label for="period">Period</label>
                            <select class="form-control" name="period" required>
                                <option></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                          </div>
                                       
              
                            
                            <div class="form-group col-md-12">
                                    <div class="form-group">
                                            <label for="exampleInputFile">File input</label>
                                            <input type="file" id="asInputFile" name="thefile" class="btn btn-default col-md-12" required>

                                            @push('scripts')
                                            <script>
                                                $(document).on('click','#asInputFile',function(){
                                                    $("#loaderTargetAs").fadeIn(500);
                                                })
                                                .on('change','#asInputFile',function(){
                                                    $("#loaderTargetAs").fadeOut(500);
                                                    if($("#asTitle").val().length){
                                                    $("#asSubmit").removeAttr('disabled');
                                                    }
                                                }) .on('blur','#asInputFile',function(){
                                                    setTimeout(function(){
                                                        if($("#asInputFile").val().length == 0){$("#loaderTargetAs").fadeOut(500); }
                                                    }, 20000);
                                                   
                                                });
                                                $(document).on('submit',"form[action='/upload']",function(){
                                                    $("#loaderTargetAs").fadeIn(500);
                                                });
                                                $(document).on('change','#asTitle',function(){
                                                    if($("#asInputFile").val().length){
                                                    $("#asSubmit").removeAttr('disabled');
                                                    }
                                                })
                                            </script>
                                            @endpush
                          
                                            <p class="help-block">Upload PDF files only.  limit of 10mb filesize</p>
                                          </div>    
                            </div>
                            <div class="form-group col-md-12">
                                    
                                    <button type="submit" class="btn btn-primary pull-right" id="asSubmit" disabled><i class="fa fa-upload"></i> <strong>Click to Upload</strong></button>
                                  </div>   
                            
                          </div>
                          <div class="box-footer">
                          </div>
                        </form>
                        <div class="overlay" style="display:none;" id="loaderTargetAs">
                                <i class="fa fa-refresh fa-spin"></i>
                              </div>
                </div>
                {{-- END Activitysheet UPLOAD --}}

                <div class="box-body">
                        <table class="table table-bordered table-striped table-hover">
                                <tbody><tr>
                                        <th style="width:100px;">date</th>
                                        <th>Title</th>
                                        
                                        <th style="width:50px;">Period</th>
                                        <th style="width: 200px">Options</th>
                                      </tr>
                                      @foreach($activitysheets as $activitysheet)
                                      <tr id="AScontent{{$activitysheet->id}}">
                                        <td>{{date("M d, Y", strtotime($activitysheet->date))}}</td>
                                      <td><a href="{{asset('view/as/'.$activitysheet->id)}}">{{ $activitysheet->title}}</a></td>
                                      <td>{{$activitysheet->period}}</td>
                                      <td><div class="pull-left">
                                            <button type="button" id="editAsShow{{$activitysheet->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                                            {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> data-href="/delete.php?id=54" --}}
                                            
                                            <button type="button" id="delAsShow{{$activitysheet->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-as-delete{{$activitysheet->id}}"><i class="fa  fa-trash"></i></button>
                                          </div></td>
                                      </tr>
                                      <tr><td colspan="5">
                            {{-- START EDIT Activitysheet --}}
                                            <div class="box box-danger" id="AS{{$activitysheet->id}}">
                                                    <style>
                                                      #AS{{$activitysheet->id}} {
                                                        display: none;
                                                        margin: 20px;
                                                        width: auto;
                                                      }
                                                    </style>
                                            
                                                    <div class="box-header with-border">
                                                      <h3 class="box-title">EDIT ActivitySheet </h3>
                                                      <!-- <button class="btn btn-box-tool pull-right" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                                                <button class="btn btn-box-tool pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
                                                    </div>
                                                  <form role="form" method="post" action="/ajaxActivitysheet/e/{{$activitysheet->id}}" enctype="multipart/form-data"  name="editAs{{$activitysheet->id}}" autocomplete="off">
                                                      <div class="box-body">
                                                        {!! csrf_field() !!}
                                                        <div class="form-group col-md-4">
                                                          
                                                        <input type="hidden" name="as" value="{{$activitysheet->id}}" > 
                                                          <label for="exampleInputSubject">Publish Date</label>
                                                        <input type="text" class="form-control" id="datepicker{{$activitysheet->id}}" name = "pubdate"placeholder="Enter Title" value="{{$activitysheet->date}}" required>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                          <label for="exampleInputSubject">Subject</label>
                                            
                                                          <select class="form-control" name="class">
                                                          <option value="{{$activitysheet->subject}}_{{$activitysheet->grade}}_{{$activitysheet->section}}"selected>{{$activitysheet->subject}} {{$activitysheet->grade}} - {{$activitysheet->section}}</option>
                                                            @foreach($subjects as $subje)
                                                          <option  value="{{$subje->subj}}_{{$subje->grade}}_{{$subje->section}}">{{UCWORDS($subje->subj)}} {{UCWORDS($subje->grade)}} - {{UCWORDS($subje->section)}}</option>
                                                            @endforeach
                                                          </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                          
                                                                <input type="hidden" name="test" value="{{$activitysheet->id}}" >
                                                                  <label for="exampleInputSubject">Period</label>
                                                                
                                                                <select class="form-control" name="period">
                                                                <option value="{{$activitysheet->period}}" selected>{{$activitysheet->period}}</option>
                                                                <option value="1" >1</option>
                                                                <option value="2" >2</option>
                                                                <option value="3" >3</option>
                                                                <option value="4" >4</option>
                                                                
                                                                </select>
                                                                </div>
                                                        <div class="form-group col-md-12">
                                                                <input type="text" class="form-control"name = "title" placeholder="Enter Title" value="{{$activitysheet->title}}">
                                                        </div>
                                            
                                                        <div class="form-group col-md-6">
                                                          <button type="submit" class="btn btn-primary" id="editAjax{{$activitysheet->id}}">Edit this Test</button>
                                                          
                                                        </div>
                                                        <div class="form-group col-md-6" id="chars">
                                                            <button type="button" class="btn" id="editAsCancel{{$activitysheet->id}}">Cancel</button>
                                                        </div>
                                                      </div>
                                                      <div class="box-footer">
                                                      </div>
                                                    </form>
                                                  </div>
                                {{-- END EDIT Activitysheet --}}
                                                                            </td></tr>   
                                                            
            {{-- START modal for delete activitysheet --}}
            <div class="modal fade" id="confirm-as-delete{{$activitysheet->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                Confirm Delete ActivitySheet {{$activitysheet->id}} - {{$activitysheet->subject}} {{$activitysheet->grade}} {{$activitysheet->section}} 
                            </div>
                            <div class="modal-body">
                                <strong>Are you sure you want to delete this ACTIVITYSHEET?</strong> <p> {!! htmlspecialchars_decode(substr($activitysheet->title, 0, 360)) !!}...</p>
                            </div>
                            <div class="modal-footer">
                                
                                <form role="form" method="post" action="/ajaxAS/x/{{$activitysheet->id}}" enctype="multipart/form-data"  name="delAs{{$activitysheet->id}}" autocomplete="off">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                  {!! csrf_field() !!}
                                  <input type="hidden" name="as" value="{{$activitysheet->id}}" />
                                <button type="submit" class="btn btn-danger btn-ok" id="delAs{{$activitysheet->id}}" data-dismiss="modal">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            {{--END modal for delete activitysheet --}}
            <tr id="editAsSuccess" style="display:none">
                    <td colspan="4">
                  <div class="alert alert-success alert-dismissible center-block" style="z-index: 1; width:auto;">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <h4><i class="icon fa fa-ban"></i> Success!</h4>
                      The Activitysheet {{ $activitysheet->title}} has been edited.
                    </div>
                  </td>
                </tr>
                                      @endforeach
                                      
                                      
                                    </tbody></table>
                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                        {{-- <button type="button" class="btn  btn-success center-block">Show All Schedule</button> --}}
                </div>
                <div class="overlay" style="display:none;" id="loaderTargetAsEdit">
                        <i class="fa fa-refresh fa-spin"></i>
                      </div>
            </div><!-- /.box -->
        </div>
        {{-- END Activitysheets --}}

        {{-- START Handouts --}}
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Handouts and Notes</h3>
                    <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" id='hideshowHO' title="upload test">
                                    <i class="fa fa-cloud-upload" style="font-size: 16px;"></i></button>
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>

        {{-- START Handout UPLOAD --}}
                <div class="box " id="hoUpload">

                        <div class="box-header with-border">
                        <h3 class="box-title">Upload an Handouts  for {{$subject}}</h3>

                        </div>
                        <form role="form" method="post" enctype="multipart/form-data" action="/upload" autocomplete="off">
                          <div class="box-body">
                            {!! csrf_field() !!}
                            <div class="form-group col-md-12">
                              <label for="title">TItle</label>
                              <input type="text" class="form-control" id="hoTitle" name = "title"placeholder="Enter Title" required>
                              <input type="hidden" class="form-control"  name = "utype" value="handout">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputSubject">Class</label>
              
                              <select class="form-control" name="class">

                                {{-- @foreach($subjects as $subj) --}}
                              {{-- <option  value="{{$subj->subj}}_{{$subj->grade}}_{{$subj->section}}">
                                  {{UCWORDS($subj->subj)}} {{UCWORDS($subj->grade)}} - {{UCWORDS($subj->section)}}
                              </option> --}}
                              {{-- @endforeach --}}
                              <option  value="{{$subject}}_{{$grade}}_{{$section}}" selected>
                                {{UCWORDS($subject)}} {{UCWORDS($grade)}} - {{UCWORDS($section)}}
                            </option>
                                
                              </select>
                            </div>
                          <div class="form-group col-md-6">
                            <label for="period">Period</label>
                            <select class="form-control" name="period" required>
                                <option></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                          </div>
                                       
              
                            
                            <div class="form-group col-md-12">
                                    <div class="form-group">
                                            <label for="exampleInputFile">File input</label>
                                            <input type="file" id="hoInputFile" name="thefile" class="btn btn-default col-md-12" required>

                                            @push('scripts')
                                            <script>
                                                $(document).on('click','#hoInputFile',function(){
                                                    $("#loaderTargetHo").fadeIn(500);
                                                })
                                                .on('change','#hoInputFile',function(){
                                                    $("#loaderTargetHo").fadeOut(500);
                                                    if($("#hoTitle").val().length){
                                                    $("#hoSubmit").removeAttr('disabled');
                                                    }
                                                }) .on('blur','#hoInputFile',function(){
                                                    setTimeout(function(){
                                                        if($("#hoInputFile").val().length == 0){$("#loaderTargetHo").fadeOut(500); }
                                                    }, 20000);
                                                   
                                                });
                                                $(document).on('submit',"form[action='/upload']",function(){
                                                    $("#loaderTargetHo").fadeIn(500);
                                                });
                                                $(document).on('change','#hoTitle',function(){
                                                    if($("#hoInputFile").val().length){
                                                    $("#hoSubmit").removeAttr('disabled');
                                                    }
                                                })
                                            </script>
                                            @endpush
                          
                                            <p class="help-block">Upload PDF files only.  limit of 10mb filesize</p>
                                          </div>    
                            </div>
                            <div class="form-group col-md-12">
                                    
                                    <button type="submit" class="btn btn-primary pull-right" id="hoSubmit" disabled><i class="fa fa-upload"></i> <strong>Click to Upload</strong></button>
                                  </div>   
                            
                          </div>
                          <div class="box-footer">
                          </div>
                        </form>
                        <div class="overlay" style="display:none;" id="loaderTargetHo">
                                <i class="fa fa-refresh fa-spin"></i>
                              </div>
                </div>
                {{-- END Handout UPLOAD --}}

                <div class="box-body">
                        <table class="table table-bordered table-striped table-hover">
                                <tbody><tr>
                                        <th style="width:100px;">date</th>
                                        <th>Title</th>
                                        
                                        <th style="width:50px;">Period</th>
                                        <th style="width: 200px">Options</th>
                                      </tr>
                                      @foreach($handouts as $handout)
                                      <tr id="HOcontent{{$handout->id}}">
                                        <td>{{date("M d, Y", strtotime($handout->date))}}</td>
                                      <td><a href="{{asset('view/ho/'.$handout->id)}}">{{ $handout->title}}</a></td>
                                      <td>{{$handout->period}}</td>
                                      <td><div class="pull-left">
                                            <button type="button" id="editHoShow{{$handout->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                                            {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> data-href="/delete.php?id=54" --}}
                                            
                                            <button type="button" id="delHoShow{{$handout->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-ho-delete{{$handout->id}}"><i class="fa  fa-trash"></i></button>
                                          </div></td>
                                      </tr>
                                      <tr><td colspan="5">
                            {{-- START EDIT Handout --}}
                                            <div class="box box-danger" id="HO{{$handout->id}}">
                                                    <style>
                                                      #HO{{$handout->id}} {
                                                        display: none;
                                                        margin: 20px;
                                                        width: auto;
                                                      }
                                                    </style>
                                            
                                                    <div class="box-header with-border">
                                                      <h3 class="box-title">EDIT Handout </h3>
                                                      <!-- <button class="btn btn-box-tool pull-right" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                                                <button class="btn btn-box-tool pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
                                                    </div>
                                                  <form role="form" method="post" action="/ajaxHandout/e/{{$handout->id}}" enctype="multipart/form-data"  name="editHo{{$handout->id}}" autocomplete="off">
                                                      <div class="box-body">
                                                        {!! csrf_field() !!}
                                                        <div class="form-group col-md-4">
                                                          
                                                        <input type="hidden" name="ho" value="{{$handout->id}}" > 
                                                          <label for="exampleInputSubject">Publish Date</label>
                                                        <input type="text" class="form-control" id="datepicker{{$handout->id}}" name = "pubdate"placeholder="Enter Title" value="{{$handout->date}}" required>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                          <label for="exampleInputSubject">Subject</label>
                                            
                                                          <select class="form-control" name="class">
                                                          <option value="{{$handout->subject}}_{{$handout->grade}}_{{$handout->section}}"selected>{{$handout->subject}} {{$handout->grade}} - {{$handout->section}}</option>
                                                            @foreach($subjects as $subje)
                                                          <option  value="{{$subje->subj}}_{{$subje->grade}}_{{$subje->section}}">{{UCWORDS($subje->subj)}} {{UCWORDS($subje->grade)}} - {{UCWORDS($subje->section)}}</option>
                                                            @endforeach
                                                          </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                          
                                                                <input type="hidden" name="test" value="{{$handout->id}}" >
                                                                  <label for="exampleInputSubject">Period</label>
                                                                
                                                                <select class="form-control" name="period">
                                                                <option value="{{$handout->period}}" selected>{{$handout->period}}</option>
                                                                <option value="1" >1</option>
                                                                <option value="2" >2</option>
                                                                <option value="3" >3</option>
                                                                <option value="4" >4</option>
                                                                
                                                                </select>
                                                                </div>
                                                        <div class="form-group col-md-12">
                                                                <input type="text" class="form-control"name = "title" placeholder="Enter Title" value="{{$handout->title}}">
                                                        </div>
                                            
                                                        <div class="form-group col-md-6">
                                                          <button type="submit" class="btn btn-primary" id="editAjax{{$handout->id}}">Edit this Test</button>
                                                          
                                                        </div>
                                                        <div class="form-group col-md-6" id="chars">
                                                            <button type="button" class="btn" id="editAsCancel{{$handout->id}}">Cancel</button>
                                                        </div>
                                                      </div>
                                                      <div class="box-footer">
                                                      </div>
                                                    </form>
                                                  </div>
                                {{-- END EDIT Handout --}}
                                                                            </td></tr>   
                                                            
            {{-- START modal for delete handout --}}
            <div class="modal fade" id="confirm-ho-delete{{$handout->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                Confirm Delete ActivitySheet {{$handout->id}} - {{$handout->subject}} {{$handout->grade}} {{$handout->section}} 
                            </div>
                            <div class="modal-body">
                                <strong>Are you sure you want to delete this HANDOUT?</strong> <p> {!! htmlspecialchars_decode(substr($handout->title, 0, 360)) !!}...</p>
                            </div>
                            <div class="modal-footer">
                                
                                <form role="form" method="post" action="/ajaxHO/x/{{$handout->id}}" enctype="multipart/form-data"  name="delHo{{$handout->id}}" autocomplete="off">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                  {!! csrf_field() !!}
                                  <input type="hidden" name="ho" value="{{$handout->id}}" />
                                <button type="submit" class="btn btn-danger btn-ok" id="delHo{{$handout->id}}" data-dismiss="modal">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            {{--END modal for delete handout --}}
            <tr id="editHoSuccess" style="display:none">
                    <td colspan="4">
                  <div class="alert alert-success alert-dismissible center-block" style="z-index: 1; width:auto;">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <h4><i class="icon fa fa-ban"></i> Success!</h4>
                      The Handout {{ $handout->title}} has been edited.
                    </div>
                  </td>
                </tr>
                                      @endforeach
                                      
                                      
                                    </tbody></table>
                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                        {{-- <button type="button" class="btn  btn-success center-block">Show All Schedule</button> --}}
                </div>
                <div class="overlay" style="display:none;" id="loaderTargetHoEdit">
                        <i class="fa fa-refresh fa-spin"></i>
                      </div>                
            </div><!-- /.box -->
        </div>
        {{-- END Handouts --}}

        @push('scripts')
        <script>
                $(document).ready(function() {
                    $(this).on('click','.target', function(){

                        if($(this).height() >40){
                            $(this).animate({height: 40}, 300 );
                            $(this).css("background-color", "#eee");

                            $(this).next().children("i.fa-angle-down").removeClass('fa-angle-down').addClass('fa-angle-up');

                        }else{
                        $(this).animate({height: $(this).get(0).scrollHeight}, 300 );
                        $(this).css("background-color", "#eee");

                        $(this).next().children("i.fa-angle-up").removeClass('fa-angle-up').addClass('fa-angle-down');
                        }
                        
                       
                        
                    });
                    
                    // $("#hideshow").click(function () {
                    //     $("#myForm").toggle(350);
                    // });
                    $(document).on("click", "#hideshow", function(){
                        $("#myForm").toggle(350);
                    });
                    $(document).on("click", "#hideshowTest", function(){
                        $("#testUpload").toggle(350);
                    });
                    $(document).on("click", "#hideshowAS", function(){
                        $("#asUpload").toggle(350);
                    });
                    $(document).on("click", "#hideshowHO", function(){
                        $("#hoUpload").toggle(350);
                    });

                    $("#datepicker").datepicker({
                        setDate: new Date(),
                        autoclose: true,
                        dateFormat: "yy-mm-dd",
                             onSelect: function (dateText) {
                                $(this).change();
                                
                            }
                    })
                    .change(function() {
                        // console.log(dateText);
                        // var targeturl = this.value.replace(/\//g,'-');
                        var output = this.value.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$1-$2");
                        var subject = "{{$subject}}";
                        var grade = "{{$grade}}";
                        var section = "{{$section}}";
                        // var targeturl = output.replace(/\//g,'-');
                        
                        console.log(this.value);
                        console.log(output);
                    //  window.location.href = "/overview/" + output;
                   
                    $.ajax({
                        url: "/ajaxt/"+subject+"/"+grade+"-"+section+"/"+output,
                        type: 'get',
                        
                        success: function(result){
                        $("#div1").hide().html(result).fadeIn(700);
                        $("#hwLoader").hide();
                        $("#hwShowAll").removeClass('hidden');
                        }
                    });

                        $("#hwShowAll").click( function(){
                            $(this).addClass("hidden");
                            $("#hwLoader").fadeIn(500);
                            });

                    
                    });

@foreach($homeworks as $homework)
$(document).on("click", "#editShow{{$homework->id}}", function(){
    //   $("#HW{{$homework->id}}").toggle(350);
    if ($('#HW{{$homework->id}}').css('display') == 'none'){
        /* currently it's not been toggled, or it's been toggled to the 'off' state,
           so now toggle to the 'on' state: */
           $('#HW{{$homework->id}}').show(350);
           // and do something...
    }
    else if ($('#HW{{$homework->id}}').css('display') != 'none'){
        /* currently it has been toggled, and toggled to the 'on' state,
           so now turn off: */
           $('#HW{{$homework->id}}').hide(350);
           // and do, or undo, something...
    }
});

    $("#editCancel{{$homework->id}}").click(function () {
      $("#HW{{$homework->id}}").toggle(350);
      $("#editShow{{$homework->id}}").text('edit');
    });
      $('#datepicker{{$homework->id}}').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
      });
@endforeach

@foreach($tests as $test)
$(document).on("click", "#editTestShow{{$test->id}}", function(){
      $("#TEST{{$test->id}}").toggle(350);
      console.log('test was clicked');
    
});

    $("#editTestCancel{{$test->id}}").click(function () {
      $("#TEST{{$test->id}}").toggle(350);
      
    });
      $('#datepicker{{$test->id}}').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
      });
@endforeach

@foreach($activitysheets as $activitysheet)
$(document).on("click", "#editAsShow{{$activitysheet->id}}", function(){
      $("#AS{{$activitysheet->id}}").toggle(350);
      console.log('activitysheet was clicked');
    
});

    $("#editAsCancel{{$activitysheet->id}}").click(function () {
      $("#AS{{$activitysheet->id}}").toggle(350);
      
    });
      $('#datepicker{{$activitysheet->id}}').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
      });
@endforeach

@foreach($handouts as $handout)
$(document).on("click", "#editHoShow{{$handout->id}}", function(){
      $("#HO{{$handout->id}}").toggle(350);
      console.log('handout was clicked');
    
});

    $("#editHoCancel{{$handout->id}}").click(function () {
      $("#HO{{$handout->id}}").toggle(350);
      
    });
      $('#datepicker{{$handout->id}}').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
      });
@endforeach

                    $(".fa-calendar").click(function(){ $("#datepicker").datepicker("show"); }); 
       
        $('#test2').datepicker({
            setDate: new Date(),
        autoclose: true,
        format: 'yyyy-mm-dd'
      });
                    
                    var page = 1;
                    // LOAD MORE Hw
                    $(document).on('click','#hwLoader', function () {
                        var subject = "{{$subject}}";
                        var grade = "{{$grade}}";
                        var section = "{{$section}}";
                         page = page+1;
                        var totals = {{$totals}};
                        console.log(page + " "+totals);
                        console.log($('#div1 ul li').length)
                        console.log($('#hw').children().length+'lenth of children');
                    $.ajax({
                        url: "/ajaxt/"+subject+"/"+grade+"-"+section+"/"+totals+"/"+page,
                        type: 'get',
                        
                        success: function(result){
                        // $("#div1 ul").append(result).hide().fadeIn(700);
                        $("#div1 #hw").append(result).hide().fadeIn(700);;
                        // $("#div1 ul").append(result);
                        // $("#hwLoader").hide();
                        // $("#hwShowAll").removeClass('hidden');
                        // page +=1 ;
                        // console.log(page + " "+totals);
                        console.log($("#div1 ul").length+' how many ul' )
                        }
                    });
                         function check(){
                        if($('#hw').children().length >= totals){
                            $('#hwLoader').attr('disabled', 'true').text('all results listed');
                            // console.log($('#hw').children().length+'lenth of children');
                            // alert($('#hw li').length +" || "+totals )
                        }
                        };//delay a bit so the check will count loaded from ajax
                         setTimeout(check, 700);

                    });


                    // $(document).on('click','body',function(){
                    //     console.log($("#div1 ul").length)
                    // });

                });// document.ready end
                @foreach($homeworks as $homework)
$('form[name="editAssignment{{$homework->id}}"]').submit(function (event) {
console.log('edit form has been submitted ');
event.preventDefault();

$("#loaderTarget").fadeIn(100);
// var nocache = new Date().getTime();  
for (instance in CKEDITOR.instances) {
CKEDITOR.instances[instance].updateElement();
}
var $form = $(this);
var serializedData = $('form[name="editAssignment{{$homework->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxAssignment/e/{{$homework->id}}",
serializedData,
function (data) {

  setTimeout(function () {
    $("#loaderTarget").fadeOut(100);
    $("#HWcontent{{$homework->id}}").html(data);
  }, 500);
//   $( "#HWcontent{{$homework->id}}" ).html( data );$("#loaderTarget").fadeOut(100);
  $("#HW{{$homework->id}}").toggle(350, function () {
    if ($("#editShow{{$homework->id}}").text() == 'edit') {
      $("#editShow{{$homework->id}}").text('cancel');
    } else {
      $("#editShow{{$homework->id}}").text('edit');
    }
  });

  setTimeout(function () {
    $('#HW{{$homework->id}} [class=target]').animate({
      height: $(this).get(0).scrollHeight
    }, 300);
    $('#HW{{$homework->id}} [class=target]').css("background-color", "#eee");
    // console.log($(this).prev().get(0).scrollHeight);
    // console.log($(this).prev().height());
    $('#HW{{$homework->id}} [class=target]').next().children("i.fa-angle-up").removeClass('fa-angle-up').addClass('fa-angle-down');
  }, 2000);


}
);


});


$(document).on("click", "#delHW{{$homework->id}}", function (event) {
event.preventDefault();
// console.log('it has been clicked');

$("#loaderTarget").fadeIn(100);

var $form = $(this);
var serializedData = $('form[name="delAssignment{{$homework->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxAssignment/x/{{$homework->id}}",
serializedData,
function (data) {

setTimeout(function () {
$("#loaderTarget").fadeOut(100);
$("#HWcontent{{$homework->id}}").html(data);
}, 500);
// $( "#HWcontent{{$homework->id}}" ).html( data );$("#loaderTarget").fadeOut(100);


setTimeout(function () {
$('#HW{{$homework->id}} [class=target]').animate({
height: $(this).get(0).scrollHeight
}, 300);
$('#HW{{$homework->id}} [class=target]').css("background-color", "#eee");
// console.log($(this).prev().get(0).scrollHeight);
// console.log($(this).prev().height());
$('#HW{{$homework->id}} [class=target]').next().children("i.fa-angle-up").removeClass('fa-angle-up').addClass('fa-angle-down');
}, 2000);
});
});

@endforeach

@foreach($tests as $test)
$('form[name="editTest{{$test->id}}"]').submit(function (event) {
console.log('edit form has been submitted ');
event.preventDefault();
$("#loaderTargetTest").fadeIn(100);
// var nocache = new Date().getTime();  
for (instance in CKEDITOR.instances) {
CKEDITOR.instances[instance].updateElement();
}
var $form = $(this);
var serializedData = $('form[name="editTest{{$test->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxTest/e/{{$test->id}}",
serializedData,
function (data) {

  setTimeout(function () {
    $("#loaderTargetTest").fadeOut(100);
    $("#TESTcontent{{$test->id}}").html(data);
    $('#editTestSuccess').insertBefore("#TESTcontent{{$test->id}}").fadeIn(350);
    $("#TEST{{$test->id}}").toggle(350);
  }, 500);


}
);



});


$(document).on("click", "#delTEST{{$test->id}}", function (event) {
event.preventDefault();
// console.log('it has been clicked');

$("#loaderTargetTest").fadeIn(100);

var $form = $(this);
var serializedData = $('form[name="delTest{{$test->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxTest/x/{{$test->id}}",
serializedData,
function (data) {

setTimeout(function () {
$("#loaderTargetTest").fadeOut(100);
$("#TESTcontent{{$test->id}}").html(data);

}, 500);

});
});

@endforeach


@foreach($activitysheets as $activitysheet)
$('form[name="editAs{{$activitysheet->id}}"]').submit(function (event) {
console.log('edit form has been submitted ');
event.preventDefault();
$("#loaderTargetAsEdit").fadeIn(100);
// var nocache = new Date().getTime();  
for (instance in CKEDITOR.instances) {
CKEDITOR.instances[instance].updateElement();
}
var $form = $(this);
var serializedData = $('form[name="editAs{{$activitysheet->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxAS/e/{{$activitysheet->id}}",
serializedData,
function (data) {

  setTimeout(function () {
    $("#loaderTargetAsEdit").fadeOut(100);
    $("#AScontent{{$activitysheet->id}}").html(data);
    $('#editAsSuccess').insertBefore("#AScontent{{$activitysheet->id}}").fadeIn(350);
    $("#AS{{$activitysheet->id}}").toggle(350);
  }, 500);

}
);



});


$(document).on("click", "#delAs{{$activitysheet->id}}", function (event) {
event.preventDefault();
// console.log('it has been clicked');

$("#loaderTargetAsEdit").fadeIn(100);

var $form = $(this);
var serializedData = $('form[name="delAs{{$activitysheet->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxAS/x/{{$activitysheet->id}}",
serializedData,
function (data) {

setTimeout(function () {
$("#loaderTargetAsEdit").fadeOut(100);
$("#AScontent{{$activitysheet->id}}").html(data);

}, 500);

});
});

@endforeach

@foreach($handouts as $handout)
$('form[name="editHo{{$handout->id}}"]').submit(function (event) {
console.log('edit form has been submitted ');
event.preventDefault();
$("#loaderTargetHoEdit").fadeIn(100);
// var nocache = new Date().getTime();  
for (instance in CKEDITOR.instances) {
CKEDITOR.instances[instance].updateElement();
}
var $form = $(this);
var serializedData = $('form[name="editHo{{$handout->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxHO/e/{{$handout->id}}",
serializedData,
function (data) {

  setTimeout(function () {
    $("#loaderTargetHoEdit").fadeOut(100);
    $("#HOcontent{{$handout->id}}").html(data);
    $('#editHoSuccess').insertBefore("#HOcontent{{$handout->id}}").fadeIn(350);
    $("#HO{{$handout->id}}").toggle(350);
  }, 500);

}
);



});


$(document).on("click", "#delHo{{$handout->id}}", function (event) {
event.preventDefault();
// console.log('it has been clicked');

$("#loaderTargetHoEdit").fadeIn(100);

var $form = $(this);
var serializedData = $('form[name="delHo{{$handout->id}}"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxHO/x/{{$handout->id}}",
serializedData,
function (data) {

setTimeout(function () {
$("#loaderTargetHoEdit").fadeOut(100);
$("#HOcontent{{$handout->id}}").html(data);

}, 500);

});
});

@endforeach

                      $('.open-datetimepicker').click(function(event){
                        event.preventDefault();
                            $('#datepicker').click();
                      });

                </script>
        @endpush

@push('scripts')
<script>
        $(function () {
          // Replace the <textarea id="editor1"> with a CKEditor
          // instance, using default configuration.
          CKEDITOR.replace('editor1', {
          toolbar: [
          // { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
          { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
          // { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
          { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
          // '/',
          { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
          { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
          { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
          { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
          '/',
          { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
          { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
          // { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
          { name: 'others', items: [ '-' ] },
          // { name: 'about', items: [ 'About' ] }
      ]
      });
      
          @foreach($homeworks as $homework )
          CKEDITOR.replace('editorHW{{$homework->id}}', {
          toolbar: [
          // { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
          { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
          // { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
          { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
          // '/',
          { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
          { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
          { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
          { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
          '/',
          { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
          { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
          // { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
          { name: 'others', items: [ '-' ] },
          // { name: 'about', items: [ 'About' ] }
      ]
      });
          @endforeach
          //bootstrap WYSIHTML5 - text editor
          // $('.textarea').wysihtml5()
        })
      </script>
@endpush
</div>

@endsection