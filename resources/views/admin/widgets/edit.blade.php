<?php

use Cmsable\Widgets\Contracts\Registry as WidgetRegistry;
use Cmsable\Widgets\Contracts\Area as WidgetArea;
use Cmsable\Widgets\FormFields\WidgetListField;

/** @var WidgetRegistry $widgets */
$widgets = App::make(WidgetRegistry::class);

/** @var WidgetArea $area */
/** @var string $widgetConfigName */
/** @var string $handle */
/** @var string $layoutName */
/** @var string $editMode */

?>

                <ul class="list-unstyled widget-list" id="{{ $handle }}" data-handle="{{ $handle }}" data-modal-title="{{ Lang::get('ems::actions.widgets.add') }}" data-select-url="{{ URL::route('widgets.index') }}" data-layout-name="{{ $layoutName }}" data-widget-config-name="{{ $widgetConfigName }}">




                    @for($row = 0; $row < $area->rowCount(); $row++)

                    <?php
                        /** @var int $row $widgetItem */
                        $widgetItem = $area->getItem($row, 0);
                        $widget = $widgets->get($widgetItem->getTypeId());
                        $classTitle = Render::classTitle($widget);
                        $addButtonText = Lang::get('ems::base.create-object',['class'=>$classTitle]);
                        $inputPrefix = $widgetConfigName;
                        $hideCloseButton = $editMode !== WidgetListField::MODE_MANAGE;
                        $draggable = $editMode === WidgetListField::MODE_MANAGE;
                    ?>

                    <li class="widget-item">
                    @include('widget-items.partials.boxed-widget-item')
                    </li>
                    @endfor

                </ul>
                <input type="hidden" id="{{ $handle }}_layout" name="{{ $layoutName }}" value="{{ $field->getValue() }}"/>
                @if($editMode === WidgetListField::MODE_MANAGE)
                <a class="btn btn-primary btn-block" onclick="selectWidget(document.getElementById('{{ $handle }}'))"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ Lang::get('ems::actions.widgets.add') }}...</a>
                @endif
