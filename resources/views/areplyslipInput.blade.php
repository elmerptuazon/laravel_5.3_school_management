@extends('admin_template')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary" style="margin-bottom: 0px;">
          <div class="box-header with-border" >
            <h3 class="box-title">Reply Slip</h3>
            <div class="box-tools pull-right">
              <label  for="date"><input type="text" id="datepicker" class="form-control" name="date" style="position:absolute; right: 70px;width: 100px; border: 0; line-height: 10px; height: 1px;padding: 5px;">
                <span class="fa fa-calendar"></span>
              </label>            
              <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <style>
            .target{
              overflow: hidden;
              height:40px;
              position: relative;
              border:0px solid #999;
              transition: .3s ease;
              cursor: pointer;                        
            }  
            .read-more{
              position: relative;
              right:0;
              bottom:0;
            }
            .target.expanded {
                height: auto;                           
            }
          </style>
          <div class="box-body">
            <div style="text-indent: 20px;">
              <h3>
              <div>{{date('F d, Y',strtotime($replyslip->date))}} - <a href="/view/rs/{{$replyslip->id}}">{{UCFIRST($replyslip->title)}}</a> </div>
                <!-- <small class="label pull-right"></small> -->
              </h3>
              <div class="col-md-8">
                <div style="color: red;" >PLEASE SELECT<span>*</span></div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Grade</label>
                    <select class="form-control select2" style="width: 100%;" name="grade" id="gradePicker">
                      <option ></option>
                      @foreach($grade_list as $grades)
                        <option value="{{$grades->grade}}">Grade {{$grades->grade}}</option>
                      @endforeach
                    </select>
                  </div>                                
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Section</label>
                    <select class="form-control select2" style="width: 100%;" name="section" id="sectionPicker">
                      <option ></option>
                      <option value='a'>Section A</option>
                    </select>
                  </div>                                
                </div>
                <div style="margin: 10px; padding: 10px;">
                <h3> Selected class : {{$grade}} - {{ucwords($section)}}</h3>
                  <style>
                    th, tr, td{                                           
                      margin: 10px;
                      padding: 5px;
                      min-width: inherit;
                      height: 40px;                                         
                    }  
                    td{
                      text-align: center;
                    }
                    .choices{
                      display: inline-block;
                    }
                  </style>
                  <form  role="form" method="post" enctype="multipart/form-data" action="/areplyslip/input/submit/{{$replyslip->id}}" autocomplete="off">
                    @csrf
                  <input type="hidden" name="grade" value="{{$grade}}">
                  <input type="hidden" name="section" value="{{$section}}">
                  <input type="hidden" name="rid" value="{{$rid}}">
                  <table class="table-hover table-bordered col-md-12" >
                    <tr>
                      <th>Student ID</th>
                      <th style="text-align: center;">Name</th>
                      <th style="text-align: center;">Choices</th>
                    </tr>
                    @if(isset($students))
                    @foreach($students as $student)
                    <tr >
                      <td>
                        <div>
                          <p style="text-align: center; margin: 5px;">{{UCWORDS($student->id)}}</p>
                        </div>
                      </td>
                      @if($student->oid != '')
                      <td class="bg-success"><a href="/profile/student/{{UCWORDS($student->id)}}">{{UCWORDS($student->firstname)}} {{UCWORDS($student->lastname)}}</a></td>
                      @else
                          <td><a href="/profile/student/{{UCWORDS($student->id)}}">{{UCWORDS($student->firstname)}} {{UCWORDS($student->lastname)}}</a></td>
                      @endif
                       <td>
                        
                          <?php $letter = 'a'; ?>
                         @foreach($rsoptions as $option)
                          <div class="input-group choices">
                            @if($student->oid == $option->oid)
                            <input style="margin-left: 5px; margin-right: 10 px;" type="radio" name="reply-{{UCWORDS($student->id)}}" value="{{$option->oid}}" checked> 
                            @else
                            <input style="margin-left: 5px; margin-right: 10 px;" type="radio" name="reply-{{UCWORDS($student->id)}}" value="{{$option->oid}}"> 
                            @endif
                  
                          <a class="label bg-{{$option->color}}" id="letterChosen">{{strtoupper($letter++)}}</a> 
                          
                          </div>
                          @endforeach
                          <div class="input-group choices">
                              <a class="label bg-yellow" id="clearChosen">clear</a> 
                            </div>
                      </td>
                    </tr>
                    @endforeach
                   
                    <tr>
                        <td colspan="3">
                          <button type="submit" class="btn  btn-primary">Save</button>
                        </td>
                      </tr>
                      @endif
                  </table>
                </form>
                </div>
              </div>
              <div class="col-md-4">
                <h3 style="color: red;">LEGEND:
                </h3>
                <ul style="list-style: none;">
                    <?php $letter = 'a'; ?>
                   @foreach($rsoptions as $option)
                <li style="margin-top: 15px;">
                <span class="label bg-{{$option->color}}" >{{strtoupper($letter++)}}</span>
                <span>{{$option->choice}}</span><a href="/view/rs/{{$rid}}" class="pull-right">Edit</a>
                </li>
                @endforeach
                
                </ul>  
              </div>
            </div>
          </div>
          <!-- /.box-body -->
                  <div class="box-footer" style="margin-top: 0px;">                    
                      
                  </div>
                  <!-- /.box-footer-->
              </div>
          </div>
  
  
</div><!--./Row-->
    @push('scripts')  
    <script>
      $(document).on('click','#letterChosen',function(){
        $(this).siblings('input').prop("checked", true);
      });
      $(document).on('click','#clearChosen',function(){
        $(this).parent().siblings().children('input[type="radio"]').prop("checked", false);
      });

      $(document).on('change','#gradePicker',function(){
        var grade = $(this).val();
        if($('#sectionPicker').val().length > 0){
          var section = $('#sectionPicker').val();
          window.location='/areplyslip/input/{{$replyslip->id}}/'+grade+'-'+section+'';
        }
      });
      $(document).on('change','#sectionPicker',function(){
        var section = $(this).val();
        if($('#gradePicker').val().length > 0){
          var grade = $('#gradePicker').val();
          window.location='/areplyslip/input/{{$replyslip->id}}/'+grade+'-'+section+'';
        }
      });
      </script>
      @endpush
@endsection