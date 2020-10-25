<?php

    function makeSelect($caption, $name, $selected, $arrayOptions, $linesDisplayed = 1, $idMatters = 0, $multiple = false)
    {
        $select = new XoopsFormSelect($caption, $name, $selected, $linesDisplayed, $multiple);

        if (1 == $idMatters) {
            $select->addOption(0, '---');
        }

        $select->addOptionArray($arrayOptions);

        return $select;
    }

    function makeNoYesArray()
    {
        $arr = [];

        $arr[0] = _AM_XENT_NO;

        $arr[1] = _AM_XENT_YES;

        return $arr;
    }

    function reference($fct1, $fct2, $fct3, $id)
    {
        global $xoopsDB;

        $myts = MyTextSanitizer::getInstance();

        $sql = 'SELECT ' . $fct3 . ', ' . $fct2 . ' FROM ' . $xoopsDB->prefix($fct1) . ' WHERE ' . $fct3 . "=$id";

        $result = $xoopsDB->query($sql);

        [$id, $champs] = $xoopsDB->fetchRow($result);

        $titres = $myts->displayTarea($champs);

        return $titres;
    }
