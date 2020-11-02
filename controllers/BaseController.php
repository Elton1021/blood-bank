<?php
class BaseController {

    protected $connection ;
    protected $tableName;

    public function __construct(){
        $this->connection = new mysqli("localhost","root","","blood_bank");
        if ($this->connection->connect_errno) {
            die("Failed to connect to MySQL: " . $this->connection->connect_error);
        }
    }

    public function checkQuotes($value){
        return str_replace('"','\"',str_replace('\'','\\\'',$value));
    }

    public function insert($data){
        $columnNames = [];
        $values = [];
        foreach($data as $column => $value){
            $columnNames []= $column;
            $values []= $this->checkQuotes($value);
        }
        try {
            $sql = 'INSERT INTO '.$this->tableName.' ('.implode(',',$columnNames).') values ("'.implode('","',$values).'")';
            if($this->connection->query($sql)){
                return $this->connection->insert_id;
            }
        } catch (Exception $e) {
            //Log
            var_dump(e);
        }
    }

    public function getData($clauses = []){
        try {
            $whereClause = [];
            foreach(($clauses['where'] ?? []) as $where) {
                foreach($where as $field => $value){
                    $whereClause []= $field.' = "'.$this->checkQuotes($value).'"';
                }
            }
            $sql = "SELECT ".implode(",",$clauses['fields'] ?? ['*'])." FROM ".$this->tableName.(sizeof($whereClause) > 0 ? ' WHERE '.implode(' AND ',$whereClause) : '');
            $result = $this->connection->query($sql);
            return $result->fetch_assoc() ?? [];
        } catch (Exception $e) {
            //Log
            var_dump(e);
        }
        return [];
    }

    function sql($query){
        try {
            $result = $this->connection->query($query);
            return $result->fetch_assoc() ?? [];
        } catch (Exception $e) {
            //Log
            var_dump(e);
        }
        return [];
    }
}