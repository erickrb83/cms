<?php
namespace Core;

use \PDO;
use \Exception;
use Core\{Config, H};

class DB{
    protected $_dbh, $_results, $_lastInsertID, $_rowCount = 0, $_fetchType = PDO::FETCH_OBJ, $_class, $_error = false;
    protected $_stmt;
    protected static $_db;

    // Will setup a Singleton Pattern use a static method to determine if it's substantiated. 

    public function __construct(){
        $host = Config::get('db_host');
        $name = Config::get('db_name');
        $user = Config::get('db_user');
        $pass = Config::get('db_password');
        $port = Config::get('db_port');
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ];
        try{
            $this->_dbh = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass, $options);
        } catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public static function getInstance(){
        if (!self::$_db){
            self::$_db = new self();
        }
        return self::$_db;
    }

    public function execute($sql, $bind=[]){
        $this->_results = null;
        $this->_lastInsertID = null;
        $this->_error = false;
        $this->_stmt = $this->_dbh->prepare($sql);
        if(!$this->_stmt->execute($bind)){
            $this->_error = true;
        }else {
            $this->_lastInsertID = $this->_dbh->lastInsertId();
        }
        return $this;
    }

    public function query($sql, $bind=[]){
        $this->execute($sql, $bind);
        if(!$this->_error){
            $this->_rowCount = $this->_stmt->rowCount();
            if($this->_fetchType === PDO::FETCH_CLASS){
                $this->_results = $this->_stmt->fetchAll($this->_fetchType, $this->_class);
            } else {
                $this->_results = $this->_stmt->fetchAll($this->_fetchType);
            }
        }
        return $this;
    }

    public function insert($table, $values){
        $fields = [];
        $binds = [];
        foreach ($values as $key => $value){
            $fields[] = $key;
            $binds[] = ":{$key}";
        }
        $fieldStr = implode('`, `', $fields);
        $bindStr = implode(', ', $binds); 
        $sql = "INSERT INTO {$table} (`{$fieldStr}`) VALUES ({$bindStr})";
        $this->execute($sql, $values);
        return !$this->_error;
    }

    public function update($table, $values, $conditions){
        $binds = [];
        $valueStr = '';
        foreach($values as $field => $value){
            $valueStr .= ", `{$field}` = :{$field}";
            $binds[$field] = $value;
        }
        $valueStr = ltrim($valueStr, ", ");
        $sql = "UPDATE {$table} SET $valueStr";

        if(!empty($conditions)){
            $conditionStr = " WHERE ";
            foreach($conditions as $field => $value){
                $conditionStr .= "`{$field}` = :cond{$field} AND ";
                $binds['cond'.$field] = $value;
            }
            $conditionStr = rtrim($conditionStr, ' AND ');
            $sql .= $conditionStr;
        }
        $this->execute($sql, $binds);
        return !$this->_error;
    }

    public function results(){
        return $this->_results;
    }

    public function count(){
        return $this->_rowCount;
    }

    public function lastInsertID(){
        return $this->_lastInsertID;
    }

    public function setClass($class){
        $this->_class = $class;
    }

    public function setFetchType($type){
        $this->_fetchType = $type;
    }

    public function getFetchType(){
        return $this->_fetchType;
    }
}

