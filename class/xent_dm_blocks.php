<?php

    #require_once XOOPS_ROOT_PATH."/modules/xentgen/include/xent_gen_tables.php";
    require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/include/functions.php';

    class XentDMBlocks
    {
        public $cachetime;

        public $content;

        public $db;

        public $displayin;

        public $idblock;

        public $name;

        public $side;

        public $template;

        public $title;

        public $visible;

        public $weight;        

        // constructor

        public function __construct()
        {
            $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        }        

        // setters

        public function setCacheTime($cachetime)
        {
            $this->cachetime = $cachetime;
        }

        public function setContent($content)
        {
            $this->content = $content;
        }

        public function setDisplayIn($displayin)
        {
            $this->displayin = $displayin;
        }

        public function setIdBlock($id)
        {
            $this->idblock = $id;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function setSide($side)
        {
            $this->side = $side;
        }

        public function setTemplate($template)
        {
            $this->template = $template;
        }

        public function setTitle($title)
        {
            $this->title = $title;
        }

        public function setVisible($visible)
        {
            $this->visible = $visible;
        }

        public function setWeight($weight)
        {
            $this->weight = $weight;
        }        

        // getters

        public function getCacheTime()
        {
            return $this->cachetime;
        }

        public function getContent()
        {
            return $this->content;
        }

        public function getDisplayIn()
        {
            return $this->displayin;
        }

        public function getIdBlock()
        {
            return $this->idblock;
        }

        public function getName()
        {
            return $this->name;
        }

        public function getSide()
        {
            switch ($this->side) {
                case 0:
                    return 0;
                    break;
                case 1:
                    return 1;
                    break;
                case 3:
                    return 2;
                    break;
                case 4:
                    return 3;
                    break;
                case 5:
                    return 4;
                    break;
            }
        }

        public function getTemplate()
        {
            return $this->template;
        }

        public function getTitle()
        {
            return $this->title;
        }

        public function getVisible()
        {
            return $this->visible;
        }

        public function getWeight()
        {
            return $this->weight;
        }        

        // methods

        public function add($inBatch = 0)
        {
            global $module_tables, $xoopsModule;

            $myts = MyTextSanitizer::getInstance();

            // il faut tout d'abord enregistrer le bloc dans la table xoops_newblocks

            $sql = 'INSERT INTO ' . $this->db->prefix('newblocks') . ' (mid, func_num, options, name, title, content, side, weight, visible, block_type, c_type, isactive, dirname, func_file, show_func, edit_func, template, bcachetime, last_modified) ' .
                'VALUES (' . $xoopsModule->getVar('mid') . ", 0, '', '" . $myts->displayTarea($this->getName()) . "', '" . $this->getTitle() . "', '" . $this->getContent() . "', " . $this->getSide() . ', ' . $this->getWeight() . ', ' . $this->getVisible() . ", 'M', 'H', 1, '" . $xoopsModule->getVar('dirname') . "', '" . mb_substr($this->getTemplate(), 0, mb_strpos($this->getTemplate(), '.')) . ".php', '" . mb_substr($this->getTemplate(), 0, mb_strpos($this->getTemplate(), '.')) . "', '', '" . $this->getTemplate() . "', " . $this->getCacheTime() . ', ' . time() . ')';

            $this->db->queryF($sql);

            // on doit trouver le id du block

            $sql = 'SELECT bid FROM ' . $this->db->prefix('newblocks') . ' ORDER by bid DESC LIMIT 1';

            $result = $this->db->query($sql);

            $block_added = $this->db->fetchArray($result);

            // linker dans la table xoops_block_module_link

            $arr = $this->getDisplayIn();

            foreach ($arr as $value) {
                $sql = 'INSERT INTO ' . $this->db->prefix('block_module_link') . ' (block_id, module_id) VALUES (' . $block_added['bid'] . ', ' . $value . ')';

                $this->db->queryF($sql);
            }

            // linker dans les permissions des groupes

            // permissions pour le groupe webmestres

            $sql = 'INSERT INTO ' . $this->db->prefix('group_permission') . ' (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (1, ' . $block_added['bid'] . ", 1, 'block_read')";

            $this->db->queryF($sql);

            // permissions pour le group utilisateur enregistrés

            $sql = 'INSERT INTO ' . $this->db->prefix('group_permission') . ' (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (2, ' . $block_added['bid'] . ", 1, 'block_read')";

            $this->db->queryF($sql);

            // ajouter à la table xoops_tplfile

            /*$sql = "INSERT INTO ".$this->db->prefix('tplfile')." (tpl_refid, tpl_module, tpl_tplset, tpl_file, tpl_desc, tpl_lastmodified, tpl_lastimported, tpl_type) ".
                "VALUES (".$block_added['bid'].", 'xentdynamicmenu', 'default', 'xent_dm_block_menu.html', '', ".time().", 0, 'block')";
            $this->db->queryF($sql);*/

            if (0 == $inBatch) {
                if (0 == $this->db->errno()) {
                    redirect_header('adminblocks.php', 1, _AM_XENT_DBUPDATED);
                } else {
                    redirect_header('adminblocks.php', 4, $this->db->error());
                }
            }
        }

        public function delete($id)
        {
            global $module_tables;

            // deleter dans la table xoops_newblocks

            $sql = 'DELETE FROM ' . $this->db->prefix('newblocks') . " WHERE bid=$id";

            $this->db->queryF($sql);

            // deleter dans la table xoops_block_module_link

            $sql = 'DELETE FROM ' . $this->db->prefix('block_module_link') . " WHERE block_id=$id";

            $this->db->queryF($sql);

            // deleter dans la table xoops_group_permission

            $sql = 'DELETE FROM ' . $this->db->prefix('group_permission') . " WHERE gperm_itemid=$id AND gperm_name='block_read'";

            $this->db->queryF($sql);

            if (0 == $this->db->errno()) {
                redirect_header('adminblocks.php', 1, _AM_XENT_DBUPDATED);
            } else {
                redirect_header('adminblocks.php', 4, $this->db->error());
            }
        }                

        public function getAllBlocks()
        {
            global $xoopsModule;

            $sql = 'SELECT * FROM ' . $this->db->prefix('newblocks') . ' WHERE mid=' . $xoopsModule->getVar('mid') . ' ORDER BY name';

            $result = $this->db->query($sql);

            return $result;
        }

        public function getAllBlocksArray()
        {
            global $xoopsModule;

            $arr = [];

            $arr[0] = _AM_XENT_DM_UNASSIGNE;

            $sql = 'SELECT * FROM ' . $this->db->prefix('newblocks') . ' WHERE mid=' . $xoopsModule->getVar('mid') . ' ORDER BY name';

            $result = $this->db->query($sql);

            while (false !== ($block = $this->db->fetchArray($result))) {
                $arr[$block['bid']] = $block['name'];
            }

            return $arr;
        }                

        public function getBlock($id)
        {
            global $module_tables;

            $sql = 'SELECT * FROM ' . $this->db->prefix('newblocks') . ' AS t1, ' . $this->db->prefix('block_module_link') . " AS t2 WHERE t1.bid=t2.block_id AND bid=$id";

            $result = $this->db->query($sql);

            $block = $this->db->fetchArray($result);

            return $block;
        }

        public function getBlockDisplayInArray($blockid)
        {
            $arr = [];

            $sql = 'SELECT module_id FROM ' . $this->db->prefix('block_module_link') . " WHERE block_id=$blockid";

            $result = $this->db->query($sql);

            while (false !== ($display = $this->db->fetchArray($result))) {
                $arr[$display['module_id']] = $display['module_id'];
            }

            return $arr;
        }

        public function update()
        {
            global $xoopsModule;

            // il faut updater la table newblocks

            $sql = 'UPDATE ' . $this->db->prefix('newblocks') . " SET name='" . $this->getName() . "', title='" . $this->getTitle() . "', content='" . $this->getContent() . "', visible=" . $this->getVisible() . ', side=' . $this->getSide() . ', weight=' . $this->getWeight() . ', bcachetime=' . $this->getCacheTime() . ", template='" . $this->getTemplate() . "', show_func='" . mb_substr($this->getTemplate(), 0, mb_strpos($this->getTemplate(), '.')) . "', func_file='" . mb_substr($this->getTemplate(), 0, mb_strpos($this->getTemplate(), '.')) . ".php' WHERE bid=" . $this->getIdBlock();

            $this->db->queryF($sql);

            // il faut updater la table block_module_link

            $sql = 'DELETE FROM ' . $this->db->prefix('block_module_link') . ' WHERE block_id=' . $this->getIdBlock();

            $this->db->queryF($sql);

            $arr = $this->getDisplayIn();

            foreach ($arr as $value) {
                $sql = 'INSERT INTO ' . $this->db->prefix('block_module_link') . ' (block_id, module_id) VALUES (' . $this->getIdBlock() . ', ' . $value . ')';

                $this->db->queryF($sql);
            }

            if (0 == $this->db->errno()) {
                redirect_header('adminblocks.php', 1, _AM_XENT_DBUPDATED);
            } else {
                redirect_header('adminblocks.php', 4, $this->db->error());
            }
        }
    }
