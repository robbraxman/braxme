<?php
session_start();
require("config-pdo.php");
$checkbox = '';//"<img src='../img/checkbox-green-128.png' style='height:25px;position:relative;top:5px' />";
$braxrooms = "<img src='../img/braxroom-square.png' style='position:relative;top:5px;height:30px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
?>
 
<div class='aboutarea pagetitle' style='background-color:#A7A9AB;color:white;text-align:center'>
    <img class='feed tapped' src='../img/arrow-stem-circle-left-128.png' style='float:left;height:30px;position:relative;top:8px;padding-left:10px;cursor:pointer' >
    <div style='padding:20px'>
    <?=$braxrooms?> Rooms
    </div>
    <div class='abouttext mainfont feedphoto' style='text-align:left;max-width:500px;margin:auto'>
        <span class='pagetitle' style='color:gray'>Help</span>
        <br><br>
        
A <b>Room</b> is your group meeting place.  
You can be the Room creator or just be a member. 
When you first start the app,  
pick a specific room to follow a thread or make a new post. Otherwise 
it will show you an activity feed of all rooms. Look for this icon.
                        <div class='divbuttontextonly'  
                            data-room='$roomHtml' data-roomid='$roomid' title='Limit posts to a specific room'>
                            <img src='../img/arrow-stem-circle-right-128.png' style='position:relative;top:5px;height:20px;width:auto;cursor:pointer' title='Select Room' alt='Select Room' />
                        <span class=pagetitle3>&nbsp;Select Room</span>
                        </div>

<br><br>  
You can have any number of rooms with different people in each one, 
maintaining a different conversation. Rooms can be public or private. 
<br><br>  
When a Room is public, it functions like an external website. 
You will find a "Share" icon which allows you to post the room contents on FB or see the external link.
Public rooms can also be made anonymous so that all posts cannot not be identified. Anyone can 
join a public room using #hashtags.
<br><br>  
Private rooms have controlled membership. The Room owner invites specific individuals from his contact 
list and manages membership. Any conversation inside a private room is limited to the members only. The 
content is completely encrypted.
<br><br>
You can post comments, videos, photos, files and external links in a Room. You also have a Room Files area
for sharing files from your Files area or Photos from your album. You can also present an album as a slide 
show.
<br><br>
There are additional options which allow you to alter the display format (like colors and titles) to
<br><br>
<span class='pagetitle' style='color:gray'>Guide</span>
<br><br>
<div style='text-align:left'>


            <div class='smalltext' data-roomid='' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img src='../img/refresh-128.png' class='stdicon_nopad' title='Refresh' 
                    style='position:relative;top:5px;cursor:pointer;height:30px;display:inline;opacity:0.5;margin-bottom:4px'/>
                <br>Refresh
                <br><br>
            </div>
    <br>
Retrieves the latest content from the Room. The app automatically refreshes content only when you enter the room.
<br><br>
            <div class='smalltext' data-roomid=''  style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img src='../img/settings-gray-128.png' class='stdicon_nopad' title='Manage Rooms' 
                    style='position:relative;top:4px;cursor:pointer;height:30px;display:inline;;margin-bottom:4px;'/>
                <br>Setup
                <br>Rooms<br>
            </div>
    <br>
This is the Setup area for Room creators. It allows you to create new rooms and maintain settings and memberships for 
your private rooms.
<br><br>
            <div class='smalltext' data-caller='room' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img  src='../img/join-circle-128.png' 
                    style='height:30px;position:relative;top:4px;cursor:pointer;margin-bottom:4px;opacity:0.9;' />
                <br>Join
                <br>Room
            </div>
    <br>
You can join a room if you know the room's #hashtag. You can also use the "Discover Rooms" feature to look
for open rooms that interest you. If you don't find the room you're looking for, then be a content creator
and start one!
<br><br>
            <div class='smalltext' data-caller='room'  data-roomid='$roomid' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img  src='../img/room-files-128.png' 
                    style='height:30px;position:relative;top:4px;cursor:pointer;margin-bottom:4px;opacity:0.9;' />
                <br>Files
                <br>
            </div>
    <br>
You can store your files on the FILES area. Once you have uploaded your files to your Brax.Me cloud storage, you 
can share it in various places including in the Room Files area. This makes the shared file available to all 
members.
<br><br>
            <div class='smalltext' data-caller='room'  data-roomid='$roomid' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img  src='../img/calendar-128.png' 
                    style='height:30px;position:relative;top:4px;cursor:pointer;margin-bottom:4px;opacity:0.9;' />
                <br>Events
                <br>
            </div>
    <br>
Enter events in the Room Events area and each member will be sent a push notification one day before, and one hour before
the event.
<br><br>
            <div class='smalltext' data-caller='room'  data-roomid='$roomid' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img  src='../img/tasks-128.png' 
                    style='height:30px;position:relative;top:4px;cursor:pointer;margin-bottom:4px;opacity:0.9;' />
                <br>Tasks
                <br>
            </div>
    <br>
Enter tasks for people who are members of the room. Notifications are sent when the status of a task changes.
<br><br>
            <div class='smalltext' data-caller='room'  data-roomid='$roomid' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img  src='../img/share-square-128.png' 
                    style='height:30px;position:relative;top:4px;cursor:pointer;margin-bottom:4px;opacity:0.9;' />
                <br>Invite
                <br>& Share
            </div>
    <br>
This allows you to either invite groups of people on Facebook or to actually post the room content directly on Facebook.
It also displays the Public View URL which is the external (publicly accessible, like a website) link to a public room.
<br><br>
            <div class='smalltext' data-caller='room'  data-roomid='$roomid' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img  src='../img/find-circle-128.png' 
                    style='height:30px;position:relative;top:4px;cursor:pointer;margin-bottom:4px;opacity:0.9;' />
                <br>Find
                <br>Posts
            </div>
    <br>
From the All Room Activity view, you can find any post by date and poster name using this search feature.
<br><br>
            <div class='smalltext' data-caller='room'  data-roomid='$roomid' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img  src='../img/braxfile.png' 
                    style='height:30px;position:relative;top:4px;cursor:pointer;margin-bottom:4px;opacity:0.9;' />
            </div>
    <br>
Whenever you see this icon, it means you can insert a link to a file in your FILES area.
<br><br>
            <div class='smalltext' data-caller='room'  data-roomid='$roomid' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img  src='../img/braxphoto.png' 
                    style='height:30px;position:relative;top:4px;cursor:pointer;margin-bottom:4px;opacity:0.9;' />
            </div>
    <br>
Whenever you see this icon, it means you can insert a photo from your photo library.
<br><br>
            <div class='smalltext' data-caller='room'  data-roomid='$roomid' style='display:inline-block;height:60px;width:42px;text-align:center;color:gray;cursor:pointer'>
                <img  src='../img/camera-gray-128.png' 
                    style='height:30px;position:relative;top:4px;cursor:pointer;margin-bottom:4px;opacity:0.9;' />
            </div>
    <br>
When you use the Camera icon on the top bar on mobile, it will bring up the Camera app of the device and any photos 
you take will automatically be inserted into the current room. If you are not in Room or Chat, the photo will 
be added to your Photo Library.
<br><br>
<img src='../img/delete-128.png' style='height:20px;position:relative;top:5px;cursor:pointer' class='friendlist' 
id='deletefriends' 
data-providerid='$providerid' data-roomid='$roomid' data-mode='M' />
<b>Remove me from Room</b>
    <br>
You will find this icon at the bottom of each room (you must have selected a room). Use this to remove yourself from membership in a room.
<br><br>



</div>
    </div>
</div>    

       
                   

