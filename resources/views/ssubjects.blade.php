@extends('admin_template')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary" >
                <div class="box-header with-border">
                <h3 class="box-title">{{UCWORDS($subject)}} Assignments </h3>
                    <div class="box-tools pull-right">
                            
                            <input type="text" id="datepicker" class="form-control" name="date" style="width: 100px; border: 0; line-height: 12px; height: 1px;padding: 5px; display: inline;">
                            <span class="fa fa-calendar" style="cursor: pointer;" title="choose date"></span>
                                    {{-- <label  for="date"><input type="text" id="datepicker" class="form-control" name="date" style="position:absolute; right: 70px;width: 100px; border: 0; line-height: 10px; height: 1px;padding: 5px;">
                                       <span class="fa fa-calendar"></span>
                                    </label> --}}
                                
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>

                <style>
                        .target{
                            overflow: hidden;
                            height:40px;
                            position: relative;
                            border:0px solid #999;
                            transition: .3s ease;
                            cursor: pointer;
                        
                        }
                        
                        
                        
                        .read-more{
                            position: relative;
                            right:0;
                            bottom:0;
                        }
                        .target.expanded {
                            height: auto;
                           
                        }
                        </style>
                <div id="div1" class="box-body" >
                    {{-- @foreach($tasks as $task)
                        <h5>
                            {{ $task['name'] }}
                            <small class="label label-{{$task['color']}} pull-right">{{$task['progress']}}%</small>
                        </h5>
                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-{{$task['color']}}" style="width: {{$task['progress']}}%"></div>
                        </div>
                    @endforeach --}}
                    <ul style="list-style: none;" id="hw">
                        {{-- <li style="border-bottom: 1px solid #ccc;">
                                <h4 style="font-weight:bold;">Math</h4><span style="float:right; font-style:italic;">{{date("M d, Y")}}</span>
                                <span style="display:block;">Tchr. Beth Jimenez</span>
                                
                                <p class="target">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                                    
                                    <span class="read-more pull-right-container" style="display:block; width:100%;">
                                            <i class="fa  pull-right  fa-angle-up"></i>
                                          </span>
                                        
                        </li> --}}
                        @foreach($homeworks as $homework)
                        <li style="border-bottom: 1px solid #ccc;">
                            <h4 style="font-weight:bold;">{{UCWORDS($homework->subject)}} {{$homework->grade .$homework->section}}</h4>
                            <span style="float:right; font-style:italic;">{{date("l M d, Y", strtotime($homework->pubdate))}}</span>
                            <span style="display:block;">{{$homework->title}} {{UCWORDS($homework->firstname)}} {{UCWORDS($homework->lastname)}}</span>
                            <div class="target">{!! htmlspecialchars_decode($homework->description)!!}</div>
                            <span class="read-more pull-right-container" style="display:block; width:100%;">
                                <i class="fa  pull-right  fa-angle-up"></i>
                            </span>
                        </li>
                        @endforeach
                        
                    </ul>
                    
                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                    {{-- <form action='#'>
                        <input type='text' placeholder='New task' class='form-control input-sm' />
                    </form> style="position: absolute; right: 10px;"--}}
                    @push('scripts')
                    <script>
                            $(document).ready(function() {
                                $(document).on('click','.target',function(){

                                    if($(this).height() >40){
                                        $(this).animate({height: 40}, 300 );
                                        $(this).css("background-color", "#eee");

                                        $(this).next().children("i.fa-angle-down").removeClass('fa-angle-down').addClass('fa-angle-up');
      
                                    }
                                    else{
                                    $(this).animate({height: $(this).get(0).scrollHeight}, 300 );
                                    $(this).css("background-color", "#eee");

                                    $(this).next().children("i.fa-angle-up").removeClass('fa-angle-up').addClass('fa-angle-down');
                                    }
                                    
                                   
                                    
                                });
                
                                
                                $("#datepicker").datepicker({
                                    autoclose: true,
                                    dateFormat: "yy-mm-dd",
                                         onSelect: function (dateText) {
                                            $(this).change();
                                            
                                        }
                                })
                                .change(function() {
                                    // console.log(dateText);
                                    // var targeturl = this.value.replace(/\//g,'-');
                                    var output = this.value.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$1-$2");
                                    var subject = "{{$subject}}";
                                    // var targeturl = output.replace(/\//g,'-');
                                    
                                    console.log(this.value);
                                    console.log(output);
                                //  window.location.href = "/overview/" + output;
                               
                                $.ajax({
                                    url: "/ajax/"+subject+"/"+output,
                                    type: 'get',
                                    
                                    success: function(result){
                                    $("#div1").hide().html(result).fadeIn(700);
                                    $("#hwLoader").hide();
                                    $("#hwShowAll").removeClass('hidden');
                                    }
                                });

                                    $("#hwShowAll").click( function(){
                                        $(this).addClass("hidden");
                                        $("#hwLoader").fadeIn(500);
                                        });

                                
                                });


                                $(".fa-calendar").click(function(){ $("#datepicker").datepicker("show"); }); 
                                
                                var page = 1;
                                // LOAD MORE Hw
                                $("#hwLoader").click(function () {
                                    var subject = "{{$subject}}";
                                     page = page+1;
                                    var totals = {{$totals}};
                                    console.log(page + " "+totals);
                                $.ajax({
                                    url: "/ajax/"+subject+"/"+totals+"/"+page,
                                    type: 'get',
                                    
                                    success: function(result){
                                    // $("#div1 ul").append(result).hide().fadeIn(700);
                                    $("#div1 #hw").append(result).hide().fadeIn(700);;
                                    // $("#hwLoader").hide();
                                    // $("#hwShowAll").removeClass('hidden');
                                    // page +=1 ;
                                    // console.log(page + " "+totals);
                                    }
                                });
                                     function check(){
                                    if($('#hw').children().length >= totals){
                                        $('#hwLoader').attr('disabled', 'true').text('all results listed');
                                        // alert($('#hw li').length +" || "+totals )
                                    }
                                    };//delay a bit so the check will count loaded from ajax
                                     setTimeout(check, 700);

                                });

                            });// document.ready end

                                  $('.open-datetimepicker').click(function(event){
                                    event.preventDefault();
                                        $('#datepicker').click();
                                  });

                            </script>
                    @endpush
                <button type="button" class="btn  btn-primary center-block" id="hwLoader">load more</button>
                        <button type="button" class="btn  btn-primary center-block hidden" id="hwShowAll" onclick="location.href='/subjects/{{$subject}}';">More Homework</button>
                    {{-- <button type="button" class="btn  btn-primary pull-right"  >{{date("M d, Y")}}</button> --}}
                </div><!-- /.box-footer-->
            </div>
        </div>
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Tests</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                        <table class="table table-bordered table-striped table-hover">
                                <tbody><tr>
                                  <th style="width:100px;">Date</th>
                                  <th>Title</th>
                                  
                                  <th style="width:50px;">Period</th>
                                  <th style="width: 200px">Teacher</th>
                                </tr>
                                @foreach($tests as $test)
                                <tr>
                                  <td>{{date("M d, Y", strtotime($test->date))}}</td>
                                <td><a href="{{asset('view/test/'.$test->id)}}">{{ $test->title}}</a></td>
                                <td>{{$test->period}}</td>
                                <td>{{$test->tchrtitle}} {{UCWORDS($test->firstname)}} {{UCWORDS($test->lastname)}}</td>
                                </tr>
                                @endforeach
                                
                                
                              </tbody></table>
                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                        {{-- <button type="button" class="btn  btn-success center-block">Show All Schedule</button> --}}
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    {{-- </div>

    <div class="row"> --}}

        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Activity Sheets</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                        <table class="table table-bordered table-striped table-hover">
                                <tbody><tr>
                                        <th style="width:100px;">Date</th>
                                        <th>Title</th>
                                        
                                        <th style="width:50px;">Period</th>
                                        <th style="width: 200px">Teacher</th>
                                      </tr>
                                      @foreach($activitysheets as $activitysheet)
                                      <tr>
                                        <td>{{date("M d, Y", strtotime($activitysheet->date))}}</td>
                                      <td><a href="{{asset('view/as/'.$activitysheet->id)}}">{{ $activitysheet->title}}</a></td>
                                      <td>{{$activitysheet->period}}</td>
                                      <td>{{$activitysheet->tchrtitle}} {{UCWORDS($activitysheet->firstname)}} {{UCWORDS($activitysheet->lastname)}}</td>
                                      </tr>
                                      @endforeach
                                      
                                      
                                    </tbody></table>
                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                        {{-- <button type="button" class="btn  btn-success center-block">Show All Schedule</button> --}}
                </div>
            </div><!-- /.box -->
        </div>
        <div class='col-md-6'>
            <!-- Box -->
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Handouts and Notes</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                        <table class="table table-bordered table-striped table-hover">
                                <tbody><tr>
                                        <th style="width:100px;">Date</th>
                                        <th>Title</th>
                                        
                                        <th style="width:50px;">Period</th>
                                        <th style="width: 200px">Teacher</th>
                                      </tr>
                                      @foreach($handouts as $handout)
                                      <tr>
                                        <td>{{date("M d, Y", strtotime($handout->date))}}</td>
                                      <td><a href="{{asset('view/ho/'.$handout->id)}}">{{ $handout->title}}</a></td>
                                      <td>{{$handout->period}}</td>
                                      <td>{{$handout->tchrtitle}} {{UCWORDS($handout->firstname)}} {{UCWORDS($handout->lastname)}}</td>
                                      </tr>
                                      @endforeach
                                      
                                      
                                    </tbody></table>
                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                        {{-- <button type="button" class="btn  btn-success center-block">Show All Schedule</button> --}}
                </div>
            </div><!-- /.box -->
        </div>

 

@push('scripts')

@endpush
</div>

@endsection