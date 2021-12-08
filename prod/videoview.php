<?php
session_start();
require_once("validsession.inc.php");
require_once("config-pdo.php");
require_once("htmlhead.inc.php");
$providerid = @tvalidator("PURIFY",$_POST['pid']);
$url = @tvalidator("PURIFY",$_POST['url']);

$iframe = YouTube($url);
function YouTube($url )
{
    //Reformatting of Stored Video
    $pos1 = strpos(strtolower($url ),"//www.youtube.com/embed/");
    if($pos1 !== false)
    {
        $src = str_replace("'","",$url);
        $src = str_replace("></iframe>","",$src);
        $src = str_replace("<","",$src);
        $vars = explode("/",$src);
        
        
            $youtube = "<iframe class='youtube' src='//www.youtube.com/embed/".$vars[4]."?playsinline=0'></iframe>";
            
        
        return $youtube;
    }
    
    
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
        $youtube = "<iframe class='youtube' src='//www.youtube.com/embed/".$hold[0]."'></iframe>";
        return $youtube;
    }
    //format 2
    $pos1 = strpos(strtolower($url ),"//youtu.be/");
    if($pos1 !== false)
    {
        $vars = explode("/",$url);

        $youtube = "<iframe class='youtube' src='//www.youtube.com/embed/".$vars[3]."'></iframe>";
        return $youtube;
    }
    
    //format 5
    $pos1 = strpos(strtolower($url ),"//player.vimeo.com/video/");
    if($pos1 !== false)
    {
        $vars = explode("/",$url);

        $youtube = "<iframe class='youtube'  style='max-width:600px;' src='//player.vimeo.com/video/".$vars[4]."'></iframe>";
        return $youtube;
    }
    
    
    //format 4
    $pos1 = strpos(strtolower($url ),"vimeo.com/");
    if($pos1 !== false)
    {
        $vars = explode("/",$url);

        $youtube = "<iframe class='youtube' style='max-width:600px;' src='//player.vimeo.com/video/".$vars[3]."'></iframe>";
        return $youtube;
    }
    
    
}
?>
</head>
<body style="background-color:black;font-family:helvetica;font-size:12px;margin:0;padding:0">
    <?=$iframe?>
    <br>
    <?=$url?>
</body>
</html>
