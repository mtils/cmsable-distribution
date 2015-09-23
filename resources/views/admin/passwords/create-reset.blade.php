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
        <? $form = Resource::form()->setRuleNames(Resource::rules())->fillByArray(['token'=>$token]) ?>
        <form {!! $form->attributes !!}>
          <div class="form-group has-feedback @if($errors->has('password')) has-error @endif">
            <input {!! $form('password')->addCssClass('form-control')->attributes->set('placeholder', $form('password')->title) !!}>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback @if($errors->has('password_confirmation')) has-error @endif">
            <input {!! $form('password_confirmation')->addCssClass('form-control')->attributes->set('placeholder', $form('password_confirmation')->title) !!}>
            <span class="glyphicon glyphicon-repeat form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <a class="muted" href="{{ URL::route('session.create') }}">@lang('ems::forms.password-email.jump-to-login.title')</a><br>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">{{ $form->actions->first()->title }}</button>
            </div><!-- /.col -->
            @foreach($form->fields as $field)
            @if($field->className == 'HiddenField')
            <input {!! $field->attributes !!}>
            @endif
            @endforeach
          </div>
        </form>
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