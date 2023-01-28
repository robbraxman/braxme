<?php
//init
$global_menu_color = "";
$global_menu2_color = "";
$global_titlebar_color = "";
$icon_scheme = '';
$global_separator_color = ""; //orange

$color_scheme = 'std';
$wallpaper_scheme = 'default';

if(!isset($_SESSION['colorscheme'])){
    $_SESSION['colorscheme']='std';
    $color_scheme = 'std';
} else {
    $color_scheme = $_SESSION['colorscheme'];
}
if(isset($_SESSION['hostedmode']) && $_SESSION['hostedmode'] =='true'){
    $color_scheme = $_SESSION['hostedcolorscheme'];
}
if(isset($_SESSION['wallpaper'])){
    $wallpaper_scheme = $_SESSION['wallpaper'];;
}

if(isset($webcolorscheme)){
    $color_scheme = $webcolorscheme;
    if($color_scheme == 'std'){
        $color_scheme='riverblue';
    }
    $wallpaper_scheme = 'default';
}

if($color_scheme==''){
    $color_scheme = 'riverblue';
}
if($color_scheme=='std' || $color_scheme == ''){
    $color_scheme = 'riverblue';
    $wallpaper_scheme = 'default';
}
if(!isset($_SESSION['devicecode'])){
    $_SESSION['devicecode']='';
}
if($_SESSION['devicecode']=='androidteink'){
    //$color_scheme = "bluesmoke";
    //$wallpaper_scheme = "none";
}
 
$global_textcolor = 'black';
$global_textcolor2 = 'gray';
$global_textcolor_reverse = 'white';
$global_background = 'white';
$global_web_background = 'white';
$global_background2 = 'white';
$global_activetextcolor = 'purple'; 
$global_activetextcolor_reverse = 'yellow'; 
$global_activetextcolor_onwhite = 'purple'; 
$global_icon_check = "<img class='icon15' title='Checked' src='../img/check-yellow-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
$global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
$global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-red-128.png' style='position:relative;top:8px;' />";
$global_icon_pin = "<img class='icon15' title='Liked' src='../img/pin-red-512.png' style='position:relative;top:8px;' />";
$global_icon_pin_gray = "<img class='icon15' title='Pin' src='../img/pin-gray-512.png' style='position:relative;top:8px;' />";
$global_icon_lock = "<img class='icon15' title='Pin' src='../img/minus-gray-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";

$global_profile_color = '#3e4749';//
$icon_darkmode = false;
$global_chatself_color = "#34495E"; //darkblue
$global_backgroundreverse = 'white';

$global_streamlive_color = 'firebrick';
$global_store_color = 'firebrick';
$iconsource_braxmedal_common = "../img/medal-orange-128.png";        
$iconsource_braxnewbie_common = "../img/newbie2.png";        

if( isset($_SESSION['superadmin']) && $_SESSION['superadmin']=='Y' ){
    if($color_scheme == "" || $color_scheme=='metalnight'){

        $color_scheme='grape night';
        $wallpaper_scheme = "none";
        $_SESSION['colorscheme']='grape night';
    }
    //echo "colorscheme: $color_scheme";
}


//if($_SESSION['superadmin']=='Y'){
    //$color_scheme = "darkmode";
//}


if( $color_scheme=='dark alley'){
    
    $global_textcolor = 'white';
    $global_textcolor2 = 'whitesmoke';
    $global_background = '#2b2b2b';
    $global_web_background = '#1C1A35';
    $global_background2 = '#3e4749';
    $global_backgroundreverse = 'whitesmoke';
    
    
    $global_banner_color = '#1b1b1b';//gray
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#6C7a89';//lynch
    $global_profile_color = '#3b3b3b';//
    $global_profiletext_color = 'white';//
    $global_titlebar_alt_color = '#4D5B60';//
    //$global_titlebar_color = 'black';//gray
    $global_titlebar_color = '#6C7a89';//lynch gray
    $global_bottombar_color = '#1b1b1b';//dark gray
    $global_separator_color = "#6C7a89"; // lynch gray
    //$global_activetextcolor = '#89c4f4'; 
    $global_activetextcolor = '#a1caf1';//7092be'; 
    $global_activetextcolor_reverse = '#89c4f4'; //facebook 
    $global_activetextcolor_onwhite = '#89c4f4';//7092be'; 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#ebf5ff"; //darkblue
    $icon_darkmode = true;
    $iconsource_braxmedal_common = "../img/medal-lynchgray-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-gray-128.png";        
    $iconsource_braxform_common = "../img/form-white-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
}
if($color_scheme=='metal night'  ){
    
    $global_textcolor = 'white';
    $global_textcolor2 = 'whitesmoke';
    $global_background = '#2b2b2b';
    //$global_web_background = '#2b2b2b';
    $global_web_background = '#1b1b1b';
    $global_background2 = '#3e4749';
    $global_backgroundreverse = 'whitesmoke';
    
    
    $global_banner_color = '#1b1b1b';//gray
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#6C7a89';//lynch
    $global_profile_color = '#3b3b3b';//
    $global_profiletext_color = 'white';//
    $global_titlebar_alt_color = '#4D5B60';//
    //$global_titlebar_color = 'black';//gray
    $global_titlebar_color = '#6C7a89';//lynch gray
    $global_bottombar_color = '#1b1b1b';//dark gray
    $global_separator_color = "#6C7a89"; // lynch gray
    //$global_activetextcolor = '#89c4f4'; 
    $global_activetextcolor = '#a1caf1';//7092be'; 
    $global_activetextcolor_reverse = '#89c4f4'; //facebook 
    $global_activetextcolor_onwhite = '#89c4f4'; //facebook 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#ebf5ff"; //darkblue
    $icon_darkmode = true;
    $iconsource_braxmedal_common = "../img/medal-lynchgray-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-gray-128.png";        
    $iconsource_braxform_common = "../img/form-white-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
}
if($color_scheme == 'moonlit night' || 
   $color_scheme=='starry night' ){
    
    $global_textcolor = 'white';
    $global_textcolor2 = 'whitesmoke';
    $global_background = '#2b2b2b';
    $global_web_background = '#1f1e2f';
    $global_background2 = '#3e4749';
    $global_backgroundreverse = 'whitesmoke';
    
    
    $global_banner_color = '#1b1b1b';//gray
    $global_menu_color = '#202020';//gray
    $global_menu2_color = '#202020';//lynch
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_titlebar_alt_color = '#4D5B60';//
    //$global_titlebar_color = 'black';//gray
    $global_titlebar_color = '#6C7a89';//lynch gray
    $global_bottombar_color = '#1b1b1b';//dark gray
    $global_separator_color = "#6C7a89"; // lynch gray
    //$global_activetextcolor = '#89c4f4'; 
    $global_activetextcolor = '#a1caf1';//7092be'; 
    $global_activetextcolor_reverse = '#89c4f4'; //facebook 
    $global_activetextcolor_onwhite = 'steelblue'; //facebook 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#ebf5ff"; //darkblue
    $icon_darkmode = true;
    $iconsource_braxmedal_common = "../img/medal-lynchgray-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-gray-128.png";        
    $iconsource_braxform_common = "../img/form-white-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
}
if($color_scheme == 'crimson night'){
    
    $global_textcolor = 'white';
    $global_textcolor2 = 'whitesmoke';
    $global_background = '#505050'; //Gray
    $global_web_background = '#4d0000'; //Crimson Dark
    $global_background2 = '#3e4749';
    $global_backgroundreverse = 'whitesmoke';
    
    
    $global_banner_color = '#1b1b1b';//gray
    $global_menu_color = '#202020';//dark gray
    $global_menu2_color = '#202020';//dark gray
    $global_profile_color = '#606060';//
    $global_profiletext_color = 'white';//
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#c70039';//crimson
    $global_bottombar_color = '#202020';//dark gray
    $global_separator_color = "gray"; //  gray
    $global_activetextcolor = 'pink'; 
    $global_activetextcolor_reverse = 'lightpink'; //facebook 
    $global_activetextcolor_onwhite = 'darkred'; //facebook 
    $global_store_color = '#ffcc00';
    
    
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-red-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-red-512.png' style='position:relative;top:8px;' />";

    $global_streamlive_color = 'steelblue';

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#ebf5ff"; //darkblue
    $icon_darkmode = true;
    $iconsource_braxmedal_common = "../img/medal-red-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-red-128.png";        
    $iconsource_braxform_common = "../img/form-white-128.png";        
    $iconsource_braxcheck_common = "../img/check-red-128.png";
}
if($color_scheme == 'grape night'){
    
    $global_textcolor = 'white';
    $global_textcolor2 = 'whitesmoke';
    $global_background = '#2b2b2b';
    $global_web_background = '#4d0028';
    $global_background2 = '#3e4749';
    $global_backgroundreverse = 'whitesmoke';
    $global_store_color = '#ffcc00';
   
    
    $global_banner_color = '#1b1b1b';//gray
    $global_menu_color = '#202020';//gray
    $global_menu2_color = '#202020';//lynch
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#674172';//lavender
    //$global_bottombar_color = '#33001a';//4d0028';//gray
    $global_bottombar_color = '#1b1b1b';//dark gray
    $global_separator_color = "gray"; //  gray
    $global_activetextcolor = 'pink'; 
    $global_activetextcolor_reverse = 'pink'; //facebook 
    $global_activetextcolor_onwhite = 'purple'; //facebook 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-purple-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-purple-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-purple-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-purple-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#ebf5ff"; //darkblue
    $icon_darkmode = true;
    $iconsource_braxmedal_common = "../img/medal-lavender-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-lavender-128.png";        
    $iconsource_braxform_common = "../img/form-white-128.png";        
    $iconsource_braxcheck_common = "../img/check-purple-128.png";
}
if($color_scheme == 'school night'){
    
    $global_textcolor = 'white';
    $global_textcolor2 = 'whitesmoke';
    $global_background = '#2b2b2b';
    $global_web_background = '#4d0028';
    $global_background2 = '#3e4749';
    $global_backgroundreverse = 'whitesmoke';
    $global_store_color = '#ffcc00';
   
    
    $global_banner_color = '#1b1b1b';//gray
    $global_menu_color = '#202020';//gray
    $global_menu2_color = '#202020';//lynch
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#c70039';//crimson
    //$global_bottombar_color = '#33001a';//4d0028';//gray
    $global_bottombar_color = '#1b1b1b';//dark gray
    $global_separator_color = "gray"; //  gray
    $global_activetextcolor = 'pink'; 
    $global_activetextcolor_reverse = '#FFCCCC'; //light red 
    $global_activetextcolor_onwhite = '#4d0028'; //facebook 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-red-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-red-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#ebf5ff"; //darkblue
    $icon_darkmode = true;
    $iconsource_braxmedal_common = "../img/medal-red-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-red-128.png";        
    $iconsource_braxform_common = "../img/form-white-128.png";        
    $iconsource_braxcheck_common = "../img/check-red-128.png";
}

if($color_scheme == 'forest night'){
    $global_textcolor = 'white';
    $global_textcolor2 = 'whitesmoke';

    $global_banner_color = '#1b1b1b';//gray
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#3e4749';//lynch
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    
    $global_background = '#2b2b2b';
    $global_web_background = '#111B15';
    $global_background2 = '#3e4749';
    $global_backgroundreverse = 'whitesmoke';

    
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#0E6655';//downygreen
    $global_bottombar_color = '#3e4749';//gray
    $global_separator_color = "#1ba39c"; //
    $global_activetextcolor = 'lightgreen'; 
    $global_activetextcolor_reverse = '#65C6BB'; 
    $global_activetextcolor_onwhite = 'darkgreen'; //facebook 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-bluegreen-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-bluegreen-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-bluegreen-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-bluegreen-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "whitesmoke"; //darkgreen
    $iconsource_braxmedal_common = "../img/medal-downygreen-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-downygreen-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-bluegreen-128.png";
    
    $icon_darkmode = true;
}



if( $color_scheme == 'crimson'){
    $global_banner_color = 'black';//gray
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#3e4749';//gray
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#c70039';//red
    $global_bottombar_color = '#2b2b2b';//dark gray
    $global_separator_color = "#eaaa20"; //orange
    $global_separator_color = "#c70039"; //red
    $global_activetextcolor = '#c70039'; 
    $global_activetextcolor_reverse = 'pink'; 
    $global_activetextcolor_onwhite = 'darkred'; 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-red-512.png' style='position:relative;top:8px;' />";
    $global_background2 = 'whitesmoke';
    $global_store_color = '#ffcc00';
    
    $global_streamlive_color = 'steelblue';

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $iconsource_braxmedal_common = "../img/medal-red-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-red-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-red-128.png";
    
}
if($color_scheme == 'newyorkpink'){
    $global_banner_color = 'black';//gray
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#e08283';//gray
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#e08283';//red
    $global_bottombar_color = '#3e4749';//gray
    $global_separator_color = "#8c586b"; //orange
    $global_separator_color = "#8c586b"; //red
    $global_activetextcolor = 'darkred'; 
    $global_activetextcolor_reverse = 'pink'; 
    $global_activetextcolor_onwhite = 'darkred'; 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-pink-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-pink-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-pink-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-pink-512.png' style='position:relative;top:8px;' />";
    $global_background2 = 'whitesmoke';
    $global_chatself_color = "#5e3b48"; //darkred

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $iconsource_braxmedal_common = "../img/medal-pink-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-peach-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-pink-128.png";
    
}
if($color_scheme == 'lavender'){
    $global_banner_color = 'black';//gray
    //$global_menu_color = '#3e4749';//gray
    $global_menu_color = '#84817a';//gray
    
    $global_menu2_color = '#674172';//gray
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = '#faf9ee';
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#674172';//lavender
    $global_bottombar_color = '#2b2b2b';//dark gray
    $global_separator_color = "purple"; //
    $global_activetextcolor = 'purple'; 
    $global_activetextcolor_reverse = 'pink'; 
    $global_activetextcolor_onwhite = 'purple'; 
    $global_store_color = 'red';
    
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-purple-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-purple-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-purple-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-purple-512.png' style='position:relative;top:8px;' />";
    $global_background2 = 'whitesmoke';

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $iconsource_braxmedal_common = "../img/medal-lavender-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-lavender-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-purple-128.png";
    
}
if($color_scheme == 'downygreen'){
    $global_banner_color = 'black';//gray
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#65C6BB';//downy green
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = '#faf9ee';
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#65C6BB';//downygreen
    $global_bottombar_color = '#3e4749';//gray
    $global_separator_color = "#1ba39c"; //
    $global_activetextcolor = '#669999'; 
    //$global_activetextcolor_reverse = '#65C6BB'; 
    $global_activetextcolor_reverse = 'lightyellow'; 
    $global_activetextcolor_onwhite = 'darkgreen'; 
    
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-bluegreen-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-bluegreen-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-bluegreen-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-bluegreen-512.png' style='position:relative;top:8px;' />";
    $global_background2 = 'whitesmoke';

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#003300"; //darkgreen
    $iconsource_braxmedal_common = "../img/medal-downygreen-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-downygreen-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-bluegreen-128.png";
    
}

if($color_scheme == 'tuscany'){
    $global_banner_color = 'black';//gray
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#F39C12';//downy green
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#F39C12';//downygreen
    $global_bottombar_color = '#3e4749';//gray
    $global_separator_color = "#F39C12"; //
    $global_activetextcolor = '#FF5733'; 
    $global_activetextcolor_reverse = '#65C6BB'; 
    $global_activetextcolor_onwhite = 'darkred'; 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-yellow-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-yellow-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-yellow-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-yellow-512.png' style='position:relative;top:8px;' />";
    $global_background2 = 'whitesmoke';

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#7B241C"; //darkgreen
    $iconsource_braxmedal_common = "../img/medal-downygreen-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-tuscany-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-yellow-128.png";
    
}

if($color_scheme == 'rustyred'){
    $global_banner_color = 'black';//gray
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#C0392B';//downy green
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#C0392B';//downygreen
    $global_bottombar_color = '#3e4749';//gray
    $global_separator_color = "#C0392B"; //
    $global_activetextcolor = '#FF5733'; 
    $global_activetextcolor_reverse = '#65C6BB'; 
    $global_activetextcolor_onwhite = 'darkred'; 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-yellow-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-yellow-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-yellow-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-yellow-512.png' style='position:relative;top:8px;' />";
    $global_background2 = 'whitesmoke';
    $global_store_color = '#ffcc00';

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#7B241C"; //darkgreen
    $iconsource_braxmedal_common = "../img/medal-orange-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-rustyred-128.png";        
    $iconsource_braxcheck_common = "../img/check-yellow-128.png";
    
}

if($color_scheme == 'beach'){
    $global_banner_color = 'black';//gray
    $global_menu_color = '#202020';//gray
    $global_menu2_color = '#67809F';//gray
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'whitesmoke';
    $global_web_background = 'white';
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#67809F';//lavender
    $global_bottombar_color = '#2b2b2b';//dark gray
    $global_separator_color = "#67809F"; //
    $global_activetextcolor = '#67809F'; 
    $global_activetextcolor = '#6699ff'; //neon purple blue
    $global_activetextcolor_reverse = 'gold'; 
    $global_activetextcolor_onwhite = 'darkblue'; 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";
    $global_background2 = 'whitesmoke';

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $iconsource_braxmedal_common = "../img/medal-lynchgray-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-bluegray-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
    
}
if($color_scheme == 'bluegray'){
    $global_banner_color = 'black';//gray
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#67809F';//gray
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = '#faf9ee';
    $global_titlebar_alt_color = '#3e4749';//
    $global_titlebar_color = '#67809F';//lavender
    $global_bottombar_color = '#2b2b2b';//dark gray
    $global_separator_color = "steelblue"; //
    $global_activetextcolor = 'steelblue'; 
    $global_activetextcolor_reverse = 'gold'; 
    $global_activetextcolor_onwhite = 'steelblue'; 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";
    $global_background2 = 'whitesmoke';

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'white';
    $iconsource_braxmedal_common = "../img/medal-lynchgray-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-bluegray-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
    
}
if($color_scheme == 'bluesmoke' ){
    
    $global_banner_color = 'black';//gray
    $global_menu_color = 'whitesmoke';//gray
    $global_menu2_color = '#3e4749';//gray
    $global_profile_color = 'white';//
    $global_profiletext_color = 'black';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    $global_titlebar_alt_color = '#3e4749';//gray
    $global_titlebar_color = '#67809F';//lavender
    $global_bottombar_color = '#2b2b2b';//dark gray
    $global_separator_color = "#67809F"; //
    $global_activetextcolor = '#67809F'; 
    $global_activetextcolor_reverse = '#67809F'; 
    $global_activetextcolor_onwhite = 'darkblue'; 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";
    $global_background2 = 'whitesmoke';

    $global_dominant_color = $global_titlebar_color;
    $icon_scheme = 'black';
    $iconsource_braxmedal_common = "../img/medal-lynchgray-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-bluegray-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
    
}

if($color_scheme == 'lawn'){
    $global_banner_color = '#3e4749';//slate
    $global_menu_color = '#1a9955';//lawn
    $global_menu2_color = '#1a9955';//lawn
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    $global_titlebar_alt_color = '#65C6BB';//skyblue
    $global_titlebar_color = '#3e4749';//gray
    $global_bottombar_color = '#3e4749';//gray
    $global_separator_color = "#eaaa20"; //orange
    $global_activetextcolor = 'lightgreen'; 
    $global_activetextcolor_reverse = 'lightgreen'; 
    $global_activetextcolor_onwhite = 'darkgreen'; 
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-bluegreen-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-bluegreen-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-green-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-bluegreen-512.png' style='position:relative;top:8px;' />";
    $global_activetextcolor = 'seagreen'; 

    $global_dominant_color = $global_menu_color;
    $icon_scheme = 'white';
    $global_chatself_color = "#003300"; //darkgreen
    $iconsource_braxmedal_common = "../img/medal-green-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-gray-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-bluegreen-128.png";
}
if($color_scheme == 'skyblue'){
    $global_banner_color = 'black';//blue dark
    $global_menu_color = '#227093';//skyblue
    $global_menu2_color = '#227093';//skyblue
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = '#ebebeb';
    $global_web_background = '#faf9ee';
    //$global_titlebar_alt_color = '#49a942';//lawn
    //$global_titlebar_color = '#2574a8';//michigan
    //$global_titlebar_color = 'green';//michigan
    $global_titlebar_color = 'steelblue';//
    $global_titlebar_alt_color = '#224170';//darkblue
    $global_bottombar_color = '#3e4749';//gray
    //$global_activetextcolor = '#22a7f0'; 
    $global_activetextcolor = 'steelblue'; 
    
    $global_activetextcolor_reverse = 'gold'; 
    $global_activetextcolor_onwhite = 'steelblue'; 
    //$global_separator_color = "#22a7f0"; //
    $global_separator_color = "#2574a8"; //lawn
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_menu_color;
    $icon_scheme = 'white';
    $iconsource_braxmedal_common = "../img/medal-skyblue-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-bluegray-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
}
if($color_scheme == 'riverblue'){
    $global_banner_color = 'black';//blue dark
    $global_menu_color = '#3c6382';//skyblue
    $global_menu2_color = '#3c6382';//skyblue
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    //$global_titlebar_alt_color = '#49a942';//lawn
    //$global_titlebar_color = '#2574a8';//michigan
    //$global_titlebar_color = 'green';//michigan
    $global_titlebar_color = '#1B4F72';//
    $global_titlebar_alt_color = '#3c6382';//darkblue
    $global_bottombar_color = '#3e4749';//gray
    //$global_activetextcolor = '#22a7f0'; 
    $global_activetextcolor = 'steelblue'; 
    
    $global_activetextcolor_reverse = 'gold'; 
    $global_activetextcolor_onwhite = 'steelblue'; 
    //$global_separator_color = "#22a7f0"; //
    $global_separator_color = "#2574a8"; //lawn
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_menu_color;
    $icon_scheme = 'white';
    $iconsource_braxmedal_common = "../img/medal-skyblue-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-bluegray-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
}
if($color_scheme == 'midnightblue'){
    $global_banner_color = 'black';//blue dark
    $global_menu_color = '#3e4749';//skyblue
    $global_menu2_color = '#808B96';//skyblue
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    //$global_titlebar_alt_color = '#49a942';//lawn
    //$global_titlebar_color = '#2574a8';//michigan
    //$global_titlebar_color = 'green';//michigan
    $global_titlebar_color = '#2C3E50';//
    $global_titlebar_alt_color = '#224170';//darkblue
    $global_bottombar_color = '#3e4749';//gray
    //$global_activetextcolor = '#22a7f0'; 
    $global_activetextcolor = 'steelblue'; 
    
    $global_activetextcolor_reverse = 'gold'; 
    $global_activetextcolor_onwhite = 'steelblue'; 
    //$global_separator_color = "#22a7f0"; //
    $global_separator_color = "#2574a8"; //lawn
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_menu_color;
    $icon_scheme = 'white';
    $iconsource_braxmedal_common = "../img/medal-skyblue-128.png";        
    $iconsource_braxgiftround_common = "../img/gift-bluegray-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
}
if($color_scheme == 'dte'){
    $global_banner_color = '#224170';//blue dark
    $global_menu_color = '#04acec';//skyblue
    $global_menu2_color = '#04acec';//skyblue
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    //$global_titlebar_color = '#2574a8';//michigan
    $global_titlebar_color = 'green';//michigan
    $global_titlebar_alt_color = '#224170';//lawn
    $global_bottombar_color = '#3e4749';//gray
    $global_activetextcolor = '#22a7f0'; 
    $global_activetextcolor_reverse = 'gold'; 
    $global_activetextcolor_onwhite = 'steelblue'; 
    //$global_separator_color = "#22a7f0"; //
    $global_separator_color = "#2574a8"; //lawn
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-lightblue-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_heart = "<img class='icon15' title='Liked' src='../img/heart-lightblue-128.png' style='position:relative;top:8px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-lightblue-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_menu_color;
    $icon_scheme = 'white';
    $iconsource_braxgiftround_common = "../img/gift-dte-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-lightblue-128.png";
}
if($color_scheme == 'michigan'){
    
    
    $global_banner_color = 'black';//black
    $global_menu_color = '#3e4749';//gray
    $global_menu2_color = '#2574a8';//michigan
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    $global_titlebar_alt_color = '#3e4749';//gray
    
    $global_titlebar_color = '#2574a8';//michigan
    $global_bottombar_color = '#2b2b2b';//dark gray
    $global_activetextcolor = 'steelblue'; 
    $global_activetextcolor_reverse = 'gold'; 
    $global_activetextcolor_onwhite = 'steelblue'; 
    $global_separator_color = "steelblue"; //
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-red-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_menu_color;
    $icon_scheme = 'white';
    $iconsource_braxgiftround_common = "../img/gift-michigan-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-red-128.png";
}
if($color_scheme == 'facebook'){
    $global_banner_color = 'black';//black
    $global_menu_color = '#45619d';//facebook
    $global_menu2_color = '#45619d';//facebook
    $global_profile_color = '#1b1b1b';//
    $global_profiletext_color = 'white';//
    $global_background = 'white';
    $global_web_background = 'whitesmoke';
    $global_titlebar_alt_color = '#3e4749';//skyblue
    $global_titlebar_color = '#3e4749';//gray
    $global_bottombar_color = '#2b2b2b';//dark gray
    $global_activetextcolor = '#45619d'; 
    $global_activetextcolor_reverse = 'gold'; 
    $global_activetextcolor_onwhite = 'steelblue'; 
    $global_separator_color = "#45619d"; //
    $global_icon_check = "<img class='icon15' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_check_blink = "<img class='icon15 blink' title='Checked' src='../img/check-red-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $global_icon_pin = "<img class='icon15' title='Pin' src='../img/pin-red-512.png' style='position:relative;top:8px;' />";

    $global_dominant_color = $global_menu_color;
    $icon_scheme = 'white';
    $iconsource_braxgiftround_common = "../img/gift-facebook-128.png";        
    $iconsource_braxform_common = "../img/form-black-128.png";        
    $iconsource_braxcheck_common = "../img/check-red-128.png";
}


if($icon_scheme == 'white'){
    //Color Specifics
    $global_menu_text_color = 'white';
    $icon_braxmenu =    "<img class='icon30'  title='Main Menu' src='../img/brax-menu-round-white-128.png' />";
    $icon_braxlive =    "<img class='icon30'  title='Live' src='../img/brax-live-round-white-128.png' />";
    $icon_braxchat =    "<img class='icon30'  title='Chat' src='../img/brax-chat-round-white-128.png' />";
    $icon_braxroom =    "<img class='icon30'  title='Rooms' src='../img/brax-room-round-white-128.png'  />";
    $icon_braxphoto =  "<img class='icon30'  title='My Photos' src='../img/brax-photo-round-white-128.png' />";
    $icon_braxdoc =    "<img class='icon30'  title='My Files' src='../img/brax-doc-round-white-128.png'  />";
    $icon_braxpeople =    "<img class='icon30'  title='Find People' src='../img/people-circle-white-128.png'  />";
    $icon_braxsettings =    "<img class='icon30'  title='Settings' src='../img/settings-circle-128.png' />";
    $icon_braxidentity =    "<img class='icon30'  title='Identity' src='../img/brax-identity-round-white-128.png' />";
    $icon_braxsecurity =    "<img class='icon30'  title='Store' src='../img/brax-security-round-white-128.png'  />";
    $icon_braxfaq =    "<img class='icon30'  title='FAQ' src='../img/brax-faq-white.png'  />";

    $icon_braxmenu2 =    "<img class='icon15'  title='Main Menu' src='../img/brax-menu-round-white-128.png' style='position:relative;top:3px'  />";
    $icon_braxlive2 =    "<img class='icon15'  title='Live' src='../img/brax-live-round-white-128.png' style='position:relative;top:3px' />";
    $icon_braxchat2 =    "<img class='icon15'  title='Chat' src='../img/brax-chat-round-white-128.png' style='position:relative;top:3px' />";
    $icon_braxroom2 =    "<img class='icon15'  title='Rooms' src='../img/brax-room-round-white-128.png' style='position:relative;top:3px'  />";
    $icon_braxphoto2 =  "<img class='icon15'  title='My Photos' src='../img/brax-photo-round-white-128.png' style='position:relative;top:3px' />";
    $icon_braxdoc2 =    "<img class='icon15'  title='My Files' src='../img/brax-doc-round-white-128.png' style='position:relative;top:3px'  />";
    $icon_braxpeople2 =    "<img class='icon15'  title='Find People' src='../img/people-circle-white-128.png' style='position:relative;top:3px'  />";
    $icon_braxsettings2 =    "<img class='icon15'  title='Settings' src='../img/brax-settings-round-white-128.png' style='position:relative;top:3px' />";
    $icon_braxidentity2 =    "<img class='icon15'  title='Identity' src='../img/brax-identity-round-white-128.png' style='position:relative;top:3px' />";
    $icon_braxstore2 =    "<img class='icon15'  title='Store' src='../img/store-circle-128.png' style='position:relative;top:3px' />";

    $icon_braxsecurity2 =    "<img class='icon15'  title='Store' src='../img/brax-security-round-white-128.png' style='position:relative;top:3px' />";
    
    $icon_braxstop =    "<img class='icon30' src='../img/Stop-Music-White_120px.png'  />";
    $icon_braxlogout =    "<img class='icon30' src='../img/logout-circle-128.png' />";
    $icon_braxlock =    "<img class='icon30' src='../img/brax-lock-128.png' />";

    $icon_braxcar =    "<img class='icon30'  title='Car' src='../img/brax-car-white-128.png'  />";
    $icon_braxstore =    "<img class='icon30'  title='Car' src='../img/brax-store-round-white.png'  />";
   
    $iconsource_braxrestart_common = "../img/Restart-White-128.png";
    $iconsource_braxalbum_common = "../img/album-white-512.png";
}
if($icon_scheme == 'black'){
    
    $global_menu_text_color = 'black';
    $icon_braxmenu =    "<img class='icon30'  title='Main Menu' src='../img/brax-menu-round-black-128.png' />";
    $icon_braxlive =    "<img class='icon30'  title='Live' src='../img/brax-live-round-black-128.png' />";
    $icon_braxchat =    "<img class='icon30'  title='Chat' src='../img/brax-chat-round-black-128.png' />";
    $icon_braxroom =    "<img class='icon30'  title='Rooms' src='../img/brax-room-round-black-128.png'  />";
    $icon_braxphoto =  "<img class='icon30'  title='My Photos' src='../img/brax-photo-round-black-128.png' />";
    $icon_braxdoc =    "<img class='icon30'  title='My Files' src='../img/brax-doc-round-black-128.png'  />";
    $icon_braxpeople =    "<img class='icon30'  title='Find People' src='../img/brax-people-round-black-128.png'  />";
    $icon_braxsettings =    "<img class='icon30'  title='Settings' src='../img/brax-settings-round-black-128.png' />";
    $icon_braxsecurity =    "<img class='icon30'  title='Store' src='../img/brax-security-round-black-128.png'  />";
    $icon_braxfaq =    "<img class='icon30'  title='FAQ' src='../img/brax-faq-black.png'  />";

    
    $icon_braxmenu2 =    "<img class='icon15'  title='Main Menu' src='../img/brax-menu-round-black-128.png' />";
    $icon_braxlive2 =    "<img class='icon15'  title='Live' src='../img/brax-live-round-black-128.png' />";
    $icon_braxchat2 =    "<img class='icon15'  title='Chat' src='../img/brax-chat-round-black-128.png' />";
    $icon_braxroom2 =    "<img class='icon15'  title='Rooms' src='../img/brax-room-round-black-128.png'  />";
    $icon_braxphoto2 =  "<img class='icon15'  title='My Photos' src='../img/brax-photo-round-black-128.png' />";
    $icon_braxdoc2 =    "<img class='icon15'  title='My Files' src='../img/brax-doc-round-black-128.png'  />";
    $icon_braxpeople2 =    "<img class='icon15'  title='Find People' src='../img/brax-people-round-black-128.png'  />";
    $icon_braxsettings2 =    "<img class='icon15'  title='Settings' src='../img/brax-settings-round-black-128.png' />";
    $icon_braxidentity2 =    "<img class='icon15'  title='Identity' src='../img/brax-identity-round-black-128.png' />";
    $icon_braxsecurity2 =    "<img class='icon15'  title='Store' src='../img/brax-security-round-black-128.png' style='position:relative;top:3px' />";
    
    $icon_braxstop =    "<img class='icon30' src='../img/Stop-Music-White_120px.png'  />";
    $icon_braxlogout =    "<img class='icon30' src='../img/logout-circle-128.png' />";
    
    $icon_braxcar =    "<img class='icon30'  title='Car' src='../img/brax-car-black-128.png'  />";
    $icon_braxstore =    "<img class='icon30'  title='Car' src='../img/brax-store-round-black.png'  />";
    $iconsource_braxalbum_common = "../img/album-black-512.png";
    
    //$icon_braxstop =    "<img class='icon25' src='../img/Stop-Music_120px.png'  />";
    //$icon_braxlogout =    "<img class='icon25' src='../img/logout-black-128.png' />";
}

if($icon_darkmode == false){
    $iconsource_braxmenu = "../img/brax-menu-round-black-128.png";
    $iconsource_braxcamera = "../img/brax-camera-round-black-128.png";
    
    $iconsource_braxpeople_common = "../img/people-circle-128.png";
    $iconsource_braxfind_common = "../img/find-circle-01-128.png";
    $iconsource_braxarrowright_common = "../img/Arrow-Right-in-Circle_120px.png";
    $iconsource_braxarrowleft_common = "../img/Arrow-Left-in-Circle_120px.png";
    $iconsource_braxpen_common = "../img/Pen-4_120px.png";
    $iconsource_braxclose_common = "../img/Close_120px.png";
    $iconsource_braxeraser_common = "../img/Eraser-3_120px.png";
    $iconsource_braxrestore_common = "../img/Restore-Window_120px.png";
    $iconsource_braxjoin_common = "../img/join-circle-gray-128.png";
    $iconsource_braxhelp_common = "../img/help-circle-128.png";
    $iconsource_braxstopmusic_common = "../img/Stop-Music_120px.png";
    $iconsource_braxplaymusic_common = "../img/Play-Music_120px.png";
    $iconsource_braxbell_common = "../img/Bell_120px.png";
    $iconsource_braxlike_common = "../img/Like_120px.png";
    $iconsource_braxunlike_common = "../img/Unlike_120px.png";
    $iconsource_braxvideo_common = "../img/Video-Tripod_120px.png";
    
    $iconsource_braxchat_common = "../img/brax-chat-round-128.png";
    
    $iconsource_braxaddressbook_common = "../img/Address-Book_120px.png";
    $iconsource_braxfolder_common = "../img/Folder_120px.png";
    $iconsource_braxinvite_common = "../img/Share_120px.png";
    $iconsource_braxrefresh_common = "../img/Refresh_120px.png";
    $iconsource_braxgear_common = "../img/Gear_120px.png";
    $iconsource_braxchatbubble_common = "../img/Speach-Bubble_120px.png";
    
    $iconsource_braxarrowup_common = "../img/arrow-circle-up-128.png";
    $iconsource_braxarrowdown_common = "../img/arrow-circle-down-128.png";
    $iconsource_braxradiotower_common = "../img/Communication-Tower-2_120px.png";
    $iconsource_braxlock_common = "../img/Lock-2_120px.png";    
    $iconsource_braxlink_common = "../img/link-circle-128.png";
    
    $iconsource_braxadd_common = "../img/add-circle-128.png";
    $iconsource_braxcredentials_common = "../img/credentials-128.png";
    $iconsource_braxwarning_common = "../img/warning-black-128.png";
    $iconsource_braxglobe_common = "../img/globe-128.png";
    $iconsource_braxgift_common = "../img/gift-128.png";
    $iconsource_braxphone_common = "../img/phone-128.png";
    $iconsource_braxtextphoto_common = "../img/text-photo-128.png";
    $iconsource_braxupload_common = "../img/upload-circle-128.png";
    $iconsource_braxdownload_common = "../img/download-circle-128.png";
    $iconsource_braxsettings_common = "../img/settings-circle-gray-128.png";
    $iconsource_braxzoom_common = "../img/Zoom-128.png";
    $iconsource_braxtasks_common = "../img/tasks-circle-128.png";
    $iconsource_braxshare_common = "../img/share-circle-128.png";
    $iconsource_braxlogout_common = "../img/logout-black-128.png";
    $iconsource_braxlock_common = "../img/lock-circle-128.png";
    $iconsource_braxrestart_common = "../img/Restart-128.png";
    $iconsource_braxedit_common = "../img/pencil-white-48.png";
    $iconsource_braxpin_common = "../img/pin-black-512.png";
    $iconsource_braxmoderator_common = "../img/moderator-black-512.png";
    $iconsource_braxwinner_common = "../img/winner-black-512.png";
    $iconsource_braxtasks_common = "../img/tasks-circle-128.png";
    

    //desk
    $fixed_background_image = '../img/background-keyboard-desk.jpg';
    $fixed_background_image_style = 'width:100%;opacity:0.2';
    $fixed_background_image_mobile = '../img/background-keyboard-desk.jpg';
    $fixed_background_image_mobile_style = 'height:100%;opacity:0.2';
    

    //silk
    $fixed_background_image = '../img/background-silky-white.jpg';
    $fixed_background_image_style = 'width:100%;opacity:0.5';
    $fixed_background_image_mobile = '../img/background-silky-white-inverted.png';
    $fixed_background_image_mobile_style = 'width:100%;height:100%;opacity:0.5';
    
    
    
    //pine
    $fixed_background_image = '../img/background-pine-texture.jpg';
    $fixed_background_image_style = 'width:100%;opacity:0.5';
    $fixed_background_image_mobile = '../img/background-pine-texture.jpg';
    $fixed_background_image_mobile_style = 'height:100%;width:100%;opacity:0.6';

    
    $fixed_background_image = '../img/background-silky-white.jpg';
    $fixed_background_image_style = 'width:100%;height:100%;opacity:0.5';
    $fixed_background_image_mobile = '../img/background-pine-texture.jpg';
    $fixed_background_image_mobile_style = 'height:100%;width:100%;opacity:0.4';

    if($color_scheme=='riverblue'){
        $fixed_background_image = '../img/background-blue-pattern.jpg';
        $fixed_background_image_style = 'height:100%;width:100%;opacity:0.2';
        $fixed_background_image_mobile = '../img/background-blue-pattern.jpg';
        $fixed_background_image_mobile_style = 'width:100%;height:100%;opacity:0.2';
    }
    
    if($color_scheme=='bluegray'){
        $fixed_background_image = '../img/background-blue-pattern.jpg';
        $fixed_background_image_style = 'height:100%;width:100%;opacity:0.2';
        $fixed_background_image_mobile = '../img/background-blue-pattern.jpg';
        $fixed_background_image_mobile_style = 'width:100%;height:100%;opacity:0.2';
        
        //$fixed_background_image = '../img/background-wavy-beige.jpg';
        //$fixed_background_image_style = 'height:100%;width:100%;opacity:0.2';
        //$fixed_background_image_mobile = '../img/background-wavy-beige.jpg';
        //$fixed_background_image_mobile_style = 'width:100%;height:100%;opacity:0.2';
    }
    if($color_scheme=='beach'){
        //beach
        $fixed_background_image = '../img/background-beach.jpg';
        $fixed_background_image_style = 'width:100%;opacity:0.4';
        $fixed_background_image_mobile = '../img/background-beach-cut.jpg';
        $fixed_background_image_mobile_style = 'height:100%;width:100%;opacity:0.4';
        
    }

    if($color_scheme=='lavender' ||
        $color_scheme=='newyorkpink' ||
        $color_scheme=='crimson' ||
        $color_scheme=='downygreen' 
           ){
        $fixed_background_image = '../img/background-silky-white.jpg';
        $fixed_background_image_style = 'width:100%;opacity:0.5';
        $fixed_background_image_mobile = '../img/background-silky-white-inverted.png';
        $fixed_background_image_mobile_style = 'width:100%;height:100%;opacity:0.5';
    }
    if($color_scheme=='tuscany'){
        //pine
        $fixed_background_image = '../img/background-marble.jpg';
        $fixed_background_image_style = 'width:100%;opacity:0.4';
        $fixed_background_image_mobile = '../img/background-marble.jpg';
        $fixed_background_image_mobile_style = 'height:100%;width:100%;opacity:0.5';
    }
    if($color_scheme=='rustyred'){
        //pine
        $fixed_background_image = '../img/background-canyon.jpg';
        $fixed_background_image_style = 'width:100%;opacity:0.4';
        $fixed_background_image_mobile = '../img/background-canyon.jpg';
        $fixed_background_image_mobile_style = 'height:100%;width:100%;opacity:0.3';
    }
    
    
    
} else {
    $iconsource_braxmenu = "../img/brax-menu-round-white-128.png";
    $iconsource_braxcamera = "../img/brax-camera-round-white-128.png";
    
    $iconsource_braxpeople_common = "../img/people-circle-white-128.png";
    $iconsource_braxfind_common = "../img/find-circle-white-128.png";
    $iconsource_braxarrowright_common = "../img/Arrow-Right-in-Circle-White_120px.png";
    $iconsource_braxarrowleft_common = "../img/Arrow-Left-in-Circle-White_120px.png";
    $iconsource_braxpen_common = "../img/Pen-4-White_120px.png";
    $iconsource_braxclose_common = "../img/delete-circle-white-128.png";
    $iconsource_braxeraser_common = "../img/Eraser-3-White_120px.png";
    $iconsource_braxrestore_common = "../img/Restore-Window-White-128.png";
    $iconsource_braxjoin_common = "../img/join-circle-white-128.png";
    $iconsource_braxhelp_common = "../img/help-circle-white-128.png";
    $iconsource_braxstopmusic_common = "../img/Stop-Music-White_120px.png";
    $iconsource_braxplaymusic_common = "../img/Play-Music-White_120px.png";
    $iconsource_braxbell_common = "../img/Bell-White-128.png";
    $iconsource_braxlike_common = "../img/Like-White_120px.png";
    $iconsource_braxunlike_common = "../img/Unlike-White_120px.png";
    $iconsource_braxvideo_common = "../img/Video-Camera-White-128.png";
    
    $iconsource_braxchat_common = "../img/brax-chat-round-white-128.png";
    
    $iconsource_braxaddressbook_common = "../img/Address-Book-White_120px.png";
    $iconsource_braxfolder_common = "../img/Folder-White_120px.png";
    $iconsource_braxinvite_common = "../img/Arrow-Inside-White_120px.png";
    $iconsource_braxrefresh_common = "../img/refresh-circle-white-128.png";
    $iconsource_braxgear_common = "../img/settings-circle-128.png";
    $iconsource_braxchatbubble_common = "../img/chat-line-white-128.png";
    
    $iconsource_braxarrowup_common = "../img/arrow-circle-up-white-128.png";
    $iconsource_braxarrowdown_common = "../img/arrow-circle-down-white-128.png";
    $iconsource_braxradiotower_common = "../img/Communication-Tower-2-White_120px.png";
    $iconsource_braxlink_common = "../img/link-circle-white-128.png";

    $iconsource_braxadd_common = "../img/add-circle-white-128.png";
    $iconsource_braxcredentials_common = "../img/credentials-white-128.png";
    $iconsource_braxwarning_common = "../img/warning-white-128.png";
    $iconsource_braxglobe_common = "../img/globe-white-128.png";
    $iconsource_braxgift_common = "../img/gift-white-128.png";
    $iconsource_braxphone_common = "../img/phone-white-128.png";
    $iconsource_braxtextphoto_common = "../img/text-photo-white-128.png";
    $iconsource_braxupload_common = "../img/upload-circle-white-128.png";
    $iconsource_braxdownload_common = "../img/download-circle-white-128.png";
    $iconsource_braxsettings_common = "../img/settings-circle-white-128.png";
    $iconsource_braxzoom_common = "../img/Zoom-White-128.png";
    $iconsource_braxtasks_common = "../img/tasks-circle-white-128.png";
    $iconsource_braxshare_common = "../img/share-circle-white-128.png";
    $iconsource_braxlogout_common = "../img/logout-circle-128.png";
    $iconsource_braxlock_common = "../img/brax-lock-128.png";
    $iconsource_braxedit_common = "../img/pencil-black-48.png";
    $iconsource_braxpin_common = "../img/pin-white-512.png";
    $iconsource_braxmoderator_common = "../img/moderator-white-512.png";
    $iconsource_braxwinner_common = "../img/winner-white-512.png";
    $iconsource_braxtasks_common = "../img/tasks-circle-white-128.png";


    
    //$fixed_background_image = '../img/background-slate.jpg';
    $fixed_background_image = '../img/background-wool-gray2.jpg';
    $fixed_background_image_style = 'width:100%;filter:brightness(50%);';
    $fixed_background_image_mobile = '../img/background-wool-gray2.jpg';
    $fixed_background_image_mobile_style = 'height:100%;filter:brightness(80%);';
    
    $fixed_background_image = '../img/background-board-dark.jpg';
    $fixed_background_image_style = 'width:100%;filter:brightness(50%);';
    $fixed_background_image_mobile = '../img/background-board-dark.jpg';
    $fixed_background_image_mobile_style = 'height:100%;opacity:.3;brightness(50%)';

    if($color_scheme == 'forest night'){
        $fixed_background_image = '../img/background-forest.jpg';
        $fixed_background_image_style = 'width:100%;opacity:.3;filter:brightness(50%);';
        $fixed_background_image_mobile = '../img/background-forest.jpg';
        $fixed_background_image_mobile_style = 'height:100%;opacity:.2;brightness(30%)';
    }
    
   
    if($color_scheme == 'grape night'){
        $fixed_background_image = '../img/background-grapes.jpg';
        $fixed_background_image_style = 'width:100%;opacity:.2;filter:brightness(50%);';
        $fixed_background_image_mobile = '../img/background-grapes.jpg';
        $fixed_background_image_mobile_style = 'height:100%;opacity:.2;filter:brightness(50%);';
        
    }
    if($color_scheme == 'moonlit night'){
        $fixed_background_image = '../img/background-waves.jpg';
        $fixed_background_image_style = 'width:100%;opacity:.3;filter:brightness(50%);';
        $fixed_background_image_mobile = '../img/background-waves.jpg';
        $fixed_background_image_mobile_style = 'height:100%;opacity:.3;filter:brightness(50%);';
        
    }
    if($color_scheme == 'school night'){
        $fixed_background_image = '../img/background-waves.jpg';
        $fixed_background_image_style = 'width:100%;opacity:.3;filter:brightness(50%);';
        $fixed_background_image_mobile = '../img/background-waves.jpg';
        $fixed_background_image_mobile_style = 'height:100%;opacity:.3;filter:brightness(50%);';
        
    }
    
    if($color_scheme == 'crimson night'){
        $fixed_background_image = '../img/background-leather.jpg';
        $fixed_background_image_style = 'width:100%;opacity:.3;filter:brightness(30%);';
        $fixed_background_image_mobile = '../img/background-leather.jpg';
        $fixed_background_image_mobile_style = 'height:100%;opacity:.3;filter:brightness(50%)';
        
        
    }
    if($color_scheme == 'metal night'){
        $fixed_background_image = '../img/background-waves.jpg';
        $fixed_background_image_style = 'width:100%;filter:brightness(50%);';
        $fixed_background_image_mobile = '../img/background-waves.jpg';
        $fixed_background_image_mobile_style = 'height:100%;filter:brightness(50%);';
        
    }
    if($color_scheme == 'starry night'){
        $fixed_background_image = '../img/background-dark-nature.jpg';
        $fixed_background_image_style = 'width:100%;filter:brightness(50%);';
        $fixed_background_image_mobile = '../img/background-dark-nature.jpg';
        $fixed_background_image_mobile_style = 'height:100%;opacity:.3;filter:brightness(50%);';
        
    }
    if($color_scheme == 'dark alley'){
        $fixed_background_image = '../img/background-dark-alley.jpg';
        $fixed_background_image_style = 'width:100%;opacity:.5;filter:brightness(20%);';
        $fixed_background_image_mobile = '../img/background-dark-alley.jpg';
        $fixed_background_image_mobile_style = 'height:100%;filter:brightness(50%);';
        
    }
    
    
    
}

$display_background_image = "
<img class='nonmobile' src='$fixed_background_image' style='$fixed_background_image_style' />
<img class='formobile' src='$fixed_background_image_mobile' style='$fixed_background_image_mobile_style' />
    ";

if($wallpaper_scheme=='none'){
    $display_background_image = "";
}
if(isset($_SESSION['hostedmode']) && $_SESSION['hostedmode'] =='true'){
    //$display_background_image = "";
}

?>