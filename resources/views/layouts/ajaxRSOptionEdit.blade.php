{{$option->total}} -
<span id="targetChoice">{{$option->choice}}</span>
<a  class="label bg-green" id="editRsOption">edit</a>
<a class="label bg-red" id="delRsOption" data-toggle="modal" data-target="#confirm-delete{{$option->oid}}">del</a>
<div class="col-md-12" style="margin-bottom:10px;display:none;" id="editOptionForm">
  <form method="post" action="/ajaxRSOptions/e/{{$option->oid}}" enctype="multipart/form-data" name="editOption">
    {!! csrf_field() !!}
    <input type="hidden" name="oid" value="{{$option->oid}}">
    <div class="col-md-10">
      <input type="text" class="form-control " style="margin:0px;padding:0px;" name="option" value="{{$option->choice}}">
    </div>
    <button type="button" class="btn btn-primary col-md-2" id="save">save</button>
  </form>
</div>
