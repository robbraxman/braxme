<?php
   function IsURL($message)
    {
        if(filter_var($message, FILTER_VALIDATE_URL) === FALSE)
        {
            return false;
        }else{
            return true;
        }        
    }
    function IsSlideshow( $comment )
    {
        global $rootserver;
        

        $pos2 = strpos(strtolower($comment),"https://");
        $pos1 = strpos(strtolower($comment),"slideshow.png");
        //$pos3 = strpos($comment,"$rootserver/img");

        if($pos1===false )//|| $pos2===false )//|| $pos3===false )
        {
            return false;
        }
        return true;

    }
    function IsPhoto( $comment )
    {
        global $rootserver;
        
        //Is this internal share doc.php -- get Alias
        $pos1 = strpos(strtolower($comment),"$rootserver");
        if($pos1 === false){
            $pos1 = strpos(strtolower($comment),"https://bytz.io");
        }
        $pos2 = strpos(strtolower($comment),"doc.php?p=");
        if($pos1!==false && $pos2!==false  )
        {
            $exploded = explode("=",$comment);
            $exploded2 = explode("&",$exploded[1]);
            $alias = $exploded2[0];
            $comment .= "&test=$alias";
            $result = pdo_query("1",
                "
                select filetype from filelib where 
                alias=? and filetype in ('jpg','jpeg','tiff','tif','png','gif') and status='Y'
                " 
                ,array($alias));
            if($row = pdo_fetch($result))
            {
                return true;
            }
        }
        
        //Is this internal share doc.php -- get Alias
        $pos1 = strpos(strtolower($comment),"$rootserver");
        if($pos1 === false){
            $pos1 = strpos(strtolower($comment),"https://bytz.io");
        }
        $pos2 = strpos(strtolower($comment),"sharedirect.php?a=");
        if($pos1!==false && $pos2!==false  )
        {
            return true;
        }
        if( !IsURL($comment)){
            return false;
        }
        
        try {
            $url_headers=get_headers($comment, 1);
            if(isset($url_headers['Content-Type'])){

                $type=explode("/",strtolower($url_headers['Content-Type']));
                if(
                        $type[0] == 'image' 
                   )
                {
                    return true;
                }
                return false;
            }    
        } catch (Exception $e) {
            //return false;
        }
        return false;

    }
    function IsEmoji( $comment )
    {
        global $rootserver;
        //Is this internal share doc.php -- get Alias
        $pos1 = strpos(strtolower($comment),"$rootserver/img/emoji-");
        if($pos1!==false  )
        {
            return true;
        }
    }
    function IsVideo( $comment )
    {
        //Detect Video
        $hold = explode( "?",$comment);

        $pos1 = strpos(strtolower($hold[0] ),"//www.youtube.com/watch");
        $pos2 = strpos(strtolower($comment ),"//www.youtube.com/embed/");
        $pos3 = strpos(strtolower($comment),"//youtu.be/");
        $pos4 = strpos(strtolower($comment),"vimeo.com/");

        if($pos1===false && $pos2===false && $pos3 ===false && $pos4 ===false)
        {
            return false;
        }
        return true;

    }
    function YouTube($url )
    {

        //format 1 with query string - specically search for 'v'
        $pos1 = strpos(strtolower($url ),"//www.youtube.com/watch");
        if($pos1 !== false)
        {
            $vars = array();
            //Extract Query String and parse into Vars
            parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
            //$youtube= '<iframe allowtransparency="true" scrolling="no" width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed/'.$my_array_of_vars['v'].'" frameborder="0"'.($fullscreen?' allowfullscreen':NULL).'></iframe>';
            $v = str_replace("\\n", " ", $vars['v']);

            $hold = explode(" ",$v);
            $youtube = "//www.youtube.com/embed/".$hold[0];
            return $youtube;
        }
        //format 2
        $pos1 = strpos(strtolower($url ),"//youtu.be/");
        if($pos1 !== false)
        {
            $vars = explode("/",$url);

            $youtube = "//www.youtube.com/embed/".$vars[3];
            return $youtube;
        }

        //format 5
        $pos1 = strpos(strtolower($url ),"//player.vimeo.com/video/");
        if($pos1 !== false)
        {
            $vars = explode("/",$url);

            $youtube = "//player.vimeo.com/video/".$vars[4];
            return $youtube;
        }


        //format 4
        $pos1 = strpos(strtolower($url ),"vimeo.com/");
        if($pos1 !== false)
        {
            $vars = explode("/",$url);

            $youtube = "//player.vimeo.com/video/".$vars[3];
            return $youtube;
        }


    }
    function IsInternalLink( $url )
    {
        global $rootserver;
        
        //Detect Internal Link
        $hold = explode( "?",$url);

        $pos1 = strpos(strtolower($hold[0] ),"//brax.me");
        $pos2 = strpos(strtolower($hold[0] ),"//bytz.io");
        
        if($pos1===false && $pos2===false )
        {
            return false;
        }
        return true;

    }
    function ExtractInternalLink( $url )
    {
        //Detect Internal Link
        $hold = explode( "?",$url);

        $pos1 = strpos(strtolower($hold[0] ),"//brax.me");
        $pos2 = strpos(strtolower($hold[0] ),"//bytz.io");

        $vars = array();
        //Extract Query String and parse into Vars
        parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
        
        
        if($pos1===false && $pos2===false )
        {
            return htmlentities($url);
        }
        return @$vars['f'];

    }
    function ProcessLinks($text)
    {
        global $installfolder;
        //return $text;
        $html = new simple_html_dom();

        // load the entire string containing everything user entered here

        $return = $html->load($text);
        $links = $html->find('a');

        foreach ($links as $link) {
            if(SafeUrl($link->href)== false){
                $malwareflag = true;
            }
            if(isset($link->href) && substr( strtolower($link->href),0,6 )!="https:"){
                $link->href = "https://bytz.io/$installfolder/wrap.php?u=" . $link->href;
            }
            if($_SESSION['mobiletype']=='A' || $_SESSION['mobiletype']=='I'){
                $link->target = "_parent";
            }

        }
        $newtext = $html->save();

        return $newtext;
    }    
    
    
    function FormatImage($img)
    {
        global $providerid;
        global $rootserver;
        
        $slideshowurl = "";
        $imgurl = "";
        $slideshow = "";
        if(IsSlideshow( $img )){
        
            $slideshow = $img;
            $img = "";
        }
        if( $slideshow!=''){
        
            $vars = null;
            parse_str( parse_url( $slideshow, PHP_URL_QUERY ), $vars );
            $slideshowurl = "
            <img src='$rootserver/img/slideshow.png' title='Slideshow' class='slideshow feedslideshowchat' data-providerid='$providerid' data-album='".$vars['a']."' style='cursor:pointer;' alt='Loading image...' />    
                       <br> ".$vars['a']."
                   ";
            //$slideshowurl = htmlentities($slideshowurl);
        };

        if( $img!=''){
        
            if(IsEmoji($img)){
            
                $imgurl = "<img class='emoji_img' src='$img' alt='Loading image...'/>";
            } else {
                
                $imgurl = "<br><img class='feedphotochat' src='$img' alt='Loading image...'/>";
            }
        }
        return "$slideshowurl$imgurl";
        
    }
    
    function FormatMessage($message)
    {
        global $rootserver;
        
        $video = "";
        $videourl = "";
    
        if(IsPhoto($message))
        {
            $img = $message;
            //$message = '';
        }
        if(IsVideo($message))
        {
            $video = $message;
            $message = '';
        }


        $urllink = '';
        if(IsURL($message))
        {
            $url = $message;
            $urllink = htmlentities($message);
            $message = '';
        }

        if( $url!='')
        {
            $url = strtolower(strip_tags($url,""));
            $url2 = wordwrap($url, 70, "<br>", true);
            $linkmessage = ExtractInternalLink($url);

            if(IsInternalLink($url)){
                $url = "
                     <br>
                     <a href='$url' target='_blank' 
                     style='text-decoration:none;color:black'> 
                        <div class='chatfilelink divbuttonchat' >
                           File
                        </div>
                     </a>
                     <br><br>$linkmessage";
            } else {
                $url = "
                     <br>
                     <a href='$url' target='_blank' 
                     style='text-decoration:none;color:seagreen'> 
                        $linkmessage
                     </a>
                     <br><br>";

            }
        }
        $imgurl = "";
        $slideshow = "";
        $slideshowurl = "";
        if(IsSlideshow( $img ))
        {
            $slideshow = $img;
            $img = "";
        }
        if( $slideshow!='')
        {
            $vars = null;
            parse_str( parse_url( $slideshow, PHP_URL_QUERY ), $vars );
            $slideshowurl = "
            <img src='$rootserver/img/slideshow.png' title='Slideshow' class='slideshow feedphotochat' data-providerid='$providerid' data-album='".$vars['a']."' style='cursor:pointer;' alt='Loading image...' />    
                       <br> ".$vars['a']."
                   ";
            //$slideshowurl = htmlentities($slideshowurl); 
        };

        if( $img!='')
        {
            if(IsEmoji($img))
            {
                $imgurl = "<img class='emoji_img' src='$img' alt='Loading image...'/>";
            }
            else
            {
                $imgurl = "<br><img class='feedphotochat' src='$img' alt='Loading image...'/>";
            }
        }
        if( $video!='')
        {
            $yturl = YouTube($video);
            $videourl = " 
                    <br>
                    <div class='videoview divbuttonchat' data-url='$yturl' style='cursor:pointer;color:gray'> 
                        Video
                    </div>
                    <br><br>
                    ";

        }

        $message = htmlentities( $message);
        $message = str_replace("\\n","<br> ",$message );
        $message = autolink($message, 50, ' class="chatlink" target="_blank" ', false);



        $message = stripslashes($message);
        $messageshort = substr(strip_tags($message),0,80);
        if( $url )
        {
            $messageshort .= " (Link) ";
        }
        if( $imgurl )
        {
            $messageshort .= " (Image) ";
        }
        if( $videourl )
        {
            $messageshort .= " (Video) ";
        }

        $message .= $url;
        $message .= $imgurl;
        $message .= $slideshowurl;
        $message .= $videourl;        
        
        return $message;
    }

    
    function FormatMessageNew($message)
    {
        if(IsPhoto($message)){
            return FormatImage($message);
        } 
        
        //$message = htmlentities($message);
        $message = htmlentities($message, ENT_COMPAT);
        $message = str_replace("\\n","  <br> ",$message );
        $message = autolink($message, 50, ' class="chatlink" target="_blank" ', false);

        
        return $message;
    }
    function DeleteChatMessage($msgid, $chatid)
    {
    //for delete
        $result = pdo_query("1",
            "
            update chatmessage set status='N' where msgid=? and 
            chatid=? 
            ",array($msgid,$chatid));
        $result = pdo_query("1",
            "
            delete from chatmessage where msgid=? and 
            chatid=? 
            ",array($msgid,$chatid));
        
        $result = pdo_query("1",
            "
            update chatmembers set lastread=now()-1000  where  chatid=? and status='Y'
            and timestampdiff(SECOND, lastread, now() ) < 120
            ",array($chatid));
        $result = pdo_query("1",
            "
            update chatmaster set lastmessage=now(),
            chatcount = (select count(*) from chatmessage where chatmessage.chatid = chatmaster.chatid and chatmessage.status = 'Y'),
            chatmembers = (select count(*) from chatmembers where chatmembers.chatid = chatmaster.chatid )
<<<<<<< HEAD
            where  chatid=? and chatmaster.status='Y'
            ",array($chatid));
=======
            where  chatid=$chatid and chatmaster.status='Y'
            ");
>>>>>>> d09b95b601296e47dbf1975a21403d408ce23ef8
    
    }
    function FlagChatMessage($action, $msgid, $chatid)
    {
        $result = pdo_query("1",
            "
            update chatmessage set flag='$action' where msgid=$msgid and chatid=$chatid
            ");
        
        pdo_query("1","
            update chatmembers set lastread=now()-1000  where  chatid=? and status='Y'
            and timestampdiff(SECOND, lastread, now() ) < 120
        ",array($chatid));
        pdo_query("1","
            update chatmaster set lastmessage=now() where 
            chatid=? and status='Y'
        ",array($chatid));
        
    }

function CreateChatMessage( $providerid, $chatid, $passkey, $message, $messageshort, $streaming, $notify, $radiostation)
{
    $encode = EncryptChat ($message,"$chatid","$passkey" );
    $encodeshort = EncryptChat ($messageshort,"$chatid","" );
    if(isset($_SESSION['loginid'])){
        $loginid = $_SESSION['loginid'];
    } else {
        $loginid = 'admin';
    }
        
        $result = pdo_query("1",
        "
            insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status, loginid)
            values
            ( ?, ?, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y',? );
        ",array($chatid,$providerid,$loginid));
        $subtype = '';
        
        if($notify){
            
            if($radiostation == 'Y'){
                $subtype = 'LV';
            }
            ChatNotificationRequest($providerid, $chatid, $encodeshort, $_SESSION['responseencoding'],$subtype);
        } 
        /*
         * This is the original and it is wrong because it's updating V even if not current broadcast
        if( $streaming ){
<<<<<<< HEAD
            pdo_query("1"," 
=======
            do_mysqli_query("1"," 
>>>>>>> d09b95b601296e47dbf1975a21403d408ce23ef8
               update broadcastlog set chatcount=chatcount+1 
               where chatid=$chatid and providerid =$providerid
               and mode ='V'
            ");
        }
         * 
         */
        if( $streaming ){
<<<<<<< HEAD
            pdo_query("1"," 
               update broadcastlog set chatcount=chatcount+1 
               where chatid=? and providerid =?
               and mode ='V' and 
               broadcastid = (select max(broadcastid) from broadcastlog 
               where mode='V' and providerid = ? and chatid=?)
            ",array($chatid,$providerid,$providerid,$chatid));
=======
            do_mysqli_query("1"," 
               update broadcastlog set chatcount=chatcount+1 
               where chatid=$chatid and providerid =$providerid
               and mode ='V' and 
               broadcastid = (select max(broadcastid) from broadcastlog 
               where mode='V' and providerid = $providerid and chatid=$chatid)
            ");
>>>>>>> d09b95b601296e47dbf1975a21403d408ce23ef8
        }
    
    
}    