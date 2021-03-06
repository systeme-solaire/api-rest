<?php
if (!defined("LOADED_AS_MODULE")) {
    die ("Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der directement &agrave; cette page...");
}
function swagger() {
    header('Content-Type: application/json; charset=utf-8');
    echo '{"swagger":"2.0",';
    echo '"info":{';
    echo '"title":"Solar System openData",';
    echo '"description":"API to get all data about all solar system objects",';
    echo '"version":"'.$GLOBALS['API_VERSION'].'"';
    echo '},'; //info
    echo '"host":"' . $_SERVER['HTTP_HOST'] . '",';
    echo '"basePath":"' . $_SERVER['SCRIPT_NAME'] . '",';
    echo '"schemes":["http' . ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 's' : '') . '"],';
    echo '"consumes":["application/json"],';
    echo '"produces":["application/json"],';
    echo '"tags":[';
    echo '{';
    echo '"name":"'.$GLOBALS['bodies'].'",';
    echo '"description":"Object with all data about the concerned body : orbitals, physicals and atmosphere"';
    echo '},';
    echo '{';
        echo '"name":"'.$GLOBALS['known'].'",';
        echo '"description":"Count of known objects"';
        echo '}';
    echo '],'; //tag
    echo '"paths":{';

    echo '"/'.$GLOBALS['bodies'].'":{';
    echo '"get":{';
    echo '"tags":["'.$GLOBALS['bodies'].'"],';
    echo '"summary":"List",';
    Bodies::echoParameters();
    echo ',';
    echo '"responses":{';
    echo '"200":{';
    echo '"description":"An array of '.$GLOBALS['bodies'].'",';
    echo '"schema":{';
    echo '"type": "object",';
    echo '"properties": {';
    echo '"'.$GLOBALS['bodies'].'": {';
    echo '"type":"array",';
    echo '"items":{';
    echo '"type": "object",';
    echo '"properties": {';
    Bodies::getDescSwaggerColumnsForBodies(true);
    echo '}'; //properties
    echo '}'; //items
    echo '}'; //table
    echo '}'; //properties
    echo '}'; //schema
    echo '}'; //200
    echo '}'; //responses
    echo '}'; //get
    echo '}'; // /'.$GLOBALS['bodies']

    echo ',"/'.$GLOBALS['bodies'].'/{id}":{';
    echo '"get":{';
    echo '"tags":["'.$GLOBALS['bodies'].'"],';
    echo '"summary":"read",';
    echo '"parameters":[';
    echo '{';
    echo '"name":"id",';
    echo '"in":"path",';
    echo '"description":"Identifier for item.",';
    echo '"required":true,';
    echo '"type":"string"';
    echo '}';
    echo '],'; // parameters
    echo '"responses":{';
    echo '"200":{';
    echo '"description":"The requested item.",';
    echo '"schema":{';
    echo '"type": "object",';
    echo '"properties": {';
    Bodies::getDescSwaggerColumnsForBodies(false);
    echo '}'; //properties
    echo '}'; //schema
    echo '}'; //200
    echo '}'; //responses
    echo '}'; //get
    echo '}'; // /'.$GLOBALS['bodies'].'/{id}

    echo ',"/'.$GLOBALS['known'].'":{';
    echo '"get":{';
    echo '"tags":["'.$GLOBALS['known'].'"],';
    echo '"summary":"List",';
    Known::echoParameters();
    echo ',';
    echo '"responses":{';
    echo '"200":{';
    echo '"description":"An array of '.$GLOBALS['known'].'",';
    echo '"schema":{';
    echo '"type": "object",';
    echo '"properties": {';
    echo '"'.$GLOBALS['known'].'": {';
    echo '"type":"array",';
    echo '"items":{';
    echo '"type": "object",';
    echo '"properties": {';
    Known::getDescSwaggerColumnsForKnown(true);
    echo '}'; //properties
    echo '}'; //items
    echo '}'; //table
    echo '}'; //properties
    echo '}'; //schema
    echo '}'; //200
    echo '}'; //responses
    echo '}'; //get
    echo '}'; // /'.$GLOBALS['known']

    echo ',"/'.$GLOBALS['known'].'/{id}":{';
    echo '"get":{';
    echo '"tags":["'.$GLOBALS['known'].'"],';
    echo '"summary":"read",';
    echo '"parameters":[';
    echo '{';
    echo '"name":"id",';
    echo '"in":"path",';
    echo '"description":"Identifier for item.",';
    echo '"required":true,';
    echo '"type":"string"';
    echo '}';
    echo '],'; // parameters
    echo '"responses":{';
    echo '"200":{';
    echo '"description":"The requested item.",';
    echo '"schema":{';
    echo '"type": "object",';
    echo '"properties": {';
    Known::getDescSwaggerColumnsForKnown(false);
    echo '}'; //properties
    echo '}'; //schema
    echo '}'; //200
    echo '}'; //responses
    echo '}'; //get
    echo '}'; // /'.$GLOBALS['known'].'/{id}
    
    echo '}'; // path
    echo '}';
}
?>