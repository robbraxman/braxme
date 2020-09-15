<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

$chatid = tvalidator("PURIFY",$_POST['chatid']);
$mode = tvalidator("PURIFY",$_POST['mode']);
?>
    <title>Broadcast Choices</title>
</head>    
<BODY class="appbody mainfont"  style='color:<?=$global_textcolor?>'>

    <div class="showtable" style='margin:auto;max-width:500px;padding:20px;color:<?=$global_textcolor?>'>
        <table>

        <tr>
        <td class='dataarea pagetitle3' style='color:<?=$global_textcolor?>'>
            <span class='pagetitle' style='color:<?=$global_textcolor?>'>Set Broadcast Source</span>
            <br><br><br>
            <b>Broadcast Source</b>
        <br>
        <!--
        <span class='nonmobile'>
        <input class='broadcasttype videowebcam' id='videowebcam' name='broadcasttype'  type='radio' value='webcam' style='position:relative;top:5px'/> WebCam
        <br>
        </span>
        -->
        <input class='broadcasttype videotwitch ' id='videotwitch' name='broadcasttype' type='radio' value='twitch' style='position:relative;top:5px'/> Twitch
        <br>
        <input class='broadcasttype videoyoutube' id='videoyoutube' name='broadcasttype' type='radio' value='youtube' style='position:relative;top:5px'/> Youtube Live Channel
        <br>
        <input class='broadcasttype videoyoutubevideo' id='videoyoutubevideo' name='broadcasttype'  type='radio' value='youtubevideo' style='position:relative;top:5px'/> Youtube Video Watch ID
        <br>
        <!--
        <input class='broadcasttype videobraxlive' id='videobraxlive' name='broadcasttype'  type='radio' value='braxlive' style='position:relative;top:5px'/> BraxLive (Premium/Beta)
        <br>
        -->
        <br>
        <span class='videochannelinfo' style='display:none'>
            <div class='smalltext videotypechannelheading'>Channel</div>
            <INPUT id="videochannel" class="videochannel" TYPE="text" >
        </span>
        <br><br><br>
        <b>Broadcast Title</b><br>
        <INPUT id="audiostreamtitle" class="videobroadcasttitle" TYPE="text" style='width:250px' >
        
        <br><br><br>
        <div class='divbuttontext setchatsession' data-mode='LIVE' data-chatid='<?=$chatid?>'>Cancel Broadcast</div>
        <br><br><br>
        <span class='videoselectaction smalltext' style='display:none'>
            <div class='divbuttontext notifyaudiostream' data-chatid='<?=$chatid?>' data-mode='<?=$mode?>' data-action='VIDEOCONFIRM' >Begin Broadcast</div>
        </span>
        <br><br><br>
                        <a href='<?=$rootserver?>/room/live' style='text-decoration:none'>
                            <div class='pagetitle3 gridnoborder' style='color:<?=$global_activetextcolor?>;margin:auto;'>
                                <u>
                                    Learn how to Broadcast #live
                                </u>
                                    <br>
                            </div>
                        </a>
        </td>
        </tr>
        
        

        <tr>
        <td class='dataarea' feedphoto>
        <INPUT id="videotype" class="videotype" TYPE="hidden" >
        <br><br>
        </td>
        </tr>
        
        
        
        </table>
    </div>
        <br>
        <br>
        <br>
</BODY>
</HTML>

