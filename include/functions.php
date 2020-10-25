<?php

    function makeBlockSideArray()
    {
        $arr = [];

        $arr[0] = _AM_XENT_DM_LEFT;

        $arr[1] = _AM_XENT_DM_RIGHT;

        $arr[2] = _AM_XENT_DM_MIDDLELEFT;

        $arr[3] = _AM_XENT_DM_MIDDLERIGHT;

        $arr[4] = _AM_XENT_DM_MIDDLECENTER;

        return $arr;
    }

    function makeBlockCacheTimeArray()
    {
        $arr = [];

        $arr[0] = _AM_XENT_DM_NOCACHE;

        $arr[30] = _AM_XENT_DM_30SECCACHE;

        $arr[60] = _AM_XENT_DM_1MINCACHE;

        $arr[300] = _AM_XENT_DM_5MINCACHE;

        $arr[1800] = _AM_XENT_DM_30MINCACHE;

        $arr[3600] = _AM_XENT_DM_1HOURCACHE;

        $arr[18000] = _AM_XENT_DM_5HOURCACHE;

        $arr[86400] = _AM_XENT_DM_1DAYCACHE;

        $arr[259200] = _AM_XENT_DM_3DAYCACHE;

        $arr[604800] = _AM_XENT_DM_1WEEKCACHE;

        $arr[2592000] = _AM_XENT_DM_1MONTHCACHE;

        return $arr;
    }

    function makeBlockDisplayInArray()
    {
        global $xoopsDB;

        $arr = [];

        $arr[-1] = _AM_XENT_DM_TOPPAGE;

        $arr[0] = _AM_XENT_DM_ALLPAGES;

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('modules');

        $result = $xoopsDB->query($sql);

        while (false !== ($modules = $xoopsDB->fetchArray($result))) {
            $arr[$modules['mid']] = $modules['name'];
        }

        return $arr;
    }

    function buildBlocksActionMenu()
    {
        echo "<br><div class='adminActionMenu'><a href=adminblocks.php class='adminActionMenu'>" . _AM_XENT_DM_ADMINBLOCKSTITLE . '</a></div>';
    }

    function buildMenusActionMenu()
    {
        echo "<br><div class='adminActionMenu'><a href=adminmenus.php class='adminActionMenu'>" . _AM_XENT_DM_ADMINMENUSTITLE . '</a></div>';
    }
