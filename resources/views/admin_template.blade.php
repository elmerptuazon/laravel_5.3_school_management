<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>STP Central</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset("/bower_components/bootstrap/dist/css/bootstrap.min.css") }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset("/bower_components/font-awesome/css/font-awesome.min.css") }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset("/bower_components/Ionicons/css/ionicons.min.css") }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css")}}">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/skins/skin-blue.min.css")}}">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{asset("/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css")}}">
  <!-- fullCalendar 2.2.5-->
  <link href="{{asset("/bower_components/fullcalendar/dist/fullcalendar.min.css")}}" rel="stylesheet" type="text/css" />
  <link href="{{asset("/bower_components/fullcalendar/dist/fullcalendar.print.css")}}" rel="stylesheet" type="text/css" media='print' />
  <!-- Pace style -->
  <link rel="stylesheet" href="{{asset("/bower_components/PACE/themes/white/pace-theme-minimal.css")}}">

  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{asset("/bower_components/bootstrap-wysihtml5/dist/bootstrap-wysihtml5-0.0.2.css")}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- ADDED BY ELMER*** -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="{{ asset("/css/controlToggleBtn.css") }}">

    @yield('table_assets')
    
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" >

  <!-- Main Header -->
  @include('header')

  <!-- Left side column. contains the logo and sidebar -->
  @include('sidebar')
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="goodload">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{ $page_title or "OVERVIEW" }}
                <small>{{ $page_description or null }}</small>
      </h1>
      <!-- You can dynamically generate breadcrumbs here -->
      @include('breadcrumb')
    </section>

    <!-- Main content -->
    <section class="content container-fluid" >
      <style>#goodload{
          /* background-image:url('some-image.jpg'); */
          display:none;
      }</style>

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <!-- Your Page Content Here -->
        @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  @include('footer')

  {{-- <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                    <span class="label label-danger pull-right">70%</span>
                  </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar --> --}}
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
@push('scripts')
<script>
$(document).ready(function(){
    $('#goodload').fadeIn(500);

  });
  </script>
  @endpush
<!-- jQuery 3 -->
<script src="{{ asset ("/bower_components/jquery/dist/jquery.min.js") }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset ("/bower_components/bootstrap/dist/js/bootstrap.min.js") }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset ("/bower_components/admin-lte/dist/js/adminlte.min.js") }}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset ("/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js") }}"></script>
 <!-- fullCalendar 2.2.5 -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>
 <script src="{{asset ("/bower_components/fullcalendar/dist/fullcalendar.min.js")}}" type="text/javascript"></script>
 <!-- PACE -->
 <script src="{{asset ("/bower_components/PACE/pace.min.js")}}"></script>
  <!-- Bootstrap WYSIHTML5 -->
<script src="{{asset ("/bower_components/bootstrap-wysihtml5/dist/bootstrap-wysihtml5-0.0.2.min.js")}}"></script>
<!-- CK Editor -->
<script src="{{asset ("/bower_components/ckeditor/ckeditor.js")}}"></script>
<!-- added by elmer -->

<!-- Date Picker (range)-->

  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

@stack('scripts')
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
     
</body>
</html>