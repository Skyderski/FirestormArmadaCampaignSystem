<?php

class Model_DbTable_User extends Zend_Db_Table_Abstract implements Zend_Acl_Role_Interface
{
    protected $_aclRoleId = null;
    protected $_name = 'users';
    protected $_primary = 'id';
 
    public function getRoleId()
    {
        if ($this->_aclRoleId == null) {
            return 'guest';
        }
 
        return $this->_aclRoleId;
    }

    
    
    public function getAllUsers(){
        
        return $this->fetchAll();
        
        
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
    
    public function addUser($data)
    {
        $this->insert($data);
    }
    
    public function updateUser($data){
        
        $this->update($data, 'id='.$data['id']);
    }
    
    public function deleteUser($id){
        
        $this->delete('id='.$id);
    }
    
    
}
?>