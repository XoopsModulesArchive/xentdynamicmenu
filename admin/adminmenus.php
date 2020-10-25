<?php

    require __DIR__ . '/admin_header.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

    foreach ($_REQUEST as $a => $b) {
        $$a = $b;
    }

    $eh = new ErrorHandler();
    xoops_cp_header();
    echo $oAdminButton->renderButtons('adminmenus');

    OpenTable();
    echo "<div class='adminHeader'>" . _AM_XENT_DM_ADMINMENUSTITLE . '</div><br>';

    function MENUSShowMenus()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMMenus = new XentDMMenus();

        $xentDMBlocks = new XentDMBlocks();

        $myts = MyTextSanitizer::getInstance();

        echo "<div align='right' class='adminActionMenu'><a href='adminmenus.php?op=MENUSAddMenu'>" . _AM_XENT_DM_ADDMENU . '</a></div>';

        $resultBlocks = $xentDMBlocks->getAllBlocks();

        while (false !== ($blocks = $xoopsDB->fetchArray($resultBlocks))) {
            echo "
	        	<table width='100%' class='outer' cellspacing='1'>
	            	<tr>
	                    <th width='75%'>" . $blocks['name'] . '</th>
	                    <th>Priority</th>
        				<th>' . _AM_XENT_DM_OPTIONS . '</th>
	                </tr>';

            $resultMenus = $xentDMMenus->getMenusBlocks($blocks['bid']);

            while (false !== ($menus = $xoopsDB->fetchArray($resultMenus))) {
                $menu = $xentDMMenus->getMenu($menus['ID_MENU']);

                $xentDMMenus->setIdMenu($menu['ID_MENU']);

                $xentDMMenus->setName($menu['name']);

                $xentDMMenus->setLink($menu['link']);

                $xentDMMenus->setPriority($menu['priority']);

                $xentDMMenus->setIdMenuParent($menu['id_menu_parent']);

                echo "
	            	<tr>
	                    <td class='even'>" . $myts->displayTarea($xentDMMenus->getName()) . "</td>
	                    <td class='even'>" . $myts->displayTarea($xentDMMenus->getPriority()) . "</td>
	    				<td class='even'><a href='adminmenus.php?op=MENUSEditMenu&id=" . $xentDMMenus->getIdMenu() . "'>" . _AM_XENT_DM_EDIT . '</a></td>
	                </tr>
	            ';
            }

            echo '</table><br>';
        }

        $arr = $xentDMMenus->getUnassignedMenus();

        echo "
        	<table width='100%' class='outer' cellspacing='1'>
            	<tr>
                    <th width='75%'>Unassigned Menus</th>
                    <th>" . _AM_XENT_DM_OPTIONS . '</th>
                </tr>';

        foreach ($arr as $value) {
            $menu = $xentDMMenus->getMenu($value);

            $xentDMMenus->setIdMenu($menu['ID_MENU']);

            $xentDMMenus->setName($menu['name']);

            $xentDMMenus->setLink($menu['link']);

            $xentDMMenus->setPriority($menu['priority']);

            $xentDMMenus->setIdMenuParent($menu['id_menu_parent']);

            echo "
            	<tr>
                    <td class='even'>" . $myts->displayTarea($xentDMMenus->getName()) . "</td>
                    <td class='even'><a href='adminmenus.php?op=MENUSEditMenu&id=" . $xentDMMenus->getIdMenu() . "'>" . _AM_XENT_DM_EDIT . '</a></td>
                </tr>
            ';
        }

        echo '</table>';
    }

    function MENUSAddMenu()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMMenus = new XentDMMenus();

        $xentDMBlocks = new XentDMBlocks();

        $myts = MyTextSanitizer::getInstance();

        $xentDMMenus->setIdMenu(0);

        if (!empty($_GET['name'])) {
            $xentDMMenus->setName($_GET['name']);
        } else {
            $xentDMMenus->setName('');
        }

        if (!empty($_GET['content'])) {
            $xentDMMenus->setContent($_GET['content']);
        } else {
            $xentDMMenus->setContent('[fr][/fr][en][/en]');
        }

        if (!empty($_GET['link'])) {
            $xentDMMenus->setLink($_GET['link']);
        } else {
            $xentDMMenus->setLink('http://');
        }

        if (!empty($_GET['priority'])) {
            $xentDMMenus->setPriority($_GET['priority']);
        } else {
            $xentDMMenus->setPriority(0);
        }

        if (!empty($_GET['id_menu_parent'])) {
            $xentDMMenus->setIdMenuParent($_GET['id_menu_parent']);
        } else {
            $xentDMMenus->setIdMenuParent(0);
        }

        if (!empty($_GET['id_block'])) {
            $xentDMMenus->setIdBlocks($_GET['id_block']);
        } else {
            $xentDMMenus->setIdBlocks($xentDMMenus->getMenusBlocksArray($xentDMMenus->getIdMenu()));
        }

        $sform = new XoopsThemeForm(_AM_XENT_DM_ADDMENU, 'addmenu', xoops_getenv('PHP_SELF'));

        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormText(_AM_XENT_DM_NAME, 'name', 50, 255, $xentDMMenus->getName()));

        $sform->addElement(new XoopsFormDhtmlTextArea(_AM_XENT_DM_CONTENT, 'content', $xentDMMenus->getContent()));

        $sform->addElement(new XoopsFormText(_AM_XENT_DM_LINK, 'link', 50, 255, $xentDMMenus->getLink()));

        $sform->addElement(new XoopsFormText(_AM_XENT_DM_PRIORITY, 'priority', 3, 5, $xentDMMenus->getPriority()));

        $sform->addElement(makeSelect(_AM_XENT_DM_MENUPARENT, 'menuparent', $xentDMMenus->getIdMenuParent(), $xentDMMenus->getRootMenusList()));

        $sform->addElement(makeSelect(_AM_XENT_DM_DISPLAYINBLOCK, 'blocks', $xentDMMenus->getIdBlocks(), $xentDMBlocks->getAllBlocksArray(), 5, 0, true));

        $save_button = new XoopsFormButton('', 'add', _AM_XENT_ADD, 'submit');

        $save_button->setExtra("onmouseover='document.addmenu.op.value=\"MENUSSaveAddMenu\"'");

        $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

        $cancel_button->setExtra("onmouseover='document.addmenu.op.value=\"MENUSShowMenus\"'");

        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement($save_button);

        $button_tray->addElement($cancel_button);

        $sform->addElement($button_tray);

        $sform->addElement(new XoopsFormHidden('op', ''));

        $sform->display();
    }

    function MENUSSaveAddMenu($name, $content, $link, $priority, $id_menu_parent, $id_blocks)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMMenus = new XentDMMenus();

        $xentDMMenus->setName(str_replace("'", '’', $name));

        $xentDMMenus->setContent(str_replace("'", '’', $content));

        $xentDMMenus->setLink($link);

        $xentDMMenus->setPriority($priority);

        $xentDMMenus->setIdMenuParent($id_menu_parent);

        $xentDMMenus->setIdBlocks($id_blocks);

        $xentDMMenus->add();
    }

    function MENUSEditMenu()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMMenus = new XentDMMenus();

        $xentDMBlocks = new XentDMBlocks();

        $myts = MyTextSanitizer::getInstance();

        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            if (!empty($_POST['id'])) {
                $id = $_POST['id'];
            } else {
                $id = 0;
            }
        }

        if (0 != $id) {
            $menu = $xentDMMenus->getMenu($id);

            if (!empty($menu['ID_MENU'])) {
                $xentDMMenus->setIdMenu($menu['ID_MENU']);

                $xentDMMenus->setName($menu['name']);

                $xentDMMenus->setContent($menu['content']);

                $xentDMMenus->setLink($menu['link']);

                $xentDMMenus->setPriority($menu['priority']);

                $xentDMMenus->setIdMenuParent($menu['id_menu_parent']);

                $xentDMMenus->setIdBlocks($xentDMMenus->getMenusBlocksArray($xentDMMenus->getIdMenu()));

                $sform = new XoopsThemeForm(_AM_XENT_DM_EDIT, 'editmenu', xoops_getenv('PHP_SELF'));

                $sform->setExtra('enctype="multipart/form-data"');

                $sform->addElement(new XoopsFormText(_AM_XENT_DM_NAME, 'name', 50, 255, $myts->displayTarea($menu['name'])));

                $sform->addElement(new XoopsFormDhtmlTextArea(_AM_XENT_DM_CONTENT, 'content', $xentDMMenus->getContent()));

                $sform->addElement(new XoopsFormText(_AM_XENT_DM_LINK, 'link', 50, 255, $xentDMMenus->getLink()));

                $sform->addElement(new XoopsFormText(_AM_XENT_DM_PRIORITY, 'priority', 3, 5, $xentDMMenus->getPriority()));

                $sform->addElement(makeSelect(_AM_XENT_DM_MENUPARENT, 'menuparent', $xentDMMenus->getIdMenuParent(), $xentDMMenus->getRootMenusList()));

                $chk = new XoopsFormCheckBox('', 'subcatsfollow', 1);

                $chk->addOption(1, _AM_XENT_DM_SUBCATSFOLLOW);

                $sform->addElement($chk);

                $sform->addElement(makeSelect(_AM_XENT_DM_DISPLAYINBLOCK, 'blocks', $xentDMMenus->getIdBlocks(), $xentDMBlocks->getAllBlocksArray(), 5, 0, true));

                $save_button = new XoopsFormButton('', 'add', _AM_XENT_MODIFY, 'submit');

                $save_button->setExtra("onmouseover='document.editmenu.op.value=\"MENUSSaveEditMenu\"'");

                $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_DELETE, 'submit');

                $cancel_button->setExtra("onmouseover='document.editmenu.op.value=\"MENUSAreYouSureToDeleteMenu\"'");

                $button_tray = new XoopsFormElementTray('', '');

                $button_tray->addElement($save_button);

                $button_tray->addElement($cancel_button);

                $sform->addElement($button_tray);

                $sform->addElement(new XoopsFormHidden('id', $xentDMMenus->getIdMenu()));

                $sform->addElement(new XoopsFormHidden('op', ''));

                $sform->display();
            }
        }  

        // s'il n'y a rien dans le paramètre id de l'adresse
    }

    function MENUSSaveEditMenu($id, $name, $content, $link, $priority, $id_menu_parent, $id_blocks, $subcatsfollow)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMMenus = new XentDMMenus();

        $xentDMMenus->setIdMenu($id);

        $xentDMMenus->setName(str_replace("'", '’', $name));

        $xentDMMenus->setContent(str_replace("'", '’', $content));

        $xentDMMenus->setLink($link);

        $xentDMMenus->setPriority($priority);

        $xentDMMenus->setIdMenuParent($id_menu_parent);

        $xentDMMenus->setIdBlocks($id_blocks);

        $xentDMMenus->setSubCatsFollow($subcatsfollow);

        $xentDMMenus->update(true);
    }

    function MENUSAreYouSureToDeleteMenu($id)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $xentDMMenus = new XentDMMenus();

        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = 0;
        }

        $menu = $xentDMMenus->getMenu($id);

        if (!empty($menu['ID_MENU'])) {
            $sform = new XoopsThemeForm(_AM_XENT_DM_AREYOUSUREDELETE, 'delmenu', xoops_getenv('PHP_SELF'));

            $sform->setExtra('enctype="multipart/form-data"');

            $sform->addElement(new XoopsFormLabel(_AM_XENT_DM_NAME, $myts->displayTarea($menu['name'])));

            $delete_button = new XoopsFormButton('', 'add', _AM_XENT_DELETE, 'submit');

            $delete_button->setExtra("onmouseover='document.delmenu.op.value=\"MENUSDeleteMenu\"'");

            $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

            $cancel_button->setExtra("onmouseover='document.delmenu.op.value=\"MENUSEditMenu\"'");

            $button_tray = new XoopsFormElementTray('', '');

            $button_tray->addElement($delete_button);

            $button_tray->addElement($cancel_button);

            $sform->addElement($button_tray);

            $sform->addElement(new XoopsFormHidden('id', $id));

            $sform->addElement(new XoopsFormHidden('op', ''));

            $sform->display();
        }  

        // aucune menu, msg d'erreur
    }

    function MENUSDeleteMenu($id)
    {
        $xentDMMenus = new XentDMMenus();

        $xentDMMenus->delete($id);
    }

        // ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';

    switch ($op) {
        case 'MENUSAddMenu':
            MENUSAddMenu();
            break;
        case 'MENUSSaveAddMenu':
            MENUSSaveAddMenu($name, $content, $link, $priority, $menuparent, $blocks);
            break;
        case 'MENUSEditMenu':
            MENUSEditMenu();
            break;
        case 'MENUSSaveEditMenu':
            if (empty($blocks)) {
                $blocks = [];
            }

            if (empty($subcatsfollow)) {
                $subcatsfollow = 0;
            }
            MENUSSaveEditMenu($id, $name, $content, $link, $priority, $menuparent, $blocks, $subcatsfollow);
            break;
        case 'MENUSAreYouSureToDeleteMenu':
            MENUSAreYouSureToDeleteMenu($id);
            break;
        case 'MENUSDeleteMenu':
            MENUSDeleteMenu($id);
            break;
        default:
            MENUSShowMenus();
            break;
    }

    // *************************** Fin de NTS **********************************

    buildMenusActionMenu();

    CloseTable();

    xoops_cp_footer();
