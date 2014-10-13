<?php
 $mysql = new SaeMysql();
 $db_uid = "stardust1900";
 $tableSql = "CREATE TABLE `rec".$db_uid."` (
			  `id` int(11) NOT NULL auto_increment,
			  `db_uid` varchar(100) default NULL,
			  `api_id` varchar(500) default NULL,
			  `title` text,
			  `url` text,
			  `comment` text,
			  `published` datetime default NULL,
			  `content` text,
			  `category` varchar(100) default NULL,
			  `title_code` text,
			  `comment_code` text,
			  PRIMARY KEY  (`id`),
			  FULLTEXT KEY `fulltext_title_comment` (`title_code`,`comment_code`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	$mysql->runSql($tableSql);
	 if( $mysql->errno() != 0 ){
		die( "Error:".$mysql->errmsg());
	 }
	 $mysql->closeDb();
?>