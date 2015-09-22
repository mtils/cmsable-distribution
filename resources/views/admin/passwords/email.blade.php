@extends('index')

@section('body')
<body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="{{ URL::route('session.create') }}"><b>CMS</b>able</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">
        {!! Notification::showInfo('<span class="text-aqua">:message</span>') !!}
        {!! Notification::showSuccess('<span class="text-green">:message</span>') !!}
        {!! Notification::showWarning('<span class="text-yellow">:message</span>') !!}
        {!! Notification::showError('<span class="text-red">:message</span>') !!}
        @if(!Notification::count()) @lang('ems::forms.password-email.fill-to-send-mail.title') @endif</p>
        <form action="{{ URL::route('password.send-email') }}" method="post">
          <div class="form-group has-feedback @if($errors->has('email')) has-error @endif">
            <input type="email" name="email" class="form-control" placeholder="@lang('ems::forms.password-email.email.title')">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <a class="muted" href="{{ URL::route('session.create') }}">@lang('ems::forms.password-email.jump-to-login.title')</a><br>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('ems::forms.actions.send.title')</button>
            </div><!-- /.col -->
          </div>
        </form>

        <!-- div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
        </div --><!-- /.social-auth-links -->

        <!-- a href="register.html" class="text-center">@lang('ems::forms.login.register-new.title')</a -->
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.3 -->
    <script src="/cmsable/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="/cmsable/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="/cmsable/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
@endsection