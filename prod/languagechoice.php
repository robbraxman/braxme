<?php
session_start();
include("config.php");
include("internationalization.php");

$language = @tvalidator("PURIFY", strtolower($_POST['language']) );
$mode = @tvalidator("PURIFY", $_POST['mode'] );
$providerid = @tvalidator("PURIFY", $_POST['providerid'] );

if($mode == 'S'){
    do_mysqli_query("1","update provider set language='$language' where providerid = $providerid");
    exit();
}
if($mode == ''){
    $result = do_mysqli_query("1","select language from  provider  where providerid = $providerid");
    if($row = do_mysqli_fetch("1",$result)){
        $language = $row['language'];
        if($language == ''){
            $language = 'english';
        }
        
        
    }
}


?>

        <div style='text-align:center;width:100%;margin:auto;padding:20px;background-color:transparent;color:<?=$global_textcolor?>'>
            <div class='pagetitle2' style='font-weight:bold;color:<?=$global_textcolor?>'><?=$menu_language?></div>
            <br>
            <div class='gridstborder smalltext' style='margin:auto;background-color:transparent;text-align:center;vertical-align:center;width:100%;max-width:500px;cursor:pointer'>
                
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='english' >English<br>US Date</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='english-uk' >English<br>UK Date</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='spanish' >Spanish<br>Español</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='danish' >Danish<br>Dansk</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='russian' >Russian<br>русский</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='dutch' >Dutch<br>Nederlands</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='french' >French<br>Français</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='korean' >Korean<br>한국어</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='german' >German<br>Deutsch</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='portuguese' >Portuguese<br>Português</div>
                <div class='languagechoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:<?=$global_menu_color?>;color:white' data-mode='S' data-language='Urdu' >Urdu<br>Urdu</div>
                
            </div>
            <div style='width:100%;float:left'>
                <br>
                <?=$menu_language?><br>
                <input class='colorscheme1' id='colorscheme1' name='colorscheme' type='text' readonly='readonly' maxlength='60' value ='<?=$language?>' style='background-color:whitesmoke;max-width:300px' />
                <br><br><br>
            </div>
            <br>
            <br><br>
        </div>
