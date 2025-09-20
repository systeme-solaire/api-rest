<?php
if (!defined("LOADED_AS_MODULE")) {
    die ("Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der directement &agrave; cette page...");
}
function checkApiKey() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        exitWith401("API key is missing. Ask your API key on https://api.le-systeme-solaire.net/generatekey.html. Add use it on a bearer token in your header Authorization description : `Authorization: Bearer <API_KEY_GUID>`");
    }

    if (preg_match('/Bearer\s+(\S+)/', $headers['Authorization'], $matches)) {
        $apiKey = $matches[1];
        DBAccess::ConfigInit();

        $result = $GLOBALS['BDD']->prepare("SELECT id FROM syssol_tab_api_keys WHERE api_key = ? AND is_validated = 1");
        $result->execute([$apiKey]);
        $row = $result->fetch();
        if ($row) {
            // Incrémente le compteur d’usage
            $update = $GLOBALS['BDD']->prepare("UPDATE syssol_tab_api_keys SET usage_count = usage_count + 1 WHERE id = ?");
            $update->execute([$row['id']]);
            $result->closeCursor();
            return true; // Accès autorisé
        }
        $result->closeCursor();
    }

    exitWith403("Invalid API key");
}

function isFilterPresent($filterName, $data, $exclude){
    $result=false;
    $onlyCols = explode(',', $data);
    if (empty($data) ||(!empty($data) && in_array($filterName,$onlyCols))){
        $result=true;
    }
    $excCols = explode(',', $exclude);
    if (!empty($exclude) && in_array($filterName,$excCols)){
        $result=false;
    }
    return $result;
}

function allowOrigin($allowOrigins) {
    if (isset($_SERVER['REQUEST_METHOD'])) {
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Origin: '.$allowOrigins);
    }
}

function executeCommand($settings, $request, $method, $get) {
    if ($method!='GET' && $method!='OPTIONS'&& $method!='HEAD' ){
        exitWith403('action');
    }
    header_remove("X-Powered-By"); 
    if ($settings['origin']) {
         allowOrigin($settings['allow_origin']);
    }
    if (!$settings['request']) {
        swagger();
    } else {
        $output = false;
        $api=parseRequestParameter($request, 'a-zA-Z0-9\-_');
        if ($api==$GLOBALS['bodies']){
            checkApiKey();
            /* si action bodies */
            $parameters = getParametersForBodies($settings,$request,$method,$get);
            switch($parameters['action']){
                case 'list': $output = listCommandForBodies($parameters); break;
                case 'read': $output = readCommandForBodies($parameters); break;
                case 'headers': $output = headersCommandForBodies(); break;
                default: $output = false;
            }
        }elseif ($api==$GLOBALS['known']){
            checkApiKey();
            /* si action knowncount */
            $parameters = getParametersForKnown($settings,$request,$method,$get);
            switch($parameters['action']){
                case 'list': $output = listCommandForKnown($parameters); break;
                case 'read': $output = readCommandForKnown($parameters); break;
                case 'headers': $output = headersCommandForKnown(); break;
                default: $output = false;
            }
        }else {
            exitWith404('entity');
        }
        if ($output!==false) {
            startOutput();
            echo json_encode($output);
        }
    }
}

function listCommandForBodies($parameters){
    extract($parameters);
    startOutput();

    $allColumns = Bodies::getValidColumns($data, $exclude);
    if (count($allColumns) == 0) {
        exitWith403("You need more data in data or less data in exclude");
    }

    $isRelPresent=isFilterPresent('rel', $data, $exclude);
    $isPlanetPresent=isFilterPresent('planet', $data, $exclude);
    $isMoonPresent=isFilterPresent('moon', $data, $exclude);
    $isMassValuePresent=isFilterPresent('massValue', $data, $exclude);
    $isMassExpPresent=isFilterPresent('massExponent', $data, $exclude);
    $isVolValuePresent=isFilterPresent('volValue', $data, $exclude);
    $isVolExpPresent=isFilterPresent('volExponent', $data, $exclude);

    echo '{"' . $GLOBALS['bodies'] . '":';
    if ($rowData){
        echo '{"data":';

        $columnString = '';
        $colCount = count($allColumns);
        if ($colCount > 0) {
            $i = 0;
            $columnString = '[';
            foreach ($allColumns as $column) {
                switch($column->getColId()){
                    case 'aroundPlanet':
                        $columnString .= '{"' . $column->getColId() . '":[';
                        if ($isPlanetPresent) {
                            $columnString .= '"planet"';
                        }
                        if ($isRelPresent) {
                            if ($isPlanetPresent) $columnString .= ',';
                            $columnString .= '"rel"';
                        }
                        $columnString .= ']}';
                        break;
                    case 'moons':
                        $columnString .= '{"' . $column->getColId() . '":[';
                        if ($isPlanetPresent) {
                            $columnString .= '"moon"';
                        }
                        if ($isRelPresent) {
                            if ($isPlanetPresent) $columnString .= ',';
                            $columnString .= '"rel"';
                        }
                        $columnString .= ']}';
                        break;
                    case 'mass':
                        $columnString .= '{"' . $column->getColId() . '":[';
                        if ($isMassValuePresent) {
                            $columnString .= '"massValue"';
                        }
                        if ($isMassExpPresent) {
                            if ($isMassValuePresent) $columnString .= ',';
                            $columnString .= '"massExponent"';
                        }
                        $columnString .= ']}';
                        break;
                    case 'vol':
                        $columnString .= '{"' . $column->getColId() . '":[';
                        if ($isVolValuePresent) {
                            $columnString .= '"volValue"';
                        }
                        if ($isVolExpPresent) {
                            if ($isVolValuePresent) $columnString .= ',';
                            $columnString .= '"volExponent"';
                        }
                        $columnString .= ']}';
                        break;
                    default:
                        $columnString .= '"' . $column->getColId() . '"';
                        break;
                }
                $i++;
                if ($i < $colCount) $columnString .= ',';
            }
            if ($isRelPresent) {
                $columnString .= ',"rel"';
            }
            $columnString .= ']';
        }
        echo $columnString;
        echo ',';
        echo '"records":';
    }

    echo '[';
    echo Bodies::getAll($allColumns, $rowData, $orderings, $page, $filters, $isRelPresent, $isPlanetPresent, $isMoonPresent, $isMassValuePresent, $isMassExpPresent, $isVolValuePresent, $isVolExpPresent);
    echo ']';
    if ($rowData){ echo '}';}
    echo '}';//fin

    return false;
}

function readCommandForBodies($parameters) {
    extract($parameters);

    $allColumns=Bodies::getValidColumns($data, $exclude);
    if (count($allColumns)==0){
        exitWith403("no column");
    }

    $isRelPresent=isFilterPresent('rel',$data, $exclude);
    $isPlanetPresent=isFilterPresent('planet', $data, $exclude);
    $isMoonPresent=isFilterPresent('moon', $data, $exclude);
    $isMassValuePresent=isFilterPresent('massValue', $data, $exclude);
    $isMassExpPresent=isFilterPresent('massExponent', $data, $exclude);
    $isVolValuePresent=isFilterPresent('volValue', $data, $exclude);
    $isVolExpPresent=isFilterPresent('volExponent', $data, $exclude);

    $object = new Bodies($key, $data, $exclude);
    if (!$object->isExists()) {
        //existe pas mais la VF existe
        if ($object->isEnglish()) {
            //redirect vers VF
            exitWith301($object->getId());
        }
        // n'existe vraiment pas
        exitWith404('entity');
    }else {
        startOutput();
        echo Bodies::getOne($object, $allColumns, $isRelPresent, $isMoonPresent, $isPlanetPresent, $isMassValuePresent, $isMassExpPresent, $isVolValuePresent, $isVolExpPresent);
    }
    return false;
}

function listCommandForKnown($parameters) {
    extract($parameters);
    startOutput();

    $allColumns = Known::getValidColumns();
    if (count($allColumns) == 0) {
        exitWith403("You need more data in data or less data in exclude");
    }

    echo '{"' . $GLOBALS['known'] . '":';
    if ($rowData){
        echo '{"data":';

        $columnString = '';
        $colCount = count($allColumns);
        if ($colCount > 0) {
            $i = 0;
            $columnString = '[';
            foreach ($allColumns as $column) {
                switch($column->getColId()){
                    default:
                        $columnString .= '"' . $column->getColId() . '"';
                        break;
                }
                $i++;
                if ($i < $colCount) $columnString .= ',';
            }
            $columnString .= ']';
        }
        echo $columnString;
        echo ',';
        echo '"records":';
    }

    echo '[';
    echo Known::getAll($allColumns, $rowData);
    echo ']';
    if ($rowData){ echo '}';}
    echo '}';//fin

    return false;
}

function readCommandForKnown($parameters) {
    extract($parameters);

    $allColumns=Known::getValidColumns();
    if (count($allColumns)==0){
        exitWith403("no column");
    }

    $object = new Known($key);
    if (!$object->isExists()) {
        // n'existe pas
        exitWith404('entity');
    }else {
        startOutput();
        echo Known::getOne($object,$allColumns);
    }
    return false;
}

function startOutput() {
    if (isset($_SERVER['REQUEST_METHOD'])) {
        header('Content-Type: application/json; charset=utf-8');
    }
}

function parseRequestParameter(&$request,$characters) {
    if ($request==='') return false;
    $pos = strpos($request,'/');
    $value = $pos?substr($request,0,$pos):$request;
    $request = $pos?substr($request,$pos+1):'';
    if (!$characters) return $value;
    return preg_replace("/[^$characters]/",'',$value);
}

function exitWith301($url){
    header("HTTP/1.1 301 Moved Permanently");
    header("location: ".$url);
    header("Connection: close");
    exit();
}

function exitWith404($type) {
    if (isset($_SERVER['REQUEST_METHOD'])) {
        header('Content-Type:',true,404);
        die("Not found ($type)");
    } else {
        throw new \Exception("Not found ($type)");
    }
}

function exitWith403($type) {
    if (isset($_SERVER['REQUEST_METHOD'])) {
        header('Content-Type:',true,403);
        die("Forbidden ($type)");
    } else {
        throw new \Exception("Forbidden ($type)");
    }
}

function exitWith401($type) {
    if (isset($_SERVER['REQUEST_METHOD'])) {
        header('Content-Type:',true,401);
        die("Unauthorized ($type)");
    } else {
        throw new \Exception("Unauthorized ($type)");
    }
}

function exitWith400($type) {
    if (isset($_SERVER['REQUEST_METHOD'])) {
        header('Content-Type:',true,400);
        die("The request could not be understood by the server due to malformed syntax. The client SHOULD NOT repeat the request without modifications. ($type)");
    } else {
        throw new \Exception("Bad request ($type)");
    }
}

function exitWith422($object) {
    if (isset($_SERVER['REQUEST_METHOD'])) {
        header('Content-Type:',true,422);
        die(json_encode($object));
    } else {
        throw new \Exception(json_encode($object));
    }
}

function parseGetParameter($get,$name,$characters) {
    $value = isset($get[$name])?$get[$name]:false;
    return $characters?preg_replace("/[^$characters]/",'',$value):$value;
}

function parseGetParameterArray($get,$name,$characters) {
    $values = isset($get[$name])?$get[$name]:false;
    if (!is_array($values)) $values = array($values);
    if ($characters) {
        foreach ($values as &$value) {
            $value = preg_replace("/[^$characters]/",'',$value);
        }
    }
    return $values;
}

function mapMethodToAction($method,$key) {
    switch ($method) {
        case 'OPTIONS': case 'HEAD': return 'headers';break;
        case 'GET': return ($key===false)?'list':'read';break;
        case 'PUT': return 'update';break;
        case 'POST': return 'create';break;
        case 'DELETE': return 'delete';break;
        case 'PATCH': return 'increment';break;
        default: exitWith404('method');
    }
    return false;
}

function headersCommandForBodies() {
    $headers = array();
    $headers[]='Access-Control-Allow-Headers: Content-Type, X-XSRF-TOKEN';
    $headers[]='Access-Control-Allow-Methods: OPTIONS, GET, HEAD';
    $headers[]='Access-Control-Allow-Credentials: true';
    $headers[]='Access-Control-Max-Age: 1728000';

    foreach ($headers as $header) header($header);

    startOutput();
    echo '{';
    echo '"header":{';
    echo '"Access-Control-Allow-Headers": ["Content-Type", "X-XSRF-TOKEN"],';
    echo '"Access-Control-Allow-Methods": ["OPTIONS", "GET", "HEAD"],';
    echo '"Access-Control-Allow-Credentials": true,';
    echo '"Access-Control-Max-Age": 1728000)';
    echo '},';

    echo '"GET":{';
        echo '"parameters":[';
        echo '{';
        echo '"name":"data",';
        echo '"in":"query",';
        echo '"description":"The data you want to retrieve (comma separated). Example: id,semimajorAxis,isPlanet.",';
        echo '"required":false,';
        echo '"type":"string"';
        echo '},';
        echo '{';
        echo '"name":"exclude",';
        echo '"in":"query",';
        echo '"description":"One or more data you want to exclude (comma separated). Example: id,isPlanet.",';
        echo '"required":false,';
        echo '"type":"string"';
        echo '},';
        echo '{';
        echo '"name":"order",';
        echo '"in":"query",';
        echo '"description":"A data you want to sort on and the sort direction (comma separated). Example: id,desc. Only one data is authorized.",';
        echo '"required":false,';
        echo '"type":"string"';
        echo '},';
        echo '{';
        echo '"name":"page",';
        echo '"in":"query",';
        echo '"description":"Page number (number>=1) and page size (size>=1 and 20 by default) (comma separated). NB: You cannot use \"page\" without \"order\"! Example: 1,10.",';
        echo '"required":false,';
        echo '"type":"string"';
        echo '},';
        echo '{';
        echo '"name":"rowData",';
        echo '"in":"query",';
        echo '"description":"Transform the object in records. NB: This can also be done client-side in JavaScript!",';
        echo '"required":false,';
        echo '"type":"boolean"';
        echo '}';
        echo ',';
        echo '{';
        echo '"name":"filter[]",';
        echo '"in":"query",';
        echo '"description":"Filters to be applied. Each filter consists of a data, an operator and a value (comma separated). Example: id,eq,mars. Accepted operators are : cs (like) - sw (start with) - ew (end with) - eq (equal) - lt (less than) - le (less or equal than) - ge (greater or equal than) - gt (greater than) - bt (between). And all opposites operators : ncs - nsw - new - neq - nlt - nle - nge - ngt - nbt. Note : if anyone filter is invalid, all filters will be ignore.",';
        echo '"required":false,';
        echo '"type":"array",';
        echo '"collectionFormat":"multi",';
        echo '"items":{"type":"string"}';
        echo '},';
        echo '{';
        echo '"name":"satisfy",';
        echo '"in":"query",';
        echo '"description":"Should all filters match (default)? Or any?",';
        echo '"required":false,';
        echo '"type":"string",';
        echo '"enum":["any"]';
        echo '}';
        echo ']'; 
    echo '}';
    echo '}';
    return false;
}

function headersCommandForKnown() {
    $headers = array();
    $headers[]='Access-Control-Allow-Headers: Content-Type, X-XSRF-TOKEN';
    $headers[]='Access-Control-Allow-Methods: OPTIONS, GET, HEAD';
    $headers[]='Access-Control-Allow-Credentials: true';
    $headers[]='Access-Control-Max-Age: 1728000';

    foreach ($headers as $header) header($header);

    startOutput();
    echo '{';
    echo '"header":{';
    echo '"Access-Control-Allow-Headers": ["Content-Type", "X-XSRF-TOKEN"],';
    echo '"Access-Control-Allow-Methods": ["OPTIONS", "GET", "HEAD"],';
    echo '"Access-Control-Allow-Credentials": true,';
    echo '"Access-Control-Max-Age": 1728000)';
    echo '},';

    echo '"GET":{';
        echo '"parameters":[';
        echo '{';
        echo '"name":"rowData",';
        echo '"in":"query",';
        echo '"description":"Transform the object in records. NB: This can also be done client-side in JavaScript!",';
        echo '"required":false,';
        echo '"type":"boolean"';
        echo '}';
        echo ']'; 
    echo '}';
    echo '}';
    return false;
}

function processOrderingsParameter($orderings) {
    if (!$orderings) return false;

    foreach ($orderings as  $order) {

        $order = explode(",",$order);

        if (count($order)<2) $order[1]='ASC';

        if (empty($order[1])) $order[1]='ASC';
        if (!strlen($order[0])) return false;

        //convert id to column name
        $descCol=Bodies::getSQLColumns();
        $find=false;
        foreach($descCol as $col) {
            if ($col->getColId() == $order[0]) {
                $find=true;
                $orderName=$col->getColName();
            }
        }
        if ($find){
            $order[0]=$orderName;
        }

        $direction = strtoupper($order[1]);
        if (in_array($direction,array('ASC','DESC'))) {
            $order[1] = $direction;
        }
    }
    if (!$find){
        $order=null;
    }

    return $order;
}

function processPageParameter($page) {
    if (!$page) return false;
    $page = explode(',',$page);
    if (!is_numeric($page[0])) return false;
    if (count($page)<2) $page[1]=20;
    if (!is_numeric($page[1])) return false;
    $page[0] = ($page[0]-1)*$page[1];

    if ($page[0]<0) $page[0]=0;
    if ($page[1]<=0) $page[1]=1;
    return $page;
}

function processFiltersParameter($tables,$satisfy,$filterStrings) {
    $filters = array();
    addFilters($filters,$tables[0],$satisfy,$filterStrings);
    return $filters;
}

function addFilters(&$filters,$table,$satisfy,$filterStrings) {
    if ($filterStrings) {
        for ($i=0;$i<count($filterStrings);$i++) {
            $parts = explode(',',$filterStrings[$i],3);
            if (count($parts)>=2) {
                if (strpos($parts[0],'.')) list($t,$f) = explode('.',$parts[0],2);
                else list($t,$f) = array($table,$parts[0]);
                $comparator = $parts[1];
                $value = isset($parts[2])?$parts[2]:null;
                $and = isset($satisfy[$t])?$satisfy[$t]:'and';
                addFilter($filters,$t,$and,$f,$comparator,$value);
            }
        }
    }
}

function addFilter(&$filters,$table,$and,$field,$comparator,$value) {
    if (!isset($filters[$table])) $filters[$table] = array();
    if (!isset($filters[$table][$and])) $filters[$table][$and] = array();
    $filter = convertFilter($field,$comparator,$value);
    if ($filter) $filters[$table][$and][] = $filter;
}

function convertFilter($field, $comparator, $value) {
    // convert boolean value
    if ($value=='true'){$value='-1';}
    if ($value=='false'){$value='0';}
    // default behavior
    $comparator = strtolower($comparator);
    if ($comparator[0]!='n') {
        if (strlen($comparator)==2) {
            switch ($comparator) {
                case 'cs': return array('? LIKE ?',$field,'%'.addcslashes($value,'%_').'%');
                case 'sw': return array('? LIKE ?',$field,addcslashes($value,'%_').'%');
                case 'ew': return array('? LIKE ?',$field,'%'.addcslashes($value,'%_'));
                case 'eq': return array('? = ?',$field,$value);
                case 'lt': return array('? < ?',$field,$value);
                case 'le': return array('? <= ?',$field,$value);
                case 'ge': return array('? >= ?',$field,$value);
                case 'gt': return array('? > ?',$field,$value);
                case 'bt':
                    $v = explode(',',$value);
                    if (count($v)<2) return false;
                    return array('? BETWEEN ? AND ?',$field,$v[0],$v[1]);
            }
        }
    } else {
        if (strlen($comparator)==3) {
            switch ($comparator) {
                case 'ncs': return array('? NOT LIKE ?',$field,'%'.addcslashes($value,'%_').'%');
                case 'nsw': return array('? NOT LIKE ?',$field,addcslashes($value,'%_').'%');
                case 'new': return array('? NOT LIKE ?',$field,'%'.addcslashes($value,'%_'));
                case 'neq': return array('? <> ?',$field,$value);
                case 'nlt': return array('? >= ?',$field,$value);
                case 'nle': return array('? > ?',$field,$value);
                case 'nge': return array('? < ?',$field,$value);
                case 'ngt': return array('? <= ?',$field,$value);
                case 'nbt':
                    $v = explode(',',$value);
                    if (count($v)<2) return false;
                    return array('? NOT BETWEEN ? AND ?',$field,$v[0],$v[1]);
            }
        }
    }
    return false;
}

function addWhereFromFilters($filters,&$sql,&$params) {
    $first = true;
    $descCol=Bodies::getSQLColumns();
            
    if (isset($filters['or'])) {
        //validation des filres
        foreach ($filters['or'] as $i=>$filter) {
            $isExists=false;
            foreach($descCol as $col) {
                if ($filter[1]==$col->getColId()){
                    $isExists=true;
                }
            }
            if ($isExists) {
                $allExist=true;
            } else {
                $allExist = false;
                break;
            }
        }
        if (!$allExist) return;

        //application des filtres
        $first = false;
        $sql .= ' WHERE (';
        foreach ($filters['or'] as $i=>$filter) {
            $find=false;
            $sql .= $i==0?'':' OR ';
            $sql .= $filter[0];

            for ($j=1;$j<count($filter);$j++) {
                if ($j==1){
                    //convert id to column name
                    foreach($descCol as $col) {
                        if ($col->getColId() == $filter[1]) {
                            $find=true;
                            $orderName=$col->getColName();
                        }
                    }
                    if ($find){
                        $params[]=$orderName;
                    }

                }else {
                    $params[] = "'".$filter[$j]."'";
                }
            }

        }
        $sql .= ')';
    }
    if (isset($filters['and'])) {
        //validation des filres
        foreach ($filters['and'] as $i=>$filter) {
            $isExists=false;
            foreach($descCol as $col) {
                if ($filter[1]==$col->getColId()){
                    $isExists=true;
                }
            }
            if ($isExists) {
                $allExist=true;
            } else {
                $allExist = false;
                break;
            }
        }
        if (!$allExist) return;

        //application des filtres
        foreach ($filters['and'] as $i=>$filter) {
            $sql .= $first?' WHERE ':' AND ';
            $sql .= $filter[0];

            $find=false;
            for ($j=1;$j<count($filter);$j++) {
                if ($j==1){
                    //convert id to column name
                    foreach($descCol as $col) {
                        if ($col->getColId() == $filter[1]) {
                            $find=true;
                            $orderName=$col->getColName();
                            $colType=$col->getColType();
                        }
                    }
                    if ($find){
                        $params[]=$orderName;
                    }
                }else {
                    $params[] = "'".$filter[$j]."'";
                }
            }
            $first = false;
        }
    }
}

function processSatisfyParameter($tables,$satisfyString) {
    $satisfy = array();
    foreach (explode(',',$satisfyString) as $str) {
        if (strpos($str,'.')) list($t,$s) = explode('.',$str,2);
        else list($t,$s) = array($tables[0],$str);
        $and = ($s && strtolower($s)=='any')?'or':'and';
        $satisfy[$t] = $and;
    }
    return $satisfy;
}

function getParametersForBodies($settings,$request,$method,$get) {
    extract($settings);

    $query     = parseRequestParameter($request, 'a-zA-Z0-9\-_');  // /bodies
    $key       = parseRequestParameter($request, 'a-zA-Z0-9\-_,'); // auto-increment or uuid
    $action    = mapMethodToAction($method,$key);
    $exclude   = parseGetParameter($get, 'exclude', 'a-zA-Z0-9\-_,.*');
    $orderings = parseGetParameterArray($get, 'order', 'a-zA-Z0-9\-_,');
    $page      = parseGetParameter($get, 'page', '0-9,');
    $rowData   = parseGetParameter($get, 'rowData', 't1');
    $data      = parseGetParameter($get, 'data', 'a-zA-Z0-9\-_,.*');
    $filters   = parseGetParameterArray($get, 'filter', false);
    $satisfy   = parseGetParameter($get, 'satisfy', 'a-zA-Z0-9\-_,.');

    $tables[]=$GLOBALS['bodies'];
    $satisfy   = processSatisfyParameter($tables,$satisfy);
    $filters   = processFiltersParameter($tables,$satisfy,$filters);

    $orderings = processOrderingsParameter($orderings);
    $page      = processPageParameter($page);

    return compact('action','tables','key','page','filters','orderings','rowData','exclude','data');
}

function getParametersForKnown($settings,$request,$method,$get) {
    extract($settings);

    $query     = parseRequestParameter($request, 'a-zA-Z0-9\-_');  // /knowncount
    $key       = parseRequestParameter($request, 'a-zA-Z0-9\-_,'); // auto-increment or uuid
    $action    = mapMethodToAction($method,$key);
    $rowData   = parseGetParameter($get, 'rowData', 't1');

    return compact('action','key','rowData');
}
?>