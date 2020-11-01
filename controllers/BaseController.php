<?php
class BaseController {

    protected $mysqli ;
    protected $connected = false;

    public function __construct(){
        $this->mysqli = new mysqli("localhost","root","","blood_bank");
        if ($this->mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $this->mysqli->connect_error);
        } else {
            $this->connected = true;
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
            var_dump('INSERT INTO '.$this->tablename.' ('.implode(',',$columnNames).') values ("'.implode('","',$values).'")');
            return $this->mysqli->query('INSERT INTO '.$this->tablename.' ('.implode(',',$columnNames).') values ("'.implode('","',$values).'")');
        } catch (Exception $e) {
            //Log
            var_dump(e);
        }
        return false;
    }

    public function getData($clauses = []){
        try {
            $whereClause = [];
            foreach(($clauses['where'] ?? []) as $where) {
                foreach($where as $field => $value){
                    $whereClause []= $field.' = "'.$this->checkQuotes($value).'"';
                }
            }
            $sql = "SELECT ".implode(",",$clauses['fields'] ?? ['*'])." FROM ".$this->tablename.(sizeof($whereClause) > 0 ? ' WHERE '.implode(' AND ',$whereClause) : '');
            $result = $this->mysqli->query($sql);
            return $result->fetch_assoc() ?? [];
        } catch (Exception $e) {
            //Log
            var_dump(e);
        }
        return [];
    }

    function sql($query){
        try {
            $result = $this->mysqli->query($query);
            return $result->fetch_assoc() ?? [];
        } catch (Exception $e) {
            //Log
            var_dump(e);
        }
        return [];
    }
}