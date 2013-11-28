<?php

abstract class Mikron_Db {

    /**
    * @var db_engine
    */
    protected static $engine = array();
    public static function query($sql) {return self::$engine[get_called_class()]->query($sql);}
    public static function fetch($row) {return self::$engine[get_called_class()]->fetch($row);}
    public static function fetchAll($rows) {return self::$engine[get_called_class()]->fetchAll($rows);}
    public static function error() {return self::$engine[get_called_class()]->error();}
    public static function lastInsertId() {return self::$engine[get_called_class()]->lastInsertId();}
    public static function prepareString($string) {return self::$engine[get_called_class()]->prepareString($string);}
    public static function setEngine($engine) {
        self::$engine[get_called_class()] = $engine;
    }

    public static function getEngine() {
        return self::$engine[get_called_class()];
    }

}