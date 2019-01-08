<?php
class Bodies implements JsonSerializable{
    private $excludeColumn;
    private $limitToColumn;

    const FIELDS = "CPT_CORPS, NOM, BL_PLANETE, DEMIGRAND_AXE, CPTE_CORPS, NOM_ANGLAIS, EXCENTRICITE, DECOUV_QUI, DECOUV_QD, DES_TEMPO";
    const TABLE = "syssol_tab_donnees";

    protected $isExists;
    protected $isEnglish;
    protected $id;
    protected $name;
    protected $isPlanet;
    protected $semimajorAxis;
    protected $aroundPlanet;
    protected $englishName;
    protected $discoveredBy;
    protected $discoveryDate;
    protected $alternativeName;

    public function isExists(){
        return $this->isExists;
    }
    public function isEnglish(){
        return $this->isEnglish;
    }
    
    public function getIsPlanet(){
        return $this->isPlanet;
    }
    public function getSemimajorAxis(){
        return $this->semimajorAxis;
    }
    public function getOrbitalExcentricity(){
        return str_replace(",", ".", $this->orbitalExcentricity);
    }
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getAroundPlanet(){
        return $this->aroundPlanet;
    }
    public function getEnglishName(){
        return $this->englishName;
    }
    public function getDiscoveredBy(){
        return $this->discoveredBy;
    }
    public function getDiscoveryDate(){
        return $this->discoveryDate;
    }
    public function getAlternativeName(){
        return $this->alternativeName;
    }

    public function __construct($id, $limitToColumn, $excludeColumn){
        DBAccess::ConfigInit();

        $this->excludeColumn=$excludeColumn;
        $this->limitToColumn=$limitToColumn;

        $scriptsql = "SELECT ".self::FIELDS." FROM ".self::TABLE." WHERE CPT_CORPS = ?";
        $result = $GLOBALS['BDD']->prepare($scriptsql);
        $result->execute(array($id));
        $this->isExists = true;
        $this->isEnglish= false;

        if ($result->rowCount()==0) {
            $this->isExists = false;
            // on cherche la version anglaise
            $scriptsql = "SELECT ".self::FIELDS." FROM ".self::TABLE." WHERE NOM_ANGLAIS = ?";
            $result = $GLOBALS['BDD']->prepare($scriptsql);
            $result->execute(array($id));
            if ($result->rowCount()!=0) {
                $this->isEnglish=true;
            }
        }
        
        if ($this->isExists || $this->isEnglish){
            $donnees = $result->fetch();
            $this->id = $donnees["CPT_CORPS"];
            $this->name = $donnees["NOM"];
            $this->isPlanet = $donnees["BL_PLANETE"];
            $this->semimajorAxis = $donnees["DEMIGRAND_AXE"];
            $this->aroundPlanet = $donnees["CPTE_CORPS"];
            $this->englishName = $donnees["NOM_ANGLAIS"];
            $this->orbitalExcentricity = $donnees["EXCENTRICITE"];
            $this->discoveredBy = $donnees["DECOUV_QUI"];
            $this->discoveryDate = $donnees["DECOUV_QD"];
            $this->alternativeName = $donnees["DES_TEMPO"];
        }
        $result->closeCursor();

        if($GLOBALS['BDD']){
            $GLOBALS['BDD'] = null;
        }
    }
    public function jsonSerialize() {
        $allColumns= Bodies::getValidColumns($this->limitToColumn, $this->excludeColumn);

        if ($this->getId()!==null) {
            $result = [];

            $j=0; // pour les colonnes
            foreach ($allColumns as $column) {
                switch ($column->getColId()) {
                    case "id":                  $result+=array('id' => $this->getId());break;
                    case "name":                $result+=array('name' => $this->getName()); break;
                    case "isPlanet":            $result+=array('isPlanet' => $this->getIsPlanet());break;
                    case "moons":               $result+=array('moons' => 'null');break;
                    case "semimajorAxis":       $result+=array('semimajorAxis' => $this->getSemimajorAxis());break;
                    case "aroundPlanet":        $result+=array('aroundPlanet' => ($this->getAroundPlanet()<>""?array('planet' => $this->getAroundPlanet(), 'rel' => $GLOBALS['API_URL'].'/'.$this->getAroundPlanet()):null));break;
                    case "englishName":         $result+=array('englishName' => $this->getEnglishName()); break;
                    case "orbitalExcentricity": $result+=array('orbitalExcentricity' => $this->getOrbitalExcentricity());break;
                    case "discoveredBy":        $result+=array('discoveredBy' => $this->getDiscoveredBy()); break;
                    case "discoveryDate":       $result+=array('discoveryDate' => $this->getDiscoveryDate()); break;
                    case "alternativeName":     $result+=array('alternativeName' => $this->getAlternativeName()); break;

                }
                $j++;
            }
        }else{
            $result = null;
        }
        return $result;
    }

    public static function getSatellite($id, $brutData, $isRelPresent, $isMoonPresent){
        DBAccess::ConfigInit();

        $result="";
        $scriptsql = "SELECT CPT_CORPS, NOM FROM ".self::TABLE." WHERE CPTE_CORPS = ?";
        $statement = $GLOBALS['BDD']->prepare($scriptsql);
        $statement->execute(array($id));
        $j=0;
        $colCount = $statement->rowCount();
        if ($colCount>0){
            $result = '[';
            while($row=$statement->fetch(PDO::FETCH_ASSOC)){
                
                if (!$brutData) {
                    $result .= '{';
                    if ($isMoonPresent) {
                        $result .= '"moon":"' . $row["NOM"] . '"';
                    }
                    if ($isRelPresent) {
                        if ($isMoonPresent)  $result .= ',';
                        $result .= '"rel":"' . $GLOBALS['API_URL'] . '/' . $row["CPT_CORPS"] . '"';
                    }
                    $result .= '}';
                }else{
                    $result .= '[';
                    if ($isMoonPresent) {
                        $result .= '"' . $row["NOM"] . '"';
                    }
                    if ($isRelPresent) {
                        if ($isMoonPresent) $result .= ',';
                        $result .= '"' . $GLOBALS['API_URL'] . '/' . $row["CPT_CORPS"] . '"';
                    }
                    $result .= ']';
                }
                $j++;
                if ($j<$colCount) $result.=',';
            }
            $statement->closeCursor();
            $result .= ']';
        }else{
            $result = 'null';
        }
        if($GLOBALS['BDD']){
            $GLOBALS['BDD'] = null;
        }

        return $result;
    }

    public static function getAll($allColumns, $brutData, $orderings, $page, $filters, $isRelPresent, $isPlanetPresent, $isMoonPresent){
        DBAccess::ConfigInit();
        $scriptsql = "SELECT ".self::FIELDS." FROM ".self::TABLE."";

        $params = array();
        if (!empty($filters)) {
             addWhereFromFilters($filters[$GLOBALS['object']],$scriptsql,$params);
        }

        if (is_array($orderings)) {
            $scriptsql .= ' ORDER BY ';
            $scriptsql .= ' '.$orderings[0];
            $scriptsql .= ' '.$orderings[1];
            if (is_array($page)) {
                $scriptsql .= ' LIMIT '.$page[0];
                $scriptsql .= ', '.$page[1];
            }
        }

        $i=1;
        foreach($params as $para){
            $scriptsql = preg_replace('/\?/', $para, $scriptsql, 1);
            $i++;
        }

        $statement = $GLOBALS['BDD']->prepare($scriptsql);
        $statement->execute();
        $i=0; // pour les lignes
        $results="";
        $colCount=count($allColumns);

        while($row=$statement->fetch(PDO::FETCH_ASSOC)){
            $i++;
            $j=0; // pour les colonnes
            if ($brutData) {
                $result = '[';
            }else{
                $result = '{';
            }

            foreach ($allColumns as $column) {
                if (!$brutData) {
                    $result .= '"' . $column->getColId() . '":';
                }
                switch ($column->getColId()){
                    case 'aroundPlanet':
                        if ($row[$column->getColName()]!=''){
                            // c'est un satellite
                            if (!$brutData) {
                                $result .= '{';
                                if ($isPlanetPresent) {
                                    $result .= '"planet":"' . $row[$column->getColName()] . '"';
                                }
                                if ($isRelPresent) {
                                    if ($isPlanetPresent)  $result .= ',';
                                    $result .= '"rel":"' . $GLOBALS['API_URL'] . '/' . $row[$column->getColName()] . '"';
                                }
                                $result .= '}';
                            }else{
                                $result .= '[';
                                if ($isPlanetPresent) {
                                    $result .= '"' . $row[$column->getColName()] . '"';
                                }
                                if ($isRelPresent) {
                                    if ($isPlanetPresent) $result .= ',';
                                    $result .= '"' . $GLOBALS['API_URL'] . '/' . $row[$column->getColName()] . '"';
                                }
                                $result .= ']';
                            }
                        }else{
                            // ce n'est pas un satellite
                            $result .= 'null';
                        }
                        break;
                    case "moons":
                        if ($row["BL_PLANETE"] != 0){
                            $result .= Bodies::getSatellite($row["CPT_CORPS"], $brutData, $isRelPresent, $isMoonPresent);
                        }else{
                            $result .= "null";
                        }
                        break;
                    default:
                        switch ($column->getColType()) {
                            case "string":
                                $result .= '"' . $row[$column->getColName()] . '"';
                                break;
                            case "boolean":
                                $result .= ($row[$column->getColName()] == 0 ? 'false' : 'true');
                                break;
                            case "num":
                            case "integer":
                            case "number":
                                $result .= ($row[$column->getColName()] == '' ? '0' : str_replace(' ', '', $row[$column->getColName()]));
                                break;
                        }
                        break;

                }
                $j++;
                if ($j<$colCount) $result.=',';
            }

            if ($isRelPresent) {
                $result .= ',';
                if (!$brutData) {
                    $result .= '"rel":';
                }
                $result .= '"' . $GLOBALS['API_URL'] . '/' . $row["CPT_CORPS"] . '"';
            }
            if ($brutData) {
                $result .= ']';
            }else{
                $result .= '}';
            }
            if ($i!=$statement->rowCount()){
                $result.=',';
            }
            $results.=$result;
        }

        if($GLOBALS['BDD']){
            $GLOBALS['BDD'] = null;
        }
        return $results;
    }

    public static function getDescColumns(){
        $descColumns[]=new Column("id", "CPT_CORPS", "string");
        $descColumns[]=new Column("name", "NOM", "string");
        $descColumns[]=new Column("englishName", "NOM_ANGLAIS", "string");
        $descColumns[]=new Column("isPlanet", "BL_PLANETE", "boolean");
        $descColumns[]=new Column("moons", "", "");
        $descColumns[]=new Column("semimajorAxis", "DEMIGRAND_AXE", "number");
        $descColumns[]=new Column("orbitalExcentricity", "EXCENTRICITE", "number");
        $descColumns[]=new Column("aroundPlanet", "CPTE_CORPS", "string");
        $descColumns[]=new Column("discoveredBy", "DECOUV_QUI", "string");
        $descColumns[]=new Column("discoveryDate", "DECOUV_QD", "string");
        $descColumns[]=new Column("alternativeName", "DES_TEMPO", "string");

        return $descColumns ;
    }

    public static function getDescSwaggerColumns($rel){
        $columns = Bodies::getDescColumns();
        $i = 0;
        foreach ($columns as $col) {
            switch ($col->getColId()) {
                case 'aroundPlanet':
                    echo '"aroundPlanet":{"type":"object", "properties":{ "planet" :{"type":"string"}, "rel" :{"type":"string"}}}';
                    break;
                case 'moons':
                    echo '"moons":{"type":"array", "items":{"type":"object", "properties": {"moon" :{"type":"string"}, "rel" :{"type":"string"}}}}';
                    break;
                default :
                    echo '"' . $col->getColId() . '": {"type": "' . $col->getColType() . '"}';
                    break;
            }
            if ($i < count($columns) - 1) echo ',';
            $i++;
        }
        if ($rel) {
            echo ',"rel":{"type":"string"}';
        }
    }
    public static function getValidColumns($limitTo, $exclude){
        $allColumns = Bodies::getDescColumns();

        if (!empty($limitTo)) {
            // Limit to $limitTo columns
            // $allColumns to $limitedColumns
            $limitedColumns = [];
            $onlyCols = explode(',', $limitTo);
            foreach ($allColumns as $col) {
                $keep = false; // not keep by default
                foreach ($onlyCols as $id) {
                    if ($col->getColId() == $id) {
                        $keep = true; // keep column
                    }
                }
                if ($keep) {
                    $limitedColumns[] = $col;
                }
            }
        }else{
            foreach ($allColumns as $col) {
                $limitedColumns[] = $col;
            }
        }

        // Remove $exclude columns
        $cleanedColumns=[];
        $removedCols = explode(',',$exclude);
        foreach ($limitedColumns as $col){
            $remove=false; // dont remove by default
            foreach($removedCols as $id){
                if ($col->getColId()==$id){
                    $remove=true; // remove column
                }
            }

            if(!$remove) {
                $cleanedColumns[]=$col;
            }
        }
   /*   //DEBUG
        foreach ($limitedColumns as $col){
            echo $col->getColName();
        }
   */
        return $cleanedColumns;
    }
}
?>