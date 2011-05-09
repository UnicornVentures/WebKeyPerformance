<?php

require_once 'config.php';
require 'gapi.class.php';
require 'SearchKeywords.class.php';

class webkeyperformance
{
    protected $url;
    protected $keys = array();
    protected $percent = array(0.4339,0.2943,0.1981,0.1745,0.1488,0.1337,0.1285,0.1035,0.0906,0,0714);
    protected $maxPosition = 3;

    public function __construct($url,$id=null)
    {
        $this->url = $url;
        $this->info($id);
    }

    private function keywordPosition($key)
    {
             $make_url = 'http://www.google.es/search?hl=es&q=' . urlencode($key) . '&start=';
             $index=0; // counting start from here
             $found=false; // set this flag to true when position found
             for ($page = 0; $page < $this->maxPosition; $page++)
             {
                if($found==true) // break the loop when position found
                break;
                $readPage = fopen($make_url . $page  . 0 ,'r');
				sleep(2);
                $contains = '';
                if ($readPage)
                {
                    while (!feof($readPage))
                    {
                        $buffer = fgets($readPage, 4096);
                        $contains .= $buffer;
                    }
                    fclose($readPage);
                 }
                $results = array();
                preg_match_all('/a href="([^"]+)" class=l.+?>.+?<\/a>/',$contains,$results);
                foreach ($results[1] as $link)
                {
                $link = preg_replace('(^http://|/$)','',$link);
                $index=$index+1;

                if (strlen(stristr($link,$this->url))>0)
                {
                $found=true;
                break;
                }
                }
             }
            if($found==true)
            return $index;
            else
            return -1;


    }

    private function info($id)
    {
		$results = array();
		if($id == null)
		{
			$results = SearchKeywords::getKeywords($this->url);
		}
		else
		{
    	    $ga = new gapi(ga_email,ga_password);
    	    $start = date('Y-m-d', strtotime('-1 day'));
        	$end = date('Y-m-d', strtotime('-1 day'));
        	$ga->requestReportData($id,array('keyword'),array('visits'),'-visits',null,$start,$end);
			foreach($ga->getResults() as $r)
			{
				$results[1][] = $r->getKeyword();
				$results[2][] = $r->getVisits();
			}

		}
	
        for($j = 0; $j < count($results[1]); ++$j)
        {
            if($results[1][$j] != "(not set)")
            {
                $this->keys[] = array("keyword" => $results[1][$j], "visits" => $results[2][$j],"pos" => $this->keywordPosition($results[1][$j]));
            }
        }
    }

	public function calculate($visits,$pos)
	{
		if($pos > count($this->percent) || $pos == -1)
		{ 
			$percent = 0.01;
		}
		else
		{
			$percent = $this->percent[$pos-1];
		}
		return ($visits * $this->percent[0]) / $percent; 

	}

    public function printInfo()
    {
		$i = 0;
		 echo "\nSitio web: ".$this->url."\n";
         printf("%-4s %-40s %5s %8s %15s %10s\n","#","keyword","posicion","visitas","visitas_top","fecha");
         echo "------------------------------------------------------------------------------------------------\n";
        foreach($this->keys as $k)
        {
        	printf("%-4d %-40s %5d %8d %15d %18s\n",++$i,$k['keyword'],$k['pos'],$k['visits'],$this->calculate($k['visits'],$k['pos']),date("d-m-Y",strtotime("now")));
        }
    }

	public function result()
	{
		return $this->keys;
	}



}
// Example use
