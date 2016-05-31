<div class="widget-item-form" data-handle="{{ $handle }}" data-input-prefix="{{ $inputPrefix }}" data-url="{{ URL::route('widgets.items.show-if-valid',[$widget->getTypeId()]) }}" data-type-id="{{ $widget->getTypeId() }}" data-id="{{ $widgetItem->getId() }}">
    {!! $widget->renderForm($widgetItem) !!}
    <? $classTitle = Render::classTitle($widget) ?>
    <? $exists = App::make('Cmsable\Widgets\Contracts\WidgetItemRepository')->exists($widgetItem) ?>
    <? $addButtonText = $exists ? Lang::get('ems::actions.save-changes') : Lang::get('ems::base.create-object',['class'=>$classTitle]) ?>
    <div class="modal-footer" id="overwritten-modal-footer">
        <button type="button" class="btn btn-primary" onclick="updateWidgetData($(this).closest('.widget-item-form'))">{{ $addButtonText }}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ Lang::get('ems::actions.cancel') }}</button>
    </div>
</div>