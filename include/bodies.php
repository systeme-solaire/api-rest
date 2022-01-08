<?php
if (!defined("LOADED_AS_MODULE")) {
    die ("Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der directement &agrave; cette page...");
}

/* Objet du système solaire */
class Bodies implements JsonSerializable{
    private $excludeColumn;
    private $limitToColumn;

    const FIELDS = "CPT_CORPS, NOM, BL_PLANETE, CPTE_CORPS, NOM_ANGLAIS, DEMIGRAND_AXE, DECOUV_QUI, DECOUV_QD, DES_TEMPO, mass_val, mass_unit, density, gravity, escape, vol_val, vol_unit, perihelion, aphelion, eccentricity, inclination, equa_radius, mean_radius, polar_radius, flattening, sideral_orbit, sideral_rotation, dimension, INCLINAISON_AXE, avg_temp, main_anomaly, arg_periapsis, long_asc_node, BL_PLANETE, BL_PLANETE_NAINE, BL_SATELLITE, BL_ASTEROIDE, BL_SAT_ASTERO, BL_TNO, BL_SAT_TNO, BL_COMETE ";
    const TABLE = "syssol_tab_donnees";

    protected $isExists;
    protected $isEnglish;
    protected $id;
    protected $name;
    protected $isPlanet;
    protected $semimajorAxis;
    protected $perihelion;
    protected $aphelion;
    protected $eccentricity; 
    protected $inclination; 
    protected $density; 
    protected $gravity; 
    protected $escape; 
    protected $meanRadius; 
    protected $equaRadius; 
    protected $polarRadius; 
    protected $flattening; 
    protected $dimension; 
    protected $aroundPlanet;
    protected $sideralOrbit; 
    protected $sideralRotation; 
    protected $englishName;
    protected $discoveredBy;
    protected $discoveryDate;
    protected $alternativeName;
    protected $massVal;
    protected $massExponent;
    protected $volVal;
    protected $volExponent;
    protected $axialTilt;
    protected $avgTemp;
    protected $mainAnomaly;
    protected $argPeriapsis;
    protected $longAscNode;
    protected $bodyType;

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
    public function getMassVal(){
        return $this->massVal;
    }
    public function getMassExponent(){
        return $this->massExponent;
    }
    public function getPerihelion(){
        return $this->perihelion;
    }
    public function getAphelion(){
        return $this->aphelion;
    }
    public function getEccentricity(){
        return $this->eccentricity;
    }
    public function getInclination(){
        return $this->inclination;
    }
    public function getDensity(){
        return $this->density;
    }
    public function getGravity(){
        return $this->gravity;
    }
    public function getEscape(){
        return $this->escape;
    }
    public function getMeanRadius(){
        return $this->meanRadius;
    }
    public function getEquaRadius(){
        return $this->equaRadius;
    }
    public function getPolarRadius(){
        return $this->polarRadius;
    }
    public function getFlattening(){
        return $this->flattening;
    }
    public function getDimension(){
        return $this->dimension;
    }
    public function getSideralOrbit(){
        return $this->sideralOrbit;
    }
    public function getSideralRotation(){
        return $this->sideralRotation;
    }
    public function getVolVal(){
        return $this->volVal;
    }
    public function getVolExponent(){
        return $this->volExponent;
    }
    public function getAxialTilt(){
        return $this->axialTilt;
    }
    public function getMassUnit(){
        return $this->massExponent;
    }
    public function getVolUnit(){
        return $this->volExponent;
    }
    public function getAvg_temp(){
        return $this->avgTemp;
    }
    public function getMain_anomaly(){
        return $this->mainAnomaly;
    }
    public function getArg_periapsis(){
        return $this->argPeriapsis;
    }
    public function getLong_asc_node(){
        return $this->longAscNode;
    }
    public function getBodyType(){
        return $this->bodyType;
    }
    
    public function __construct($id, $limitToColumn, $excludeColumn){
        DBAccess::ConfigInit();

        $this->excludeColumn=$excludeColumn;
        $this->limitToColumn=$limitToColumn;

        $scriptsql = "SELECT ".self::FIELDS." FROM ".self::TABLE." WHERE CPT_CORPS = ? AND API = 1";
        $result = $GLOBALS['BDD']->prepare($scriptsql);
        $result->execute(array($id));
        $this->isExists = true;
        $this->isEnglish= false;

        if ($result->rowCount()==0) {
            $this->isExists = false;
            // on cherche la version anglaise
            $scriptsql = "SELECT ".self::FIELDS." FROM ".self::TABLE." WHERE NOM_ANGLAIS = ? AND API = 1";
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
            $this->eccentricity = $donnees["eccentricity"];
            $this->discoveredBy = $donnees["DECOUV_QUI"];
            $this->discoveryDate = $donnees["DECOUV_QD"];
            $this->alternativeName = $donnees["DES_TEMPO"];
            $this->massVal = $donnees["mass_val"];
            $this->massExponent = $donnees["mass_unit"];
            $this->volVal = $donnees["vol_val"];
            $this->volExponent = $donnees["vol_unit"];
            $this->perihelion = $donnees["perihelion"];
            $this->aphelion = $donnees["aphelion"];
            $this->inclination = $donnees["inclination"]; 
            $this->density = $donnees["density"]; 
            $this->gravity = $donnees["gravity"]; 
            $this->escape = $donnees["escape"]; 
            $this->meanRadius = $donnees["mean_radius"]; 
            $this->equaRadius = $donnees["equa_radius"]; 
            $this->polarRadius = $donnees["polar_radius"]; 
            $this->flattening = $donnees["flattening"]; 
            $this->dimension = $donnees["dimension"]; 
            $this->sideralOrbit = $donnees["sideral_orbit"]; 
            $this->sideralRotation = $donnees["sideral_rotation"]; 
            $this->axialTilt = $donnees["INCLINAISON_AXE"];
            $this->avgTemp = $donnees["avg_temp"];
            $this->mainAnomaly = $donnees["main_anomaly"];
            $this->argPeriapsis = $donnees["arg_periapsis"];
            $this->longAscNode = $donnees["long_asc_node"];
            if ($donnees["BL_PLANETE"]=='-1'){
                $this->bodyType = 'Planet';
            }elseif ($donnees["BL_PLANETE_NAINE"]=='-1'){
                $this->bodyType = 'Dwarf Planet';
            }elseif ($donnees["BL_SATELLITE"] =='-1' or $donnees["BL_SAT_ASTERO"]=='-1' or $donnees["BL_SAT_TNO"]=='-1'){
                $this->bodyType = 'Moon';
            }elseif ($donnees["BL_ASTEROIDE"] =='-1' or $donnees["BL_TNO"]=='-1'){
                $this->bodyType = 'Asteroid';
            }elseif ($donnees["BL_COMETE"]=='-1'){
                $this->bodyType = 'Comet';
            }elseif ($donnees["CPT_CORPS"]=='soleil'){
                $this->bodyType = 'Star';
            }else{
                $this->bodyType = '';
            }
            
        }
        $result->closeCursor();

        if($GLOBALS['BDD']){
            $GLOBALS['BDD'] = null;
        }
    }

    /* Serialization de l'objet */
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
                    case "aroundPlanet":        $result+=array('aroundPlanet' => ($this->getAroundPlanet()<>""?array('planet' => $this->getAroundPlanet(), 'rel' => $GLOBALS['API_URL_BODIES'].'/'.$this->getAroundPlanet()):null));break;
                    case "englishName":         $result+=array('englishName' => $this->getEnglishName()); break;
                    case "eccentricity":        $result+=array('eccentricity' => $this->getEccentricity());break;
                    case "discoveredBy":        $result+=array('discoveredBy' => $this->getDiscoveredBy()); break;
                    case "discoveryDate":       $result+=array('discoveryDate' => $this->getDiscoveryDate()); break;
                    case "alternativeName":     $result+=array('alternativeName' => $this->getAlternativeName()); break;
                    case "mass":                $result+=array('mass' => ($this->getMassVal()<>0?array('massValue' => $this->getMassVal(), 'massExponent' => $this->getMassUnit()):null));break;
                    case "vol":                 $result+=array('vol' => ($this->getVolVal()<>0?array('volValue' => $this->getVolVal(), 'volExponent' => $this->getVolUnit()):null));break;
                    case "perihelion":          $result+=array('perihelion' => $this->getPerihelion());break;
                    case "aphelion":            $result+=array('aphelion' => $this->getAphelion());break; 
                    case "inclination":         $result+=array('inclination' => $this->getInclination());break;
                    case "density":             $result+=array('density' => $this->getDensity());break; 
                    case "gravity":             $result+=array('gravity' => $this->getGravity());break; 
                    case "escape":              $result+=array('escape' => $this->getEscape());break; 
                    case "meanRadius":          $result+=array('meanRadius' => $this->getMeanRadius());break; 
                    case "equaRadius":          $result+=array('equaRadius' => $this->getEquaRadius());break;
                    case "polarRadius":         $result+=array('polarRadius' => $this->getPolarRadius());break; 
                    case "flattening":          $result+=array('flattening' => $this->getFlattening());break; 
                    case "dimension":           $result+=array('dimension' => $this->getDimension());break; 
                    case "sideralOrbit":        $result+=array('sideralOrbit' => $this->getSideralOrbit());break;
                    case "sideralRotation":     $result+=array('sideralRotation' => $this->getSideralRotation());break; 
                    case "axialTilt":           $result+=array('axialTilt' => $this->getAxialTilt());break;
                    case "avgTemp":             $result+=array('avgTemp' => $this->getAvg_temp());break;
                    case "mainAnomaly":         $result+=array('mainAnomaly' => $this->getMain_anomaly());break;
                    case "argPeriapsis":        $result+=array('argPeriapsis' => $this->getArg_periapsis());break;
                    case "longAscNode":         $result+=array('longAscNode' => $this->getLong_asc_node());break;
                    case "bodyType":            $result+=array('bodyType' => $this->getBodyType());break;
                }
                $j++;
            }
        }else{
            $result = null;
        }
        return $result;
    }

    /* Obtenir les satellites d'un astre */
    public static function getSatellite($id, $rowData, $isRelPresent, $isMoonPresent){
        DBAccess::ConfigInit();

        $result="";
        $scriptsql = "SELECT CPT_CORPS, NOM FROM ".self::TABLE." WHERE CPTE_CORPS = ? AND API = 1";
        $statement = $GLOBALS['BDD']->prepare($scriptsql);
        $statement->execute(array($id));
        $j=0;
        $colCount = $statement->rowCount();
        if ($colCount>0){
            $result = '[';
            while($row=$statement->fetch(PDO::FETCH_ASSOC)){
                
                if (!$rowData) {
                    $result .= '{';
                    if ($isMoonPresent) {
                        $result .= '"moon":"' . $row["NOM"] . '"';
                    }
                    if ($isRelPresent) {
                        if ($isMoonPresent)  $result .= ',';
                        $result .= '"rel":"' . $GLOBALS['API_URL_BODIES'] . '/' . $row["CPT_CORPS"] . '"';
                    }
                    $result .= '}';
                }else{
                    $result .= '[';
                    if ($isMoonPresent) {
                        $result .= '"' . $row["NOM"] . '"';
                    }
                    if ($isRelPresent) {
                        if ($isMoonPresent) $result .= ',';
                        $result .= '"' . $GLOBALS['API_URL_BODIES'] . '/' . $row["CPT_CORPS"] . '"';
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

    /* renvoie le JSON d'un astre */
    public static function getOne($object, $allColumns, $isRelPresent, $isMoonPresent, $isPlanetPresent, $isMassValuePresent, $isMassExpPresent, $isVolValuePresent, $isVolExpPresent){
        $result='{';
        if ($object->getId()!==null) {
            $j=0; // pour les colonnes
            foreach ($allColumns as $column) {
                switch ($column->getColId()) {
                    case "id":
                        $result .= '"id":"' . $object->getId() . '"';
                        break;
                    case "name":
                        $result .= '"name":"' . $object->getName() . '"';
                        break;
                    case "englishName":
                        $result .= '"englishName":"' . $object->getEnglishName() . '"';
                        break;
                    case "isPlanet":
                        $result .= '"isPlanet":' . ($object->getIsPlanet() == 0 ? 'false' : 'true') . '';
                        break;
                    case "moons":
                        $result .= '"moons":'.Bodies::getSatellite($object->getId(), false, $isRelPresent, $isMoonPresent);
                        break;
                    case "semimajorAxis":
                        $result .= '"semimajorAxis":' . ($object->getSemimajorAxis() != 0 ? $object->getSemimajorAxis() : 0) . '';
                        break;
                    case "perihelion":
                        $result .= '"perihelion":' . ($object->getPerihelion() != 0 ? $object->getPerihelion() : 0) . '';
                        break;
                    case "aphelion":
                        $result .= '"aphelion":' . ($object->getAphelion() != 0 ? $object->getAphelion() : 0) . '';
                        break;
                    case "eccentricity":
                        $result .= '"eccentricity":' . ($object->getEccentricity() != 0 ? $object->getEccentricity() : 0) . '';
                        break;
                    case "inclination":
                        $result .= '"inclination":' . ($object->getInclination() != 0 ? $object->getInclination() : 0) . '';         
                        break;
                    case "density":             
                        $result .= '"density":' . ($object->getDensity() != 0 ? $object->getDensity() : 0) . '';
                        break; 
                    case "gravity":            
                        $result .= '"gravity":' . ($object->getGravity() != 0 ? $object->getGravity() : 0) . '';
                        break; 
                    case "escape":              
                        $result .= '"escape":' . ($object->getEscape() != 0 ? $object->getEscape() : 0) . '';
                        break; 
                    case "meanRadius":          
                        $result .= '"meanRadius":' . ($object->getMeanRadius() != 0 ? $object->getMeanRadius() : 0) . '';
                        break; 
                    case "equaRadius":          
                        $result .= '"equaRadius":' . ($object->getEquaRadius() != 0 ? $object->getEquaRadius() : 0) . '';
                        break;
                    case "polarRadius":         
                        $result .= '"polarRadius":' . ($object->getPolarRadius() != 0 ? $object->getPolarRadius() : 0) . '';
                        break; 
                    case "flattening":          
                        $result .= '"flattening":' . ($object->getFlattening() != 0 ? $object->getFlattening() : 0) . '';
                        break; 
                    case "axialTilt":          
                        $result .= '"axialTilt":' . ($object->getAxialTilt() != 0 ? $object->getAxialTilt() : 0) . '';
                        break; 
                    case "avgTemp":          
                        $result .= '"avgTemp":' . ($object->getAvg_temp() != 0 ? $object->getAvg_temp() : 0) . '';
                        break; 
                    case "mainAnomaly":          
                        $result .= '"mainAnomaly":' . ($object->getMain_anomaly() != 0 ? $object->getMain_anomaly() : 0) . '';
                        break; 
                    case "argPeriapsis":          
                        $result .= '"argPeriapsis":' . ($object->getArg_periapsis() != 0 ? $object->getArg_periapsis() : 0) . '';
                        break; 
                    case "longAscNode":          
                        $result .= '"longAscNode":' . ($object->getLong_asc_node() != 0 ? $object->getLong_asc_node() : 0) . '';
                        break; 
                    case "dimension":          
                        $result .= '"dimension":"'.$object->getDimension(). '"';
                        break; 
                    case "sideralOrbit":       
                        $result .= '"sideralOrbit":' . ($object->getSideralOrbit() != 0 ? $object->getSideralOrbit() : 0) . '';
                        break;
                    case "sideralRotation":     
                        $result .= '"sideralRotation":' . ($object->getSideralRotation() != 0 ? $object->getSideralRotation() : 0) . '';
                        break; 
                    case "mass":
                        $result .= '"mass":';
                        if ($object->getMassVal() <> 0) {
                            $result .= '{';
                            if ($isMassValuePresent) {
                                $result .= '"massValue":' . $object->getMassVal() ;
                            }
                            if ($isMassExpPresent) {
                                if ($isMassValuePresent) $result .= ', ';
                                $result .= '"massExponent":' . $object->getMassExponent();
                            }
                            $result .= '}';
                        } else {
                            $result .= 'null';
                        }
                        break;
                    case "vol":
                        $result .= '"vol":';
                        if ($object->getMassVal() <> 0) {
                            $result .= '{';
                            if ($isVolValuePresent) {
                                $result .= '"volValue":' . $object->getVolVal() ;
                            }
                            if ($isVolExpPresent) {
                                if ($isVolValuePresent) $result .= ', ';
                                $result .= '"volExponent":' . $object->getVolExponent();
                            }
                            $result .= '}';
                        } else {
                            $result .= 'null';
                        }
                        break;
                    case "aroundPlanet":
                            $result .= '"aroundPlanet":';
                            if ($object->getAroundPlanet() <> "") {
                                $result .= '{';
                                if ($isPlanetPresent) {
                                    $result .= '"planet":"' . $object->getAroundPlanet() . '"';
                                }
                                if ($isRelPresent) {
                                    if ($isPlanetPresent) $result .= ', ';
                                    $result .= '"rel":"' . $GLOBALS['API_URL_BODIES'] . '/' . $object->getAroundPlanet() . '"';
                                }
                                $result .= '}';
                            } else {
                                $result .= 'null';
                            }
                        break;
                    case "discoveredBy":
                        $result .= '"discoveredBy":"' . $object->getDiscoveredBy() . '"';
                        break;
                    case "discoveryDate":
                        $result .= '"discoveryDate":"' . $object->getDiscoveryDate() . '"';
                        break;
                    case "alternativeName":
                        $result .= '"alternativeName":"' . $object->getAlternativeName() . '"';
                        break;
                    case "bodyType":
                        $result .= '"bodyType":"' . $object->getBodyType() . '"';
                        break;
                }
                $j++;
                if ($j < count($allColumns)) {
                    $result .= ',';
                }
            }
        }else{
            $result = null;
        }
        $result.='}';
        return $result;
    }

    public static function getAll($allColumns, $rowData, $orderings, $page, $filters, $isRelPresent, $isPlanetPresent, $isMoonPresent, $isMassValuePresent, $isMassExpPresent, $isVolValuePresent, $isVolExpPresent){
        DBAccess::ConfigInit();
        $scriptsql = "SELECT ".self::FIELDS." FROM ".self::TABLE."";

        $params = array();
        if (!empty($filters)) {
             addWhereFromFilters($filters[$GLOBALS['bodies']],$scriptsql,$params);
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
            if ($rowData) {
                $result = '[';
            }else{
                $result = '{';
            }

            foreach ($allColumns as $column) {
                if (!$rowData) {
                    $result .= '"' . $column->getColId() . '":';
                }
                switch ($column->getColId()){
                    case 'aroundPlanet':
                        if ($row[$column->getColName()]!=''){
                            // c'est un satellite
                            if (!$rowData) {
                                $result .= '{';
                                if ($isPlanetPresent) {
                                    $result .= '"planet":"' . $row[$column->getColName()] . '"';
                                }
                                if ($isRelPresent) {
                                    if ($isPlanetPresent)  $result .= ',';
                                    $result .= '"rel":"' . $GLOBALS['API_URL_BODIES'] . '/' . $row[$column->getColName()] . '"';
                                }
                                $result .= '}';
                            }else{
                                $result .= '[';
                                if ($isPlanetPresent) {
                                    $result .= '"' . $row[$column->getColName()] . '"';
                                }
                                if ($isRelPresent) {
                                    if ($isPlanetPresent) $result .= ',';
                                    $result .= '"' . $GLOBALS['API_URL_BODIES'] . '/' . $row[$column->getColName()] . '"';
                                }
                                $result .= ']';
                            }
                        }else{
                            // ce n'est pas un satellite
                            $result .= 'null';
                        }
                        break;
                    case 'mass':
                        if ($row["mass_val"]!=0){
                            // il a une masse
                            if (!$rowData) {
                                $result .= '{';
                                if ($isMassValuePresent) {
                                    $result .= '"massValue":' . $row["mass_val"];
                                }
                                if ($isMassExpPresent) {
                                    if ($isMassValuePresent) $result .= ',';
                                    $result .= '"massExponent":' . $row["mass_unit"] ;
                                }
                                $result .= '}';
                            }else{
                                $result .= '[';
                                if ($isMassValuePresent) {
                                    $result .= $row["mass_val"];
                                }
                                if ($isMassExpPresent) {
                                    if ($isMassValuePresent) $result .= ',';
                                    $result .= $row["mass_unit"];
                                }
                                $result .= ']';
                            }
                        }else{
                            // ce n'est pas un satellite
                            $result .= 'null';
                        }
                        break;
                    case 'vol':
                        if ($row["vol_val"]!=0){
                            // il a une masse
                            if (!$rowData) {
                                $result .= '{';
                                if ($isVolValuePresent) {
                                    $result .= '"volValue":' . $row["vol_val"];
                                }
                                if ($isVolExpPresent) {
                                    if ($isVolValuePresent) $result .= ',';
                                    $result .= '"volExponent":' . $row["vol_unit"] ;
                                }
                                $result .= '}';
                            }else{
                                $result .= '[';
                                if ($isVolValuePresent) {
                                    $result .= $row["vol_val"];
                                }
                                if ($isVolExpPresent) {
                                    if ($isVolValuePresent) $result .= ',';
                                    $result .= $row["vol_unit"];
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
                            $result .= Bodies::getSatellite($row["CPT_CORPS"], $rowData, $isRelPresent, $isMoonPresent);
                        }else{
                            $result .= "null";
                        }
                        break;
                    case "bodyType":
                        if ($row["BL_PLANETE"]=='-1'){
                            $result .= '"Planet"';
                        }elseif ($row["BL_PLANETE_NAINE"]=='-1'){
                            $result .= '"Dwarf Planet"';
                        }elseif ($row["BL_SATELLITE"] =='-1' or $row["BL_SAT_ASTERO"]=='-1' or $row["BL_SAT_TNO"]=='-1'){
                            $result .= '"Moon"';
                        }elseif ($row["BL_ASTEROIDE"] =='-1' or $row["BL_TNO"]=='-1'){
                            $result .= '"Asteroid"';
                        }elseif ($row["BL_COMETE"]=='-1'){
                            $result .= '"Comet"';
                        }elseif ($row["CPT_CORPS"]=='soleil'){
                            $result .= '"Star"';
                        }else{
                            $result .= '""';
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
                if (!$rowData) {
                    $result .= '"rel":';
                }
                $result .= '"' . $GLOBALS['API_URL_BODIES'] . '/' . $row["CPT_CORPS"] . '"';
            }
            if ($rowData) {
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

    /* Les properties */
    public static function getDescColumns(){
        $descColumns[]=new Column("id", "CPT_CORPS", "string");
        $descColumns[]=new Column("name", "NOM", "string");
        $descColumns[]=new Column("englishName", "NOM_ANGLAIS", "string");
        $descColumns[]=new Column("isPlanet", "BL_PLANETE", "boolean");
        $descColumns[]=new Column("moons", "", "");
        $descColumns[]=new Column("semimajorAxis", "DEMIGRAND_AXE", "number");
        $descColumns[]=new Column("perihelion", "perihelion", "number");
        $descColumns[]=new Column("aphelion", "aphelion", "number");
        $descColumns[]=new Column("eccentricity", "eccentricity", "number");
        $descColumns[]=new Column("inclination", "inclination", "number");
        $descColumns[]=new Column("mass", "", "");
        $descColumns[]=new Column("vol", "", "");
        $descColumns[]=new Column("density", "density", "number");
        $descColumns[]=new Column("gravity", "gravity", "number");
        $descColumns[]=new Column("escape", "escape", "number");
        $descColumns[]=new Column("meanRadius", "mean_radius", "number");
        $descColumns[]=new Column("equaRadius", "equa_radius", "number");
        $descColumns[]=new Column("polarRadius", "polar_radius", "number");
        $descColumns[]=new Column("flattening", "flattening", "number");
        $descColumns[]=new Column("dimension", "dimension", "string");
        $descColumns[]=new Column("sideralOrbit", "sideral_orbit", "number");
        $descColumns[]=new Column("sideralRotation", "sideral_rotation", "number");
        $descColumns[]=new Column("aroundPlanet", "CPTE_CORPS", "string");
        $descColumns[]=new Column("discoveredBy", "DECOUV_QUI", "string");
        $descColumns[]=new Column("discoveryDate", "DECOUV_QD", "string");
        $descColumns[]=new Column("alternativeName", "DES_TEMPO", "string");
        $descColumns[]=new Column("axialTilt", "INCLINAISON_AXE", "number");
        $descColumns[]=new Column("avgTemp", "avg_temp ", "number");
        $descColumns[]=new Column("mainAnomaly", "main_anomaly", "number");
        $descColumns[]=new Column("argPeriapsis", "arg_periapsis", "number");
        $descColumns[]=new Column("longAscNode", "long_asc_node", "number");
        $descColumns[]=new Column("bodyType", "", "string");

        return $descColumns ;
    }

    public static function echoParameters(){
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
    }

    /* le Swagger des properties */
    public static function getDescSwaggerColumnsForBodies($rel){
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
                case 'mass':
                    echo '"mass":{"type":"object", "properties":{ "massValue" :{"type":"number"}, "massExponent" :{"type":"integer"}}}';
                    break;
                case 'vol':
                    echo '"vol":{"type":"object", "properties":{ "volValue" :{"type":"number"}, "volExponent" :{"type":"integer"}}}';
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

    /* pour faire le ménage dans les colonnes */
    public static function getValidColumns($limitTo, $exclude){
        $allColumns = Bodies::getDescColumns();

        if (!empty($limitTo)) {
            // Limit to $limitTo columns
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

        return $cleanedColumns;
    }
}
?>