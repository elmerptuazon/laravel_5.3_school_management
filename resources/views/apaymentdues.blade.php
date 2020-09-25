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
            <h3 class="box-title">Payment Dues</h3>
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
              <span class="col-xs-6 col-md-3"><button class="btn btn-block btn-primary"  id="hideshow" >Create a Payment Due notice</button></span>
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
                        <h3 class="box-title">Payment Dues notice</h3>
                      </div>
                      <form role="form" role="form" method="post" action="/apaymentdues/post" enctype="multipart/form-data" id="incidentPost" autocomplete="off">
                        <div class="box-body">
                          <div class="form-group col-md-3">
                            @csrf
                            <label for="exampleInputSubject">Date</label>
                            
                            <input type="text" pattern="\d{4}/\d{1,2}/\d{1,2}" class="datepicker" id="exampleInputTitle" placeholder="YYYY-MM-DD" name="date" required>
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
                              <option  disabled selected>  choose grade & section  </option>
                                @foreach($gradesection as $gs)
                                <option value="{{$gs->grade}}-{{$gs->section}}"> {{$gs->grade}} - {{$gs->section}} </option>
                                @endforeach
                              </select>
                          </div>
                          <div class="form-group col-md-3">
                              <label for="exampleInputSubject">Status</label>
                              <select class="form-control" name="status" required>
                                <option value="" disabled selected>  choose Status  </option>
                                <option value="unpaid" >  UNPAID  </option>
                                <option value="paid" >  PAID  </option>
                                <option value="reminder" >  REMINDER  </option>
                                  
                                </select>
                            </div>
                          <div class="form-group col-md-6">
                              <label for="exampleInputSubject">amount</label>
                              <input type="text" class="form-control" id="exampleInputTitle" placeholder="Amount" name="amount" required>
                          </div>
                          <div class="form-group col-md-6">
                              <label for="title">Description</label>
                              <input type="text" class="form-control" id="exampleInputTitle" placeholder="description" name="description" required>
                          </div>
                          {{-- <div class="form-group col-md-6">
                              <label for="description">Incident Description</label>
                            <textarea name="description" class="form-control" rows="7"  placeholder="Enter your Incident details" required></textarea>
                          </div> --}}
                          
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
                          <th style="text-align: center;" class="">Payment Due</th>
                          <th style="text-align: center;">Status</th>
                          <th style="text-align: center;">Amount</th>
                          
                          <th style="text-align: center;"></th>
                        </tr>

                        @foreach($paymentdues as $due)
                        <tr  >
                            <td>{{date('F M d, Y',strtotime($due->date))}}</td>
                            <td class="hidden-xs"><a href="/profile/student/{{$due->sid}}">{{$due->sid}}</a></td>
                            <td class=""><a href="/profile/student/{{$due->sid}}">{{ ucwords($due->firstname) }} {{ ucwords($due->lastname) }}</a> {{$due->grade}} {{$due->section}}</td>
                            <td class="phid"><a>{{$due->description}}</a></td>
                            <td>
                                <span class="fa fa-warning text-{{$due->color}}"> {{$due->status}}</span>
                            </td>
                            <td>
                                 {{$due->amount}}
                            </td>
                            <td>
                              <a class="label bg-green" id="editIncident"><i class="fa fa-pencil"></i></a>
                              <a class="label bg-red"><i class="fa fa-trash" id="delRs" data-toggle="modal" data-target="#confirm-delete{{$due->id}}"></i></a>
                            </td>
                           
                        </tr>

                        <tr class="hidp" style="display:none;"> <!--hidden part-->
                            <td colspan="7">
                            <div class="callout callout-{{$due->color}}" >
                                    {{-- <h4>{{$incident->title}}</h4> --}}
                                    <p>{{$due->description}}
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
                                  <h3 class="box-title">Edit Payment Dues </h3>
                                </div>
                                <form role="form" role="form" method="post" action="/apaymentdues/e" enctype="multipart/form-data" id="incidentPost" autocomplete="off" >
                                  <div class="box-body">
                                    <div class="form-group col-md-3">
                                      @csrf
                                      <label for="exampleInputSubject">Date</label>
                                    <input type="hidden" class="form-control" id="exampleInputTitle" placeholder="YYYY-MM-DD" name="pdid" required value="{{$due->id}}">
                                      
                                    <input type="text" class="form-control" id="exampleInputTitle" placeholder="YYYY-MM-DD" name="date" required value="{{$due->date}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputSubject">Student ID</label>
                                        <input type="text" class="form-control" id="exampleInputTitle" placeholder="Student Id" name="sid" required value="{{$due->sid}}">
                                      </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputSubject">FirstName</label>
                                        <input type="text" class="form-control" id="exampleInputTitle" placeholder="First Name" name="firstname" required value="{{$due->firstname}}">
                                      </div>
                                      <div class="form-group col-md-3">
                                          <label for="exampleInputSubject">LastName</label>
                                          <input type="text" class="form-control" id="exampleInputTitle" placeholder="Last Name" name="lastname" required value="{{$due->lastname}}">
                                        </div>
                                    <div class="form-group col-md-3">
                                      <label for="exampleInputSubject">Grade/Year Level</label>
                                      <select class="form-control" name="class" required>
                                        <option value="{{$due->grade}}-{{$due->section}}" selected>  {{$due->grade}}-{{$due->section}}  </option>
                                          @foreach($gradesection as $gs)
                                          <option value="{{$gs->grade}}-{{$gs->section}}"> {{$gs->grade}} - {{$gs->section}} </option>
                                          @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputSubject">Status</label>
                                        <select class="form-control" name="status" required>
                                          <option value="{{$due->status}}"  selected>  {{$due->status}} </option>
                                          <option value="paid" >  Paid  </option>
                                          <option value="unpaid" >  Unpaid  </option>
                                          <option value="reminder" >  Reminder  </option>
                                          
                                            
                                          </select>
                                      </div>
                                      <div class="form-group col-md-6">
                                          <label for="description">Amount</label>
                                        <input type="text"name="amount" class="form-control"  placeholder="Enter your Incident details" value="{{$due->amount}}" required >
                                      </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label for="description">Description</label>
                                      <textarea name="description" class="form-control" rows="7"  placeholder="Enter your Incident details" required >{{$due->description}}</textarea>
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
<tr id="rsDel{{$due->id}}" style="height:0;margin:0;padding:0;">
    <td colspan="6" style="height:0;margin:0;padding:0;">
{{-- START modal for delete incident start --}}
<div class="modal fade" id="confirm-delete{{$due->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        Confirm Delete Payment Due {{$due->id}} - {{$due->description}}
    </div>
    <div class="modal-body">
        <strong>Are you sure you want to delete this Payment Due notification?</strong> <p> {!! htmlspecialchars_decode(substr($due->description, 0, 360)) !!}...</p>
        <p><i class="label bg-purple " style="padding:10px;font-size:14px;"> 
        <strong>amount - {{$due->amount}}</strong>.</i></p>
    </div>
    <div class="modal-footer">
        
        
        <form role="form" method="post" action="/apaymentdues/x" enctype="multipart/form-data" id="specific{{$due->id}}" name="delRsForm">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="delIncidentActualCancel">Cancel</button>
            {!! csrf_field() !!}
            <input type="hidden" name="pdid" value="{{$due->id}}" />

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