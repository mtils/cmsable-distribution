            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <!-- div class="user-panel">
                        <div class="pull-left image">
                            <img src="{{ URL::asset('themes/admin/img/avatar3.png') }}" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>Hello, Jane</p>

                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div -->
                    <!-- search form -->
                    <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Suchen..."/>
                            <span class="input-group-btn">
                                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">

                    @foreach(Menu::all() as $level1)
                    @if(count($level1->filteredChildren()))
                    <li class="treeview @if(Menu::isCurrent($level1) || Menu::isSection($level1) )active @endif">
                        <a href="#" title="{{ $level1->title }}">
                            <i class="fa {{ $level1->icon }}"></i> <span>{{ $level1->menu_title }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @foreach($level1->filteredChildren() as $level2)
                            <li class="@if(Menu::isCurrent($level2)) active @endif">
                                <a href="{{ URL::to($level2) }}" title="{{ $level2->title }}">
                                    <i class="fa fa-angle-double-right"></i> <span>{{ $level2->menu_title }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @else
                    <li class="@if(Menu::isCurrent($level1)) active @endif">
                        <a href="{{ URL::to($level1) }}" title="{{ $level1->title }}">
                            <i class="fa {{ $level1->icon }}"></i> <span>{{ $level1->menu_title }}</span>
                        </a>
                    </li>
                    @endif

                    @endforeach
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>