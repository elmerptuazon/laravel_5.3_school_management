@extends('admin_template')

@section('content')

<div class="row">
  <div class="col-md-9">
    <div class="box box-primary" >
      <div class="box-header with-border">
        
        <h3 class="box-title">Class Chat</h3>
        @if(Auth::user()->type == 't' || Auth::user()->type == 'a')
        <select id="select_grade_list" class="form-control">
          <option value="">--Select Grade--</option>
          @isset($grade_list)
            @foreach($grade_list as $grades)
              <option value="{{$grades->grade}}-{{$grades->section}}">{{$grades->grade}}-{{$grades->section}}</option>
            @endforeach
          @endisset
        </select>
        @isset($grade)
          <h3>Class Selected: {{$grade}}-{{$section}}</h3>
        @endisset
        @endif
        <div class="box-tools pull-right">            
          <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
        </div>
      </div><!--boxheader-->

      <div class="box-body">
        <div class="col-md-12">
          <!-- Conversations are loaded here -->
          <div class="direct-chat-messages" style="height:500px;">

             <!-- Message. Default to the left -->
             
              
              <div class="direct-chat-msg right">
                 
                 
              </div>
              




            </div>
            <!--/.direct-chat-messages-->
        </div><!--./col9-->
      </div><!--./box-body--> 

      <div class="box-footer">
          <form action="/api/chat/post" id="chatform" method="post" autocomplete="off">
              <div class="input-group">
                <input type="text" id="chatmsg" name="chatmsg" placeholder="Type Message ..." class="form-control">
                {{ csrf_field() }}
              <input type="hidden" id="ui" name="ui" value="{{Auth::user()->ident}}">
              <input type="hidden" id="class" name="class" value="{{$grade}}-{{$section}}">
              <input type="hidden" id="utype" name="utype" value="{{Auth::user()->type}}">
                    <span class="input-group-btn">
                      <submit id=chatsubmit  class="btn btn-success btn-flat">Send</submit>
                    </span>
              </div>
            </form>   
      </div><!--./boxfooter-->
    </div><!--./box-->
  </div><!--./col-->
</div><!--./row-->
@push('scripts')  
<script>
var lastTimeID = 0;

$(document).ready(function() {
// autoscroll down for the chat

var height = 0;
function getHeight(){

  $('.direct-chat-msg').each(function(i, value){
        height += parseInt($(this).height());

  });
  return height;
}
var heighter = getHeight();


$('#select_grade_list').on('change', function() {
    var gradeValue = $(this).val();

    window.location.href = '/chat/'+gradeValue;
});
  
  // $('.direct-chat-messages').animate({scrollTop: heighter},'slow');
  $('.direct-chat-messages').animate({scrollTop: $('.direct-chat-msg').get(0).scrollHeight},'slow');
  //prevent submit of form
  $('#chatform').submit(function(event){
          event.preventDefault();
          //add stuff here
          $('.direct-chat-messages').animate({scrollTop: heighter},'slow');
      });

  $('#chatsubmit').click( function(event) {
    event.preventDefault();
    sendChatText();
    $('#chatmsg').val("");
    getChatText();
  });

  $('#chatmsg').keypress(function(e) {
    var key = e.which;
    if (key == 13) // the enter key code
    {
      $('#chatsubmit').click();
      return false;
    }
  });

  startChat();
});

function startChat(){
  setInterval( function() { getChatText(); }, 2000);
  getChatText();

}
var lastTimeID = 0;
var grade = "{{$grade}}";
var section = "{{$section}}";
function getChatText() {
  $.ajax({
    type: "GET",
    // url: "/stpdemo/control/chat.php?lastTimeID=" + lastTimeID
    url: "/api/chat/"+grade+"-"+section+"/"+lastTimeID
  }).done( function( data )
  {
    var jsonData = data;
    var jsonLength = jsonData.chat.length;
    var html = "";
    for (var i = 0; i < jsonLength; i++) {
      var result = jsonData.chat[i];
      //html += '<div style="color:#' + result.color + '">(' + result.chattime + ') <b>' + result.usrname +'</b>: ' + result.chattext + '</div>';
      html += '<div class="direct-chat-msg ' + result.placement1 +'">';
      html += '<div class="direct-chat-info clearfix">';
      html += '<span class="direct-chat-name pull-'+result.placement2 +'">'+ result.username +'</span>';
      html += '<span class="direct-chat-timestamp pull-'+result.placement3+'">'+result.chattime+'</span>';
      html += '</div>';
      html += '<img class="direct-chat-img" src="/uploads/profile/'+result.avatar+'" alt="Message User Image">';
      html += '<div class="direct-chat-text pull-'+result.placement2 +'">'+result.message+'</div>';
      html += '</div>';
      console.log(result);
      lastTimeID = result.mid;
    }
    // console.log(lastTimeID);
    $('.direct-chat-messages').append(html);
    if (jsonLength >0){
    $('.direct-chat-messages').animate({scrollTop: $(".direct-chat-messages").prop("scrollHeight")},'slow');
    // $('.direct-chat-messages').animate({scrollTop: $('.direct-chat-msg').get(0).scrollHeight},'slow');
    }
  });
}

function sendChatText(){
  var chatInput = $('#chatmsg').val();
  var uid = $('#ui').val();
  var gs = $('#class').val();
  var utype = $('#utype').val();
  console.log(chatInput+uid+gs+utype);
  if(chatInput !== ""){
    $.ajax({
      type: "POST",
      // url: "/stpdemo/control/chat.php?chattext=" + encodeURIComponent( chatInput ),
      url: "/api/chat/post",
      data: {
        "_token": "{{ csrf_token() }}",
        "chatmsg":chatInput,
        "ui":uid,
        "utype":utype,
        'gs':gs
      
      }
    });
  }

  // $.post( "test.php", { name: "John", time: "2pm" } );
}
  </script>
@endpush
@endsection