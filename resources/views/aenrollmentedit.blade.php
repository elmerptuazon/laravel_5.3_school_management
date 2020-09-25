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

      <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Primary Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method='post' action="{{ route('posteditstudents') }}" enctype="multipart/form-data" role="form">
              @csrf
              <div class="box-body">
                <div class="col-md-6 col-sm-12">
                <input type="hidden" id="constant_student_id" Value = "{{$senrollmentinfo->id}}">
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Student ID</label>
                    @if($senrollmentinfo->id == null || $senrollmentinfo->id == '')
                      <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->id}}</p>
                      <input type="hidden" type="number" class="hiddenDefVal" value="{{$senrollmentinfo->id}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" maxlength="7" class="form-control input-classOnClick" id="exampleInputEmail1" placeholder="Student Id" name="student_id" Value = "{{$senrollmentinfo->id}}" >
                      
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Last Name</label>
                    @if($senrollmentinfo->lastname == null || $senrollmentinfo->lastname == '')
                      <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{ ucwords($senrollmentinfo->lastname) }}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->lastname}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" id="exampleInputEmail1" placeholder="Lastname" name="student_lastname" Value = "{{$senrollmentinfo->lastname}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">First Name</label>
                    @if($senrollmentinfo->firstname == null || $senrollmentinfo->firstname == '')
                      <p style="color:red;"  style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{ ucwords($senrollmentinfo->firstname) }}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->firstname}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" id="exampleInputEmail1" placeholder="firstname" name="student_firstname" Value = "{{$senrollmentinfo->firstname}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Grade</label>
                    @if($senrollmentinfo->grade == null || $senrollmentinfo->grade == '')
                      <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->grade}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->grade}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" id="exampleInputEmail1" placeholder="grade" name="student_grade" Value = "{{$senrollmentinfo->grade}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Section</label>
                    @if($senrollmentinfo->section == null || $senrollmentinfo->section == '')
                      <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->section}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->section}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" id="exampleInputEmail1" placeholder="Student Id" name="student_section" Value = "{{$senrollmentinfo->section}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">School Year</label>
                    <p class="formelements">2018-2019</p>

                    <div class="input-group hidden">
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" name="student_school_year" Value = "2018-2019">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Status</label>
                    <p class="formelements">Enrolled</p>

                    <div class="input-group hidden">
                      <!-- <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Student Id" Value = "2018-2019"> -->
                      <select class="form-control" name="student_status_enrolled">
                        <option value="1">Enrolled</option>
                        <option value="2">Pre-Enrolled</option>
                        <option value="3">Pending</option>
                        <option value="4">Dropped</option>
                        
                      </select>
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  
                
                </div><!-- col 6 close-->
                <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label for="exampleInputEmail1" class="" style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Profile Pic</label>
                  <div class="box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{asset('uploads/profile/'.$senrollmentinfo->profilepic.'')}}" alt="User profile picture">
                  </div>
                  Update Profile Pic<input type="file" value="{{$senrollmentinfo->profilepic}}" accept=".jpg" name="student_student_profile_pic" id="student_image" class="">
                </div>

                <div class="form-group">
                
                  <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Gender</label>
                  @if($senrollmentinfo->gender == null || $senrollmentinfo->gender == '')
                    <p style="color:red;"  class="formelements">No Info</p>
                  @else
                    <p class="formelements">{{$senrollmentinfo->gender}}</p>
                    <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->gender}}">
                  @endif
                  

                  <div class="input-group hidden">
                  <select class="form-control input-classOnClick" value="{{$senrollmentinfo->gender}}" name="student_gender">
                        <option value="m">M</option>
                        <option value="f">F</option>
                        
                        </select>
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                      </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Date of Birth</label>
                    @if($senrollmentinfo->birthdate == null || $senrollmentinfo->birthdate == '')
                    <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->birthdate}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->birthdate}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="date" class="form-control input-classOnClick" id="exampleInputEmail1" placeholder="Sbirthdate" name="student_birthdate" Value = "{{$senrollmentinfo->birthdate}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <!-- <button type="submit" id="enrollmentSave" class="btn btn-success" disabled>Save</button> -->
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
                    @if($senrollmentinfo->s_mom_id == null || $senrollmentinfo->s_mom_id == '')
                    <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_mom_id}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_mom_id}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" maxlength="8" class="form-control input-classOnClick" name="student_mid" id="exampleInputEmail1" placeholder="Mom's ID" Value = "{{$senrollmentinfo->s_mom_id}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
               <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Name</label>
                    @if($senrollmentinfo->s_momname == null || $senrollmentinfo->s_momname == '')
                    <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{ ucwords($senrollmentinfo->s_momname) }}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_momname}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" name="student_mname" id="exampleInputEmail1" placeholder="Mom's Name" Value = "{{$senrollmentinfo->s_momname}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                <div class="form-group">
                  <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Profile Pic</label>
                  <div class="box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{asset('uploads/profile/'.$senrollmentinfo->s_mom_profilepic.'')}}" alt="User profile picture">
                  </div>{{--http://main.stpcentral.net/uploads/profile/profile_f.png--}}
                  Update Mom Profile Pic<input type="file" value="{{$senrollmentinfo->s_mom_profilepic}}" accept=".jpg" name="student_mprofile_pic" id="student_mom_image" class="">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Ofc Tel</label>
                    @if($senrollmentinfo->s_momofficetel == null || $senrollmentinfo->s_momofficetel == '')
                    <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_momofficetel}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_momofficetel}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="number" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_moffice_tel" placeholder="Mom's Ofc Tel" Value = "{{$senrollmentinfo->s_momofficetel}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Mobile Phone</label>
                    @if($senrollmentinfo->s_momcellno == null || $senrollmentinfo->s_momcellno == '')
                    <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_momcellno}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_momcellno}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="number" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_mmobile_no" placeholder="Mom's Mobile" Value = "{{$senrollmentinfo->s_momcellno}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Email</label>
                    @if($senrollmentinfo->s_momemail == null || $senrollmentinfo->s_momemail == '')
                      <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_momemail}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_momemail}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_memail" placeholder="Mom's Email" Value = "{{$senrollmentinfo->s_momemail}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mom Ofc Address</label>
                    @if($senrollmentinfo->s_momofcaddress == null || $senrollmentinfo->s_momofcaddress == '')
                      <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_momofcaddress}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_momofcaddress}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_moffice_address" placeholder="Mom's Ofc Address" Value = "{{$senrollmentinfo->s_momofcaddress}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                 

                </div>
                <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad ID</label>
                    @if($senrollmentinfo->s_dad_id == null || $senrollmentinfo->s_dad_id == '')
                      <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_dad_id}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_dad_id}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" maxlength="8" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_did" placeholder="Dad's ID" Value = "{{$senrollmentinfo->s_dad_id}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
               <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Name</label>
                    @if($senrollmentinfo->s_dadname == null || $senrollmentinfo->s_dadname == '')
                      <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{ ucwords($senrollmentinfo->s_dadname) }}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_dadname}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_dname" placeholder="Dad's Name" Value = "{{$senrollmentinfo->s_dadname}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                <div class="form-group">
                  <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Profile Pic</label>
                  <div class="box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{asset('uploads/profile/'.$senrollmentinfo->s_dad_profilepic.'')}}" alt="User profile picture">
                  </div>{{--http://main.stpcentral.net/uploads/profile/profile_m.png--}}
                  Update Dad Profile Pic<input type="file" value="{{$senrollmentinfo->s_dad_profilepic or ''}}" accept=".jpg" name="student_dprofile_pic" id="student_dad_image" class="">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Ofc Tel</label>
                    @if($senrollmentinfo->s_dadofficetel == null || $senrollmentinfo->s_dadofficetel == '')
                      <p style="color:red;"  class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_dadofficetel}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_dadofficetel}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="number" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_doffice_tel" placeholder="Dad's Ofc Tel" Value = "{{$senrollmentinfo->s_dadofficetel}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Mobile Phone</label>
                    @if($senrollmentinfo->s_dadcellno == null || $senrollmentinfo->s_dadcellno == '')
                      <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_dadcellno}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_dadcellno}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="number" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_dmobile_no" placeholder="Dad's Mobile" Value = "{{$senrollmentinfo->s_dadcellno}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Email</label>
                    @if($senrollmentinfo->s_dademail == null || $senrollmentinfo->s_dademail == '')
                      <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_dademail}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_dademail}}">
                    @endif

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_demail" placeholder="Dad's Email" Value = "{{$senrollmentinfo->s_dademail}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Dad Ofc Address</label>
                    @if($senrollmentinfo->s_dadofcaddress == null || $senrollmentinfo->s_dadofcaddress == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_dadofcaddress}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_dadofcaddress}}">
                    @endif
                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_doffice_address" placeholder="Dad's Ofc Address" Value = "{{$senrollmentinfo->s_dadofcaddress}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                 
                </div>



              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <!-- <button type="submit" id="enrollmentSave" class="btn btn-success" disabled>Save</button> -->
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
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Mobile Number</label>
                    @if($senrollmentinfo->s_cellno == null || $senrollmentinfo->s_cellno == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_cellno}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_cellno}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="number" class="form-control input-classOnClick" id="exampleInputEmail1" name="student_mobile_no" placeholder="Mobile Number " Value = "{{$senrollmentinfo->s_cellno}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Landline</label>
                    @if($senrollmentinfo->s_landline == null || $senrollmentinfo->s_landline == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_landline}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_landline}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="number" class="form-control input-classOnClick" name="student_landline" id="exampleInputEmail1" placeholder="Home Phone" Value = "{{$senrollmentinfo->s_landline}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Address</label>
                    @if($senrollmentinfo->s_address == null || $senrollmentinfo->s_address == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_address}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_address}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" name="student_address" id="exampleInputEmail1" placeholder="Residential Address" Value = "{{$senrollmentinfo->s_address}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Prefered Contact</label>
                    @if($senrollmentinfo->s_prefcontact == null || $senrollmentinfo->s_prefcontact == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_prefcontact}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_prefcontact}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" name="student_preferred_contact" id="exampleInputEmail1" placeholder="Mom " Value = "{{$senrollmentinfo->s_prefcontact}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  

                  

                  
                
                </div><!-- col 6 close-->
               

                
                
              
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <!-- <button type="submit" id="enrollmentSave" class="btn btn-success" disabled>Save</button> -->
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
                    @if($senrollmentinfo->s_guardianname == null || $senrollmentinfo->s_guardianname == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_guardianname}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_guardianname}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" name="student_gname" id="exampleInputEmail1" placeholder="Guardian Name" Value = "{{$senrollmentinfo->s_guardianname}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Guardian Relation</label>
                    @if($senrollmentinfo->s_guardianrelation == null || $senrollmentinfo->s_guardianrelation == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_guardianrelation}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_guardianrelation}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" name="student_grelation" id="exampleInputEmail1" placeholder="Guardian Relation to student" Value = "{{$senrollmentinfo->s_guardianrelation}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Guardian Tel No.</label>
                    @if($senrollmentinfo->s_guardiantel == null || $senrollmentinfo->s_guardiantel == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_guardiantel}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_guardiantel}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="number" class="form-control input-classOnClick" name="student_gtel_no" id="exampleInputEmail1" placeholder="Guardian Tel" Value = "{{$senrollmentinfo->s_guardiantel}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  
                  </div><!-- col 6 close-->
                  <div class="col-md-6 col-sm-12">
                  

                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Guardian Mobile</label>
                    @if($senrollmentinfo->s_guardiancellno == null || $senrollmentinfo->s_guardiancellno == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_guardiancellno}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_guardiancellno}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="number" class="form-control input-classOnClick" name="student_gmobile_no" id="exampleInputEmail1" placeholder="Mobile" Value = "{{$senrollmentinfo->s_guardiancellno}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Guardian email</label>
                    @if($senrollmentinfo->s_guardianemail == null || $senrollmentinfo->s_guardianemail == '')
                    <p style="color:red;" class="formelements">No Info</p>
                    @else
                      <p class="formelements">{{$senrollmentinfo->s_guardianemail}}</p>
                      <input type="hidden" class="hiddenDefVal" value="{{$senrollmentinfo->s_guardianemail}}">
                    @endif
                    

                    <div class="input-group hidden">
                      <input type="text" class="form-control input-classOnClick" name="student_gemail" id="exampleInputEmail1" placeholder="email" Value = "{{$senrollmentinfo->s_guardianemail or ''}}">
                      <span class="input-group-addon"><a class="label bg-red"><i class="fa fa-ban"></i></a></span>
                    </div>
                  </div>
                  
                  </div><!-- col 6 close-->
               

                
                
              
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" id="enrollmentSave" class="btn btn-success" name="submit">Save</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

         

      </div>

</div><!--./Row-->
    @push('scripts')  
    <script>
          
  // $(document).on("click", '.formelements',function(){
  //   $(this).next('.input-group').removeClass('hidden');
  //   $(this).addClass('hidden');
  //   // $("#enrollmentSave").prop("disabled", false);
  // });

  // $(document).on("click", '.input-group-addon a',function(event){
  //   event.preventDefault();
  //   $(this).parents('.form-group').children('.input-group').addClass('hidden');
  //   $(this).parents('.form-group').children('.formelements').removeClass('hidden');
  // });
  

    // $(document).on("click", '#dummy1' ,function(){ 
    //     $(this).parent(). next().toggle(350);
        
    //   }) ;
    //   $(document).on("click", '#editRs' ,function(){ 
    //      $(this).parents('.phid').nextAll().eq(1).toggle(350);
       
    //   });
    //   $(document).on("click", '#cancelEditRs' ,function(){ 
    //      $(this).parents('tr').toggle(350);
        
    //   });

    //     $(document).on('click','button#editRsActual', function(){
    //     $('#rsEditLoader').fadeIn(350);
    //     event.preventDefault();
    //     var rdo = this;

    //     var serializedData = $(rdo).parents('form[name="editRsForm"]').serialize();
    //     // var optionId = $(rdo).parents('form[name="editRsForm"]').children().children('input[name="rid"]').val();
    //     var optionId = $(rdo).parents('form[name="editRsForm"]').find('input[name="rid"]').val();
    //     alert(optionId);
    //      $.post(
    //     "/ajaxRS/e/"+optionId,
    //     serializedData,
    //      function (data) {

    //        setTimeout(function () {
    //                  $('#rsEditLoader').fadeOut(350);
    //                 //  alert("Replyslip has been deleted");
    //                 //  location.reload();
    //           $(rdo).parents('tr').prevAll().eq(1).html(data);
    //           $(rdo).parents('tr').toggle(350);
    //            }, 500);
    //        });
        
    //     });

   
    //    //delete replyslip ajax                                                 
    //    $(document).on('click','button#delRsActual', function(){
    //         $('#rsEditLoader').fadeIn(350);
    //     var rdo = this;

    //     var serializedData = $(rdo).parent('form[name="delRsForm"]').serialize();
    //     var optionId = $(rdo).parent().children('input[name="rid"]').val();
   
    //      $.post(
    //     "/ajaxRS/x/"+optionId,
    //     serializedData,
    //      function (data) {

    //        setTimeout(function () {
    //                  $('#rsEditLoader').fadeOut(350);
    //                  alert("Replyslip has been deleted");
    //                  location.reload();
    //         //   $(rdo).parents('li').html(data);
    //            }, 500);
    //        });
    //     event.preventDefault();
    //     });
       
    $(document).ready(function(){
            var selectedDefaultValue;
            $(this).on("click", '.formelements',function(){
                $(this).siblings('.input-group').removeClass('hidden');
                $(this).addClass('hidden');
                
                var pTag = $(this).val();
                
            });
            // for cancel Button (input-group-addon)
            $(this).on("click", '.input-group-addon a',function(event){
                event.preventDefault();
                selectedDefaultValue = $(this).parents('.form-group').children('input.hiddenDefVal').val();
                
                $(this).parents('.input-group').children('.input-classOnClick').val(selectedDefaultValue);
                $(this).parents('.form-group').children('.input-group').addClass('hidden');
                $(this).parents('.form-group').children('p.formelements').removeClass('hidden');

                
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
        })


      </script>
      @endpush
@endsection