<?php

require_once "db.class.php";
require_once "webkeyperformance.class.php";

 $db = DataBase::getInstance(); // LLama al Singleton
 $query = $db->query('SELECT * FROM web');
 $_query_keyword = $db->prepare('SELECT * FROM keyword where id_web=? and name =?');
 $_query_insertinfokeyword = $db->prepare('INSERT INTO infoKeyword (id,date,pos,visits,visitsIfOne) VALUES (?,?,?,?,?)');
 $_query_insertkeyword = $db->prepare('INSERT INTO keyword (id_web,name) VALUES (?,?)');
 foreach($query->fetchAll() as $qu)
 {
	echo "\n".$qu['name']."\n";
 	$webperformance = new webkeyperformance($qu['name'],$qu['profile_id']);
	foreach($webperformance->result() as $info)
	{
		$_query_keyword->execute(array($qu['id'],$info['keyword']));
 		if(!$_query_keyword->rowCount())
		{
			$_query_insertkeyword->execute(array($qu['id'],$info['keyword']));
			$id = $db->lastInsertId();
		}
		else
		{
			$id = $_query_keyword->fetch();
			$id = $id['id'];
		}
		$ifone = $webperformance->calculate($info['visits'],$info['pos']);
		$_query_insertinfokeyword->execute(array($id,date('Y-m-d', strtotime('now')),$info['pos'],$info['visits'],$ifone));

		
	}
	$webperformance->printInfo();
	break;

 }
