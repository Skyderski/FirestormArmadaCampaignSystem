<?php

class Zend_View_Helper_Element extends Zend_View_Helper_Abstract{

    
    var $typeArray = array(1=>"planète tellurique",
        2 => "planète gazeuse");
    
    
    public function displayImage($imageName){
        
        return $output;
    }
    
    public function displayType($typeCode)
    {
        global $typeArray;
        return $typeArray[$typeCode];
    }
}