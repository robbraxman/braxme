<?php
session_start();
require_once("config.php");
require_once("internationalization.php");

$colorscheme = @mysql_safe_string( strtolower($_POST['colorscheme']) );
$wallpaper = @mysql_safe_string( strtolower($_POST['wallpaper']) );
$mode = @mysql_safe_string( $_POST['mode'] );
$providerid = @mysql_safe_string( $_POST['providerid'] );

if($mode == 'S'){
    do_mysqli_query("1","update provider set colorscheme='$colorscheme' where providerid = $providerid");
    exit();
}
if($mode == 'W'){
    do_mysqli_query("1","update provider set wallpaper='$wallpaper' where providerid = $providerid");
    exit();
}
if($mode == ''){
    $colorscheme = 'std';
    $result = do_mysqli_query("1","select colorscheme, wallpaper from  provider  where providerid = $providerid");
    if($row = do_mysqli_fetch("1",$result)){
        $colorscheme = $row['colorscheme'];
        $wallpaper = $row['wallpaper'];
        
        if($wallpaper =='none'){
            $wallpapertext = 
                    "<div class='colorchoice' data-mode='W' data-wallpaper='default' style='cursor:pointer;color:$global_activetextcolor'>Set to Default Wallpaper</div>";
        } else {
            $wallpapertext = 
                    "<div class='colorchoice' data-mode='W' data-wallpaper='none' style='cursor:pointer;color:$global_activetextcolor'>Set to No Wallpaper</div>";
            
        }
        
        
    }
    $enterprise = false;
    if($_SESSION['sponsorcolorscheme']!='std' && $_SESSION['sponsor']!=''){
        $colorscheme = "Enterprise Color: $_SESSION[sponsorcolorscheme]";
        $enterprise = true;
    }
}
if($enterprise == true){
    
?>
        <div style='text-align:center;width:100%;margin:auto;padding:20px;background-color:transparent;color:<?=$global_textcolor?>'>
            <div class='pagetitle2' style='font-weight:bold;color:<?=$global_textcolor?>'><?=$menu_colortheme?></div>
            <br>
            <div class=label style='margin:auto;text-align:center;width:100%'><?=$colorscheme?></div>
            <br>
            <br><br>
        </div>

<?php    
    exit();
}


?>

        <div style='text-align:center;width:100%;margin:auto;padding:20px;background-color:transparent;color:<?=$global_textcolor?>'>
            <div class='pagetitle2' style='font-weight:bold;color:<?=$global_textcolor?>'><?=$menu_colortheme?></div>
            <br>
            <div class='gridstborder smalltext' style='margin:auto;background-color:transparent;text-align:center;vertical-align:center;width:100%;max-width:500px;cursor:pointer'>

                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#c70039;color:white' data-mode='S' data-colorscheme='crimson' >crimson</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#e08283;color:white' data-mode='S' data-colorscheme='newyorkpink' >newyorkpink</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#674172;color:white' data-mode='S' data-colorscheme='lavender' >lavender</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#67809F;;color:white' data-mode='S' data-colorscheme='bluegray' >bluegray</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:whitesmoke;;color:gray' data-mode='S' data-colorscheme='bluesmoke' >bluesmoke</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#65c6bb;;color:white' data-mode='S' data-colorscheme='downygreen' >downygreen</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#C0392B;;color:white' data-mode='S' data-colorscheme='rustyred' >rustyred</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#F39C12;;color:white' data-mode='S' data-colorscheme='tuscany' >tuscany</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#2574a8;;color:white' data-mode='S' data-colorscheme='michigan' >michigan</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#49a942;;color:white' data-mode='S' data-colorscheme='lawn' >lawn</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#04acec;;color:white' data-mode='S' data-colorscheme='skyblue' >skyblue</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#5DADE2;;color:white' data-mode='S' data-colorscheme='riverblue' >riverblue</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#808B96;;color:white' data-mode='S' data-colorscheme='midnightblue' >midnightblue</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#45619d;;color:white' data-mode='S' data-colorscheme='facebook' >facebook</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#3e4749;color:white' data-mode='S' data-colorscheme='moonlit night' >moonlit night</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#2b2b2b;color:white' data-mode='S' data-colorscheme='crimson night' >crimson night</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#1b1b1b;color:white' data-mode='S' data-colorscheme='grape night' >grape night</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#1b1b1b;color:white' data-mode='S' data-colorscheme='forest night' >forest night</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#1b1b1b;color:white' data-mode='S' data-colorscheme='metal night' >metal night</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#1b1b1b;color:white' data-mode='S' data-colorscheme='starry night' >starry night</div>
                <div class='colorchoice' style='cursor:pointer;border:2px solid white;padding-top:15px;float:left;height:40px;width:100px;background-color:#1b1b1b;color:white' data-mode='S' data-colorscheme='dark alley' >dark alley</div>

            </div>
            <div style='width:100%;float:left'>
                <br>
                <?=$menu_colortheme?><br>
                <input class='colorscheme1' id='colorscheme1' name='colorscheme' type='text' readonly='readonly' maxlength='60' value ='<?=$row['colorscheme']?>' style='background-color:whitesmoke;max-width:300px' />
                <br><br><br>
                <?=$wallpapertext?>
            </div>
            <br>
            <br><br>
        </div>
