<?php
class Application_Form_User extends Zend_Form
{
	
	protected $_id=0;
    
 	public function __construct($options = null)
    {
    	if($options['id']){
    		$this->_id= $options['id'];
    	}
        
        if($options['password_salt'])
            $this->_password_salt = $options['password_salt'];
      /*  else
        {
              for ($i = 0; $i < 50; $i++) {
                $dynamicSalt .= chr(rand(33, 126));
            }
           $this->_password_salt = $dynamicSalt;
        }
        */    
        
    	parent::__construct($options);
    }
    
    
    
    
    public function init()
    {
         $this->setName('elements');
        $this->setIsArray(true);
        //Tableau contenant les champs
        $form = array();
	
        $id = new Zend_Form_Element_Hidden('id');
        $id->setDecorators(array('ViewHelper'));
		$form[] = $id;
        
      
        
        $salt= new Zend_Form_Element_Hidden('password_salt');
        $salt->setDecorators(array('ViewHelper'));
        $form[]=$salt;
        

       		
        $name = new Zend_Form_Element_Text('username');
        $name->setLabel('Nom d\'utilisateur*')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('stringLength', false, array(6, 20))	
                ->addValidator(new Zend_Validate_Alnum())
                ->addValidator('regex', false, array('/^[a-z]+/'))
              ->addValidator('NotEmpty')
                   ->addFilter('StringToLower');
         $form[] =$name;

         $password = new Zend_Form_Element_Password('password');
         $password->setLabel('Mot de passe*')
                 ->setRequired(true)
                 ->addFilter('StripTags')
                 ->addValidator ( new Zend_Validate_StringLength(6,20) )		
                 ->addValidator('NotEmpty');
          $form[] =$password;
          
          $email = new Zend_Form_Element_Text('email');
          $email->setLabel('e-mail*')
                  ->addValidator(new Zend_Validate_EmailAddress());
          $form[] =$email;
        
        	
		$cbActive= new Zend_Form_Element_Checkbox('active');
		$cbActive->setLabel('Actif')->setValue(1);
		$form[] =	$cbActive;
		
		
        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
        $envoyer->setAttrib('class', 'bt_connexion');
		//$formulaireElement[] =$envoyer;
		
        $this->addElements($form);
        
         $this->addElements(array($envoyer));
    }
}
?>