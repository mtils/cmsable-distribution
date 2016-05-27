<? $items = App::make('Cmsable\Widgets\Contracts\WidgetItemRepository'); ?>
<ul class="list-unstyled widget-select">
@foreach($widgets as $widget)
<?
    $classTitle = Render::classTitle($widget);
    $modalTitle = Lang::get('ems::actions.widgets.create-widget',['widgetTitle'=>$classTitle]);
    $addButtonText = Lang::get('ems::base.create-object',['class'=>$classTitle]);
    $handleParam = isset($handle) ? "?handle=$handle" : '';
?>
<li>
    <? $item = $items->make($widget->getTypeId()) ?>
    <div class="box box-default box-solid" onclick="editWidgetItem($(this).data('create-url'), '{{ $modalTitle }}')" data-create-url="{{ URL::route('widgets.items.create', [$widget->getTypeId()]) . $handleParam }}" data-editable="{{ (int)$widget->isEditable() }}" data-widget-type="{{ $widget->getTypeId() }}">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $classTitle }}</h3>
        </div>
    <div class="box-body">
    {!! $widget->renderPreview($item) !!}
    </div>
</li>
@endforeach
</ul>