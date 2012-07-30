<?php

class Zend_View_Helper_Toolbar extends Zend_View_Helper_Abstract{

    public function toolbar(){
        
        $list = $this->renderListElement("main",0,"nav pull-right");
        
       
        
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
        
        echo $output;
        
    }
    
    
    public function renderListElement($category,$parent=0,$options){
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        
        $userModel = new Model_DbTable_User();    
        $menuModel = new Model_DbTable_Menu();
        
        
        $select = $menuModel->select()->order("order ASC");
        
        $select->where("category='$category'")
            ->where("parent=$parent")
                ->where("active=1");
          
        if(!$identity)$select->where("role=-1");
        else{
              $user = $userModel->getUserByName($identity);
            if($user->role==Model_DbTable_User::ADMIN)   // ADMIN
                $select->where("role<=2");
            elseif($userModel->isManager($user->id))    // Manager
               $select->where("role<=1");
            else $select->where("role<=0");             // joueur
        }
        
                $list ="<ul class='$options'>";
        
        $menuList = $menuModel->fetchAll($select);
        if(!$menuList->count())return false;
        
        foreach($menuList as $menu){
            
            if($menu->params)$params = $this->buildParams($menu->params);
                $params['controller']=$menu->controller;
                $params['action']= $menu->action;

            
            if($this->isDynamic($menu))
                $list.= $this->makeDynamicMenu($menu);
            else{
                //classic
                
                //if($params['dynamic'])

                $url =  $this->view->url($params,null,true);

                    $sublist = $this->renderListElement($category,$menu->id,"dropdown-menu");
                if($sublist){

                    $list.=<<<EOD
                        <li class="dropdown">
                            <a href="#" data-target="#"
                                class="dropdown-toggle"
                                data-toggle="dropdown">
                                {$menu->label}
                                <b class="caret"></b>
                            </a>
                                {$sublist}                        

                        </li>    
EOD;

                }else{
                    $list .="<li><a href='$url' >{$menu->label}</a></li>";
                }

            }
        }
        
         $list .="</ul>";
        return $list;
        
    }
    
    public function buildParams($textParams){
        
        // format :
        // cle:valeur\n
        
        $coupleArray = explode("\n", $textParams);
        $params = array();
        foreach($coupleArray as $couple){
            
            $values = explode(":", $couple);
            $params[$values[0]]=$values[1];
            
        }
        
        
        return $params;
        
    }
    
    public function isDynamic($menu){
        $regexp="/#.*$/";
        return preg_match($regexp, $menu->label, $matches);
        
    }
    
    
    public function makeDynamicMenu($menu){
        
        
        switch($menu->label){
            
            case '#MANAGE_MAP' :
                $mapModel = new Model_DbTable_Map();
                $maplist = $mapModel->getAllMaps(Model_DbTable_User::ADMIN);
                if($maplist->count()){
                    $list .="<li class='divider'></li>";
                    foreach($maplist as $map)
                    {
                        $url =  $this->view->url(array('controller'=>'manage','action'=>'map','mapid'=>$map->id),null,true);
                        $list .="<li><a href='$url' >{$map->name}</a></li>";
                    }
                }
                break;
           default: return false;
               break;
                
        }
                
                return $list;
        
    }
    
  
  
}