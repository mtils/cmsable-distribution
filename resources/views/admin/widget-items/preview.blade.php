<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Widget preview</title>
    <link href="{{ config('cmsable.cms-editor-css') }}" rel="stylesheet">

</head>
<body>
    <?php

    use Cmsable\Widgets\Contracts\WidgetItem;
    use Cmsable\Widgets\Contracts\Widget;

     /** @var WidgetItem $widgetItem */
     /** @var Widget $widget */

    ?>
    @if($widgetItem->getData())
        {!! $widget->render($widgetItem) !!}
    @else
        {!! $widget->renderPreview($widgetItem) !!}
    @endif

</body>
