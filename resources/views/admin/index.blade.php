<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>CMSable | @guessTrans(Menu::current()->getMenuTitle())</title>
    @section('head')
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="/cmsable/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Datepicker -->
    <link href="/cmsable/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

    <link href="/cmsable/js/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/cmsable/css/filemanager.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="/cmsable/AdminLTE/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="/cmsable/AdminLTE/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link href="/cmsable/css/admin.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    @show
  </head>
  <body class="skin-blue">
    <!-- Site wrapper -->
    <div class="wrapper">
      @include('partials.main-header')

      <!-- =============================================== -->

      @include('partials.sidebar')

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        @section('content-header')
        @include('partials.content-header')
        @show
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-12">
                {!! Notification::showInfo('<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>:message</div>') !!}
                {!! Notification::showSuccess('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>:message</div>') !!}
                {!! Notification::showWarning('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>:message</div>') !!}
                {!! Notification::showError('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>:message</div>') !!}
            </div>
         </div>
         <div class="row">
         @yield('content')
         </div>

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights reserved.
      </footer -->
    </div><!-- ./wrapper -->
    @section('js')
    <!-- jQuery 2.1.3 -->
    <script src="{{ URL::route('files.js-config') }}"></script>
    <script src="/cmsable/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="/cmsable/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- SlimScroll -->
    <script src="/cmsable/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- SlimScroll -->
    <script src="/cmsable/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/cmsable/js/select2/js/select2.full.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='/cmsable/plugins/fastclick/fastclick.min.js'></script>
    <!-- jquery.fileDownload -->
    <script src='/cmsable/js/fileDownload/jquery.fileDownload.js'></script>
    <!-- AdminLTE App -->
    <script src="/cmsable/AdminLTE/js/app.min.js" type="text/javascript"></script>
    <script src="/cmsable/js/admin.js" type="text/javascript"></script>
    @show
  </body>
</html>