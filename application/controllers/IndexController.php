<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
         $maps = new Model_DbTable_Map();
         $this->view->mapList = $maps->getAllMaps();
        
        
        // action body
        $form = new Application_Form_Login();
			
	// mise en place des decorators
	$form->addDecorator('Description', array('placement' => 'PREPEND','class' => 'chapeau'));
        $this->view->form = $form;
        
              
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
      
          
      
         echo $this->view->displayElement($element);
        
        
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        
        
    }


}

