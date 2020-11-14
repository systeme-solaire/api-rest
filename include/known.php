<?php
if (!defined("LOADED_AS_MODULE")) {
    die ("Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der directement &agrave; cette page...");
}

/* Objet du système solaire */
class Known implements JsonSerializable{
    const FIELDS = "ID, VAL, FROM_DATE";
    const TABLE = "syssol_tab_known";

    protected $id;
    protected $knownCount;
    protected $updateDate;
    protected $isExists;

    public function getId(){
        return $this->id;
    }
    public function getKnownCount(){
        return $this->knownCount;
    }
    public function getUpdateDate(){
        return $this->updateDate;
    }
    public function isExists(){
        return $this->isExists;
    }

    public function __construct($id){
        DBAccess::ConfigInit();

        $scriptsql = "SELECT ".self::FIELDS." FROM ".self::TABLE." WHERE ID = ?";
        $result = $GLOBALS['BDD']->prepare($scriptsql);
        $result->execute(array($id));
        $this->isExists = true;

        if ($result->rowCount()==0) {
            $this->isExists = false;
        }
        
        if ($this->isExists ){
            $donnees = $result->fetch();
            $this->id = $donnees["ID"];
            $this->knownCount = $donnees["VAL"];
            $this->updateDate = $donnees["FROM_DATE"];
        }
        $result->closeCursor();

        if($GLOBALS['BDD']){
            $GLOBALS['BDD'] = null;
        }
    }

    public static function getDescSwaggerColumnsForKnown($rel){
        $columns = Known::getDescColumns();
        $i = 0;
        foreach ($columns as $col) {
            echo '"' . $col->getColId() . '": {"type": "' . $col->getColType() . '"}';      
            if ($i < count($columns) - 1) echo ',';
            $i++;
        }
        if ($rel) {
            echo ',"rel":{"type":"string"}';
        }
    }

    public static function echoParameters(){
        echo '"parameters":[';
        echo '{';
        echo '"name":"rowData",';
        echo '"in":"query",';
        echo '"description":"Transform the object in records. NB: This can also be done client-side in JavaScript!",';
        echo '"required":false,';
        echo '"type":"boolean"';
        echo '}';
        echo ']'; 
    }

    public static function getDescColumns(){
        $descColumns[]=new Column("id", "ID", "string");
        $descColumns[]=new Column("knownCount", "VAL", "number");
        $descColumns[]=new Column("updateDate", "FROM_DATE", "string");
        return $descColumns ;
    }

    public static function getValidColumns(){
        $allColumns = Known::getDescColumns();
        $cleanedColumns=[];
        foreach ($allColumns as $col) {
            $cleanedColumns[] = $col;
        }
        return $cleanedColumns;
    }

    public static function getAll($allColumns, $rowData){
        DBAccess::ConfigInit();
        $scriptsql = "SELECT ".self::FIELDS." FROM ".self::TABLE."";

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

            $result .= ',';
            if (!$rowData) {
                $result .= '"rel":';
            }
            $result .= '"' . $GLOBALS['API_URL_KNOWN'] . '/' . $row["ID"] . '"';

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

    /* renvoie le JSON d'une données */
    public static function getOne($object, $allColumns){
        $result='{';
        if ($object->getId()!==null) {
            $j=0; // pour les colonnes
            foreach ($allColumns as $column) {
                switch ($column->getColId()) {
                    case "id":
                        $result .= '"id":"' . $object->getId() . '"';
                        break;
                    case "knownCount":
                        $result .= '"knownCount":' . ($object->getKnownCount() != 0 ? $object->getKnownCount() : 0) . '';
                        break;
                    case "updateDate":
                        $result .= '"updateDate":"' . ($object->getUpdateDate() != 0 ? $object->getUpdateDate() : 0) . '"';
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

    /* Serialization de l'objet */
    public function jsonSerialize() {
        $allColumns= Known::getValidColumns();

        if ($this->getId()!==null) {
            $result = [];

            $j=0; // pour les colonnes
            foreach ($allColumns as $column) {
                switch ($column->getColId()) {
                    case "id":                $result+=array('id' => $this->getId());break;
                    case "knownCount":        $result+=array('knownCount' => $this->getKnownCount());break;
                    case "updateDate":        $result+=array('updateDate' => $this->getUpdateDate());break;
                }
                $j++;
            }
        }else{
            $result = null;
        }
        return $result;
    }
}
?>