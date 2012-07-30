<?php

class InitController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    
   
    
    public function databaseAction(){
        
          $db = Zend_Db_Table::getDefaultAdapter();
         
          $sql=<<<EOD

   DROP TABLE IF EXISTS `maptouser`;

DROP TABLE IF EXISTS `elements`;
CREATE TABLE `elements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `mapid` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `Xcoord` int(10) unsigned NOT NULL,
  `Ycoord` int(10) unsigned NOT NULL,
  `ressources` int(10) unsigned NOT NULL,
  `population` int(10) unsigned NOT NULL,
  `tech` int(10) unsigned NOT NULL,
  `defences` int(10) unsigned NOT NULL,
  `image` varchar(100) NOT NULL,
  `active` int(10) unsigned NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Definition of table `factions`
--

DROP TABLE IF EXISTS `factions`;
CREATE TABLE `factions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `logo` varchar(45) DEFAULT NULL,
  `logo_small` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `factions`
--


INSERT INTO `factions` (`id`,`name`,`logo`,`logo_small`) VALUES 
 (1,'Aquan Prime',NULL,NULL),
 (2,'Dindrenzi Federation',NULL,NULL),
 (3,'Sorylian Collective',NULL,NULL),
 (4,'Terran Alliance',NULL,NULL),
 (5,'The Directorate',NULL,NULL),
 (6,'The Relthoza',NULL,NULL),
 (7,'Alliance of Kurak',NULL,NULL),
 (8,'Zenian League',NULL,NULL);


--
-- Definition of table `fleet`
--

DROP TABLE IF EXISTS `fleet`;
CREATE TABLE `fleet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `mapid` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `map2user`
--

DROP TABLE IF EXISTS `map2user`;
CREATE TABLE `map2user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mapid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `role` int(10) unsigned NOT NULL,
  `faction` int(10) unsigned DEFAULT NULL,
  `ressources` int(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `maps`
--

DROP TABLE IF EXISTS `maps`;
CREATE TABLE `maps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `active` int(10) unsigned NOT NULL,
  `background` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(45) NOT NULL,
  `category` varchar(45) NOT NULL,
  `controller` varchar(45) DEFAULT NULL,
  `action` varchar(45) DEFAULT NULL,
  `params` varchar(45) DEFAULT NULL,
  `order` int(10) unsigned DEFAULT NULL,
  `role` float DEFAULT NULL,
  `parent` int(10) unsigned DEFAULT NULL,
  `active` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--
INSERT INTO `menu` (`id`,`label`,`category`,`controller`,`action`,`params`,`order`,`role`,`parent`,`active`) VALUES 
 (3,'Accueil','main','index',NULL,NULL,0,-1,0,1),
 (4,'D&eacute;connexion','main','login','logout',NULL,100,0,0,1),
 (5,'Gestion','main','manage',NULL,NULL,1,1,0,1),
 (6,'Campagnes','main','manage','index',NULL,0,1,5,1),
 (7,'Administration','main','admin','index',NULL,2,2,0,1),
 (8,'Campagnes','main','admin','maps',NULL,0,2,7,1),
 (9,'Utilisateurs','main','admin','users',NULL,1,2,7,1),
 (10,'#MANAGE_MAP','main','manage','map','dynamic:mapid\r\nmapid:#MAP_ID',1,1,5,1);


--
-- Definition of table `models`
--

DROP TABLE IF EXISTS `models`;
CREATE TABLE `models` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `factionid` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `points` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `session_id` char(32) NOT NULL,
  `save_path` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `session_data` text,
  PRIMARY KEY (`session_id`,`save_path`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `active` int(10) unsigned DEFAULT NULL,
  `role` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




EOD;
        $db->query($sql);
         
      $userModel = new Model_DbTable_User();
      $userModel->addUser(array("username"=>'admin',"password"=>md5("helene"),"email"=>"fact@antsnest.fr","active"=>1, "role"=>  Model_DbTable_User::ADMIN));
         
        $this->_redirect("/init/check");
        
        
        
    }
    
    public function checkAction(){
        
         $db = Zend_Db_Table::getDefaultAdapter();
        $sql="show tables;";
        
          $stmt = $db->query($sql);
         
          foreach($stmt->fetchAll() as $line){
              
              var_dump($line);
              echo "<hr/>";
              
          }
              
          
            $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        
         
    }
    
   


}

