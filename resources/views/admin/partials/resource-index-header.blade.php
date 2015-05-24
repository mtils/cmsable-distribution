@if(isset($form) && $form)
    <div class="nav-tabs-custom">
        <ul id="search-tabs" class="nav nav-tabs">
            <li class="active"><a href="#" data-toggle="tab">Einfache Suche</a></li>
            <? $actions = Actions::forCollection($search) ?>
            @if(count($actions))
            <li class="dropdown pull-right">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Aktionen <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($actions->filtered('!whole-result') as $action)
                    @if($action->isEnabled())
                    <li role="presentation"><a role="menuitem" onclick="{{ $action->onClick }}" tabindex="-1" href="{{ $action->url }}" class="{{ $action->contexts }}">{{ $action->title }}</a></li>
                    @endif
                    @endforeach
                    <li class="divider"></li>
                    @foreach($actions->filtered('whole-result') as $action)
                    @if($action->isEnabled())
                    <li role="presentation"><a role="menuitem" onclick="{{ $action->onClick }}" tabindex="-1" href="{{ $action->url }}" class="{{ $action->contexts }}">{{ $action->title }}</a></li>
                    @endif
                    @endforeach
                </ul>
            </li>
            @endif
        </ul>
    {!! $form !!}
    </div>
@endif