<?php

class Zend_View_Helper_Toolbar extends Zend_View_Helper_Abstract{

   
    
    
    public function toolbar(){
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        $userModel = new Model_DbTable_User();
        $role = $userModel->getRoleFromIdentity($identity);
        
        
        $output ="<ul class='toolbar'>";
        
        foreach($this->makeMainLinks($role) as $link){
        
            $output .="<li><a href='".$link['url']."'>".$link['label']."</a></li>";
        
        }
        
        $output .="</ul>";
        
        $output .="<ul class='subtoolbar'>";
        
        if($role==Model_DbTable_User::ADMIN)
        {
            foreach($this->makeSubLinks($role) as $link){

                $output .="<li><a href='".$link['url']."'>".$link['label']."</a></li>";

            }

            $output .="</ul>";
        }
        $output .="<ul class='gametoolbar'>";
        
        foreach($this->makeGameLinks($role) as $link){
        
            $output .="<li><a href='".$link['url']."'>".$link['label']."</a></li>";
        
        }
        
        $output .="</ul>";
        
        
        
        return $output;
    }
    
    public function makeMainLinks($role){
        
        $links = array();
            
        // home
        $links[]= array('label'=>"Accueil",'url'=>$this->view->url(array('controller' => 'index', 'action' => 'index'),null,true));
        
        //profil
        $links[]= array('label'=>"Profil",'url'=>$this->view->url(array('controller' => 'index', 'action' => 'profil'),null,true));
        
        
        if($role==Model_DbTable_User::ADMIN)
        {
           $links[]= array('label'=>"Administration",'url'=>$this->view->url(array('controller' => 'admin', 'action' => 'index'),null,true));
            
        }
        
        
        // logout
        $links[]= array('label'=>"Déconnection",'url'=>$this->view->url(array('controller' => 'login', 'action' => 'logout'),null,true));
        
        
        return $links;
        
    }
    
     public function makeGameLinks($role){
        
        $links = array();
            
       
        return $links;
        
    }
    
    public function makeSubLinks($role){
        
        $links = array();
            
        // home
        $links[]= array('label'=>"Users",'url'=>$this->view->url(array('controller' => 'admin', 'action' => 'userslist'),null,true));
        
        //profil
        $links[]= array('label'=>"Maps",'url'=>$this->view->url(array('controller' => 'admin', 'action' => 'maplist'),null,true));
        
        
        return $links;
        
    }
  
}