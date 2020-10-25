<?php

    require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/class/xent_dm_menus.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    function xent_dm_block_menu_combo()
    {
        global $xoopsDB, $block_bid, $module_tables, $HTTP_COOKIE_VARS;

        $myts = MyTextSanitizer::getInstance();

        $xentDMMenus = new XentDMMenus();

        $arr = $xentDMMenus->getRootMenusList();

        $block = [];

        $array_menu = [];

        foreach ($arr as $key => $value) {
            if ($xentDMMenus->displayMenu($key, $block_bid)) {
                $menu = $xentDMMenus->getMenu($key);

                $array_menu['menu_combo'] = $myts->displayTarea($value);

                $array_menu['menu_combolink'] = $menu['link'];

                $block['menu'][] = $array_menu;
            }
        }

        return $block;
    }
