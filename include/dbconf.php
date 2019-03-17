<?php
if (!defined("LOADED_AS_MODULE")) {
    die ("Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der directement &agrave; cette page...");
}
if ($GLOBALS['DEBUG']==0) {
    $config['localhost']    = "prod"; 
    $config['dbName']       = "dbprod";
    $config['login']        = "dblogin";
    $config['pwd']          = "dbpass";
} else {
    $config['localhost']    = "localhost";
    $config['dbName']       = "copy_prod";
    $config['login']        = "root";
    $config['pwd']          = "";
}
?>