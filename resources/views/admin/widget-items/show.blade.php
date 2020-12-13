    @if($framed)
    @include('widget-items.partials.boxed-widget-item')
    @else

    {!! $widget->renderPreview($widgetItem) !!}
    @endif
