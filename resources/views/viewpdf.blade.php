@extends('admin_template')

@section('content')

<div class="row">
        {{-- <pre> {{ print_r($replyslips) }} </pre>
        <pre> {{ print_r($studentuser) }} </pre>
        <pre> {{ print_r($scope) }} </pre>
        <pre> {{ print_r(session('currentIdent')) }} </pre> --}}
@if( Auth::user()->type == 'a')
    <div class="col-md-8">
@endif
@if(Auth::user()->type=='s' or Auth::user()->type=='p' or Auth::user()->type=='t' )
    <div class="col-md-12">
@endif
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="box-title">
                        <h3 class="box-title">View</h3>
                    
                </div>
                <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
            </div>
            @if($uploadtype == 'test')
            <div class="box-body">
                    <object data="{{asset ("/uploads/pdf/".$test->filename) }}" type="application/pdf" width="100%" height="600px">
                    alt : <a href="{{asset ("/uploads/pdf/".$test->filename) }}">{{$test->title}}</a>
                      </object>
            </div>
            @endif
            @if($uploadtype == 'activitysheet')
            <div class="box-body">
                    <object data="{{asset ("/uploads/pdf/".$activitysheet->filename) }}" type="application/pdf" width="100%" height="600px">
                    alt : <a href="{{asset ("/uploads/pdf/".$activitysheet->filename) }}">{{$activitysheet->title}}</a>
                      </object>
            </div>
            @endif
            @if($uploadtype == 'handout')
            <div class="box-body">
                    <object data="{{asset ("/uploads/pdf/".$handout->filename) }}" type="application/pdf" width="100%" height="600px">
                    alt : <a href="{{asset ("/uploads/pdf/".$handout->filename) }}">{{$handout->title}}</a>
                      </object>
            </div>
            @endif
            @if($uploadtype == 'replyslip')
            <div class="box-body">
                    <object data="{{asset ("/uploads/pdf/".$replyslip->filename) }}" type="application/pdf" width="100%" height="600px">
                    alt : <a href="{{asset ("/uploads/pdf/".$replyslip->filename) }}">{{$replyslip->title}}</a>
                      </object>
            </div>
            @endif
            <div class="box-footer">

            </div>
        </div>
    </div>
    @if( Auth::user()->type == 'a')
    <div class="col-md-4">
            <div class="box box-primary">
                    <div class="box-header with-border">
                            <div class="box-title">
                                    <h3 class="box-title">Edit</h3>
                                
                            </div>
                            <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                                </div>
                    </div>
                    <div class="box-body">
                           Add options for this reply slip: 
                           <i>to track results of responses</i>
                           <i class="fa fa-plus"></i>
                           <input type="test" class="form-group form-control">
                    </div>
                    <div class="box-footer">
        
                    </div>
            </div>
    </div>
    @endif
</div>

@endsection