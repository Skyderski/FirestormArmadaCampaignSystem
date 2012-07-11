<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $form = new Application_Form_Login();
			
	// mise en place des decorators
	$form->addDecorator('Description', array('placement' => 'PREPEND','class' => 'chapeau'));
$this->view->form = $form;
        
              
    }
    
    public function mapAction()
    {
        $mapid= $this->_getparam('mapid',0);
        
       
        $this->view->mapid = $mapid;
        
        

if($mapid){
        $map = new Model_DbTable_Map();
	$this->view->map = $map->getMap($mapid);
         //$this->style = MapHelper::createStyleforMap($this->view->map);
        $elements = new Model_DbTable_Element();
         $this->view->elements = $elements->getAllActiveElement($mapid);
         
}			
        
        

    }


}

