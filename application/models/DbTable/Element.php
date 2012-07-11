<?php
class Model_DbTable_Element extends Zend_Db_Table_Abstract
{
    protected $_name = 'elements';
    protected $_primary = 'id';
    
    const TELLURIC =1;
    const GAZ = 2;
    const ASTERO = 3;
    const STATION = 4;
    
    const IMAGEPATH="/images/elements/";
    
    static $types = array(self::TELLURIC=>array('image'=>'telluric','name'=>'Téllurique'),
        self::GAZ => array('image'=>'gaz','name'=>"Gazeuse"),
        self::ASTERO =>array('image'=>'asteroid','name'=>"Astéroïd"),
        self::STATION => array('image'=>'station','name'=>"Station"));
    
    static $consonnes = array('b','br',
        'c','cr','ch','ct',
        'd','dr',
        'f','fr','ff',
        'g','gr','gt',
        'h','hl','hr',
        'j','jj','jr',
        'k','kk','kr','kh',
        'l','ll','lh','lr',
        'm','mm','mb','mn','mr',
        'n','nn','nt','nr',
        'p','pr','pp','pt','ph',
        'q','qu','qh',
        'r','rr','rs','rt','rh','rm','rn',
        's','ss','st','sr','sc','sh','sk','sl','sm','sn','sp','sq',
        't','tc','td','th',
        'v',
        'w','wh',
        'x','xh',
        'z','zh'
               
        );
    
   static $romanNumbers=array('I','II','III','IV','V','VI','VII','VIII','IX','X');
       
       
   
    
    static $voyelles = array('a','e','i','o','u','y');
    
    
    static function makeRandomSyllabes(){
        
        
        return  self::$consonnes[array_rand(self::$consonnes)].self::$voyelles[array_rand(self::$voyelles)];
    }
    
    
    public function makeRandomName(){
       
        $nbmot = rand(1, 2);
        
        $name=array();
        
        for($mot=0;$mot<$nbmot;$mot++){
             
            $currentMot="";
            $nbSyll = rand(1, 3);
                 for($syl=0;$syl<$nbSyll;$syl++){
                     $currentMot.=self::makeRandomSyllabes();
                     
                 }
                $name[].= ucfirst($currentMot);
            
            
        }
        
           if(rand(0,1)){
            $name[].= self::$romanNumbers[array_rand(self::$romanNumbers)];
            
        }
            
        
        
        return implode(" ", $name);
        
        
        
    }
    
    
    public function getTypes(){
        return self::$types;
    }
    
    /* recupere les images d elements dispo */
    
    public function getImages($type=''){
        
    $imagetypes = array("image/png","image/jpeg", "image/gif");
    $dir = self::IMAGEPATH;

    // array to hold return value
    $retval = array();

    // add trailing slash if missing
    if(substr($dir, -1) != "/") $dir .= "/";

    // full server path to directory
    $fulldir = "{$_SERVER['DOCUMENT_ROOT']}/$dir";

    $d = @dir($fulldir) or die("getImages: Failed opening directory $dir for reading");
    while(false !== ($entry = $d->read())) {
      // skip hidden files
      if($entry[0] == ".") continue;

      
      // check for image files
      $f = escapeshellarg("$fulldir$entry");
      
     
      
      if((!$type)||(strpos(' '.$entry, $type)))
        $retval[] = $entry;
          
      
    }
    $d->close();

    return $retval;
  }
  
  
  public function getImagesByTypes(){
      
      $types = $this->getTypes();
      $output= array();
      
      foreach($types as $type){
          
          $output[$type['image']]=$this->getImages($type['image']);
      }
      
    

    return $output;
      
      
  }
  
    
    public function getAllElement($mapid){
        
        return $this->fetchAll('mapid='.$mapid);
        
        
    }
    
    public function getAllActiveElement($mapid){
        
        return $this->fetchAll('mapid='.$mapid .' AND active=1');
        
    }
    
    public function getElement($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row ;
    }   
    
    public function addElement($data)
    {
        
        if(!$this->getElementFromCoord($data['mapid'],$data['Xcoord'],$data['Ycoord']))
            $this->insert($data);
        
        //return false;
    }
    
    public function updateElement($data){
        
        $this->update($data, 'id='.$data['id']);
    }
    
    public function deleteElement($id){
        
        $this->delete('id='.$id);
    }
    
     public function deleteAllElements($mapid){
        
         if($mapid)
            $this->delete('mapid='.$mapid);
    }
    
    
    public function deleteElementsFromMap($mapid){
        
        $this->delete('mapid='.$mapid);
    }
    
    public function getElementFromCoord($mapid,$Xcoord,$Ycoord)
    {
        $select = $this->select()
                ->where('mapid='.$mapid)
                ->where('Xcoord='.$Xcoord)
                ->where('Ycoord='.$Ycoord);
        
        $stmt = $select->query();
       
        return $stmt->fetchObject();
        
    }
    public function getElementFromCoordArray($mapid,$Xcoord,$Ycoord)
    {
        $select = $this->select()
                ->where('mapid='.$mapid)
                ->where('Xcoord='.$Xcoord)
                ->where('Ycoord='.$Ycoord);
        
        $stmt = $select->query();
       
        return $stmt->fetch();
        
    }
    
       
    
}
?>