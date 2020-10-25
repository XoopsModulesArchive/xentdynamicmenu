<?php

    require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/class/xent_dm_menus.php';
    require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/class/xent_dm_blocks.php';
    require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/class/xent_dm_config.php';

    function xent_dm_block_menu_single()
    {
        global $block_bid, $xoopsDB;

        $myts = MyTextSanitizer::getInstance();

        $xentDMMenus = new XentDMMenus();

        $xentDMConfig = new XentDMConfig();

        $xentDMBlocks = new XentDMBlocks();

        $theblock = $xentDMBlocks->getBlock($block_bid);

        $block = [];

        $result = $xentDMMenus->getMenusBlocks($block_bid);

        $menu = '<ul>';

        while (false !== ($menus = $xoopsDB->fetchArray($result))) {
            $menuToDis = $xentDMMenus->getMenu($menus['ID_MENU']);

            $menu .= '<div>' . $xentDMConfig->getMenuIndicator() . "<a href='" . $menuToDis['link'] . "'>" . $myts->displayTarea($menuToDis['content'], 1) . '</a><div>';
        }

        $menu .= '</ul>';

        if (empty($theblock['content'])) {
            $block['menu'] = $menu;
        } else {
            $block['content'] = $myts->displayTarea($theblock['content']);
        }

        return $block;
    }
