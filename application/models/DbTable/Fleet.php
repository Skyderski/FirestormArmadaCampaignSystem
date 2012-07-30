<?php
class Model_DbTable_Fleet extends Zend_Db_Table_Abstract
{
    protected $_name = 'fleet';
    protected $_primary = 'id';
    
    
        
    public function getMap($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row ;
    }   
    
}

class Model_DbTable_Factions extends Zend_Db_Table_Abstract
{
    protected $_name = 'factions';
    //protected $_primary = 'id';
 
    public function getFactionName($id){
        
        return $this->fetchRow("id=$id")->name;
    }
   
    
}



?>