<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
         $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        
           $this->initView();
           
           $this->view->messages = $this->_flashMessenger->getMessages();
       
    }
    
    /* fonction appellee avant chaque action */
     public function preDispatch()
    {
        $action =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        
                 
                 
        if ((strcmp($action,"activation"))&&(!Zend_Auth::getInstance()->hasIdentity())) {
                $this->_helper->redirector('index','login');
            }
            
        $this->view->layout()->toolbar = $this->view->toolbar();
        
    }
 

    public function indexAction()
    {
        
         $maps = new Model_DbTable_Map();
         $this->view->mapList = $maps->getAllMaps();
        
         if(count($this->view->mapList)==1)
            $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'map', 'mapid' => $this->view->mapList[0]->id),null,true));
        
              
    }
    
    
    public function mapAction()
    {
        $mapid = $this->_getParam('mapid',0);
        
        if($mapid){
            $maps = new Model_DbTable_Map();
            $elements = new Model_DbTable_Element();
            $this->view->map =  $maps->getMap($mapid);
            $this->view->elementList = $elements->getAllElement($mapid);
            $this->view->layout()->subtitle=$this->view->map->name;
            $this->view->layout()->summary="";
            
            
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
    
    public function activationAction(){
        
        $usermodel = new Model_DbTable_User();
        
        if ($this->getRequest()->isPost())
        {
            $formData = $this->getRequest()->getPost();
            $form = new Application_Form_User(array('activate'=>1));
            $form->populate($formData);
            $reponses = $form->getValues();
            $reponses=$reponses[$form->getName()];
            $isEdit =$reponses['edit']; 

            

            //Contrôle automatique ZendForm
            if ($form->isValid($formData))
            {
                unset($reponses['edit']);
                    //Récupération des  données du formulaire
                $reponses['role']=  Model_DbTable_User::MEMBER;
                $reponses['active']=  1;
                if($reponses['password'])
                        $reponses['password']=md5($reponses['password']);
                else
                        unset($reponses['password']);

               
                $usermodel->updateUser($reponses);
                 $this->_flashMessenger->addMessage("info|Votre compte joueur est créé");
                 $this->_redirect($this->view->url(array("controller"=>"index","action"=>"index"),null,true));


            }else{
                
                $this->view->form = $form;
            }
    }else{
        
        $code = $this->_getparam('code',0);
        
        
        $user=$usermodel->fetchAll("password='$code'");
        
        if($user->count())
        {            
            $theUser = $user[0]->toArray();
            $form = new Application_Form_User(array('activate'=>1));
            $form->populate($theUser);
           
            $this->view->form = $form;
            
        }else{
             $this->_flashMessenger->addMessage("error|La clé n'est pas valide ou le compte a déjà été activé");
             $this->view->message();
            
        }
        
        
    }
        
        
        
        
        $this->view->layout()->subtitle="Création de compte Joueur";
        
        
        $this->view->layout()->summary=<<<EOD
                 Bienvenue dans l'outil de Campagne pour Firestorm Armada.<br/>
                 Vous avez été invité à partiper à une campagne, pour y accéder, merci de compléter ce formulaire.
                 
EOD;

        
        
    }


}

