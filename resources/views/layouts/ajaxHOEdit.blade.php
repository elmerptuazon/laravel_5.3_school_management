
  
    {{-- <script>
      setTimeout(
      $('#editTestSuccess').insertBefore("#TESTcontent{{$test->id}}").fadeIn(350);
      ,1500)
    </script> --}}

          
        <td>{{date("M d, Y", strtotime($handout->date))}}</td>
                                <td><a href="{{asset('view/as/'.$handout->id)}}">{{ $handout->title}}</a>  : {{$handout->grade}} - {{ucwords($handout->section)}}</td>
                                <td>{{$handout->period}}</td>
                                <td><div class="pull-right">
                                    <button type="button" id="editHoShow{{$handout->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                                    {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> data-href="/delete.php?id=54" --}}
                                    
                                    <button type="button" id="delHoShow{{$handout->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-ho-delete{{$handout->id}}"><i class="fa  fa-trash"></i></button>
                                  </div> 
                                </td>  

                                   
