                <ul class="list-unstyled widget-list" id="{{ $handle }}" data-handle="{{ $handle }}" data-modal-title="{{ Lang::get('ems::actions.widgets.add') }}" data-select-url="{{ URL::route('widgets.index') }}" data-layout-name="{{ $layoutName }}" data-widget-config-name="{{ $widgetConfigName }}">

                    <? $widgets = App::make('Cmsable\Widgets\Contracts\Registry'); ?>

                    @for($row = 0; $row < $area->rowCount(); $row++)

                    <?
                        $widgetItem = $area->getItem($row, 0);
                        $widget = $widgets->get($widgetItem->getTypeId());
                        $classTitle = Render::classTitle($widget);
                        $addButtonText = Lang::get('ems::base.create-object',['class'=>$classTitle]);
                        $inputPrefix = $widgetConfigName;
                    ?>
                    <li class="widget-item">
                    @include('widget-items.partials.boxed-widget-item')
                    </li>
                    @endfor

                </ul>
                <input type="hidden" id="{{ $handle }}_layout" name="{{ $layoutName }}" value="{{ $field->getValue() }}"/>
                <a class="btn btn-primary btn-block" onclick="selectWidget(document.getElementById('{{ $handle }}'))"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ Lang::get('ems::actions.widgets.add') }}...</a>
