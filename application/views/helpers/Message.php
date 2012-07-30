<?php

class Zend_View_Helper_Message extends Zend_View_Helper_Abstract{

    public function message(){
        
       $messages= $this->view->messages;
        
       if($messages){
           $output="";
           foreach($messages as $message){
            $messageArray = explode('|', $message);
            $type = $messageArray[0];
            $messageContent = $messageArray[1];

    
            $output .= <<<EOD

<div class="alert alert-{$type}">{$messageContent}</div>

                    
EOD;
           }
echo $output;
       }
            
    }
        
        
        
        
        
  
}