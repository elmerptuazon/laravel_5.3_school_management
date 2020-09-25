@extends('admin_template')

@section('content')
<div class="row">
    <div class="col-md-12">
    <form method="post" action="{{ route('teachereditpost') }}" enctype="multipart/form-data" role="form">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Teacher Details</h3>
            </div>
            
            @csrf
                <div class="box-body">
                <div class="col-md-6 col-sm-12">

                    <div class="form-group">
                    <label  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Teacher ID</label>
                    

                        
                        @if(isset($teacherdetails->id))
                            <input type="hidden" name="teacher_id_fixed" value="{{$teacher_clicked_id}}">
                            <input type="text" maxlength="7" class="form-control" Value = "{{$teacherdetails->id}}" disabled>
                        @else
                            <input type="text" maxlength="7" class="form-control" placeholder="Student Id" Value = "" name="teacher_id" disabled>
                        @endif
                   
                    </div>
                    <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Last Name</label>
                    

                   
                        @if(isset($teacherdetails->lastname))
                            <input type="text" class="form-control" Value = "{{$teacherdetails->lastname}}" name="teacher_lastname" required>
                        @else
                            <input type="text" class="form-control" placeholder="Lastname" Value = "" name="teacher_lastname" required>
                        @endif
                    
                    </div>

                    <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">First Name</label>
                    

                   
                        @if(isset($teacherdetails->firstname))
                            <input type="text" class="form-control" Value = "{{$teacherdetails->firstname}}" name="teacher_firstname" required>
                        @else
                            <input type="text" class="form-control" placeholder="firstname" Value = "" name="teacher_firstname" required>
                        @endif
                   
                    </div>

                    <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Cellphone Number</label>
                    

                    
                        @if(isset($teacherdetails->t_cellno))
                            <input type="number" class="form-control" Value = "{{$teacherdetails->t_cellno}}" name="teacher_cellphone">
                        @else
                            <input type="number" class="form-control" placeholder="Cell No" Value = "" name="teacher_cellphone">
                        @endif
                    
                    </div>

                    <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Landline Number</label>
                    

                   
                        @if(isset($teacherdetails->t_landline))
                            <input type="number" class="form-control" Value = "{{$teacherdetails->t_landline}}" name="teacher_landline">
                        @else
                            <input type="number" class="form-control" placeholder="Landline" Value = "" name="teacher_landline">
                        @endif
                    
                    </div>

                    <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Email</label>
                    

                   
                        @if(isset($teacherdetails->t_email))
                            <input type="email" class="form-control" Value = "{{$teacherdetails->t_email}}" name="teacher_email">
                        @else
                            <input type="email" class="form-control" placeholder="Email" Value = "" name="teacher_email">
                        @endif
                    
                    </div>

                    <div class="form-group">
                    <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Address</label>
                    

                    
                        @if(isset($teacherdetails->t_email))
                            <textarea class="form-control" Value = "" name="teacher_address">{{$teacherdetails->t_address}}</textarea>
                        @else
                            <textarea class="form-control" placeholder="Address" Value = "" name="teacher_address"></textarea>
                        @endif
                    
                    </div>

                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                        <label class="" style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Profile Pic</label>
                        <div class="box-profile">
                            <img class="profile-user-img img-responsive img-circle" src="{{ asset("/uploads/profile/".$teacherdetails->profilepic) }}" alt="User profile picture">
                        </div>
                        Update Profile Pic<input id="student_image" type="file" value="{{$teacherdetails->profilepic}}" accept=".jpg, .png" name="teacher_profile_pic">
                        </div>

                        @push('scripts')
                        <script>
                            $('#student_image').bind('change', function() {
                                var fileSize = this.files[0].size/1024/1024;

                                if(fileSize > 2) {
                                    alert('File size is too big. Please choose image 2MB lower')
                                    $(this).val('');
                                }
                            })
                        </script>
                        @endpush

                        <div class="form-group">
                            <label for="exampleInputEmail1"  style="background-color:#e7e7e7; padding: 0 10px 0 10px; width:100%">Date of Birth(YYYY-MM-DD)</label>
                            
                            @if(isset($teacherdetails->birthdate))
                                <input type="text" class="form-control" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" Value = "{{$teacherdetails->birthdate}}" name="teacher_dob" required>
                            @else
                                <input type="text" class="form-control" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" placeholder="birthdate" Value = "" name="teacher_dob" required>
                            @endif
                        </div>
                        </div>
                </div>
                <div class="box-footer">
                <button style="float:right;" class="btn btn-primary" type="submit" name="submit">Save</button>
                </div>

        </div>
        </form>
    </div>
</div>
@endsection