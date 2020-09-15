<?php
session_start();
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once("room.inc.php");


$result = pdo_query("1","
           select 
           statuspost.shareid, statuspost.comment, statuspost.encoding, 
           statuspost.providerid, statuspost.owner,
           provider.providername
           from statuspost
           left join provider on provider.providerid = statuspost.providerid
           where (statuspost.articleid is null or statuspost.articleid = '') 
           and statuspost.owner > 0 and statuspost.parent='Y' and 
           (statuspost.title='' or statuspost.title is null) 
           and statuspost.comment!=''
           order by statuspost.postdate desc 
           ");

$count = 1;
while($row = pdo_fetch($result)){
    
    $shareid = $row['shareid'];
    $decryptedpost = DecryptPost( $row['comment'], $row['encoding'], $row['owner'], "");
    //echo $decryptedpost;
    $tmp = html_entity_decode( $decryptedpost, ENT_QUOTES);
    if($tmp == ''){
        continue;
    }
    $searchstring = "<span class='roomposttitle'>";
    $i1 = strpos($tmp, $searchstring );
    $title = '';
    
    if($i1!==false){
        
        $title = substr($tmp,$i1+strlen($searchstring) );
        $i2 = strpos($title, "</span>");
        $title = substr($title, 0, $i2);
        $title = htmlentities(removeEmoji2($title),ENT_QUOTES);
        
    }
    if($title == ''){
        $searchstring = "<img";
        $i1 = strpos($tmp, $searchstring );
        if($i1 === false){
            //echo "$tmp...";
        }
    }
    if($title == ''){
        continue;
    }
        
    //pdo_query("1","update statuspost set title = '$title' where shareid = '$shareid' and parent = 'Y'    ");
    echo "<br><b>$count $row[providername] $title</b><br>";
    $count++;
    if($count > 1000){
        exit();
    }
    pdo_query("1","update statuspost set title='$title' where shareid='$shareid' and parent='Y' ");
}

?>
