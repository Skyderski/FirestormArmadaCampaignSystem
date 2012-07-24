<?php

class ManageController extends Zend_Controller_Action
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
        else{
            $identity = Zend_Auth::getInstance()->getIdentity();
            $userModel = new Model_DbTable_User();
            
            $user = $userModel->getUserByName($identity);
            
            $maprole=$userModel->getRoleForMap($user->id);
            
            if(($maprole!=Model_DbTable_User::ADMIN)&&($user->role!=Model_DbTable_User::ADMIN)){
                $this->_helper->redirector('index','login');
             }
                 
            }
            
            
              $this->view->layout()->title = "Interface d'Administration";
              
              $this->view->layout()->toolbar = $this->view->toolbar();
       
    }

 

    public function indexAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
         $maps = new Model_DbTable_Map();
         $this->view->mapList = $maps->getAllMaps($identity,  Model_DbTable_User::ADMIN);
        
        // if(count($this->view->mapList)==1)
          //  $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'map', 'mapid' => $this->view->mapList[0]->mapid),null,true));
        
              
    }
    
    public function mapAction(){
        //gestion de la carte
        
    }
    
    public function usersAction(){
        //gestion des utilisateurs
        
    }
    
    public function player2mapAction()
    {
        
    }


}

