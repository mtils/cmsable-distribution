    <ul class="nav nav-tabs">
        @foreach($mainActions as $action)
            <li @if(Route::currentRouteName() == $action->name) class="active"@endif>
                <a href="{{ $action->url }}" onclick="{{ $action->onClick }}" tabindex="-1" href="{{ $action->url }}">{{ $action->title }}</a>
            </li>
        @endforeach

        @if(count($besideActions))

        <li class="dropdown pull-right">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="@lang('ems::actions.actions')">
                <i class="fa fa-gear"></i>
            </a>
            <ul class="dropdown-menu">
                @foreach($besideActions as $action)
                    @if($action->isEnabled())
                        <li role="presentation"><a role="menuitem" onclick="{{ $action->onClick }}" tabindex="-1" href="{{ $action->url }}" class="{{ $action->contexts }}">{{ $action->title }}</a></li>
                    @endif
                @endforeach
            </ul>
        </li>
        @endif
    </ul>
    <div style="clear: both"></div>