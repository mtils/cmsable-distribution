    <? use Illuminate\Pagination\AbstractPaginator;$filterActions = isset($filterActions) ? $filterActions : false; ?>
    <? $context = isset($context) ? $context : 'default' ?>
    <? $fileInterface = 'FileDB\Model\FileInterface' ?>

    <table class="table table-bordered table-striped {{ $collection->cssClasses }}">
        <tr>
            @foreach($collection->columns as $col)
            <th class=""><a href="{{ $col->sortHref }}">{{ $col->title }}<i class="fa fa-fw {{ $col->sortOrder ? 'fa-sort-'.$col->sortOrder : '' }} pull-right"></i></a></th>
            @endforeach
            <th></th>
        </tr>
    @foreach($collection as $row)
        <tr>
            @foreach($collection->columns as $col)
            <? $value = $col->value ?>
            @if($value instanceof FileDB\Model\FileInterface)
            <td><img src="{{ $value->getUrl() }}" style="width: 100px; height: auto;"/></td>
            @else
            <td>{{ $value }}</td>
            @endif
            @endforeach
            <td>
            <? $actions = $filterActions ? Actions::forItem($row)->filtered($filterActions) : Actions::forItem($row) ?>
            @foreach($actions as $action)
            <a href="{{ $action->url }}" {!! $action->data !!} onclick="{{ $action->onClick }}" class="btn btn-default btn-sm {{ $action->contexts }}" title="{{ $action->title }}"><i class="fa {{ $action->icon }}"></i></a>
            @endforeach
        </tr>
    @endforeach
    </table>
    {{-- Pagination --}}
    <?
        $paginator = $collection->getSrc();
//        if ($paginator instanceof AbstractPaginator) {
            foreach (Request::all() as $key=>$value) {
                if ($key != 'page' && is_scalar($value) && $value !== '') {
                    $paginator->appends($key, $value);
                }
            }
//        }
    ?>
    {!! $paginator->render() !!}
