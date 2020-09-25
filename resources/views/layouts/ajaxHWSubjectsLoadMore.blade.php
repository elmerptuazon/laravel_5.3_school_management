

                @foreach($homeworks as $homework)
                <li style="border-bottom: 1px solid #ccc;">
                <h4 style="font-weight:bold;">{{UCWORDS($homework->subject)}}  {{$homework->grade .$homework->section}}</h4><span style="float:right; font-style:italic;">{{date("l M d, Y", strtotime($homework->pubdate))}}</span>
                <span style="display:block;">{{$homework->title}} {{UCWORDS($homework->firstname)}} {{UCWORDS($homework->lastname)}}</span>
                <div class="target">{!! htmlspecialchars_decode($homework->description)!!}
                        <br /><br />
                
                  @if(Auth::user()->type =='t')
                  <div class="pull-right">
                          <button type="button" id="editShow{{$homework->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                          {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> data-href="/delete.php?id=54" --}}
                          
                          <button type="button" id="delShow{{$homework->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-delete{{$homework->id}}"><i class="fa  fa-trash"></i></button>
                </div>
                @endif
                </div>
                            <span class="read-more pull-right-container" style="display:block; width:100%;">
                                    <i class="fa  pull-right  fa-angle-down"></i>
                                  </span>

                @if(Auth::user()->type =='t')
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
              <form role="form" method="post" action="/ajaxAssignment/e/{{$homework->id}}" enctype="multipart/form-data"  name="editAssignment{{$homework->id}}">
                  <div class="box-body">
                    {!! csrf_field() !!}
                    <div class="form-group col-md-6">
                      
                    <input type="hidden" name="hw" value="{{$homework->id}}" >
                      <label for="exampleInputSubject">Publish Date</label>
                    <input type="text" class="form-control" id="datepicker{{$homework->id}}" name = "pubdate"placeholder="Enter Title" value="{{$homework->pubdate}}">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputSubject">Subject</label>
      
                      <select class="form-control" name="class">
                      <option value="{{$homework->subject}}_{{$homework->grade}}_{{$homework->section}}"selected>{{$homework->subject}} {{$homework->grade}} - {{$homework->section}}</option>
                        @foreach($subjects as $subj)
                      <option  value="{{$subj->subj}}_{{$subj->grade}}_{{$subj->section}}">{{UCWORDS($subj->subj)}} {{UCWORDS($subj->grade)}} - {{UCWORDS($subj->section)}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-12">
                    <textarea id="editorHW{{$homework->id}}" name="description" rows="10" cols="80">{!! htmlspecialchars_decode($homework->description) !!}</textarea>
                    </div>
      
                    <div class="form-group col-md-6">
                      <button type="buttom" class="btn btn-primary" id="editAjax{{$homework->id}}">Edit this Assignment</button>
                      
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
                              
                              <form role="form" method="post" action="/ajaxAssignment/x/{{$homework->id}}" enctype="multipart/form-data"  name="delAssignment{{$homework->id}}">
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
<script>

$(document).on("click", "#editShow{{$homework->id}}", function(){
      $("#HW{{$homework->id}}").toggle(350);
      console.log("I am invoked");
    // if ($('#HW{{$homework->id}}').css('display') != 'none'){
    //     /* currently it's not been toggled, or it's been toggled to the 'off' state,
    //        so now toggle to the 'on' state: */
    //        $('#HW{{$homework->id}}').show(350);
    //        // and do something...
    // }
    // else if ($('#HW{{$homework->id}}').css('display') == 'none'){
    //     /* currently it has been toggled, and toggled to the 'on' state,
    //        so now turn off: */
    //       $('#HW{{$homework->id}}').hide(350);
    //        // and do, or undo, something...
    // }
});

    $("#editCancel{{$homework->id}}").click(function () {
      $("#HW{{$homework->id}}").toggle(350);
      $("#editShow{{$homework->id}}").text('edit');
    });
      $('#datepicker{{$homework->id}}').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
      });

        </script>
                @endif
                
                </li>
                @endforeach
                
            
