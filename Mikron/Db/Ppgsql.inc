<?php

class Mikron_Db_Pgsql implements Mikron_Db_Engine {

    private $connection = null;

    public function __construct($server = null, $port = null, $username = null, $password = null, $database_name = null) {
         $connectString  = '';
         if($server) { $connectString  = "{$connectString} host={$server}"; }
         if($username) { $connectString = "{$connectString} user={$username}";}
         if($password) { $connectString = "{$connectString} password={$password}"; }
         if($port) { $connectString = "{$connectString} port={$port}";  }
         if($database_name) { $connectString = "{$connectString} dbname={$database_name}"; }
         $this->connection = pg_connect($connectString);
         if(!$this->connection)
         {
           throw new Exception(Language::_DB_ERROR_CONNECT, Constants::_DB_ERROR_CONNECT_ID);
         }
    }

    public function query($sql) {
        $ret = pg_query($this->connection, $sql);
        return $ret;
    }

    public function fetch($reult) {
        return pg_fetch_object($reult);
    }

    public function close() {
        pg_close($this->connection);
    }

    public function escape_string($string) {
        return pg_escape_string($this->connection, $string);
    }

    public function numRows($result) {
        return pg_num_rows($result);
    }

    public function lastInsertId() {
        /**
        * @todo
        */
        throw new Exception('Not implemented');
    }

}

/*db::query("SET NAMES 'utf8'");
db::query("set character_set_client='utf8'");
db::query("set character_set_results='utf8'");
db::query("set collation_connection='utf8_general_ci'");*/
