


    <td class="hidden-xs hidden-sm hidden-md one">{{$replyslip->id}}</td>
    <!-- <td><input type="button" value=" Title of Permission Form" class="btn btn-primary btn-xs btn-flat"></td> -->

  <td class="two" id="dummy1"><a>{{$replyslip->title}}</a></td>

    <td class="three">{{$replyslip->grade}}</td>
    
    <td class="five">{{$replyslip->date}} </td>
    <td class="hidden-xs ">{{$replyslip->teacher_id}} </td>
    <td><a class="label bg-green" id="editRs">Edit</a><a class="label bg-red " id="delRs" data-toggle="modal" data-target="#confirm-delete{{$replyslip->id}} ">Del</a></td>
  