<?php

    #require_once XOOPS_ROOT_PATH."/modules/xentgen/include/xent_gen_tables.php";
    require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/include/functions.php';

    class XentDMConfig
    {
        public $db;

        public $smarConfig;

        public function __construct()
        {
            $this->db = XoopsDatabaseFactory::getDatabaseConnection();

            $hModule = xoops_getHandler('module');

            $hModConfig = xoops_getHandler('config');

            $smartModule = $hModule->getByDirname('xentdynamicmenu');

            $this->smartConfig = &$hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));
        }

        // true ou false

        public function getActivateBlocPliable()
        {
            if (0 == $this->smartConfig['activate_bloc_pliable']) {
                return false;
            }
  

            return true;
        }

        public function getMenuIndicator()
        {
            return $this->smartConfig['menu_indicator'];
        }
    }
