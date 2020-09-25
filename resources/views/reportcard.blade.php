@extends('admin_template')

@section('content')


        <!--testing pace-->
        <div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Report Card </h3>
              </div>
              <div class="box-body ">
              <style>
                @media 
                  only screen and (max-width: 720px),(min-device-width: 360px) and (max-device-width: 1024px){
                    .table-inner{
                      table-layout: fixed;
                      min-width:1390px;
                    }
                    .table-outter{
                      overflow-x: auto;
                      border: 1px solid gray;
                      width: auto;
                      margin-left: 10px;
                      margin-right: 10px;
                    }
                    /* .contact{
                      float: left;
                    } 
                    .address{
                      float: left;
                    } */
                    .schoolLogo{
                      display: block;
                      margin-left: auto;
                      margin-right: auto;
                      width: 25%
                    }
                    /* .counsle{
                      float: left;
                    } */
                    .studentInfo, .sy{
                      margin-left: 15px;
                      clear: both;
                    }
                  }
                  /* @media end.. */

                  img{
                    display: block;
                    margin-left: auto;
                    margin-right: auto;
                    width: 50%
                  }
                th{
                  text-align: center;
                }
                td{
                  text-align: center;
                }
                tr.trBody:nth-child(even), tr.gradingChart:nth-child(even), tr.commentsChart:nth-child(even){
                  background-color: #f2f2f2;
                }         
                /* .studentInfo{
                  float: right;
                } */
                .studNfo{
                  margin: 0px;
                  padding: 0px;
                }             
              </style>
                <!--Logo and School Name-->
                <div class="row">
                  <!-- <div class="rotateTargetHeader"> -->
                  <div class="col-xs-12 col-md-3">

                    <img class="schoolLogo"src="https://www.garlandisd.net/sites/default/files/styles/img_node/public/blueribbon_2014.jpg"alt="schoolLogo" />
                  </div>
                  <div class="col-xs-12 col-md-4 center-block">
                    <div><h1 class="schoolBoard col-xs-12"> Your School Name</h1></div>
                    <div class="address col-xs-12" style="float: left;"><small>School Address</small></div>
                    <div class="contact col-xs-12" style="float: right;"><small>School Contact Number</small></div>
                  </div>
                  <div class="col-xs-12 col-md-5">
                    <div class="col-xs-12 col-md-6"><small class="sy">School Year</small></div>
                    <div class="col-xs-12 col-md-6 studentInfo">
                      <div class="col-xs-12 col-md-12 studNfo">Sam Sample</div>
                      <div class="col-xs-12 col-md-12 studNfo">Grade: 9</div>
                      <div class="col-xs-12 col-md-12 studNfo">student Id: xxxxxxxxxxx</div>
                    </div>                    
                  </div>
                  <div class="col-xs-12 col-md-12">
                    <div class="col-md-6">Grading Period Ending: June 14, 2019</div>
                    <div class="col-md-6 counsle">Counselor: Rapp, D</div>
                  </div>
                </div>
                <div class="row">
                  <div class='table-outter'>
                    <table border="1" class="table-inner" style="width: 1350px; margin-left: 35px;">
                      <thead>
                        <tr>
                          <th style="width: 15px;">p</th>
                          <th class="headerCourse" style="width: 150px;" rowspan="6">SUBJECTS</th>
                          <!-- <th class="headerTchr" style="width: 250px;" rowspan="6">TEACHER</th> -->
                          <th class="headerGrades" style="width: 500px;" colspan="6">GRADES</th>
                          <th class="headerCredit" style="width: 120px;" rowspan="1">DEPORTMENT</th>                                                                              
                          <th class="tr2" style="width: 25px;" rowspan="1">1st</th>
                          <th class="tr2" style="width: 25px;" rowspan="1">2nd</th>
                          <th class="tr2" style="width: 25px;" rowspan="1">3rd</th>
                          <th class="tr2" style="width: 25px;" rowspan="1">4th</th>
                          <th class="tr2" style="width: 25px;" rowspan="1">Final</th>
                        </tr>
                        <tr>
                          <th>e</th>
                          <th class="tr2" style="width: 25px;" rowspan="5">1st</th>
                          <th class="tr2" style="width: 25px;" rowspan="5">2nd</th>
                          <th class="tr2" style="width: 25px;" rowspan="5">3rd</th>
                          <th class="tr2" style="width: 25px;" rowspan="5">4th</th>                          
                          <th class="tr2" style="width: 25px;" rowspan="5">Final</th>
                          <th class="tr2" style="width: 25px;" rowspan="5">Action Taken</th>
                          <!-- <th class="tr2" style="width: 25px;" rowspan="5">General Average</th> -->
                          <th class="tr2" style="width: 25px;" rowspan="2">Club activity</th>                                                    
                          <td rowspan="2"></td>
                          <td rowspan="2"></td>
                          <td rowspan="2"></td>
                          <td rowspan="2"></td>
                          <td rowspan="2"></td> 
                        </tr>
                        <tr>
                          <th>r</th>
                        </tr>
                        <tr>
                          <th>o</th>        
                          <th class="tr2" style="width: 25px;" rowspan="2">Conduct</th>   
                          <td rowspan="2"></td>
                          <td rowspan="2"></td>
                          <td rowspan="2"></td>
                          <td rowspan="2"></td>
                          <td rowspan="2"></td>                                                                   
                        </tr>
                        <tr>
                          <th>i</th>
                        </tr>
                        <tr>
                          <th>d</th>
                        <th class="headerCredit" style="width: 150px;" rowspan="1" colspan="6">Attendance</th>
                        </tr>
                      </thead>
                    <tbody>
                      <tr class="trBody">
                        <td>1</td>
                        <td>Christian Living</td>
                        <!-- <td>Massengill-Johnson, T</td> -->
                        <td>{{$christian_living_1st->final_grade or ''}}</td>
                        <td>{{$christian_living_2nd->final_grade or ''}}</td>
                        <td>{{$christian_living_3rd->final_grade or ''}}</td>
                        <td>{{$christian_living_4th->final_grade or ''}}</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                        <!--end in grades grid-->                        
                        <th class="headerCredit" style="width: 150px;" rowspan="2">Days of School</th>                    
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>                                                            
                      </tr>
                      <tr class="trBody">
                        <td>2</td>
                        <td>English</td>
                        <!-- <td>Velazquez-Lopez, J</td> -->
                        <td>{{$english_1st->final_grade or ''}}</td>
                        <td>{{$english_2nd->final_grade or ''}}</td>
                        <td>{{$english_3rd->final_grade or ''}}</td>
                        <td>{{$english_4th->final_grade or ''}}</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                        <!--end in grades grid-->
                      </tr>
                      <tr class="trBody">
                        <td>3</td>
                        <td>Filipino</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>{{$filipino_1st->final_grade or ''}}</td>
                        <td>{{$filipino_2nd->final_grade or ''}}</td>
                        <td>{{$filipino_3rd->final_grade or ''}}</td>
                        <td>{{$filipino_4th->final_grade or ''}}</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                        <th class="headerCredit" style="width: 150px;" rowspan="2">Days of Absent</th>                    
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td> 
                      </tr>
                      <tr class="trBody">
                        <td>4</td>
                        <td>Araling Panlipunan</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>{{$araling_panlipunan_1st->final_grade or ''}}</td>
                        <td>{{$araling_panlipunan_2nd->final_grade or ''}}</td>
                        <td>{{$araling_panlipunan_3rd->final_grade or ''}}</td>
                        <td>{{$araling_panlipunan_4th->final_grade or ''}}</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                      </tr>
                      <tr class="trBody">
                        <td>5</td>
                        <td>Mathematics</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>{{$math_1st->final_grade or ''}}</td>
                        <td>{{$math_2nd->final_grade or ''}}</td>
                        <td>{{$math_3rd->final_grade or ''}}</td>
                        <td>{{$math_4th->final_grade or ''}}</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                        <th class="headerCredit" style="width: 150px;" rowspan="2">Tardy</th> 
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>                    
                 
                      </tr>
                      <tr class="trBody">
                        <td>6</td>
                        <td>Science</td>
                        <!-- <td>Wonderfsul, K</td> -->
                        <td>{{$science_1st->final_grade or ''}}</td>
                        <td>{{$science_1st->final_grade or ''}}</td>
                        <td>{{$science_1st->final_grade or ''}}</td>
                        <td>{{$science_1st->final_grade or ''}}</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                      </tr>
                      <tr>
                        <td>7</td>
                        <td colspan="7"></td>
                        <td colspan="6" rowspan="9">
                            <table border="1" style="width: 100%;">
                                <tr>
                                  <th colspan="10">Grading</th>
                                </tr>
                                <tr>
                                  <th colspan="2">Academic ad Special Subjects:</th>
                                  <th colspan="2">Deportment</th>
                                </tr>
                                <tr class="gradingChart">
                                  <td>Advance</td>
                                  <td>90 - 100</td>
                                  <td>O - Outstanding</td>
                                  <td>97 - 100</td>                      
                                </tr>
                                 <tr class="gradingChart">
                                   <td>Proficient</td>
                                   <td>85 - 89</td>
                                   <td>HS - Hightly Satisfactory</td>
                                   <td>92 - 96</td>    
                                </tr>
                                 <tr class="gradingChart">
                                   <td>Approaching Proficiency</td>
                                   <td>80 - 84</td>
                                   <td>VS - Very Satisfactory</td>
                                   <td>86-91</td>    
                                </tr>
                                 <tr class="gradingChart">
                                   <td>Developing</td>
                                   <td>75 - 79</td>
                                   <td>S - Satisfact</td>
                                   <td>80 - 25</td>    
                                </tr>
                                 <tr class="gradingChart">
                                   <td>Beginning</td>
                                   <td>Below - 74</td>
                                   <td>MS - Moderate Satisfactory</td>
                                   <td>75 - 79</td>    
                                </tr>
                                <tr class="gradingChart">
                                   <td> </td>
                                   <td> </td>
                                   <td>NI - Needs improvement</td>
                                   <td>70 - 74</td>    
                                </tr>
                                <tr class="gradingChart">
                                   <td> </td>
                                   <td> </td>
                                   <td>NSA - Needs Special Attention</td>
                                   <td>65 - 69</td>    
                                </tr>
                              </table>
                        </td>
                      </tr>
                      <tr class="trBody">
                        <td>8</td>
                        <td>Penmanship</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>90</td>
                        <td>88</td>
                        <td>93</td>
                        <td>89</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                      </tr>
                      <tr class="trBody">
                        <td>9</td>
                        <td>Health</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>90</td>
                        <td>88</td>
                        <td>93</td>
                        <td>89</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                      </tr>
                      <tr class="trBody">
                        <td>10</td>
                        <td>Physical Education</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>90</td>
                        <td>88</td>
                        <td>93</td>
                        <td>89</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                      </tr>
                      <tr class="trBody">
                        <td>11</td>
                        <td>Music</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>90</td>
                        <td>88</td>
                        <td>93</td>
                        <td>89</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                      </tr>
                      <tr class="trBody">
                        <td>12</td>
                        <td>Arts</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>90</td>
                        <td>88</td>
                        <td>93</td>
                        <td>89</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                      </tr>
                      <tr class="trBody">
                        <td>13</td>
                        <td>Computer</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>90</td>
                        <td>88</td>
                        <td>93</td>
                        <td>89</td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                      </tr>
                      <tr class="trBody">
                        <td>14</td>
                        <td colspan="7"></td>
                      </tr>
                      <tr class="trBody">
                        <td>15</td>
                        <td>GENERAL AVERAGE</td>
                        <!-- <td>Wonderful, K</td> -->
                        <td>90</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <!-- <td></td> -->
                      <!-- </tr> -->
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row" style="margin-top: 10px;">
                <div class="col-md-4">

                  </div>
                  <!-- <div class="col-md-8"> -->
                    <style>
                     
                    </style>
   
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer" >
              </div><!-- /.box-footer-->
            </div>
          </div>
        </div>
 

@endsection