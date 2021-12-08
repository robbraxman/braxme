<?php
session_start();
require_once("config-pdo.php");
require_once("room.inc.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_POST['providerid']);

    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $roomid = @tvalidator("ID",$_POST['roomid']);
    $eventname = @tvalidator("PURIFY",$_POST['eventname']);
    $eventdesc = @tvalidator("PURIFY",$_POST['eventdesc']);
    $eventdate = @tvalidator("PURIFY",$_POST['eventdate']);
    $eventtime = @tvalidator("PURIFY",$_POST['eventtime']);
    $eventid = @tvalidator("PURIFY",$_POST['eventid']);
    $backgroundcolor = @tvalidator("PURIFY",$_POST['backgroundcolor']);
    $color = @tvalidator("PURIFY",$_POST['color']);
    $trimcolor = @tvalidator("PURIFY",$_POST['trimcolor']);

    //Detect MM/DD/YYYY format from Firefox/IE
    $datehold = explode("/",$eventdate);
    if(strlen($datehold[0])==2)
    {
        $test = new DateTime($eventdate);
        $eventdate = date_format($test, 'Y-m-d'); // 2011-03-03 00:00:00
    }
    
    //Detect MM/DD/YYYY format from Firefox/IE
    $timehold = explode(":",$eventtime);
    if($eventtime!='' && strlen($timehold[1])>2)
    {
        $min = substr($timehold[1],2);
        $am = strtolower(ltrim(substr($timehold[1],2,3)));
        $houradd = 0;
        if($am=='pm'){
            $houradd = 12;
        }
        $hour = $timehold[0]+$houradd;
        $eventtime = "$hour:$min";
    }
    
    $sizing = RoomSizing();
    
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img src='../img/arrow-stem-circle-left-128.png' style='position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $calendaricon= "<img src='../img/calendar-128.png' style='position:relative;top:3px;height:20px;width:auto;padding:0' />";
    //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    
    
    
    
?>    
    <br>
    &nbsp;&nbsp;
    <center>
    <div class='divbuttontextonly feed tapped' 
        id='feed' data-roomid='<?=$roomid?>' style='color:<?=$trimcolor?>'>
                Home
    </div>
    </center>
                
<?php
$result = pdo_query("1","
        select date_format(date_add(eventdate,INTERVAL $_SESSION[timezoneoffset] HOUR),'%b %d, %y %a %h:%i%p') as eventdate2, eventtime,
        eventname, eventdesc, eventid, eventdate,
        events.providerid, provider.providername, provider.name2 from 
        events 
        left join provider on provider.providerid = events.providerid
        where roomid=?
        and eventdate > now()
        order by eventdate asc 
        ",array($roomid));
while($row = pdo_fetch($result)){

    echo "
        <div class='mainfont gridstdborder'
            style='width:auto;cursor:pointer;margin:auto;
            padding:20px;background-color:$backgroundcolor;color:$color'
            >
            <div class='pagetitle2' style='margin:3px;color:$color'>$calendaricon $row[eventname]</div>
            $row[eventdate2]<br>
            $row[eventdesc]<br>
               
        </div>
        ";
}

?>

