    @if(isset($allowCreate) && $allowCreate)
    <? 
        if (isset($modelLangKey)) {
            $classTitle = Lang::choice($modelLangKey,1);
            $buttonText = Lang::get('ems::action.create-model', ['model'=>$classTitle]);
        }
        elseif (isset($createButtonText)) {
            $buttonText = $createButtonText;
        }
        $nextRouteName = str_replace('index','create', Route::currentRouteName());
    ?>
    <div class="btn btn-success btn-file" style="width: {{ (strlen($buttonText) < 10) ? '135' : strlen($buttonText)*10 }}px;" onclick="window.location='{{ URL::route($nextRouteName) }}'">
        <i class="fa fa-edit"></i>
        {{ $buttonText }}
    </div><br/><br/>
    @endif