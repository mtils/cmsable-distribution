    @if(isset($allowCreate) && $allowCreate)
    <? $classTitle = Lang::choice($modelLangKey,1);
        $nextRouteName = str_replace('index','create', Route::currentRouteName());
    ?>
    <div class="btn btn-success btn-file" style="width: {{ (strlen($classTitle) < 10) ? '135' : strlen($classTitle)*10 }}px;" onclick="window.location='{{ URL::route($nextRouteName) }}'">
        <i class="fa fa-edit"></i>
        Neue {{ $classTitle }}
    </div><br/><br/>
    @endif