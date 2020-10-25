<?php
// ------------------------------------------------------------------------- //

//                  Module xentDynamicMenu pour Xoops 2.0.7                     //

//                              Version:  1.0                                //

// ------------------------------------------------------------------------- //

// Author: Milhouse                                        				     //

// Purpose:                           				     //

// email: hotkart@hotmail.com                                                //

// URLs:                      												 //

//---------------------------------------------------------------------------//

global $xoopsModuleConfig;
$modversion['name'] = _MI_XENT_DM_NAME;
$modversion['version'] = '1.0.1';
$modversion['description'] = _MI_XENT_DM_DESC;
$modversion['credits'] = 'M4D3L, marcan, solo71 (for multimenu) and i might forget some people';
$modversion['author'] = 'Ecrit pour Xoops2<br>par Alexandre Parent (Milhouse)';
$modversion['license'] = '';
$modversion['official'] = 1;
$modversion['image'] = 'images/xent_dynamicmenu_logo.png';
$modversion['help'] = '';
$modversion['dirname'] = 'xentdynamicmenu';

// MYSQL FILE
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['onInstall'] = 'include/install.php';
$modversion['onUninstall'] = 'include/uninstall.php';

// Tables created by sql file
//If you hack this modules, dont change the order of the table.
//All
#$modversion['tables'][0] = "xent_dm_blocks";
$modversion['tables'][0] = 'xent_dm_menus';
$modversion['tables'][1] = 'xent_dm_link_menu_block';

#$modversion['templates'][1]['file'] = 'xent_dm_block_menu.html';
#$modversion['templates'][1]['description'] = '';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['hasMain'] = 0;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['blocks'][1]['file'] = 'xent_dm_block_menu_list.php';
$modversion['blocks'][1]['name'] = 'DynamicMenu List';
$modversion['blocks'][1]['description'] = 'DynamicMenu List Description';
$modversion['blocks'][1]['show_func'] = 'xent_dm_block_menu_list';
$modversion['blocks'][1]['template'] = 'xent_dm_block_menu_list.html';

$modversion['blocks'][2]['file'] = 'xent_dm_block_menu_single.php';
$modversion['blocks'][2]['name'] = 'DynamicMenu Single';
$modversion['blocks'][2]['description'] = 'DynamicMenu Single Description';
$modversion['blocks'][2]['show_func'] = 'xent_dm_block_menu_single';
$modversion['blocks'][2]['template'] = 'xent_dm_block_menu_single.html';

$modversion['blocks'][3]['file'] = 'xent_dm_block_menu_combo.php';
$modversion['blocks'][3]['name'] = 'DynamicMenu Combo';
$modversion['blocks'][3]['description'] = 'DynamicMenu Combo Description';
$modversion['blocks'][3]['show_func'] = 'xent_dm_block_menu_combo';
$modversion['blocks'][3]['template'] = 'xent_dm_block_menu_combo.html';

//Configs
$modversion['config'][1]['name'] = 'activate_bloc_pliable';
$modversion['config'][1]['title'] = '_MI_XENT_DM_CONFIG_ACTIVATEBP';
$modversion['config'][1]['description'] = '_MI_XENT_DM_CONFIG_ACTIVATEBPDESC';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = '0';
$modversion['config'][1]['default'] = '';

$modversion['config'][2]['name'] = 'menu_indicator';
$modversion['config'][2]['title'] = '_MI_XENT_DM_CONFIG_MENUIND';
$modversion['config'][2]['description'] = '_MI_XENT_DM_CONFIG_MENUINDDESC';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['default'] = '»&nbsp;';
