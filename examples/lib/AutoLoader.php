<?php

$libDir = realpath(dirname(__FILE__) . '/../../src');
$collectionsDir = realpath(dirname(__FILE__) . '/../../../collection/src');
$exampleDir = realpath(dirname(__FILE__) . '/../');

set_include_path(implode(PATH_SEPARATOR,array($libDir, $collectionsDir, $exampleDir)));


function includeFileByClassName($className){
    if(strpos($className,'\\') !== FALSE){
        $tiles = explode('\\',$className);
        $filePath = implode('/',$tiles) . '.php';
        if(include_once($filePath)){
            return true;
        }
    }
}

spl_autoload_register('includeFileByClassName');