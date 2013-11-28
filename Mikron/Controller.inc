<?php

class Mikron_Controller {

    static private $layout_directory;
    static private $view_directory;
    static private $view_script;
    static private $layout_script;
    static $disabled_view = false;
    static $disabled_layout = false;
    private $variables = array();
    private $functions = array();

    public function __construct() {
    }

    public function init() {
    }

    public function assign($code, $value) {
        if(is_callable($value)) {
            $this->functions[$code] = Array('function' => $value, 'object' => null);
        }
        elseif(is_object($value)) {
            $this->$code = $value;
            $this->variables[$code] = $value;
        }
        else {
            $this->$code = $value;
            $this->variables[$code] = $value;
        }
    }

    public static function disabledView() {
        self::$disabled_view = true;
    }

    public static function disabledLayout() {
        self::$disabled_layout = true;
    }

    static function isDisabledLayout() {
        return self::$disabled_layout;
    }

    static function isDisabledView() {
        return self::$disabled_view;
    }

    static function setLayoutDirectory($dir_path) {
        self::$layout_directory = $dir_path;
    }

    static function setViewDirectory($dir_path) {
        self::$view_directory = $dir_path;
    }

    static function setLayoutScript($script_name) {
        self::$layout_script = $script_name;
    }

    static function setViewScript($script_name) {
        self::$view_script = $script_name;
    }
    
    static function getLayoutDirectory() {
        return self::$layout_directory;
    }

    static function getLayoutScript() {
        return self::$layout_script;
    }

    static function getViewDirectory() {
        return self::$view_directory;
    }

    static function getViewScript() {
        return self::$view_script;
    }

    public function drawLayout() {
        if(!self::isDisabledLayout()) {
            include self::getLayoutDirectory().'/'.self::getLayoutScript().'.html';
        } else {
            self::drawView();
        }
    }

    public function drawView() {
        if(!self::isDisabledView()) {
            include self::getViewDirectory().'/'.str_replace('_', '/', self::getViewScript()).'.html';
        }
    }

    public function redirect($uri) {
        header("location: {$uri}", true, 302);
        exit;
    }

    public function __get($name) {
        if(array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        } elseif(array_key_exists($name, $this->functions)) {
            return $this->functions[$name];
        }
    }

    public function __set($name, $value) {
        return $this->assign($name, $value);
    }

}
