<?php

class Mikron_Route {

    private static $routes = array();
    public static $lastException = null;

    public static function init() {
    }

    /**
    * Load file with route:add() commands
    * 
    * @param string $file
    */
    private static function load($file) {
        require_once($file);
    }

    public static function assign($uri, $action = null, $layout = null, $hasGet = array(), $hasPost = array()) {
        self::add(
            array(
                    'uri' => $uri,
                    'hasget' => $hasGet,
                    'haspost' => $hasPost,
                    'action' => $action,
                    'layout' => $layout,
                )   
        );
    }

    public static function add() {
        foreach(func_get_args() as $route) {
            self::$routes[] = $route;
        }
    }

    public static function call() {
        $application = 'index';
        $controller = 'index';
        $action = 'index';
        $layout = null;
        $view = null;
        $uri = $_SERVER['REQUEST_URI'];
        if(substr($uri,0,1) == '/') {
            $uri = substr($uri, 1);
        }
        // адрес страницы разбитый слешами
        $uri_components = explode('/', $uri);
        $uri_components_count = count($uri_components);
        // переменные из адреса страницы
        $route_variables = array();
        $uri_variables = array();
        foreach(self::$routes as $route) {
            $route_uri = $route['uri'];
            if(!is_array($route_uri)) {
                $route_uri = array($route_uri);
            }
            // индекс секции рассматриваемой маски
            $part_number = 0;
            // если указано несколько адресных масок
            foreach($route_uri as $ruri) {
                // разбиваем на секции
                $ruri = explode('/', $ruri);
                $use_this_route = true;
                $part_number = 0;
                // просматриваем каждую секцию и проверяем ее в текущем адресе
                foreach($ruri as $r) {
                    $component = $uri_components[$part_number];
                    // если в маске пути больше секций, чем в рассматриваемом адресе
                    // переход на следующую маску
                    if(++$part_number > $uri_components_count) {
                        $use_this_route = false;
                        break;
                    }
                    // если сектор рассматривается в качестве переменной
                    if(substr($r, 0, 1) == ':') {
                        $v = explode('=', substr($r,1), 2);
                        // имя переменной
                        $key = $v[0];
                        // если есть условие для значения переменной
                        if(count($v) == 2) {
                            $cr = $v[1];
                            // простая проверка является ли переменная числом
                            if($cr == 'digit') {
                                if(is_numeric($component))
                                {
                                    continue;
                                }
                                $use_this_route = false;
                                break;
                            }
                            // иначе рассматриваем условие в качестве регулярного выражения
                            // которому должна соответствовать переменная
                            $cr = '/^'.$cr.'$/';
                            $variables = false;
                            $eq = preg_match_all($cr, $component, $variables);
                            // если соответствует регулярному выражению,
                            // тогда переходим к следующему сектору
                            if(!$eq) {
                                $use_this_route = false;
                                break;
                            }
                        }
                        $uri_variables[$key] = $component;
                    }
                    else {
                        if($r != $component) {
                            $use_this_route = false;
                            break;
                        }
                    }
                }
                if($use_this_route) {
                    foreach(array('hasget', 'haspost') as $method) {
                        if(!array_key_exists($method, $route)) {
                            continue;
                        }
                        foreach($route[$method] as $param_name => $param_value) {
                            $method_var = $method == 'hasget' ? $_GET : $_POST;
                            if(array_key_exists($param_name, $method_var)) {
                                if(!is_null($param_value)) {
                                    if(is_array($param_value) && !is_array($method_var[$param_name]))
                                    {$use_this_route = false;break;}
                                    elseif($method_var[$param_name] != $param_value)
                                    {$use_this_route = false;break;}
                                    else{$valid_route = false;}
                                }
                            }
                            else {
                                $use_this_route = false;
                                break;
                            }
                        }
                    }
                    if($use_this_route) {
                        if(array_key_exists('action', $route)) {
                            $action = $route['action'];
                        }
                        if(array_key_exists('view', $route)) {
                            $view = $route['view'];
                        }
                        if(array_key_exists('layout', $route)) {
                            $layout = $route['layout'];
                        }
                        foreach($uri_variables as $key => $uvar) {
                            $route_variables[$key] = $uvar;
                        }
                        if(!self::parseAction($action, $layout, $view, $route_variables)) {
                            throw new Exception('Action ['.$action.'] not found');
                        }
                        return true;
                    }
                }
            }
        }
        if(!self::parseAction($_SERVER['REQUEST_URI'])) {
            if(self::$lastException instanceof Exception) {                
                throw new Exception(self::$lastException->getMessage());
            }
        }
    }

    private static function parseAction($uri, $layout = null, $view = null, $route_variables = array()) {
        $application = null;
        $controller = null;
        $action = null;
        # $layout = null;
        # $view = null;
        $s = explode('?', $uri);
        $uri = $s[0];
        if(substr($uri, 0, 1) == '/') {
            $uri = substr($uri,1);
        }
        if($uri == '') { $uri = null; }
        $components = explode('/', $uri);
        if($components[count($components) - 1] == '') {
            array_pop($components);
        }
        try {
            if(count($components) >= 3) {
                $application = $components[0];
                $controller = $components[1];
                $action = $components[2];
                if(!self::run($application, $controller, $action, $layout, $view, $route_variables)) {
                    throw new Exception('Не удалось определить действие');
                }
            }
            elseif(count($components) == 2) {
                $controller = $components[0];
                $action = $components[1];
                if(!self::run('index', $controller, $action, $layout, $view, $route_variables)) {
                    if(!self::run($application, $controller, 'index', $layout, $view, $route_variables)) {
                        if(!self::run($application.'/'.$controller, 'index', 'index', $layout, $view, $route_variables)) {
                            throw new Exception('Не удалось определить действие');
                        }
                    }
                }
            }
            elseif(count($components) == 1) {
                $controller = $components[0];
                if(!self::run('index', $controller, 'index', $layout, $view, $route_variables)) {
                    throw new Exception('Не удалось определить действие');
                }
            }
            else {
                if(!self::run('index', 'index', 'index', $layout, $view, $route_variables)) {
                    throw new Exception('Не удалось определить действие');
                }
            }
            return true;
        }
        catch(Exception $ex) {
            $application = null;
            $controller = null;
            $action = null;
            $view = null;
            return false; // throw new Exception('Route not found', null, $ex);
        }
    }

    private static function camelCasePath($path) {
        $path = str_replace('\\', '/', $path);
        $items = explode('/', $path);
        $response = array();
        foreach($items as $item) {
            $response[] = ucfirst($item);
        }
        return implode(null, $response);
    }

    private static function run($application, $controller_name, $action, $layout, $view, $route_variables) {
        try {
            // page::$layout = $layout;
            $controller_script = M_WWW_ROOT.'/application/'.$application.'/'.$controller_name.'.php';
            $controller_class = $controller_name.'Controller';
            if(!file_exists($controller_script)) {
                throw new Exception('Controller file ['.$controller_script.'] not found');
            }
            $controller_script = realpath($controller_script);
            require_once($controller_script);
            $controller_class = self::camelCasePath($application).self::camelCasePath($controller_name).'_Controller';
            if(!class_exists($controller_class)) {
                throw new Exception('Controller class ['.$controller_class.'] not found');
            }
            $controller = new $controller_class($application, $controller_name, $action);
            $action_method = self::camelCasePath($action);
            Mik::$cur_app = $application;
            if(!method_exists($controller, $action_method)) {
                throw new Exception("Action [{$action_method}] not found in controller [{$controller_script}]");
            }                
            else {
                if(is_null($layout)) {
                    $layout = 'index';
                }
                $layouts_directory = M_WWW_ROOT.'/layout';
                Mikron_Controller::setLayoutDirectory($layouts_directory);
                Mikron_Controller::setLayoutScript($layout);
                Mikron_Controller::setViewDirectory(M_WWW_ROOT."/application/{$application}/views/{$controller_name}");
                Mikron_Controller::setViewScript($action);
                foreach($route_variables as $code => $value) {
                    $controller->assign($code, $value);
                }
                // dumpr($route_variables);
                $controller->init();
                $controller->$action_method();
                $controller->drawLayout();
            }
            return true;
        }
        catch(Exception $ex) {
            self::$lastException = $ex;
            return false;
        }
    }

}
