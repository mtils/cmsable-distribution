<div class="widget-item-form" data-handle="{{ $handle }}" data-url="{{ URL::route('widgets.items.show-if-valid',[$widget->getTypeId()]) }}" data-type-id="{{ $widget->getTypeId() }}" data-id="{{ $widgetItem->getId() }}">
    {!! $widget->renderForm($widgetItem) !!}
    <? $classTitle = Render::classTitle($widget) ?>
    <? $addButtonText = Lang::get('ems::base.create-object',['class'=>$classTitle]) ?>
    <div class="modal-footer" id="overwritten-modal-footer">
        <button type="button" class="btn btn-primary" onclick="saveWidget($(this).closest('.widget-item-form'))">{{ $addButtonText }}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ Lang::get('ems::actions.cancel') }}</button>
    </div>
</div>