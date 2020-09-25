@extends('admin_template')

@section('content')

<div class="row">

      <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Primary Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
                <div class="col-md-6 col-sm-12">

                  <div class="form-group">
                    <label for="exampleInputEmail1">Student ID</label>
                    <p class="formelements">1211009</p>

                    <div class="input-group hidden">
                      <input type="text" maxlength="7" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "1211009">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Last Name</label>
                    <p class="formelements">Ledda</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "Ledda">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">First Name</label>
                    <p class="formelements">Jean-Luc</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "Jean-Luc">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Grade</label>
                    <p class="formelements">4</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "4">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Section</label>
                    <p class="formelements">J</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "J">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">School Year</label>
                    <p class="formelements">2018-2019</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "2018-2019">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Status</label>
                    <p class="formelements">Enrolled</p>

                    <div class="input-group hidden">
                      <!-- <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "2018-2019"> -->
                      <select class="form-control">
                        <option>Enrolled</option>
                        <option>Pre-Enrolled</option>
                        <option>Pending</option>
                        <option>Dropped</option>
                        
                      </select>
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  
                
                </div><!-- col 6 close-->
                <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Profile Pic</label>
                  <div class="box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="http://main.stpcentral.net/uploads/profile/profile.png" alt="User profile picture">
                  </div>
                  Update Profile Pic<input type="file" id="exampleInputFile" accept=".jpg" class="">
                </div>

                <div class="form-group">
                
                  <label for="exampleInputEmail1">Gender</label>
                  <p class="formelements">M</p>

                  <div class="input-group hidden">
                  <select class="form-control">
                        <option>M</option>
                        <option>F</option>
                        
                        </select>
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                      </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Date of Birth</label>
                    <p class="formelements">2010-09-30</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "2018-2019">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" id="enrollmentSave" class="btn btn-success" disabled>Save</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

         

      </div>

      <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Parents Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
                
                <div class="col-md-6 col-sm-12">
               <div class="form-group">
                    <label for="exampleInputEmail1">Mom Name</label>
                    <p class="formelements">Mom's Name</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Name" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Mom Profile Pic</label>
                  <div class="box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="http://main.stpcentral.net/uploads/profile/profile_f.png" alt="User profile picture">
                  </div>
                  Update Mom Profile Pic<input type="file" id="exampleInputFile" accept=".jpg" class="">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Mom Ofc Tel</label>
                    <p class="formelements">Mom Ofc Tel</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Ofc Tel" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Mom Mobile Phone</label>
                    <p class="formelements">Mom's Mobile</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Mobile" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Mom Email</label>
                    <p class="formelements">Mom's Email</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Email" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Mom Ofc Address</label>
                    <p class="formelements">Mom's Name</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Ofc Address" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                 

                </div>
                <div class="col-md-6 col-sm-12">
               <div class="form-group">
                    <label for="exampleInputEmail1">Dad Name</label>
                    <p class="formelements">Dad's Name</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Name" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Dad Profile Pic</label>
                  <div class="box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="http://main.stpcentral.net/uploads/profile/profile_m.png" alt="User profile picture">
                  </div>
                  Update Dad Profile Pic<input type="file" id="exampleInputFile" accept=".jpg" class="">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Dad Ofc Tel</label>
                    <p class="formelements">Dad Ofc Tel</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Ofc Tel" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Dad Mobile Phone</label>
                    <p class="formelements">Dad's Mobile</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Mobile" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Dad Email</label>
                    <p class="formelements">Dad's Email</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Email" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Dad Ofc Address</label>
                    <p class="formelements">Dad's Name</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Ofc Address" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                 
                </div>



              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <!-- <button type="submit" id="enrollmentSave" class="btn btn-success" disabled>Save</button> -->
              </div>
            </form>
          </div>
          <!-- /.box -->



         

      </div> <!-- col md 6 close-->

</div>
<div class="row">

      <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Contact Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
                <div class="col-md-12 col-sm-12">

                  <div class="form-group">
                    <label for="exampleInputEmail1">Mobile Number</label>
                    <p class="formelements">09XX-XXXXXXX</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mobile Number " Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Landline</label>
                    <p class="formelements">XXX-XXXX</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Home Phone" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Address</label>
                    <p class="formelements">#house Street, Subd. Brgy., City, Postal Code</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Residential Address" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Prefered Contact</label>
                    <p class="formelements">Mom</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom " Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  

                  

                  
                
                </div><!-- col 6 close-->
               

                
                
              
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <!-- <button type="submit" id="enrollmentSave" class="btn btn-success" disabled>Save</button> -->
              </div>
            </form>
          </div>
          <!-- /.box -->

         

      </div>




            <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Other Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
                <div class="col-md-6 col-sm-12">

                  
               
                  <div class="form-group">
                    <label for="exampleInputEmail1">Guardian Name</label>
                    <p class="formelements">Lola Grandma</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Guardian Name" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Guardian Relation</label>
                    <p class="formelements">Grandmother</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Guardian Relation to student" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Guardian Tel No.</label>
                    <p class="formelements">XXX-XXXX</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Guardian Tel" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  
                  </div><!-- col 6 close-->
                  <div class="col-md-6 col-sm-12">
                  

                  <div class="form-group">
                    <label for="exampleInputEmail1">Guardian Mobile</label>
                    <p class="formelements">Grandmother Mobile</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mobile" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Guardian email</label>
                    <p class="formelements">Grandmother email</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="email" Value = "">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span>
                    </div>
                  </div>
                  
                  </div><!-- col 6 close-->
               

                
                
              
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <!-- <button type="submit" id="enrollmentSave" class="btn btn-success" disabled>Save</button> -->
              </div>
            </form>
          </div>
          <!-- /.box -->

         

      </div>

</div><!--./Row-->
    @push('scripts')  
    <script>

  $(document).on("click", '.formelements',function(){
    $(this).next('.input-group').removeClass('hidden');
    $(this).addClass('hidden');
    $("#enrollmentSave").prop("disabled", false);
  });

  $(document).on("click", '.input-group-addon a',function(event){
    event.preventDefault();
    $(this).parents('.form-group').children('.input-group').addClass('hidden');
    $(this).parents('.form-group').children('.formelements').removeClass('hidden');
  });
  

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
        alert(optionId);
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