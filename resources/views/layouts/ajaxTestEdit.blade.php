
  
    {{-- <script>
      setTimeout(
      $('#editTestSuccess').insertBefore("#TESTcontent{{$test->id}}").fadeIn(350);
      ,1500)
    </script> --}}

          
        <td>{{date("M d, Y", strtotime($test->date))}}</td>
                                <td><a href="{{asset('view/test/'.$test->id)}}">{{ $test->title}}</a>  : {{$test->grade}} - {{ucwords($test->section)}}</td>
                                <td>{{$test->period}}</td>
                                <td><div class="pull-right">
                                    <button type="button" id="editTestShow{{$test->id}}"class="btn btn-success btn-sm "><i class="fa  fa-pencil"> </i> </button>
                                    {{-- <a href="#" class="btn btn-app"  id="editShow{{$homework->id}}"><i class="fa fa-edit"></i>Edit</a> data-href="/delete.php?id=54" --}}
                                    
                                    <button type="button" id="delTestShow{{$test->id}}"class="btn btn-danger btn-sm " data-toggle="modal" data-target="#confirm-test-delete{{$test->id}}"><i class="fa  fa-trash"></i></button>
                                  </div> 
                                </td>  

                                   
