                <ul class="list-unstyled" id="{{ $handle }}">
                    
                </ul>
                <a class="btn btn-primary btn-block" onclick="selectWidget('{{ URL::route('widgets.index') . '?handle=' . $handle }}','{{ Lang::get('ems::actions.widgets.add') }}')"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ Lang::get('ems::actions.widgets.add') }}...</a>
