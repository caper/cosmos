<?php

class Prodom_Controller_Error extends Zend_Controller_Action {

    protected $exception = null;
    protected $is_ajax = false;

    public function init() {
        $layout = Zend_Layout::getMvcInstance();
        $this->_helper->_layout->setLayout('index');
        $layout->setLayoutPath(dirname(__FILE__).'/../../../library/layout/error'); 
        $response = $this->getResponse();
        $exceptions = $response->getException();
        $this->exception = $exceptions[0];
        $e = new Exception();
        $this->is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $this->_helper->viewRenderer->setNoRender(true);
        if($this->is_ajax) {
            $this->_helper->layout()->disableLayout();
        }   
    }

    public function errorAction() {        
        $errors = $this->_getParam('error_handler');
        switch($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // ошибка 404 - не найден контроллер или действие
                self::_forward('error404');
                break;
            default:
                // ошибка приложения
                if($this->exception->getCode() == 401) {
                    self::_forward('error401');
                } elseif($this->exception->getCode() == 404) {
                    self::_forward('error404');
                } elseif($this->exception->getCode() == 950) {
                    self::_forward('error950');
                } else {
                    self::_forward('error500');
                }
                break;
        }
    }

    private function dump() {
        /*$log = new Type_Log_Record_Exception(array(
            'date' => date('Y-m-d H:i:s'),
            'uri' => $_SERVER['REQUEST_URI'],
            'post' => print_r($_POST, 1),
            'exception' => print_r($this->exception, 1),
            'message' => $this->exception->getMessage(),
            'server' => print_r($_SERVER, 1),
            'status' => $this->exception->getCode()
        ));
        Mongo_Log::addExceptionRecord($log);*/
        $dump = 'date = '.date('Y-m-d H:i:s', time())
            ."\n\nuri = ".$_SERVER['REQUEST_URI']
            ."\n\npost = ".print_r($_POST, 1)
            ."\n------- Exception -------\n"
            .print_r($this->exception, 1)
            ."\n------- Server -------\n"
            .print_r($_SERVER, 1);
        $dir = dirname(__FILE__).'/../../../exception/'.date('Y-m-d', time());
        //if(!file_exists($dir)) {
        //    mkdir($dir, 0777, true);
        //}
        // file_put_contents($dir.'/'.date('H-i-s_', time()).rand(100000, 999999).'.txt', $dump);
        return true;
    }

    public function error404Action() {
        $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
        // Удаление добавленного ранее содержимого        
        $this->getResponse()->clearBody();        
        if($this->is_ajax) {
            $this->getResponse()->setHeader('Content-Type', 'application/json; charset=utf-8', true);
            echo json_encode(array('status' => 'error', 'message' => $this->exception->getMessage(), 'code' => 404));
        } else {
            $this->getResponse()->setHeader('Content-Type', 'text/html; charset=utf-8', true);
            $this->view->message = IS_DEVELOPER_HOST ? $this->exception->getMessage() : 'Страница не найдена';
            $this->view->error_code = 404;
        }
    }

    public function error401Action() {
        $this->_redirect('http://'.ROOT_DOMAIN.'/index/logout/');
    }

    public function error500Action() {
        // Удаление добавленного ранее содержимого
        $this->getResponse()->clearBody();
        self::dump();
        if($this->is_ajax) {
            $this->getResponse()->setRawHeader('HTTP/1.1 200 Ok');
            $this->getResponse()->setHeader('Content-Type', 'application/json; charset=utf-8', true);
            echo json_encode(array('status' => 'error', 'message' => $this->exception->getMessage()));
        } else {
            $this->getResponse()->setRawHeader('HTTP/1.1 500 Internal Server Error');
            $this->getResponse()->setHeader('Content-Type', 'text/html; charset=utf-8', true);
            $this->view->error_code = $this->exception->getCode();
            $this->view->message = IS_DEVELOPER_HOST ? $this->exception->getMessage() : 'Произошла непредвиденная ошибка';
        }
    }
    
    public function error950Action() {
        $this->getResponse()->setRawHeader('HTTP/1.1 200 Ok');
        // Удаление добавленного ранее содержимого
        $this->getResponse()->clearBody();
        self::dump();
        if($this->is_ajax) {
            $this->getResponse()->setHeader('Content-Type', 'application/json; charset=utf-8', true);
            echo json_encode(array('status' => 'error', 'message' => $this->exception->getMessage(), 'code' => 200));
        } else {
            $this->getResponse()->setHeader('Content-Type', 'text/html; charset=utf-8', true);
            $this->view->message = $this->exception->getMessage();
            $this->view->error_code = 500;
        }
    }

}
