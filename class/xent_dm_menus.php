<?php

    #require_once XOOPS_ROOT_PATH."/modules/xentgen/include/xent_gen_tables.php";
    require_once XOOPS_ROOT_PATH . '/modules/xentdynamicmenu/include/functions_user.php';

    class XentDMMenus
    {
        public $content;

        public $db;

        public $idblocks;

        public $idmenu;

        public $idmenuparent;

        public $link;

        public $name;

        public $priority;

        public $subcatsfollow;                

        // constructor

        public function __construct()
        {
            $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        }        

        // setters

        public function setContent($content)
        {
            $this->content = $content;
        }

        public function setIdBlocks($arr)
        {
            $this->idblock = $arr;
        }

        public function setIdMenu($idmenu)
        {
            $this->idmenu = $idmenu;
        }

        public function setIdMenuParent($idmenuparent)
        {
            $this->idmenuparent = $idmenuparent;
        }

        public function setLink($link)
        {
            $this->link = $link;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function setPriority($priority)
        {
            $this->priority = $priority;
        }

        public function setSubCatsFollow($subcatsfollow)
        {
            $this->subcatsfollow = $subcatsfollow;
        }                        

        // getters

        public function getContent()
        {
            return $this->content;
        }

        public function getIdBlocks()
        {
            return $this->idblock;
        }

        public function getIdMenu()
        {
            return $this->idmenu;
        }

        public function getIdMenuParent()
        {
            return $this->idmenuparent;
        }

        public function getLink()
        {
            return $this->link;
        }

        public function getName()
        {
            return $this->name;
        }

        public function getPriority()
        {
            return $this->priority;
        }

        public function getSubCatsFollow()
        {
            return $this->subcatsfollow;
        }                                

        // methods

        public function add($inBatch = 0)
        {
            global $module_tables;

            $myts = MyTextSanitizer::getInstance();

            $this->setName($myts->addSlashes($this->getName()));

            $this->setContent($myts->addSlashes($this->getContent()));

            $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[0]) . " (name, content, link, priority, id_menu_parent) VALUES ('" . $this->getName() . "', '" . $this->getContent() . "', '" . $this->getLink() . "', " . $this->getPriority() . ', ' . $this->getIdMenuParent() . ')';

            $this->db->queryF($sql);

            // il faut retrouver le dernier id entré (le dernier menu) dans la table xoops_xent_dm_menus

            $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[0]) . ' ORDER BY ID_MENU DESC LIMIT 1';

            $result = $this->db->query($sql);

            $last_record = $this->db->fetchArray($result);

            $last_id = $last_record['ID_MENU'];

            $arr = $this->getIdBlocks();

            foreach ($arr as $value) {
                $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[1]) . ' (ID_MENU, ID_BLOCK) VALUES (' . $last_id . ', ' . $value . ')';

                $this->db->queryF($sql);
            }

            if (0 == $inBatch) {
                if (0 == $this->db->errno()) {
                    redirect_header('adminmenus.php', 1, _AM_XENT_DBUPDATED);
                } else {
                    redirect_header('adminmenus.php', 4, $this->db->error());
                }
            }
        }

        public function delete($id)
        {
            global $module_tables;

            $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[0]) . " WHERE ID_MENU=$id";

            $this->db->queryF($sql);

            $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[1]) . " WHERE ID_MENU=$id";

            $this->db->queryF($sql);

            if (0 == $this->db->errno()) {
                redirect_header('adminmenus.php', 1, _AM_XENT_DBUPDATED);
            } else {
                redirect_header('adminmenus.php', 4, $this->db->error());
            }
        }

        public function displayMenu($idmenu, $idblock)
        {
            if ($this->existsInLinkTable($idmenu, $idblock)) {
                $id_parent = reference_('xent_dm_menus', 'id_menu_parent', 'ID_MENU', $idmenu);

                while (0 != $id_parent) {
                    if ($this->existsInLinkTable($id_parent, $idblock)) {
                        $id_parent = reference_('xent_dm_menus', 'id_menu_parent', 'ID_MENU', $id_parent);
                    } else {
                        return false;
                        exit;
                    }
                }

                return true;
            }
  

            return false;
        }

        public function existsInLinkTable($idmenu, $idblock)
        {
            $sql = 'SELECT * FROM ' . $this->db->prefix('xent_dm_link_menu_block') . " WHERE ID_MENU=$idmenu AND ID_BLOCK=$idblock";

            $result = $this->db->query($sql);

            $menu = $this->db->fetchArray($result);

            if (!empty($menu['ID_MENU'])) {
                return true;
            }
  

            return false;
        }

        public function getAllMenus()
        {
            global $module_tables;

            $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[0]) . ' ORDER BY name';

            $result = $this->db->query($sql);

            return $result;
        }

        public function getMenu($id)
        {
            global $module_tables;

            $sql = 'SELECT * FROM ' . $this->db->prefix('xent_dm_menus') . " WHERE ID_MENU=$id";

            $result = $this->db->query($sql);

            $menu = $this->db->fetchArray($result);

            return $menu;
        }

        public function getMenusBlocks($idblock)
        {
            global $module_tables;

            $sql = 'SELECT t1.ID_MENU, t2.ID_MENU, t2.priority FROM ' . $this->db->prefix('xent_dm_link_menu_block') . ' as t1, ' . $this->db->prefix('xent_dm_menus') . " as t2 WHERE ID_BLOCK=$idblock AND t1.ID_MENU=t2.ID_MENU ORDER BY t2.priority";

            $result = $this->db->query($sql);

            return $result;
        }

        public function getMenusBlocksArray($idmenu)
        {
            global $module_tables;

            $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[1]) . " WHERE ID_MENU=$idmenu";

            $result = $this->db->query($sql);

            $arr = [];

            while (false !== ($menus = $this->db->fetchArray($result))) {
                $arr[$menus['ID_BLOCK']] = $menus['ID_BLOCK'];
            }

            return $arr;
        }

        public function getMenuLevel($id)
        {
            $menu = $this->getMenu($id);

            $id_parent = $menu['id_menu_parent'];

            $lvl = 0;

            while (0 != $id_parent) {
                $menu = $this->getMenu($id_parent);

                $id_parent = $menu['id_menu_parent'];

                $lvl++;
            }

            return $lvl;
        }

        public function getRootMenusList($selected = false)
        {
            global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

            $sql = 'SELECT * FROM ' . $this->db->prefix('xent_dm_menus') . ' WHERE id_menu_parent=0';

            $result = $this->db->query($sql);

            $arr_menuroot = [];

            $arr_menuroot[0] = '-------';

            while (false !== ($menuroot = $this->db->fetchArray($result))) {
                $id = $menuroot['ID_MENU'];

                $arr_menuroot = $this->getSubMenusList($id, true, 0, $arr_menuroot);
            }

            return $arr_menuroot;
        }

        public function getSubMenusList($id, $bool = false, $niv = 0, $ar = [])
        {
            global $module_tables;

            $id_menu = $id;

            $niveau = $niv;

            $sql = 'SELECT * FROM ' . $this->db->prefix('xent_dm_menus') . " WHERE ID_MENU=$id_menu";

            $result = $this->db->query($sql);

            $submenu = $this->db->fetchArray($result);

            static $arr = [];

            if (true === $bool) {
                $arr = $ar;
            }

            $arr[$submenu['ID_MENU']] = str_repeat('-', $niveau * 2) . $submenu['content'];

            $sql = 'SELECT * FROM ' . $this->db->prefix('xent_dm_menus') . " WHERE id_menu_parent=$id_menu ORDER BY priority";

            $result = $this->db->query($sql);

            [$ID_MENU] = $this->db->fetchRow($result);

            while (!empty($ID_MENU)) {
                $niveau++;

                $this->getSubMenusList($ID_MENU, false, $niveau);

                $niveau--;

                [$ID_MENU] = $this->db->fetchRow($result);
            }

            return $arr;
        }

        public function getUnassignedMenus()
        {
            global $module_tables;

            $arr = [];

            $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[0]);

            $result = $this->db->query($sql);

            while (false !== ($menu = $this->db->fetchArray($result))) {
                $id = $menu['ID_MENU'];

                $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[1]) . " WHERE ID_MENU=$id";

                $result1 = $this->db->query($sql);

                $menuAssigned = $this->db->fetchArray($result1);

                if (empty($menuAssigned['ID_MENU'])) {
                    $arr[$id] = $id;
                }
            }

            return $arr;
        }

        public function hasSubCats($id)
        {
            $sql = 'SELECT * FROM ' . $this->db->prefix('xent_dm_menus') . " WHERE id_menu_parent=$id";

            $result = $this->db->query($sql);

            $menu = $this->db->fetchArray($result);

            if (empty($menu['ID_MENU'])) {
                return false;
            }
  

            return true;
        }

        public function update()
        {
            global $module_tables;

            #$this->setTitle(str_replace("\'","''",$this->getTitle()));

            $myts = MyTextSanitizer::getInstance();

            $this->setName($myts->addSlashes($this->getName()));

            $this->setContent($myts->addSlashes($this->getContent()));

            $id_menu_parent_org = reference_($module_tables[0], 'id_menu_parent', 'ID_MENU', $this->getIdMenu());

            $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[0]) . ' WHERE id_menu_parent=' . $this->getIdMenu();

            $result = $this->db->query($sql);

            if (0 == $this->getSubCatsFollow()) {
                while (false !== ($smenu = $this->db->fetchArray($result))) {
                    if (0 != $id_menu_parent_org) {
                        $sql = 'UPDATE ' . $this->db->prefix($module_tables[0]) . ' SET id_menu_parent=' . $id_menu_parent_org . ' WHERE ID_MENU=' . $smenu['ID_MENU'];
                    } else {
                        $sql = 'UPDATE ' . $this->db->prefix($module_tables[0]) . ' SET id_menu_parent=' . $this->getIdMenu() . ' WHERE ID_MENU=' . $smenu['ID_MENU'];
                    }

                    $this->db->queryF($sql);
                }
            }

            // ici on update le menu à modifier

            $sql = 'UPDATE ' . $this->db->prefix($module_tables[0]) . " SET name='" . $this->getName() . "', content='" . $this->getContent() . "', link='" . $this->getLink() . "', priority=" . $this->getPriority() . ', id_menu_parent=' . $this->getIdMenuParent() . ' WHERE ID_MENU=' . $this->getIdMenu();

            $this->db->queryF($sql);

            // il faut updater la table xoops_xent_dm_menu_block_link

            $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[1]) . ' WHERE ID_MENU=' . $this->getIdMenu();

            $this->db->queryF($sql);

            $arr = $this->getIdBlocks();

            foreach ($arr as $value) {
                if (0 == $value) {
                    //Do nothing
                } else {
                    $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[1]) . ' (ID_MENU, ID_BLOCK) VALUES (' . $this->getIdMenu() . ', ' . $value . ')';

                    $this->db->queryF($sql);
                }
            }

            if (0 == $this->db->errno()) {
                redirect_header('adminmenus.php', 1, _AM_XENT_DBUPDATED);
            } else {
                redirect_header('adminmenus.php', 4, $this->db->error());
            }
        }
    }
