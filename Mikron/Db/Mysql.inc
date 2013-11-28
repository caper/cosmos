<?php

class Mikron_Db_Mysql implements Mikron_Db_Engine {

    private $connection = null;
    private $args = null;

    public function __construct($server, $port, $username, $password, $database_name) {
        $this->args = func_get_args();
    }

    private function getConnection() {
        if(is_null($this->connection)) {
            $server = $this->args[0];
            $port = $this->args[1];
            $username = $this->args[2];
            $password = $this->args[3];
            $database_name = $this->args[4];
            $this->connection = mysql_connect($server, $username, $password);
            mysql_select_db($database_name, $this->getConnection());
            $this->query("SET NAMES 'utf8'");
            $this->query("set character_set_client = 'utf8'");
            $this->query("set character_set_results = 'utf8'");
            $this->query("set collation_connection = 'utf8_general_ci'");
        }
        return $this->connection;
    }
    
    public function query($sql) {
        $ret = mysql_query($sql, $this->getConnection());
        return $ret;
    }

    public function error() {
        return mysql_error($this->getConnection());
    }

    public function prepareString($string) {
        return htmlspecialchars($string);
        // return mysql_escape_string($string);
    }

    public function fetch($row) {
        return mysql_fetch_object($row);
    }

    public function fetchAll($rows) {
        if(!$rows) {
            die(mysql_error($this->getConnection()));
        }
        $resp = array();
        while($row = mysql_fetch_object($rows)) {
            $resp[] = (object)$row;
        }
        return $resp;
    }

    public function close() {
        mysql_close($this->getConnection());
    }

    public function escape_string($string) {
        if(!is_numeric($string)) {
            $string = '\''.mysql_real_escape_string($string, $this->getConnection()).'\'';
        }
        return $string;
    }

    public function numRows($result) {
        return mysql_num_rows($result);
    }

    public function lastInsertId() {
        return mysql_insert_id($this->getConnection());
    }

}
