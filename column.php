<?php
if (!defined("LOADED_AS_MODULE")) {
    die ("Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der directement &agrave; cette page...");
}
class Column{
    protected $colId;
    protected $colName;
    protected $colType;

    public function getColId(){
        return $this->colId;
    }

    public function getColName(){
        return $this->colName;
    }

    public function getColType(){
        return $this->colType;
    }

    public function __construct($colId, $colName, $colType){
        $this->colId=$colId;
        $this->colName=$colName;
        $this->colType=$colType;
    }
}
?>