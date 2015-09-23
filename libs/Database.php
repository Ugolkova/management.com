<?php

class Database extends PDO {
    
    public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS)
    {
        parent::__construct($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS);
    }

    /**
     * Alter Table
     * 
     * @param String $sql Query string
     */
    public function alterTable( $sql ){
        $sth = $this->prepare($sql);
        $sth->execute();
    }
    
    /**
     * Select 
     * 
     * @param String $sql Query String
     * @param Array $array Associative array with bind values
     * @param String $fetchMode Normally we use PDO::FETCH_ASSOC
     * @return Array Fetched rows
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC){
        $sth = $this->prepare($sql);
        foreach($array as $key=>$value){
            $sth->bindValue($key, $value);
        }
        $q = $sth->execute();
        return $sth->fetchAll($fetchMode);
    }
    
    /**
     * Insert
     * 
     * @param String    $table Table
     * @param Array     $data Associative array
     * @param String    $onDuplUpdate Part of query string 'ON DUPLICATE UPDATE ...'
     * @return Integer
     */
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
    
    /**
     * Update entries
     * 
     * @param String    $table  Table
     * @param Array     $data   Associative array
     * @param String    $where  Example: 'x=5 AND y!=6'
     * @return Boolean
     */
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
    
    /**
     * Delete entries
     * 
     * @param String    $table Table
     * @param String    $where Example: 'x=5 AND y!=6'
     * @param Integer   $limit Limit entries
     */
    public function delete($table, $where, $limit = 1){
        $sth = $this->prepare("DELETE FROM $table WHERE $where LIMIT $limit");
        $sth->execute();
    }
}

