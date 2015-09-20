<?php

class Database extends PDO {

    public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS)
    {
        parent::__construct($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS);
    }

    public function alterTable( $sql ){
        $sth = $this->prepare($sql);
        $sth->execute();
    }
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC){
        $sth = $this->prepare($sql);
        foreach($array as $key=>$value){
            $sth->bindValue($key, $value);
        }
        $q = $sth->execute();
        return $sth->fetchAll($fetchMode);
    }
    
    public function insert($table, $data, $onDuplUpdate = ''){
        $fieldNames = "`" . implode("`, `", array_keys($data)) . "`";
        $fieldValues = ":" . implode(", :", array_keys($data));
                
        $query = "INSERT INTO $table ($fieldNames) VALUES ($fieldValues)";
        if( $onDuplUpdate != '' ){
            $query .= ' ON DUPLICATE KEY UPDATE ' . $onDuplUpdate;
        }
                
        $sth = $this->prepare($query);
        foreach($data as $key=>$value){
            $sth->bindValue(":$key", $value);
        }
        
        $sth->execute();
        
        return $this->lastInsertId();
    }
    
    public function update($table, $data, $where){
        $fieldDetails = null;
        
        foreach($data as $key=>$value){
            $fieldDetails .= "$key = :$key,";
        }
        
        $fieldDetails = rtrim($fieldDetails, ",");        
        $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
        foreach($data as $key=>$value){
            $sth->bindValue(":$key", $value);
        }
        
        return $sth->execute();        
    }
    
    public function delete($table, $where, $limit = 1){
        $sth = $this->prepare("DELETE FROM $table WHERE $where LIMIT $limit");
        $sth->execute();
    }
}

