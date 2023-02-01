<?php
require_once("notify.inc.php");
require_once('simple_html_dom.php');
require_once("lib_autolink.php");
require_once("roommanage.inc.php");
require_once("profanity.php");

function RoomPostNew( 
        $mode, $providerid, $shareid, $roomid, $title, $comment, 
        $video, $photo, $link, $anonymous, $articleid )
{
        global $rootserver;
        global $installfolder;
        global $superadmin;
        
        $slideshow_album = "";
    
        if( $roomid =='' || $roomid == 'All ') {
            return false;
        }
        
        //New Post
        if($mode == 'P'){
            
            $shareid = uniqid("BZG", true);
            $parent = 'Y';
            $owner = $providerid;
            $commentcount = 0;
            
        } else {
            
            $parent = 'N';
            $owner = $providerid;
            $title = '';
            
            $result2 = pdo_query("1",
                "
                    select count(*) as commentcount from
                    statuspost where parent!='Y' and shareid=?
                ",array($shareid));
            if( $row2 = pdo_fetch($result2)){
                $commentcount = intval($row2['commentcount'])+1;
                $result2 = pdo_query("1",
                    "
                        update statuspost set commentcount = ? where shareid=? and parent='Y'
                    ",array($commentcount,$shareid));
            }
            
            
        }
        $profileflag = "";
        $roomstyle = "";
        $restricted = "";
        $blocked = "";
        $roomowner = "";
        $result = pdo_query("1","
            select private, anonymousflag, adminonly, profileflag, roomstyle,
            (select 'Y' from statusroom where statusroom.roomid = roominfo.roomid and owner = ? limit 1 )
               as owner,
            (select owner from statusroom where statusroom.roomid = roominfo.roomid and owner = ? limit 1 )
               as roomowner,
            (select 'Y' from roommoderator where roommoderator.roomid = roominfo.roomid and roommoderator.providerid = ?)
               as moderator,
            (select restricted from provider where providerid = ? ) as restricted
            from roominfo where roomid = ?
            ",array($providerid, $providerid, $providerid, $providerid, $roomid));
        
        if( $row = pdo_fetch($result)){
            
            $anonymousflag = $row['anonymousflag'];   
            if($providerid!=0){
                if(($row['owner']!='Y' && $row['moderator']!='Y') && $row['adminonly']=='Y'){
                    //echo "Owner Posting Only";
                    return false;
                }
            }
            $profileflag = $row['profileflag'];
            $roomstyle = $row['roomstyle'];
            $private = $row['private'];
            $roomowner = $row['roomowner'];
            $restricted = $row['restricted'];
            $roomownerflag = $row['owner'];
        }

        $result = pdo_query("1","
            select 'Y' as blockstatus from blocked where blockee=? and blocker in
            (select owner from statusroom where roomid=?  )
            ",array($providerid, $roomid));
        
        if( $row = pdo_fetch($result)){
            
            $blocked = $row['blockstatus'];
        }

        if(($blocked == 'Y') || ( $restricted == 'Y' && $roomowner!='Y' && $private!='Y') || ($profileflag == 'Y' && $roomownerflag!='Y' && $mode == 'P')){
            return false;
        }
        
        $postid = uniqid("A", true);
        
        $title = rtrim(ltrim($title));
        $comment = rtrim(ltrim($comment));
        $video = rtrim(ltrim($video));
        $link = rtrim(ltrim($link));
        $slideshow = "";
        
        if($private !='Y'){
        
            $testcomment = ProfanityCheck($title.$comment);
            if($testcomment!=$title.$comment){
                $comment = $testcomment;
                $video = "";
                $link = "";
                $title = "";
            }
        }
        
        //if($comment == 'stfu'){
        //    $comment = "I am zucking idiot. I'm sorry";
        //}
        
        //Special Handling 
        $photo = rawurldecode(rtrim(ltrim($photo)));
        if( IsSlideshow($photo)){
            //Allow Handling of Photo Albums with Spaces
            //An Issue here because Spaces are used to separate entity types (Image, Link, etc.)
            $temp = explode("=",$photo);
            //$photo = $temp[0]."=".rawurlencode($temp[1]);
            $photo = $temp[0]."=".rawurlencode($temp[1]);
        }
        
        $combinedcomment = "$photo $link $comment";
        
        //FIRST Htmlentities - Should be no valid HTML from user in here after this
        $combinedcomment = htmlentities($combinedcomment, ENT_COMPAT );
        
        //$combinedcomment = strip_tags($combinedcomment );
        $hold_comment = str_replace("\\n", " <br>", $combinedcomment);
        $hold_comment = str_replace("\\r", " ", $hold_comment);
                
        $hold = explode(" ",$hold_comment);
        $text_comment = '';
        $photo = "";
        $video = "";
        foreach($hold as $comment_item){
            
            if($comment_item!==''  ){
            
                if(IsVideoStreaming($comment_item)){
                    $vars = null;
                    parse_str( parse_url( $comment_item, PHP_URL_QUERY ), $vars );
                    $text_comment .= "<br>
                            <a href='$comment_item' style='text-decoration:none;color:black' target='_blank'>
                                <img src='$rootserver/img/videostream.png' style='max-width:100%;height:auto' />
                            </a>
                            <br><br>".$vars['f']." <br>".$vars['t']."
                            ";
                    
                } else
                if(IsAudioStreaming($comment_item)){
                    $vars = null;
                    parse_str( parse_url( $comment_item, PHP_URL_QUERY ), $vars );
                    $text_comment .= "<br>
                            <a href='$comment_item' style='text-decoration:none;color:black' target='_blank'>
                                <img src='$rootserver/img/musicpost1.png' style='max-width:100%;height:auto' />
                            </a>
                            <br><br>".$vars['f']." <br>".$vars['t']."
                            ";
                    
                } else
                if( IsSlideshow($comment_item)){
                    
                    $vars = null;
                    parse_str( parse_url( $comment_item, PHP_URL_QUERY ), $vars );
                    $firstimg = GetSlideShowFirstImg($providerid, rawurldecode($vars['a']));
                    $slideshow_album = $vars['a'];
                    $photo = $firstimg;
                    
                    //Temporary during testing
                    //if($_SESSION['superadmin']!='Y'){
                    //$text_comment .= "
                    //    <img src='$firstimg' class='slideshow' data-providerid='$providerid' data-album='".$vars['a']."' style='position:relative' />    
                    //    <br>
                    //    ";
                    //}
                    $text_comment .= "
                        
                            <div class='roomalbumtitle' style='text-align:left'>
                                Slideshow: ".$vars['a']."
                                <br>
                            </div>
                           ";
                    
                } else
                if(IsVideo($comment_item)){
                    $text_comment .= " ".YouTube($comment_item);
                    
                } else
                if(IsPhoto($comment_item )){
                    //echo "$comment_item\nIs Photo...";
                    if($photo==''){
                        //Post Photo if first
                        $photo = $comment_item;
                                
                    } else {
                        $text_comment .= " ".$comment_item;
                    }
                    
                } else 
                if(IsLink($comment_item )){
                    $comment_item .= " ";
                    //echo "$comment_item\nIs Link...";
                    if(filter_var($comment_item, FILTER_VALIDATE_URL)){
                        $imageOG = GetOGImageTag($comment_item);
                        if($imageOG!=''){
                            $photo = $imageOG;  
                            //$text_comment .= "OG ".$photo;
                        }
                        $text_comment .= " ".$comment_item;
                    } else {
                        $text_comment .= " ".$comment_item;
                    }               
                    
                } else {
                    $text_comment .= $comment_item." ";
                }
            } 

        }
        $text_comment = autolink($text_comment, 50, ' class="chatlink" target="_blank" ', false);
        
        $combinedcomment = $text_comment;
        
        
        $precomment = "";
        if($title!='' && $combinedcomment!=''){
            $precomment = "<span class='roomposttitle'>".strip_tags($title)."</span> &nbsp;<br><br>";
        } else
        if($title!='' && $combinedcomment==''){
            $precomment = strip_tags($title);
        }
        $title = htmlentities($title, ENT_QUOTES);
        //HtmlEntities now DOUBLED
        $combinedcomment = htmlentities( $precomment.$combinedcomment, ENT_QUOTES );

        //Test/
        $encoding = $_SESSION['responseencoding'];
        $encryptedcomment = EncryptPost($combinedcomment, "$providerid","");    
        /*
        if($video!=''){
            $video = EncryptPost($video, "$providerid","");
        }
        if($photo!=''){
            $photo = EncryptPost($photo, "$providerid","");
        }
         * 
         */
        if($photo!='' && $encoding!='BASE64' && $encoding!='PLAINTEXT'){
            $photo = EncryptPost($photo, "$providerid","");
        }
        //$room = tvalidator("PURIFY",$room);
        $posterid = $providerid;
        if($anonymousflag == 'Y'){
            $posterid = 0;
        }

        
        $result = pdo_query("1","
            insert into statuspost
            (providerid, comment, postdate, shareid, parent, 
             owner, likes, roomid, postid,
             link, photo, video, encoding, anonymous, articleid, title, album ) values
            (?, ?, now(), ?,?, 
             ?, 0, ?,?,
              '',?,?,?,?,?,?,? )
                ",array(
                     $posterid, $encryptedcomment,$shareid,$parent, 
                     $owner, $roomid,$postid,
                     $photo,$video,$encoding,$anonymous, $articleid, $title,$slideshow_album
                    
                ));
        if(!$result){
            /*
            echo "            
            insert into statuspost
            (providerid, comment, postdate, shareid, parent, 
             owner, likes, roomid, postid,
             link, photo, video, encoding, anonymous, articleid, title ) values
            ($posterid, '$encryptedcomment', now(), '$shareid','$parent', 
             $owner, 0, $roomid,'$postid',
              '','$photo','$video','$encoding','$anonymous', $articleid, '$title' )
                  ";
             * 
             */


        }
        if($profileflag == 'Y'){
            pdo_query("1","update provider set lastactive = now() where providerid = ? ",array($posterid));
        }    
        
        FlagUnreadPost( $providerid, $shareid, $postid, $roomid, $anonymous, $mode, $articleid );
        FlagMakePost(   $providerid, $shareid, $postid, $roomid );

        return $postid;
}



function RoomPostEdit( 
        $providerid, $postid, $comment ) 
{
        global $rootserver;
        global $installfolder;
    
        if( $comment == ''){
            return false;
        }
        //extract title
        $i1 = strpos($comment,"<title>");
        $i2 = strpos($comment,"</title>");
        $title = substr($comment,0, $i2);
        $title = htmlentities(substr($title,$i1+7), ENT_QUOTES);
        
        //$comment = substr($comment, $i2+8);
        
        //$title = rtrim(ltrim($title));
        $comment = rtrim(ltrim($comment));
        $comment = str_replace("title>","span>",$comment);
        $comment = htmlentities( $comment, ENT_QUOTES );
        $comment = str_replace("\\n","<br> ",$comment);
        
        $comment = str_replace("&lt;span&gt;","<span class='roomposttitle'>",$comment);
        $comment = str_replace("&lt;/span&gt;","</span>",$comment);
        $text_comment = autolink($comment, 50, ' class="chatlink" target="_blank" ', false);
        
        //HtmlEntities now DOUBLED
        $combinedcomment = htmlentities( $text_comment, ENT_QUOTES );

        //Test
        $encoding = $_SESSION['responseencoding'];
        $encryptedcomment = EncryptPost($combinedcomment, "$providerid","");    
        

        pdo_query("1","
            update statuspost
            set title=?, comment = ?, encoding=?
            where postid = ?
                ",array($title,$encryptedcomment,$encoding,$postid));
        //Don't change owner on edit but record edit with actual user
        FlagEditPost( $_SESSION['pid'], $postid );

        return true;
}


function RoomPost( 
        $mode, $providerid, $shareid, $roomid, $title, $comment, 
        $video, $photo, $link, $anonymous, $articleid )
{
        return RoomPostNew( 
        $mode, $providerid, $shareid, $roomid, $title, $comment, 
        $video, $photo, $link, $anonymous, $articleid );
        
}
function SharePost( $providerid, $articleid, $roomid, $room )
{
    

    $shareid = uniqid("BZG", true);
    $postid = uniqid("A", true);
    
    $result = pdo_query("1","
        select comment, photo, encoding, providerid 
        from statuspost where articleid = ? and providerid = 0
        ",array($articleid));
    
    if($row = pdo_fetch($result)){
        
        $decrypted = DecryptPost("$row[comment]", "$row[encoding]", "$row[providerid]", "");
        $encrypted = EncryptPost("$decrypted", $providerid, "");
        
        $decryptedphoto = DecryptPost("$row[photo]", "$row[encoding]", "$row[providerid]", "");
        $encryptedphoto = EncryptPost("$decryptedphoto", $providerid, "");
        
        
    }
    
    pdo_query("1","
        insert into statuspost

        (providerid, comment, postdate, shareid, parent, 
        owner, likes, roomid, postid,
        link, photo, video, encoding, anonymous, articleid ) 

        select ?, ?, now(), ?, 'Y',
        ?, 0, ?, ?,
        '', ?, '', '$_SESSION[responseencoding]', anonymous, articleid 
        from statuspost where articleid = ? and providerid = 0 limit 1
        ",array(
            $providerid, $encrypted, $shareid,
            $providerid, $roomid, $postid,
            $encryptedphoto, $articleid 
            
        ));

    
    FlagUnreadPost( $providerid, $shareid, $postid, $roomid, '', 'P',"" );
    FlagMakePost(   $providerid, $shareid, $postid, $roomid );
    
    return;
}


/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function IsVideo( $comment )
{
    //Detect Video
    $hold = explode( "?",$comment);
    
    $pos1 = strpos(strtolower($hold[0] ),"//www.youtube.com/watch");
    $pos2 = strpos(strtolower($comment ),"//m.youtube.com/watch");
    $pos3 = strpos(strtolower($comment ),"//www.youtube.com/embed/");
    $pos4 = strpos(strtolower($comment),"//youtu.be/");
    $pos5 = strpos(strtolower($comment),"vimeo.com/");

    if($pos1===false && 
       $pos2===false && 
       $pos3 ===false && 
       $pos4 ===false && 
       $pos5 ===false){
        return false;
    }
    return true;
    
}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function YouTubeMeta($url, $videotitle)
{
    //Reformatting of Stored Video
    $pos1 = strpos(strtolower($url ),"//www.youtube.com/embed/");
    if($pos1 !== false){
        $src = str_replace("'","",$url);
        $src = str_replace("></iframe>","",$src);
        $src = str_replace("<","",$src);
        $src1 = explode("?",$src);
        $vars = explode("/",$src1[0]);

        
        $url = 'https://www.googleapis.com/youtube/v3/videos?id=';
        $end =  "&part=snippet&key=";
        $vid = $vars[4];;
        //$response = file_get_contents($url.$vid.$end);
        //$obj = json_decode($response,true);            

        $youtube = "
                <img class='youtube videoview' data-url='//www.youtube.com/watch?v=".$vars[4]."' 
                     src='//img.youtube.com/vi/".$vars[4]."/0.jpg' style='cursor:pointer' />
                ";
        
        if(rtrim($videotitle)!=''){
            $youtube .= "<div style='padding-left:10px;padding-right:10px'>Video: ".$videotitle." </div>";
        } else {
            $youtube .= "<div style='padding-left:10px;padding-right:10px'>Video </div>";
            
        }

       
       
        if(InternetTooSlow()){
            $youtube = " 
                    <div class='videoview' data-url='//www.youtube.com/watch?v=".$vars[4]."' style='cursor:pointer;padding-left:10px;padding-right:10px;color:#00A0E3'> 
                        <b>Youtube Video</b>
                    </div>
                    ";
            $youtube .= "<div style='padding-left:10px;padding-right:10px'>".$videotitle." </div>";
        }
        
        return $youtube;
    }
    
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function YouTube($url )
{
    
    //Already proper format but convert to Iframe (for consistency)
    $pos1 = strpos(strtolower($url ),"//www.youtube.com/embed/");
    if($pos1 !== false){
        $src = str_replace("'","",$url);
        $src = str_replace("></iframe>","",$src);
        $src = str_replace("<","",$src);
        $src1 = explode("?",$src);
        $vars = explode("/",$src1[0]);

        $youtube = "<iframe class='youtube' src='//www.youtube.com/embed/".$vars[4]."'></iframe>";
        return $youtube;
    }
    
    
    //format 1 with query string - specically search for 'v'
    $pos1 = strpos(strtolower($url ),"//www.youtube.com/watch");
    if($pos1 !== false){
        $vars = array();
        //Extract Query String and parse into Vars
        parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
        //$youtube= '<iframe allowtransparency="true" scrolling="no" width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed/'.$my_array_of_vars['v'].'" frameborder="0"'.($fullscreen?' allowfullscreen':NULL).'></iframe>';
        $v = str_replace("\\n", " ", $vars['v']);
        
        $hold = explode(" ",$v);
        $youtube = "<iframe class='youtube' src='//www.youtube.com/embed/".$hold[0]."'></iframe>";
        return $youtube;
    }
    
    //format 1 with query string - specically search for 'v'
    $pos1 = strpos(strtolower($url ),"//m.youtube.com/watch");
    if($pos1 !== false){
        $vars = array();
        //Extract Query String and parse into Vars
        parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
        //$youtube= '<iframe allowtransparency="true" scrolling="no" width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed/'.$my_array_of_vars['v'].'" frameborder="0"'.($fullscreen?' allowfullscreen':NULL).'></iframe>';
        $v = str_replace("\\n", " ", $vars['v']);
        
        $hold = explode(" ",$v);
        $youtube = "<iframe class='youtube' src='//www.youtube.com/embed/".$hold[0]."'></iframe>";
        return $youtube;
    }
    
    //format 2
    $pos1 = strpos(strtolower($url ),"//youtu.be/");
    if($pos1 !== false){
        $vars = explode("/",$url);

        $youtube = "<iframe class='youtube' src='//www.youtube.com/embed/".$vars[3]."'></iframe>";
        return $youtube;
    }
    
    //format 5
    $pos1 = strpos(strtolower($url ),"//player.vimeo.com/video/");
    if($pos1 !== false){
        $vars = explode("/",$url);

        $youtube = "<iframe class='youtube'  style='max-width:600px;' src='//player.vimeo.com/video/".$vars[4]."'></iframe>";
        return $youtube;
    }
    
    
    //format 4
    $pos1 = strpos(strtolower($url ),"vimeo.com/");
    if($pos1 !== false){
        $vars = explode("/",$url);

        $youtube = "<iframe class='youtube' style='max-width:600px;' src='//player.vimeo.com/video/".$vars[3]."'></iframe>";
        return $youtube;
    }
    
    
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function IsPhoto( $comment )
{
    global $rootserver;
    
    //Is this internal share doc.php -- get Alias
    $pos1 = strpos(strtolower($comment),"$rootserver");
    if($pos1 === false){
        $pos1 = strpos(strtolower($comment),"https://bytz.io");
    }
    $pos2 = strpos(strtolower($comment),"doc.php?p=");
    if($pos1!==false && $pos2!==false ){
        
        $exploded = explode("=",$comment);
        $exploded2 = explode("&",$exploded[1]);
        $alias = $exploded2[0];
        $comment .= "&test=$alias";
        $result = pdo_query("1",
            "
            select filetype from filelib where 
            alias=? and filetype in ('jpg','jpeg','png','gif') and status='Y'
            ",array($alias)
            );
        if($row = pdo_fetch($result)){
            return true;
        }
    }
    
    $pos1 = strpos(strtolower($comment),"$rootserver");
    if($pos1 === false){
        $pos1 = strpos(strtolower($comment),"https://bytz.io");
    }
    $pos2 = strpos(strtolower($comment),"sharedirect.php?a=");
    if($pos1!==false && $pos2!==false ){
        return true;
    }
    
    
    
    $pos3 = strpos(strtolower($comment),"sharedirect.php?a=");
    if($pos1!==false && $pos3!==false  ){
        
        $exploded = explode("=",$comment);
        $exploded2 = explode("&",$exploded[1]);
        $alias = $exploded2[0];
        $comment .= "&test=$alias";
        $result = pdo_query("1",
            "
            select filetype from photolib where alias=?
            and filetype in ('jpg','jpeg','png','gif') 
            ",array($alias)
            );
        if($row = pdo_fetch($result)){
            return true;
        }
    }
    if(!filter_var($comment, FILTER_VALIDATE_URL)){  
        //echo "Not valid URL-$comment";
        return false;
    }      
    

    try {
        $url_headers=get_headers($comment, 1);
        if($url_headers!==FALSE && isset($url_headers['Content-Type'])){

            $type=explode("/",strtolower($url_headers['Content-Type']));
            if(
                    $type[0] == 'image' 
               ){
                return true;
            }
        }    
        //Exception
        if(strstr(strtolower($comment),".jpg?")!==FALSE && 
           substr(strtolower($comment),0,8)=="https://" 
           ){
                //echo "<img src='$comment' />";
                //echo "Exception Error";
                return true;
        }
        return false;
        
    } catch (Exception $e) {
        return false;
    }
    
}

function IsLink($comment)
{
    $pos1 = strpos(strtolower($comment),"https://");
    $pos2 = strpos(strtolower($comment),"http://");

    if($pos1===false && $pos2===false){
        return false;
    }
    return true;
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function GetSlideShowFirstImg($providerid, $album)
{
    global $rootserver;
    global $installfolder;
    
    $albumclean = tvalidator("PURIFY",html_entity_decode($album, ENT_QUOTES));
    
    $result = pdo_query("1","
        select alias from photolib where (providerid=? or album like '*%')
            and
            album=?  order by createdate asc
            ",array($providerid,$albumclean));
    if($row = pdo_fetch($result)){
        $alias = $row['alias'];
        return "$rootserver/$installfolder/sharedirect.php?a=$alias";
    }
    
    //$firstimg = GetSlideShowFirstImg($providerid, $vars['a']);
    return "$rootserver/img/slideshow.png";
    
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function IsVideoStreaming( $comment )
{
    global $rootserver;
    
    $pos1 = strpos(strtolower($comment),"$rootserver");
    $pos1a = strpos(strtolower($comment),"https://bytz.io");
    $pos2 = strpos(strtolower($comment),"videoplayer.php");
    if(($pos1===false && $pos1a===false) || $pos2===false  ){
        return false;
    }
    return true;
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function IsAudioStreaming( $comment )
{
    global $rootserver;
    
    $pos1 = strpos(strtolower($comment),"$rootserver");
    $pos1a = strpos(strtolower($comment),"https://bytz.io");
    $pos2 = strpos(strtolower($comment),"soundplayer.php");
    if(($pos1===false && $pos1a===false) || $pos2===false  ){
        return false;
    }
    return true;
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function IsEmoji( $comment )
{
    global $rootserver;
    //Is this internal share doc.php -- get Alias
    $pos1 = strpos(strtolower($comment),"$rootserver/img/emoji-");
    if($pos1!==false  ){
        return true;
    }
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */


function IsSlideshow( $comment )
{
    global $rootserver;
    
    $pos1 = strpos(strtolower($comment),"https://");
    $pos2 = strpos(strtolower($comment),"slideshow.png");
    $pos3 = strpos($comment,"$rootserver/img");
    
    if($pos1===false || $pos2===false || $pos3===false ){
        return false;
    }
    return true;
    
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function GetOGImageTag($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    //parsing begins here:
    $doc = new DOMDocument();
    @$doc->loadHTML($data);
    $nodes = $doc->getElementsByTagName('title');

    //get and display what you need:
    //$title = $nodes->item(0)->nodeValue;

    $metas = $doc->getElementsByTagName('meta');

    $image = "";
    $url = "";
    $title = "";
    
    for ($i = 0; $i < $metas->length; $i++){
        $meta = $metas->item($i);
        if($meta->getAttribute('property') == 'og:title')
            $title = $meta->getAttribute('content');
        if($meta->getAttribute('property') == 'og:image')
            $image = $meta->getAttribute('content');
        if($meta->getAttribute('property') == 'og:url')
            $url = $meta->getAttribute('content');
        
    }
    
    return "$image";
}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */


function RoomPostLike( $providerid, $shareid, $postid, $roomid)
{

    
        $result = pdo_query("1","
            select * from statusreads where providerid=? and shareid=? and postid=? and xaccode='L'
                ",array($providerid,$shareid,$postid));
        if(!$row = pdo_fetch($result)){
            pdo_query("1","
                update statuspost set likes=likes+1 where shareid = ? and postid=?
                    ",array($shareid,$postid));

            pdo_query("1","
                insert into statusreads (providerid, shareid, postid, xaccode, actiontime, roomid ) values (
                                        ?, ?, ?, 'L', now(), ? )
                    ",array($providerid,$shareid,$postid,$roomid));
            $result = pdo_query("1","
                select providerid from statuspost where shareid=? and postid=?
                ",array($shareid,$postid));
            if( $row = pdo_fetch($result)){
                $owner = $row['providerid'];   
            }


            FlagUnreadPost( $providerid, $shareid, $postid, $roomid, "", "L","" );
        }
    
}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function RoomPostDelete( $providerid, $shareid, $postid, $roomid )
{
    
    //Is this a Parent Post and being deleted by Owner?
    $result = pdo_query("1","
        select parent from statuspost
        where shareid=? and postid=? and 
        parent='Y' and 
        ( 
            providerid=? or 
            owner=? or
            exists 
            ( select providerid from roommoderator 
              where roomid=? and providerid=? 
            ) 
            or exists 
            (   select providerid from statusroom 
                where roomid=? and owner=? 
            ) 
            or 'Y' = '$_SESSION[superadmin]'

        )
    ",array(
        $shareid,$postid,$providerid,$providerid,$roomid,$providerid,$roomid,$providerid
    ));
    if($row = pdo_fetch($result)){
        $parent = $row['parent'];

        //Delete all posts for this thread if this is parent
        pdo_query("1","
            delete from statuspost
                where shareid=?  
                ",array($shareid));
        
    } else {
        //deleting a non-parent post
        
        $result2 = pdo_query("1",
            "
                select count(*) as commentcount from
                statuspost where parent!='Y' and shareid=?
            ",array($shareid));
        if( $row2 = pdo_fetch($result2)){
            $commentcount = intval($row2['commentcount'])-1;
            if($commentcount < 0 ){
                $commentcount = 0;
            }
            $result2 = pdo_query("1",
                "
                    update statuspost set commentcount = ? where shareid=? and parent='Y'
                ",array($commentcount,$shareid));
        }
        
    }
    
    //Delete only single post
    pdo_query("1","
        delete from statuspost
        where   (
                    (
                        providerid=? or 
                        owner=? or
                        exists 
                        (select providerid from roommoderator 
                         where roomid=? and providerid=? 
                        ) or
                        exists 
                        (   select providerid from statusroom 
                            where roomid=? and owner=? 
                        ) 
                    ) 
                    or 'Y' = '$_SESSION[superadmin]'
                )
                and shareid='$shareid' and postid='$postid' 
            ",array(
                $providerid,$providerid,$roomid,$providerid,$roomid,$providerid
            ));
    

    //Delete any statusreads of type R of mine since I've seen it
    pdo_query("1","
        delete from statusreads
        where shareid =? and providerid=?
        and xaccode='R'
            ",array($shareid,$providerid));

    pdo_query("1","
        delete from statusreads
        where shareid not in 
            (select shareid from statuspost where shareid=? 
            and statusreads.postid = statuspost.postid )
        and postid = ?
            ",array($shareid,$postid));

    pdo_query("1","
        insert into statusreads (providerid, shareid, postid, xaccode, actiontime, roomid ) values (
                                ?, ?, ?, 'D', now(), ? )
            ",array($providerid,$shareid,$postid,$roomid));

    
}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function FlagBumpPost( $providerid, $shareid, $postid, $roomid )
{
        pdo_query("1","
            insert into statusreads 
            (providerid, shareid, postid, xaccode, actiontime, roomid ) values
            ( ?, ?, ?, 'B', now(), ? )
            ",array($providerid,$shareid,$postid,$roomid));
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function FlagMakePost( $providerid, $shareid, $postid, $roomid )
{
    if($providerid > 0){
        pdo_query("1","
            insert into statusreads 
            (providerid, shareid, postid, xaccode, actiontime, roomid ) values
            ( ?, ?, ?, 'P', now(), ? )
            ",array($providerid,$shareid,$postid,$roomid));
    }

    pdo_query("1","
        update roominfo set lastactive = now() where roomid = ?
        ",array($roomid));
        
        
}
function FlagEditPost( $providerid, $postid )
{
        
    $shareid = '';
    $roomid = '';
    $result = pdo_query("1",
        "
        select shareid,roomid from statuspost where postid = ?        
        ",array($postid));
    if( $row = pdo_fetch($result)){
        $roomid = $row['roomid'];
        $shareid = $row['shareid'];
    } else {
        return;
    }
        
    pdo_query("1","
        insert into statusreads 
        (providerid, shareid, postid, xaccode, actiontime, roomid ) values
        ( ?, ?, ?, 'E', now(), ? )
        ",array($providerid,$shareid,$postid,$roomid));

    //pdo_query("1","
    //    update roominfo set lastactive = now() where roomid = ?
    //    ",array($roomid));
        

        
        
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function FlagBlockPost( $providerid, $shareid, $postid, $roomid )
{
    pdo_query("1","
        insert into statusreads 
        (providerid, shareid, postid, xaccode, actiontime, roomid ) values
        ( ?, ?, ?, 'X', now(), ? )
        ",array($providerid,$shareid,$postid,$roomid));
        
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function FlagUnreadPost( $providerid, $shareid, $postid, $roomid, $anonymous, $subtype, $articleid )
{
    pdo_query("1","
        delete from statusreads where shareid=? and xaccode='R'
        ",array($shareid));
    
    if(intval($articleid)==0){
        //RoomNotification($providerid, $roomid, $subtype, $shareid, $postid, $anonymous );
        RoomNotificationRequest($providerid, $roomid, $subtype, $shareid, $postid, $anonymous );
    }

    pdo_query("1"," 
        update statusroom set lastaccess = now()
        where roomid = ?
    ",array($roomid));
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function FlagReadPost( $shareid )
{
    pdo_query("1","
        delete from statusreads where shareid=? and xaccode='R'
        ",array($shareid));
    
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function PinPost( $providerid, $shareid, $postid, $roomid )
{
    $result = pdo_query("1","
        select pin from statuspost where shareid = ? and parent='Y'
            ",array($shareid));
    if($row = pdo_fetch($result)){
        $pin = $row['pin'];
    }
    if($pin == 0){
    
        pdo_query("1","
            update statuspost set pin = 10 where shareid = ? and 
                parent='Y' and
                (
                    exists 
                     (   select providerid from roommoderator 
                         where roomid=? and providerid=? 
                     ) 
                    or exists 
                     (   select providerid from statusroom 
                         where roomid=? and owner=? 
                     ) 
                )

            ",array($shareid, $roomid,$providerid,$roomid,$providerid ));
    } else {
        pdo_query("1","
            update statuspost set pin = 0 where shareid = ? and 
                parent='Y' and
                (
                    exists 
                     (   select providerid from roommoderator 
                         where roomid=? and providerid=? 
                     ) 
                    or exists 
                     (   select providerid from statusroom 
                         where roomid=? and owner=? 
                     ) 
                )

            ",array($shareid, $roomid,$providerid,$roomid,$providerid ));
        
    }
        
}
function LockPost( $providerid, $shareid, $postid, $roomid )
{
    
    $result = pdo_query("1","
        select locked from statuspost where postid = ? 
            ",array($postid));
    if($row = pdo_fetch($result)){
        $lock = 1;
        if($row['locked']==1){
           $lock = 0;
        }
    
        pdo_query("1","
            update statuspost set locked = $lock where postid = ? and 
                (
                    exists 
                     (   select providerid from roommoderator 
                         where roomid=? and providerid=? 
                     ) 
                    or exists 
                     (   select providerid from statusroom 
                         where roomid=? and owner=? 
                     ) 
                )

            ",array($postid,$roomid,$providerid,$roomid,$providerid));
    }
        
}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function UnPinPost( $providerid, $shareid, $postid, $roomid )
{
    pdo_query("1","
        update statuspost set pin = 0 where postid = ? and
            (
                exists 
                 (   select providerid from roommoderator 
                     where roomid=? and providerid=? 
                 ) 
                or exists 
                 (   select providerid from statusroom 
                     where roomid=? and owner=? 
                 ) 
            )
        ",array($postid, $roomid,$providerid,$roomid,$providerid));
        
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function RecentRooms ( $providerid, $format, $fulllist )
{

    return "";
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function RoomSizing()
{
    $mainwidth = '540px';
    $statuswidth = '500px';
    $statuswidth2 = '460px';
    $padding = '5px';
    if( !isset($_SESSION['sizing'])){
        $sizing['mainwidth'] = $mainwidth;
        $sizing['statuswidth'] = $statuswidth;
        $sizing['statuswidth2'] = $statuswidth2;
        $sizing['padding'] = $padding;
        return( (object) $sizing);
        
    }
    if( $_SESSION['sizing']=='2500'){
        $mainwidth = '1280px';
        $statuswidth = '1120px';
        $statuswidth2 = '800px';
        $padding = "50px";
    }
    if( $_SESSION['sizing']=='1900'){
        $mainwidth = '940px';
        $statuswidth = '910px';
        $statuswidth2 = '860px';
        $padding = "0px";
    }
    if( $_SESSION['sizing']=='1600'){
        $mainwidth = '840px';
        $statuswidth = '810px';
        $statuswidth2 = '760px';
        $padding = "0px";
    }
    if( $_SESSION['sizing']=='1400'){
        $mainwidth = '540px';
        $statuswidth = '480px';
        $statuswidth2 = '480px';
        $padding = "0px";
        /*
        $mainwidth = '640px';
        $statuswidth = '610px';
        $statuswidth2 = '560px';
        $padding = "20px";
         * 
         */
    }
    if( $_SESSION['sizing']=='1200'){
        $mainwidth = '540px';
        $statuswidth = '480px';
        $statuswidth2 = '480px';
        $padding = "0px";
        
    }
    if( $_SESSION['sizing']=='1000'){
        $mainwidth = '540px';
        $statuswidth = '480px';
        $statuswidth2 = '480px';
        $padding = '0px';
        
    }
    if( $_SESSION['sizing']=='600'){
        $mainwidth = '99%';
        $statuswidth = '99%';
        //$mainwidth = '550px';
        //$statuswidth = '520px';
        $statuswidth2 = '400px';
        $padding = '0px';
        
    }
    if( $_SESSION['sizing']=='414'){
        $mainwidth = '95%';
        $statuswidth = '93%';
        //$mainwidth = '394px';
        //$statuswidth = '374px';
        $statuswidth2 = '320px';
        $padding = '0px';
        
    }
    if( $_SESSION['sizing']=='375'){
        $mainwidth = '99%';
        $statuswidth = '99%';
        //$mainwidth = '355px';
        //$statuswidth = '345px';
        $statuswidth2 = '310px';
        $padding = '0px';
        
    }
    if( $_SESSION['sizing']=='320'){
        $mainwidth = '99%';
        $statuswidth = '99%';
        //$mainwidth = '300px';
        //$statuswidth = '280px';
        $statuswidth2 = '255px';
        $padding = '0px';
        
    }
    $sizing['mainwidth'] = $mainwidth;
    $sizing['statuswidth'] = $statuswidth;
    $sizing['statuswidth2'] = $statuswidth2;
    $sizing['padding'] = $padding;
            
            
    return( (object) $sizing);
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function RoomInfo($providerid, $roomid, $mainwidth, $page, $memberinfo)
{
    global $rootserver;
    global $installfolder;
    global $appname;
    global $global_titlebar_color;
    global $global_textcolor;
    global $menu_aboutme;
    
    $room = '';
    $handle = '';
    $roomdesc = '';
    $photo = '';
    $slideshow = "";
    $photourl = "";
    $photourl2 = "";
    $chatid = "";
    $chatidquiz = "";
    $profileflag = "";
    $avatarurl = "";
    $roominvitehandle = "";
    $publishprofile = "";
    $webpublishprofile = "";
    $ownerhandle = "";
    $sponsor="";
    $storeurl="";
    $external = "";
    $searchengine = "";
    $analytics = "";
    $subscription = 0;
    $subscriptionusd = 0;
    $subscriptionpending = '';
    $subscriptiondays = 0;
    $tokenpay = "";
    $adminroom = "";
    $wallpaper="";
    $radiostation = '';
    $store = '';
    $roomstyle = '';
    
    
    if($roomid == 'All'){
        return "";
    }
    $origproviderid = $providerid;
    
    
    if($providerid!=''){
        pdo_query("1","
            delete from statusreads where xaccode = 'R' and providerid = ? and roomid = ?
        ",array($providerid,$roomid));
    }

    $result = pdo_query("1","
            select distinct 
                roominfo.roomid, roominfo.room, 
                (select owner from statusroom where owner = providerid and statusroom.roomid = roominfo.roomid ) as owner,
                roominfo.external,
                roominfo.profileflag,
                roominfo.photourl,
                roominfo.photourl2,
                roominfo.roomdesc,
                roominfo.adminroom,
                roominfo.sponsor,
                roominfo.parentroom,
                roominfo.rsscategory,
                roominfo.anonymousflag,
                roominfo.adminroom,
                roominfo.roominvitehandle,
                roominfo.webtextcolor,
                roominfo.webpublishprofile,
                roominfo.webflags,
                roominfo.storeurl,
                roominfo.store,
                roominfo.searchengine,
                roominfo.analytics,
                roominfo.subscription,
                roominfo.subscriptionusd,
                roominfo.subscriptiondays,
                roominfo.wallpaper,
                roominfo.radiostation,
                roominfo.roomstyle,
                (select sponsor.logo from sponsor where sponsor.sponsor = roominfo.sponsor) as logo,
                (select chatid from chatspawned where chatspawned.roomid = roominfo.roomid limit 1) as chatid,
                (select chatid from chatmaster where chatmaster.roomid = roominfo.roomid and status='Y' order by chatid desc limit 1) as chatidquiz,
                (select handle from roomhandle where roomhandle.roomid = roominfo.roomid )
                as handle,
                (select count(*) from statuspost where statuspost.roomid = roominfo.roomid) as postcount
            
            from roominfo
            where 
            roominfo.roomid=?
            limit 1

            ",array($roomid));

    
    //<img src='../img/door-128.png' style='position:relative;top:5px;height:20px' />
    $ownername2 = 'Abandoned';
    $ownername = "Unmoderated";
    $avatarurl = "$rootserver/img/faceless.png";
    $profileroomid = 0;
    $ownerhandle = '';
    $publishprofile = '';

    if($row = pdo_fetch($result)){

        $profileflag = $row['profileflag'];
        $ownerid = $row['owner'];

        $result2 = pdo_query("1","
            select
            provider.profileroomid,
            provider.providername as ownername, 
            provider.avatarurl, provider.publishprofile,
            provider.handle as ownerhandle, provider.store
            from provider where providerid = ?
                ",array($ownerid)
                );
        if($row2 = pdo_fetch($result2)){

            $ownername2 = $row2['ownername'];
            $ownername = "Moderated by ".rtrim($row2['ownername']);
            if($row['adminroom']=='Y' ){
                $ownername = "Moderated by ".$appname ;
            }
            if($profileflag == 'Y'){
                $ownername = $row2['ownername'];
            }
            $profileroomid = $row2['profileroomid'];
            
            $ownerhandle = $row2['ownerhandle'];
            $publishprofile = $row2['publishprofile'];
            $avatarurl = $row2['avatarurl'];

            //Feed Room
            if($row['rsscategory']!='' || $row['anonymousflag']=='Y'){
                $avatarurl = "$rootserver/img/faceless.png";
                $ownerid = 0;
            }
            if($row2['store']!='Y'){
                $row['store']='N';
            }
            

        }
        
        
        
        $room = htmlentities($row['room'],ENT_QUOTES);
        $roomdesc = urldecode($row['roomdesc']);
        $handle = $row['handle'];
        $photourl = $row['photourl'];
        $photourl2 = $row['photourl2'];
        $chatid = $row['chatid'];
        $chatidquiz = $row['chatidquiz'];
        $roomstyle = $row['roomstyle'];
        $postcount = $row['postcount'];
        $photo = '';
        $sponsor = $row['sponsor'];
        $external = $row['external'];
        $adminroom = $row['adminroom'];
        $roominvitehandle = $row['roominvitehandle'];
        $webpublishprofile = $row['webpublishprofile'];
        $webtextcolor = $row['webtextcolor'];
        $webflags = $row['webflags'];
        $wallpaper = $row['wallpaper'];
        $searchengine = $row['searchengine'];
        $analytics = base64_decode($row['analytics']);
        $storeurl = $row['storeurl'];
        $store = $row['store'];
        $logo = $row['logo'];
        $radiostation = $row['radiostation'];
        $subscription = (float) $row['subscription'];
        $subscriptionusd = (float) $row['subscriptionusd'];
        $subscriptiondays = (float) $row['subscriptiondays'];
        if((float) $subscription !== 0 && $subscription!='' && $row['owner'] != $providerid){

            $subscriptionperiod = "One Time";
            if($subscriptiondays > 0){
                $subscriptionperiod = "for $subscriptiondays Days";
            }
            
            $subscriptionpending = 'Y';
            $tokenpaymsg = "This room has premium content. Subscribe
                        to get access.";
            if($memberinfo->subscribedate!='' && 
               $memberinfo->today >= $memberinfo->expiredate && $memberinfo->expiredate!='' ){
                $tokenpaymsg = "Your subscription has expired. Subscribe
                        to get access. ";
            }
            if($subscription < 0){
                $tokenpaymsg = " 
                    This is a subscription test. No actual subscription tokens are required. Please click on Subscribe to continue. 
                    To repeat the test, unsubscribe from the room.
                        ";
                
            }
            
            $tokenpay = "        
                <tr>
                    <td>
                        <br><center>
                        <span class='mainfont' style='color:$global_textcolor'>$tokenpaymsg
                        </span><br><br><br>
                        <a href='$rootserver/prod/roomtokenpay.php?roomid=$roomid&mode=p' 
                             style='text-decoration:none'>
                             <div class='divbuttontext' 
                             style='background-color:$global_titlebar_color;color:white'>
                                Subscribe $subscription Tokens $subscriptionperiod</div></a></center><br><br>
                    </td>
                </tr>
                ";
        }
        if((float) $subscriptionusd !== 0 && $subscriptionusd!='' && $row['owner'] != $providerid){

            $subscriptionperiod = "One Time";
            if($subscriptiondays > 0){
                $subscriptionperiod = "for $subscriptiondays Days";
            }
            
            $subscriptionpending = 'Y';
            $tokenpaymsg = "This room has premium content. Subscribe
                        to get access.";
            if($memberinfo->subscribedate!='' && 
               $memberinfo->today >= $memberinfo->expiredate && $memberinfo->expiredate!='' ){
                $tokenpaymsg = "Your subscription has expired. Subscribe
                        to get access. ";
            }
            if($subscriptionusd < 0){
                $tokenpaymsg = " 
                    This is a subscription test. No actual subscription tokens are required. Please click on Subscribe to continue. 
                    To repeat the test, unsubscribe from the room.
                        ";
                
            }
            
            $tokenpay = "        
                <tr>
                    <td>
                        <br><center>
                        <span class='mainfont' style='color:$global_textcolor'>$tokenpaymsg
                            <br>
                                Subscribe Paypal $subscriptionusd  $subscriptionperiod
                            
                        </span><br><br><br>
                            <form id='fmPaypal' method='post' action= 'https://www.sandbox.paypal.com/cgi-bin/webscr'>
                                    <input type='hidden' name='cmd' value='_xclick'>
                                    <input type='hidden' name='currency_code' value='USD'>
                                    <input type='hidden' name='business' value='rob@brax.me'>
                                    <input type='hidden' name='item_name' value='Room/$roomid'>
                                    <input type='hidden' name='item_number' value='$_SESSION[pid]'>
                                    <input type='hidden' name='amount' value='$subscriptionusd'>
                                    <input type='hidden' name='no_shipping' value='1'>
                                    <input type='hidden' name='tax' value='0'>
                                    <input type='hidden' name='return' value='$rootserver/$installfolder/paypalreturn.php?mode=1' />
                                    <input type='hidden' name='cancel_return' value='$rootserver/$installfolder/paypalreturn.php?mode=cancel1' />
                                    <input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'  style='height:47px'>
                                    <img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>
                            </form>
                           </center><br><br>
                    </td>
                </tr>
                ";
        }

        
        if($photourl !== '' && IsSlideshow($photourl)){
            //$photo = $comment;
            $temp = Explode("=",$photourl);
            //$slideshow = $temp[0]."=".rawurlencode($temp[1]);
            $photourl = '';
            $album = rawurlencode($temp[1]);
            $vars = null;
            parse_str( parse_url( $slideshow, PHP_URL_QUERY ), $vars );
            if($mainwidth!=='100%'){
                $width = $mainwidth;
                $height = round(intval($width)*(6/8)+30 )."px";
            } else {
                $width = $_SESSION['innerwidth']-2;
                $width = $width."px";
                $height = round(intval($width)*(6/8) )."px";
            }
            
            $slideshow = "
                     <iframe class='gridnoborder' src='$rootserver/$installfolder/slideshow.php?album=".$album."&pid=$providerid' 
                         width='$width' height='$height' seamles=seamless style='max-width:100%;padding:0;margin:0'   >
                     </iframe>
                     ";
        }
        
        if($photourl!=''){
            $photo .= "
                    <br>
                     <img class='feedphoto' src='$photourl' style='height:100px;;max-width:400px' />
                     ";
        }
        if(InternetTooSlow()){
            $photo = '';
        }

        $icon = "";//<img class='icon20' src='../img/private-128.png' style='top:2px' />";
            
        if($handle!=''){
            $ownername = "".$handle;
            $icon = "";
        }
        if($origproviderid === '' && $photo==''){
            return "";
        }
        if($origproviderid === '' && $photo!=''){
            return "
                        <div style='inline-block;margin:auto;text-align:center'>
                            <span class=smalltext style='font-weight:100'>
                            </span>
                            $photo
                        </div>
                        ";
        }
        $photo_plus = $photo;
        if( $roomdesc!=''){
            $photo_plus .= "
                     <div class='smalltext' style='padding-left:20px;padding-right:20px;padding-top:5px'>
                    $roomdesc
                     </div>
                     <br>
                     ";

        }
        if(intval($page)>0){
            $photo = "";
        }
        $ownerinfo['roomOwner'] = "
                    <div style='inline-block;margin:auto;text-align:center'>
                        <div class='pagetitle3' style='font-weight:bold;color:white;padding-top:5px'>
                            $room
                        </div>
                        <span class=smalltext style='font-weight:100'>
                            $ownername
                        </span>
                        $photo_plus
                    </div>
                    ";

        $parentroomid = "";
        $parentroomhandle = $row['parentroom'];
        if($row['parentroom']!=''){
            $result = pdo_query("1","
                select roominfo.roomid, roominfo.external
                from roomhandle 
                left join roominfo on roomhandle.roomid = roominfo.roomid
                where roomhandle.handle = '$row[parentroom]' and roominfo.external!='Y'
                ",null);
            if($row = pdo_fetch($result)){
                $parentroomid = $row['roomid'];
            }
                
            
        }
        
        $ownerinfo['profileflag'] = $profileflag;
        $ownerinfo['room'] = $room;
        if($profileflag == 'Y'){
            $ownerinfo['room'] = "$menu_aboutme";
        }
        
        $ownerinfo['ownername'] = $ownername;
        $ownerinfo['ownername2'] = $ownername2;
        $ownerinfo['photo'] = $photo;
        $ownerinfo['roomdesc'] = $roomdesc;
        $ownerinfo['photourl'] = $photourl;
        $ownerinfo['photourl2'] = $photourl2;
        $ownerinfo['handle'] = $handle;
        $ownerinfo['chatid'] = $chatid;
        $ownerinfo['chatidquiz'] = $chatidquiz;
        $ownerinfo['sponsor'] = $sponsor;
        $ownerinfo['parentroomid'] = $parentroomid;
        $ownerinfo['parentroomhandle'] = $parentroomhandle;
        $ownerinfo['profileroomid'] = $profileroomid;
        $ownerinfo['adminroom']= $adminroom;
        $ownerinfo['avatarurl'] = $avatarurl;
        $ownerinfo['ownerid'] = $ownerid;
        $ownerinfo['postcount'] = $postcount;
        $ownerinfo['roominvitehandle'] = $roominvitehandle;
        $ownerinfo['publishprofile'] = $publishprofile;
        $ownerinfo['webpublishprofile'] = $webpublishprofile;
        $ownerinfo['webtextcolor'] = $webtextcolor;
        $ownerinfo['webflags'] = $webflags;
        $ownerinfo['wallpaper'] = $wallpaper;
        $ownerinfo['ownerhandle'] = $ownerhandle;
        $ownerinfo['storeurl'] = $storeurl;
        $ownerinfo['store'] = $store;
        $ownerinfo['logo'] = $logo;
        $ownerinfo['external'] = $external;
        $ownerinfo['analytics'] = html_entity_decode($analytics);
        if($searchengine != 'Y'){
            $ownerinfo['analytics']='';
        }
        $ownerinfo['tokenpay'] = $tokenpay;
        $ownerinfo['subscriptionpending'] = $subscriptionpending;
            
        if($memberinfo->subscribedate!='' && $memberinfo->today <= $memberinfo->expiredate && $memberinfo->expiredate!='' ){
            $ownerinfo['subscriptionpending']='';
            $ownerinfo['tokenpay']='';
        }
        $ownerinfo['radiostation'] = $radiostation;
        $ownerinfo['roomstyle'] = $roomstyle;
        
        return( (object) $ownerinfo );
    }
    return "";

}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function MemberCheck($providerid, $roomid)
{
    if($providerid==''){
        $providerid ='0';
    }
    $memberinfo = array();
    $memberinfo['roomid'] = "All";
    $memberinfo['roomforsql'] = "";
    $memberinfo['roomhtml'] = "";
    $memberinfo['owner'] = "";
    $memberinfo['ownername'] = "";
    $memberinfo['handle'] = "";
    $memberinfo['anonymous'] = "";
    $memberinfo['member'] = "";
    $memberinfo['private'] = "";
    $memberinfo['adminonly'] = "";
    $memberinfo['roomfiles'] = "";
    $memberinfo['adminroom'] = "";
    $memberinfo['unread'] = "";
    $memberinfo['showmembers']="";
    $memberinfo['moderator']="";
    $memberinfo['groupname']="";
    $memberinfo['radiostation']="";
    $memberinfo['sponsor']="";
    $memberinfo['subscribedate']="";
    $memberinfo['today']='';
    $memberinfo['favorite']='';
    
    if(intval($roomid) > 0){
        //Find Room Owner
        $result = pdo_query("1","
            select statusroom.roomid, roominfo.room, roominfo.radiostation,
            statusroom.owner, provider.providername as ownername,
            (select 'Y' from notifymute where notifymute.id = statusroom.roomid and idtype='R' and notifymute.providerid = ? ) as mute,
            (select groupname from groups where groups.groupid = roominfo.groupid ) as groupname,
            (select providerid from roommoderator where roommoderator.roomid = statusroom.roomid
             and roommoderator.providerid = ?) as moderator,
            (select handle from roomhandle where roomhandle.roomid = statusroom.roomid) as handle,
            (select 'Y' from roomfavorites where roomfavorites.roomid = statusroom.roomid and roomfavorites.providerid = ? ) as favorite,
            roominfo.private, roominfo.anonymousflag, roominfo.adminonly, roominfo.adminroom, roominfo.showmembers,
            roominfo.sponsor, roominfo.profileflag,
            (select DATE_FORMAT(now(),'%Y%m%d' )) as today,
            (select DATE_FORMAT(subscribedate,'%Y%m%d') from statusroom s2 where s2.roomid = statusroom.roomid and s2.providerid = ? limit 1 ) as subscribedate,
            (select DATE_FORMAT(expiredate,'%Y%m%d') from statusroom s2 where s2.roomid = statusroom.roomid and s2.providerid = ? limit 1 ) as expiredate,
            (select 'Y' from statusroom s2 where s2.roomid = statusroom.roomid and s2.providerid = ? limit 1 ) as member,
            (select 
                DATE_FORMAT(date_add(createdate,INTERVAL $_SESSION[timezoneoffset]*60 MINUTE), '%b %d %a %h:%i %p')  
                from roomfiles 
                where roomfiles.roomid = statusroom.roomid 
                and timestampdiff( DAY, createdate, now() ) < 30
                order by createdate desc limit 1 
            ) as roomfiles,
            ( select 'Y' from statusreads where statusreads.providerid = ?
                and xaccode = 'R' and datediff( now(), actiontime) < 2 and 
                statusreads.roomid != ? limit 1 ) as unread
            from statusroom 
            left join roominfo on statusroom.roomid = roominfo.roomid
            left join provider on statusroom.owner = provider.providerid
            where statusroom.roomid=$roomid  and statusroom.owner = statusroom.providerid
                ",array($providerid, $providerid,$providerid,$providerid,$providerid,$providerid,$providerid,$roomid));
        if($row = pdo_fetch($result)){
            $memberinfo['roomid'] = $row['roomid'];
            $memberinfo['roomforsql'] = tvalidator("PURIFY",$row['room']);
            $memberinfo['roomhtml'] = htmlentities($row['room'],ENT_QUOTES);
            $memberinfo['owner'] = $row['owner'];
            $memberinfo['moderator'] = $row['moderator'];
            $memberinfo['ownername'] = $row['ownername'];
            $memberinfo['handle'] = $row['handle'];
            $memberinfo['anonymous'] = $row['anonymousflag'];
            $memberinfo['member'] = $row['member'];
            $memberinfo['private'] = $row['private'];
            $memberinfo['adminonly'] = $row['adminonly'];
            $memberinfo['roomfiles'] = $row['roomfiles'];
            $memberinfo['adminroom'] = $row['adminroom'];
            $memberinfo['unread'] = $row['unread'];
            $memberinfo['showmembers'] = $row['showmembers'];
            $memberinfo['groupname'] = $row['groupname'];
            $memberinfo['radiostation'] = $row['radiostation'];
            $memberinfo['sponsor'] = $row['sponsor'];
            $memberinfo['mute'] = $row['mute'];
            $memberinfo['subscribedate'] = $row['subscribedate'];
            $memberinfo['expiredate'] = $row['expiredate']; 
            $memberinfo['today'] = $row['today'];
            $memberinfo['favorite'] = $row['favorite'];
            $memberinfo['ownermoderatorflag']='N';
            $memberinfo['showmembers'] = $row['showmembers'];
            
            if($providerid == $row['owner'] || 
               $providerid == $row['moderator'] || 
               $_SESSION['superadmin']=='Y' ){
                $memberinfo['showmembers'] = "Y";
                $memberinfo['ownermoderatorflag']='Y';
            }
            if($row['adminonly']==''){
                $memberinfo['adminonly']='N';
            }
            if($row['profileflag']=='Y'){
                $row['member']='Y';
                NewProfileRoomMember($roomid, $row['owner'], $providerid);
            }
            
        }
    }
    return (object) $memberinfo;
    
}


/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function DisplayNewPost($readonly, $providerid, $roomid, $handle )
{
    global $menu_newtopic;
    global $admintestaccount;
    global $iconsource_braxadd_common;
    
    $owner = false;
    $roomanonymous = '';
    $adminroom = '';
    $roomtip = "";
    
    if($readonly == 'Y'){
        return "";
    }
    
    if( intval($roomid) > 0 ){
        $result = pdo_query("1","
                  select owner from statusroom 
                  where roomid=?  and owner=? ",
                array($roomid,$providerid));
        if($row = pdo_fetch($result)){
            $owner = true;
        }

        $result = pdo_query("1","
                select anonymousflag, adminroom, (select handle 
                from roomhandle where roomhandle.roomid = roominfo.roomid ) as handle
                from roominfo where roomid=? ",array($roomid));
        if($row = pdo_fetch($result)){
            $roomanonymous = $row['anonymousflag'];
            $roomhandle = $row['handle'];
            $adminroom = $row['adminroom'];
        }
    }

    $add = "";
    if(($adminroom!='Y' && $roomid !='All') || $providerid==$admintestaccount){
        $add =  "       
                <span class='newroompost makecommenttop mainfont' data-shareid=''  placeholder='$menu_newtopic' style='padding-left:10px;cursor:pointer;background-color:transparent;' >
                    <img class='icon20' src='$iconsource_braxadd_common' style='cursor:pointer;' />
                </Span>
                ";
    }

        if( $_SESSION['roomuser']=='1' && $roomid == 'All'){
            
            return "
                    <div class='makecommenttop tipbubble pagetitle3' style='color:white;background-color:#72b6e4;cursor:pointer'>
                    Tip: This view shows activity highlights in all rooms. Select a specific Room to interact with the group.
                    </div>
                    <br>
                    $add
            ";
        }  else 
        if( $_SESSION['roomuser']=='1'){
            
            if($_SESSION['mobilesize']=='Y'){
                return "
                    $add 
                    <br>
                ";
            }
            if($_SESSION['mobilesize']=='N'){
                return "
                    $add  
                    <br><br>
                    <div class='tipbubble pagetitle3' data-shareid='' style='color:white;background-color:#72b6e4;cursor:pointer'>
                    Any post in a Room sends mobile notifications to all members
                    </div>
                    <br>
                ";
            }
        }
        else
        if( $_SESSION['roomuser']=='N' && intval($roomid)==0){
            $roomtip = "<br>";
            if($_SESSION['enterprise']!='Y'){
                
                $roomtip .= 
                    "
                    <div class='tipbubble pagetitle3 gridstdborder' style='background-color:#72b6e4'>
                        <div class='roomjoin tapped' style='cursor:pointer;padding-left:20px;padding-right:20px;color:white' data-caller='room'>
                           Join a Room via #HashTag
                            <img src='../img/arrow-stem-circle-right-white-128.png' style='height:15px;position:relative;top:2px' />
                        </div>
                    </div>
                    <br>    
                    <div class='tipbubble pagetitle3 gridstdborder' style='background-color:#72b6e4'>
                        <div class='roomdiscover tapped' style='cursor:pointer;padding-left:20px;padding-right:20px;color:white' data-caller='room'>
                           Tip: Discover Open Rooms
                            <img src='../img/arrow-stem-circle-right-white-128.png' style='height:15px;position:relative;top:2px' />
                        </div>
                    </div>
                    <br>    
                ";
            }
            $roomtip .= 
                "
                <br>    
                $add
            ";
            /*
            $roomtip .= 
                "
                <div class='tipbubble pagetitle3 gridstdborder' style='background-color:#72b6e4'>
                    <div class='friends tapped' style='cursor:pointer;padding-left:20px;padding-right:20px;color:white' data-caller='room'>
                       Tip: Organize a Room for your Group
                        <img src='../img/arrow-stem-circle-right-white-128.png' style='height:15px;position:relative;top:2px' />
                    </div>
                </div>
                <br>    
                $add
            ";
             * 
             */
            return $roomtip;
        }
        else
        if( $roomid !='All'){
                return "
                    $add
                ";
        }

}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function DetermineRoomStage()
{
    if( $_SESSION['roomuser']!=''){
        $result = pdo_query("1","select count(*) as count from statusroom where owner = $_SESSION[pid]  ",null);
        $row = pdo_fetch($result);
        if( intval($row['count']) == 0){
            $_SESSION['roomuser'] = 'N';
        } else 
        if( intval($row['count']) == 1){
            $_SESSION['roomuser'] = '1';
            $result = pdo_query("1","select count(*) as count from statuspost where owner = $_SESSION[pid]  ",null);
            $row = pdo_fetch($result);
            if(intval($row['count']>0) )
            {
                    $_SESSION['roomuser']='A'; //Add Members
            }
        } else {
            $_SESSION['roomuser'] = '';
        }
    }
        
    if( $_SESSION['roomuser']=='N' ){
        $result = pdo_query("1","select count(*) as count from statusroom where providerid = $_SESSION[pid]  ",null);
        $row = pdo_fetch($result);
        if( intval($row['count']) > 0){
            $_SESSION['roomuser'] = 'M';
        }
    }
    //echo "roomuser = $_SESSION[roomuser]";
}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function LikeButton($providerid, $likes, $shareid, $postid, $roomid, $selectedroomid,$float, $parent, $scrollreference )
{
    global $global_icon_heart;
    global $global_backgroundreverse;
    
        if($providerid==''){
            return "";
        }
        if($parent == 'Y'){
            $class='feed';
        }
        else
        {
            $class='feedreply';
        }
        if($providerid == ''){
            $class = "";
        }
        if($likes !='0') {
            $likes = " ".$likes;
        }
        else {
            $likes = '';
        }
        if($likes == ''){
           $likebutton = "
                         <div class='$class divbuttonlike divbuttonlike0 divbuttonlike_unsel roomcontrols tapped smalltext2' style='$float'
                            data-shareid='$shareid' data-postid='$postid'  data-reference='$scrollreference' 
                            data-selectedroomid='$selectedroomid' data-roomid='$roomid' data-mode='L' data-parent='$parent'>
                             <img 
                             class='icon15'
                             src='../img/Heart-2_120px.png' 
                             style='top:0px' />
                             $likes</div>
                       ";
        }
        
        if($likes != ''){
            $likebutton = "
                         <div class='feedreply divbuttonlike divbuttonlike1 divbuttonlike_unsel roomcontrols tapped smalltext2'  
                            style='$float;background-color:$global_backgroundreverse;color:black;position:relative;top:-5px'
                            data-shareid='$shareid' data-postid='$postid'    data-reference='$scrollreference'
                            data-selectedroomid='$selectedroomid' data-roomid='$roomid' data-mode='L' data-parent='$parent'>
                            $global_icon_heart 
                             $likes
                          </div>
                       ";

        }
        
        return $likebutton;
    
}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function DeleteButton( $parent, $owner, $owner2, $moderator, $providerid, $shareid, $postid, $roomid, $selectedroomid, $float, $scrollreference )
{
        $superadmin = 'N';
        if(isset($_SESSION['superadmin'])){
            $superadmin = $_SESSION['superadmin'];
        }
        if(intval($providerid)==0){
            return "";
        }

        //if( $parent == 'Y' && $providerid != $owner && $superadmin!='Y' ){
        //    return "";
        //}
        if(  $providerid != $owner && $providerid !=$owner2 && $moderator!='Y' && $superadmin!='Y' ){
            return "";
        }
        $classes = "feedreply";
        $margin = "";
        if($parent == 'Y'){
            $classes = "feed";
            $margin = "margin-left:10px;margin-right:3px;top:0";
        } else {
            
         $margin = "position:relative;top:10px";
         
        }
        $deletebutton =  "
            <img 
            class='icon15 $classes roomcontrols tapped' 
            src='../img/Close_120px.png'  style='$margin;$float;opacity:1;'
            title='Delete post'
            data-mode='D' data-postid='$postid' data-shareid='$shareid'  data-roomid='$roomid'  
            data-selectedroomid='$selectedroomid'   
            data-reference='$scrollreference'                       
            />
        ";
        return $deletebutton;
}   
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function RoomManageMenu()
{
    return "      <br>
                <span class='pagetitle'>&nbsp;Manage Rooms</span> 
                <br>
                &nbsp;&nbsp;
                <div class='divbuttontextonly feed showtop tapped' id='feed'>
                    <img src='../img/arrow-stem-circle-left-white-128.png' style='position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />
                    All Blogs
                </div>
                <br><br><br>
                <table class='gridnoborder smalltext' style='margin:auto;text-align:center;color:white'>
                <tr>
                    <td>
                    <img src='../img/tools-128.png' style='height:120px;cursor:pointer' class='friends tapped'>
                    <br>Setup Blogs
                    <br>
                    </td>

                    <td>
                    <img src='../img/announce-128.png' style='height:120px;cursor:pointer' class='roomdiscover tapped'>
                    <br>Discover Blogs
                    <br>
                    </td>

                </tr>
                </table>
                <br><br><br>

           ";
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function ShareRoom ( $readonly, $caller, $providerid, $roomid, $style, $showroomtip, $callerflag, $owner )
{
    global $rootserver;
    global $installfolder;
    global $appname;
    global $global_textcolor;
    global $iconsource_braxarrowright_common;
    global $iconsource_braxlink_common;
    
    $owned = "";
    
    if($readonly == 'Y'){
        return "";
    }
    
    if( intval($roomid) == 0) {
        return "";
    }

    $icon2 = "$iconsource_braxlink_common";


    $result = pdo_query("1","
        select roomhandle.handle, roominfo.external,
        roominfo.room, statusroom.owner, roominfo.private
        from statusroom
        left join roomhandle on roomhandle.roomid = statusroom.roomid
        left join roominfo on roominfo.roomid = statusroom.roomid
        where statusroom.roomid=? and statusroom.owner=statusroom.providerid
            ",array($roomid));
    if($row = pdo_fetch($result)){
        
        $handle = $row['handle'];
        $private = $row['private'];
        $external = $row['external'];
        if($row['owner']=="$providerid" || $owner!=''){
            $owned = 'Y';
        }
        $room = str_replace('&amp;','%26',rawurlencode($row['room'])); 
        
    }  else {
        
        $external = "";
        $handle = "";
        $owned = "X";
    }
    if($handle == ''){
        $private = 'Y';
    }
    if(intval($roomid) <= 1){
        return "";
    }
    /* Note: Actual Links are created in RoomCreateInvite.php */

    
    if( $external=='Y' ){
            return "
             <div class='groupinvitecreate roombutton' style='vertical-align:top;display:inline-block;;color:$global_textcolor' data-roomid='$roomid' data-mode=''>
                 <img  class='icon35' src='$icon2' 
                     style='display:inline;cursor:pointer;position:relative;top:0px;margin:0;padding:0;text-align:center' />
                                     <br>
                                     <span class=smalltext>
                                     External
                                     <br>
                                     Website
                                     <br>
                                     <br>
                                     </span>
             </div>
             $style
            <br><br>
            <br><br>
            <span class='smalltext groupinvitelinkgroup'  style='display:none;color:$global_textcolor'>
                
                <b>Website URL to Share</b></span><br>
                <input class='groupinvitelink smalltext' type='text' readonly=readonly value='' 
                    style='display:none;background-color:white;;height:25px;max-width:80%;width:400px' />
                <img class='groupinvitelinkgroup groupinvitegotolink' src='$iconsource_braxarrowright_common' style='display:none;position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />
                    
            </span>

             ";
        
    }
    if( $private == 'Y' && $owned=='Y' ){
            return "
             <div class='groupinvitecreate roombutton' style='vertical-align:top;display:inline-block;;color:$global_textcolor' data-roomid='$roomid' data-mode=''>
                 <img  class='icon35' src='$icon2' 
                     style='display:inline;cursor:pointer;position:relative;top:0px;margin:0;padding:0;text-align:center' />
                                     <br>
                                     <span class=smalltext>
                                     External
                                     <br>
                                     Blog
                                     <br>
                                     Invite
                                     <br>
                                     </span>
             </div>
             $style
            <br><br>
            <br><br>
            <span class='smalltext groupinvitelinkgroup'  style='display:none;color:$global_textcolor'>
                
                <b>Room Invite URL to Share (48 Hour Lifespan)</b></span><br>
                <input class='groupinvitelink smalltext' type='text' readonly=readonly value='' 
                    style='display:none;background-color:white;;height:25px;max-width:80%;width:400px' />
                <img class='groupinvitelinkgroup groupinvitegotolink' src='$iconsource_braxarrowright_common' style='display:none;position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />
                    
            </span>

             ";
        
    }
    if( $private === 'Y' && $owned!=='Y' ){
            return "
             <div class='roombutton' style='vertical-align:top;display:inline-block;;opacity:0.2;color:$global_textcolor' data-roomid=''>
                 <img  class='icon35 forowner'    src='$icon2' 
                     style='display:inline;cursor:pointer;position:relative;top:0px;margin:0;padding:0;text-align:center' />
                                     <br>
                                     <span class=smalltext>
                                     Blog
                                     <br>
                                     Invite
                                     <br>Link<br>
                                     </span>
             </div>
             $style
            <br><br>
             ";
        
    }
    

        $returntext = "
        <div class='groupinvitecreate' style='vertical-align:top;display:inline-block;height:90px;width:70px;color:$global_textcolor' data-roomid='$roomid'  data-mode=''>
            <img class='icon35' src='$icon2' style='display:inline;cursor:pointer;position:relative;top:0px;width:auto;margin:0;padding:0;text-align:center' />
                                <br>
                                <span class=smalltext>
                                In-App
                                <br>
                                Invite
                                <br>
                                </span>
        </div>
        $style
        <div class='groupinvitecreate' style='vertical-align:top;display:inline-block;height:90px;width:70px;color:$global_textcolor' data-roomid='$roomid'  data-mode='S'>
            <img class='icon35' src='$icon2' style='display:inline;cursor:pointer;position:relative;top:0px;width:auto;margin:0;padding:0;text-align:center' />
                                <br>
                                <span class=smalltext>
                                External
                                <br>
                                Signup
                                <br>
                                Link
                                <br>
                                </span>
        </div>
        $style
            ";

        
        $returntext .= "
            <br><br>
            <span class='smalltext groupinvitelinkgroup'  style='display:none;color:$global_textcolor'>
                <b>Invite URL to Share</b></span><br>
                <input class='smalltext groupinvitelink' type='text' readonly=readonly value='' 
                    style='display:none;background-color:white;height:25px;max-width:80%;width:300px' />
                <img class='groupinvitelinkgroup groupinvitegotolink' src='$iconsource_braxarrowright_common' 
                    style='display:none;position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />
                <br>
            </span>
        ";
        return $returntext;
 
}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

        
function FormatComment( $callerstyle, $postid, $providerid, $roomid, $encoding, $incomment, $title, 
        $inphoto, $album, $invideo, $inlink, $style, $parent, $mainwidth, $statuswidth2, 
        $videotitle, $articleid, $page, $readonly, $blockee, $blocker, $moderator )
{
    global $rootserver;
    global $installfolder;
    global $global_activetextcolor;
    global $global_activetextcolor_onwhite;
    global $global_textcolor;
    global $global_titlebar_color;
    global $global_background;
    global $menu_edit;
    global $menu_more;
    global $menu_save;
    global $menu_cancel;
    
    if($blocker!='' || $blockee!=''){
        $comment = "";
        if($blockee!=''){
            $comment = "
                <div style='color:black;word-wrap:break-word;padding-left:20px;padding-right:20px;padding-top:20px;$style'>
                  (Blocked Content)
                </div>
                ";
        }
        $comment .= "";
        return $comment;
    }
    
    
    
    //$mainwidth = "540px";
    //$statuswidth2 = "460px";
    $dontshortenflag = false;
    $cleanPostid = str_replace(".",'',$postid );
    
    if($readonly == 'Y'){
        $dontshortenflag = true;
    }
    
    //Temporary during testing
    //if($_SESSION['superadmin']!='Y'){
    //    if($album!=''){
    //        $inphoto = '';
    //    }
    //    
    //}
    
    $owner = false;
    if( //$_SESSION['superadmin']=='Y' && 
        $providerid == $_SESSION['pid'] && 
        $inlink =='' && 
        $inphoto =='' && 
        $invideo == '' && 
        $incomment!=''){
            $owner = true;
    }
    if($moderator == 'Y'){
        $owner = true;
        
    }
    $page = "0";
    
    $decryptedpost = DecryptPost( $incomment, $encoding, $providerid, "");
    $newcomment = DecryptPost( $incomment, $encoding, "", "");
    
    $mypost = $decryptedpost;
    if( strstr($decryptedpost,"slideshow ")!==false &&
        strstr($decryptedpost,"img src=")!==false            
      ){
        $owner = false;
        $dontshortenflag = true;
    }
    if( strstr($decryptedpost,"videoplayer.php")!==false &&
        ( strstr($decryptedpost,"bytz.io")!==false || 
          strstr($decryptedpost,"brax.me")!==false  )
      ){
        $owner = false;
        $dontshortenflag = true;
    }

    $comment = '';
    if($inphoto!='') {
        
        if( $encoding !='SPA1.0' && $encoding!='BASE64'){
            
            $photo = DecryptPost($inphoto,$encoding,"$providerid","");
            //This is a conversion fix. Some entries have blank that was encrypted  
            if($photo === ''){
                $inphoto = "";
            } else {
                $photo = HttpsWrapper($photo);
            }
            
        } else {
            
            $photo = $inphoto;
        }
    }
    if($inphoto!='') {

        if($parent == 'Y'){
            
            $maxwidth = $mainwidth;
            //$maxwidth = "100%";
            /*
            if($maxwidth == '100%'){
            
                $innerwidth = intval($_SESSION['innerwidth'])-2;
                $maxwidth = $innerwidth."px";
            }
             * 
             */
            
        } else {
            
            $maxwidth = $statuswidth2;
        }

        $photolink = RootServerReplace(WrapPhoto($photo));
        
        $slideshowalbum = "";
        //slideshow
        if($album!=''){
            
            $slideshowclass = 'slideshow';
            $slideshowalbum = "data-album='$album' ";
            $slideshowstyle = 'cursor:pointer;max-height:300px;';
            
            $comment = "
                <div class='roomphoto $slideshowclass' data-providerid='$providerid' $slideshowalbum src='$photolink'  
                    style='height:300px;max-width:100%;$slideshowstyle;overflow:hidden;background-color:$global_background;text-align:center'
                >
                    <div class='pagetitle3' style='width:100%;background-color:$global_background;color:$global_textcolor;margin:0;padding:5px;text-align:center'>
                        Tap for Slide Show
                    </div>
                    <img class='roomphoto' src='$photolink'  
                        style='max-width:100%;height:auto;max-height:100%;
                        background-color:white;
                        margin:auto' />
                </div>
                ";

            if($parent !=='Y'){

                $comment = "
                    <div class='roomphoto $slideshowclass' data-providerid='$providerid' $slideshowalbum src='$photolink'  
                        style='height:300px;max-width:100%;$slideshowstyle;margin-right:10px;margin-bottom:20px;overflow:hidden'
                    >
                        <img class='' src='$photolink' 
                            style='height:auto;width:auto;max-width:100%;max-height:100%;
                            background-color:white;'
                        />
                    </div>
                    <br><br>";
            }
            

        } else {
            
            $comment = "
                <img class='roomphoto expandphoto'  src='$photolink'  
                    style='height:auto;width:100%;max-height:600px;overflow:hidden;
                    background-color:white;
                    margin:auto' />
                ";

            if($parent !=='Y'){

                $comment = "
                    <img class='roomphoto expandphoto'  src='$photolink'  
                        style='height:auto;width:100%;max-height:600px;margin-right:10px;margin-bottom:20px;overflow:hidden;
                        background-color:white;'
                    />
                    <br><br>";
            }
            
        }


     

    }
    if( $invideo!=''){
        
        if( $encoding!='SPA1.0' && $encoding!='BASE64'){
            $video = HttpsWrapper(DecryptPost($invideo, $encoding, "$providerid",""));
        } else {
            $video = $invideo;
        }
        
        $video = YouTubeMeta($video, $videotitle);
        
        $comment .= $video."<br>";
    }
    if( $comment == ''){
        //$comment = "<img class='feedphoto desaturate' src='../img/background-abstract-yellow.jpg' />";
    }

    //if( $parent == 'Y' ){
    if(true){
        if($postid == 'A63cf5dc6705e89.13097108'){
                $comment .= "
                             <div style='color:$global_textcolor;word-wrap:break-word;
                                padding-left:30px;padding-right:20px;padding-top:10px;$style'>$decryptedpost $postid<br>$incomment, $encoding, $providerid<br>$newcomment</div>
                            ";
            
        }
        $tmp = html_entity_decode( $decryptedpost, ENT_QUOTES);
        //$tmp = $decryptedpost;
        if($tmp!=''){
            $tmp = RootServerReplace(WrapLinks($tmp) );
            $tmp = LinkToBrowser($tmp);
            if($callerstyle == '2'){
                $comment .= "
                             <div style='color:$global_textcolor;word-wrap:break-word;
                                padding-left:30px;padding-right:20px;padding-top:10px;$style'>
                            ";
                $tmp = str_replace(" class='roomposttitle'"," class='pagetitle2' style='color:$global_textcolor'",$tmp);
                
            }
            if($callerstyle == '3'){
                $comment .= "
                             <div style='color:black;word-wrap:break-word;
                                padding-left:30px;padding-right:20px;padding-top:10px;$style'>
                            ";
                
            }
            
            if($parent == 'Y' && $callerstyle == ''){
                if($comment == "" ){
                    $comment .= "<br>";
                }
                $comment .= "<div style='color:black;padding:0px'>
                             <div style='color:black;word-wrap:break-word;
                                padding-left:30px;padding-right:20px;padding-top:20px;$style'>
                            ";
            }
            //$tmp = wordwrap($tmp,30,"<br />", true);

            $testshort = strip_tags($tmp,"<span><br>");
            //$short = substr(strip_tags($tmp,"<span><br>"),0,300);
            //$short = substr(strip_tags($tmp),0,300);
            $short =$tmp;

            $more = "";
            if(strlen($tmp)>1){
                
                if($callerstyle ==''){
                    $more = "<span class='showmore tapped' style='color:$global_activetextcolor_onwhite' >
                            ... <img class='icon15' src='../img/Arrow-Right-in-Circle_120px.png' style='top:3px' /> $menu_more
                         </span>";
                    if($owner){
                        $more = "<span class='showmore tapped' style='color:$global_activetextcolor_onwhite' >
                                     <img class='icon15' src='../img/Arrow-Right-in-Circle_120px.png' style='top:3px' /> $menu_edit
                                 </span>";

                    } 
                } else {
                    $more = "";
                    if($callerstyle =='2' || $callerstyle=='3' ){
                        $dontshortenflag = true;
                        $more = "...";
                    }
                }
            }
            //$more = "";
            if( strlen($testshort)<= 300 || $dontshortenflag ){
                //$short = substr(strip_tags($tmp),0,300);
                $short = $tmp;
                if(!$owner){
                    $more = "";
                }
            } else {
                
                $short = substr(strip_tags(removeEmoji2(str_replace("<br>","\\n",$tmp)),"<span><br>"),0,300);
                $short = str_replace("\\n","<br>",$short);
                
            }

            $tmp = preg_replace('{(<br>)+$}i', '', $tmp); //from end
            if($owner){
                $short = $tmp;
                
                $tmp2 = str_replace("<br>","&#10;",$tmp);
                //Convert Span RoompostTitle to <title>
                $tmp2 = str_replace(" class='roomposttitle'","",$tmp2);
                $tmp2 = str_replace("span","title",$tmp2);
                //Remove <a> Tags
                $tmp2 = UndoLinks($tmp2);
                $tmp2 = strip_tags($tmp2,"<title>");
                if($parent == 'Y' && strpos($tmp2,"<title>")===false){
                    $tmp2 = "<title></title>".chr(10).chr(10).$tmp2;
                }
                
                
                $commentlong = "
                            <span class='commentlong' style='display:none'>
                                <textarea id='roomedit-$cleanPostid' class='mainfont' style='width:100%;height:300px'>$tmp2</textarea>
                                <br><br>
                                <div class='feededitpost tapped' style='display:inline-block;cursor:pointer' 
                                    data-roomid='$roomid' data-postid='$postid'  data-owner='$providerid'
                                     data-postidclean='$cleanPostid' data-page='$page' >
                                    <img class='icon15' src='../img/arrow-circle-right-gray-128.png' style='top:3px' /> $menu_save
                                </div>
                                <div class='feed tapped' style='display:inline-block;cursor:pointer' 
                                    data-roomid='$roomid' data-postid='$postid' 
                                     data-postidclean='$cleanPostid' data-page='$page' >
                                    <img class='icon15' src='../img/arrow-circle-right-gray-128.png' style='top:3px' /> $menu_cancel
                                </div>
                            </span>$more 
                ";
            } else {
                $commentlong = "
                            <span class='commentlong' style='display:none'>
                            $tmp
                            </span>$more
                ";             
                
            }
            if($tmp!=''){
                $comment .= "<span class='maincomment'>
                               <span class='commentshort' >
                                $short
                               </span>
                               $commentlong
                               
                            </span>";
            }



            if($inlink !=''){
                
                if( $comment!=''){
                    $comment.="<br>";
                }
                $link2 = wordwrap($inlink,30,"<br />", true);
                $comment .= "<br><a href='$inlink' target='_self' >$link2</a><br>";
                /*
                if($_SESSION['superadmin']=='Y'){
                    $comment .= "<br><a href='$inlink' target='_self' >$link2</a><br>";
                } else {
                    $comment .= "<br><a href='$inlink' target='_blank' >$link2</a><br>";
                    
                }
                 * 
                 */
            }
            if(intval($articleid)!=0){
                $comment .= "
                            <br><br>
                            <div class='rssview mainfont' data-articleid='$articleid' 
                                style='color:$global_activetextcolor_onwhite;cursor:pointer'>
                                View Full Article
                            </div>
                            <br>
                            ";
                if(intval($providerid) == 0){
                    $comment .=
                            "
                            <div class='roomsharepost mainfont' data-articleid='$articleid' 
                                data-roomid='$roomid' data-mode=''
                                style='color:$global_activetextcolor_onwhite;cursor:pointer'>
                                Share
                            </div>
                            <br>
                            ";
                }
            }
            
            if($parent == 'Y' && $callerstyle==''){
                $comment .= "</div></div>";
            }
            if($callerstyle=='2'){
                $comment .= "</div>";
            }
        }
        
    } else {
        $comment1 = html_entity_decode($decryptedpost);
        if($comment1!=''){
            $comment1 = WrapLinks($comment1);
        }
        $comment .= $comment1;
    }
    return $comment;


}






/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function IsHttps($url)
{
    $u = strtolower($url);
    if( substring($u,0,8) == "https://" ){
        return true;
    }
    return false;
}

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function RoomPosterInfo( $roomid, $providerid, $avatarurl, $adminroom, $private, $anonymous, $roomanonymous, $name, $name2, $alias, $handle, $blockee, $blocker, $medal )
{
        global $rootserver;
        global $appname;
        global $admintestaccount;
        
        $poster = array();


        //If not anonymous use real name
        $poster['name'] = $name;
        $poster['avatar']="$avatarurl";
        $poster['nochat']='';
        
        //Use Name 2 for public
        if($handle!='' && $name2!='' && $private == 'N'){
            $poster['name'] = $name2;
        }
        //If in public rooms and Alias Requested - use alias
        if(($adminroom=='Y' || $handle!='') && $anonymous == 'A' && $alias!='' ){
            $poster['name'] = $alias;
        }
        if( ($adminroom == 'Y' && intval($providerid)==$admintestaccount) ||
            ($roomid == 1 && intval($providerid)==$admintestaccount) 
            ){
            $poster['name'] ="$appname Admin";
            $poster['avatar']="$rootserver/img/admin.png";
        }
        $poster['medal']=$medal;
        if($anonymous =='Y' || $roomanonymous=='Y'){
            $poster['name'] ='Anonymous';
            //$poster['avatar']="$rootserver/img/faceless.png";
            //if($poster['avatar'] == "$rootserver/img/faceless.png"){
                $poster['avatar'] = "$rootserver/img/egg-blue.png";
            $poster['nochat']='Y';
            $poster['medal'] = "";
            //}
        }
        if($poster['avatar'] == "$rootserver/img/faceless.png" || $poster['avatar']==''){
            $poster['avatar'] = "$rootserver/img/egg-blue.png";
        }
        if($poster['name']==''){
            $poster['name'] ='Feed';
        }
        if($blockee!='' || $blocker!=''){
            $avatarurl = "$rootserver/img/egg-blue.png";
            $poster['name'] = "Unknown";
        }
        
        
        return (object) $poster;
        
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */


 function ApplySponsor($providerid, $sponsor)
 {
     if(intval($providerid)==0 || $sponsor == ''){
         return;
     }
     $result = pdo_query("1","
         select priority from sponsor where sponsor = ? 
         ",array($sponsor));
     if( $row = pdo_fetch($result)){
         $priority = $row['priority'];

         //Always apply sponsor
        if($priority == 1){
            pdo_query("1","
                 update provider set sponsor = ? 
                 where providerid = ?
                ",array($sponsor,$providerid));
        } else 
        if($priority == 2){
            //Do not override existing sponsor
            pdo_query("1","
                 update provider set sponsor = ? 
                 where sponsor = '' and providerid = ? 
                ",array($sponsor,$providerid));
        } else {
            
        }
     }
     
 }

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function WrapLinks($text)
{
    global $installfolder;
    global $rootserver;
    //return $text;
    $malwareflag = false;
    $html = new simple_html_dom();

    // load the entire string containing everything user entered here

    $return = $html->load($text);
    $links = $html->find('a');

    foreach ($links as $link){ 
        if(SafeUrl($link->href)== false){
            $malwareflag = true;
        }
        if(isset($link->href) && substr( strtolower($link->href),0,6 )!="https:"){
            //$link->href = 'https://bytz.io/prod/wrap.php?u=' . htmlentities($link->href);
            $link->href = "$rootserver/$installfolder/wrap.php?u=" . $link->href;
        }
        if(@tvalidator("PURIFY",$_SESSION['mobiletype'])=='A' || @tvalidator("PURIFY",$_SESSION['mobiletype'])=='I'){
            $link->target = "_blank";
        }
    }
    $text = $html->save();
    if( $malwareflag == true){
        $text = strip_tags($text,"<br><b><p>");
        $text .= "<br><br><b style='color:firebrick'>Google has flagged the link(s) above as possible malware. If you wish to access the link, we recommend that you use a TOR Browser.</b>";
    }
    
    
    return $text;
}    
function UndoLinks($text)
{
    
    $html = new simple_html_dom();

    // load the entire string containing everything user entered here

    $return = $html->load($text);
    $links = $html->find('a');

    foreach ($links as $link){ 
        $href = $link->href;
        $link->outertext = $href;
    }
    $text = $html->save();
    
    
    return $text;
}    

/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function WrapPhoto($text)
{
    global $rootserver;
    global $installfolder;
    //return $text;
    $html = new simple_html_dom();

    // load the entire string containing everything user entered here

    $return = $html->load($text);
    $links = $html->find('a');
    
    
    foreach ($links as $link){
        if( isset($link->href) && 
            substr( strtolower($link->href),0,6 )!="https:"){
                $shortlink = $link->href;
                if(substr( strtolower($link->href),0,7 )!=="http://"){
                    $shortlink = substr($link->href,7);
                }
            $link->href = "$rootserver/$installfolder/wrapphoto.php?u=$text" . $shortlink;
        }
    }
    $text = $html->save();
    
    
    return $text;
}    
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function CleanImage($text)
{
    //return $text;
    $html = new simple_html_dom();

    // load the entire string containing everything user entered here

    $return = $html->load($text);
    $images = $html->find('img');

    foreach ($images as $img) {
        $img->class = "feedphoto";
        $img->width = null;
        $img->height = null;
        $img->srcset = null;
        $img->sizes = null;
        $img->style = 'max-width:99%;float:left;padding-right:10px;padding-bottom:10px';
    }
    $text = $html->save();
    
    
    return $text;
}    
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */


function SafeUrl($url)
{
    return true;
    
    //Deprecated by Google so no longer used. Need a local version in V4 API of Safebrowsing

    $apikey = "";
    $encoded_url = urlencode($url);
    
    $api = "https://sb-ssl.google.com/safebrowsing/api/lookup?client=CLIENT&key=$apikey&appver=1.1&pver=3.1&url=$encoded_url";    
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);
    
    if($data == "malware"){
        return false;
    } else {
        return true;
    }

    
    //APIKEY
}

    function removeEmoji2($text)
    {
        
        return preg_replace('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1FFFF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
        
    }
    function LinkToBrowser($source)
    {
        //return $source;
        if($_SESSION['mobiletype']=='I'){
            return str_replace("_blank","_self",$source);
        } else {
            return $source;
        }
        
    }
?>
