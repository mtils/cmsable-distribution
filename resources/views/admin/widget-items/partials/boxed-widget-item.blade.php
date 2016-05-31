    <? 
        $classTitle = Render::classTitle($widget);
        $modalTitle = Lang::get('ems::actions.widgets.create-widget',['widgetTitle'=>$classTitle]);
        $hideCloseButton = isset($hideCloseButton) ? $hideCloseButton : false;
    ?>
    <div class="box box-default box-solid widget-frame" data-modal-title="{{ $modalTitle }}" data-edit-url="{{ URL::route('widgets.items.edit-preview', [$widgetItem->getTypeId(), $widgetItem->getId()]) }}" data-type-id="{{ $widgetItem->getTypeId() }}" data-id="{{ $widgetItem->getId() }}" data-handle="{{ $handle }}" data-input-prefix="{{ $inputPrefix }}">
        <div class="box-header with-border drag-handle">
            <h3 class="box-title">{{ $classTitle }}</h3>
            @if(!$hideCloseButton)
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="delete"><i class="fa fa-times"></i></button>
            </div><!-- /.box-tools -->
            @endif
        </div><!-- /.box-header -->
        <div class="box-body" onclick="editWidgetItem($(this).closest('.widget-frame'))">
    {!! $widget->renderPreview($widgetItem) !!}
        </div><!-- /.box-body -->
        <input class="widget-config" type="hidden" name="{{ $inputPrefix }}[{{ $widgetItem->getId() }}]" value="{!! htmlentities(json_encode($widgetItem->getData()), ENT_QUOTES, 'UTF-8') !!}"/>
    </div><!-- /.box -->
