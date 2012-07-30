<?php

class ManageController extends Zend_Controller_Action
{

    public function init()
    {
        
         /* Initialize action controller here */
         $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        
           $this->initView();
           
           $this->view->messages = $this->_flashMessenger->getMessages();
           
           $userModel = new Model_DbTable_User();
           $this->view->currentUser = $userModel->getCurrentUser();
       
       
     
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
            
            if((!$userModel->isManager($user->id))&&($user->role!=Model_DbTable_User::ADMIN)){
                $this->_helper->redirector('index','login');
             }
                 
            }
            
            
            
            $this->view->layout()->toolbar = $this->view->toolbar();
       
    }

 

    public function indexAction()
    {
         $maps = new Model_DbTable_Map();
        
         $this->view->mapList = $maps->getAllMaps(Model_DbTable_User::ADMIN);
         $this->view->layout()->subtitle="Liste des Campagnes";
         $this->view->layout()->summary=<<<EOD
                 Cette page présente les différentes Campagnes que vous maitrisez.<br/> 
                 Vous pouvez modifier les paramètres de vos campagnes, et y gérer la liste des joueurs.
                 
EOD;
        /* if(count($this->view->mapList)==1)
            $this->_redirect($this->view->url(array('controller' => 'manage', 'action' => 'map', 'mapid' => $this->view->mapList[0]->mapid),null,true));
        */
              
    }
    
    
    
    public function mapAction(){
        //gestion de la carte
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
        
              
        $elements->addElement($data,1);
        }
           
         
      
        
       $this->_redirect("/manage/map/mapid/".$mapid);
    }
    
     public function deleteallelementsAction(){
        
        $mapid = $this->_getParam('mapid',0);
        $elements = new Model_DbTable_Element();
        $elements->deleteAllElements($mapid);
        $this->_redirect("/manage/map/mapid/".$mapid);
    }
   
    
    public function usersAction(){
        //gestion des utilisateurs;
          
        
        $mapid = $this->_getParam('mapid',0);
        if(!$mapid)$this->_redirect("/manage/");
        $usermodel = new Model_DbTable_User();
        
        $this->view->layout()->subtitle="Liste des Joueurs";
         $this->view->layout()->summary=<<<EOD
                 Vous trouverez ici la liste des joueurs de votre campagne.<br/>
                 Lors d'un ajout, un mail sera envoyé au joueur le prévenant de son inscription à la campagne.<br/>
                 Lors d'une suppression toutes les données liées au joueur pour cette campagne seront supprimées.
                    
                 
EOD;
       
        
        $this->view->mapid=$mapid;
        //$this->view->list = $usermodel->getAllUsersByMap($mapid);
        
        $this->view->list = $usermodel->getPlayers($mapid);
        
    }
    
    public function displayEmailForm($mapid){
        
         $emailForm = <<<EOD
           
   <div class="modal-title-hidden hide">Nouveau Joueur</div>
        <div class="alert alert-info">
           Si le joueur est déjà inscrit, il sera automatiquement ajouté à votre campagne, dans le cas contraire, une invitation lui sera envoyée par mail.
        </div>
            <form class="well form-search">
                <div id="error" class="alert alert-error hide">
                        adresse mail non valide !
                </div> 

<label>e-mail :</label>
                
<input type="text" id="email" name="email" class="input-medium search-query">
                               

                <button type="submit" class="btn">Envoyer</button>
                
            </form>  
            

EOD;
        $emailScript= <<<EOD
         
         function IsValidEmail(email)
            {
                var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
                return filter.test(email);

            }

         
$(document).ready(function(){       
    $(".btn").click(function() {  
       var email = $("input#email").val();  
        
         if ( (email == "") || (!IsValidEmail(email)) )
        {
  
            $("#error").show();  
            $("input#email").focus();  
            return false;  
        }
        var dataString = '&email=' + email;  
        $.post("/manage/adduser/mapid/{$mapid}", dataString, function(data, textStatus) {
                    $("#modal .modal-body").html(data); }
                    , "html");
      
        return false;                    


     });      

});
EOD;
        
        echo $emailForm;
        
        echo $this->view->headScript()->appendScript($emailScript);
       
        
    }
    
    public function adduserAction(){
                
        // un on propose un email
         $mapid = $this->_getParam('mapid',0);
       $usermodel = new Model_DbTable_User();
       $currentUser = $usermodel->getCurrentUser();
        
       
         if ($this->getRequest()->isPost())
		{
                    $formData = $this->getRequest()->getPost();
			//Contrôle automatique ZendForm
                    if($user = $usermodel->fetchRow("email='{$formData['email']}'"))
                        {   // mail inconnu, on créé le user vide et on envoie l'invit
                            $userid = $user->id;
                           
                            // envoi de mail !
                            
                            $this->view->mail('addPlayer',$formData['email'],$currentUser,$mapid);
                            
                         }else{
                             $userid = $usermodel->insert(array('email'=>$formData['email']));
                              
                             //envoi du message
                             
                            $this->view->mail('invitation',$formData['email'],$currentUser,$mapid);
                            
                            
                         }
                          $this->_flashMessenger->addMessage("info|Utilisateur ajouté");
                             $usermodel->addUserToMap($mapid,$userid);
                          
                 // affichage du message
                          
                        
                // retour a la page
                        
                echo "<script>location.reload();</script>";
                        
                        
		}else{
        $this->displayEmailForm($mapid);
       
                    
              }
         $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
    }
    
    public function removeuserAction(){
        $mapid = $this->_getparam('mapid',0);
        $userid = $this->_getparam('userid',0);
        
        if($mapid && $userid){
            $map2userModel = new Model_DbTable_Map2user();
            $map2userModel->delete("mapid={$mapid} AND userid={$userid}");
            
        }
        
        
        $this->_flashMessenger->addMessage('info|Joueur Retiré !');
        $this->_redirect($this->view->url(array("controller"=>"manage","action"=>"users","mapid"=>$mapid),null,true));
        
        
    }
    
    public function makemanagerAction(){
        
        $mapid = $this->_getparam('mapid',0);
        $userid = $this->_getparam('userid',0);
        
        if($mapid && $userid){
            $map2userModel = new Model_DbTable_Map2user();
            $map2userModel->update(array('role'=> Model_DbTable_User::ADMIN),"mapid={$mapid} AND userid={$userid}");
            $this->_flashMessenger->addMessage('info|Manager Promu');
        }else{
            $this->_flashMessenger->addMessage('error|Manque un paramètre !');
        
            
        }
       
       $this->_redirect($this->view->url(array("controller"=>"manage","action"=>"users","mapid"=>$mapid),null,true));
         
    }
    
    public function unmakemanagerAction(){
        
        $mapid = $this->_getparam('mapid',0);
        $userid = $this->_getparam('userid',0);
        
        if($mapid && $userid){
            $map2userModel = new Model_DbTable_Map2user();
            $map2userModel->update(array('role'=> Model_DbTable_User::MEMBER),"mapid={$mapid} AND userid={$userid}");
            
        }
       
       $this->_redirect($this->view->url(array("controller"=>"manage","action"=>"users","mapid"=>$mapid),null,true));
         
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
        $title = ($element)?"Modification":"Création";
        echo "<div class='modal-title-hidden hide'>".$title."</div>";
        echo '<div class="clear:both">&nbsp;</div>';
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        
    }
    

}

