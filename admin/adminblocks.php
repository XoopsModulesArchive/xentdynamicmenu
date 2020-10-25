<?php

    require __DIR__ . '/admin_header.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';

    foreach ($_REQUEST as $a => $b) {
        $$a = $b;
    }

    $eh = new ErrorHandler();
    xoops_cp_header();
    echo $oAdminButton->renderButtons('adminblocks');

    OpenTable();
    echo "<div class='adminHeader'>" . _AM_XENT_DM_ADMINBLOCKSTITLE . '</div><br>';

    $block_arr = XoopsBlock::getByModule($xoopsModule->mid());

    function BLOCKSShowBlocks()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMBlocks = new XentDMBlocks();

        $myts = MyTextSanitizer::getInstance();

        $result = $xentDMBlocks->getAllBlocks();

        echo "<div align='right' class='adminActionMenu'><a href='adminblocks.php?op=BLOCKSAddBlock'>" . _AM_XENT_DM_ADDBLOCK . '</a></div>';

        echo "
        	<table width='100%' class='outer' cellspacing='1'>
            	<tr>
                    <th>" . _AM_XENT_DM_NAME . '</th>
                    <th>' . _AM_XENT_DM_OPTIONS . '</th>
                </tr>';

        while (false !== ($blocks = $xoopsDB->fetchArray($result))) {
            $xentDMBlocks->setIdBlock($blocks['bid']);

            $xentDMBlocks->setName($blocks['name']);

            $xentDMBlocks->setTitle($blocks['title']);

            echo "
            	<tr>
                    <td class='even'>" . $myts->displayTarea($xentDMBlocks->getName()) . "</td>
                    <td class='even'><a href='adminblocks.php?op=BLOCKSEditBlock&id=" . $xentDMBlocks->getIdBlock() . "'>" . _AM_XENT_DM_EDIT . '</a></td>
                </tr>
            ';
        }

        echo '
        	</table>
        ';
    }

    function BLOCKSAddBlock()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMBlocks = new XentDMBlocks();

        $xentDMTemplates = new XentDMTemplates();

        $myts = MyTextSanitizer::getInstance();

        if (!empty($_GET['name'])) {
            $xentDMBlocks->setName($_GET['name']);
        } else {
            $xentDMBlocks->setName('');
        }

        if (!empty($_GET['title'])) {
            $xentDMBlocks->setTitle($_GET['title']);
        } else {
            $xentDMBlocks->setTitle('[fr][/fr][en][/en]');
        }

        if (!empty($_GET['content'])) {
            $xentDMBlocks->setTitle($_GET['content']);
        } else {
            $xentDMBlocks->setTitle('[fr][/fr][en][/en]');
        }

        if (!empty($_GET['visible'])) {
            $xentDMBlocks->setVisible($_GET['visible']);
        } else {
            $xentDMBlocks->setVisible(0);
        }

        if (!empty($_GET['side'])) {
            $xentDMBlocks->setSide($_GET['side']);
        } else {
            $xentDMBlocks->setSide(0);
        }

        // peut etre un array

        if (!empty($_GET['displayin'])) {
            $xentDMBlocks->setDisplayIn($_GET['displayin']);
        } else {
            $xentDMBlocks->setDisplayIn(-1);
        }

        if (!empty($_GET['weight'])) {
            $xentDMBlocks->setWeight($_GET['weight']);
        } else {
            $xentDMBlocks->setWeight(0);
        }

        if (!empty($_GET['cachetime'])) {
            $xentDMBlocks->setCacheTime($_GET['cachetime']);
        } else {
            $xentDMBlocks->setCacheTime(0);
        }

        $sform = new XoopsThemeForm(_AM_XENT_DM_ADDBLOCK, 'addblock', xoops_getenv('PHP_SELF'));

        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormText(_AM_XENT_DM_NAME, 'name', 50, 255, $xentDMBlocks->getName()));

        $sform->addElement(new XoopsFormText(_AM_XENT_DM_TITLE, 'title', 50, 255, $xentDMBlocks->getTitle()));

        $sform->addElement(new XoopsFormDhtmlTextArea(_AM_XENT_DM_CONTENT, 'content', $xentDMBlocks->getContent()));

        $sform->addElement(makeSelect(_AM_XENT_DM_DISPLAY, 'display', $xentDMBlocks->getVisible(), makeNoYesArray(), 2));

        $sform->addElement(makeSelect(_AM_XENT_DM_DISPLAYWHERE, 'side', $xentDMBlocks->getSide(), makeBlockSideArray(), 5));

        $sform->addElement(makeSelect(_AM_XENT_DM_DISPLAYIN, 'displayin', $xentDMBlocks->getDisplayIn(), makeBlockDisplayInArray(), 5, 0, true));

        $sform->addElement(new XoopsFormText(_AM_XENT_DM_WEIGHT, 'weight', 3, 5, $xentDMBlocks->getWeight()));

        $sform->addElement(makeSelect(_AM_XENT_DM_CACHETIME, 'cachetime', $xentDMBlocks->getCacheTime(), makeBlockCacheTimeArray()));

        $sform->addElement(makeSelect(_AM_XENT_DM_TEMPLATE, 'template', $xentDMBlocks->getTemplate(), $xentDMTemplates->getAllTemplates()));

        $save_button = new XoopsFormButton('', 'add', _AM_XENT_ADD, 'submit');

        $save_button->setExtra("onmouseover='document.addblock.op.value=\"BLOCKSSaveAddBlock\"'");

        $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

        $cancel_button->setExtra("onmouseover='document.addblock.op.value=\"BLOCKSShowBlocks\"'");

        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement($save_button);

        $button_tray->addElement($cancel_button);

        $sform->addElement($button_tray);

        $sform->addElement(new XoopsFormHidden('op', ''));

        $sform->display();
    }

    function BLOCKSSaveAddBlock($name, $title, $content, $display, $side, $displayin, $weight, $cachetime, $template)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMBlocks = new XentDMBlocks();

        $xentDMBlocks->setName(str_replace("'", '’', $name));

        $xentDMBlocks->setTitle(str_replace("'", '’', $title));

        $xentDMBlocks->setContent(str_replace("'", '’', $content));

        $xentDMBlocks->setVisible($display);

        $xentDMBlocks->setSide($side);

        $xentDMBlocks->setDisplayIn($displayin);

        $xentDMBlocks->setWeight($weight);

        $xentDMBlocks->setCacheTime($cachetime);

        $xentDMBlocks->setTemplate($template);

        $xentDMBlocks->add();
    }

    function BLOCKSEditBlock()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMBlocks = new XentDMBlocks();

        $xentDMTemplates = new XentDMTemplates();

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
            $block = $xentDMBlocks->getBlock($id);

            if (!empty($block['bid'])) {
                $xentDMBlocks->setIdBlock($block['bid']);

                $xentDMBlocks->setTitle($block['title']);

                $xentDMBlocks->setContent($block['content']);

                $xentDMBlocks->setName($block['name']);

                $xentDMBlocks->setVisible($block['visible']);

                $xentDMBlocks->setWeight($block['weight']);

                $xentDMBlocks->setCacheTime($block['bcachetime']);

                $xentDMBlocks->setTemplate($block['template']);

                $xentDMBlocks->setSide($block['side']);

                $sform = new XoopsThemeForm(_AM_XENT_DM_EDIT . ' - ' . $myts->displayTarea($xentDMBlocks->getTitle()), 'editblock', xoops_getenv('PHP_SELF'));

                $sform->setExtra('enctype="multipart/form-data"');

                $sform->addElement(new XoopsFormText(_AM_XENT_DM_NAME, 'name', 50, 255, $xentDMBlocks->getName()));

                $sform->addElement(new XoopsFormText(_AM_XENT_DM_TITLE, 'title', 50, 255, $xentDMBlocks->getTitle()));

                $sform->addElement(new XoopsFormDhtmlTextArea(_AM_XENT_DM_CONTENT, 'content', $xentDMBlocks->getContent()));

                $sform->addElement(makeSelect(_AM_XENT_DM_DISPLAY, 'display', $xentDMBlocks->getVisible(), makeNoYesArray(), 2));

                $sform->addElement(makeSelect(_AM_XENT_DM_DISPLAYWHERE, 'side', $xentDMBlocks->getSide(), makeBlockSideArray(), 5));

                $sform->addElement(makeSelect(_AM_XENT_DM_DISPLAYIN, 'displayin', $xentDMBlocks->getBlockDisplayInArray($block['bid']), makeBlockDisplayInArray(), 5, 0, true));

                $sform->addElement(new XoopsFormText(_AM_XENT_DM_WEIGHT, 'weight', 3, 5, $xentDMBlocks->getWeight()));

                $sform->addElement(makeSelect(_AM_XENT_DM_CACHETIME, 'cachetime', $xentDMBlocks->getCacheTime(), makeBlockCacheTimeArray()));

                $sform->addElement(makeSelect(_AM_XENT_DM_TEMPLATE, 'template', $xentDMBlocks->getTemplate(), $xentDMTemplates->getAllTemplates()));

                $save_button = new XoopsFormButton('', 'add', _AM_XENT_MODIFY, 'submit');

                $save_button->setExtra("onmouseover='document.editblock.op.value=\"BLOCKSSaveEditBlock\"'");

                $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_DELETE, 'submit');

                $cancel_button->setExtra("onmouseover='document.editblock.op.value=\"BLOCKSAreYouSureToDeleteBlock\"'");

                $button_tray = new XoopsFormElementTray('', '');

                $button_tray->addElement($save_button);

                $button_tray->addElement($cancel_button);

                $sform->addElement($button_tray);

                $sform->addElement(new XoopsFormHidden('id', $xentDMBlocks->getIdBlock()));

                $sform->addElement(new XoopsFormHidden('op', ''));

                $sform->display();
            }
        }  

        // s'il n'y a rien dans le paramètre id de l'adresse
    }

    function BLOCKSSaveEditBlock($id, $name, $title, $content, $display, $side, $displayin, $weight, $cachetime, $template)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $xentDMBlocks = new XentDMBlocks();

        $xentDMBlocks->setIdBlock($id);

        $xentDMBlocks->setName(str_replace("'", '’', $name));

        $xentDMBlocks->setTitle(str_replace("'", '’', $title));

        $xentDMBlocks->setContent(str_replace("'", '’', $content));

        $xentDMBlocks->setVisible($display);

        $xentDMBlocks->setSide($side);

        $xentDMBlocks->setDisplayIn($displayin);

        $xentDMBlocks->setWeight($weight);

        $xentDMBlocks->setCacheTime($cachetime);

        $xentDMBlocks->setTemplate($template);

        $xentDMBlocks->update();
    }

    function BLOCKSAreYouSureToDeleteBlock($id)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $xentDMBlocks = new XentDMBlocks();

        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = 0;
        }

        $title = $xentDMBlocks->getBlock($id);

        if (!empty($title['bid'])) {
            $sform = new XoopsThemeForm(_AM_XENT_DM_AREYOUSUREDELETE, 'delblock', xoops_getenv('PHP_SELF'));

            $sform->setExtra('enctype="multipart/form-data"');

            $sform->addElement(new XoopsFormLabel(_AM_XENT_DM_TITLE, $myts->displayTarea($title['title'])));

            $delete_button = new XoopsFormButton('', 'add', _AM_XENT_DELETE, 'submit');

            $delete_button->setExtra("onmouseover='document.delblock.op.value=\"BLOCKSDeleteBlock\"'");

            $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

            $cancel_button->setExtra("onmouseover='document.delblock.op.value=\"BLOCKSEditBlock\"'");

            $button_tray = new XoopsFormElementTray('', '');

            $button_tray->addElement($delete_button);

            $button_tray->addElement($cancel_button);

            $sform->addElement($button_tray);

            $sform->addElement(new XoopsFormHidden('id', $id));

            $sform->addElement(new XoopsFormHidden('op', ''));

            $sform->display();
        }  

        // aucune job, msg d'erreur
    }

    function BLOCKSDeleteBlock($id)
    {
        $xentDMBlocks = new XentDMBlocks();

        $xentDMBlocks->delete($id);
    }

        // ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';

    switch ($op) {
        case 'BLOCKSAddBlock':
            BLOCKSAddBlock();
            break;
        case 'BLOCKSSaveAddBlock':
            BLOCKSSaveAddBlock($name, $title, $content, $display, $side, $displayin, $weight, $cachetime, $template);
            break;
        case 'BLOCKSEditBlock':
            BLOCKSEditBlock();
            break;
        case 'BLOCKSSaveEditBlock':
            BLOCKSSaveEditBlock($id, $name, $title, $content, $display, $side, $displayin, $weight, $cachetime, $template);
            break;
        case 'BLOCKSAreYouSureToDeleteBlock':
            BLOCKSAreYouSureToDeleteBlock($id);
            break;
        case 'BLOCKSDeleteBlock':
            BLOCKSDeleteBlock($id);
            break;
        default:
            BLOCKSShowBlocks();
            break;
    }

    // *************************** Fin de NTS **********************************

    buildBlocksActionMenu();

    CloseTable();

    xoops_cp_footer();
