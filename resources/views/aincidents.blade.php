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

  <div class="col-md-12">
    <div class="box box-primary" style="margin-bottom: 0px;">
        <div class="box-header with-border" >
            <h3 class="box-title">Incidents</h3>
            <!-- <div class="box-tools pull-right"> 
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
            </div> -->
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
              <div class="row">
              <span class="col-xs-6 col-md-3"><button class="btn btn-block btn-primary"  id="hideshow" >Create a Incident notice</button></span>
              </div>
              <!--Form--> 
                <div class="myContainer">
                  @push('scripts')
                    <script>
                      $(document).ready(function(){
                        $("#hideshow").on('click',function(){
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
                        <h3 class="box-title">Incident Report</h3>
                      </div>
                      <form role="form" role="form" method="post" action="/aincident/post" enctype="multipart/form-data" id="incidentPost" autocomplete="off">
                        <div class="box-body">
                          <div class="form-group col-md-3">
                            @csrf
                            <label for="exampleInputSubject">Date</label>
                            <input type="text" class="form-control" id="exampleInputTitle" placeholder="YYYY-MM-DD" name="date" required>
                          </div>
                          <div class="form-group col-md-3">
                              <label for="exampleInputSubject">Student ID</label>
                              <input type="text" class="form-control" id="exampleInputTitle" placeholder="Student Id" name="sid" required>
                            </div>
                          <div class="form-group col-md-3">
                              <label for="exampleInputSubject">FirstName</label>
                              <input type="text" class="form-control" id="exampleInputTitle" placeholder="First Name" name="firstname" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputSubject">LastName</label>
                                <input type="text" class="form-control" id="exampleInputTitle" placeholder="Last Name" name="lastname" required>
                              </div>
                          <div class="form-group col-md-3">
                            <label for="exampleInputSubject">Grade/Year Level</label>
                            <select class="form-control" name="class" required>
                              <option value="" disabled selected>  choose grade & section  </option>
                                @foreach($gradesection as $gs)
                                <option value="{{$gs->grade}}-{{$gs->section}}"> {{$gs->grade}} - {{$gs->section}} </option>
                                @endforeach
                              </select>
                          </div>
                          <div class="form-group col-md-3">
                              <label for="exampleInputSubject">Severity</label>
                              <select class="form-control" name="severity" required>
                                <option value="" disabled selected>  choose Severiity  </option>
                                <option value="warning" >  Warning  </option>
                                <option value="minor" >  Minor  </option>
                                <option value="major" >  Major  </option>
                                  
                                </select>
                            </div>
                          <div class="form-group col-md-6">
                              <label for="exampleInputSubject">reported by</label>
                              <input type="text" class="form-control" id="exampleInputTitle" placeholder="Reported By" name="reported_by" required>
                          </div>
                          <div class="form-group col-md-6">
                              <label for="title">Incident</label>
                              <input type="text" class="form-control" id="exampleInputTitle" placeholder="Incident" name="title" required>
                          </div>
                          <div class="form-group col-md-6">
                              <label for="description">Incident Description</label>
                            <textarea name="description" class="form-control" rows="7"  placeholder="Enter your Incident details" required></textarea>
                          </div>
                          
                        </div>
                        <div class="box-footer">
                          <div class="col-md-12 clear-fix">
                            <button type="submit" class="btn btn-warning">Save</button>
                            <button type="button" class="btn btn-default pull-right" id="cncl">Cancel</button>
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
                    .phid:hover{
                      cursor:pointer;
                    }
                  </style>
                      <table border="0" class="table-hover col-xs-12 col-md-12" id="dummy1">
                        <tr>
                          <th style="text-align: center;">Date</th>
                          <th style="text-align: center;" class="hidden-xs">S.I.D.</th>
                          <th style="text-align: center;">Name</th>
                          <th style="text-align: center;" class="">Incident</th>
                          <th style="text-align: center;">Severity</th>
                          <th style="text-align: center;" class="hidden-xs hidden-sm">reported by</th>
                          <th style="text-align: center;"></th>
                        </tr>
                        @if(isset($incidents))
                        @foreach($incidents as $incident)
                        <tr  >
                            <td>{{$incident->date}}</td>
                            <td class="hidden-xs"><a href="/profile/student/{{$incident->sid}}">{{$incident->sid}}</a></td>
                            <td class=""><a href="/profile/student/{{$incident->sid}}">{{ ucwords($incident->firstname) }} {{ ucwords($incident->lastname)}}</a> {{$incident->grade}} {{$incident->section}}</td>
                            <td class="phid"><a>{{$incident->title}}</a></td>
                            <td>

                                <span class='fa fa-warning text-{{$incident->color}}'> {{$incident->severity}}</span>
                            </td>
                            <td class="hidden-xs hidden-sm">{{$incident->reported_by}}</td>
                            <td>
                              <a class="label bg-green" id="editIncident"><i class="fa fa-pencil"></i></a>
                              <a class="label bg-red"><i class="fa fa-trash" id="delRs" data-toggle="modal" data-target="#confirm-delete{{$incident->id}}"></i></a>
                            </td>
                           
                        </tr>

                        <tr class="hidp" style="display:none;"> <!--hidden part-->
                            <td colspan="7">
                            <div class='callout callout-{{$incident->color}}' >
                                    <h4>{{$incident->title}}</h4>
                                    <p>{{$incident->description}}
                                    </p>
                                </div> 
                            </td>
                        </tr><!--./hidden part-->

                        {{-- Start edit incident  --}}
                        <tr style="display:none"><td colspan="7">

                            <div class="box box-success" id="">
                                <style>
                                  
                                </style>              
                                <div class="box-header with-border">
                                  <h3 class="box-title">Edit Incident </h3>
                                </div>
                                <form role="form" role="form" method="post" action="/aincident/e" enctype="multipart/form-data" id="incidentPost" autocomplete="off" >
                                  <div class="box-body">
                                    <div class="form-group col-md-3">
                                      @csrf
                                      <label for="exampleInputSubject">Date</label>
                                    <input type="hidden" class="form-control" id="exampleInputTitle" placeholder="YYYY-MM-DD" name="iid" required value="{{$incident->id}}">
                                      
                                    <input type="text" class="form-control" id="exampleInputTitle" placeholder="YYYY-MM-DD" name="date" required value="{{$incident->date}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputSubject">Student ID</label>
                                        <input type="text" class="form-control" id="exampleInputTitle" placeholder="Student Id" name="sid" required value="{{$incident->sid}}">
                                      </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputSubject">FirstName</label>
                                        <input type="text" class="form-control" id="exampleInputTitle" placeholder="First Name" name="firstname" required value="{{$incident->firstname}}">
                                      </div>
                                      <div class="form-group col-md-3">
                                          <label for="exampleInputSubject">LastName</label>
                                          <input type="text" class="form-control" id="exampleInputTitle" placeholder="Last Name" name="lastname" required value="{{$incident->lastname}}">
                                        </div>
                                    <div class="form-group col-md-3">
                                      <label for="exampleInputSubject">Grade/Year Level</label>
                                      <select class="form-control" name="class" required>
                                        <option value="{{$incident->grade}}-{{$incident->section}}" selected>  {{$incident->grade}}-{{$incident->section}}  </option>
                                          @foreach($gradesection as $gs)
                                          <option value="{{$gs->grade}}-{{$gs->section}}"> {{$gs->grade}} - {{$gs->section}} </option>
                                          @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputSubject">Severity</label>
                                        <select class="form-control" name="severity" required>
                                          <option value="{{$incident->severity}}"  selected>  {{$incident->severity}} </option>
                                          <option value="warning" >  Warning  </option>
                                          <option value="minor" >  Minor  </option>
                                          <option value="major" >  Major  </option>
                                            
                                          </select>
                                      </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputSubject">reported by</label>
                                        <input type="text" class="form-control" id="exampleInputTitle" placeholder="Reported By" name="reported_by" required value="{{$incident->reported_by}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="title">Incident</label>
                                        <input type="text" class="form-control" id="exampleInputTitle" placeholder="Incident" name="title" required value="{{$incident->title}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="description">Incident Description</label>
                                      <textarea name="description" class="form-control" rows="7"  placeholder="Enter your Incident details" required >{{$incident->description}}</textarea>
                                    </div>
                                    
                                  </div>
                                  <div class="box-footer">
                                    <div class="col-md-12 clear-fix">
                                      <button type="submit" class="btn btn-warning" id="editIncidentActual">Save</button>
                                      <button type="button" class="btn btn-default pull-right" id="cancelEditIncident">Cancel</button>
                                    </div>
                                  </div>
                                </form>
                                <div class="overlay" style="display:none;" id="incidentEditLoader">
                                    <i class="fa fa-refresh fa-spin"></i>
                            </div>
                              </div>
                                  

                                                            </td>


                            </tr>
                            {{-- End edit replyslip  --}}
<tr id="rsDel{{$incident->id}}" style="height:0;margin:0;padding:0;">
    <td colspan="6" style="height:0;margin:0;padding:0;">
{{-- START modal for delete incident start --}}
<div class="modal fade" id="confirm-delete{{$incident->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        Confirm Delete Incident {{$incident->id}} - {{$incident->title}}
    </div>
    <div class="modal-body">
        <strong>Are you sure you want to delete this Incident?</strong> <p> {!! htmlspecialchars_decode(substr($incident->description, 0, 360)) !!}...</p>
        <p><i class="label bg-purple " style="padding:10px;font-size:14px;"> 
                <strong>all answered replies with this chosen option shall also be deleted</strong>.</i></p>
    </div>
    <div class="modal-footer">
        
        
        <form role="form" method="post" action="/aincident/x" enctype="multipart/form-data" id="specific{{$incident->id}}" name="delRsForm">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="delIncidentActualCancel">Cancel</button>
            {!! csrf_field() !!}
            <input type="hidden" name="iid" value="{{$incident->id}}" />

            {{-- <button type="submit" class="btn btn-danger btn-ok" id="delIncidentActual"  data-dismiss="modal">Delete</button> --}}
            <button type="submit" class="btn btn-danger btn-ok" id="delIncidentActual" >Delete</button>
        </form>
     

    </div>
</div>
</div>
</div>
{{--END modal for delete incident --}}
</td>
</tr>
                        @endforeach
                        @endif

{{-- 
                        <tr  class="phid">
                                <td>01-14-2019</td>
                                <td>3-04-167</td>
                                <td>Leon De Castro</td>
                                <td class="hidden-xs hidden-sm">Grade 2 - B</td>
                                <td>
                                    <span class="fa fa-warning text-warning"> warning</span>
                                </td>
                                <td class="hidden-xs ">Mr Alberto Estrero</td>
                               
                            </tr>

                            <tr class="hidp" style="display:none;"> <!--hidden part-->
                                <td colspan="7">
                                    <div class="callout callout-warning" >
                                        <h4>Incident Report</h4>
                                        <p>Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                            Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                            Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                            Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                            Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                        </p>
                                    </div> 
                                </td>
                            </tr><!--./hidden part-->

                            <tr  class="phid">
                                    <td>01-14-2019</td>
                                    <td>3-04-167</td>
                                    <td>Leon De Castro</td>
                                    <td class="hidden-xs hidden-sm">Grade 2 - B</td>
                                    <td>
                                        <span class="fa fa-warning text-info"> warning</span>
                                    </td>
                                    <td class="hidden-xs ">Mr Alberto Estrero</td>
                                   
                                </tr>

                        <tr class="hidp" style="display:none;"> <!--hidden part-->
                            <!-- <td ></td> -->
                            <td colspan="7">
                                <div class="callout callout-info" >
                                    <h4>Incident Report</h4>
                                    <p>Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                        Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                        Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                        Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                        Exam is nearing Please Settle the Bill Account for the child to take the exam.
                                    </p>
                                </div> 
                            </td>
                        </tr><!--./hidden part--> --}}


                    </table>
                </div>           
        </div><!-- /.box-body -->
        <div class="box-footer" style="margin-top: 0px;">                    
            <!-- <button type="button" class="btn  btn-primary" >june 9, 2009</button> -->
            <!-- <button type="submit" class="btn  btn-primary pull-right"  >Submit</button> -->
        </div><!-- /.box-footer-->
    
    </div>
  </div>
  
  
</div><!--./Row-->
    @push('scripts')  
    <script>
    $('div.taskAlert').fadeOut(3000);
  $(document).on("click", '#dummy1 .phid' ,function(){ 
    $(this).parent().next().toggle(350);
    console.log( $(this).parent().next());
  }) 
  $('#cncl').on('click',function(){
    $('#myForm').toggle(350);
  })                  


$(document).on("click", '#editIncident' ,function(){ 
         $(this).parents('tr').nextAll().eq(1).toggle(350);
       
      });
$(document).on('click','#cancelEditIncident', function(){

    $(this).parents('tr').toggle(350);
});

$(document).on("click", '#editIncidentActual' ,function(){
  // event.preventDefault(); 
         $(this).parents('.box').children('#incidentEditLoader').toggle(350);
       
      });



  $(document).on("click", "#delHW", function (event) {
event.preventDefault();
// console.log('it has been clicked');

$("#loaderTarget").fadeIn(100);

var $form = $(this);
var serializedData = $('form[name="delAssignment"]').serialize();
// console.log(serializedData);
$.post(
"/ajaxAssignment/x//",
serializedData,
function (data) {

setTimeout(function () {
$("#loaderTarget").fadeOut(100);
$("#HWcontent").html(data);
}, 500);

});
});
      </script>
      @endpush
@endsection