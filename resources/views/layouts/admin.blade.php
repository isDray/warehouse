<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>倉儲系統</title>
  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{url('../node_modules/admin-lte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{url('../node_modules/admin-lte/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{url('../node_modules/admin-lte/bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('../node_modules/admin-lte/dist/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="{{url('../node_modules/admin-lte/dist/css/skins/skin-blue.min.css')}}">

  <link rel="stylesheet" href="{{url('../node_modules/admin-lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- REQUIRED JS SCRIPTS -->
  <!-- jQuery 3 -->
  <script src="{{url('../node_modules/admin-lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="{{url('../node_modules/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{url('../node_modules/admin-lte/dist/js/adminlte.min.js')}}"></script>
  <script src="{{url('../node_modules/admin-lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{url('../node_modules/admin-lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
  <script src="{{url('../node_modules/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>   

<script type="text/javascript">

  var request = $.ajax({
      url: "{{url('/commonSuccessMsg')}}",
      method: "POST",
      data: { _token: "{{ csrf_token() }}",},
      dataType: "JSON"
  });
 
  request.done(function( msg ) {

      if( msg !== false ){
          
          Swal.fire({
              html: msg,
              title: '執行成功',
              type: 'success',
              confirmButtonText: '關閉',
          }); 
      
      }
  });
 
  request.fail(function( jqXHR, textStatus ) {
      
  });

  var request2 = $.ajax({
      url: "{{url('/commonErrMsg')}}",
      method: "POST",
      data: { _token: "{{ csrf_token() }}",},
      dataType: "JSON"
  });
 
  request2.done(function( msg ) {

      if( msg !== false ){
          
          Swal.fire({
              html: msg,
              title: '執行失敗',
              type: 'error',
              confirmButtonText: '關閉',
          });        
      }
  });
 
  request2.fail(function( jqXHR, textStatus ) {
      
  });

</script>       
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
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>倉儲系統</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>倉儲系統</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <!-- 郵件圖示 
          <li class="dropdown messages-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>

                <ul class="menu">
                  <li>
                    <a href="#">
                      <div class="pull-left">

                        <img src="{{url('../node_modules/admin-lte/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
                      </div>

                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>

                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>

                </ul>

              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          -->


          <!-- 提示區塊
          <li class="dropdown notifications-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>

                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>

                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>

          <li class="dropdown tasks-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>

                <ul class="menu">
                  <li>
                    <a href="#">

                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>

                      <div class="progress xs">

                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>

                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          -->
          <!-- 人物頭像
          <li class="dropdown user user-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

              <img src="{{url('../node_modules/admin-lte/dist/img/user2-160x160.jpg')}}" class="user-image" alt="User Image">

              <span class="hidden-xs">Alexander Pierce</span>
            </a>
            <ul class="dropdown-menu">

              <li class="user-header">
                <img src="{{url('../node_modules/admin-lte/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>

              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>

              </li>

              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="#" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          -->
          <li>
              <a href="#" class='fa fa-user'> {{Auth::user()->name}} </a>
          </li>
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- 
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{url('../node_modules/admin-lte/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>
       -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">



        <li class="header">操作選單</li>
        <!-- Optionally, you can add icons to the links -->
        <!--
        <li class="">
            <a href="#"><i class="fa fa-dashboard"></i><span>資訊面板</span></a>

        </li>
        <li class="">
            <a href="#"><i class="fa fa-user"></i><span>使用者權限管理</span></a>

        </li>
        -->
        <!-- 開闔 treeview-->
        @role('admin')
        <li class='menu-open'  data-accordion='0'>
            <a href="#"><i class="	fa fa-users"></i> <span>使用者管理</span>
            <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
            </span>
            </a>
            <ul class="treeview-menu" style="display: block;">
                <li><a href="{{url('/admin_user_all')}}">使用者列表</a></li>
            </ul>            
        </li>
        @endrole

        <li class=' menu-open'  data-accordion='0'>
            <a href="#"><i class="fa fa-cubes"></i> <span>倉儲相關操作</span>
            <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
            </span>
            </a>
            <ul class="treeview-menu" style="display: block;">
                <li><a href="{{url('/admin_goods_find')}}">商品位置搜尋</a></li>
                <li><a href="{{url('/admin_shelf_nullBlock')}}">空貨架搜尋</a></li>
                <li><a href="{{url('/admin_self_all')}}">貨架圖示清單</a></li>
                <li><a href="{{url('/admin_whare_house')}}">倉庫管理</a></li>
                <li><a href="{{url('/admin_shelf')}}">貨架管理</a></li>
                <li><a href="{{url('/admin_shelf_multipleToBlock')}}">大量入倉</a></li>
                <li><a href="{{url('/admin_shelf_import')}}">匯入貨架</a></li>
            </ul>            
        </li>

        <li class='menu-open' data-accordion='0'>
            <a href="#"><i class="fa fa-barcode"></i> <span>商品相關操作</span>
            <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
            </span>
            </a>
            <ul class="treeview-menu" style="display: block;">
                <li><a href="{{url('/admin_goods_import')}}">匯入貨號</a></li>
            </ul>            
        </li>
        
        <!--
        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link in level 2</a></li>
            <li><a href="#">Link in level 2</a></li>
          </ul>
        </li>
        -->

      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{$pageName }}
        <small>{{$pageName2 }}</small>
      </h1>
      
      <!--
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
      -->

    </section>

    <!-- Main content -->
    <section class="content container-fluid">

 @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href="#">yes94136</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <!--<li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>-->
      <!--<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>-->
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        
        <a class="dropdown-item  fa fa-sign-out" href="{{ route('logout') }}"
          onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
        {{ __('登出使用者') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>

        <!--
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
        </ul>-->
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
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->



@if( $errors->any() )
<script type="text/javascript">
var formErrMsg = '';
@foreach ($errors->all() as $error)
    formErrMsg += "<span style='color:#f27474;font-size:16px;'>{{ $error }}</span><br>";
@endforeach
    
    Swal.fire({
        html: formErrMsg,
        title: '發生錯誤!',
        type: 'error',
        confirmButtonText: '關閉',
    });
</script>

@endif


@if(Session::has('test'))
<script type="text/javascript">
    Swal.fire({
        html: "{{ Session::get('test') }}",
        title: '發生錯誤!',
        type: 'error',
        confirmButtonText: '關閉',
    });
</script>

@endif

</body>
</html>