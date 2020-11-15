<?php

use Cmsable\Widgets\Contracts\Area;
use Cmsable\Widgets\Contracts\Registry as WidgetRegistry;
use Cmsable\Widgets\Contracts\Area as WidgetArea;
use Cmsable\Widgets\Contracts\WidgetItem;

/** @var WidgetRegistry $widgets */
$widgets = App::make(WidgetRegistry::class);

/** @var WidgetArea $area */
/** @var string $widgetConfigName */
/** @var string $handle */
/** @var string $layoutName */
/** @var string $editMode */
/** @var WidgetItem|null $widgetItem */
/** @var Area|null $area */

?>
@if($widgetItem)
                <ul class="list-unstyled widget-list" id="{{ $handle }}" data-handle="{{ $handle }}" data-modal-title="{{ Lang::get('ems::actions.widgets.select-item') }}" data-select-url="{{ URL::route('widget-items.index') }}" data-show-url="{{ URL::route('widget-items.show', ['%id%']) }}" data-area-id="{{ $area ? $area->getId() : '' }}" data-layout-name="{{ $layoutName }}" data-widget-config-name="{{ $widgetConfigName }}">
                    <?php
                        $widget = $widgets->get($widgetItem->getTypeId());
                        $classTitle = Render::classTitle($widget);
                        $inputPrefix = $widgetConfigName;
                        $hideCloseButton = true;
                        $draggable = false;
                        $editCall = "changeWidgetItem(document.getElementById('$handle'))";
                    ?>

                    <li class="widget-item">
                    @include('widget-items.partials.boxed-widget-item')
                    </li>

                </ul>
                <input type="hidden" id="{{ $handle }}_item_id" name="{{ $layoutName }}" value="{{ $field->getValue() }}"/>
    <script>
        document.getElementById('inline-modal').addEventListener('widgetSelected', function (event) {
            if (event.detail.input_prefix !== '{{ $inputPrefix }}') {
                return;
            }
            $('#{{ $handle }}_item_id').val(event.detail.id);
            $('#{{ $handle }} li.widget-item')[0].innerHTML = event.detail.html;
            $('#{{ $handle }} li.widget-item').first().click(function () {
                changeWidgetItem(document.getElementById('{{ $handle }}'));
            });
        });
    </script>
@endif