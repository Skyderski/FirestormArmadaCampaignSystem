<?php
class Model_DbTable_Map extends Zend_Db_Table_Abstract
{
    protected $_name = 'maps';
    protected $_primary = 'id';
    
    
    public function getAllMaps($identity=null){
        
        
        if($identity){
            $userModel = new Model_DbTable_User();
            $user = $userModel->getUserByName($identity);
           
           $select = $this->select("maps")->setIntegrityCheck(false)->join('map2user', 'mapid=maps.id')
                   ->join("users",'map2user.userid=users.id');
        }else
        {
            
            return $this->fetchAll();
        }
        
      return $this->fetchAll($select);
        
        
    }
    
    public function eraseAll(){
        
        // $this-> SHOW TABLES;
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
        
        $this->delete('id='.$id);
    }
    
    
}
?>