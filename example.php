<?php

require("thepiratebay.class.php");

$query = "example";
$page  = 1;

$tpb    = new ThePirateBay;
/* OPTIONAL HOST CHANGE
$tpb->host = "http://thepiratebay.cr";
*/
$search = $tpb->searchTorrents($query, $page);

foreach ($search as $info) {
    $name               = $info['name'];
    $link               = $info['link'];
    $main_category      = $info['main_category'];
    $secondary_category = $info['secondary_category'];
    $seeders            = $info['seeders'];
    $leechers           = $info['leechers'];
    $description        = $info['description'];
    $magnet_link        = $info['magnet_link'];
    
    echo "Name: " . $name . "<br />Link: " . $link . "<br />Main Category: " . $main_category . "<br />Secondary Category: " . $secondary_category . "<br />Seeders: " . $seeders . "<br />Leechers: " . $leechers . "<br />Description: " . $description . "<br />Magnet Link: " . $magnet_link . "<br /><br />---------------<br /><br />";
    
}

?>
