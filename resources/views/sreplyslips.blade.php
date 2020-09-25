@extends('admin_template')

@section('content')

<div class="row">
        {{-- <pre> {{ print_r($replyslips) }} </pre>
        <pre> {{ print_r($studentuser) }} </pre>
        <pre> {{ print_r($scope) }} </pre>
        <pre> {{ print_r(session('currentIdent')) }} </pre> --}}
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="box-title">
                        <h3 class="box-title">Reply Slips</h3>
                    
                </div>
                <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
            </div>
            <div class="box-body">
                    <table class="table table-hover">
                            <tbody><tr>
                              
                        @if(Auth::user()->type =='s' || Auth::user()->type =='p')        
                              <th>Reply Slip Title</th>
                              <th>Date</th>
                              <th>Status</th>
                              <th>Answer</th>
                            </tr>
                            @foreach($replyslips as $replyslip)
                            <tr>
                            <td><a href="{{asset('view/rs/'.$replyslip->id)}}">{{ $replyslip->title}}</a></td>
                              <td>{{ date('l - M d, Y', strtotime($replyslip->date))}}</td>
                            <td><span class="label label-{{$replyslip->choice != '' ? 'success':'warning'}}">{{ $replyslip->choice != '' ? 'Answered' : 'Pending' }}</span></td>
                            <td>{{$replyslip->choice}}</td>
                            </tr>
                            @endforeach
                        @endif  
                        
                        @if(Auth::user()->type =='t' || Auth::user()->type =='a')
                                <th>ID</th>        
                              <th>Reply Slip Title</th>
                              <th>Date</th>
                              <th>Scope</th>
                              
                            </tr>
                            @foreach($replyslips as $replyslip)
                            <tr>
                            <td>{{$replyslip->id}}</a></td>
                            <td><a href="{{asset('view/rs/'.$replyslip->id)}}">{{$replyslip->title}}</a></td>
                              <td>{{ date('l - M d, Y', strtotime($replyslip->date))}}</td>
                            <td>{{$replyslip->grade}}</span></td>
                          
                            </tr>
                            @endforeach
                        @endif     
                            
                          </tbody></table>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>
</div>

@endsection