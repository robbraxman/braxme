<?php
    
    function RenameIcecastRecording($providerid, $chatid, $filename, $broadcasttitle)
    {
        $result = pdo_query("1",
            "select broadcastid from broadcastlog  
             where providerid = ? and 
             chatid = ? order by broadcastid desc limit 1
            "
            ,array($providerid,$chatid));
        $broadcastid = 0;
        if($row = pdo_fetch($result)){
            $broadcastid = $row['broadcastid'];
        }
        
        
        $streamhash = substr(hash("sha1", $chatid),0,8);
        $streamid = "chat$streamhash";

        $uniqid = uniqid();
        $cleanedfilename = str_replace(" ","", $filename."-".$uniqid );
        
        $data_string = "mode=S&streamid=$streamid&filename=$cleanedfilename";
        $ch = curl_init('http://audio.brax.live/save_recording.php');
        if($ch ){
            //live.zuckbook.net/save_recording.php
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);
            pdo_query("1"," 
                insert into recordings (streamid, providerid, filename, createdate, status, broadcasttitle, broadcastid ) values 
                  (?, ?, '$cleanedfilename.mp3', now(), ?, ?,? )
                    ",array($streamid,$providerid,$result,$broadcasttitle,$broadcastid));
        }
        
        return $result;
        
    }
    function DeleteIcecastRecording($providerid, $chatid )
    {
        $streamhash = substr(hash("sha1", $chatid),0,8);
        $streamid = "chat$streamhash";
        $streamidfile = $streamid.".mp3";

        
        $data_string = "mode=D&streamid=$streamid&filename=$streamidfile";
        $ch = curl_init('http://audio.brax.live/save_recording.php');
        if($ch ){
            //live.zuckbook.net/save_recording.php
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);
        }
        
        return $result;
        
    }
    function DeleteIcecastRecordingFilename($providerid, $chatid, $recid )
    {
        $streamhash = substr(hash("sha1", $chatid),0,8);
        $streamid = "chat$streamhash";

        $filename = '';
        $result = pdo_query("1","select filename from recordings where recid=? ",array($recid));
        if($row = pdo_fetch($result)){
            $filename = $row['filename'];
        }
        if($filename == ''){
            return;
        }
        
        
        $data_string = "mode=D&streamid=$streamid&filename=$filename";
        $ch = curl_init('http://audio.brax.live/save_recording.php');
        if($ch ){
            //live.zuckbook.net/save_recording.php
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);
        }
        
        return $result;
        
    }
    function GetStreamingAccount($providerid)
    {
        
        $result = pdo_query("1","select providername, streamingaccount from provider where providerid=? ",array($providerid));
        $row = pdo_fetch($result);
        $streamingaccount = $row['streamingaccount'];
        $broadcastername = $row['providername'];
        
        $array['streamingaccount'] = $streamingaccount;
        $array['broadcastername'] = $broadcastername;
        
        
        if( $streamingaccount!=''){
            if(
               strstr(strtolower($streamingaccount),"braxlive/")===false
               &&
               strstr(strtolower($streamingaccount),"webcam/")===false
               &&
               strstr(strtolower($streamingaccount),"periscope/")===false
               &&
               strstr(strtolower($streamingaccount),"youtube/")===false
               &&
               strstr(strtolower($streamingaccount),"youtubevideo/")===false
               &&
               strstr(strtolower($streamingaccount),"twitch/")===false
               &&
               strstr(strtolower($streamingaccount),"twitter-tweet")===false
               ){
                echo "Invalid Video Stream Info";
                exit();
            }
        }
        return( (object) $array);
        
    }
    function TouchMembers($chatid)
    {
        /*
        pdo_query("1","
            update provider set lastnotified = null where providerid in (select providerid from chatmembers where chatid=$chatid)
            ");
         * 
         */
        pdo_query("1","
            update alertrefresh set lastnotified = null where providerid in (select providerid from chatmembers where chatid=?)
            ",array($chatid));
    }
    function GetNewBroadcastID( $parmkey, $parmcode )
    {
            
        
            $result = pdo_query("1","
                select val1 from parms where parmkey=? and parmcode=? 
                ",array($parmkey,$parmcode));
            if( $row = pdo_fetch($result)){
                $val1 =intval($row['val1']);
            } else {
                
                $maxval = 100;
                if($parmkey =='ROOM') {
                    $result = pdo_query("1","
                        select max(roomid)+1 as maxval from statusroom
                        ");
                    if( $row = pdo_fetch($result)){
                    
                        $maxval =intval($row['maxval']);
                    }
                }
                
                $result = pdo_query("1","
                    insert into parms (parmkey, parmcode, val1, val2 ) values 
                    (?,?, ?, 0 )
                ",array($parmkey,$parmcode,$maxval));
                $val1 = $maxroomid;
     
            }
            $result = pdo_query("1","
                update parms set val1=val1+1 where parmkey=? and parmcode=?
            ",array($parmkey,$parmcode));
            $val1++;
            
            return $val1;
        
    }
    function GoLive($providerid, $chatid, $title, $broadcastmode )
    {
            $streamhash = substr(hash("sha1", $chatid),0,8);
            $streamid = "chat$streamhash";
            
            $result = pdo_query("1",
                "
                update chatmaster set streamid=?, 
                broadcaster = ?, live = 'Y', 
                radiotitle=?, broadcastmode=? 
                where chatid=? and radiostation in ('Y','Q')
                ",array($streamid,$providerid,$title,$broadcastmode,$chatid));
        
    }
    function ChangeLiveTitle($chatid, $title )
    {
            
            $result = pdo_query("1",
                "
                update chatmaster set radiotitle=? 
                where chatid=? and radiostation in ('Q', 'Y')
                ",array($title, $chatid));
        
    }    
    function CreateNewBroadcastLog($providerid, $chatid)
    {
        $result = pdo_query("1",
            "
            update chatmembers set broadcaster = null where chatid=$? 
            ",array($chatid));
        $broadcastid = GetNewBroadcastID( "BROADCAST", "ID" );

        $result = pdo_query("1",
            "
            insert into broadcastlog 
            (providerid, chatid, broadcastdate, mode, broadcastid, chatcount ) 
            values 
            ( ?, ?, now(), 'B', ?, 0 )
            ",array($providerid,$chatid,$broadcastid));

    }
    function SetChatPopupVideoViewer($providerid, $broadcastmode, $chatid)
    {
        $result = pdo_query("1"," 
            delete from chatpopup where chatid=? 
            ",array($chatid));
        
        if($broadcastmode!='V'){
            return;
        }
        
        $streamobj = GetStreamingAccount($providerid);
        $broadcastername = $streamobj->broadcastername;
        $streamingaccount = $streamobj->streamingaccount;
        
        
        //Chatpopup
        if( $streamingaccount !=''){
            $base64_streaming_account = base64_encode($streamingaccount);
            
            $result = pdo_query("1"," 
                insert into chatpopup (broadcaster, chatid, url ) 
                values (?, ?, '$base64_streaming_account' )
                ",array($providerid,$chatid));
            
        }
        
    }
    function SetStreamingAccountFromConnect($providerid, $app, $chatid)
    {
        $braxsource = '';
        $twitchstreamkey = '';
        $youtubestreamkey = '';
        $result = pdo_query("1","select braxsource, youtubestreamkey, twitchstreamkey from restream where providerid = ? ",array($providerid));
        if($row = pdo_fetch($result)){
            $braxsource = $row['braxsource'];
            $youtubestreamkey = $row['youtubestreamkey'];
            $twitchstreamkey = $row['twitchstreamkey'];
        }
        if($braxsource=='' || $twitchstreamkey == ''){
            $braxsource = 'youtube';
        }
        if($twitchstreamkey!='' && ($app == 'live_t' || $app == 'live_pt')){
            $braxsource = 'twitch';
        }
        if($youtubestreamkey!='' && ($app == 'live_y' || $app == 'live_py' || $app == 'live')){
            $braxsource = 'youtube';
        }
        if($youtubestreamkey == '' && $twitchstreamkey == ''){
            return false;
        }
        
        //Broadcast Brax from Twitch
        if($braxsource == 'twitch' ){
            
        
            pdo_query("1","delete from chatpopup where chatid = ? and broadcaster = ? ",array($chatid,$providerid));
            $result = pdo_query("1","select channel from streamingaccounts where providerid = ? and videotype = 'twitch' ",array($providerid));
            if($row = pdo_fetch($result)){
                $url64 = base64_encode("twitch/"."$row[channel]");
                $url = "twitch/"."$row[channel]";
            }
            pdo_query("1"," 
                insert into chatpopup (chatid, broadcaster, url ) values (?, ?, '$url64' )
                ",array($chatid,$providerid));
            pdo_query("1"," 
                update provider set streamingaccount=? where providerid = ?
                ",array($url,$providerid));
            return true;
        }
        //Broadcast Brax from youtube
        if($braxsource == 'youtube' ){
            
        
            pdo_query("1","delete from chatpopup where chatid = ? and broadcaster = ? ",array($chatid,$providerid));
            $result = pdo_query("1","select channel from streamingaccounts where providerid = ? and videotype = 'youtube' ",array($providerid));
            if($row = pdo_fetch($result)){
                $url64 = base64_encode("youtube/"."$row[channel]");
                $url = "youtube/"."$row[channel]";
            }
            pdo_query("1"," 
                insert into chatpopup (chatid, broadcaster, url ) values (?, ?, '$url64' )
                ",array($chatid,$providerid));
            pdo_query("1"," 
                update provider set streamingaccount='$url' where providerid = ?
                ",array($providerid));
            return true;
        }
        return false;
        
        
    }
    
    
    function BroadcastModeMessage($providerid, $chatid, $mode, $action, $title_decoded )
    {
        
        $result = pdo_query("1","
                select keyhash, radiotitle, broadcaster, hidemode, 
                owner, radiostation, title,
                ( select radiostation from roominfo 
                  where 
                  roominfo.roomid = chatmaster.roomid
                ) as roomradiostation
                from chatmaster where chatid=?
                ",array($chatid));
        if( !$row = pdo_fetch($result)){

            return( (object) null);
        }

    
        $radiostation = $row['radiostation'];
        $radiotitle = stripslashes(base64_decode($row['radiotitle']));
        
        $streamobj = GetStreamingAccount($providerid);
        $broadcastername = $streamobj->broadcastername;
        if($mode == 'STREAM'){

            $message = "Channel is LIVE - $broadcastername - OnAir - $radiotitle";
            $messageshort = "$broadcastername is LIVE - $radiotitle";
        }
        if($mode == 'BROADCASTER'){

            if($action == 'VIDEO' || $action == 'WEBCAM'){

                $message = "Live Video - $broadcastername - $title_decoded";

            } else {

                $message = "Broadcast Started - $broadcastername - OnAir - $title_decoded";

            }

            $messageshort = "$broadcastername is LIVE - $title_decoded";
        }
        if($mode == 'ENDBROADCAST'){

            $message = "Broadcast Ended - $broadcastername";
            $messageshort = "$broadcastername is Off Air";

        }

        $array['message']=$message;
        $array['messageshort']=$messageshort;
        return( (object) $array);

    }
    function FindPreferredStation($providerid)
    {

        //if currently live, no action
        $result = pdo_query("1","
        select chatid, radiostation, broadcaster from chatmaster 
        where
        radiostation='Y' and live='Y' 
        and 
        broadcaster = ?
        ",array($providerid));
        if($row = pdo_fetch($result)){
            return "";
        }
        
        
        $result = pdo_query("1","
        select broadcasttype from restream where providerid = ? 
        ",array($providerid));
        $broadcasttype = '';
        if($row = pdo_fetch($result)){
            $broadcasttype = $row['broadcasttype'];
        }
        //if($broadcasttype!='Art'){
        //    $broadcasttype = '';
        //}
    
        
        $result = pdo_query("1","
        select chatid from chatmaster where 
        radiostation='Y' and live='N' and roomid is not null
        and broadcasttype = ?
        order by lastmessage desc
        ",array($broadcasttype));
        $chatid = '';
        if($row = pdo_fetch($result)){
            return  $row['chatid'];
        }
        
        $result = pdo_query("1","
        select chatid from chatmaster where 
        radiostation='Y' and live='N' and roomid is not null
        and broadcasttype = ''
        order by lastmessage desc
        ");
        $chatid = '';
        if($row = pdo_fetch($result)){
            return  $row['chatid'];
        }
        $result = pdo_query("1","
        select chatid from chatmaster where chatid in 
        (
            select chatid FROM broadcastlog 
            where providerid = ? and mode ='B'
        )
        and radiostation='Y' and live='N' and roomid is not null
        order by lastmessage desc limit 2
        ",array($providerid));
        $chatid = '';
        if($row = pdo_fetch($result)){
            return  $row['chatid'];
        }
        
        
        return "";
    }