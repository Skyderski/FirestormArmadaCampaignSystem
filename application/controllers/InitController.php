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
) ENGINE=InnoDB AUTO_INCREMENT=871 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `elements`
--

/*!40000 ALTER TABLE `elements` DISABLE KEYS */;
/*!40000 ALTER TABLE `elements` ENABLE KEYS */;


--
-- Definition of table `map2user`
--

DROP TABLE IF EXISTS `map2user`;
CREATE TABLE `map2user` (
  `mapid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `role` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `map2user`
--

/*!40000 ALTER TABLE `map2user` DISABLE KEYS */;
INSERT INTO `map2user` (`mapid`,`userid`,`role`,`id`) VALUES 
 (5,1,1,1),
 (5,4,1,3);
/*!40000 ALTER TABLE `map2user` ENABLE KEYS */;


--
-- Definition of table `maps`
--

DROP TABLE IF EXISTS `maps`;
CREATE TABLE `maps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `active` int(10) unsigned NOT NULL,
  `background` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `maps`
--

/*!40000 ALTER TABLE `maps` DISABLE KEYS */;
INSERT INTO `maps` (`id`,`name`,`width`,`height`,`active`,`background`) VALUES 
 (3,'Carte de test',50,50,1,'starfiel.gif'),
 (4,'carte 30x30',30,30,1,'starfiel.gif'),
 (5,'carte ce soir',15,15,1,'starfiel.gif');
/*!40000 ALTER TABLE `maps` ENABLE KEYS */;


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `session`
--

/*!40000 ALTER TABLE `session` DISABLE KEYS */;
INSERT INTO `session` (`session_id`,`save_path`,`name`,`modified`,`lifetime`,`session_data`) VALUES 
 ('bv5r2nivbkj2q239l2j4pravp7','\\xampp\\tmp','PHPSESSID',1343137590,1440,'Zend_Auth|a:1:{s:7:\"storage\";s:5:\"admin\";}');
/*!40000 ALTER TABLE `session` ENABLE KEYS */;


--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `active` int(10) unsigned NOT NULL,
  `role` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`,`username`,`password`,`email`,`active`,`role`) VALUES 
 (1,'admin','2eb800914b6b1b7eb3eb2679b5f50a40','antoine@gmail.com',1,1),
 (4,'vulkan','6bffab515fc47ab83e4283941fdd1042','guillaume.larcheveque@gmail.com',1,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

EOD;
        $db->query($sql);
         
      
         
          $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        
        
        
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

