<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    
    /* fonction appellee avant chaque action */
     public function preDispatch()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
                $this->_helper->redirector('index','login');
            }
            
        $this->view->layout()->toolbar = $this->view->toolbar();
        
    }
 

    public function indexAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
         $maps = new Model_DbTable_Map();
         $this->view->mapList = $maps->getAllMaps($identity);
        
         if(count($this->view->mapList)==1)
            $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'map', 'mapid' => $this->view->mapList[0]->mapid),null,true));
        
              
    }
    
    
    public function mapAction()
    {
        $mapid = $this->_getParam('mapid',0);
        
        if($mapid){
            $maps = new Model_DbTable_Map();
            $elements = new Model_DbTable_Element();
            $this->view->map =  $maps->getMap($mapid);
            $this->view->elementList = $elements->getAllElement($mapid);
        }else
           $this->_redirect("/");

			
        
        

    }
    
    public function elementshowAction(){
        
        $mapid = $this->_getparam('mapid',0);
        $xCoord = $this->_getparam('xcoord',0);
        $yCoord = $this->_getparam('ycoord',0);
        
        $modelElement = new Model_DbTable_Element();
        $element = $modelElement->getElementFromCoordArray($mapid, $xCoord, $yCoord);
      
          
      $this->view->modaltitle = "test";
         echo $this->view->displayElement($element);
        
        
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        
        
    }
    
    public function profileAction(){
        
        echo "lol";
       
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
    }


}

