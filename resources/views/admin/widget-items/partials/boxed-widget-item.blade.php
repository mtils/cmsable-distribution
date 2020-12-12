    <?php
        $classTitle = Render::classTitle($widget);
        $modalTitle = Lang::get('ems::actions.widgets.create-widget',['widgetTitle'=>$classTitle]);
        $hideCloseButton = isset($hideCloseButton) ? $hideCloseButton : false;
        $draggable = isset($draggable) ? $draggable : true;
        $editCall = isset($editCall) ? $editCall : "editWidgetItem($(this).closest('.widget-frame'))";
        $iframeId = "frame-$handle-" . $widgetItem->getId();
        $showUrl = url()->route('widget-items.show', [$widgetItem->getId()]);
        $previewMode = isset($previewMode) ? $previewMode : false;
    ?>
    <div class="box box-default box-solid widget-frame" data-modal-title="{{ $modalTitle }}" data-edit-url="{{ URL::route('widgets.items.edit-preview', [$widgetItem->getTypeId(), $widgetItem->getId()]) }}" data-type-id="{{ $widgetItem->getTypeId() }}" data-id="{{ $widgetItem->getId() }}" data-handle="{{ $handle }}" data-input-prefix="{{ $inputPrefix }}">
        <div class="box-header with-border {{ $draggable ? 'drag-handle' : '' }}">
            <h3 class="box-title">{{ $classTitle }}</h3>
            @if(!$hideCloseButton)
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="delete"><i class="fa fa-times"></i></button>
            </div><!-- /.box-tools -->
            @endif
        </div><!-- /.box-header -->
        <div class="box-body" onclick="{{ $editCall }}">
            @if(!$previewMode)
                <div class="box-blocker"><i class="fa fa-edit edit-icon"></i></div>
                <iframe src="{{ $showUrl }}" class="widget-window" id="{{ $iframeId }}" scrolling="no"></iframe>
            @else
                {!! $widget->renderPreview($widgetItem) !!}
            @endif

            <script>
                //var iframe = document.getElementById('{{ $iframeId }}');
                var widgetIframe = document.getElementById('{{ $iframeId }}');
                if (widgetIframe) {
                    widgetIframe.onload = function () {
                        var currentIframe = document.getElementById('{{ $iframeId }}');
                        var iframeContentHeight = currentIframe.contentWindow.document.body.scrollHeight;
                        var iframeScale = currentIframe.getBoundingClientRect().height / currentIframe.offsetHeight

                        var holderHeight = Math.round(iframeContentHeight * iframeScale);

                        console.log('Transform is ' + iframeScale);
                        console.log('Resizing iframe "{{ $iframeId }}" to ' + iframeContentHeight + 'px');
                        currentIframe.style.height = iframeContentHeight + 'px';
                        currentIframe.parentElement.style.height = holderHeight + 'px';

                        //iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
                    };
                }
            </script>
        </div><!-- /.box-body -->
        <input class="widget-config" type="hidden" name="{{ $inputPrefix }}[{{ $widgetItem->getId() }}]" value="{!! htmlentities(json_encode($widgetItem->getData()), ENT_QUOTES, 'UTF-8') !!}"/>
    </div><!-- /.box -->
