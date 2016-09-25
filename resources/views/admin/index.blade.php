<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>CMSable | @guessTrans(Menu::current()->getMenuTitle())</title>
    @section('head')
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <!-- link href="/cmsable/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" / -->
    @asset('bootstrap/css/bootstrap.min.css')
    <!-- Font Awesome Icons -->
    @asset('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css')

    <!-- Ionicons -->
    @asset('css/ionicons.min.css')
    <!-- Datepicker -->
    @asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')

    @asset('js/select2/css/select2.min.css')
    @asset('css/filemanager.css')
    <!-- Theme style -->
    @asset('AdminLTE/css/AdminLTE.min.css')
    <!-- iCheck -->
    @asset('plugins/iCheck/square/blue.css')
    <!-- jquery.contextMenu -->
    @asset('js/contextmenu/jquery.contextMenu.min.css')

    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    @asset('AdminLTE/css/skins/_all-skins.min.css')
    @asset('css/admin.css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
    @assets('css')
    <style>{!! Render::css(); !!}</style>
    @show
  </head>
  @section('body')
  <body class="skin-blue">
    <!-- Site wrapper -->
    @section('wrapper')
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
    @show <!-- section('wrapper') -->
    @section('js')
    @asset(URL::route('files.js-config'), 'js')
    @asset('plugins/jQuery/jQuery-2.1.3.min.js')
    @asset('plugins/jQueryUI/jquery-ui-1.11.4.min.js')
    @asset('bootstrap/js/bootstrap.min.js')
    @asset('plugins/slimScroll/jquery.slimscroll.min.js')
    @asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')
    @asset('js/select2/js/select2.full.min.js')
    @asset('plugins/fastclick/fastclick.min.js')
    @asset('js/fileDownload/jquery.fileDownload.js')
    @asset('js/contextmenu/jquery.contextMenu.min.js')
    <!-- html5sortable -->
    <!-- script src='/cmsable/js/html5sortable/html.sortable.min.js'></script -->
    <!-- AdminLTE App -->
    @asset('AdminLTE/js/app.min.js')
    @asset('js/admin.js')
    @assets('js')
    @show
  </body>
  @show
</html>
