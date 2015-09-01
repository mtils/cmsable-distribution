    @foreach($typeActions as $action)
        @if($action->isEnabled())
            <? $buttonText = $action->title ?>
            <div class="btn btn-success btn-file" style="width: {{ (strlen($buttonText) < 10) ? '135' : strlen($buttonText)*10 }}px;" onclick="window.location='{{ $action->url }}'">
                <i class="fa fa-edit"></i>
                {{ $buttonText }}
            </div>
        @endif
    @endforeach
    <br/><br/>
