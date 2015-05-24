<? if(Menu::inSubPath() && isset($title)):
    $menu_title = isset($menu_title) ? $menu_title : $title;
    Menu::setCurrent($menu_title, $title);
    endif;
?>