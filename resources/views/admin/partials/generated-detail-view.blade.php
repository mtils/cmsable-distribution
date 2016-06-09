<? $keys = Scaffold::keys(get_class($model), 'details') ?>
<? $keys = $keys ? $keys : array_keys($model->getAttributes()); ?>
<? $titles = App::make('Versatile\Introspection\Contracts\TitleIntrospector'); ?>
<div class="box">
    @if($title = Scaffold::shortName($model))
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
    </div>
    @endif
    <div class="box-body">
        <table class="table table-bordered">
        @foreach($keys as $key)
            <tr>
                <th>{{ $titles->keyTitle($model, $key) }}</th>
                <td>{{ $model->getAttribute($key) }}</td>
            </tr>
        @endforeach
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->