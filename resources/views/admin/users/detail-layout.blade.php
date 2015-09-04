@extends('layouts.detail-2col')

@section('side-boxes')
            <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" 
src="/cmsable/img/user-128px.png" alt="User profile picture">
                  <h3 class="profile-username text-center">{{ 
$userTitle or $model->email }}</h3>
                  <p class="text-muted text-center">{{ 
$userType or 'User' }}</p>

                  <ul class="list-group list-group-unbordered">
                    @if(isset($userMainInfos))
                    @foreach($userMainInfos as $key=>$value)
                    <li class="list-group-item">
                      <b>{{ $key }}</b> <a class="pull-right">{{ $value }}</a>
                    </li>
                    @endforeach
                    @else
                    <li class="list-group-item">
                      <b>@lang('models.user.fields.created_at')</b> <a 
class="pull-right"><?= $model->created_at ? 
$model->created_at->format(Lang::get('ems::base.datetime-format')) : ''  ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>@lang('models.user.fields.activated_at')</b> <a 
class="pull-right"><?= $model->activated_at ? 
$model->activated_at->format(Lang::get('ems::base.datetime-format')) : ''  
?></a>
                    </li>
                    <li class="list-group-item">
                      <b>@lang('models.user.fields.last_login')</b> <a 
class="pull-right"><?= $model->last_login ? 
$model->last_login->format(Lang::get('ems::base.datetime-format')) : ''  ?></a>
                    </li>
                    @endif
                  </ul>

                  <!-- a href="#" class="btn btn-primary 
btn-block"><b>Follow</b></a -->
                </div><!-- /.box-body -->
              </div>
@section('sidebox2')
@show
@stop