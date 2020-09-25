
{{-- {{print_r(session('children'))}}
{{print_r(session('currentIdent'))}}
<pre>{{print_r(session())}}</pre> --}}
<table class="table table-striped table-hover">
        <tbody><tr >
            <th>Subject</th>
            <th>Schedule</th>
            <th>day</th>
          {{-- <th style="width: 200px">Teacher</th> --}}
        </tr>
    
        @foreach($monday as $mon)
        <tr >
            <td>{{UCWORDS($mon->subject)}}</td>
            <td>{{$mon->schedule}}</td>
            <td>Monday</td>
        </tr>
        @endforeach

        @foreach($tuesday as $tues)
        <tr >
            <td>{{UCWORDS($tues->subject)}}</td>
            <td>{{$tues->schedule}}</td>
            <td>Tuesday</td>
        </tr>
        @endforeach
        @foreach($wednesday as $wed)
        <tr >
            <td>{{UCWORDS($wed->subject)}}</td>
            <td>{{$wed->schedule}}</td>
            <td>Wednesday</td>
        </tr>
        @endforeach
        @foreach($thursday as $thurs)
        <tr >
            <td>{{UCWORDS($thurs->subject)}}</td>
            <td>{{$thurs->schedule}}</td>
            <td>Thursday</td>
        </tr>
        @endforeach
        @foreach($friday as $fri)
        <tr>
            <td>{{UCWORDS($fri->subject)}}</td>
            <td>{{$fri->schedule}}</td>
            <td>Friday</td>
        </tr>
        @endforeach



        </tbody>

</table>