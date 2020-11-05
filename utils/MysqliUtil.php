<?php
class MysqliUtil {

    protected $connection;
    public $connectionError;
    protected $tableName;

    public function __construct(){
        error_reporting(E_ERROR | E_PARSE);
        $this->connection = new mysqli("sql12.freemysqlhosting.net","sql12374463","wSmgcZWsM3","sql12374463");
        $this->connectionError = $this->connection->connect_errno;
    }

    public function __destruct(){
        $this->connection->close();
    }

    public function escapeQuotes($value){
        return str_replace('"','\"',str_replace('\'','\\\'',$value));
    }

    public function insert($data,$tableName = null){
        $columnNames = [];
        $values = [];
        foreach($data as $column => $value){
            $columnNames []= $column;
            $values []= $this->escapeQuotes($value);
        }
        $sql = 'INSERT INTO '.($tableName ?? $this->tableName).' ('.implode(',',$columnNames).') values ("'.implode('","',$values).'")';
        $result = $this->connection->query($sql);
        if($result){
            return $this->connection->insert_id;
        }
    }

    public function update($data,$where){
        $set = [];
        $whereClause = [];
        foreach($data as $column => $value){
            $set []= $column.' = "'.$this->escapeQuotes($value).'"';
        }
        foreach($where as $column => $value){
            $whereClause []= $column.' = "'.$this->escapeQuotes($value).'"';
        }
        $sql = 'UPDATE '.$this->tableName.' SET '.implode(',',$set).' '.( sizeof($whereClause) > 0 ? 'WHERE '.implode(' AND ',$whereClause) : '');
        return $this->connection->query($sql);
    }

    public function collectData($result){
        $data = [];
        while($row = $result->fetch_assoc()){
            $data []= $row;
        }
        return $data;
    }

    public function getData($clauses = []){
        $whereClause = [];
        foreach(($clauses['where'] ?? []) as $where) {
            foreach($where as $field => $value){
                $whereClause []= $field.' = "'.$this->escapeQuotes($value).'"';
            }
        }
        $sql = "SELECT ".implode(",",$clauses['fields'] ?? ['*'])." FROM ".$this->tableName.(sizeof($whereClause) > 0 ? ' WHERE '.implode(' AND ',$whereClause) : '');
        $result = $this->connection->query($sql);
        if($result->num_rows > 0){
            return $this->collectData($result);
        }
        return [];
    }

    function getByQuery($query){
        $result = $this->connection->query($query);
        if($result->num_rows > 0){
            return $this->collectData($result);
        }
        return [];
    }
}