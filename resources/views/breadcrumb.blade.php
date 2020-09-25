
@if(Auth::user()->type == 's'  or Auth::user()->type == 'p')
@if(Request::is('overview'))
<ol class="breadcrumb">
    <li><a href="/overview"><i class="fa fa-home"></i> Overview</a></li>
    
</ol>
@endif

@if(Request::is('replyslips'))
<ol class="breadcrumb">
        <li><a href="/overview"><i class="fa fa-home"></i> Overview</a></li>
    <li><a href="/replyslips"></i> Replyslips</a></li>
    
</ol>
@endif

@if(Request::is("subjects/*"))
<ol class="breadcrumb">
<li><a href="/overview"><i class="fa fa-home"></i> Overview</a></li>
@foreach($subjects as $subject)
    @if(Request::is('subjects/'.str_replace(' ','_',trim($subject->subject))) )
    <li><a href="/subjects/{{str_replace(' ','_',trim($subject->subject))}}"> {{ucwords($subject->subject)}}</a></li>
    @endif
@endforeach


</ol>
@endif

@if(Request::is("view/test/*"))
<ol class="breadcrumb">
<li><a href="/overview"><i class="fa fa-home"></i> Overview</a></li>
<li><a href="/subjects/{{$test->subject}}"> {{ucwords($test->subject)}}</a></li>
<li> {{ucwords($test->title)}}</li>
</ol>
@endif

@if(Request::is("view/as/*"))
<ol class="breadcrumb">
<li><a href="/overview"><i class="fa fa-home"></i> Overview</a></li>
<li><a href="/subjects/{{$activitysheet->subject}}"> {{ucwords($activitysheet->subject)}}</a></li>
<li> {{ucwords($activitysheet->title)}}</li>
</ol>
@endif

@if(Request::is("view/ho/*"))
<ol class="breadcrumb">
<li><a href="/overview"><i class="fa fa-home"></i> Overview</a></li>
<li><a href="/subjects/{{$handout->subject}}"> {{ucwords($handout->subject)}}</a></li>
<li> {{ucwords($handout->title)}}</li>
</ol>
@endif

@if(Request::is('directory/students'))
<ol class="breadcrumb">
        <li><a href="/overview"><i class="fa fa-home"></i> Overview</a></li>
<li><a href="/directory/students"></i> {{$studentuser->grade}}-{{$studentuser->section}} Students</a></li>
    
</ol>
@endif

@if(Request::is('directory/teachers'))
<ol class="breadcrumb">
        <li><a href="/overview"><i class="fa fa-home"></i> Overview</a></li>
<li><a href="/directory/teachers"></i> {{$studentuser->grade}}-{{$studentuser->section}} Teachers</a></li>
    
</ol>
@endif

@if(Request::is('directory/staff'))
<ol class="breadcrumb">
        <li><a href="/overview"><i class="fa fa-home"></i> Overview</a></li>

    
</ol>
@endif

@endif