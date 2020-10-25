<?php

    function reference_($fct1, $fct2, $fct3, $id)
    {
        global $xoopsDB;

        $myts = MyTextSanitizer::getInstance();

        $sql = 'SELECT ' . $fct3 . ', ' . $fct2 . ' FROM ' . $xoopsDB->prefix($fct1) . ' WHERE ' . $fct3 . "=$id";

        $result = $xoopsDB->query($sql);

        [$id, $champs] = $xoopsDB->fetchRow($result);

        $titres = $myts->displayTarea($champs);

        return $titres;
    }
