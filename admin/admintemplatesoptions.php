<?php

    require __DIR__ . '/admin_header.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

    foreach ($_REQUEST as $a => $b) {
        $$a = $b;
    }

    $eh = new ErrorHandler();
    xoops_cp_header();
    echo $oAdminButton->renderButtons('admintemplatesoptions');

    OpenTable();
    echo "<div class='adminHeader'>" . _AM_XENT_DM_ADMINTOSTITLE . '</div><br>';

    function TOSShowTos()
    {
    }

    // ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';

    switch ($op) {
        default:
            TOSShowTos();
            break;
    }

    // *************************** Fin de NTS **********************************

    CloseTable();

    xoops_cp_footer();
