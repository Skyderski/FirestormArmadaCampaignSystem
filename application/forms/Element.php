<?php
class Application_Form_Element extends Zend_Form
{
	
	protected $_id=0;
    
 	public function __construct($options = null)
    {
    	if($options['id']){
    		$this->_id= $options['id'];
    	}
    	parent::__construct($options);
    }
    
    
    
    
    public function init()
    {
        $this->setName('elements');
        $this->setIsArray(true);
        //Tableau contenant les champs
        $formulaireElement = array();
	
        $id = new Zend_Form_Element_Hidden('id');
        $id->setDecorators(array('ViewHelper'));
		$formulaireElement[] = $id;
                
         $mapid = new Zend_Form_Element_Hidden('mapid');
        $mapid->setDecorators(array('ViewHelper'));
		$formulaireElement[] = $mapid;
		
                
                // image
                
         $image= new Zend_Form_Element_Select('image');
           $image->setLabel('Image*')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        
        $imageOptions = array();
        $elements = new Model_DbTable_Element();
        foreach ($elements->getImages() as $key)
        {
            $imageOptions[] = array ('key' => $key,'value'=>$key);
        }
        $image->addMultiOptions ( $imageOptions );
	$formulaireElement[] =$image;  
		
   		
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nom*')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator ( new Zend_Validate_StringLength(0,80) )		
              ->addValidator('NotEmpty');
         $formulaireElement[] =$name;

         
         
         $type= new Zend_Form_Element_Select('type');
        $type->setLabel('Type*')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
        
        
        $typeOptions = array();
        $elements = new Model_DbTable_Element();
        
        foreach ($elements->getTypes() as $key=>$value)
        {
            $typeOptions[] = array ('key' => $key,'value'=>$value['name']);
        }
        $type->addMultiOptions ( $typeOptions );
	$formulaireElement[] =$type;  
        
        // ressources
        $ressources= new Zend_Form_Element_Text('ressources');
        $ressources->setLabel('Ressources')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator ( new Zend_Validate_Digits() )
                ->addValidator ( new Zend_Validate_StringLength(1,1) )
                ->setAttrib('size', '1')
                ->setAttrib('maxlength', '1')
                ->addValidator('NotEmpty');
	$formulaireElement[] =$ressources;
        // tech
        $ressources= new Zend_Form_Element_Text('tech');
        $ressources->setLabel('Technologie')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator ( new Zend_Validate_Digits() )
                ->addValidator ( new Zend_Validate_StringLength(1,1) )
                ->setAttrib('size', '1')
                ->setAttrib('maxlength', '1')
                ->addValidator('NotEmpty');
	$formulaireElement[] =$ressources;
        
        // defences
        $ressources= new Zend_Form_Element_Text('defences');
        $ressources->setLabel('Défenses')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator ( new Zend_Validate_Digits() )
                ->addValidator ( new Zend_Validate_StringLength(1,1) )
                ->setAttrib('size', '1')
                ->setAttrib('maxlength', '1')
                ->addValidator('NotEmpty');
	$formulaireElement[] =$ressources;
        
        // population
        $population= new Zend_Form_Element_Text('population');
        $population->setLabel('Population')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator ( new Zend_Validate_Digits() )
                ->addValidator ( new Zend_Validate_StringLength(1,1) )
                ->setAttrib('size', '1')
                ->setAttrib('maxlength', '1')
                ->addValidator('NotEmpty');
	$formulaireElement[] =$population;
        
        
        //coordonneesX
        $xCoord= new Zend_Form_Element_Text('Xcoord');
        $xCoord->setLabel('Xcoord')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator ( new Zend_Validate_Digits() )
                ->addValidator ( new Zend_Validate_StringLength(1,3) )
                ->setAttrib('size', '3')
                ->setAttrib('maxlength', '3')
                ->addValidator('NotEmpty');
	$formulaireElement[] =$xCoord;
        
        //coordonneesY
        
         $yCoord= new Zend_Form_Element_Text('Ycoord');
        $yCoord->setLabel('Ycoord')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator ( new Zend_Validate_Digits() )
                ->addValidator ( new Zend_Validate_StringLength(1,3) )
                ->setAttrib('size', '3')
                ->setAttrib('maxlength', '3')
                ->addValidator('NotEmpty');
	$formulaireElement[] =$yCoord;
        
        //actif
        
        
        
        
        	
		$cbActive= new Zend_Form_Element_Checkbox('active');
		$cbActive->setLabel('Actif')->setValue(1);
		$formulaireElement[] =	$cbActive;
		
		
        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('id', 'boutonenvoyer');
        $envoyer->setAttrib('class', 'bt_connexion');
		//$formulaireElement[] =$envoyer;
		
        $this->addElements($formulaireElement);
        
          
         $this->addDisplayGroup(
        array('Xcoord','Ycoord'), 
        'coord', 
        array("legend" => "Coordonnées")
        
        );
        $this->addDisplayGroup(
        array('ressources','population','tech','defences'), 
        'attributes', 
        array("legend" => "Attributs")
        
        );
		 $this->addElements(array($envoyer));
    }
}
?>