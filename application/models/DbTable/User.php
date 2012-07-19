<?php

class Model_DbTable_User extends Zend_Db_Table_Abstract implements Zend_Acl_Role_Interface
{
    protected $_name = 'users';
    protected $_primary = 'id';
    
     
    const MEMBER =0;
    const ADMIN =1;
    
 
   public function getRoleId()
    {
        if ($this->_aclRoleId == null) {
            return 'guest';
        }
 
        return $this->_aclRoleId;
    }
    
    public function getRoleFromIdentity($identity){
        
        $user = $this->fetchRow("username = '$identity'");
        
        return $user->role;
    }

    
    
    public function getAllUsers(){
        
        return $this->fetchAll();
        
        
    }
    
    public function getAllUsersByMap($mapid){
        
        $db = Zend_Db_Table::getDefaultAdapter();
        
        $select = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                        ->setIntegrityCheck(false);
        $select->join('map2user',
        'map2user.userid= users.id')
            ->where('mapid='.$mapid);
        
        $stmt =$db->query($select);
        
 
        return $stmt->fetchAll();
        
        
    
        
        
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
            throw new Exception("Count not find row $id");
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
    
    
    public function affectMap2users($data){
        
       $map2user= new Model_DbTable_Map2user();
       foreach($data as $currentAffectation){
            
            
            $map2user->insert(array("userid"=>$currentAffectation['userid'],
                "mapid"=>$currentAffectation['mapid'],
                "role"=>$currentAffectation['role']
                ));
            
            
        }
        
    }
    
    
}
?>

<?php


class Model_DbTable_Map2user extends Zend_Db_Table_Abstract
{
    protected $_name = 'map2user';
 
    
    
}



?>