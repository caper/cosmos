<?php

interface Mikron_Db_Engine {

    public function numRows($result);

    /**
    * @param string $sql
    */
    public function query($sql);

    /**
    * Return object from result row
    * @param mixed $row
    */
    public function fetch($row);

    /**
    * Close connection to database
    */
    public function close();

    public function error();

    /**
    * Функция экранирования переменных для SQL запросов
    * 
    * @param string string
    * @return string
    */
    public function escape_string($string);

}

