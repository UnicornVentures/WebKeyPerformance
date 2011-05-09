<?php
class SearchKeywords
{
    const KEYWORD_PARSER = "/\,\"([a-zA-Z\s]+)\"\,([0-9]+)\]/";

    static public function getKeywords($domain)
    {
        if ($domain) {
            $url = file_get_contents("https://www.google.com/adplanner/rpc/SiteDetailsService/getPlacementProfile?&request_pb=%5Bnull%2C%5Bnull%2C1%2C%22" . $domain . "%22%5D%2C%22001%22%2C10%5D");
            preg_match_all(static::KEYWORD_PARSER, $url, $keywords);

            return $keywords;
        } else {
            throw new Exception("The domain can not be empty");
        }
    }
}

