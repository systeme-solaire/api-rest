<?php
define("LOADED_AS_MODULE","1");
$GLOBALS['object']='bodies';
$GLOBALS['DEBUG']=0;

$GLOBALS['API_VERSION']="1.0.0";

$GLOBALS['VirtualHost']="api";
if ($_SERVER['HTTP_HOST']==$GLOBALS['VirtualHost']){
    $GLOBALS['DEBUG']=1;
    $GLOBALS['API_URL']='http://'.$_SERVER['HTTP_HOST'].'/rest/'.$GLOBALS['object'];
}else{
    $GLOBALS['API_URL']='https://'.$_SERVER['HTTP_HOST'].'/rest/'.$GLOBALS['object'];
}

include_once('include/column.php');
include_once('include/dbaccess.php');
include_once('include/bodies.php');
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