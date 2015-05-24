        <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            @foreach(Menu::all('') as $level1)
                <? $level1Children = $level1->filteredChildren(); ?>
                @if(count($level1Children))
                    <li class="treeview @if(Menu::isCurrent($level1) || Menu::isSection($level1) ) active @endif">
                        <a href="#" title="@guessTrans($level1->title)">
                            <i class="fa {{ $level1->icon }}"></i> <span>@guessTrans($level1->menu_title)</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @foreach($level1Children as $level2)
                            <li class="@if(Menu::isCurrent($level2)) active @endif">
                                <a href="{{ URL::to($level2) }}" title="@guessTrans($level2->title)">
                                    <i class="fa fa-circle-o"></i> @guessTrans($level2->menu_title)
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="@if(Menu::isCurrent($level1)) active @endif">
                        <a href="{{ URL::to($level1) }}" title="@guessTrans($level1->title)">
                            <i class="fa {{ $level1->icon }}"></i> @guessTrans($level1->menu_title)
                        </a>
                    </li>
                @endif
            @endforeach
         </ul> 