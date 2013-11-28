<?php
  
class Mikron_Routines {
    
    public static function catchFunction($function, $parameters) {
        ob_start();
        if(is_callable($function))
        {
            echo call_user_func($function, $parameters);
        }
        return ob_get_clean();
    }

    public static function wwwPath($filePath) {
        if(!file_exists($filePath)) {
            throw new Exception('File not found');
        }
        $filePath = realpath($filePath);
        $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $filePath);
        $filePath = str_replace('/', DIRECTORY_SEPARATOR, $filePath);
        $rootPath = realpath(M_WWW_ROOT).'/public';
        $rootPath = str_replace('\\', DIRECTORY_SEPARATOR, $rootPath);
        $rootPath = str_replace('/', DIRECTORY_SEPARATOR, $rootPath);
        $filePath = str_replace($rootPath, null, $filePath);
        $filePath = str_replace('\\', '/', $filePath);
        return $filePath;
    }

}
