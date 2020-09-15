<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
$streamid = @mysql_safe_string($_POST['streamid']);
$chatid = @mysql_safe_string($_POST['chatid']);
$broadcastid = @mysql_safe_string($_POST['broadcastid']);
$filename = @mysql_safe_string($_POST['filename']);
$live = 0;

    pdo_query("1",
        "
        insert into broadcastlog 
        (providerid, chatid, broadcastdate, mode, broadcastid ) 
        values 
        ( $_SESSION[pid], ?, now(), 'R', ? )
        ",array($chatid,$broadcastid));


?>

<script src="../libs/audio/audiojs/audio.min.js"></script>
<script>
audiojs.events.ready(function() 
{
    var as = audiojs.createAll();
});
    
</script>

<body style="background-color:whitesmoke">
    <div style='font-family:helvetica;font-size:large;padding:5px'><b>Replay Audio Stream</b></div>
    <div class='selectchatlist' data-mode='LIVE' style='padding:20px'>
            <img class='icon20' src='<?=$iconsource_braxarrowleft_common?>' /> Back
        </div>
    <br><br>
    <audio src='http://audio.brax.live/recordings/<?=$filename?>' >
        <!--
        <embed src="https://audio.brax.live/recordings/<?=$filename?>" type="audio/mp3" width="300"
          height="100"/>
        -->
    </audio>
    <br><br><br>
    <div class='formobile' style="padding:20px;margin:auto">
        On iOS, disable MUTE button to listen.
    </div>

</body>
<?php
    
?>
