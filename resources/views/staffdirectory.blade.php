@extends('admin_template')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Staff Directory</h3>
        <div class="box-tools pull-right">            
          <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
        </div>
      </div><!--boxheader-->
      <div class="box-body">
        <div class="col-md-12">
          <!--oneSet-->
          <!-- <div class="box box-default"> -->
            {{-- <div class="box-header with-border">
            </div><!--boxheader--> --}}
            {{-- <div class="box-body"> --}}
              <!-- <div class="row"> -->
                <div>
                  <h3> Accounting Department</h3>
                </div>
                <table class="table table-striped text-left ">
                    <tr>
                      <th class="col-md-4">Name</th>
                      <th class="col-md-4">Position</th>
                      <th class="col-md-4">Contact Number</th>
                    </tr>
                    <tr>
                      <td>Lourna Ramirez</td>
                      <td>Head Accountant</td>
                      <td>669-5589</td>
                    </tr>
                    <tr>
                        <td>Regina Rodriguez</td>
                        <td>Vice-Head Accountant</td>
                        <td>669-5588</td>
                    </tr>
                    <tr>
                        <td>Remi Santiago</td>
                        <td>Accountant</td>
                        <td>669-5588</td>
                    </tr>
                    <tr>
                        <td>Daisy Dela Cruz</td>
                        <td>Accoutant</td>
                        <td>669-5588</td>
                    </tr>
                  </table>
                
              <!-- <div> -->
                <div>
                  <h3> Faculty Department</h3>
                </div>
                <table class="table table-striped text-left ">
                    <tr>
                      <th class="col-md-4">Name</th>
                      <th class="col-md-4">Position</th>
                      <th class="col-md-4">Contact Number</th>
                    </tr>
                    <tr>
                      <td>Nina Mcintire</td>
                      <td>Faculty Head</td>
                      <td>669-5587</td>
                    </tr>
                    <tr>
                        <td>Juan De la Cruz</td>
                        <td>Vice-Head Accountant</td>
                        <td>669-5588</td>
                    </tr>
                    <tr>
                        <td>Remi Santiago</td>
                        <td>Accountant</td>
                        <td>669-5588</td>
                    </tr>
                    <tr>
                        <td>Daisy Dela Cruz</td>
                        <td>Accoutant</td>
                        <td>669-5588</td>
                    </tr>
                  </table>
                <!-- </div> -->
                
              <!-- </div> -->
              <!--./row-->
            {{-- </div><!--./box-body--> --}}
          <!-- </div> -->
          <!--./boxdefault-->
          <!--./oneSet-->
        </div><!--./col12-->
      </div><!--./box-body--> 
      <div class="box-footer">         
      </div><!--./boxfooter-->
    </div><!--./box-->
  </div><!--./col-->
</div><!--./row-->

@endsection