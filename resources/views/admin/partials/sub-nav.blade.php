    <ul class="nav navbar-nav">
        @foreach($mainActions as $action)
            <li @if(Route::currentRouteName() == $action->name) class="active"@endif>
                <a href="{{ $action->url }}" onclick="{{ $action->onClick }}" tabindex="-1">{{ $action->title }}</a>
            </li>
        @endforeach

        @if(count($besideActions))

        <li class="dropdown pull-right">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" 
title="@lang('ems::actions.more')">@lang('ems::actions.more')
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                @foreach($besideActions as $action)
                    @if($action->isEnabled())
                        <li role="presentation"><a role="menuitem" onclick="{{ $action->onClick }}" tabindex="-1" href="{{ $action->url }}" class="{{ $action->contexts }}">{{ $action->title }}</a></li>
                    @endif
                @endforeach
            </ul>
        </li>
        @endif
    </ul>