
  
    {{-- <script>
      setTimeout(
      $('#editTestSuccess').insertBefore("#TESTcontent{{$test->id}}").fadeIn(350);
      ,1500)
    </script> --}}

          
        <td>{{date("M d, Y", strtotime($activitysheet->date))}}</td>
                                <td><a href="{{asset('view/as/'.$activitysheet->id)}}">{{ $activitysheet->title}}</a>  : {{$activitysheet->grade}} - {{ucwords($activitysheet->section)}}</td>
                                <td>{{$activitysheet->period}}</td>
                                <td><div class="pull-right">
                                    <button type="button" id="editAsShow{{$activitysheet->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                                    {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> data-href="/delete.php?id=54" --}}
                                    
                                    <button type="button" id="editAsShow{{$activitysheet->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-as-delete{{$activitysheet->id}}"><i class="fa  fa-trash"></i></button>
                                  </div> 
                                </td>  

                                   
