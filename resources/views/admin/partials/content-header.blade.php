    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @guessTrans(Menu::current()->menu_title)
                <small>@guessTrans(Menu::current()->menu_title)</small>
            </h1>
            <ol class="breadcrumb">
            <? $breadcrumbs = Breadcrumbs::get(); $last = count($breadcrumbs)-1 ?>
            @foreach($breadcrumbs as $idx => $crumb)
                @if($idx==$last)
                <li class="active"><i class="fa {{ $crumb->icon }}"></i> @guessTrans($crumb->menu_title)</li>
                @else
                <li><a href="{{ Url::to($crumb) }}"><i class="fa {{ $crumb->icon }}"></i> @guessTrans($crumb->menu_title)</a></li>
                @endif
            @endforeach
            </ol>
        </section>