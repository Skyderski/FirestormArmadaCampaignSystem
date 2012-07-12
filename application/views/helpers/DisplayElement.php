<?php

class Zend_View_Helper_DisplayElement extends Zend_View_Helper_Abstract{

    public function displayElement($element){
        
        
        $elementModel = new Model_DbTable_Element();
        $types = $elementModel->getTypes();
        $type = $types[$element['type']]['name']; 
        
        $status ="";
        if($element['status'])$status = "Status : {$element['status']}";
        
        
        $output=<<<EOD
        
        <h2>{$element['name']}</h2>
            <div class="elementimg">
                <img src="/images/elements/{$element['image']}" />
            </div>
            <div id="description">
                {$type}<br/>
                {$status}
                
            </div>
            
                <fieldset id="fieldset-attributes">
                <legend>Attributs</legend>
            <dl class="attributes">
                <dt class="pop">population</dt><dd>{$element['population']}</dd>
                <dt class="tech">tech</dt><dd>{$element['tech']}</dd>
                <dt class="defence">défense</dt><dd>{$element['defences']}</dd>
                <dt class="ressources">ressources</dt><dd>{$element['ressources']}</dd>
            </dl>
            </fieldset>
        
EOD;
        
        return $output;
        
    }
  
}