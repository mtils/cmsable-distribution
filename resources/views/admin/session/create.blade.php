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
        @if(!Notification::count()) @lang('ems::forms.login.login-to-start.title') @endif</p>
        <? $form = Resource::form() ?>

        <!-- form action="{{ URL::route('session.store') }}" method="post" -->
        <form {!! $form->attributes !!}>
          <div class="form-group has-feedback @if($errors->has('email')) has-error @endif">
            <input {!! $form('email')->addCssClass('form-control')->attributes->set('placeholder', $form('email')->title) !!}>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback @if($errors->has('password')) has-error @endif">
            <!-- input type="password" name="password" class="form-control" placeholder="@lang('ems::forms.login.password.title')" -->
            <input {!! $form('password')->addCssClass('form-control')->attributes->set('placeholder', $form('email')->title) !!}>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> @lang('ems::forms.login.remember-me.title')
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">{{ $form->actions->first()->title }}</button>
            </div><!-- /.col -->
          </div>
        </form>

        <!-- div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
        </div --><!-- /.social-auth-links -->
        @foreach($form->fields as $field)
        @if($field->className == 'LiteralField')
        {!! $field !!}<br/>
        @endif
        <!-- a href="{{ URL::route('password.create-email') }}">@lang('ems::forms.login.forgot-password.title')</a><br -->
        @endforeach
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