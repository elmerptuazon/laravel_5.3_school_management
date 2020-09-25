@extends('admin_template')

@section('content')

<div class="row">
        <div class="col-md-12">
            <div class="box box-primary" style="margin-bottom: 0px;">
                <div class="box-header with-border" >
                    <h3 class="box-title">Reply Slip</h3>
                    <div class="box-tools pull-right"> 
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
                      {{-- <span><button class="btn  btn-default"  id="hideshow" style="width: 15%;">+ Click to Register a Reply Slip</button></span> --}}
                      <span><button class="btn  btn-default"  id="hideshow" >+ Click to Register a Reply Slip</button></span>
                      <!--Form--> 
                        <div class="myContainer">
                            @push('scripts')
                            <script>
                              $(document).ready(function(){
                                $("#hideshow").click(function(){
                                  $("#myForm").toggle(350);
                                });
                              });
                            </script>
                            @endpush
                            <div class="box box-primary" id="myForm">
                              <style>
                                #myForm{
                                  display: none;
                                  margin: 20px;
                                  width: auto;
                                }
                              </style>              
                              <div class="box-header with-border">
                                <h3 class="box-title">Reply Slip</h3>
                              </div>
                              <form role="form" method="post" enctype="multipart/form-data" action="/upload/replyslip" autocomplete="off">
                                @csrf
                                <div class="box-body">
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputSubject">Title</label>
                                    <input type="text" class="form-control" id="exampleInputTitle" name="title" placeholder="Enter Title" required>
                                    <input type="hidden" name="utype" value="rs">
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputSubject">Scope</label>
                                    <select class="form-control select2" name="scope">
                                      <option value="na" selected>All Levels</option>
                                      </select>
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputFile">Attach PDF file</label>
                                    <input type="file" id="exampleInputFile" name="thefile" accept="application/pdf" required>
                                  </div>
                                </div>
                                <div class="box-footer">
                                  <div class="form-group col-md-6 clear-fix">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div><!--End of Upload Form-->

                        <!-- <h3>Grade and Section Selected</h3> -->
                      
                        <div class="row" style="margin: 10px; padding: 10px;">
                          <style>                         
                            th, tr, td{
                              /* border: 1px solid grey; */
                              margin: 15px;
                              padding: 5px;
                              min-width: inherit;
                              height: 40px;
                              text-align: center;
                            }
                            #dummy1 :hover{
                              cursor:pointer;
                            }
                          </style>
                              <table border="0" class="table-hover col-xs-12 col-md-12" >
                                <tr>
                                  <th style="text-align: center;" class="hidden-xs hidden-sm hidden-md">Reply Slip ID</th>
                                  <th style="text-align: center;">Title</th>
                                  <th style="text-align: center;">Scope</th>
                                  
                                  <th style="text-align: center;">Date</th>
                                  <th style="text-align: center;" class="hidden-xs">Uploader</th>
                                  <th style="text-align: center;">Options</th>
                                </tr>
                                @foreach($replyslips as $replyslip)
                                <tr  class="phid">
                                  <td class="hidden-xs hidden-sm hidden-md one">{{$replyslip->id}}</td>
                                  <!-- <td><input type="button" value=" Title of Permission Form" class="btn btn-primary btn-xs btn-flat"></td> -->

                                <td class="two" id="dummy1"><a >{{$replyslip->title}}</a></td>

                                  <td class="three">{{$replyslip->grade}}</td>
                                  
                                  <td class="five">{{$replyslip->date}}</td>
                                  <td class="hidden-xs ">{{$replyslip->teacher_id}}</td>
                                  <td><a  class="label bg-green" id="editRs">Edit</a><a  class="label bg-red " id="delRs" data-toggle="modal" data-target="#confirm-delete{{$replyslip->id}}">Del</a></td>
                                </tr>

                                <tr class="hidp" style="display:none;"> <!--hidden part-->
                                  <td colspan="3">
                                  <a href="/areplyslip/input/{{$replyslip->id}}"><button class="btn btn-warning btn-sm " style="margin-right: 5px;">Input Replies</button></a>
                                    <a href="/view/rs/{{$replyslip->id}}"><button class="btn btn-success btn-sm ">Reply Slip</button></a>
                                  </td>
                                  <td colspan="3">
                                    <div class="col-md-3">
                                      <h4 style="text-align: right;">Results</h4>
                                    </div>
                                    <div class="col-md-9">
                                      <ul class="list-unstyled">
                                          @foreach($replyslip->replyoption as $option)                                    
                                        <li>
                                          <p style="text-align: left;"><strong>{{$option->replyans}}</strong> {{$option->choice}}</p>
                                        </li>
                                        @endforeach
                                        {{-- <li>
                                          <p style="text-align: left;"><strong>4</strong> option b: The number of students that have not approved by their parents.</p>
                                        </li>
                                        <li>
                                          <p style="text-align: left;"><strong>2</strong> option c: The number of students that have not decided by their parents.</p>
                                        </li> --}}
                                      </ul>
                                    </div> 
                                </tr><!--./hidden part-->
                                {{-- Start edit replyslip  --}}
                                <tr style="display:none"><td colspan="6">

                                        <div class="box box-success" id="RS{{$replyslip->id}}" >
                                                <style>
                                                  #TEST17 {
                                                    /* display: none; */
                                                    margin: 20px;
                                                    width: auto;
                                                  }
                                                </style>
                                        
                                                <div class="box-header with-border">
                                                  <h3 class="box-title">EDIT Replyslip</h3>
                                                  <!-- <button class="btn btn-box-tool pull-right" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                                            <button class="btn btn-box-tool pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
                                                </div>
                                              <form role="form" method="post" action="/ajaxRS/e/{{$replyslip->id}}" enctype="multipart/form-data" name="editRsForm" autocomplete="off">
                                                  <div class="box-body">
                                                    @csrf
                                                    <div class="form-group col-md-4">
                                                      
                                                    <input type="hidden" name="rid" value="{{$replyslip->id}}"> 
                                                      <label for="exampleInputSubject">Title</label>
                                                    <input type="text" class="form-control" id="datepicker{{$replyslip->id}}" name="title" placeholder="Enter Title" value="{{$replyslip->title}}">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                      <label for="exampleInputSubject">Scope</label>
                                        
                                                      <select class="form-control" name="scope">
                                                      <option value="{{$replyslip->grade}}" selected>{{$replyslip->grade}}</option>
                                                                      {{--<option value="gs">Grade school</option>
                                                                      <option value="hs">High School</option>--}}
                                                                      <option value="na">All Levels</option>
                                                                      </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                      
                                                            <input type="hidden" name="rid" value="{{$replyslip->id}}">
                                                              <label for="exampleInputSubject">Date</label>
                                                            
                                                              <input type="text" class="form-control" id="datepicker17" name="date" placeholder="Enter Title" value="{{$replyslip->date}}">
                                                            </div>
                                                    
                                        
                                                    <div class="form-group col-md-6">
                                                      <button type="submit" class="btn btn-primary" id="editRsActual">Save Edit</button>
                                                      
                                                    </div>
                                                    <div class="form-group col-md-6" id="chars">
                                                        <button type="button" class="btn" id="cancelEditRs">Cancel</button>
                                                    </div>
                                                  </div>
                                                  <div class="box-footer">
                                                  </div>
                                                </form>
                                              </div>
                                              

                                                                        </td>


                                        </tr>
                                        {{-- End edit replyslip  --}}
            <tr id="rsDel{{$replyslip->id}}" style="height:0;margin:0;padding:0;">
                <td colspan="6" style="height:0;margin:0;padding:0;">
{{-- START modal for delete replyslip start --}}
<div class="modal fade" id="confirm-delete{{$replyslip->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Confirm Delete ReplySlip {{$replyslip->id}} - {{$replyslip->title}}
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to delete this Option?</strong> <p> {!! htmlspecialchars_decode(substr($replyslip->title, 0, 360)) !!}...</p>
                    <p><i class="label bg-purple " style="padding:10px;font-size:14px;"> 
                            <strong>all answered replies with this chosen option shall also be deleted</strong>.</i></p>
                </div>
                <div class="modal-footer">
                    
                    
                    <form role="form" method="post" action="/ajaxRS/x/{{$replyslip->id}}" enctype="multipart/form-data" id="specific{{$replyslip->id}}" name="delRsForm">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="delRsActualCancel">Cancel</button>
                        {!! csrf_field() !!}
                        <input type="hidden" name="rid" value="{{$replyslip->id}}" />

                        <button type="submit" class="btn btn-danger btn-ok" id="delRsActual"  data-dismiss="modal">Delete</button>
                    </form>
                 

                </div>
            </div>
        </div>
    </div>
{{--END modal for delete replyslip --}}
</td>
</tr>

                                @endforeach
                               

                              </table>
                        </div>           
                </div><!-- /.box-body -->
                <div class="box-footer" style="margin-top: 0px;">                    
               
                </div><!-- /.box-footer-->
                <div class="overlay" style="display:none;" id="rsEditLoader">
                        <i class="fa fa-refresh fa-spin"></i>
                </div>
        </div>
    </div><!--./Row-->
    @push('scripts')  
    <script>
    $(document).on("click", '#dummy1' ,function(){ 
        $(this).parent(). next().toggle(350);
        
      }) ;
      $(document).on("click", '#editRs' ,function(){ 
         $(this).parents('.phid').nextAll().eq(1).toggle(350);
       
      });
      $(document).on("click", '#cancelEditRs' ,function(){ 
         $(this).parents('tr').toggle(350);
        
      });

        $(document).on('click','button#editRsActual', function(){
        $('#rsEditLoader').fadeIn(350);
        event.preventDefault();
        var rdo = this;

        var serializedData = $(rdo).parents('form[name="editRsForm"]').serialize();
        // var optionId = $(rdo).parents('form[name="editRsForm"]').children().children('input[name="rid"]').val();
        var optionId = $(rdo).parents('form[name="editRsForm"]').find('input[name="rid"]').val();
        // alert(optionId);
         $.post(
        "/ajaxRS/e/"+optionId,
        serializedData,
         function (data) {

           setTimeout(function () {
                     $('#rsEditLoader').fadeOut(350);
                    //  alert("Replyslip has been deleted");
                    //  location.reload();
              $(rdo).parents('tr').prevAll().eq(1).html(data);
              $(rdo).parents('tr').toggle(350);
               }, 500);
           });
        
        });

   
       //delete replyslip ajax                                                 
       $(document).on('click','button#delRsActual', function(){
            $('#rsEditLoader').fadeIn(350);
        var rdo = this;

        var serializedData = $(rdo).parent('form[name="delRsForm"]').serialize();
        var optionId = $(rdo).parent().children('input[name="rid"]').val();
   
         $.post(
        "/ajaxRS/x/"+optionId,
        serializedData,
         function (data) {

           setTimeout(function () {
                     $('#rsEditLoader').fadeOut(350);
                     alert("Replyslip has been deleted");
                     location.reload();
            //   $(rdo).parents('li').html(data);
               }, 500);
           });
        event.preventDefault();
        });
    


      </script>
      @endpush
@endsection