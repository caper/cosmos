<?php

class Prodom_Helper_Init extends Zend_Controller_Action_Helper_Abstract {

    protected $user, $moduleName, $controllerName, $actionName, $is_ajax;

    public function getName() {
        return 'Init';
    }

    /**
    * Hook
    */
    public function postInit() {
    }

    public function init() {
        if ($zca = $this->getActionController()) {
            if(property_exists($zca, 'user')) {
                return false;
            }
            $zca->user = new User;
            $this->user = $zca->user;
            $zca->menu = new Plugin_Menu;
            Zend_Registry::set('user', $zca->user);
            Zend_Registry::set('menu', $zca->menu);
            $view = Zend_Layout::getMvcInstance()->getView();
            $view->assign('user', $zca->user);
            $view->assign('menu', $zca->menu);
            $this->moduleName = $this->getRequest()->getModuleName();
            $this->controllerName = $this->getRequest()->getControllerName();
            $this->actionName = $this->getRequest()->getActionName();
            $module = ucfirst($this->moduleName);
            $controller = ucfirst($this->controllerName);
            $action = ucfirst($this->actionName);
            $module = ($module == 'Default' ? null : $module.'_');
            // @ajax
			$action_doc = Prodom_Reflection::parseMethodDoc($module.$controller.'Controller', $action.'Action');
            $this->is_ajax = array_key_exists('ajax', $action_doc);
            if($this->is_ajax) {
                Zend_Layout::getMvcInstance()->disableLayout();
                Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
				Zend_Controller_Front::getInstance()->getResponse()->setHeader('Content-Type', 'application/json; charset=utf-8', true);
            }
            return $this->postInit();
        }
    }
	
}