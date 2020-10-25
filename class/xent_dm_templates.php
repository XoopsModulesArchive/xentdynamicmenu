<?php

    #require_once XOOPS_ROOT_PATH."/modules/xentgen/include/xent_gen_tables.php";
    require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/include/functions.php';

    class XentDMTemplates
    {
        public $db;

        // constructor

        public function __construct()
        {
            $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        }

        public function getAllTemplates()
        {
            $arr = [];

            $sql = 'SELECT * FROM ' . $this->db->prefix('tplfile') . " WHERE tpl_module='xentdynamicmenu'";

            $result = $this->db->query($sql);

            while ($templates = $this->db->fetchArray($result)) {
                $arr[$templates['tpl_file']] = $templates['tpl_file'];
            }

            return $arr;
        }
    }
