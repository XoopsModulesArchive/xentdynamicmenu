<?php

require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/class/xent_dm_menus.php';
require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/class/xent_dm_config.php';

function xent_dm_block_menu_list()
{
    global $xoopsDB, $block_bid, $module_tables, $HTTP_COOKIE_VARS;

    $myts = MyTextSanitizer::getInstance();

    $xentDMMenus = new XentDMMenus();

    $xentDMConfig = new XentDMConfig();

    $block = [];

    $arr = $xentDMMenus->getRootMenusList();

    $menu = '';

    $menu .= '<ul>';

    $lvlcurrent = 0;

    $lvlold = 0;

    $firstDisplayed = false;

    foreach ($arr as $key => $value) {
        // pour pas pogner le "------"

        if (0 != $key) {
            if ($xentDMMenus->displayMenu($key, $block_bid)) {
                $viewtest = 0;

                $menuobj = $xentDMMenus->getMenu($key);

                //############################LIRE LE COOKIE#############################

                $IDMENU = 'menu' . $xentDMMenus->getMenuLevel($menuobj['ID_MENU']) . $menuobj['ID_MENU'];

                if ((isset($HTTP_COOKIE_VARS[$IDMENU])) && ($HTTP_COOKIE_VARS[$IDMENU] > '')) {
                    $viewtest = $HTTP_COOKIE_VARS[$IDMENU];
                }

                //########################################################################

                if ($xentDMConfig->getActivateBlocPliable()) {
                    if (0 == $viewtest) {
                        $display = "style='display: none'";
                    } else {
                        $display = '';
                    }

                    $bloc_pliable = "onClick=\"xentdynamicmenu_blockpliable('menu" . $xentDMMenus->getMenuLevel($menuobj['ID_MENU']) . $menuobj['ID_MENU'] . "')\"";
                } else {
                    $bloc_pliable = '';

                    $display = '';
                }

                $menuIndicator = $xentDMConfig->getMenuIndicator();

                if (empty($menuobj['link'])) {
                    $menuobj['link'] = '#';
                }

                // pour le display de </div>

                $lvlold = $lvlcurrent;

                $lvlcurrent = $xentDMMenus->getMenuLevel($menuobj['ID_MENU']);

                if (0 == $menuobj['id_menu_parent']) {
                    $lvlcurrent = 0;

                    $lvlold = 0;
                }

                if ($lvlcurrent < $lvlold) {
                    $menu .= str_repeat('</div>', ($lvlold - $lvlcurrent));
                }

                if (0 == $menuobj['id_menu_parent']) {
                    // ici c'est un menu root

                    if (true === $firstDisplayed) {
                        $menu .= '</div>';

                        if (false === $xentDMConfig->getActivateBlocPliable()) {
                            $menu .= '<br>';
                        }
                    } else {
                        $firstDisplayed = true;
                    }

                    // il faut regarder si le menu a des sous-menu ou non

                    if ($xentDMMenus->hasSubCats($menuobj['ID_MENU'])) {
                        $menu .= '<div>' .
                        str_repeat('&nbsp;', $xentDMMenus->getMenuLevel($menuobj['ID_MENU'])) . "$menuIndicator<a href='" . $menuobj['link'] . "' $bloc_pliable>" . $myts->displayTarea($menuobj['content']) . "</a>
 									</div>
 									<div id='menu" . $xentDMMenus->getMenuLevel($menuobj['ID_MENU']) . $menuobj['ID_MENU'] . "' $display>
 								";
                    } else {
                        $menu .= "<div>
 										$menuIndicator<a href='" . $menuobj['link'] . "'>" . $myts->displayTarea($menuobj['content']) . '</a>
 									';
                    }
                } else {
                    // ici c'est un sous-menu

                    // on doit checker s'il a des sous-menu ou non

                    if ($xentDMMenus->hasSubCats($menuobj['ID_MENU'])) {
                        $menu .= '	<div>' .
                        str_repeat('&nbsp;', $xentDMMenus->getMenuLevel($menuobj['ID_MENU'])) . "$menuIndicator<a href='" . $menuobj['link'] . "' class='sousmenu' $bloc_pliable>" . $myts->displayTarea($menuobj['content']) . "</a>
 										</div>
 										<div id='menu" . $xentDMMenus->getMenuLevel($menuobj['ID_MENU']) . $menuobj['ID_MENU'] . "' $display>
 								";
                    } else {
                        $menu .= str_repeat('&nbsp;', $xentDMMenus->getMenuLevel($menuobj['ID_MENU'])) . "$menuIndicator<a href='" . $menuobj['link'] . "' class='sousmenu'>" . $myts->displayTarea($menuobj['content']) . '</a><br>';
                    }
                }
            }
        }
    }

    $menu .= '</div></ul>';

    $block['menu'] = $menu;

    return $block;
}
