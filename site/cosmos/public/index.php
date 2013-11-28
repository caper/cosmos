<?php

    session_start();

    // include framework bootstrapper
    include(dirname(__FILE__).'/../../../Mikron/boot.php');
    Mik::boot(dirname(__FILE__).'/..');

    // create database engine instance
    class db1 extends Mikron_Db {}
    db1::setEngine(new Mikron_Db_Mysql('185.12.92.117', 3306, 'user', 'n6mBvRfk', 'cosmos'));
    
    Mikron_Route::assign(array('page/:id'), 'index/page/open', 'index', array(), array());

    // Routes configure. Sample: /user/guest/?del=1024
    //Mikron_Route::assign(array('delete/:what/:id'), 'index/index/delete', 'index', array(), array());
    //Mikron_Route::assign(array('article/load/:id'), 'index/article/load', 'index', array(), array());
    //Mikron_Route::assign(array('article/save/:id'), 'index/article/save', 'index', array(), array());
    //Mikron_Route::assign(array('gallery/create'), 'index/gallery/create', 'index', array(), array());
    //Mikron_Route::assign(array('gallery/deletephoto'), 'index/gallery/deletephoto', 'index', array(), array());
    //Mikron_Route::assign(array('gallery/:name=[\(\)a-z_0-9.@-]+'), 'index/gallery/open', 'index', array(), array());

    function generateCode($length) {
       $chars = 'abdefhiknrstyz1234567890';
       $numChars = strlen($chars);
       $string = '';
       for ($i = 0; $i < $length; $i++)
       {
          $string .= substr($chars, rand(1, $numChars) - 1, 1);
       }
       return $string;
    }
        
    /*try {
        $domainUser = Model_User::getDomainsUser();
    } catch(Exception $ex) {
        var_dump($domainUser);
        die($ex->getMessage());
        // User by submain name not found
        header('Location: /404.html', 302);
    }
    */

    if(isset($_SESSION['profile'])) {
        $user = $_SESSION['profile'];
        //define('ISADMIN', $user);
    } else {
        //define('ISADMIN', false);
    }
    
    include dirname(__FILE__).'/Menu.php';

    Mikron_Route::call();