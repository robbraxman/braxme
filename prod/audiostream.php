<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
$streamid = @mysql_safe_string($_POST['streamid']);
$chatid = @mysql_safe_string($_POST['chatid']);
$live = 0;

if($chatid > 0){
    pdo_query("1", " 
        update chatmembers 
            set chatmembers.broadcaster = (select broadcaster from chatmaster where chatmembers.chatid = chatmaster.chatid)
            where chatmembers.chatid = ?  and chatmembers.providerid = $_SESSION[pid]
    ",array($chatid));
    
    $result = pdo_query("1",
        "select broadcastid from broadcastlog  
         where 
         chatid = $chatid and mode='B' order by broadcastid desc limit 1
        "
        );
    if($row = do_mysqli_fetch("1",$result)){

        pdo_query("1",
            "
            insert into broadcastlog 
            (providerid, chatid, broadcastdate, mode, broadcastid, chatcount ) 
            values 
            ( $_SESSION[pid], ?, now(), 'V', $row[broadcastid], 0 )
            ",array($chatid));
    }
    
} else {
    exit();
}

$uniqid = uniqid();

?>
<body style="background-color:whitesmoke">
<?php
if(CheckLiveStream($streamid)){
    $live++;
?>
    <div style='font-family:helvetica;font-size:large;padding:5px'><b>Audio Stream Starting</b></div>
    <div style='font-family:helvetica;font-size:small;padding:5px;'>Once you hear the live audio stream, you can return to Chat to converse with the Broadcaster</div>
    <br><br>
    <div id="audio_container">
        <p class="status"><a class="button" id="audio-button">Load audio</a> <span id="audio-progress-container"></span></p>
        <audio id='audio' controls autoplay  src='https://audio.brax.live:8443/<?=$streamid?>?y=<?=$uniqid?>' webkit-playsinline playsinline >
            <embed src="https://audio.brax.live:8443/<?=$streamid?>?y=<?=$uniqid?>" type="audio/mpeg" width="300"
              height="100"/>
        </audio>
    </div>
    <script>
        var audioBuffer = new Mediabuffer(document.getElementById('audio'), audioProgress, audioReady);			
        document.getElementById('audio-button').addEventListener('click', audioLoad, true);			
        document.getElementById('audio-button').style.display = 'inline-block';
        audioLoad();
    </script>
<?php
} else {
    
        //End Broadcast
        $result = pdo_query("1",
            "
            update chatmaster set broadcaster = null where chatid=? and radiostation == 'Y'
            ",array($chatid));
        
        $result = pdo_query("1",
            "
            update chatmembers set broadcaster = null where chatid=? 
            ",array($chatid));
        
        $result = pdo_query("1",
            "
            delete from notification where chatid=? and notifytype='CP' and notifysubtype='LV'
            and notifyid > 0
            ",array($chatid));
    
}










if(CheckLiveStream($streamid.".2")){
    $live++;
?>
    <br><br><br>
    <audio controls autoplay src='https://audio.brax.live:8443/<?=$streamid?>.2' />

<?php
}
if($live == 0){
?>
    <div style='font-family:helvetica;font-size:large;padding:5px'><b>Broadcast Ended</b></div>
<?php
}
?>

</body>
<?php
    
?>
