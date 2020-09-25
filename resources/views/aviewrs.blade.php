@extends('admin_template')

@section('content')

<div class="row">
        {{-- <pre> {{ print_r($replyslips) }} </pre>
        <pre> {{ print_r($studentuser) }} </pre>
        <pre> {{ print_r($scope) }} </pre>
        <pre> {{ print_r(session('currentIdent')) }} </pre> --}}
        @if(Auth::user()->type == 't')
        <div class="col-md-12">
          @else
        <div class="col-md-8">
          @endif
                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title">Permission Form</h3>
                            <div class="box-tools pull-right">            
                              <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                              <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                            </div>
                          </div><!--boxheader-->
                  
                          <div class="box-body">
                            <!--circle-->
                            <div class="row">
                                @foreach($rsoptions as $option)        
                         

                                <div class="col-xs-6 col-md-6 col-lg-4 text-center" >
                                                <div style="display:inline;">
                                                  @if($replyslip->total != 0)
                                                  <input type="text" class="knob" value="{{round($option->total/$replyslip->total * 100)}}" data-width="120" data-height="120" data-fgcolor="{{$option->color}}" >
                                                  @endif
                                                </div>
                                        <div class="center-block"style="font-size: smaller; position: relative; text-align: center;  margin-bottom: 25px; width: 200px;height: 30px;overflow: hidden;
                                        text-overflow: ellipsis;white-space: nowrap;">
                                                @if($replyslip->total != 0)
                                                <strong>{{$option->total}} / {{$replyslip->total}} </strong> 
                                                @endif

                                                {{$option->choice}}
                                        </div>
                                                
                                  </div><!-- ./col -->
                                @endforeach
                              {{-- <!--knob2-->   
                              <div class="col-xs-6 col-md-6  text-center" style="position: relative; right: 45px;">
                                  <div style="display:inline;">
                                    <canvas width="250" height="250" style="width: 90px; height: 90px;"></canvas>
                                    <input type="text" class="knob" value="5" data-width="120" data-height="120" data-fgcolor="#f56954" style="width: 49px; height: 30px; 
                                      position: absolute; vertical-align: middle; margin-top: 30px; margin-left: -69px; border: 0px; background: none; font-style: normal;
                                      font-variant: normal; font-weight: bold; font-stretch: normal; font-size: 18px; line-height: normal; font-family: Arial; 
                                      text-align: center; color: rgb(0, 192, 239); padding: 0px; -webkit-appearance: none;">
                                  </div>
                                  <div style="font-size: smaller; position: relative; text-align: center; left: 45px; margin-bottom: 25px;"><strong>3/30</strong> Will not go</div>
                                </div><!-- ./col -->
                  
                              <!--knob3-->   
                              <div class="col-xs-6 col-md-6  text-center" style="position: relative; right: 45px;">
                                  <div style="display:inline;">
                                    <canvas width="250" height="250" style="width: 90px; height: 90px;"></canvas>
                                    <input type="text" class="knob" value="45" data-width="120" data-height="120" data-fgcolor="orange" style="width: 49px; height: 30px; 
                                      position: absolute; vertical-align: middle; margin-top: 30px; margin-left: -69px; border: 0px; background: none; font-style: normal;
                                      font-variant: normal; font-weight: bold; font-stretch: normal; font-size: 18px; line-height: normal; font-family: Arial; 
                                      text-align: center; color: rgb(0, 192, 239); padding: 0px; -webkit-appearance: none;">
                                  </div>
                                  <div style="font-size: smaller; position: relative; text-align: center; left: 45px; margin-bottom: 25px;"><strong>12/30</strong> Undecided</div>
                                </div><!-- ./col -->  --}}

                                <!--knob total-->   
                                
                              <div class="col-xs-6 col-md-6 col-lg-4 text-center" >
                                        <div style="display:inline;">
                                        @if($replyslip->total != 0)
                                          <input type="text" class="knob" value="{{round($replyslip->totalunans/$replyslip->total * 100)}}" data-width="120" data-height="120" data-fgcolor="#d3d3d3" >
                                          @endif
                                        </div>
                                        <div style="font-size: smaller; position: relative; text-align: center; margin-bottom: 25px;"><strong>{{$replyslip->totalunans}} / {{$replyslip->total}}</strong> Pending</div>
                                      </div><!-- ./col --> 
                               
                              </div><!--./row-->
                              
                              <!--./circle--> 
                              <object data="{{asset ("/uploads/pdf/".$replyslip->filename) }}" type="application/pdf" width="100%" height="600px">
                                alt : <a href="{{asset ("/view/rs/".$replyslip->id) }}">{{$replyslip->title}}</a>
                                  </object>
                          </div><!--./box-body-->
                  
                          <div class="box-footer">
                            
                          </div><!--./boxfooter-->
                        </div><!--./box-->
                      </div><!--./col-->


                      @if(Auth::user()->type == 'a')
                      <div class="col-md-4">
                              @push('scripts')
                                <!--Reply Option code-->
                                  <script>
                                      $(document).ready(function(){
                                          // var i=1;
                                          $('.add_field').on('click', function(){
                                              var htmldata='<li style="margin-bottom: 10px;" class="input-group"><input type="text" name="choices[]" class="form-control" required><div class="input-group-btn"><button  class="btn btn-danger remove_field" type="button" ><i class="fa fa-times"></i></button></div></li>';
                                              // i++;
                                              $('.ap').append(htmldata);	
                                          });
                                          $('.wrapper2 ').on('click','.remove_field',function(){
                                              $(this).parents('li').remove();	
                                              // i--;
                                          });
                                      });
                                  </script>
                                  @endpush
                          
                                  <div class="box box-primary">
                                      <div class="box-header with-border">
                                          <h3 class="box-title">
                                              Replyslip Choices
                                          </h3>
                                          <div class="box-tools pull-right">            
                                              <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                              <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                                          </div>
                                      </div><!--boxheader-->
                                
                                      <div class="box-body">
                                                <h3>total replyslips issued: {{$replyslip->total}}</h3>
                                        <ul class="list-unstyled">
                                                @foreach($rsoptions as $opt)
                                        <li style="margin:20px;" class="display:block;">{{$opt->total}} - <span id="targetChoice">{{$opt->choice}}</span> 
                                                <a  class="label bg-green" id="editRsOption">edit</a>
                                                <a  class="label bg-red" id="delRsOption" data-toggle="modal" data-target="#confirm-delete{{$opt->oid}}">del</a>
                                                {{-- <button type="button" id="delShow{{$opt->oid}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-delete{{$opt->oid}}"><i class="fa  fa-trash"></i></button> --}}
                                                <div class="col-md-12" style="margin-bottom:10px;display:none;" id="editOptionForm">
                                                                <form role="form" method="post"  enctype="multipart/form-data"  name="editOption" autocomplete="off">
                                                                {!! csrf_field() !!}
                                                                <input type="hidden" name="oid" value="{{$opt->oid}}">
                                                                        <div class="col-md-10">
                                                                <input type="text" class="form-control " style="margin:0px;padding:0px;" name="option" value="{{$opt->choice}}">
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary col-md-2" id="save">save</button>
                                                                </form>
                                                </div>
                {{-- START modal for delete start --}}
              <div class="modal fade" id="confirm-delete{{$opt->oid}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    Confirm Delete Homework {{$opt->oid}} - {{$opt->choice}}
                                </div>
                                <div class="modal-body">
                                    <strong>Are you sure you want to delete this Option?</strong> <p> {!! htmlspecialchars_decode(substr($opt->choice, 0, 360)) !!}...</p>
                                    <p><i class="label bg-purple " style="padding:10px;font-size:14px;"> 
                                            <strong>all answered ({{$opt->total}}) replies with this chosen option shall also be deleted</strong>.</i></p>
                                </div>
                                <div class="modal-footer">
                                    
                                    <form role="form" method="post" action="/ajaxAssignment/x/{{$opt->oid}}" enctype="multipart/form-data" name="delOption">
                                      <button type="button" class="btn btn-default" data-dismiss="modal" id="delOptCancel">Cancel</button>
                                      {!! csrf_field() !!}
                                      <input type="hidden" name="oid" value="{{$opt->oid}}" />
                                    <button type="submit" class="btn btn-danger btn-ok" id="delOpt" data-dismiss="modal">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                {{--END modal for delete  --}}
                                        </li>
                                                @endforeach
                                        </ul>
                                      </div>
                                      

                                      @push('scripts')
                                      {{-- WARNING changing order of elements in layout (li span etc) in loop will break the  jquery --}}
                                        <script>
                                                $(document).on('click','#editRsOption', function(){
                                                         tag = $(this).parents('li').html();
                                                         choice = $(this).parent().children('span').text();
                                                        //  editOptionForm = $(this).parent().children('#editOptionForm').html();
                                                         editOptionForm = $(this).parent().children('#editOptionForm').show().wrap('<p/>').parent().html();
                                                         $(this).parent().children('#editOptionForm').unwrap();

                                                        $(this).parents('li').html(editOptionForm);

                                                        
                                                        $(document).on('click','button#save', function(){
                                                                // console.log('0)' + tag);
                                                                event.preventDefault();
                                                                var eo = this;
                                                                $('#optionEditLoader').fadeIn(350);

                                                                // var $form = $(this);
                                                                var serializedData = $(eo).parents('form[name="editOption"]').serialize();
                                                                var optionId = $(eo).parent('form').children('input[name="oid"]').val();
                                                                console.log(optionId);
                                                                

                                                                $.post(
                                                                "/ajaxRSOption/e/"+optionId,
                                                                serializedData,
                                                                function (data) {

                                                                  setTimeout(function () {
                                                                    $("#optionEditLoader").fadeOut(100);
                                                                    $(eo).parents('li').html(data);
                                                                  }, 500);
                                                          
                                                                });

                                                        });
                                                        
                                                });

                                        
$( 'form' ).submit(function( event ) {
  // alert( "Handler for .submit() called." );
  // event.preventDefault();
});
                                                $(document).ready(function() {
$('input').on('keyup keypress', function(e) {
        // alert('pressed');
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
});

                                                   $(document).on("click", "#delRsOption", function (event) {
                                                        

                                                           $(document).on('click','button#delOpt', function(){
                                                                $('#optionEditLoader').fadeIn(350);
                                                        event.preventDefault();
                                                        var rdo = this;

                                                        var serializedData = $(rdo).parents('form[name="delOption"]').serialize();
                                                        var optionId = $(rdo).parent('form').children('input[name="oid"]').val();
                                                        // alert(serializedData);
                                                        $.post(
                                                                "/ajaxRSOption/x/"+optionId,
                                                                serializedData,
                                                                function (data) {

                                                                        setTimeout(function () {
                                                                                $('#optionEditLoader').fadeOut(350);
                                                                                $(rdo).parents('li').html(data);
                                                                        }, 500);
                                                                });
                                                           });
                                                        });
                                                        

                                                

                                               
                                        </script>
                                      @endpush
                                        <hr style="border: 2px solid #eee;">
                                      <form method="post" action="/optionsadd" autocomplete="off">
                                        {!! csrf_field() !!}
                                      <input type="hidden" name="rs" value="{{$replyslip->id}}">
                                      <div class="box-body">
                                          <p>Here you can Add, Delete or Change the specific replies that will be sent to the other party. </p>
                                          <button id="button2" class="add_field btn btn-sm btn-primary center-block" style="margin-bottom: 5px;" type="button">Add Another Option</button>
                                              <div class="wrapper2">    
                                                  <ul class="ap" style="list-style-type: none;">
                                                      <li style="margin-bottom: 10px;" class="input-group">
                                                        <input type="text" name="choices[]" class="form-control" required>
                                                        <div class="input-group-btn">
                                                          <button  class="btn btn-danger remove_field" type="button" ><i class="fa fa-times"></i>
                                                          </button>
                                                        </div>
                                                      </li>
                                                  </ul>        
                                              </div>
                                          
                          
                                      </div><!--./boxbody-->
                                      <div class="box-footer">
                                          {{-- <a href="reply.html"><button class="btn btn-primary btn-sm center-block">submit</button></a> --}}
                                          <button type="submit" class="btn btn-primary btn-sm center-block">submit</button>
                                      </div><!--./boxfooter-->
                                </form>

                                <div class="overlay" style="display:none;" id="optionEditLoader">
                                                <i class="fa fa-refresh fa-spin"></i>
                                </div>
                                  </div><!--./box-->
                              </div><!--./col-->
                            @endif
</div>

@push('scripts')
<!-- jQuery Knob -->
<script src="/bower_components/jquery-knob/js/jquery.knob.js"></script>
<script>
                /* jQueryKnob */
              $(function (){
                $(".knob").knob({
                  "readOnly": true,
                  'format' : function (value) {
                  return value + '%';
                  },
                });
              });
</script>
@endpush
@endsection