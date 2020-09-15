<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_POST['providerid']);

    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $roomid = @tvalidator("ID",$_POST['roomid']);
    //Data
    $backgroundcolor = strip_tags(@tvalidator("PURIFY",$_POST['backgroundcolor']));
    $color = strip_tags(@tvalidator("PURIFY",$_POST['color']));
    $trimcolor = strip_tags(@tvalidator("PURIFY",$_POST['trimcolor']));
    $title = strip_tags(@tvalidator("PURIFY",$_POST['title']),"<span><div>");
    $subtitle = strip_tags(@tvalidator("PURIFY",$_POST['subtitle']),"<span><div><a>");
    $subtitle2 = strip_tags(@tvalidator("PURIFY",$_POST['subtitle2']),"<span><div><a>");
    $footer = strip_tags(@tvalidator("PURIFY",$_POST['footer']),"<span><div><a>");
    $analytics = @tvalidator("PURIFY",$_POST['analytics']);
    
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img src='../img/arrow-stem-circle-left-white-128.png' style='position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    if( $mode == 'S')
    {
        $result = pdo_query("1","
            delete from roomwebstyle where roomid=$roomid
                ");
        
        //Data
        $result = pdo_query("1","
            insert into roomwebstyle (roomid, seq, stylekey, styledata ) values
            ($roomid,1,'backgroundcolor', '$backgroundcolor')
                ");
        $result = pdo_query("1","
            insert into roomwebstyle (roomid, seq, stylekey, styledata ) values
            ($roomid,2,'title', '$title')
                ");
        $result = pdo_query("1","
            insert into roomwebstyle (roomid, seq, stylekey, styledata ) values
            ($roomid,3,'subtitle', '$subtitle')
                ");
        
        
        $result = pdo_query("1","
            insert into roomwebstyle (roomid, seq, stylekey, styledata ) values
            ($roomid,4,'footer', '$footer')
                ");
        $result = pdo_query("1","
            insert into roomwebstyle (roomid, seq, stylekey, styledata ) values
            ($roomid,5,'analytics', '$analytics')
                ");
        
        $result = pdo_query("1","
            insert into roomwebstyle (roomid, seq, stylekey, styledata ) values
            ($roomid,6,'subtitle2', '$subtitle2')
                ");
        $result = pdo_query("1","
            insert into roomwebstyle (roomid, seq, stylekey, styledata ) values
            ($roomid,7,'color', '$color')
                ");
        $result = pdo_query("1","
            insert into roomwebstyle (roomid, seq, stylekey, styledata ) values
            ($roomid,8,'trimcolor', '$trimcolor')
                ");
        
    }
    if( $mode == 'D')
    {
    }
    if($backgroundcolor == '')
    {
        $backgroundcolor = 'black';
    }
    if($color == '')
    {
        $color = 'white';
        
    }
    if($trimcolor == '')
    {
        $trimcolor = 'orange';
        
    }
    
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    $result = pdo_query("1","
        select styledata, stylekey, seq 
        from roomwebstyle where roomid=$roomid order by seq asc
            ");
    while($row = pdo_fetch($result)){
        if($row['stylekey']=='backgroundcolor')
        {
            $backgroundcolor = $row['styledata'];
        }
        if($row['stylekey']=='color')
        {
            $color = $row['styledata'];
        }
        if($row['stylekey']=='trimcolor')
        {
            $trimcolor = $row['styledata'];
        }
        if($row['stylekey']=='title')
        {
            $title = $row['styledata'];
        }
        if($row['stylekey']=='subtitle')
        {
            $subtitle = $row['styledata'];
        }
        if($row['stylekey']=='subtitle2')
        {
            $subtitle2 = $row['styledata'];
        }
        if($row['stylekey']=='footer')
        {
            $footer = $row['styledata'];
        }
        if($row['stylekey']=='analytics')
        {
            $analytics = $row['styledata'];
        }
     }
?>
    <br>
    <span class='pagetitle' style="color:white">&nbsp;Web Styles for Public View</span> 
    <br>
    &nbsp;&nbsp;
    <div class='divbuttontextonly friends showtop tapped' 
        data-roomid='<?=$roomid?>' data-mode=''>
            <?=$braxsocial?>
            Back

    </div>

    <div style='padding:0;margin:0;background-color:whitesmoke;color:black'>
        <table class='gridnoborder' style='width:300px;margin:auto' >
            <tr>
                <td class='pagetitle2'">
                    Web Styles
                </td>
            </tr>
            <tr>
                <td>Background-Color</td>
            </tr>
            <tr>
                <td>
                    <input id='webstyle_backgroundcolor' class='webstyle_backgroundcolor'  style='width:275px' placeholder='Background-Hex-Color' type='text' size='50' maxlength='30' value='<?=$backgroundcolor?>'/>
                </td>
            </tr>
            <tr>
                <td>Text-Color</td>
            </tr>
            <tr>
                <td>
                    <input id='webstyle_color' class='webstyle_color' style='width:275px' placeholder='Text-Hex-Color' type='text' size='50' maxlength='30' value='<?=$color?>'/>
                </td>
            </tr>
            <tr>
                <td>Trim-Color</td>
            </tr>
            <tr>
                <td>
                    <input id='webstyle_trimcolor' class='webstyle_trimcolor' placeholder='Text-Hex-Color' type='text' size='50'  style='width:275px' maxlength='30' value='<?=$trimcolor?>'/>
                </td>
            </tr>
            <tr>
                <td>Title</td>
            </tr>
            <tr>
                <td>
                    <input class='webstyle_title' placeholder='Title' type='text' size='50' maxlength='50' style='width:275px' value='<?=$title?>'/>
                </td>
            </tr>
            <tr>
                <td>Subtitle</td>
            </tr>
            <tr>
                <td>
                    <input class='webstyle_subtitle' placeholder='Subtitle' type='text' size='50'  style='width:275px' value='<?=$subtitle?>'/>
                </td>
            </tr>
            <tr>
                <td>Subtitle-2</td>
            </tr>
            <tr>
                <td>
                    <input class='webstyle_subtitle2' placeholder='Subtitle Small Font' type='text' size='50'  style='width:275px' value='<?=$subtitle2?>'/>
                </td>
            </tr>
            <tr>
                <td>Footer</td>
            </tr>
            <tr>
                <td>
                    <input class='webstyle_footer' placeholder='Footer' type='text' size='50'  style='width:275px' value='<?=$footer?>'/>
                </td>
            </tr>
            <!--
            <tr>
                <td style='text-align:right;padding-right:5px'>Analytics Script</td>
                <td style='width:150px'>
                    <textarea class='webstyle_analytics' 
                              placeholder='Analytics Script <script></script>' type='text' size='50' ><?=$analytics?></textarea>
                </td>
            </tr>
            -->
            <tr>
                <td>
                    <br>
                    <div class='divbutton3 divbutton3_unsel roomweb' data-roomid='<?=$roomid?>' data-mode='S'>
                        Save Styles
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <br><br>
                    <b>Tip</b>: Menus will automatically appear at the top of the webpage 
                    based on the files that have been shared in the room's FILES area.
                    MUSIC Menu (Mp3 files), PHOTOS Menu (Jpg, Png, Tif), FILES Menu (Pdf).
                    <br><br>
                    If you share an Html 
                    file, it will become a menu option that displays that file. The file's title will 
                    be the Menu item name.
                </td>
            </tr>
            
        </table>
    </div>
    <script>
    $("#webstyle_backgroundcolor").spectrum({
        showPalette: true,
        showSelectionPalette: true,
        palette: [
        ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
        ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
        ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
        ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
        ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
        ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
        ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
        ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]],
        preferredFormat: "hex",
        color: "<?=$backgroundcolor?>"
    });
    $("#webstyle_color").spectrum({
        showPalette: true,
        showSelectionPalette: true,
        palette: [
        ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
        ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
        ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
        ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
        ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
        ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
        ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
        ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]],
        preferredFormat: "hex",
        color: "<?=$color?>"
    });
    $("#webstyle_trimcolor").spectrum({
        showPalette: true,
        showSelectionPalette: true,
        palette: [
        ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
        ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
        ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
        ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
        ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
        ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
        ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
        ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]],
        preferredFormat: "hex",
        color: "<?=$trimcolor?>"
    });
    </script>

