<?php

    global $xoopsDB, $xoopsModule;

    $sql = 'DELETE FROM ' . $xoopsDB->prefix('tplfile') . " WHERE tpl_module='xentdynamicmenu'";
    $xoopsDB->query($sql);
