<?php
define("LOADED_AS_MODULE","1");
$GLOBALS['bodies']='bodies';
$GLOBALS['known']='knowncount';
$GLOBALS['positions'] = 'positions';
$GLOBALS['DEBUG']=0;

$GLOBALS['API_VERSION']="3.0.0";

$GLOBALS['VirtualHost']="localhost";
if ($_SERVER['HTTP_HOST']==$GLOBALS['VirtualHost']){
    $GLOBALS['DEBUG']=1;
    $GLOBALS['API_URL_BODIES']='http://'.$_SERVER['HTTP_HOST'].'/rest/'.$GLOBALS['bodies'];
    $GLOBALS['API_URL_KNOWN']='http://'.$_SERVER['HTTP_HOST'].'/rest/'.$GLOBALS['known'];
    $GLOBALS['API_URL_POSITIONS']='http://'.$_SERVER['HTTP_HOST'].'/rest/'.$GLOBALS['positions'];
}else{
    $GLOBALS['API_URL_BODIES']='https://'.$_SERVER['HTTP_HOST'].'/rest/'.$GLOBALS['bodies'];
    $GLOBALS['API_URL_KNOWN']='https://'.$_SERVER['HTTP_HOST'].'/rest/'.$GLOBALS['known'];
    $GLOBALS['API_URL_POSITIONS']='https://'.$_SERVER['HTTP_HOST'].'/rest/'.$GLOBALS['positions'];
}

include_once('include/column.php');
include_once('include/dbaccess.php');
include_once('include/bodies.php');
include_once('include/known.php');
include_once('include/swagger.php');
include_once('include/function.php');

$request = isset($request)?$request:null;
$get = isset($get)?$get:null;
$method = isset($method)?$method:null;

if (!$request) {
    $request = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';
    if (!$request) {
        $request = isset($_SERVER['ORIG_PATH_INFO'])?$_SERVER['ORIG_PATH_INFO']:'';
        $request = $request!=$_SERVER['SCRIPT_NAME']?$request:'';
    }
}
if (!$method) {
    $method = $_SERVER['REQUEST_METHOD'];
}
if (!$get) {
    $get = $_GET;
}

$request = trim($request,'/');

$origin = true;
$allow_origin = '*';

$settings = compact('method', 'request', 'get', 'origin', 'allow_origin');

executeCommand($settings, $request, $method, $get);
?>