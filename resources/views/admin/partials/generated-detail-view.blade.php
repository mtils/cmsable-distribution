<? $keys = isset($keys) ? $keys : Scaffold::keys(get_class($model), 'details') ?>
<? $keys = $keys ? $keys : array_keys($model->getAttributes()); ?>
<? $titles = App::make('Versatile\Introspection\Contracts\TitleIntrospector'); ?>
<? $title = isset($title) ? $title : Scaffold::shortName($model) ?>
<? $split = isset($split) ? $split : false ?>
<?


?>
<div class="box">
    @if($title)
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
    </div>
    @endif
    <div class="box-body">
        <table class="table table-bordered">
        @if($split)
        <? $i = 0 ?>
        @foreach($keys as $key)
            @if($i%2===0)
            <tr>
            @endif
                <th>{{ $titles->keyTitle($model, $key) }}</th>
                <td>{{ $model->getAttribute($key) }}</td>
            @if($i%2!==0)
            </tr>
            @endif
            <? $i++ ?>
        @endforeach
        @else
        @foreach($keys as $key)
            <tr>
                <th>{{ $titles->keyTitle($model, $key) }}</th>
                <td>{{ $model->getAttribute($key) }}</td>
            </tr>
        @endforeach
        @endif
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->