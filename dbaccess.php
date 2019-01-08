<?php
class DBAccess{
    public static function ConfigInit() {
        /* ********************    CONNECTION A LA BASE    ******************** */
        require("include/dbconf.php");

        try {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            $GLOBALS['BDD'] = new PDO('mysql:host='.$config['localhost'].';dbname='.$config['dbName'], $config['login'], $config['pwd'], $pdo_options);
            $GLOBALS['BDD']->exec('SET NAMES utf8');
        }
        Catch(Exception $e){
            die("Impossible d'acc&egrave;der &agrave; la base.");
        }
    }
}
?>