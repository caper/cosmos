<?php

class Prodom_Application_Front extends Zend_Controller_Plugin_Abstract {

    /**
    * Dispatch an HTTP request to a controller/action.
    *
    * @param Zend_Controller_Request_Abstract|null $request
    * @param Zend_Controller_Response_Abstract|null $response
    * @return void|Zend_Controller_Response_Abstract Returns response object if returnResponse() is true
    */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
    }

}
