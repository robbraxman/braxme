<?php
require_once("notify.inc.php");
require_once('simple_html_dom.php');
require_once("lib_autolink.php");

function RoomPostNew( 
        $mode, $providerid, $shareid, $roomid, $room, $title, $comment, 
        $video, $photo, $link, $anonymous, $articleid )
{
        global $rootserver;
        global $installfolder;
    
        if( $roomid =='' || $roomid == 'All ') {
            return false;
        }
        
        //New Post
        if($mode == 'P'){
            
            $shareid = uniqid("BZG", true);
            $parent = 'Y';
            $owner = $providerid;
            
        } else {
            
            $parent = 'N';
            $result = pdo_query("1","
                select owner from statuspost where shareid='$shareid' and parent = 'Y'
                ");
            if( $row = pdo_fetch($result)){
                $owner = $row['owner'];   
            }
            
        }

        $postid = uniqid("A", true);
        
        $title = rtrim(ltrim($title));
        $comment = rtrim(ltrim($comment));
        $video = rtrim(ltrim($video));
        $link = rtrim(ltrim($link));
        $slideshow = "";
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
                    $text_comment .= "<br><br>
                    <img src='$firstimg' class='slideshow roomalbum' data-providerid='$providerid' data-album='".$vars['a']."' style='cursor:pointer;width:100%;height:auto' />    
                            <br>
                            <div class='roomalbumtitle'>
                                Slideshow: ".$vars['a']."
                                <br><span class='smalltext' style='color:firebrick'>Tap Image for Slideshow </span>
                            </div>
                           ";
                    
                } else
                if(IsVideo($comment_item)){
                    $text_comment .= " ".YouTube($comment_item);
                    
                } else
                if(IsPhoto($comment_item )){
                    
                    if($photo==''){
                        //Post Photo if first
                        $photo = $comment_item;
                                
                    } else {
                        $text_comment .= " ".$comment_item;
                    }
                    
                } else 
                if(IsLink($comment_item )){
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
                    $text_comment .= " ".$comment_item;
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
        
        //HtmlEntities now DOUBLED
        $combinedcomment = htmlentities( $precomment.$combinedcomment, ENT_QUOTES );

        //Test
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
        if($photo!=''){
            $photo = EncryptPost($photo, "$providerid","");
        }
        //$room = tvalidator("PURIFY",$room);

        pdo_query("1","
            insert into statuspost
            (providerid, comment, postdate, shareid, parent, 
             owner, likes, room, roomid, postid,
             link, photo, video, encoding, anonymous, articleid ) values
            ($providerid, '$encryptedcomment', now(), '$shareid','$parent', 
             $owner, 0, '$room', $roomid,'$postid',
              '','$photo','$video','$encoding','$anonymous', $articleid )
                ");
            
            
        FlagUnreadPost( $providerid, $shareid, $postid, $roomid, $anonymous, $mode );
        FlagMakePost(   $providerid, $shareid, $postid, $roomid );

        return $postid;
}


function RoomPostNew2( 
        $mode, $providerid, $shareid, $roomid, $room, $title, $comment, 
        $video, $photo, $link, $anonymous, $articleid )
{
        global $rootserver;
        global $installfolder;
    
        if( $roomid =='' || $roomid == 'All ') {
            return false;
        }
        
        //New Post
        if($mode == 'P'){
            
            $shareid = uniqid("BZG", true);
            $parent = 'Y';
            $owner = $providerid;
            
        } else {
            
            $parent = 'N';
            $result = pdo_query("1","
                select owner from statuspost where shareid='$shareid' and parent = 'Y'
                ");
            if( $row = pdo_fetch($result)){
                $owner = $row['owner'];   
            }
            
        }

        $postid = uniqid("A", true);
        
        $title = rtrim(ltrim($title));
        $comment = rtrim(ltrim($comment));
        $video = rtrim(ltrim($video));
        $link = rtrim(ltrim($link));
        $slideshow = "";
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
                    $text_comment .= "<br><br>
                    <img src='$firstimg' class='slideshow roomalbum' data-providerid='$providerid' data-album='".$vars['a']."' style='cursor:pointer;width:100%;height:auto' />    
                            <br>
                            <div class='roomalbumtitle'>
                                Slideshow: ".$vars['a']."
                                <br><span class='smalltext' style='color:firebrick'>Tap Image for Slideshow </span>
                            </div>
                           ";
                    
                } else
                if(IsVideo($comment_item)){
                    $text_comment .= " ".YouTube($comment_item);
                    
                } else
                if(IsPhoto($comment_item )){
                    
                    if($photo==''){
                        //Post Photo if first
                        $photo = $comment_item;
                                
                    } else {
                        $text_comment .= " ".$comment_item;
                    }
                    
                } else 
                if(IsLink($comment_item )){
                    if(filter_var($comment_item, FILTER_VALIDATE_URL)){
                        $imageOG = GetOGImageTag($comment_item);
                        $photo = $imageOG;  
                        $text_comment .= " ".$photo;
                    } else {
                        $text_comment .= " ".$comment_item;
                    }               
                    
                } else {
                    $text_comment .= " ".$comment_item;
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
        
        //HtmlEntities now DOUBLED
        $combinedcomment = htmlentities( $precomment.$combinedcomment, ENT_QUOTES );

        //Test
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
        if($photo!=''){
            $photo = EncryptPost($photo, "$providerid","");
        }
        //$room = tvalidator("PURIFY",$room);

        pdo_query("1","
            insert into statuspost
            (providerid, comment, postdate, shareid, parent, 
             owner, likes, room, roomid, postid,
             link, photo, video, encoding, anonymous, articleid ) values
            ($providerid, '$encryptedcomment', now(), '$shareid','$parent', 
             $owner, 0, '$room', $roomid,'$postid',
              '','$photo','$video','$encoding','$anonymous', $articleid )
                ");
            
            
        FlagUnreadPost( $providerid, $shareid, $postid, $roomid, $anonymous, $mode );
        FlagMakePost(   $providerid, $shareid, $postid, $roomid );

        return $postid;
}

function RoomPostEdit( 
        $providerid, $postid, $title, $comment ) 
{
        global $rootserver;
        global $installfolder;
    
        
        $title = rtrim(ltrim($title));
        $comment = rtrim(ltrim($comment));
        $text_comment = autolink($text_comment, 50, ' class="chatlink" target="_blank" ', false);
        
        $combinedcomment = $text_comment;
        
        //HtmlEntities now DOUBLED
        $combinedcomment = htmlentities( $precomment.$combinedcomment, ENT_QUOTES );

        //Test
        $encoding = $_SESSION['responseencoding'];
        $encryptedcomment = EncryptPost($combinedcomment, "$providerid","");    
        

        pdo_query("1","
            update statuspost
            set comment = '$encryptedcomment', encoding='$encoding'
            where postid = '$postid'
                ");

        return true;
}


function RoomPost( 
        $mode, $providerid, $shareid, $roomid, $room, $title, $comment, 
        $video, $photo, $link, $anonymous, $articleid )
{
        if($_SESSION['superadmin']=='Y'){
            
            return RoomPostNew( 
            $mode, $providerid, $shareid, $roomid, $room, $title, $comment, 
            $video, $photo, $link, $anonymous, $articleid );
            
        } 
        return RoomPostNew( 
        $mode, $providerid, $shareid, $roomid, $room, $title, $comment, 
        $video, $photo, $link, $anonymous, $articleid );
        return;
    
        global $rootserver;
        global $installfolder;
    
        if( $roomid =='' || $roomid == 'All ') {
            return false;
        }
        
        //New Post
        if($mode == 'P'){
            
            $shareid = uniqid("BZG", true);
            $parent = 'Y';
            $owner = $providerid;
            
        } else {
            
            $parent = 'N';
            $result = pdo_query("1","
                select owner from statuspost where shareid='$shareid' and parent = 'Y'
                ");
            if( $row = pdo_fetch($result)){
                $owner = $row['owner'];   
            }
            
        }

        $postid = uniqid("A", true);
        
        $title = rtrim(ltrim($title));
        $comment = rtrim(ltrim($comment));
        $video = rtrim(ltrim($video));
        $photo = rtrim(ltrim($photo));
        $link = rtrim(ltrim($link));
        $slideshow = "";
        

        //Works only if Entire link is photo ONLY
        if($photo === '' && IsPhoto($comment)){
            $photo = $comment;
            $comment = "";
        }
        
        //Detect Video
        if(IsVideo($comment)){
            $video = $comment;
            $comment = "";
        }
        if($photo !== '' && IsSlideshow($photo)){
            //$photo = $comment;
            $slideshow = $photo;
            $photo = '';
        }
        
        
        
        //LogDebug($providerid, "2providerid $providerid, room $roomid, share $shareid, roomforsql $room, imgurl $photo, title $title ");
        $combinedcomment = "";
        if( $comment!='' || 
            $title!='' || 
            $video!='' || 
            $photo!='' || 
            $link!='' || 
            $slideshow!='' ){
            
            if($comment!=''){
                //$comment = strip_tags($comment, "<a><br><b><u><ul><li><iframe>");
                $comment = htmlspecialchars($comment, ENT_COMPAT);
                $comment = str_replace("\\n","<br>", $comment);
                
                if(filter_var($comment, FILTER_VALIDATE_URL)){
                    $imageOG = GetOGImageTag($comment);
                    $photo = $imageOG;  
                }                
                
                $comment = autolink($comment, 50, ' class="chatlink" target="_blank" ', false);
            }
            
            
            //$link = strip_tags($link );
            
            $combinedcomment = $comment;
            if( $photo!=''){
                
                $photo = strip_tags($photo );
                $hold_photo = str_replace("\\n", " <br>", $photo);
                
                $hold = explode(" ",$hold_photo);
                $text_comment = '';
                foreach($hold as $comment_item){
                    //Is URL?
                    $pos1 = strpos($comment_item,"://");
                    if($comment_item!=='' && $pos1===false ){
                        $text_comment .= " ".$comment_item;
                    }
                    if($pos1!==false){
                        $photo = $comment_item;
                    }
                
                }
                $combinedcomment = $combinedcomment.$text_comment;
                
            }
            if( $video!='') {
                
                $video = strip_tags($video );
                $hold_video = str_replace("\\n", " <br>", $video);
                $hold_video = str_replace("\\r", " ", $hold_video);
                
                $hold = explode(" ",$hold_video);
                $text_comment = '';
                foreach($hold as $comment_item){
                    $pos1 = strpos(strtolower($comment_item),"//youtu");
                    $pos2 = strpos(strtolower($comment_item),"//www.youtube");
                    if($comment_item!=='' && $pos1===false && $pos2===false){
                        $text_comment .= " ".$comment_item;
                    }
                
                }
                $combinedcomment = $text_comment;
                
                $video = YouTube($video);
                $photo = '';
                //$combinedcomment .= $video;
            }
            $precomment = "";
            if($title!='' && $combinedcomment!=''){
                $precomment = "<span class='roomposttitle'>".strip_tags($title)."</span> &nbsp;<br><br>";
            } else
            if($title!='' && $combinedcomment==''){
                $precomment = strip_tags($title);
            }
            
            $combinedcomment = htmlentities( $precomment.$combinedcomment, ENT_QUOTES );
            //$og =  GetOGTags($comment);
            //$combinedcomment .= $og;
            
            if($link!=''){
                
                $link = strip_tags($link );
                if(IsPhoto($link)){
                    $photo = $link;
                    
                } else
                if(IsVideoStreaming($link)){
                    $vars = null;
                    parse_str( parse_url( $link, PHP_URL_QUERY ), $vars );
                    $combinedcomment = $combinedcomment."<br>
                            <a href='$link' style='text-decoration:none;color:black' target='_blank'>
                                <img src='$rootserver/img/videostream.png' style='max-width:100%;height:auto' />
                            </a>
                            <br><br>".$vars['f']." <br>".$vars['t']."
                            ";
                    
                } else
                if(IsAudioStreaming($link)){
                    $vars = null;
                    parse_str( parse_url( $link, PHP_URL_QUERY ), $vars );
                    $combinedcomment = $combinedcomment."<br>
                            <a href='$link' style='text-decoration:none;color:black' target='_blank'>
                                <img src='$rootserver/img/musicpost1.png' style='max-width:100%;height:auto' />
                            </a>
                            <br><br>".$vars['f']." <br>".$vars['t']."
                            ";
                    
                } else {
                    $vars = null;
                    parse_str( parse_url( $link, PHP_URL_QUERY ), $vars );
                    $combinedcomment = $combinedcomment."<br><br>
                            <a href='$link' style='text-decoration:none;color:black'>
                                <div class='roomfilelink divbutton3'>File Link</div>
                            </a>
                            <br><br>".$vars['f']."
                            ";
                }
            };
            if($slideshow!=''){
            
                    $vars = null;
                    parse_str( parse_url( $slideshow, PHP_URL_QUERY ), $vars );
                    $firstimg = GetSlideShowFirstImg($providerid, $vars['a']);
                    $combinedcomment = $combinedcomment."<br><br>
                    <img src='$firstimg' class='slideshow roomalbum' data-providerid='$providerid' data-album='".$vars['a']."' style='cursor:pointer;width:100%;height:auto' />    
                            <br>
                            <div class='roomalbumtitle'>
                                Slideshow: ".$vars['a']."
                                <br><span class='smalltext' style='color:firebrick'>Tap Image for Slideshow </span>
                            </div>
                           ";
            };
            
            
            //Test
            $encoding = $_SESSION['responseencoding'];
            $encryptedcomment = EncryptPost($combinedcomment, "$providerid","");
            if($encoding!='SPA1.0'){
                if($video!=''){
                    $video = EncryptPost($video, "$providerid","");
                }
                if($photo!=''){
                    $photo = EncryptPost($photo, "$providerid","");
                }
            }
            //$room = tvalidator("PURIFY",$room);
            
            pdo_query("1","
                insert into statuspost
                (providerid, comment, postdate, shareid, parent, 
                 owner, likes, room, roomid, postid,
                 link, photo, video, encoding, anonymous, articleid ) values
                ($providerid, '$encryptedcomment', now(), '$shareid','$parent', 
                 $owner, 0, '$room', $roomid,'$postid',
                  '','$photo','$video','$encoding','$anonymous', $articleid )
                    ");
            
            
            FlagUnreadPost( $providerid, $shareid, $postid, $roomid, $anonymous, $mode );
            FlagMakePost(   $providerid, $shareid, $postid, $roomid );
            
            return $postid;
        }    
        return false;
}
function SharePost( $providerid, $articleid, $roomid, $room )
{
    

    $shareid = uniqid("BZG", true);
    $postid = uniqid("A", true);
    
    $result = pdo_query("1","
        select comment, photo, encoding, providerid 
        from statuspost where articleid = $articleid and providerid = 0
        ");
    
    if($row = pdo_fetch($result)){
        $decrypted = DecryptPost("$row[comment]", "$row[encoding]", "$row[providerid]", "");
        $encrypted = EncryptPost("$decrypted", $providerid, "");
        
        $decryptedphoto = DecryptPost("$row[photo]", "$row[encoding]", "$row[providerid]", "");
        $encryptedphoto = EncryptPost("$decryptedphoto", $providerid, "");
        
        
    }
    
    pdo_query("1","
        insert into statuspost

        (providerid, comment, postdate, shareid, parent, 
        owner, likes, room, roomid, postid,
        link, photo, video, encoding, anonymous, articleid ) 

        select $providerid, '$encrypted', now(), '$shareid', 'Y',
        $providerid, 0, '$room', $roomid, '$postid',
        '', '$encryptedphoto', '', '$_SESSION[responseencoding]', anonymous, articleid 
        from statuspost where articleid = $articleid and providerid = 0
        ");
    
    
    FlagUnreadPost( $providerid, $shareid, $postid, $roomid, '', 'P' );
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
        $end =  "&part=snippet&key=AIzaSyAMiz7TJ-8WMPBr7QrlVy6RD30eL7bgunY";
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
            alias='$alias' and filetype in ('jpg','jpeg','png','gif') and status='Y'
            " 
            );
        if($row = pdo_fetch($result)){
            return true;
        }
    }
    
    $pos3 = strpos(strtolower($comment),"sharedirect.php?a=");
    if($pos1!==false && $pos3!==false  ){
        
        $exploded = explode("=",$comment);
        $exploded2 = explode("&",$exploded[1]);
        $alias = $exploded2[0];
        $comment .= "&test=$alias";
        $result = pdo_query("1",
            "
            select filetype from photolib where alias='$alias'
            and filetype in ('jpg','jpeg','png','gif') 
            " 
            );
        if($row = pdo_fetch($result)){
            return true;
        }
    }
    if(!filter_var($comment, FILTER_VALIDATE_URL)){  
        return false;
    }      
    

    try {
        $url_headers=get_headers($comment, 1);
        if(isset($url_headers['Content-Type'])){

            $type=explode("/",strtolower($url_headers['Content-Type']));
            if(
                    $type[0] == 'image' 
               ){
                return true;
            }
            return false    ;
        }    
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
    
    $albumclean = tvalidator("PURIFY",html_entity_decode($album, ENT_QUOTES));
    
    $result = pdo_query("1","
        select alias from photolib where (providerid=$providerid or album like '*%')
            and
            album='$albumclean'  order by createdate asc
            ");
    if($row = pdo_fetch($result)){
        $alias = $row['alias'];
        return "$rootserver/prod/sharedirect.php?a=$alias";
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
            select * from statusreads where providerid=$providerid and shareid='$shareid' and postid='$postid' and xaccode='L'
                ");
        if(!$row = pdo_fetch($result)){
            pdo_query("1","
                update statuspost set likes=likes+1 where shareid = '$shareid' and postid='$postid'
                    ");

            pdo_query("1","
                insert into statusreads (providerid, shareid, postid, xaccode, actiontime, roomid ) values (
                                        $providerid, '$shareid', '$postid', 'L', now(), $roomid )
                    ");
            $result = pdo_query("1","
                select providerid from statuspost where shareid='$shareid' and postid='$postid'
                ");
            if( $row = pdo_fetch($result)){
                $owner = $row['providerid'];   
            }


            FlagUnreadPost( $providerid, $shareid, $postid, $roomid, "", "L" );
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
        where shareid='$shareid' and postid='$postid' and 
        parent='Y' and 
        ( 
            providerid=$providerid or 
            owner=$providerid or
            exists 
            ( select providerid from roommoderator 
              where roomid=$roomid and providerid=$providerid 
            ) 
            or exists 
            (   select providerid from statusroom 
                where roomid=$roomid and owner=$providerid 
            ) 
            or 'Y' = '$_SESSION[superadmin]'

        )
    ");
    if($row = pdo_fetch($result)){
        $parent = $row['parent'];

        //Delete all posts for this thread if this is parent
        pdo_query("1","
            delete from statuspost
                where shareid='$shareid'  
                ");
        
    }
    
    //Delete only single post
    pdo_query("1","
        delete from statuspost
        where   (
                    (
                        providerid=$providerid or 
                        owner=$providerid or
                        exists 
                        (select providerid from roommoderator 
                         where roomid=$roomid and providerid=$providerid 
                        ) or
                        exists 
                        (   select providerid from statusroom 
                            where roomid=$roomid and owner=$providerid 
                        ) 
                    ) 
                    or 'Y' = '$_SESSION[superadmin]'
                )
                and shareid='$shareid' and postid='$postid' 
            ");
    

    //Delete any statusreads of type R of mine since I've seen it
    pdo_query("1","
        delete from statusreads
        where shareid ='$shareid' and providerid=$providerid
        and xaccode='R'
            ");

    pdo_query("1","
        delete from statusreads
        where shareid not in 
            (select shareid from statuspost where shareid='$shareid' 
            and statusreads.postid = statuspost.postid )
        and postid = '$postid'
            ");

    pdo_query("1","
        insert into statusreads (providerid, shareid, postid, xaccode, actiontime, roomid ) values (
                                $providerid, '$shareid', '$postid', 'D', now(), $roomid )
            ");

    
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
            ( $providerid, '$shareid', '$postid', 'B', now(), $roomid )
            ");
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
        pdo_query("1","
            insert into statusreads 
            (providerid, shareid, postid, xaccode, actiontime, roomid ) values
            ( $providerid, '$shareid', '$postid', 'P', now(), $roomid )
            ");

        pdo_query("1","
            update roominfo set lastactive = now() where roomid = $roomid
            ");
        
        
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
        ( $providerid, '$shareid', '$postid', 'X', now(), $roomid )
        ");
        
}
/****************************************************************
 * 
 * 
 * 
 * 
 * 
 * 
 */

function FlagUnreadPost( $providerid, $shareid, $postid, $roomid, $anonymous, $subtype )
{
    pdo_query("1","
        delete from statusreads where shareid='$shareid' and xaccode='R'
        ");
    
    //RoomNotification($providerid, $roomid, $subtype, $shareid, $postid, $anonymous );
    RoomNotificationRequest($providerid, $roomid, $subtype, $shareid, $postid, $anonymous );

    pdo_query("1"," 
        update statusroom set lastaccess = now()
        where roomid = $roomid
    ");
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
        delete from statusreads where shareid='$shareid' and xaccode='R'
        ");
    
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
    pdo_query("1","
        update statuspost set pin = 10 where postid = '$postid' and 
            (
                owner = '$providerid'
                or exists 
                 (   select providerid from roommoderator 
                     where roomid=$roomid and providerid=$providerid 
                 ) 
                or exists 
                 (   select providerid from statusroom 
                     where roomid=$roomid and owner=$providerid 
                 ) 
            )
    
        ");
        
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
        update statuspost set pin = 0 where postid = '$postid' and
            (
                owner = '$providerid'
                or exists 
                 (   select providerid from roommoderator 
                     where roomid=$roomid and providerid=$providerid 
                 ) 
                or exists 
                 (   select providerid from statusroom 
                     where roomid=$roomid and owner=$providerid 
                 ) 
            )
        ");
        
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
    $recentrooms = "";
    $chatlist = "";

    $limit = "11";
    $result = pdo_query("1","
            select distinct statusroom.roomid, roominfo.room, 
            provider.providername as ownername, statusroom.owner,
            roominfo.lastactive as actiontime,
            date_format( now(), '%Y%m%d%H%i') as now,  
            (select handle from roomhandle where roomhandle.roomid = statusroom.roomid )
            as handle,
            (select count(*) from statusreads where xaccode = 'R' 
             and statusroom.roomid = statusreads.roomid 
             and statusroom.providerid = statusreads.providerid ) as activeflag
            from statusreads
            left join statusroom on 
                statusroom.roomid = statusreads.roomid and
                statusreads.providerid = statusroom.providerid
            left join provider on provider.providerid = statusroom.owner
            left join roominfo on roominfo.roomid = statusroom.roomid
            where statusroom.providerid=$providerid
            and statusreads.xaccode = 'R'
            and datediff( now(), roominfo.lastactive)  < 7
            order by actiontime desc, roominfo.room asc limit $limit

            ");
    $i=0;
            while($row = pdo_fetch($result)){
                
                $elapsed = intval($row['now'])-intval($row['actiontime']);
                $recentflag = false;
                if($elapsed < 60*24*60){
                    $recentflag = true;
                }
                $active = "";
                if(intval($row['activeflag']) > 0){
                    $active = "<img  src='../img/warning-orange2-128.png' style='height:10px;position:relative:top:0px' /> ";
                }
                
                if($row['handle']!=''){
                    $row['ownername']=$row['handle'];
                }
                if($i == 0) {
                    $recentrooms = "<br>
                                    <div  class='gridstdborder' 
                                        style='padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:10px;
                                        display:inline-block;text-align:center;background-color:#50c8e8;margin:auto;
                                        vertical-align:top;border-radius:25px'>
                                        <div class='smalltext' style='padding-bottom:5px;color:#ffdd00'><b>Trending</b></div>
                                    ";
                }
                /* *******************
                 * 
                 * DESKTOP QUICK JUMP
                 * 
                 * *******************/
                $roomfit = $row['room'];
                if( strlen($row['room'])>25){
                    $roomfit = preg_replace('/\s+?(\S+)?$/', '', substr($row['room'], 0, 25));
                }
                if($fulllist || $recentflag ){
                    $recentrooms .= "
                    <div class='feed mainbutton mainfont tapped2 recentbutton' data-roomid='".$row['roomid']."' 
                        data-room='".$row['room']."' data-selectedroom='Y' 
                        style='display:inline-block;cursor:pointer;border:0px solid lightgray;
                        background-color:transparent;color:black;overflow:hidden;'>
                            <div class='smalltext'
                            style='display:block;color:white;white-space:nowrap;'>
                            <b>$active ".$roomfit."</b>
                            </div>
                            <span class=smalltext style='color:white'>
                                ".$row['ownername']."<br>
                            </span>
                    </div>
                    ";
                }
                $i++;
            }
            if( $i == 1) {
                $recentrooms = "";
            }
            else
            if( $i > 0) {
                if( $fulllist ){
                    $recentrooms .= "
                    <div class='mainbutton roomselect tapped2 recentbutton'  
                        style='display:inline-block;cursor:pointer;border:0px solid lightgray;
                        background-color:transparent;color:black;overflow:hidden;
                        ;max-width:90%;text-align:left;margin-bottom:5px;vertical-align:top' >
                            <div class='smalltext'
                                style='display:block;color:gold;'>
                                More...
                            </div>
                    </div>
                    ";
                }
                $recentrooms .= "</div>";
            }
    return $recentrooms;
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
    $statuswidth = '510px';
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
        $padding = "20px";
    }
    if( $_SESSION['sizing']=='1600'){
        $mainwidth = '840px';
        $statuswidth = '810px';
        $statuswidth2 = '760px';
        $padding = "20px";
    }
    if( $_SESSION['sizing']=='1400'){
        $mainwidth = '840px';
        $statuswidth = '810px';
        $statuswidth2 = '760px';
        $padding = "20px";
    }
    if( $_SESSION['sizing']=='1200'){
        $mainwidth = '540px';
        $statuswidth = '500px';
        $statuswidth2 = '500px';
        
    }
    if( $_SESSION['sizing']=='1000'){
        $mainwidth = '540px';
        $statuswidth = '500px';
        $statuswidth2 = '500px';
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

function RoomOwner($providerid, $roomid, $mainwidth, $page)
{
    global $rootserver;
    global $installfolder;
    global $appname;
    
    $room = '';
    $handle = '';
    $roomdesc = '';
    $photo = '';
    $slideshow = "";
    $photourl = "";
    
    
    if($roomid == 'All'){
        return "";
    }
    $origproviderid = $providerid;
    if($providerid==''){
        $result = pdo_query("1","
            select owner from statusroom where roomid=$roomid and owner=providerid "
            );
        if($row = pdo_fetch($result)){
            $providerid = "$row[owner]";
        }
        
    }
    pdo_query("1","
        delete from statusreads where xaccode = 'R' and providerid = $providerid and roomid = $roomid
    ");

    $result = pdo_query("1","
            select distinct statusroom.roomid, roominfo.room, 
                provider.providername as ownername, 
                statusroom.owner,
                roominfo.photourl,
                roominfo.roomdesc,
                roominfo.adminroom,
                (select handle from roomhandle where roomhandle.roomid = statusroom.roomid )
                as handle
            
            from statusroom
            left join provider on provider.providerid = statusroom.owner
            left join roominfo on statusroom.roomid = roominfo.roomid
            where 
            (
                (statusroom.owner=$providerid or statusroom.providerid=$providerid)
                or 
                (
                statusroom.roomid in (select roomid from publicrooms)
                )
            )
            and statusroom.roomid=$roomid

            ");

    //<img src='../img/door-128.png' style='position:relative;top:5px;height:20px' />

    if($row = pdo_fetch($result)){
        $ownername = "Moderated by ".rtrim($row['ownername']);
        if($row['adminroom']=='Y' ){
            $ownername = "Moderated by ".$appname ;
        }
        $room = $row['room'];
        $handle = $row['handle'];
        $photourl = $row['photourl'];
        $roomdesc = $row['roomdesc'];
        $photo = '';
        
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
        
        $ownerinfo['room'] = $room;
        $ownerinfo['ownername'] = $ownername;
        $ownerinfo['photo'] = $photo;
        $ownerinfo['roomdesc'] = $roomdesc;
        $ownerinfo['photourl'] = $photourl;
        $ownerinfo['handle'] = $handle;
        
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

function DisplayRoomTip($providerid, $roomid, $handle )
{
    
    $owner = false;
    $roomanonymous = '';
    $adminroom = '';
    $roomtip = "";
    if( intval($roomid) > 0 ){
        $result = pdo_query("1","
                  select owner from statusroom 
                  where roomid=$roomid  and owner=$providerid ");
        if($row = pdo_fetch($result)){
            $owner = true;
        }

        $result = pdo_query("1","
                select anonymousflag, adminroom, (select handle 
                from roomhandle where roomhandle.roomid = roominfo.roomid ) as handle
                from roominfo where roomid=$roomid ");
        if($row = pdo_fetch($result)){
            $roomanonymous = $row['anonymousflag'];
            $roomhandle = $row['handle'];
            $adminroom = $row['adminroom'];
        }
    }

    $add = "";
    if(($adminroom!='Y' && $roomid !='All') || $providerid==690001027){
        $add =  "       
                        <input class='makecommenttop mainfont' data-shareid='' readonly=readonly placeholder='New Topic - Comment, links, photo, video' style='cursor:pointer;height:30px;background-color:white;width:310px;min-width:50%;max-width:90%;padding:5px' />
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

function MemberCheck($providerid, $roomid)
{
    if($providerid==''){
        $providerid ='0';
    }
    $room = array();
    $room['roomid'] = "All";
    $room['roomforsql'] = "";
    $room['roomhtml'] = "";
    $room['owner'] = "";
    $room['ownername'] = "";
    $room['handle'] = "";
    $room['anonymous'] = "";
    $room['member'] = "";
    $room['private'] = "";
    $room['adminonly'] = "";
    $room['roomfiles'] = "";
    $room['adminroom'] = "";
    $room['unread'] = "";
    $room['showmembers']="";
    $room['moderator']="";
    
    if(intval($roomid) > 0){
        //Find Room Owner
        $result = pdo_query("1","
            select statusroom.roomid, roominfo.room, 
            statusroom.owner, provider.providername as ownername,
            (select providerid from roommoderator where roommoderator.roomid = statusroom.roomid
             and roommoderator.providerid = $providerid) as moderator,
            (select handle from roomhandle where roomhandle.roomid = statusroom.roomid) as handle,
            roominfo.private, roominfo.anonymousflag, roominfo.adminonly, roominfo.adminroom, roominfo.showmembers,
            (select 'Y' from statusroom s2 where s2.roomid = statusroom.roomid and s2.providerid = $providerid limit 1 ) as member,
            (select 
                DATE_FORMAT(date_add(createdate,INTERVAL $_SESSION[timezoneoffset]*60 MINUTE), '%b %d %a %h:%i %p')  
                from roomfiles 
                where roomfiles.roomid = statusroom.roomid 
                and timestampdiff( DAY, createdate, now() ) < 30
                order by createdate desc limit 1 
            ) as roomfiles,
            ( select 'Y' from statusreads where statusreads.providerid = $providerid
                and xaccode = 'R' and datediff( now(), actiontime) < 2 and 
                statusreads.roomid != $roomid limit 1 ) as unread
            from statusroom 
            left join roominfo on statusroom.roomid = roominfo.roomid
            left join provider on statusroom.owner = provider.providerid
            where statusroom.roomid=$roomid  and statusroom.owner = statusroom.providerid
                ");
        if($row = pdo_fetch($result)){
            $room['roomid'] = $row['roomid'];
            $room['roomforsql'] = tvalidator("PURIFY",$row['room']);
            $room['roomhtml'] = htmlentities($row['room'],ENT_QUOTES);
            $room['owner'] = $row['owner'];
            $room['moderator'] = $row['moderator'];
            $room['ownername'] = $row['ownername'];
            $room['handle'] = $row['handle'];
            $room['anonymous'] = $row['anonymousflag'];
            $room['member'] = $row['member'];
            $room['private'] = $row['private'];
            $room['adminonly'] = $row['adminonly'];
            $room['roomfiles'] = $row['roomfiles'];
            $room['adminroom'] = $row['adminroom'];
            $room['unread'] = $row['unread'];
            $room['showmembers'] = $row['showmembers'];
        }
    }
    return (object) $room;
    
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
        $result = pdo_query("1","select count(*) as count from statusroom where owner = $_SESSION[pid]  ");
        $row = pdo_fetch($result);
        if( intval($row['count']) == 0){
            $_SESSION['roomuser'] = 'N';
        } else 
        if( intval($row['count']) == 1){
            $_SESSION['roomuser'] = '1';
            $result = pdo_query("1","select count(*) as count from statuspost where owner = $_SESSION[pid]  ");
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
        $result = pdo_query("1","select count(*) as count from statusroom where providerid = $_SESSION[pid]  ");
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
            $likes = " +".$likes;
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
                             class='icon20'
                             src='../img/heart-gray-128.png' 
                             style='top:0px' />
                             $likes</div>
                       ";
        }
        if($likes != ''){
            $likebutton = "
                         <div class='feedreply divbuttonlike divbuttonlike1 divbuttonlike_unsel roomcontrols tapped smalltext2 gridstdborder'  style='$float;background-color:white;color:black;position:relative;top:0px'
                            data-shareid='$shareid' data-postid='$postid'    data-reference='$scrollreference'
                            data-selectedroomid='$selectedroomid' data-roomid='$roomid' data-mode='L' data-parent='$parent'>
                             <img 
                                class='icon15'
                                src='../img/heart-orange-128.png' 
                                style='top:3px;' />
                             $likes</div>
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

function DeleteButton( $parent, $owner, $owner2, $providerid, $shareid, $postid, $roomid, $selectedroomid, $float, $scrollreference )
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
        if(  $providerid != $owner && $providerid !=$owner2 && $superadmin!='Y' ){
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
            class='icon20 $classes roomcontrols tapped' 
            src='../img/delete-gray-128.png'  style='$margin;$float;opacity:0.7;'
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
                    All Rooms
                </div>
                <br><br><br>
                <table class='gridnoborder smalltext' style='margin:auto;text-align:center;color:white'>
                <tr>
                    <td>
                    <img src='../img/tools-128.png' style='height:120px;cursor:pointer' class='friends tapped'>
                    <br>Setup Rooms
                    <br>
                    </td>

                    <td>
                    <img src='../img/announce-128.png' style='height:120px;cursor:pointer' class='roomdiscover tapped'>
                    <br>Discover Rooms
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

function ShareRoom (  $caller, $providerid, $roomid, $style, $showroomtip, $callerflag )
{
    global $rootserver;
    global $installfolder;
    global $appname;
    
    $owned = "";
    $roomtip = "";
    
    if( intval($roomid) == 0) {
        return "";
    }

    $icon1 = "$rootserver/img/facebook-blue-128.png";
    $icon2 = "$rootserver/img/link-square-blue-128.png";
    $icon3 = "$rootserver/img/share-fb-square-blue-128.png";
    $icon4 = "$rootserver/img/public-blue-128.png";
    $icon5 = "$rootserver/img/person-square-blue-128.png";
    $icon6 = "$rootserver/img/mail-square-blue-128.png";

    $uniqid2 = substr(uniqid(),4,8);
    $uniqid = uniqid("$uniqid2");

    $result = pdo_query("1","
        select roomhandle.handle, 
        roominfo.room, statusroom.owner, roominfo.private
        from statusroom
        left join roomhandle on roomhandle.roomid = statusroom.roomid
        left join roominfo on roominfo.roomid = statusroom.roomid
        where statusroom.roomid=$roomid and statusroom.owner=statusroom.providerid
            ");
    if($row = pdo_fetch($result)){
        
        $handle = $row['handle'];
        $private = $row['private'];
        if($row['owner']=="$providerid"){
            $owned = 'Y';
        }
        $room = str_replace('&amp;','%26',rawurlencode($row['room'])); 
    }  else {
        
        $handle = "";
        $owned = "X";
    }
    if($handle == ''){
        $private = 'Y';
    }
    if(intval($roomid) <= 1){
        return "";
    }
    
    $shareopentitle = "Join $row[room] on $appname";
    //$shareopentitle = str_replace(" ","%20",$shareopentitle);
    //$sharelink = "$rootserver/prod/roominvite.php?i=$roomid&k=$uniqid";
    
    //Public Link
    $handleshort = substr($row['handle'],1);
    $sharelink = "$rootserver/prod/roominvite.php?r=$handleshort";
    if($private == 'Y'){
        $sharelink = "$rootserver/prod/roominvite.php?i=$roomid&k=$uniqid";
    }
    $urlencoded = urlencode($sharelink);
    $urlencoded .= "&text=".htmlentities(stripslashes($shareopentitle), ENT_QUOTES);


    if($_SESSION['mobilesize']=='Y') {
        
        $fbshare = "braxme://sharefb?u=$urlencoded";
        //$fbshare_room = "braxme://sharefb?u=$urlencoded_room";
    } else {
        
        $fbshare = "http://www.facebook.com/sharer.php?u=$urlencoded";
        //$fbshare_room = "http://www.facebook.com/sharer.php?u=$urlencoded_room";
    }
    
    if($showroomtip == '1')
    {
        $roomtip = "
                        <center>
                        <div style='padding:20px'>
                                <div class='tipbubble pagetitle3 tapped gridstdborder'  data-roomid='$roomid' style='color:black;background-color:gold;cursor:pointer'>
                                    Promote your room by tapping on INVITE button in a room and sharing a Group Invite Link
                                </div>
                        </div>
                        <br><br>
                        </center>
            ";
        
    }
    
    if( $private == 'Y' && $owned=='Y' ){
            return "
             <div class='groupinvitecreate roombutton' style='vertical-align:top;display:inline-block;;color:black' data-roomid='$roomid'>
                 <img  class='icon35' src='$icon2' 
                     style='display:inline;cursor:pointer;position:relative;top:0px;margin:0;padding:0;text-align:center' />
                                     <br>
                                     <span class=smalltext>
                                     Group
                                     <br>
                                     Invite
                                     <br>Link<br>
                                     </span>
             </div>
             $style
             <div class='friendinvite tooltip tapped roombutton' 
                 data-caller='room'
                 style='vertical-align:top;display:inline-block;background-color:transparent;white-space:nowrap;color:black' 
                 id='friendlist' title='Invite via Email or Text' data-roomid='$roomid'>
                 <img class='icon35' src='$icon5' 
                     style='display:inline;cursor:pointer;position:relative;top:0px;margin:0;padding:0;text-align:center;' />
                                     <br>
                                     <span class=smalltext>
                                     Individual
                                     <br>Room<br>
                                     Invite<br>
                                     </span>
             </div>

            $style
            <br><br>
            $roomtip
            <br><br>
            <span class='smalltext groupinvitelinkgroup'  style='display:none;color:black'><b>Group Invite URL to Share (48 Hour Lifespan)</b></span><br>
                <input class=groupinvitelink type='text' readonly=readonly value='$sharelink' style='display:none;background-color:white;font-size:12px;height:15px;max-width:80%;width:400px' />
                    <img class='groupinvitelinkgroup groupinvitegotolink' src='../img/arrow-stem-circle-right-128.png' style='display:none;position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />
                    
            </span>

             ";
        
    }
    if( $private === 'Y' && $owned!=='Y' ){
            return "
             <div class='roombutton' style='vertical-align:top;display:inline-block;;opacity:0.2;color:black' data-roomid='$roomid'>
                 <img  class='icon35 forowner'    src='$icon2' 
                     style='display:inline;cursor:pointer;position:relative;top:0px;margin:0;padding:0;text-align:center' />
                                     <br>
                                     <span class=smalltext>
                                     Group
                                     <br>
                                     Invite
                                     <br>Link<br>
                                     </span>
             </div>
             $style
             <div class='roombutton forowner tooltip tapped' 
                 data-caller='room'
                 style='vertical-align:top;display:inline-block;background-color:transparent;white-space:nowrap;opacity:0.2;color:black' 
                 id='friendlist' title='Invite via Email or Text' data-roomid='$roomid'>
                 <img class='icon35' src='$icon5' 
                     style='display:inline;cursor:pointer;position:relative;top:0px;margin:0;padding:0;text-align:center;' />
                                     <br>
                                     <span class=smalltext  style='color:black' >
                                     Individual
                                     <br>Room<br>
                                     Invite<br>
                                     </span>
             </div>
             $style
                 
            <br><br>
             ";
        
    }
    $shareopentitle = "Join $row[room] on $appname";
    //$shareopentitle = str_replace(" ","%20",$shareopentitle);
    $urlencoded = urlencode($sharelink);
    $urlencoded .= "&text=".htmlentities(stripslashes($shareopentitle), ENT_QUOTES);
    
    $roomlink = "$rootserver/blog/$handleshort";
    $urlencoded_room = urlencode("$roomlink&k=$uniqid");
    $urlencoded_room .= "&text=".htmlentities(stripslashes($row['room']), ENT_QUOTES);
    

    if($_SESSION['mobilesize']=='Y'){
        $fbshare = "braxme://sharefb?u=$urlencoded";
        $fbshare_room = "braxme://sharefb?u=$urlencoded_room";
    } else {
        $fbshare = "http://www.facebook.com/sharer.php?u=$urlencoded";
        $fbshare_room = "http://www.facebook.com/sharer.php?u=$urlencoded_room";
    }
        $returntext = "
        <div class='groupinvitecreate' style='vertical-align:top;display:inline-block;height:90px;width:70px;color:black' data-roomid='$roomid'>
            <img class='icon35' src='$icon2' style='display:inline;cursor:pointer;position:relative;top:0px;width:auto;margin:0;padding:0;text-align:center' />
                                <br>
                                <span class=smalltext>
                                Group
                                <br>
                                Invite
                                <br>Link<br>
                                </span>
        </div>
        $style
        <div class='friendinvite tooltip tapped roombutton' 
            data-caller='room'
            style='vertical-align:top;display:inline-block;background-color:transparent;white-space:nowrap;color:black' 
            id='friendlist' title='Invite via Email or Text' data-roomid='$roomid'>
            <img class='icon35' src='$icon5' 
                style='display:inline;cursor:pointer;position:relative;top:0px;margin:0;padding:0;text-align:center;' />
                                <br>
                                <span class=smalltext>
                                Invidivual
                                <br>Room<br>
                                Invite<br>
                                </span>
        </div>
        
        $style
            ";

        
        $returntext .= "
            <br>
            <span class='smalltext groupinvitelinkgroup'  style='display:none;color:black'><b>Group Invite URL to Share</b></span><br>
                <input class=groupinvitelink type='text' readonly=readonly value='$sharelink' style='display:none;background-color:white;font-size:12px;height:15px;max-width:80%;width:300px' />
                <img class='groupinvitelinkgroup groupinvitegotolink' src='../img/arrow-stem-circle-right-128.png' style='display:none;position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />
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

        
function FormatComment( $postid, $providerid, $roomid, $encoding, $incomment, $inphoto, $invideo, $inlink, $style, $parent, $mainwidth, $statuswidth2, $videotitle, $articleid )
{
    global $rootserver;
    global $installfolder;
    //$mainwidth = "540px";
    //$statuswidth2 = "460px";
    $cleanPostid = str_replace(".",'',$postid );
    
    
    $owner = false;
    
    /*
    if( $_SESSION['superadmin']=='Y' && 
        $providerid == $_SESSION['pid'] && 
        $inlink =='' && 
        $inphoto =='' && 
        $invideo == '' && 
        $incomment!=''){
        $owner = true;
    }
     * 
     */

    $decryptedpost = DecryptPost( $incomment, $encoding, $providerid, "");
    

    $comment = '';
    if($inphoto!='') {
        
        if( $encoding !='SPA1.0' && $encoding!='BASE64'){
            
            $photo = DecryptPost($inphoto,$encoding,"$providerid","");
            
        } else {
            
            $photo = $inphoto;
        }

        if($parent == 'Y'){
            
            $maxwidth = $mainwidth;
            /*
            if($maxwidth == '100%'){
            
                $innerwidth = intval($_SESSION['innerwidth'])-2;
                $maxwidth = $innerwidth."px";
            }
             * 
             */
            $maxwidth = "100%";
            
        } else {
            
            $maxwidth = $statuswidth2;
        }
        if(IsEmoji($photo)){
            
            $comment = "
                <img class='emoji_img' src='$photo' style='margin:5px' />";
        } else {
            
            $photolink = WrapPhoto($photo);
            $comment = "
                <img class='roomphoto expandphoto' src='$photolink' 
                style='max-width:$maxwidth;height:auto;background-color:white' /><br>";
     
        }

    }
    if( $invideo!=''){
        
        if( $encoding!='SPA1.0' && $encoding!='BASE64'){
            $video = DecryptPost($invideo, $encoding, "$providerid","");
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
        $class='';
        $tmp = html_entity_decode( $decryptedpost, ENT_QUOTES);
        //$tmp = $decryptedpost;
        if($tmp!=''){
            $tmp = WrapLinks($tmp);
            if($parent == 'Y'){
                $comment .= "<div style='color:black;padding:0px'>
                             <div style='color:black;word-wrap:break-word;padding:20px;$style'>
                            ";
            }
            //$tmp = wordwrap($tmp,30,"<br />", true);

            $testshort = strip_tags($tmp,"<span><br>");
            //$short = substr(strip_tags($tmp,"<span><br>"),0,300);
            //$short = substr(strip_tags($tmp),0,300);
            $short =$tmp;

            $more = "";
            if(strlen($tmp)>1){
                $more = "<span class='showmore tapped' >
                            ... <img class='icon15' src='../img/arrow-circle-right-gray-128.png' style='top:3px' /> More
                         </span>";
            }
            //$more = "";
            if( strlen($testshort)<= 300 ){
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
                $tmp2 = str_replace("<br>","&#10;",$tmp);
                $tmp2 = strip_tags($tmp2,"<span><a>");
                $commentlong = "
                            <span class='commentlong' style='display:none'>
                                <textarea id='$cleanPostid' class='mainfont' style='width:100%;height:300px'>$tmp2</textarea>
                                <div class='feededitpost tapped' style='cursor:pointer' data-roomid='$roomid' data-postid='$postid' data-postidclean='$cleanPostid'  >
                                    <img class='icon15' src='../img/arrow-circle-right-gray-128.png' style='top:3px' /> Save Changes
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
                                style='color:steelblue;cursor:pointer'>
                                View Full Article
                            </div>
                            <br>
                            ";
                if(intval($providerid) == 0){
                    $comment .=
                            "
                            <div class='roomsharepost mainfont' data-articleid='$articleid' 
                                data-roomid='$roomid' data-mode=''
                                style='color:steelblue;cursor:pointer'>
                                Share
                            </div>
                            <br>
                            ";
                }
            }
            
            if($parent == 'Y'){
                $comment .= "</div></div>";
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

function RoomPosterInfo( $roomid, $providerid, $avatarurl, $adminroom, $private, $anonymous, $roomanonymous, $name, $name2, $alias, $handle )
{
        global $rootserver;
        
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
        if( ($adminroom == 'Y' && intval($providerid)==690001027) ||
            ($roomid == 1 && intval($providerid)==690001027) 
            ){
            $poster['name'] ='Brax.Me Admin';
            $poster['avatar']="$rootserver/img/admin.png";
        }
        if($anonymous =='Y' || $roomanonymous=='Y'){
            $poster['name'] ='Anonymous';
            //$poster['avatar']="$rootserver/img/faceless.png";
            //if($poster['avatar'] == "$rootserver/img/faceless.png"){
                $poster['avatar'] = "$rootserver/img/egg-blue.png";
            $poster['nochat']='Y';
            //}
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

function WrapLinks($text)
{
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
            $link->href = 'https://bytz.io/prod/wrap.php?u=' . $link->href;
        }
    }
    $text = $html->save();
    if( $malwareflag == true){
        $text = strip_tags($text,"<br><b><p>");
        $text .= "<br><br><b style='color:firebrick'>Google has flagged the link(s) above as possible malware. If you wish to access the link, we recommend that you use a TOR Browser.</b>";
    }
    
    
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
    //return $text;
    $html = new simple_html_dom();

    // load the entire string containing everything user entered here

    $return = $html->load($text);
    $links = $html->find('a');

    foreach ($links as $link){
        if( isset($link->href) && 
            substr( strtolower($link->href),0,6 )!="https:"){
            $link->href = "$rootserver/$installfolder/wrapphoto.php?u=$text" . $link->href;
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

    $apikey = "AIzaSyDd_Y3cZI4MRmEFA4vnqzlfoKEM8LTBWjs";
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
        
        return preg_replace('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
        
    }
?>
