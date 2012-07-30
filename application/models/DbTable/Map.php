<?php
class Model_DbTable_Map extends Zend_Db_Table_Abstract
{
    protected $_name = 'maps';
    protected $_primary = 'id';
    
    
    public function getAllMaps($role=0){
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        
        if($identity){
            
            $usermodel = new Model_DbTable_User();
            $user = $usermodel->fetchRow("username='$identity'");
            
            if($user->role==Model_DbTable_User::ADMIN){
                
                return $this->fetchAll();
            }
            
            $map2userModel = new Model_DbTable_Map2user();
            $subselect = $map2userModel->select()->from('map2user','map2user.mapid as id')->where("userid={$user->id}");
            
            if($role)$subselect->where('map2user.role='.$role);
            
            $mapidsArray = array();
           
            return $this->fetchAll("id IN ($subselect)");
           
        }
        
        
        
    }
    
        
    public function getMap($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row ;
    }   
    
    public function addMap($data)
    {

        
         
        $this->insert($data);
        
        
    }
    
    public function updateMap($data){
        
        $this->update($data, 'id='.$data['id']);
    }
    
    public function delMap($id){
        
        $elements = new Model_DbTable_Element();
        $elements->deleteAllElements($id);
        
        $this->delete('id='.$id);
    }
    
    
    
    
   
    
}
?>