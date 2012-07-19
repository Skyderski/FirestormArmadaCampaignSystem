<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
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
    
    public function updatedatabaseAction(){
        
         $db = Zend_Db_Table::getDefaultAdapter();
         
          $sql=<<<EOD
      ALTER TABLE `elements` ADD COLUMN `status` TEXT NOT NULL AFTER `active`;
      CREATE TABLE users (
    id INTEGER  NOT NULL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(32) NULL,
    password_salt VARCHAR(32) NULL,
    email VARCHAR(150) NULL
);
CREATE TABLE `session` (
    `session_id` char(32) NOT NULL,
    `save_path` varchar(32) NOT NULL,
    `name` varchar(32) NOT NULL DEFAULT '',
    `modified` int,
    `lifetime` int,
    `session_data` text,
    PRIMARY KEY (`Session_ID`, `save_path`, `name`)
);

EOD;
         $db->query($sql);
         
          $this->_redirect("/admin/maplist");
    }
    
    public function initdbAction(){
        
        $db = Zend_Db_Table::getDefaultAdapter();
        $stmt =$db->query("SHOW TABLES");
        
 
        $rows = $stmt->fetchAll();
        foreach($rows as $row){
            
            $db->query("Drop table ".$row['Tables_in_282300-7']);
                 
        }
        
        $sql=<<<EOD
      CREATE TABLE  `elements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `mapid` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `Xcoord` int(10) unsigned NOT NULL,
  `Ycoord` int(10) unsigned NOT NULL,
  `ressources` int(10) unsigned NOT NULL,
  `tech` int(10) unsigned NOT NULL,
  `defences` int(10) unsigned NOT NULL,
  `image` varchar(100) NOT NULL,
  `active` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1
EOD;
         $db->query($sql);
        
        $sql=<<<EOD
        CREATE TABLE  `maps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `active` int(10) unsigned NOT NULL,
  `background` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1
        
EOD;
        $db->query($sql);
        
        
        
          
        
       
       $this->_redirect("/admin/maplist");
        
    }
    
    public function maplistAction()
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
        $this->_redirect("/admin/maplist");
    }
    
    public function deletemapAction(){
        
        $id= $this->_getparam('mapid',0);
        if($id){
             $maps = new Model_DbTable_Map();
             $maps->delMap($id);
        }
        $this->_redirect("/admin/maplist");
        
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
            
            $this->_redirect("/admin/maplist");
        }
    }
    
    public function switchactivationAction(){
        
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
         
         
         $this->_redirect("/admin/maplist");
    }
    
    public function elementlistAction(){
        
        $mapid = $this->_getParam('mapid',0);
        
        $maps = new Model_DbTable_Map();
        $elements = new Model_DbTable_Element();
        $this->view->map =  $maps->getMap($mapid);
        
         $this->view->layout()->title .= " - Carte : ".$this->view->map->name;
        
        
        
        $form = new Application_Form_Element();
        $form->envoyer->setLabel('Ajouter');
        $form->setDefaults(array('mapid'=>$mapid,'active'=>1));
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost())
		{
            		$formData = $this->getRequest()->getPost();
			//Contrôle automatique ZendForm
			if ($form->isValid($formData))
			{
				//Récupération des  données du formulaire
				$reponses = $form->getValues();
				$reponses=$reponses[$form->getName()];

				$elements->addElement($reponses);
				
			}
		}
        $this->view->elementList = $elements->getAllElement($mapid);
        
        
        
    }
    
    public function elementshowAction(){
        
        $mapid = $this->_getparam('mapid',0);
        $xCoord = $this->_getparam('xcoord',0);
        $yCoord = $this->_getparam('ycoord',0);
        
        $modelElement = new Model_DbTable_Element();
        $element = $modelElement->getElementFromCoordArray($mapid, $xCoord, $yCoord);
      
        $form = new Application_Form_Element();
        $form->envoyer->setLabel('Enregistrer');
        
        $this->view->form = $form;
        
        if($element)
            $form->populate($element);
        else
            $form->populate(array("Xcoord"=>$xCoord,"Ycoord"=>$yCoord,"mapid"=>$mapid));
        
        echo $form;
        
        echo '<div class="clear:both">&nbsp;</div>';
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        
    }
    
    
    public function createrandomelementAction(){
        
        $mapid = $this->_getParam('mapid',0);
        $density = $this->_getParam('density',0);
        
        $maps = new Model_DbTable_Map();
        $map = $maps->getMap($mapid);
        
       
        
        $nb = ($map->width * $map->height)*$density/100;
         
         $elements = new Model_DbTable_Element();
        
         $elements->getImages();
        
         
         
         $elements = new Model_DbTable_Element();
         $images = $elements->getImagesByTypes();
         $types = $elements->getTypes();
         
        for($i=0;$i<$nb;$i++){
            
            $type = array_rand($types);
            
             $image = $images[$types[$type]['image']][array_rand($images[$types[$type]['image']])];

            
         $data = array(
                    'mapid' => $map->id, 
                    'name'      =>  $elements->makeRandomName(),
                    'Xcoord' => rand (0,$map->width-1),
                    'Ycoord'      => rand (0,$map->height-1),
                    'ressources' => rand ( 0 , 5 ),
                    'population' => rand ( 0 , 5 ),
                    'tech' => rand ( 0 , 5 ),
                    'defences' => rand ( 0 , 5 ),
                    'image' => $image,
                    'type' => $type,
                    'active' => 1
                );
        
              
        $elements->addElement($data);
        }
           
        /* 
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
       */
        
       $this->_redirect("/admin/elementlist/mapid/".$mapid);
    }
    
    public function deleteallelementsAction(){
        
        $mapid = $this->_getParam('mapid',0);
        $elements = new Model_DbTable_Element();
        $elements->deleteAllElements($mapid);
        $this->_redirect("/admin/elementlist/mapid/".$mapid);
    }
    
    
    public function userslistAction(){
        
         $form = new Application_Form_User();
         $this->view->form = $form;
         
         $this->view->layout()->title .= " - Utilisateurs ";
         
          $userModel = new Model_DbTable_User();
          
          $this->view->list = $userModel->getAllUsers();
         
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
        }
        $this->_redirect("/admin/userslist");
        
        
    }
    
    
    public function addusers2mapAction(){
        
        
        $usermodel = new Model_DbTable_User();
        
        $user2add = array("userid"=> 1, "mapid"=> 5, "role"=> 1);
        
        $usermodel->affectMap2users(array($user2add));
        
        
        $this->_redirect("/admin/userslist");
        
    }
    
    
    
}
?>
