<?php

namespace  Env\Model;

class Model extends \Env\Object
{
    protected $model;

    protected $schema;

    protected $manager;

    protected $db;

    protected static $dbh;

    protected static $closeQuery = true;

    public function __construct()
    {
        $this->manager = \Env\Data\Collection::getInstance()->manager; 
    
        $db = Mysql\Connection::getInstance();
    }


    public static function sql($sql, $data = array(), $fetchType = \PDO::FETCH_ASSOC )
    {
        self::$dbh = Mysql\Connection::getInstance()->conn;

        $query = self::$dbh->prepare($sql);

        $query->execute($data);

        $limit = $query->rowCount();

        if ($limit === 0)
            return array();

        if ($limit == 1)
            $data = $query->fetch($fetchType);
        else
            $data = $query->fetchAll($fetchType);

        if( self::$closeQuery )
            $query->closeCursor();

        return $data;
    }


    public static function all($sql, $data = array(), $fetchType = \PDO::FETCH_ASSOC )
    {
        self::$dbh = Mysql\Connection::getInstance()->conn;

        $query = self::$dbh->prepare($sql);

        $query->execute($data);

        $limit = $query->rowCount();

        if ($limit === 0)
            return array();

       
        $data = $query->fetchAll($fetchType);

        $query->closeCursor();

        return $data;
    }

    public static function call($sql, $data = array() ){
        self::$dbh = Mysql\Connection::getInstance()->conn;

        $query = self::$dbh->prepare($sql);

        $query->execute($data);

        $query->closeCursor();

        return $data;
    }
}