<?php
class Application_Form_User extends Zend_Form
{
	
	protected $_id=0;
        protected $_isEdit=0; 
        protected $_activate=0;
        protected $_email="";
        
    
 	public function __construct($options = null)
    {
            if($options['id']){
    		$this->_id= $options['id'];
    	}
        if($options['edit']){
            $this->_isEdit=1;
        }
        if($options['activate'])
            $this->_activate=1;
        
        if($options['email'])
            $this->_email=$options['email'];
        
        
    	parent::__construct($options);
    }
    
    
    
    
    public function init()
    {
         $this->setName('users');
        $this->setIsArray(true);
        //Tableau contenant les champs
        $form = array();
	
        $id = new Zend_Form_Element_Hidden('id');
        $id->setDecorators(array('ViewHelper'));
		
                
        $edit= new Zend_Form_Element_Hidden('edit');
        $edit->setValue($this->_isEdit)
                ->setDecorators(array('ViewHelper'));;
        
      
       		
        $name = new Zend_Form_Element_Text('username');
        $name->setLabel('Nom d\'utilisateur*')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('stringLength', false, array(3, 20))
                ->addValidator(new Zend_Validate_Alnum())
                ->addValidator('regex', false, array('/^[a-z]+/'))
              ->addValidator('NotEmpty')
                   ->addFilter('StringToLower');
        
            $name->addValidator( new Zend_Validate_Db_NoRecordExists(array('table'=>'users','field'=>'username')));
        

         
         $password = new Zend_Form_Element_Password('password');
         $password->setLabel('Mot de passe*')
                 ->setRequired(true)
                 ->addFilter('StripTags')
                 ->addValidator ( new Zend_Validate_StringLength(6,20) )		
                 ->addValidator('NotEmpty');
         
          
          $email = new Zend_Form_Element_Text('email');
          $email->setLabel('e-mail*')
                  ->addValidator(new Zend_Validate_EmailAddress());
          
         
          
          
        $isAdmin= new Zend_Form_Element_Checkbox('role');
	$isAdmin->setLabel('Admin')->setValue(0);
	
         
            
        
        	
		$cbActive= new Zend_Form_Element_Checkbox('active');
		$cbActive->setLabel('Actif')->setValue(1);
		
		
		
        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
        $envoyer->setAttrib('class', 'bt_connexion');
		//$formulaireElement[] =$envoyer;
	
        $form[] = $id;
        
        $form[] = $edit;
         $form[] =$name;
         $form[] =$password;
         $form[] =$email;
         if(!$this->_activate){
            $form[] =	$isAdmin;
            $form[] =	$cbActive;
        }
        
        $this->addElements($form);
        
         $this->addElements(array($envoyer));
    }
}
?>