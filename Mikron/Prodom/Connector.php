<?php

class Prodom_Connector {

    private static $savedConnections = array();

    static function getConnection($config_name) {
        $options = Config::get($config_name);
        $dbms = $options->dbms;
        // unset($options->dbms);
        switch($dbms) {
            case 'pgsql': {
                return self::getPgsqlConnection($options);
            }
            case 'mysql': {
                return self::getMysqlConnection($options);
            }
            case 'redis': {
                return self::getRedisConnection($options);
            }
        }
    }

    /**
    * @return Rediska
    */
    private static function getRedisConnection($options) {
    	$key = md5(json_encode($options));
        if (array_key_exists($key, self::$savedConnections)) {
            return self::$savedConnections[$key];
        }
        self::$savedConnections[$key] = new Rediska(
            array(
                'namespace' => $options->namespace,
                'servers' => array(
                    array(
                        'host' => $options->host,
                        'port' => $options->port
                    )
                )
            )
        );
        return self::$savedConnections[$key];
    }

    /**
    * @return Zend_Db_Adapter_Pdo_Mysql
    */
    private static function getMysqlConnection($options) {
    	$key = md5(json_encode($options));
        if (array_key_exists($key, self::$savedConnections)) {
            return self::$savedConnections[$key];
        }
        // настройка подключения к базе
        $con = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => $options->host,
            'username' => $options->username,
            'password' => $options->password,
            'dbname'   => $options->dbname,
            // 'driver_options'=> array(PDO_MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8') // 'SET CHARACTER SET utf8') //'SET NAMES UTF8')
        ));
        $con->query("set character_set_client = 'utf8'");
        $con->query("set character_set_results = 'utf8'");
        $con->query("set collation_connection = 'utf8_general_ci'");        
        $con->setFetchMode(Zend_Db::FETCH_OBJ);
        self::$savedConnections[$key] = $con;
        return self::$savedConnections[$key];
    }

    /**
    * @return Zend_Db_Adapter_Pdo_Pgsql
    */
    private static function getPgsqlConnection($options) {
    	$key = md5(json_encode($options));
        if (array_key_exists($key, self::$savedConnections)) {
            return self::$savedConnections[$key];
        }
        // PostgreSQL
        $con = new Zend_Db_Adapter_Pdo_Pgsql(array(
            'host'     => $options->host,
            'username' => $options->username,
            'password' => $options->password,
            'dbname'   => $options->dbname
            /*'profiler' => false,
            'persistent' => true,*/
        ));
        $con->setFetchMode(Zend_Db::FETCH_OBJ);
        self::$savedConnections[$key] = $con;
        return self::$savedConnections[$key];
    }

}

