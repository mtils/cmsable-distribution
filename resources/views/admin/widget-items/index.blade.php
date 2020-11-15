<?php

use Cmsable\Widgets\Contracts\WidgetItem;
use Cmsable\Widgets\Contracts\Registry as WidgetRegistry;

/** @var WidgetItem[] $items */
/** @var WidgetRegistry $widgetRegistry */

?>
<ul class="list-unstyled widget-select">
    @foreach($items as $widgetItem)
       <?php
        /** @var WidgetItem $widgetItem */
        $widget = $widgetRegistry->get($widgetItem->getTypeId());
        $classTitle = Render::classTitle($widget);
        $modalTitle = Lang::get('ems::actions.widgets.create-widget',['widgetTitle'=>$classTitle]);
        $addButtonText = Lang::get('ems::base.create-object',['class'=>$classTitle]);
        $handleParam = isset($handle) ? "?handle=$handle" : '';
        $createUrl = URL::route('widgets.items.create', [$widget->getTypeId()]) . "?handle=$handle&input_prefix=$inputPrefix";
        $draggable = false;
        $editCall = "selectWidgetItem($(this).closest('.widget-frame'))";

        ?>
        <li>
            <? $hideCloseButton = true ?>
            @include('widget-items.partials.boxed-widget-item')
        </li>
    @endforeach
</ul>