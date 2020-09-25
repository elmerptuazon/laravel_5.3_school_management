
        <h4 style="font-weight:bold;">{{ucwords($homework->subject)}}</h4>
            <span style="float:right; font-style:italic;">{{date("M d, Y", strtotime($homework->pubdate))}}</span>
            <span style="display:block;">{{ucwords($homework->grade)}} {{ucwords($homework->section)}}</span>

            <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Success!</h4>
                    The assignment has been edited.
                  </div>
   
        <div class="target" style="height:auto;background-color: #eee">
              {{-- {!!$homework->description!!} --}}
              {!!htmlspecialchars_decode($homework->description)!!}
              
              <br /><br />
              <div class="pull-right">
                <button type="button" id="editShow{{$homework->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> --}}
                
                <button type="button" id="delShow{{$homework->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-delete{{$homework->id}}"><i class="fa  fa-trash"></i></button>
              </div>
        </div>