<?php
class Model_DbTable_Map extends Zend_Db_Table_Abstract
{
    protected $_name = 'maps';
    protected $_primary = 'id';
    
    
    public function getAllMaps(){
        
        return $this->fetchAll();
        
        
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