<?php

class Zend_View_Helper_Toolbar extends Zend_View_Helper_Abstract{

      
    public function toolbar(){
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        $userModel = new Model_DbTable_User();
        $role = $userModel->getRoleFromIdentity($identity);
        
         //$this->view->headScript()->prependScript("$(document).ready(function(){ $('.dropdown-toggle').dropdown() }");
       
        
        
        $list ='<ul class="nav pull-right">';
        
        foreach($this->makeMainLinks($role) as $link){
        
            $url = $this->view->url(array('controller' => $link['controller'], 'action' => $link['action']),null,true);
                
            if($link["content"]){
                
                $sublist="";
                foreach($link["content"] as $sublink){
                    
                    $suburl = $this->view->url(array('controller' => $sublink['controller'], 'action' => $sublink['action']),null,true);
                    
                    $sublist.="<li><a {$sublink['options']} href='{$suburl}'  >{$sublink['label']}</a></li>";
                    
                }
                
              $list.=<<<EOD
                <li class="dropdown">
                    <a href="#" data-target="#"
                        class="dropdown-toggle"
                        data-toggle="dropdown">
                        {$link['label']}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        {$sublist}                        
                    </ul>
                </li>    
EOD;
            }else{
                
                
            $list .="<li><a {$link['options']} href='".$url."' >".$link['label']."</a>"
                    
                    
                    ."</li>";
            }
        
        }
        
        
        
        $list .="</ul>";
        
         $output=<<<EOD
            <div class="navbar navbar-fixed-top">
                 <div class="navbar-inner">
                    <div class="container">
                        <a class="brand" href="#">
                            {$this->view->layout()->title}
                        </a>
                         $list

                    </div>
                </div>
            </div>     
   
EOD;
        
       
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
        $links[]= array('label'=>"Accueil",'controller' => 'index', 'action' => 'index');
        
        //profil
        $links[]= array('label'=>"Profil",'controller' => 'index', 'action' => 'profil',
            'options'=>"data-toggle='modal' data-target='#modal' id='profile'");
        
        $script=<<<EOD
            $(document).ready(function(){
                $("a#profile").click(function() { 
                    $.get("/index/profile", {}, function(data, textStatus) { $("#modal .modal-body").html(data); }, "html");

                    $('#modal').modal('show')
                    return false; 

                    });

            });
 
        
EOD;
        
        $this->view->headScript()->offsetSetScript(100,$script);
       
        
        
        if($role==Model_DbTable_User::ADMIN)
        {
           $links[]= array('label'=>"Administration",'controller' => 'admin', 'action' => 'index',"content"=>$this->makeAdminLinks($role));
            
        }
        
        $links[]= array('label'=>"Gestion",'controller' => 'manage', 'action' => 'index',"content"=>$this->makeManageLinks());
        
        
        
        // logout
        $links[]= array('label'=>"Déconnection",'controller' => 'login', 'action' => 'logout');
        
        
       // 
        
        
        
        return $links;
        
    }
    
     public function makeGameLinks($role){
        
        $links = array();
            
       
        return $links;
        
    }
    
    public function makeManageLinks(){
        $links = array();
       
            
        // home
        $links[]= array('label'=>"Utilisateurs",'controller' => 'manage', 'action' => 'userslist');
        
        //profil
        $links[]= array('label'=>"Cartes",'controller' => 'manage', 'action' => 'maplist');
        
         return $links;
    }
    
    public function makeAdminLinks($role){
        
        $links = array();
            
        // home
        $links[]= array('label'=>"Utilisateurs",'controller' => 'admin', 'action' => 'userslist');
        
        //profil
        $links[]= array('label'=>"Cartes",'controller' => 'admin', 'action' => 'maplist');
        
        
        return $links;
        
    }
  
}