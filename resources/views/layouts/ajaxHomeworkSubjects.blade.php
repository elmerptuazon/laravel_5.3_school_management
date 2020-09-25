
        <ul style="list-style: none;">
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
                <h4 style="font-weight:bold;">{{UCWORDS($homework->subject)}}  {{$homework->grade .$homework->section}}</h4><span style="float:right; font-style:italic;">{{date("l M d, Y", strtotime($homework->pubdate))}}</span>
                <span style="display:block;">{{$homework->title}} {{UCWORDS($homework->firstname)}} {{UCWORDS($homework->lastname)}}</span>
                <p class="">{!! htmlspecialchars_decode($homework->description)!!}</p>
                            <span class="read-more pull-right-container" style="display:block; width:100%;">
                                    <i class="fa  pull-right  fa-angle-down"></i>
                                  </span>
                </li>
                @endforeach
                
            </ul>
