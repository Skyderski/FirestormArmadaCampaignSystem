<?php

class Zend_View_Helper_DisplayElement extends Zend_View_Helper_Abstract{

    public function displayElement($element){
        
        
        $elementModel = new Model_DbTable_Element();
        $types = $elementModel->getTypes();
        $type = $types[$element['type']]['name']; 
        
        $status ="";
        if($element['status'])$status = "Status : {$element['status']}";
        
        
        $output=<<<EOD
        <div class="modal-title-hidden hide">{$element['name']}</div>
        <div class="row-fluid">
            <div class="span4">
                <img src="/images/elements/{$element['image']}" />
            </div>
            <div class="span8">
                <div class="row-fluid">
                    <div class="span2">
                  {$type}
                    </div>
                    <div class="span4 offset2">
                        {$status}
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                         <div class="btn-group">
                            <span class="btn btn-info" title="Population">
                                <i class="icon-user icon-white"></i><br/>{$element['population']}
                            </span>
                            <span class="btn btn-info" title="Tech">
                                <i class="icon-cog icon-white"></i><br/>{$element['tech']}
                            </span>
                            <span class="btn btn-info" title="Défense">
                                <i class="icon-warning-sign icon-white"></i><br/>{$element['defences']}
                            </span>
                            <span class="btn btn-info" title="Ressources">
                                <i class="icon-tint icon-black"></i><br/>{$element['ressources']}
                            </span>
                         </div> 
                               
                    </div>
                    
                </div>  
                
            </div>    
            
                  
        </div>
        <div class="row-fluid">
            <div class="span4">
                
            </div>
            <div class="span8">
               
                
            </div>
           
                
                 
                    
          </div>      
            
        
EOD;
        
        return $output;
        
    }
  
}