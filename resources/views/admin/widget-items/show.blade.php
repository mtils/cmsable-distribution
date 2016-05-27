    @if($framed)
    <div class="box box-default box-solid" data-type-id="{{ $widgetItem->getTypeId() }}" data-id="{{ $widgetItem->getId() }}">
        <div class="box-header with-border">
            <h3 class="box-title">{{ Render::classTitle($widget) }}</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
    @endif
    {!! $widget->renderPreview($widgetItem) !!}
    @if($framed)
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    @endif
