@extends('admin_template')

@section('content')

<div class="row">
<div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Students List </h3>
              </div>
              <div class="box-body">
                <table class="table table-hover">
                  <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Grade and Section</th>
                    <th>Status</th>
                    <th>Option</th>
                  </tr>

@if(isset($classmates))
                  <!--hidden Form-->
                  @foreach($classmates as $classmate)
                  <tr>
                    <td>{{$classmate->id}}</td>
                    <td>{{ ucwords($classmate->firstname) }} {{ ucwords($classmate->lastname) }}</td>
                    <td>{{$classmate->grade}} - {{$classmate->section}}</td>
                    <td>Enrolled</td>
                    <td>
                    <a href="/aenrollment/e/{{$classmate->id}}" >
                      <button type="button" class="btn btn-success editButton btn-xs" id="editBtn">
                        <i class="fa  fa-pencil"> </i> 
                        <input type="hidden" name="hwId" value="{{$classmate->id}}">
                      </button>                    
                      <!-- <button type="button" id="delBtn"class="btn btn-danger btn-xs">
                        <i class="fa  fa-trash"></i>
                      </button> -->
                    </a>
                    </td>
                  </tr>
                  @endforeach
                  <!--Form End-->
@endif
                  <!--hidden Form-->
                  
                  <!--Form End-->
                </table>
              </div>
              <!-- /.box-body -->
              <div class="box-footer" >
              </div><!-- /.box-footer-->
            </div>
          </div>


</div><!--./Row-->
    @push('scripts')  
    <script>


    </script>
      @endpush
@endsection