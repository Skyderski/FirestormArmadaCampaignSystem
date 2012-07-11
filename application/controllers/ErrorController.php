<?php

class ErrorController extends Zend_Controller_Action
{

     public function errorAction()
    {
        $this->_helper->layout->setLayout('layouts/site');
        $errors = $this->_getParam('error_handler');
        switch ($errors->type) { 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error 
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                // logging
                $logger = Zend_Registry::get("logger");
                // logger le type d'exception et sa trace
                $logger->err($errors->exception->getMessage());
                $logger->err($errors->exception->getTraceAsString());
                // rediriger la sortie var_dump dans le fichier de log
                ob_start();
                var_export($errors->request->getParams());
                $formatedParams = ob_get_contents();
                ob_end_clean();
                $logger->err($formatedParams);
                break;
        }
        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}

