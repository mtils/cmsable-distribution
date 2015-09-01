    @if(count($searchActions))
        <div class="nav-tabs-custom">
            <ul id="tabs-{{ Resource::getCurrentResource() }}" class="nav nav-tabs">

            @foreach($searchActions as $action)
                <li class="active {{ $action->contexts }}">
                    <a href="{{ $action->url }}" data-toggle="tab">{{ $action->title }}</a>
                </li>
            @endforeach

            @if(count($typeActions))
            <li class="dropdown pull-right">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    @lang('ems::actions.actions') <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($typeActions as $action)
                        @if($action->isEnabled())
                            <li role="presentation"><a role="menuitem" onclick="<?= $action->onClick ?>" tabindex="-1" href="{{ $action->url }}" class="{{ $action->contexts }}">{{ $action->title }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </li>
            @endif
            </ul>
            <div class="tab-content">
                @searchForm()
            </div>
        </div>
    @else
        <div class="box">
            <div class="box-body">
                @searchForm()
            </div>
        </div>
    @endif