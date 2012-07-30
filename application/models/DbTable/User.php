<?php

class Model_DbTable_User extends Zend_Db_Table_Abstract 
{
    protected $_name = 'users';
    protected $_primary = 'id';
    
     
    const MEMBER =0;
    const ADMIN =1;
    
    
 
    
    public function getRoleFromIdentity($identity){
        
        $user = $this->fetchRow("username = '$identity'");
        
        return $user->role;
    }
    
    
    public function isManager($userid,$mapid=0){
        
        $db = Zend_Db_Table::getDefaultAdapter();
        
        $map2userModel = new Model_DbTable_Map2user();
        $adminRole = Model_DbTable_User::ADMIN;
        
        $select = $map2userModel->select()->where("userid={$userid}")->where("role={$adminRole}");
        
        
        if($mapid)$select->where("mapid={$mapid}");

        return $map2userModel->fetchAll($select)->count();
        
    }
    
    
    
    public function getCurrentUser(){
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        
        return $this->getUserByName($identity);
        
    }
    
    
    
    public function getRoleForMap($userid,$mapid=0)
    {
        
        $db = Zend_Db_Table::getDefaultAdapter();
        
        
        $select = $db->select()->from("map2user")->where("userid=".$userid);
        
        if($mapid)
                $select->where("mapid=".$mapid);
        
        $stmt =$db->query($select);
        $row = $stmt->fetch();
 
        return $row->role;
        
        
        
    }
    
    public function getUser($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row ;
    }  
    
     public function getUserByName($username)
    {
        $row = $this->fetchRow('username = "' . $username.'"');
        if (!$row) {
            throw new Exception("Count not find row $username");
        }
        return $row ;
    }   
    
    public function addUser($data)
    {
        $this->insert($data);
    }
    
    public function updateUser($data){
        
        echo $id;
        
        $this->update($data, 'id='.$data['id']);
    }
    
    public function deleteUser($id){
        
        $this->delete('id='.$id);
    }
    
    
    public function addUserToMap($mapid,$userid,$role=0){
        
       $map2user= new Model_DbTable_Map2user();
       
       if(!$map2user->fetchRow("userid={$userid} AND mapid={$mapid}"))
       {
       
         $map2user->insert(array("userid"=>$userid,
                "mapid"=>$mapid,
                "role"=>$role,
            
             "ressources"=>0,
             "faction"=>0
                ));
          
            
        }
        
        
        
    }
    
    public function getPlayers($mapid){
         
        $db = Zend_Db_Table::getDefaultAdapter();
        
        $map2userModel = new Model_DbTable_Map2user();
        
        $select = $this->select()->setIntegrityCheck(false)->from('users','users.*')
                ->joinLeft('map2user',"users.id=map2user.userid",array("ressources","faction","role as mapRole"))
                ->joinLeft('factions',"map2user.faction=factions.id",array("factions.name as factionName","logo as factionLogo","logo_small as factionLogo_small" ))
                ->where('map2user.userid=users.id')
                ->order("mapRole DESC");
        
       // echo $select;
        return $this->fetchAll($select);
        
    
            
     }
    
     public function getManager($map){
        
        $map2userModel = new Model_DbTable_Map2user();
        
        $roleManager = Model_DbTable_User::ADMIN;
        $subselect = $map2userModel->select()->from('map2user','map2user.userid as id')->where("mapid={$map->id} AND role={$roleManager}");
            
        return $this->fetchRow("id IN ($subselect)");
    }
    
      
    
    
}

class Model_DbTable_Map2user extends Zend_Db_Table_Abstract
{
    protected $_name = 'map2user';
 
    public function getPlayerNumber($map){
        return count($this->fetchAll("mapid={$map->id}"));
        
    }
    
    
    
}

class Model_DbTable_Menu extends Zend_Db_Table_Abstract
{
    protected $_name = 'menu';
 
   
    
}

?>