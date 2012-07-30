<?php

class Zend_View_Helper_Mail extends Zend_View_Helper_Abstract{

    public function mail($type,$email,$sender,$mapid){
        
        
        switch($type){
            
            case 'invitation':
                
                    $mailContent = $this->getInvitationMail($email,$sender,$mapid);
                break;
            case 'addPlayer':
                $mailContent = $this->getAddPlayerMail($email,$sender,$mapid);
                 break;
        }
        
        
        
        
        $mail = new Zend_Mail();
        
        $mail->setFrom('FACT@antsnest.fr', 'FA Campaign Tool');
        $mail->addTo($email);
        $mail->setReplyTo($sender->email);
        //$mail->setBodyText($mailContent['bodyText']);
        $mail->setBodyHtml($mailContent['bodyHtml']);
        
        $mail->setSubject($mailContent['subject']);
        
        if(!strcmp($_SERVER['HTTP_HOST'],"localhost:27"))
               echo "mail sent !";
        else
            $mail->send();
        
        //$this->view->_flashMessenger->addMessage("Mail envoyé !");
                 
    }
    
    public function getInvitationMail($email,$sender,$mapid){
        
        
    $subject = "Invitation Campagne Firestorm Armada";
    
    
    $code = md5(uniqid(mt_rand(), true));
    $usermodel = new Model_DbTable_User();
    $mapModel = new Model_DbTable_Map();
    $map = $mapModel->fetchRow("id=$mapid");
    
    $usermodel->update(array('password'=>$code), "email='$email'");
    
    $link2activate = $this->view->url(array("controller"=>"index","action"=>"activation","code"=>$code));
    $bodyText="";
    $bodyHtml=<<<EOD
    
<p>Bonjour,</p>
<p>Vous venez d'être invité par {$sender->username} à participer à la campagne Firestorm Armada "{$map->name}" sur le site <a href='http://{$_SERVER['HTTP_HOST']}'>http://{$_SERVER['HTTP_HOST']}</a>. </p>

<p>Pour valider votre inscription, veuillez vous rendre sur ce lien : <a href="http://{$_SERVER['HTTP_HOST']}/$link2activate">http://{$_SERVER['HTTP_HOST']}{$link2activate}</a> et completer le formulaire d'inscription.</p>

<p>Bon jeu !</p>

<p>L'équipe FACT</p>

    
    
EOD;

$bodyHtml = htmlentities($bodyHtml, ENT_NOQUOTES, "UTF-8");
$bodyHtml = htmlspecialchars_decode($bodyHtml);
$bodyText = $bodyHtml;


$bodyHtml= "<html> <body >".$bodyHtml."</body></html>"; 
    
                
    return array("subject"=>$subject ,
        "bodyHtml"=>$bodyText,
        "bodyText"=>$bodyHtml);
    }
    
     public function getAddPlayerMail($email,$sender,$mapid){
        
        
    
    
    
    $code = md5(uniqid(mt_rand(), true));
    $mapModel = new Model_DbTable_Map();
    $map = $mapModel->fetchRow("id=$mapid");
    
    $subject = "Ajout Campagne {$map->name}";
    
    $bodyText="";
    $bodyHtml=<<<EOD
    
<p>Bonjour,</p>
    <p>Vous venez d'être ajouté par {$sender->username} à participer 
à la campagne Firestorm Armada "{$map->name}" 
sur le site <a href='http://{$_SERVER['HTTP_HOST']}'>http://{$_SERVER['HTTP_HOST']}</a>. 
    </p>


<p>Bon jeu !</p>

<p>L'équipe FACT</p>

    
    
EOD;

$bodyHtml = htmlentities($bodyHtml, ENT_NOQUOTES, "UTF-8");
$bodyHtml = htmlspecialchars_decode($bodyHtml);
$bodyText = $bodyHtml;


$bodyHtml= "<html> <body >".$bodyHtml."</body></html>"; 
    
                
    return array("subject"=>$subject ,
        "bodyHtml"=>$bodyText,
        "bodyText"=>$bodyHtml);
    }
  
}