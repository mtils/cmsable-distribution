        <div class="nav-tabs-custom">

            <ul class="nav nav-tabs">
                @foreach(Actions::forItem($model, 'show-tabs') as $action)
                    <li class="{{ $action->isActive() ? 'active' : '' }}"><a href="{{ $action->url }}">{{ $action->title }}</a></li>
                @endforeach
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
