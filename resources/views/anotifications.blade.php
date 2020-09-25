@extends('admin_template')

@section('content')

<div class="row">


  <div class="col-md-12">
    <div class="box box-primary">


        
      <div class="box-header with-border">
        <h1 class="box-title">Notifications</h1>
        <!-- <span><button class="btn btn-block btn-sm btn-primary" id="hideshow" style="width: 10%;">Upload</button></span> -->
        <!--Form--> 

        
      @if($user['type'] == 'a')
      <div class="myContainer">
        <script>
          //loading animation//
         
        </script>
          <div class="box" style="margin-top: 10px;">
            <form role="form" enctype="multipart/form-data" name="notification" method="post" action="/notification/post" autocomplete="off">
            <div class="box-header with-border">
              <h3 class="box-title">Message</h3>
            </div>
            <div class="box-body" style="margin: 1px; padding: 5px;;">
                <div class="form-group col-md-6">
                  <!-- <label for="exampleInputSubject">Title</label> -->
                  <input type="text" class="form-control" name="title" id="exampleInputTitle" placeholder="Notification Title" style="margin-bottom: 5px;" required>
                  @csrf
                  <textarea class="form-control" name="message" rows="5" cols="88" placeholder="Notification Message" style="margin-bottom: 5px; " required></textarea>  
                  <select class="form-control" name="notification_select_sendgroup">
                    <option value="all">Send to all</option>
                    <option value="parents">Send to parents</option>
                    <option value="teachers">Send to teachers</option>
                    <option value="kinder">Send to kinder</option>
                    <option value="nursery">Send to nursery</option>
                    <option value="prep">Send to prep</option>
                    @isset($grade_section)
                    @foreach($grade_section as $grades)
                    <option value="s-{{$grades->grade}}">Grade: {{$grades->grade}}</option>
                    @endforeach
                    @endisset
                  </select>
                </div>
            </div><!--./boxbody-->
            <div class="box-footer">
                <div class="col-md-6 pull rights">
                    <button type="submit" class="sub btn btn-primary btn-sm">Submit</button>
                  </div>
            </div>
          </form>
          </div><!--./boxprimary-->
        </div><!--End of Upload Form-->  
        @endif

      </div><!--boxheader-->
      
      <style>                      
          .bell{
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid black;
            display: inline-block;
            padding: 1px;
            background-color: #00a65a;
          }
          .cont{
              text-indent: 25px;
              overflow: hidden;
              height: 40px;
              
            }
            /* for notification list style*/
            .comm{
              background-color: white;
              /* margin: 2px 0px; */
              border-bottom: 1px solid whitesmoke;
              padding: 15px;
              cursor: pointer;
            }
        </style>
      <div class="box-body">
          <div class="row" style="margin: 0px;">
            <div class="box box-solid">
              <!--start of notification-->
              <h3>Notification List</h3>
              @isset($notificationsList)
                @foreach($notificationsList as $notify)
                <div class="box-comment notifTemp">
                <div class="col-md-12 comm {{$notify->viewed == 0?'bg-gray':''}}">
                    <div class="col-md-1 bell alert-success"><i class="fa notificon {{$notify->viewed == 0? 'fa-envelope-o':'fa-envelope'}}" style="font-size: 25px;"></i></div>
                    <div class="comment-text col-md-11">
                      <span class="username notificationTitle">
                          {{UCWORDS($notify->title)}}
                        <span class="text-muted pull-right">{{date('l M d, Y h:i:s A',$notify->date)}}</span>
                      </span><!-- /.username -->
                      <form>
                      <p class="cont target">
                        {{$notify->message}}

                      </p>
                      <input type="hidden" name="nid" value="{{$notify->id}}">
                      @csrf

                    </form>
                    </div> <!-- /.comment-text --> 
                  </div> <!--./ col12-->               
                </div><!-- /.box-comment -->   
                <!--./End of a notification-->
                @endforeach
              @endisset

              
              {{-- <!--start of notification-->
              <div class="box-comment">
                  <div class="col-md-12 comm bg-gray ">
                    <div class="col-md-1 bell"><i class="fa fa-bell" style="font-size: 25px;"></i></div>
                    <div class="comment-text col-md-11" style="margin: 0px;">
                      <span class="username">
                        Fr. Romulo Diosdado
                        <span class="text-muted pull-right">8:03 PM Today</span>
                      </span><!-- /.username -->
                      <p class="cont ">Keffiyeh blog actually fashion axe vegan, irony biodiesel. Cold-pressed hoodie chillwave put a bird on it aesthetic, bitters brunch meggings vegan iPhone. 
                        Dreamcatcher vegan scenester mlkshk. Ethical master cleanse Bushwick, occupy Thundercats banjo cliche ennui farm-to-table mlkshk fanny pack gluten-free. Marfa butcher vegan quinoa, bicycle rights disrupt tofu scenester chillwave 3 wolf moon asymmetrical taxidermy pour-over. 
                        Quinoa tote bag fashion axe, Godard disrupt migas church-key tofu blog locavore. Thundercats cronut polaroid Neutra tousled, meh food truck selfies narwhal American Apparel.
                        Raw denim McSweeney's bicycle rights, iPhone trust fund quinoa Neutra VHS kale chips vegan PBR&B 
                        literally Thundercats +1. Forage tilde four dollar toast, banjo health goth paleo butcher. 
                        Four dollar toast Brooklyn pour-over American Apparel sustainable, lumbersexual listicle gluten-free health goth umami hoodie. 
                        Synth Echo Park bicycle rights DIY farm-to-table, retro kogi sriracha dreamcatcher PBR&B flannel hashtag irony Wes Anderson. 
                        Lumbersexual Williamsburg Helvetica next level. Cold-pressed slow-carb pop-up normcore Thundercats Portland, cardigan literally meditation lumbersexual crucifix. Wayfarers raw denim paleo Bushwick, keytar Helvetica scenester keffiyeh 8-bit irony mumblecore whatever viral Truffaut.</p>
                    </div> <!-- /.comment-text --> 
                  </div> <!--./ col12-->               
                </div><!-- /.box-comment -->   
                <!--./End of a notification--> --}}
              
            </div>
            <!--./boxfooter-->
          </div><!--./row-->
      </div><!--./box-body-->
     <!--loading animation-->
      <div class="overlay" style="display:none;" id="loaderTargetAsEdit">
        <i class="fa fa-refresh fa-spin"></i>
      </div>  

    </div><!--./box-->
  </div><!--./col-->
  </div><!--./col-->


  
</div><!--./Row-->
    @push('scripts')  
    <script>
  var cont_clicked = 0;
  $(document).on('click','.cont', function(){
  if($(this).height() >40){
        $(this).animate({height: 40}, 300 );

  }else{
        $(this).animate({height: $(this).get(0).scrollHeight}, 300 );

        $(this).parent().siblings(".bell").children('i').removeClass("fa-envelope").addClass("fa-envelope-o");
       
  }
  cont_clicked = 1;
  $(this).parents('.bg-gray').removeClass("bg-gray");

    var serializedData = $(this).parents('form').serialize();
    var target = this;
    
    $.post(
    "/ajaxNotify/update",
    serializedData,
    function (data) {

      // setTimeout(function () {
      //   $("#optionEditLoader").fadeOut(100);
      //   $(eo).parents('li').html(data);
      // }, 500);
        $(target).append(data);
        
    });
  // alert('notification updated as read');
});

 $(document).ready(function(){
            $(document).on('click','.sub', function(){
              console.log("one");
              $('.overlay').fadeIn(350);
                setTimeout(function(){
                  $('.overlay').fadeOut(350) 
                  console.log("dot");        
                },1000);
            });

            $(this).on("click",".comm",function(){
              var notifTitle = $(this).find('.notificationTitle').text();
              var notifMsg = $(this).find('.target').text();
              // console.log('title of selected notif : '+notifTitle + ' - '+ notifMsg);
              // $(this).children('.comm').removeClass('viewed').addClass('bg-white');
              
              $('#notificationTitle').text(notifTitle);
              $('#notificationInformation').text(notifMsg);
              if(cont_clicked == 0) {
                return
              } else if(cont_clicked == 1) {
                $(this).find('.notificon').removeClass('fa-envelope-o').addClass('fa-envelope');
                $("#modalEditTestForm").modal("show");
                cont_clicked = 0;
              }
              

            })
          });

$(document).on('click', 'button#save', function () {
  // console.log('0)' + tag);
  event.preventDefault();
  var eo = this;
  $('#optionEditLoader').fadeIn(350);

  // var $form = $(this);
  var serializedData = $(eo).parents('form[name="editOption"]').serialize();
  var optionId = $(eo).parent('form').children('input[name="oid"]').val();
  console.log(optionId);

  $.post(
    "/ajaxRSOption/e/" + optionId,
    serializedData,
    function (data) {

      setTimeout(function () {
        $("#optionEditLoader").fadeOut(100);
        $(eo).parents('li').html(data);
      }, 500);

    });

});



      </script>
      @endpush

  <form role="form" method="POST" enctype="multipart/form-data" action="http://main.stpcentral.net/api/test/e" id="testEditForm" autocomplete="off">        
    <!-- <form role="form" method="POST" enctype="multipart/form-data" action="http://localhost:8080/api/test/e" id="testEditForm" autocomplete="off">         -->
    <div class="modal fade" id="modalEditTestForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Notification</h4>
          </div>
          <div class="modal-body bodyHeight">
            <div class="row" style="margin-bottom: 5px;">
              <div class="col-xs-12">
                <!-- <label>Title</label> -->
                <h4 id="notificationTitle" style="border-bottom: 1px solid gray">Title of the Notification</h4>
              </div>
              <div class="col-xs-12">
                <!-- <label>Title</label> -->
                <p id="notificationInformation" style="text-indent: 25px;">Sample Message of the notification aldhajlhdaljdnalkjnd ojahd a asdqw  qwr q  qw r r 12r qw rqwrqwrq wrr 12r1r 1f1.</p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="closeModal" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    </form>
@endsection