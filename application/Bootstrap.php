<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

//lorsque la m�thode commence par init alors elle est appel�e au lancement de l'appli
	protected function _initAutoload()
	{
		$moduleLoader = Zend_Loader_Autoloader::getInstance();
		$moduleLoader->registerNamespace('Gru');
		
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
			'namespace' => '',
			'basePath' => APPLICATION_PATH));
		return $moduleLoader;
	}

	//initialise la fonction layout	
	protected function _initViewHelpers()
	{
	    $this->bootstrap('layout');
	    $layout = $this->getResource('layout');
	    $view = $layout->getView();
	    $view->doctype('XHTML1_STRICT');
	    $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
	    $view->headTitle()->setSeparator(' - ');
	    $view->headTitle('FireStorm Armada Campaign Tool');
            
         
	}
	
	
	protected function _initView()
	{
		$view = new Zend_View();
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);
 
		//début ajout
		$view->addHelperPath(APPLICATION_PATH . '/../library/Gru/View/Helper/', 'Gru_View_Helper_');
		$view->addHelperPath(APPLICATION_PATH . '/../application/views/helpers/');
                // fin de l'ajout
                $view->addHelperPath(APPLICATION_PATH . "/../library/ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper_");
                
                
                
                $view->layout()->title="Firestorm Armada Campaign Tool<br/>";
 

 
		return $view;
	}
        
        protected function _initHTMLView()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->headLink()->appendStylesheet('/css/style.css');
        $view->headLink()->appendStylesheet('/css/bootstrap/css/bootstrap.css');
        
        
        
        
        
    }
    
    protected function _initLogging()
    {
        $logger = new Zend_Log();
        // récupérer et filtrer sur le niveau de log
        $optionLevel = (int) $this->_options["logging"]["level"];
        $filter = new Zend_Log_Filter_Priority($optionLevel);
        $logger->addFilter($filter);
        // ajouter un rédacteur qui écrit dans le fichier défini
        $optionPath = $this->_options["logging"]["filename"];
        $writer = new Zend_Log_Writer_Stream($optionPath);
        $logger->addWriter($writer);
        Zend_Registry::set("logger", $logger);
    }
    
 

        
}

