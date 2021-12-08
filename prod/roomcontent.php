<?php
session_start();
require_once("config-pdo.php");
require("crypt-pdo.inc.php");
require("aws.php");

    
    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_SESSION['pid']);
    
    $roomid = @tvalidator("ID",$_POST['roomid']);
    $selectedfolder = @tvalidator("PURIFY",$_POST['folder']);
    $filename = @tvalidator("PURIFY",$_POST['filename']);
    $sort = @tvalidator("PURIFY",$_POST['sort']);
    $target = @tvalidator("PURIFY",$_POST['target']);
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $page = @intval( tvalidator("PURIFY",$_POST['page']));
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $altfilename = @tvalidator("PURIFY",$_POST['altfilename']);
    $filtername = @tvalidator("PURIFY",$_POST['filtername']);

    $backgroundcolor = @tvalidator("PURIFY",$_POST['backgroundcolor']);
    $color = @tvalidator("PURIFY",$_POST['color']);
    $trimcolor = @tvalidator("PURIFY",$_POST['trimcolor']);
    $subset = @tvalidator("PURIFY",$_POST['subset']);
    $timezoneoffset = @tvalidator("PURIFY",$_POST['timezoneoffset']);
    
    
    $result = pdo_query("1","select room from statusroom where roomid=? and owner=providerid limit 1",array($roomid));
    while( $row = pdo_fetch($result))
    {
        $room = $row['room'];
    }
    
    $sort_text = "createdate2 desc";
    
    //*************************************************************
    //*************************************************************
    //*************************************************************
         
          
    
    echo "

        <div style='
         background-color:transparent;
         padding-top:5px;
         '>
            <br>
            <div class='feed tapped' data-roomid='$roomid' style='text-align:center;cursor:pointer;color:$trimcolor'>
                Home
            </div>
            <br><br>
            

        <div class='' style='background-color:$backgroundcolor'>
         ";

    

    //*************************************************************
    //*************************************************************
    //*************************************************************
    // FULL FILE LIST
    //*************************************************************
    //*************************************************************
    //*************************************************************

    if($subset=='music')
    {
    
        $result = pdo_query("1",
            "
                select origfilename, filename, folder, alias, views, filetype, filesize, title,
                date_format( date_add(createdate,INTERVAL ($timezoneoffset)*60 MINUTE),'%b %d, %y %h:%i%p') as createdate,
                createdate as createdate2, encoding, providerid
                from filelib where 
                filename in (select filename from roomfiles where roomid=?  and filelib.filename=roomfiles.filename )
                and filetype='mp3' and status='Y'                   
                order by $sort_text
            ",array($roomid));
        
        
        echo "<div style='padding:0;margin:auto;text-align:center;background-color:$backgroundcolor'>";
        echo " 
                        <script>
                        audiojs.events.ready(function() 
                        {
                            var as = audiojs.createAll();
                        });
                        </script>
        ";

        while($row = pdo_fetch($result))
        {
            $encoding = $row['encoding'];
            $origfilename = DecryptText($row['origfilename'], $encoding, $row['filename'] );
            $title = DecryptText($row['title'], $encoding, $row['filename'] );
            if($title == ''){
                $title = $origfilename;
            }

            $icon = GetFileTypeIcon( $row['filetype']);           


            $shorttitle = substr($title,0,25);
            if(strlen($title)>25)
            {
                $shorttitle .="...";
            }

            $shortname = substr($origfilename,0,25);
            if(strlen($origfilename)>25)
            {
                $shortname .="...";
            }




            $stream = "";
            if($row['filetype']=='mp3')
            {   
                $filename = getAWSObjectUrl($row['filename']);
                $stream =
                    "
                    <br>
                    <div style='display:inline-block;margin:auto'>
                    <audio src='$filename' preload='none' style='width:300px;max-width:80%' />            
                    </div>
                    ";

                echo "
                    <div
                        class='smalltext'
                        style='cursor:pointer;
                        display:inline-block;
                        text-align:center;
                        margin-auto;
                        background-color:$backgroundcolor;
                        padding-left:5px;
                        width:80%;height:100px' 
                        >
                            <span stye='font-family:11px;color:$color'>
                            $title<br>
                            </span>
                        $stream
                    </div>
                        <br>
                    ";
            }
        }
        echo "
            </div>
            ";
        exit();
    }
      
    //*************************************************************
    //*************************************************************
    //*************************************************************
    
    if($subset=='photos')
    {
    
        $result = pdo_query("1",
            "
                select origfilename, filename, folder, alias, views, filetype, filesize, title,
                date_format( date_add(createdate,INTERVAL ($timezoneoffset)*60 MINUTE),'%b %d, %y %h:%i%p') as createdate,
                createdate as createdate2, encoding, providerid
                from filelib where 
                filename in (select filename from roomfiles where roomid=? and filelib.filename=roomfiles.filename )
                and filetype in ('jpg','png','tif','gif')
                and status='Y'
                order by $sort_text
            ",array($roomid));

        echo "<div style='padding:0;margin:auto;text-align:center;background-color:$backgroundcolor'>";
    
        while($row = pdo_fetch($result))
        {
            $encoding = $row['encoding'];
            $origfilename = DecryptText($row['origfilename'], $encoding, $row['filename'] );
            $title = DecryptText($row['title'], $encoding, $row['filename'] );
            if($title == ''){
                $title = $origfilename;
            }

            $icon = GetFileTypeIcon( $row['filetype']);           


            $shorttitle = substr($title,0,25);
            if(strlen($title)>25)
            {
                $shorttitle .="...";
            }

            $shortname = substr($origfilename,0,25);
            if(strlen($origfilename)>25)
            {
                $shortname .="...";
            }




            $stream = "";
            $filename = getAWSObjectUrl($row['filename']);

                echo "
                    <div
                        class='smalltext'
                        style='cursor:pointer;
                        display:inline-block;
                        text-align:left;
                        margin:10px;
                        background-color:transparent;
                        color:$color;
                        width:300px;height:auto' 
                        >
                            <div class='gridstdborder' style='width:100%;height:100%'>
                            <img src='$filename' style='width:100%;height:auto' />
                            $title
                                <br><br><br>
                            </div>
                    </div>
                    ";
        }
        echo "
            </div>
            ";
    }
    //*************************************************************
    //*************************************************************
    //*************************************************************
    
    if($subset=='files')
    {
    
        $result = pdo_query("1",
            "
                select origfilename, filename, folder, alias, views, filetype, filesize, title,
                date_format( date_add(createdate,INTERVAL ($timezoneoffset)*60 MINUTE),'%b %d, %y %h:%i%p') as createdate,
                createdate as createdate2, encoding, providerid
                from filelib where 
                filename in (select filename from roomfiles where roomid=? and filelib.filename=roomfiles.filename )
                and filetype in ('pdf')
                and status='Y'
                order by $sort_text
            ",array($roomid));

        echo "<div style='padding:0;margin:auto;text-align:center;background-color:$backgroundcolor'>";
    
        while($row = pdo_fetch($result))
        {
            $encoding = $row['encoding'];
            $origfilename = DecryptText($row['origfilename'], $encoding, $row['filename'] );
            $title = DecryptText($row['title'], $encoding, $row['filename'] );
            if($title == ''){
                $title = $origfilename;
            }

            $icon = GetFileTypeIcon( $row['filetype']);           


            $shorttitle = substr($title,0,25);
            if(strlen($title)>25)
            {
                $shorttitle .="...";
            }

            $shortname = substr($origfilename,0,25);
            if(strlen($origfilename)>25)
            {
                $shortname .="...";
            }




            $stream = "";
            $filename = getAWSObjectUrl($row['filename']);

                echo "
                    <div
                        class='smalltext'
                        style='cursor:pointer;
                        display:inline-block;
                        text-align:left;
                        margin:10px;
                        background-color:transparent;
                        color:$color;
                        width:300px;height:auto' 
                        >
                            <div class='' style='width:100%;height:100%'>
                            <a href='$filename'>
                            $title
                            </a>
                                <br><br>
                            </div>
                    </div>
                    ";
        }
        echo "
            </div>
            ";
    }          
    //*************************************************************
    //*************************************************************
    //*************************************************************

    
    echo "</div></div>";
    
function GetFileTypeIcon( $filetype )
{
            $icon = '../img/flat_other.png';
            if( $filetype=='jpg')
            {
                $icon = '../img/flat_camera.png';
            }    
            if( $filetype=='png' ||
                $filetype=='gif' ||
                $filetype=='tif' ||
                $filetype=='tiff' ||
                $filetype=='bmp' 
              )
            {
                $icon = '../img/flat_photo.png';
            }    
            if( $filetype=='mp3' ||
                $filetype=='m4a' ||
                $filetype=='m4p' 
              )
            {
                $icon = '../img/flat_mp3.png';
            }    
            if( $filetype=='wav')
            {
                $icon = '../img/flat_wav.png';
            }    
            if( $filetype=='zip')
            {
                $icon = '../img/flat_zip.png';
            }    
            if( $filetype=='ppt' ||
                $filetype=='pptx' )
            {
                $icon = '../img/flat_ppt.png';
            }    
            if( $filetype=='xls' ||
                $filetype=='xlsx' )
            {
                $icon = '../img/flat_excel.png';
            }    
            if( $filetype=='doc' ||
                $filetype=='docx' ||
                $filetype=='pages' )
            {
                $icon = '../img/flat_doc.png';
            }    
            if( $filetype=='pdf')
            {
                $icon = '../img/flat_pdf.png';
            }    
            if( $filetype=='mp4' ||
                $filetype=='mov')
            {
                $icon = '../img/flat_movie.png';
            }    
    return $icon;
}
    
?>