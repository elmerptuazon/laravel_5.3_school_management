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
            <form method="post" action="{{ route('primarydetails') }}" id="primarydetailsform" enctype="multipart/form-data" role="form">
            @csrf
              <div class="box-body">
                <div class="col-md-6 col-sm-12">

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Student ID</label>
                    

                    <div class="input-group ">
                      <input type="text" maxlength="7" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "" name="primarydetails_studentid">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Last Name</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Lastname" Value = "" name="primarydetails_lastname">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">First Name</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="firstname" Value = "" name="primarydetails_firstname">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> --> 
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Grade</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="grade" Value = "" name="primarydetails_grade">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Section</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" maxlength="2" placeholder="Section" Value = "" name="primarydetails_section">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">School Year</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="School Year" Value = "2019-2020" name="primarydetails_schoolyear">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Status</label>
                    

                    <div class="input-group ">
                      <!-- <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "2018-2019"> -->
                      <select class="form-control" name="primarydetails_status">
                        <option selected>Enrolled</option>
                        <option>Pre-Enrolled</option>
                        <option>Pending</option>
                        <option>Dropped</option>
                        
                      </select>
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  
                
                </div><!-- col 6 close-->
                <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label for="exampleInputEmail1" class="" style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Profile Pic</label>
                  <div class="box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="http://main.stpcentral.net/uploads/profile/profile.png" alt="User profile picture">
                  </div>
                  Update Profile Pic<input type="file" id="student_image" value="" accept=".jpg" class="" name="primarydetails_profilepic">
                </div>

                <div class="form-group">
                
                  <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Gender</label>
                  

                  <div class="input-group ">
                  <select class="form-control" name="primarydetails_gender">
                        <option>M</option>
                        <option>F</option>
                        
                        </select>
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                      </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Date of Birth(YYYY-MM-DD)</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="birthdate" Value = "" name="primarydetails_dob" required>
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <!-- <button type="submit" id="enrollmentSave" class="btn btn-success">Save</button> -->
              </div>
            
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
            
              <div class="box-body">
                
                <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom ID</label>
                   

                    <div class="input-group ">
                      <input type="text" maxlength="8" class="form-control" id="exampleInputEmail1" placeholder="Mom's ID" Value = "" name="parentsdetails_momid">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
               <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Name</label>
                   

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Name" Value = "" name="parentsdetails_momname">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  <div class="form-group">
                  <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Profile Pic</label>
                  <div class="box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="http://main.stpcentral.net/uploads/profile/profile_f.png" alt="User profile picture">
                  </div>
                  Update Mom Profile Pic<input type="file" id="student_mom_image" accept=".jpg" class="" name="parentsdetails_momprofilepic">
                </div>
                

                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Ofc Tel</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Ofc Tel" Value = "" name="parentsdetails_momofficetel">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Mobile Phone</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Mobile" Value = "" name="parentsdetails_mommobile">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Email</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Email" Value = "" name="parentsdetails_momemail">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Ofc Address</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom's Ofc Address" Value = "" name="parentsdetails_momofficeaddress">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                 

                </div>
                <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad ID</label>
                    

                    <div class="input-group ">
                      <input type="text" maxlength="8" class="form-control" id="exampleInputEmail1" placeholder="Dad's Name" Value = "" name="parentsdetails_dadid">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
               <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Name</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Name" Value = "" name="parentsdetails_dadname">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                <div class="form-group">
                  <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Profile Pic</label>
                  <div class="box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="http://main.stpcentral.net/uploads/profile/profile_m.png" alt="User profile picture">
                  </div>
                  Update Dad Profile Pic<input type="file" id="student_dad_image" accept=".jpg" class="" name="parentsdetails_dadprofilepic">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Ofc Tel</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Ofc Tel" Value = "" name="parentsdetails_dadofficetel">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Mobile Phone</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Mobile" Value = "" name="parentsdetails_dadmobile">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Email</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Email" Value = "" name="parentsdetails_dademail">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Ofc Address</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Dad's Ofc Address" Value = "" name="parentsdetails_dadofficeaddress">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                 
                </div>



              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <!-- <button type="submit" class="btn btn-success">Save</button> -->
              </div>
            
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
            
              <div class="box-body">
                <div class="col-md-12 col-sm-12">

                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Email</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Email" Value = "" name="contactdetails_email">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mobile Number</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mobile Number " Value = "" name="contactdetails_mobile">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Landline</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Home Phone" Value = "" name="contactdetails_landline">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Address</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="#house Street, Subd. Brgy., City, Postal Code" Value = "" name="contactdetails_address">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Prefered Contact</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mom " Value = "" name="contactdetails_preferredcontact">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                </div><!-- col 6 close-->
      
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <!-- <button type="submit" class="btn btn-success">Save</button> -->
              </div>
            
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
            
              <div class="box-body">
                <div class="col-md-6 col-sm-12">

                  
               
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Guardian Name</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Guardian Name" Value = "" name="otherdetails_guardianname">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Guardian Relation</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Guardian Relation to student" Value = "" name="otherdetails_guardianrelation">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Guardian Tel No.</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Guardian Tel" Value = "" name="otherdetails_guardiantel">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  
                  </div><!-- col 6 close-->
                  <div class="col-md-6 col-sm-12">
                  

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Guardian Mobile</label>
                   

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Mobile" Value = "" name="otherdetails_guardianmobile">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Guardian email</label>
                    

                    <div class="input-group ">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="email" Value = "" name="otherdetails_guardianemail">
                      <!-- <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-trash"></i></a></span> -->
                    </div>
                  </div>
                  
                  </div><!-- col 6 close-->
               

                
                
              
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" id="enrollmentSave" class="btn btn-success">Save</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

         

      </div>

</div><!--./Row-->
    @push('scripts')  
    <script>

  // $(document).on("click", '.formelements',function(){
  //   $(this).next('.input-group').removeClass('');
  //   $(this).addClass('');
  //   $("#enrollmentSave").prop("disabled", false);
  // });

  $(document).on("click", '.input-group-addon a',function(event){
    event.preventDefault();
    $(this).parents('.form-group').children('.input-group').addClass('');
    $(this).parents('.form-group').children('.formelements').removeClass('');
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

        $('#student_image').bind('change', function() {
              var fileSize = this.files[0].size/1024/1024;

              if(fileSize > 2) {
                alert('File size is too big. Please choose image 2MB lower')
                $(this).val('');
              }
            })
            $('#student_mom_image').bind('change', function() {
              var fileSize = this.files[0].size/1024/1024;

              if(fileSize > 2) {
                alert('File size is too big. Please choose image 2MB lower')
                $(this).val('');
              }
            })
            $('#student_dad_image').bind('change', function() {
              var fileSize = this.files[0].size/1024/1024;

              if(fileSize > 2) {
                alert('File size is too big. Please choose image 2MB lower')
                $(this).val('');
              }
            })

    


      </script>
      @endpush
@endsection