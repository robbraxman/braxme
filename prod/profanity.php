<?php
function ProfanityCheck($message)
{
    $profanityarray = array("cunt","nigger","motherfucker","nigger","jew monkey","fuck you");
    
    $profane = false;
    foreach($profanityarray as $profanity)
    {
        if(strstr(strtolower($message), strtolower($profanity))!==false){
            $profane = true;
            break;
        }
        
    }
    $result = pdo_query("1","select profanity from provider where providerid = $_SESSION[pid]",null);
    if($row = pdo_fetch($result)){
        if($row['profanity']>5){
            ModerationOneDayDelete();
            pdo_query("1","update provider set profanity=0 where providerid = $_SESSION[pid]",null);
            return "...";
        }
    }

    if($profane == true){
        pdo_query("1","update provider set profanity=profanity+1 where providerid = $_SESSION[pid]",null);
        $message = "Notice: Profanity filter applied. Repeated violations will cause a 24 hour automated total post deletion.";
    }
    return $message;
}
function ModerationOneDayDelete($userid)
{
    global $admintestaccount;
    
    if($userid==$admintestaccount){
        return;
    }
    
    pdo_query("1",
            "
            delete from statuspost where providerid = ?
            and datediff(now(),postdate)<=1 and roomid not in
            (select roomid from statusroom where owner!= ?)
            ",array($userid,$userid)
            );
    pdo_query("1",
            "
            delete from chatmessage where providerid = ?
            and datediff(now(),msgdate)<=1
            and chatid in (select chatid from chatmaster where roomid in
              (select roomid from statusroom where owner!= ?)
            )
            ",array($userid,$userid)
            );
    pdo_query("1",
            "
            delete from notification where providerid = ?
            ",array($userid)
            );
    pdo_query("1",
            "
            update chatmembers set lastmessage = '1900-01-01' where providerid=?
            ",array($userid)
            );
    
    
}
function ModerationHardRestrict($userid)
{
    global $admintestaccount;
    
    if($userid==$admintestaccount){
        return;
    }
    
    $result = pdo_query("1","select restricted, iphash2 from provider where providerid = ?",array($userid));
    if($row = pdo_fetch($result)){
        
        $iphash2 = $row['iphash2'];
        
        if($row['restricted']=='Y'){
            
            pdo_query("1",
            "
             update iphash set ban='' where ip = ?
            ",array($iphash2)
            );
            
            pdo_query("1",
            "
             delete from banhash where banid=?
            ",array($iphash2)
            );
            
            
            pdo_query("1",
            "
             update provider set restricted ='' where providerid = ? and restricted ='Y'
            ",array($userid)
            );

            /*
            echo "
                <script>
                localStorage.removeItem('hgtx');
                </script>
            ";
             * 
             */
            
            
            return "Unrestricted";
        } else {
            
            
            pdo_query("1",
                    "
                    delete from statuspost where providerid = ?
                    and datediff(now(),postdate)<=7
                    ",array($userid)
                    );
            pdo_query("1",
                    "
                    delete from chatmessage where providerid = ?
                    and datediff(now(),msgdate)<=7
                    ",array($userid)
                    );
            pdo_query("1",
                    "
                    delete from notification where providerid = ?
                    ",array($userid)
                    );
            pdo_query("1",
                    "
                    update chatmembers set lastmessage = '1900-01-01' where providerid=?
                    ",array($userid)
                    );
            pdo_query("1",
                    "
                    update provider set restricted='Y',publishprofile='',lastactive = '1900-01-01', publish='N' where providerid=?
                    ",array($userid)
                    );
            
            pdo_query("1",
                    "
                     update iphash set ban='Y' where ip = ?
                    ",array($iphash2)
                    );
            
            pdo_query("1",
                    "
                     insert ignore into banhash (banid) values (?)
                    ",array($iphash2)
                    );
            
            
            return "HardRestricted";
            
            
        }        
    }

    
}
function ModerationProfileRestrict($userid)
{
    global $admintestaccount;
    
    if($userid==$admintestaccount){
        return;
    }
    pdo_query("1",
            "
            update provider set publishprofile='*** Moderated. TOS Violation ***',lastactive = '1900-01-01', publish='N' where providerid=?
            ",array($userid)
            );
    return "ProfileErased";
    
}

function ModerationRestrict($userid)
{
    global $admintestaccount;
    
    if($userid==$admintestaccount){
        return;
    }
    
    $result = pdo_query("1","select restricted from provider where providerid = ?",array($userid));
    if($row = pdo_fetch($result)){
        
        if($row['restricted']=='Y'){
            pdo_query("1",
            "
             update provider set restricted ='' where providerid = ? and restricted ='Y'
            ",array($userid)
            );
            return "Unrestricted";
        } else {
            pdo_query("1",
            "
             update provider set restricted ='Y' where providerid = ? and (restricted !='Y' or restricted is null)
            ",array($userid)
            );
            pdo_query("1",
                    "
                    update provider set lastactive = '1900-01-01', publish='N' where providerid=?
                    ",array($userid)
                    );
            pdo_query("1",
            "
                delete from blocked where blockee=? and blocker = 0
            ",array($userid)
            );
            return "Restricted";
            
        }
    }
}


function ModerationShadowBan($userid)
{
    global $admintestaccount;
    
    if($userid==$admintestaccount){
        return;
    }
    
    $result = pdo_query("1","select 'Y' as blockflag from blocked where blockee = ? and blocker = 0",array($userid));
    if($row = pdo_fetch($result)){
        
            pdo_query("1",
            "
             update provider set restricted ='' where providerid = ?
            ",array($userid)
            );
            pdo_query("1",
            "
                delete from blocked where blockee=? and blocker = 0
            ",array($userid)
            );
            return "UnShadowBanned";
    } else {
            pdo_query("1",
            "
             update provider set restricted ='' where providerid = ? 
            ",array($userid)
            );
            pdo_query("1",
            "
             insert ignore into blocked (blocker, blockee) values (0, ?)
            ",array($userid)
            );
            return "ShadowBanned";
            
    }
}

function Inactivate($userid)
{
    global $admintestaccount;
    
    if($userid==$admintestaccount){
        return;
    }
    if($_SESSION['superadmin']!=='Y'){
        return;
    }
    pdo_query("1",
            "
            update provider set active='N' where providerid=?
            ",array($userid)
            );
    return "Inactive";
    
}
function Activate($userid)
{
    global $admintestaccount;
    
    if($userid==$admintestaccount){
        return;
    }
    if($_SESSION['superadmin']!=='Y'){
        return;
    }
    pdo_query("1",
            "
            update provider set active='Y' where providerid=?
            ",array($userid)
            );
    return "Active";
    
}
function ModerationIpRestrict($userid)
{
    global $admintestaccount;
    
    if($userid==$admintestaccount){
        return;
    }
    $iphash2 = '';
    $result = pdo_query("1","select iphash2 from provider where providerid = ?  ",array($userid));
    if($row = pdo_fetch($result)){
        
        $iphash2 = $row['iphash2'];
        if($iphash2==''){
            return "Invalid Request";
        }
    }
    
    $result = pdo_query("1","select * from banhash where banid = ?",array($iphash2));
    if($row = pdo_fetch($result)){
            
            pdo_query("1",
            "
             delete from banhash where banid=?
            ",array($iphash2)
            );
            return "IP Ban Released";
            
    } else {
            
            pdo_query("1",
                    "
                     insert ignore into banhash (banid) values (?)
                    ",array($iphash2)
                    );
            
            
            return "IP Restricted";
            
    }

    
}