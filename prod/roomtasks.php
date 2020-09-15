<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_POST['providerid']);

    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $roomid = @tvalidator("ID",$_POST['roomid']);
    $eventname = @tvalidator("PURIFY",$_POST['eventname']);
    $eventdesc = @tvalidator("PURIFY",$_POST['eventdesc']);
    $eventdate = @tvalidator("PURIFY",$_POST['eventdate']);
    $eventtime = @tvalidator("PURIFY",$_POST['eventtime']);
    $eventassign = @tvalidator("PURIFY",$_POST['eventassign']);
    $eventid = @tvalidator("PURIFY",$_POST['eventid']);
    $priority = @tvalidator("PURIFY",$_POST['priority']);
    $sort = @tvalidator("PURIFY",$_POST['sort']);
    $page= @tvalidator("PURIFY",$_POST['page']);
    
    if($priority ==''){
        $priority = '9';
    }
    $checked1='';
    $checked2='';
    $checked3='';
    if( $sort == "" || $sort == "priority")
    {
        $sort_text = "order by priority asc, eventdate asc";
        $checked1 = "checked=checked";
    }
    if( $sort == "targetdate")
    {
        $sort_text = "
                 order by eventdate2 asc, priority asc";
        $checked2 = "checked=checked";
    }
    if( $sort == "createdate")
    {
        $sort_text = "order by createdate2 asc, priority asc";
        $checked3 = "checked=checked";
    }

    //Detect MM/DD/YYYY format from Firefox/IE
    $eventdate = str_replace("/","-",$eventdate);
    $eventdate = str_replace(".","-",$eventdate);
    $datehold = explode("-",$eventdate);
    if(strlen($datehold[0])==2)
    {
        $eventdate = $datehold[2]."-".$datehold[0]."-".$datehold[1];
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
    
    
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img class='icon20' src='../img/arrow-stem-circle-left-128.png' style='padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $tasksicon= "<img class='icon25' src='../img/tasks-128.png' style='position:relative;top:3px;padding:0' />";
    //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    if( $mode == 'S')
    {
        if( $eventname !='' && $eventdate!='' )
        {
            if($eventid == '')
            {
                $result = pdo_query("1"," 
                insert into tasks (roomid, eventname, eventdesc, eventdate, eventtime, eventassign,  priority, providerid, createdate, status, notificationstatus )
                values
                    ($roomid,'$eventname', '$eventdesc', date_add('$eventdate $eventtime',INTERVAL ($_SESSION[timezoneoffset])*(-1) HOUR), '$eventtime', '$eventassign','$priority',$providerid, now(), 'Y','' )
                       ");
            }
            else 
            {
                $result = pdo_query("1"," 
                update tasks set eventname = '$eventname', eventassign='$eventassign', priority='$priority',
                    eventdate=date_add('$eventdate $eventtime',INTERVAL ($_SESSION[timezoneoffset])*(-1) HOUR), eventtime='$eventtime', eventdesc='$eventdesc' 
                    where eventid = $eventid and providerid = $providerid and roomid=$roomid
                       ");
            
            }
        $mode = '';
        }
    }
    if( $mode == 'D')
    {
        $result = pdo_query("1"," 
            delete from tasks where eventid = $eventid and providerid = $providerid and roomid=$roomid
                   ");
        $mode = '';
    }
    if( $mode == 'F')
    {
        $result = pdo_query("1"," 
            insert into tasksaction (eventid, roomid, donebyid, donecode, donedate ) values
            ( $eventid, $roomid, $providerid,'Done', now() )
                   ");
        $mode = '';
    }
    if( $mode == 'U')
    {
        $result = pdo_query("1"," 
            delete from tasksaction where eventid = $eventid and donebyid = $providerid and roomid=$roomid
                   ");
        $mode = '';
    }
    
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    if( $mode == 'A' || $mode == 'E')
    {
        if($mode == 'A'){
            $action = 'Save';
            $eventdate = date("Y-m-d",time()+$_SESSION['timezone']*60*60);
            $eventtime = '00:00';
        }
        if($mode == 'E'){
            $action = 'Edit';
            $result = pdo_query("1","
                select date_format(date_add(eventdate,INTERVAL $_SESSION[timezoneoffset] HOUR),'%Y-%m-%d') as eventdate, 
                       date_format(date_add(eventdate,INTERVAL $_SESSION[timezoneoffset] HOUR),'%k:%i') as eventtime, 
                       eventname, eventdesc, eventassign, priority
                from tasks where roomid=$roomid and eventid=$eventid and providerid=$providerid
                    ");
            if($row=pdo_fetch($result)){
                $eventdate = "$row[eventdate]";
                $eventtime = "$row[eventtime]";
                $eventname = "$row[eventname]";
                $eventdesc = "$row[eventdesc]";
                $eventassign = "$row[eventassign]";
                $priority = "$row[priority]";
            }
        }
?>
    <br>
    <span class='pagetitle' style='color:black'>&nbsp;Tasks List</span> 
    <br>
    &nbsp;&nbsp;
    <div class='mainfont roomtasks tapped'  style='color:black'
        data-roomid='<?=$roomid?>' data-mode=''>
            &nbsp;&nbsp;<?=$braxsocial?>
                Tasks List&nbsp;&nbsp;
    </div>

    <div style='margin:0;background-color:transparent;color:black'>
        <table class='gridnoborder' style='padding:10px' >
            <tr>
                <td style='width:10%;text-align:right;padding-right:5px'></td>
                <td class='pagetitle2' style='color:black'>
                    <?=$action?> Task
                </td>
            </tr>
            <tr>
                <td style='width:10%;text-align:right;padding-right:5px'>Target Date</td>
                <td>
                    <input class='eventdate' placeholder='MM/DD/YYYY' type='date' value='<?=$eventdate?>'/>
                </td>
            </tr>
            <tr>
                <td style='width:10%;text-align:right;padding-right:5px'>
                    Target Time
                </td>
                <td>
                    <input class='eventtime' placeholder='00:00AM' type='time' size=8 value='<?=$eventtime?>'/>
                </td>
            </tr>
            <tr>
                <td style='width:10%;text-align:right;padding-right:5px'>
                    Task Name
                </td>
                <td >
                    <input class='dataentry eventname mainfont' placeholder='Task Title' type='text' size='30' maxlength='45' style='width:100%' value='<?=$eventname?>'/>
                </td>
            </tr>
            <tr>
                <td style='width:10%;text-align:right;padding-right:5px'>
                    Task Description
                </td>
                <td >
                    <textarea class='dataentry mainfont eventdesc' 
                              placeholder='Task Description' cols='50' rows='3' size='30' 
                              style='width:100%;margin-top:5px;padding:0' 
                    ><?=$eventdesc?></textarea>
                </td>
            </tr>
            <tr>
                <td style='width:10%;text-align:right;padding-right:5px'>
                    Assigned To
                </td>
                <td>
                    <input class='dataentry eventassign mainfont' placeholder='Assigned To' type='text' size='30' maxlength='45;width:100%' value='<?=$eventassign?>'/>
                </td>
            </tr>
            <tr>
                <td style='width:10%;text-align:right;padding-right:5px'>
                Priority
                </td>
                <td>
                    <input class='eventpriority mainfont' placeholder='Priority 1-9' type='text' size='30' maxlength='1;width:100%' value='<?=$priority?>'/>
                </td>
            </tr>
            <tr>
                <td style='width:10%;text-align:right;padding-right:5px'></td>
                <td>
                    <br>
                    <div class='divbutton3 divbutton3_unsel roomtasks tapped' data-eventid='<?=$eventid?>' data-roomid='<?=$roomid?>' data-mode='S'>
                        <?=$action?> Task
                    </div>
                    <br><br><br>
                </td>
            </tr>
            
        </table>
    </div>

<?php
    exit();
    }
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    
    
    
    
?>    
    <br>
    <span class='pagetitle' style='color:black'>&nbsp;Task List</span> 
    <br>
    &nbsp;&nbsp;
    <div class='mainfont feed tapped'  style='color:black'
        id='feed' data-roomid='<?=$roomid?>'>
            &nbsp;&nbsp;<?=$braxsocial?>
                Room&nbsp;&nbsp;
    </div>
    &nbsp;&nbsp;
    <div class='mainfont roomtasks tapped'  style='color:black'
         data-roomid='<?=$roomid?>' data-mode='A'>
            <img class='icon20' src='../img/add-circle-128.png' style='padding-left:20px' />
            New Task
    </div>
    <br><br>
    <div  style='color:black;text-align:center'>
                <input type='radio' name='sharesort' class='roomtasks' data-page='1' data-roomid='<?=$roomid?>' data-mode='' data-sort='priority' <?=$checked1?> style='cursor:pointer;position:relative;top:5px'> Priority
                &nbsp;&nbsp;
                <input type='radio' name='sharesort' class='roomtasks' data-page='1' data-roomid='<?=$roomid?>' data-mode='' data-sort='targetdate' <?=$checked2?> style='cursor:pointer;position:relative;top:5px'> Target Date
                &nbsp;&nbsp;
                <input type='radio' name='sharesort' class='roomtasks' data-page='1' data-roomid='<?=$roomid?>' data-mode='' data-sort='createdate' <?=$checked3?> style='cursor:pointer;position:relative;top:5px'> Create Date
                &nbsp;&nbsp;
    </div>
                
<?php
$result = pdo_query("1","
        select 
        date_format(date_add(eventdate,INTERVAL $_SESSION[timezoneoffset] HOUR),'%b %d, %y %a %h:%i%p') as eventdate, 
        date_format(date_add(tasks.createdate,INTERVAL $_SESSION[timezoneoffset] HOUR),'%b %d, %y %a %h:%i%p') as createdate, 
        eventtime,
        tasks.eventdate as eventdate2,
        tasks.createdate as createdate2,
        eventname, eventdesc, eventassign, roomid, priority,
        eventid, tasks.providerid, provider.providername, provider.name2 from 
        tasks 
        left join provider on provider.providerid = tasks.providerid
        where roomid=$roomid
        $sort_text 
        ");


while($row = pdo_fetch($result)){
    
    $delete = "";
    $edit = "";
    $donelog = "";
    if($providerid == $row['providerid'])
    {
        
        $delete = 
                "
                <img class='roomtasks tapped' data-mode='D' data-roomid='$roomid'
                    data-eventid='$row[eventid]'
                src='../img/delete-gray-128.png' 
                style='height:15px;float:right;cursor:pointer'
                />
                ";
        $edit = 
                "&nbsp;&nbsp;&nbsp;&nbsp;
                <div class='roomtasks tapped' data-mode='E' data-roomid='$roomid'
                    data-eventid='$row[eventid]'
                style='display:inline;height:15px;cursor:pointer;color:#00A0E3'
                >
                Edit</div>
                ";
    }
    $done = 
            "
            <div class='roomtasks' data-mode='F' data-roomid='$roomid'
                data-eventid='$row[eventid]'
            style='display:inline;height:15px;cursor:pointer;color:#00A0E3'
            >
            Done</div>
            ";
    $undo = 
            "
            <div class='roomtasks tapped' data-mode='U' data-roomid='$roomid'
                data-eventid='$row[eventid]'
            style='display:inline;height:15px;cursor:pointer;color:#00A0E3'
            >
            Undo</div>
            ";
        
    $result2 = pdo_query("1","
        select providername, name2, 
        date_format(tasksaction.donedate,'%b %d, %Y') as donedate,
        donedate as donedate2, 
        donecode
        from tasksaction
        left join provider on provider.providerid = tasksaction.donebyid
        where tasksaction.eventid=$row[eventid] and tasksaction.roomid=$row[roomid]
        order by donedate2 desc
        ");
    while($row2 = pdo_fetch($result))
    {
        $check = "";
        if($row2['donecode']=='Done')
        {
            $check = "<img src='../img/check-yellow-128.png' style='height:15px;position:relative;top:5px' />";
        }
        $donelog .= "<br>$check $row2[donedate] - <b>$row2[donecode]</b> $row2[providername] &nbsp;&nbsp; $undo ";
    }
    if($donelog!='')
    {
        $donelog = "<br><br>".$donelog;
    }

    echo "
        <div class='mainfont gridstdborder'
            style='cursor:pointer;margin-top:5px;margin-left:5px;margin-right:5px;
            padding:10px;background-color:white;color:black'
            >
            <div class='pagetitle2a' style='margin-bottom:3px'>$tasksicon $row[eventname]</div>
            <hr style='height:0px;border:0;border-top: 1px solid rgba(0, 0, 0, 0.1);border-bottom: 1px solid rgba(255, 255, 255, 0.3);'>
            Priority: $row[priority]<br>
            Target Date: $row[eventdate]  <br>
            Create Date: $row[createdate]  <br>
            <br>
            $row[eventdesc]<br>
            <br>
            Assigned: $row[eventassign]<br>
            $delete <span style='color:gray'>Created by: $row[providername]</span> $edit &nbsp;&nbsp;&nbsp;&nbsp; $done $donelog
               
        </div>
        ";
}

?>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br><br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>