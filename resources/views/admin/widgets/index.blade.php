<? $items = App::make('Cmsable\Widgets\Contracts\WidgetItemRepository'); ?>
<ul class="list-unstyled widget-select">
@foreach($widgets as $widget)
<?
    $classTitle = Render::classTitle($widget);
    $modalTitle = Lang::get('ems::actions.widgets.create-widget',['widgetTitle'=>$classTitle]);
    $addButtonText = Lang::get('ems::base.create-object',['class'=>$classTitle]);
    $handleParam = isset($handle) ? "?handle=$handle" : '';
    $createUrl = URL::route('widgets.items.create', [$widget->getTypeId()]) . "?handle=$handle&input_prefix=$inputPrefix";
    $draggable = false;
    $previewMode = true;
?>
<li>
    <? $widgetItem = $items->make($widget->getTypeId()) ?>
    <? $hideCloseButton = true ?>
    @include('widget-items.partials.boxed-widget-item')
</li>
@endforeach
</ul>