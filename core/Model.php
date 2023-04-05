<?php 
namespace Core;
use \PDO;
use Core\DB;

class Model{
    protected static $table = "";
    protected static $columns = false;
    protected $_validationPassed = true, $_errors = [], $_skipUpdate = [];

    protected static function getDb($setFetchClass = false) {
        $db = DB::getInstance();
        if($setFetchClass){
            $db->setClass(get_called_class());
            $db->setFetchType(PDO::FETCH_CLASS);
        }
        return $db;
    }

    public static function insert($values){
        $db = static::getDb();
        return $db->insert(static::$table, $values);
    }

    public static function update($values, $conditions){
        $db = static::getDb();
        return $db->update(static::$table, $values, $conditions);
    }

    public function delete(){
        $db = static::getDb();
        $table = static::$table;
        $params = [
            'conditions' => "id = :id",
            'bind' => ['id' => $this->id] // ????
        ];
        list('sql' => $conds, 'bind' => $bind) = self::queryParamBuilder($params);
        $sql = "DELETE FROM {$table} {$conds}";
        return $db->execute($sql, $bind);
    }

    public static function find($params = []){
        $db = static::getDb(true);
        list('sql' => $sql, 'bind' => $bind) = self::selectBuilder($params);
        return $db->query($sql, $bind)->results();
    }

    public static function findFirst($params = []){
        $db = static::getDb(true);
        list('sql' => $sql, 'bind' => $bind) = self::selectBuilder($params);
        $results = $db->query($sql, $bind)->results();
        return isset($results[0]) ? $results[0] : false; 
    }

    public static function findById($id){
        return self::findFirst([
            'conditions' => "id = :id",
            'bind' => ['id' => $id]
        ]);
    }

    public static function findTotal($params = []){
        unset($params['limit']);
        unset($params['offset']);
        $table = static::$table;
        $sql = "SELECT COUNT(*) AS total FROM {$table}";
        list('sql' => $conds, 'bind' => $bind) = self::queryParamBuilder($params);
        $sql .= $conds;
        $db = static::getDb();
        $results = $db->quert($sql, $bind);
        $total = $results->getRowCount() > 0 ? $results->results()[0]->total : 0;
        return $total;
    }

    public static function selectBuilder($params = []){
        $columns = array_key_exists('columns', $params) ? $params['columns'] : "*";
        $table = static::$table;
        $sql = "SELECT {$columns} FROM {$table}";
        list('sql' => $conds, 'bind' => $bind) = self::queryParamBuilder($params);
        $sql .= $conds;
        return ['sql' => $sql, 'bind' => $bind];
    }

    public static function queryParamBuilder($params = []){
        $sql = "";
        $bind = array_key_exists('bind', $params) ? $params['bind'] : [];
        //JOINS
        // [['table2', 'table1.id' = 'table2.key', 'tableAlias', 'LEFT' ]]
        if(array_key_exists('joins', $params)){
            $joins = $params['joins'];
            foreach($joins as $join){
                $joinTable = $join[0];
                $joinOn = $join[1];
                $joinAlias = isset($join[2]) ? $join[2] : "";
                $joinType = isset($join[3]) ? "{$join[3]} JOIN" : "JOIN";
                $sql .= " {$joinType} {$joinTable} {$joinAlias} ON {$joinOn}";
            }
        }
        //WHERE
        if(array_key_exists('conditions', $params)){
            $conditions = $params['conditions'];
            $sql .= " WHERE {$conditions}";
        }
        //GROUP
        if(array_key_exists('group', $params)){
            $group = $params['group'];
            $sql .= " GROUP BY {$group}";
        } 
        //ORDER
        if(array_key_exists('order', $params)){
            $order = $params['order'];
            $sql .= " ORDER BY {$order}";
        }
        //LIMIT
        if(array_key_exists('limit', $params)){
            $limit = $params['limit'];
            $sql .= " LIMIT {$limit}";
        }
        //OFFSET
        if(array_key_exists('offset', $params)){
            $offset = $params['offset'];
            $sql .= " OFFSET {$offset}";
        }
        return ['sql' => $sql, 'bind' => $bind];
    }
}