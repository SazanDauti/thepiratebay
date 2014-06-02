<?php
/** ThePirateBay Class
 * @author Sazan Dauti
 * @version 1.0
 * @website https://github.com/SazanDauti/thepiratebay/
 * @copyright 2014 - 2014 - Sazan Dauti
 * @license LICENSE.md
 * 
 *  Copyright (C) 2014 - 2014  Sazan Dauti
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>
 * 
 */
class ThePirateBay {
    
    private function openConnection($url) {
        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL, $url);
        $contents = curl_exec($ch);
        return $contents;
    }
    
    public function searchTorrents($q, $page) {
        $category_array      = array();
        $scategory_array     = array();
        $name_array          = array();
        $link_array          = array();
        $magnet_array        = array();
        $seederleecher_array = array();
        $desc_array          = array();
        $all_array           = array();
        
        
        $searchpage = $page - 1;
        $url        = "http://thepiratebay.se/search/" . $q . "/" . $searchpage . "/99/0";
        $contents   = $this->openConnection($url);
        $dom        = new DOMDocument();
        @$dom->loadHTML($contents);
        $xpath = new DOMXpath($dom);
        
        $getCat1 = $xpath->query("//td[@class='vertTh']");
        
        foreach ($getCat1 as $catinfo) {
            $maincat   = $catinfo->getElementsByTagName("a")->item(0)->nodeValue;
            $secondcat = $catinfo->getElementsByTagName("a")->item(1)->nodeValue;
            array_push($category_array, $maincat);
            array_push($scategory_array, $secondcat);
        }
        
        $getName = $xpath->query("//div[@class='detName']");
        
        foreach ($getName as $name) {
            $getlink = $name->getElementsByTagName("a")->item(0);
            $name    = $getlink->nodeValue;
            $link    = "http://thepiratebay.se" . $getlink->getAttribute("href");
            array_push($name_array, $name);
            array_push($link_array, $link);
        }
        
        $getMagnetLink = $xpath->query("//a[@title='Download this torrent using magnet']");
        foreach ($getMagnetLink as $magnetlink1) {
            $magnetlink = $magnetlink1->getAttribute("href");
            array_push($magnet_array, $magnetlink);
        }
        
        $getseedleech = $xpath->query("//td[@align='right']");
        foreach ($getseedleech as $seedleech) {
            $value = $seedleech->nodeValue;
            array_push($seederleecher_array, $value);
        }
        
        $getDesc = $xpath->query("//font[@class='detDesc']");
        foreach ($getDesc as $desc) {
            $description = $desc->nodeValue;
            array_push($desc_array, $description);
        }
        
        
        for ($i = 0; $i < count($name_array); $i++) {
            $seed  = $i * 2;
            $leech = $seed + 1;
            $temp  = array(
                "name" => $name_array[$i],
                "link" => $link_array[$i],
                "main_category" => $category_array[$i],
                "secondary_category" => $scategory_array[$i],
                "seeders" => $seederleecher_array[$seed],
                "leechers" => $seederleecher_array[$leech],
                "description" => $desc_array[$i],
                "magnet_link" => $magnet_array[$i]
            );
            array_push($all_array, $temp);
        }
        
        return $all_array;
        
        
    }
    
    
}

?>
