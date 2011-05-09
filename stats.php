<?php
require_once "db.class.php";

 $db = DataBase::getInstance(); // LLama al Singleton
 $query = $db->query("select w.name as webpage,k.name as keywordname,ki.pos as pos, ki.visits as visits, ki.visitsifone as ifone, ki.date as date from web w
		      inner join keyword as k on id_web = w.id
		      inner join infoKeyword as ki on ki.id = k.id
		      group by w.id,k.id,ki.id ");

$webpage = null;
$i = 0;
foreach($query->fetchAll() as $result)
{
	if($result['pos'] != 1)
	{
		if($webpage != $result['webpage'])
		{
			 $webpage = $result['webpage'];
			 echo "\nSitio web: ".$webpage."\n";
			printf("%-4s %-40s %5s %8s %15s %10s\n","#","keyword","posicion","visitas","visitas_top","fecha");
			echo "------------------------------------------------------------------------------------------------\n";
			$i = 0;
		}
		printf("%-4d %-40s %5d %8d %15d %18s\n",++$i,$result['keywordname'],$result['pos'],$result['visits'],$result['ifone'],date("d-m-Y",strtotime($result['date'])));
	}
	

}


