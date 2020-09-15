<?php
session_start();
require_once("config-pdo.php");

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = mysql_safe_string($_POST['providerid']);
    $chatid = mysql_safe_string($_POST['chatid']);
    $mode = @mysql_safe_string($_POST['mode']);
    
    $mobilesize = $_SESSION['mobilesize'];
    /*
    if($mobilesize == 'Y'){
        $width = intval($_SESSION['innerwidth']);
        $height = $width*(9/16);
        $height .= "px";
        $width .= "px";
    } else
     * 
     */
    if(intval($_SESSION['innerwidth'])<768){
        $width = intval($_SESSION['innerwidth']);
        $height = $width*(9/16);
        $height .= "px";
        $width .= "px";
        
    } else {
        $width = "100%";
        $height = "100%";
    }
    
    
    //$fullscreen = "<img class='fullscreenvideo icon15' src='../img/full-screen-expand-128.png' style='float:left;padding-top:5px;padding-bottom:5px;padding-left:15px;cursor:pointer' /><br><br>";
    $fullscreen = "<center><img class='fullscreenvideo icon15' src='../img/full-screen-expand-128.png' style='padding-top:5px;padding-bottom:5px;padding-left:15px;cursor:pointer' /></center>";
    $result = pdo_query("1","
                select chatpopup.url, chatpopup.broadcaster, provider.handle  from 
                chatpopup 
                left join provider on chatpopup.broadcaster = provider.providerid
                where chatpopup.chatid=? and chatpopup.broadcaster in 
                (select broadcaster from chatmaster where chatmaster.chatid=chatpopup.chatid) 
             ",array($chatid));
    $url = "";
    if($row = pdo_fetch($result)){
        $url = base64_decode($row['url']);
        $broadcaster = base64_decode($row['broadcaster']);
        $handle = $row['handle'];
        
    }
    $link = explode("/",$url);
    
    if($mode == 'B' && $link[0]!='webcam' ){
        $panel = 'panel';
        $data =  "<div class='pagetitle' style='color:white;padding:20px'>Live Stream Active</div> ";
        $data .= "
            <div class='mainfont' style='color:white;padding:20px'>
                You are broadcasting a live video stream. It is not shown to the broadcaster to 
                avoid sound interference. To monitor the video, click on <img class='icon15' src='../img/Movie2_120px.png' />
            </div>";
        $param = "";
        
        $arr = array('data'=> "$data",
                     'param'=> "$param",
                     'panel'=> "$panel",
                     'fullscreen' => "$fullscreen"
            
                    );

        echo json_encode($arr);
        exit();
        
    }
    if($mode == 'B' && $link[0]=='webcam' ){
        
        $panel = 'panel';
        $data = "<div class='pagetitle2' style='padding:30px'><a href='https://videolive.brax.me/live/broadcast2.jsp?host=videolive.brax.me&stream=".substr($_SESSION['handle'],1)."' target=_blank style='text-decoration:none;color:white'><u>Launch Webcam Broadcaster</u></a></div>";
        $param = "";
        
        $arr = array('data'=> "$data",
                     'param'=> "$param",
                     'panel'=> "$panel",
                     'fullscreen' => "$fullscreen"
            
                    );
        echo json_encode($arr);
        exit();
    }
    
    
    
    
    $autoplay = "autoplay='true' muted='false' playsinline='true' ";
    $autoplayYt = "&autoplay=1&playsinline=1";
    if($broadcaster == $_SESSION['pid'] && $_SESSION['mobilesize']!='Y'){
        $autoplay = "autoplay='false' muted='true' ";
        $autoplayYt = "";
    }
    $panel ='popup';
    $param ='';
    $data = '';
    $script = "";
    if(strtolower($link[0]=='twitch')){
        $panel = 'panel';
        //$data =  "<iframe class='videoframe' src='https://player.twitch.tv?channel=".$link[1]."' style='border:0px none transparent;width:100%;height:100%;overflow:hidden;z-index:0' allowfullscreen='true' playsinline='true' $autoplay></iframe>";
        $data =  "
            <div id='twitch-embed'></div>

                <!-- Load the Twitch embed script -->
                <!--<script src='https://embed.twitch.tv/embed/v1.js'></script>-->
                <!-- Create a Twitch.Embed object that will render within the twitch-embed root element. -->
                <script type='text/javascript'>
                  var twitchwidth = '$width';
                  var twitchheight = '$height';
                  if(twitchwidth === 0 ){
                    twitchwidth = '100%';
                  }
                  var embed = new Twitch.Embed('twitch-embed', {
                    autoplay: true,
                    width: twitchwidth,
                    height: twitchheight,
                    playsinline: true,
                    layout: 'video',
                    muted : 'false',
                    channel: '".$link[1]."'
                  });
                embed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
                   var player = embed.getPlayer();
                   player.play();
                   player.setMuted(false);
                 });                  
                </script>            
             ";

        pdo_query("1", " 
            update chatmembers 
                set chatmembers.broadcaster = (select broadcaster from chatmaster where chatmembers.chatid = chatmaster.chatid)
                where chatmembers.chatid = ? and chatmembers.providerid = $_SESSION[pid]
        ",array($chatid));
        
    } else 
    if(strtolower($link[0]=='youtube')){
        $panel = 'panel';
        $data =  "<iframe class='videoframe' src='https://www.youtube.com/embed/live_stream?channel=".$link[1]."&rel=0&$autoplayYt' style='border:0px none transparent;width:$width;height:$height' allowfullscreen='true' playsinline='true' $autoplay></iframe> ";
        pdo_query("1", " 
            update chatmembers 
                set chatmembers.broadcaster = (select broadcaster from chatmaster where chatmembers.chatid = chatmaster.chatid)
                where chatmembers.chatid = ?  and chatmembers.providerid = $_SESSION[pid]
        ",array($chatid));
    } else 
    if(strtolower($link[0]=='braxlive')){
        
        $panel = 'panel';
        $data =  "<iframe class='videoframe' src='http://videolive.brax.me/live/viewer2.jsp?host=videolive.brax.me&stream=".substr($handle,1)."'  style='border:0px none transparent;width:100%;height:100%;padding:0;margin:0'  ></iframe> ";
        pdo_query("1", " 
            update chatmembers 
                set chatmembers.broadcaster = (select broadcaster from chatmaster where chatmembers.chatid = chatmaster.chatid)
                where chatmembers.chatid = ?  and chatmembers.providerid = $_SESSION[pid]
        ",array($chatid));
    } else 
    if(strtolower($link[0]=='webcam')){
        
        $panel = 'panel';
        $data =  "<iframe class='videoframe' src='https://videolive.brax.me/live/viewer2.jsp?host=videolive.brax.me&stream=".substr($handle,1)."'  style='border:0px none transparent;width:100%;height:100%;padding:0;margin:0'  allowfullscreen='yes' $autoplay></iframe> ";
        pdo_query("1", " 
            update chatmembers 
                set chatmembers.broadcaster = (select broadcaster from chatmaster where chatmembers.chatid = chatmaster.chatid)
                where chatmembers.chatid = ?  and chatmembers.providerid = $_SESSION[pid]
        ",array($chatid));
    } else 
    if(strtolower($link[0]=='youtubevideo')){
        $panel = 'panel';
        $data =  "<iframe class='videoframe' src='https://www.youtube.com/embed/".$link[1]."?rel=0&$autoplayYt' style='border:0px none transparent;width:100%;height:100%' allowfullscreen='yes' $autoplay></iframe> ";
        pdo_query("1", " 
            update chatmembers 
                set chatmembers.broadcaster = (select broadcaster from chatmaster where chatmembers.chatid = chatmaster.chatid)
                where chatmembers.chatid = ?  and chatmembers.providerid = $_SESSION[pid]
        ",array($chatid));
    } else 
    if(strtolower($link[0]=='periscope')){
        $data =  "https://periscope.tv/".$link[1];
        $param = 'width=500,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=1000,top=100';
    } else {
    }
    
    if(strstr($url,"twitter-tweet")!==false){
        $panel = 'panel';
        $data = $url;
    }
   
    $result = pdo_query("1",
        "select broadcastid from broadcastlog  
         where 
         chatid = ? and mode='B' order by broadcastid desc limit 1
        ",array($chatid)
        );
    if($row = pdo_fetch($result)){

        pdo_query("1",
            "
            insert into broadcastlog 
            (providerid, chatid, broadcastdate, mode, broadcastid, chatcount ) 
            values 
            ( ?, ?, now(), 'V', $row[broadcastid], 0 )
            ",array($providerid,$chatid));
    }

    $arr = array('data'=> "$data",
                 'param'=> "$param",
                 'panel'=> "$panel",
                 'fullscreen'=> "$fullscreen" 
        
                );

    echo json_encode($arr);
    exit();
    
?>

