<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
          $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        
           $this->initView();
           
           $this->view->messages = $this->_flashMessenger->getMessages();
       
       
    }
    
      public function preDispatch()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
                $this->_helper->redirector('index','login');
            }
        else{
            $identity = Zend_Auth::getInstance()->getIdentity();
            $userModel = new Model_DbTable_User();
            $role = $userModel->getRoleFromIdentity($identity);
            if($role!=Model_DbTable_User::ADMIN){
             $this->_helper->redirector('index','login');
             }
            
                  
            }
            
             $this->view->layout()->toolbar = $this->view->toolbar();
              $this->view->layout()->title = "Interface d'Administration";
      
        
    }

    public function indexAction()
    {
       
        $maps = new Model_DbTable_Map();
        $this->view->mapList = $maps->getAllMaps();
        
       
      
        
    }
    
            
    public function mapsAction()
    {
        
        $maps = new Model_DbTable_Map();
	$this->view->mapList = $maps->getAllMaps();
        
        $this->view->layout()->title .= " - Cartes ";
        
    }
    
    public function addmapAction(){
        
        $name= $this->_getparam('name','');
        $width= $this->_getparam('width',0);
        $height= $this->_getparam('height',0);
        $background= $this->_getparam('background',0);
        
        if(($name)&&($width)&&($height)){
            $maps = new Model_DbTable_Map();
            
            $data = array(
            'name'      => $name,
            'width' => $width,
            'height'      => $height,
                'background' => $background,
            'active' => 0
        );
            
            $maps->addMap($data);
        }
        $this->_redirect("/admin/maps");
    }
    
    public function deletemapAction(){
        
        $id= $this->_getparam('mapid',0);
        if($id){
             $maps = new Model_DbTable_Map();
             $maps->delMap($id);
        }
        $this->_redirect("/admin/maps");
        
    }
    
    
    public function editmapAction(){
        
        $edit= $this->_getparam('edit',0);
        
        $id = $this->_getparam('mapid',0);
        $maps = new Model_DbTable_Map();
        $map =  $maps->getMap($id);
        $this->view->map =$map;
        
        if($edit){
            // MaJ de la carte
            $id= $this->_getParam('mapid',$map->id);
            $name= $this->_getparam('name',$map->name);
            $width= $this->_getparam('width',$map->width);
            $height= $this->_getparam('height',$map->height);
            $active = $this->_getParam('active',$map->active);
            $background  = $this->_getParam('background',$map->background);
            
                 $data = array(
                    'id' => $id, 
                    'name'      => $name,
                    'width' => $width,
                    'height'      => $height,
                     'background' => $background,
                    'active' => $active
                );
                
                $maps->updateMap($data);
            
            $this->_redirect("/admin/maps");
        }
    }
    
    public function togglemapAction(){
        
         $id= $this->_getParam('mapid',0);
         if($id){
            $maps = new Model_DbTable_Map();
            $map =  $maps->getMap($id);
            $data = array(
                    'id' => $id, 
                    'active' => ($map->active)?0:1
            );
                
                $maps->updateMap($data);
             
             
         }
         
         
         $this->_redirect("/admin/maps");
    }
    
    
        public function toggleuserAction(){
        
         $id= $this->_getParam('id',0);
         
         if($id){
            $userModel = new Model_DbTable_User();
            $user =  $userModel->fetchRow("id=$id");
            $data = array(
                    'id' => $id, 
                    'active' => ($user->active)?0:1
            );
                
                $userModel->update($data,"id=$id");
             
         }
         
         
         $this->_redirect("/admin/users");
    }

    
    
    public function elementshowAction(){
        
         $mapid = $this->_getparam('mapid',0);
        $xCoord = $this->_getparam('xcoord',0);
        $yCoord = $this->_getparam('ycoord',0);
        
        
        $this->_redirect($this->view->url(array('controller'=>'manage','action'=>'elementshow',
            'mapid'=>$mapid,
            'xcoord'=>$xCoord,
            'ycoord'=>$yCoord
            ),null,true ));
        
    }
    
    
    
    public function usersAction(){
        
         $form = new Application_Form_User();
         $this->view->form = $form;
         
         $this->view->layout()->title .= " - Utilisateurs ";
         
          $userModel = new Model_DbTable_User();
          
          $this->view->list = $userModel->fetchAll();
         
         if ($this->getRequest()->isPost())
		{
            		$formData = $this->getRequest()->getPost();
                       
                       $form->populate($formData);
                        $reponses = $form->getValues();
                        $reponses=$reponses[$form->getName()];
                        $isEdit =$reponses['edit']; 
                        
                        
                        if($isEdit){
                                 $form = new Application_Form_User(array('edit'=>1));
                        }
                            
                        //Contrôle automatique ZendForm
			if ($form->isValid($formData))
			{
                            unset($reponses['edit']);
                            	//Récupération des  données du formulaire
                            
                           if($reponses['password'])
                                    $reponses['password']=md5($reponses['password']);
                            else
                                    unset($reponses['password']);
                            
                            if($isEdit)
                            {
                               $userModel->updateUser($reponses);
                            }
                            else{
                               $userModel->addUser($reponses);
                                }
                                    
				
			}else
                        {
                             $reponses = $form->getValues();
                            $reponses=$reponses[$form->getName()];
                            foreach( $form->getMessages() as $message){
                                
                             
                                    var_dump($message);
                                
                                
                            }
                            
                        }
		}
         
         
        
    }
    
    public function adduserAction(){
        
         
        
        $id= $this->_getparam('id',0);
        if($id){
            $form = new Application_Form_User(array('edit'=>1));
            $userModel=new Model_DbTable_User();
            $user = $userModel->fetchRow('id = '.$id);
            $form->populate($user->toArray());
        }else{
            
            $form = new Application_Form_User();
        }
            
         echo $form;
          $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
       
         
    }
    
    public function deleteuserAction(){
         $id= $this->_getparam('id',0);
        if($id){
             $users = new Model_DbTable_User();
             $users->delete('id = '.$id);
             $map2user = new Model_DbTable_Map2user();
             $map2user->delete("userid=$id");
             
        }
        $this->_redirect("/admin/users");
        
        
    }
    
    
    public function addusers2mapAction(){
        
        
        $usermodel = new Model_DbTable_User();
        
        $user2add = array("userid"=> 1, "mapid"=> 5, "role"=> 1);
        
        $usermodel->affectMap2users(array($user2add));
        
         $user2add = array("userid"=> 4, "mapid"=> 5, "role"=> 1);
        
        $usermodel->affectMap2users(array($user2add));
        
        
        $this->_redirect("/admin/users");
        
    }
    
    public function toggleadminAction(){
        
        $userid = $this->_getparam('id',0);
        
        if($userid){
           $userModel = new Model_DbTable_User();
           $user = $userModel->fetchRow("id=$userid");
            if($user->role==Model_DbTable_User::ADMIN)$role=Model_DbTable_User::MEMBER;
            else $role=Model_DbTable_User::ADMIN;
            
            
            $userModel->update(array("role"=>$role),"id=$userid");
            $this->_flashMessenger->addMessage('info|Admin Promu');
        }else{
            $this->_flashMessenger->addMessage('error|Manque un paramètre !');
        
            
        }
       
       $this->_redirect($this->view->url(array("controller"=>"admin","action"=>"users"),null,true));
         
    }
    
    
    
}
?>
