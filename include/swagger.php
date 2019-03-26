<?php
if (!defined("LOADED_AS_MODULE")) {
    die ("Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der directement &agrave; cette page...");
}
function swagger() {
    header('Content-Type: application/json; charset=utf-8');
    echo '{"swagger":"2.0",';
    echo '"info":{';
    echo '"title":"Solar System openData",';
    echo '"description":"API to get all datas about all solar system objects",';
    echo '"version":"'.$GLOBALS['API_VERSION'].'"';
    echo '},'; //info
    echo '"host":"' . $_SERVER['HTTP_HOST'] . '",';
    echo '"basePath":"' . $_SERVER['SCRIPT_NAME'] . '",';
    echo '"schemes":["http' . ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 's' : '') . '"],';
    echo '"consumes":["application/json"],';
    echo '"produces":["application/json"],';
    echo '"tags":[';
    echo '{';
    echo '"name":"'.$GLOBALS['object'].'",';
    echo '"description":"Object with all datas about the concerned body : orbitals, physicals and atmosphere"';
    echo '}';
    echo '],'; //tag
    echo '"paths":{';
    echo '"/'.$GLOBALS['object'].'":{';

    echo '"get":{';
    echo '"tags":["'.$GLOBALS['object'].'"],';
    echo '"summary":"List",';

    echoParameters();
    echo ',';
    echo '"responses":{';
    echo '"200":{';
    echo '"description":"An array of '.$GLOBALS['object'].'",';
    echo '"schema":{';
    echo '"type": "object",';
    echo '"properties": {';
    echo '"'.$GLOBALS['object'].'": {';
    echo '"type":"array",';
    echo '"items":{';
    echo '"type": "object",';
    echo '"properties": {';
    Bodies::getDescSwaggerColumns(true);
    echo '}'; //properties
    echo '}'; //items
    echo '}'; //table
    echo '}'; //properties
    echo '}'; //schema
    echo '}'; //200
    echo '}'; //responses

    echo '}'; //get
    // }
    echo '}'; //solarsystem


    echo ',"/'.$GLOBALS['object'].'/{id}":{';
    //     foreach ($table['id_actions'] as $j=>$action) {
    //          if ($j>0) echo ',';
    echo '"get":{';
    echo '"tags":["'.$GLOBALS['object'].'"],';
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
    //   if ($action['name']=='read') {
    echo '"responses":{';
    echo '"200":{';
    echo '"description":"The requested item.",';
    echo '"schema":{';
    echo '"type": "object",';
    echo '"properties": {';
    Bodies::getDescSwaggerColumns(false);
    echo '}'; //properties
    echo '}'; //schema
    echo '}'; //200
    echo '}'; //responses

    echo '}';
    echo '}';
    //}
    echo '}'; //table/id
    //}
    echo '}';
}
?>