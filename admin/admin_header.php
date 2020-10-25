<?php

    //echo "<link rel='stylesheet' type='text/css' media='all' href='include/admin.css'>";

    require __DIR__ . '/admin_buttons.php';
    include '../../../mainfile.php';
    require dirname(__DIR__, 3) . '/include/cp_header.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once dirname(__DIR__) . '/class/xent_dm_blocks.php';
    require_once dirname(__DIR__) . '/class/xent_dm_menus.php';
    require_once dirname(__DIR__) . '/class/xent_dm_config.php';
    require_once dirname(__DIR__) . '/class/xent_dm_templates.php';
    require_once dirname(__DIR__) . '/include/functions.php';
    require_once dirname(__DIR__) . '/include/functions_admin.php';

    global $xoopsModule;

    $versioninfo = $moduleHandler->get($xoopsModule->getVar('mid'));
    $module_tables = $versioninfo->getInfo('tables');

    if (is_object($xoopsUser)) {
        $xoopsModule = XoopsModule::getByDirname('xentdynamicmenu');

        if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
            redirect_header(XOOPS_URL . '/', 1, _NOPERM);

            exit();
        }
    } else {
        redirect_header(XOOPS_URL . '/', 1, _NOPERM);

        exit();
    }

    $module_id = $xoopsModule->getVar('mid');
    $oAdminButton = new AdminButtons();
    $oAdminButton->AddTitle(_AM_XENT_DM_ADMINMENUTITLE);

    //$oAdminButton->AddButton(_AM_XENT_DM_INDEX, "index.php", 'index');

    $oAdminButton->AddButton(_AM_XENT_DM_ADMINBLOCKS, 'adminblocks.php', 'adminblocks');
    $oAdminButton->AddButton(_AM_XENT_DM_ADMINMENUS, 'adminmenus.php', 'adminmenus');
    #$oAdminButton->AddButton(_AM_XENT_DM_ADMINTOS, "admintemplatesoptions.php", "admintemplatesoptions");

    $oAdminButton->AddTopLink(_AM_XENT_DM_PREFERENCES, XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $module_id);
    $oAdminButton->addTopLink(_AM_XENT_DM_UPDATEMODULE, XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=xentdynamicmenu');

    $myts = MyTextSanitizer::getInstance();
