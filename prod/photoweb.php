<?php
exit();
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

$max = 50;

    $providerid = tvalidator("PURIFY",$_SESSION[pid]);
    $collection = tvalidator("PURIFY",$_POST[collection]);
    $description = tvalidator("PURIFY",$_POST[description]);
    $mode = tvalidator("PURIFY",$_POST[mode]);
    if( $collection != "" && $mode =='D')
    {
        pdo_query("1","
            delete from sharecollection where providerid=?
            and collection = ?
            ",array($providerid, $collection));
        
        pdo_query("1","
            delete from shares where providerid=? and sharetype='A'
            and collection = ?
            ",array($providerid,$collection));
        
        pdo_query("1","
            delete from shares where providerid=? and sharetype='W'
            and sharelocal = ?
            ",array($providerid,$collection));
        
        
        $collection = "";
    }
    if( $collection != "" && $mode =='A')
    {

        $result = pdo_query("1","
            select url
            from sharecollection where providerid=?
            and collection=? and collection in (select sharelocal
            from shares where providerid=? )
         ",array($providerid,$collection,$providerid));
        if( $row = pdo_fetch($result))
        {
            $url = $row[url];
        }
        else
        {
            $linkid = uniqid("GW", true);
            $url = "$rootserver/$installfolder/sharew.php?p=$linkid";
            $securetype='O';
            $proxyfilename = "";
            $result = pdo_query("1","
                insert into shares 
                (setid, providerid, sharedate, sharetype, sharelocal, 
                shareid, shareto, shareexpire, sharetitle, platform, 
                securetype, proxyfilename, collection )
                values
                ('', ?, now(), 'W', ?, ?, 
                    'Web', date_add( now(), INTERVAL 1095 DAY), 
                    ?,'Web',?,?,'' )
             ",array($providerid,$collection,$linkid,$collection,$securetype,$proxyfilename));
            
        }
        
        
        
        pdo_query("1","
            delete from sharecollection where providerid=?
            and collection = ?
            ",array($providerid,$collection));
        
        pdo_query("1","
            delete from shares where providerid=?
            and collection = ? and sharetype='A'
            ",array($providerid,$collection));
        
        /*
        pdo_query("1","
            delete from shares where providerid=$providerid and sharetype='W'
            and sharelocal = '$collection'
            ");
        */
        
        for($i=1;$i<=$max;$i++)
        {
            $albumpost = "album".$i;
            $album = tvalidator("PURIFY",$_POST[$albumpost]);
            if( $album!="")
            {
                
                $linkid1 = uniqid("CX", true);
                $url1 = "$rootserver/$installfolder/soa.php?p=$linkid1";
                
                $result = pdo_query("1","
                    insert into shares 
                    (setid, providerid, sharedate, sharetype, sharelocal, 
                    shareid, shareto, shareexpire, sharetitle, platform, 
                    securetype, proxyfilename, collection )
                    values
                    ('', ?, now(), 'A', ?, ?, 
                        'Unspecified', date_add( now(), INTERVAL 1095 DAY), 
                        ?,'',?,?,? )
                 ",array($providerid,$album,$linkid1,$album,$securetyype,$proxyfilename,$collection));
                
                
                pdo_query("1","
                    insert into sharecollection
                    (providerid, collection, album, url, url1, seq, description ) 
                    values
                    (?, ?, ?,?,?,?, ? ) 
                    ",array(
                        $providerid, $collection, $album,$url,$url1, $i, $description  
                    ));
            }
        }
        
    }
    
    if( $collection == "")
    {

        $albumselect = "";  
        $description = "";

        $result2 = pdo_query("1","
                select distinct album from photolib where providerid = ? and 
                album!='' and album!='All'  order by album asc
                ",array($providerid));
        $albumselect .= "<option value=''>Select</option>";
        while( $row2 = pdo_fetch($result))
        {
            $albumselect .= "<option value='$row2[album]'>$row2[album]</option>";
        }

        $albumselect .= "
            </select>
                    ";
    }
    else
    {
        $albumselect = "";  

        $result2 = pdo_query("1","
                select album,seq, url, description from sharecollection where providerid=$providerid 
                    and collection = '$collection' order by seq asc
                ");
        
        for($i=0;$i<=$max;$i++) $album_selected[$i]="";
        
        while( $row2 = pdo_fetch($result))
        {
            $album_selected[$row2[seq]]="<option value='$row2[album]' selected=selected>$row2[album]</option>";
            $url="$row2[url]";
            $description = "$row2[description]";
        }
        
        
        $result = pdo_query("1","
                select distinct album from photolib where providerid = ? and 
                album!='' and album!='All'  order by album asc
                ",array($providerid));
        $albumselect .= "<option value=''></option>";
        while( $row = pdo_fetch($result))
        {
            $albumselect .= "<option value='$row[album]'>$row[album]</option>";
        }

        $albumselect .= "
            </select>
                    ";
        
    }
     

?>
<script>
$(document).ready( function() {
       
       
       var xhr = null;
       
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.replace('description');
        CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
  
        
        //$( '#message' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
         $('body').on("mouseenter", ".stdlistrow", function(){
            $(this).removeClass('unsel').addClass('sel');
            
        });
        $('body').on("mouseleave", ".stdlistrow", function(){
            $(this).removeClass('sel').addClass('unsel');
            
        });



        $('body').on("mouseenter", ".divbutton", function(){
            $(this).removeClass('divbutton_unsel').addClass('divbutton_sel');
            
        });
        $('body').on("mouseleave", ".divbutton", function(){
            $(this).removeClass('divbutton_sel').addClass('divbutton_unsel');
            
        });

        $('body').on("click", ".displaycollection", function(){
            
            $('#collectionname').val( $(this).data("collection"));
            $('#mode').val("");
            $('#collection').submit();
                            
        });


        $(document).on('click','#send', function(){
            
                if($('#collectionname').val()=="")
                {
                    alertify.alert("Please enter a name for the collection");
                    return;
                }
                
                $('#collection').submit();
 
        });
        $(document).on('click','#clear', function(){
            
            $('#collectionname').val("");
            $('#description').val("");
            $('#collection').submit();
            $('#mode').val("");
 
        });
        $(document).on('click','#delete', function(){
                $('#mode').val("D");
                $('#collection').submit();
 
        });

        
 
        $('td.label').show();
        $('td.labelrequired').show();
        $('div.label').hide();
        $('div.labelrequired').hide();
 

        $('#info1').click( function() 
        {

        });
        $('#info2').click( function() 
        {

        });

        if($('#collectionname').val()=='')
        {
            $('#delete').hide();
            $('#link').hide();
            $('.preview').hide();
        }
        
  
    
       
       
});
</script>
<title>Create a Collection</title>
</head>
<BODY class="appbody" >
   <FORM id="collection" ACTION="collection.php"  METHOD="POST" >

    <INPUT id="pid" class="pid" TYPE="hidden" NAME="pid" value="<?=$_SESSION[pid]?>">        
    <INPUT id="loginid" class="loginid" TYPE="hidden" NAME="loginid" value="<?=$_SESSION[loginid]?>"  >
    <INPUT id="password" TYPE="hidden" NAME="password"  >
    
        <span class="pagetitle">My Photo Websites</span>
        <br><br>
           
        <table id="newmsgtable" class="newmsgtable messageentrytable" style='padding-top:0;margin-top:0'>

        <tr>
            <td class="label">Existing Websites<br><br><br><br></td>
            <td class="dataarea">
            <?php
            $result = pdo_query("1","
                select distinct collection from sharecollection where providerid=?
                    order by collection asc
                ",array($providerid));
            while($row = pdo_fetch($result))
            {
                echo "
                    <div class='displaycollection' style='cursor:pointer;display:inline;color:steelblue' data-collection='$row[collection]'>$row[collection]</div>
                        &nbsp;&nbsp;&nbsp;
                    ";
            }
            ?>
                <br><br>
            </td>
        </tr>
            
            
        <tr>
            <td class="dummy"></td>
            <td class="dataarea">
                <input id='mode' name="mode" type='hidden' value='A'>
            <div class="divbutton divbutton_unsel send" id="send">Save</div>
            &nbsp;&nbsp;
            <div class="divbutton divbutton_unsel delete" id="delete">Delete</div>
            &nbsp;&nbsp;
            <div class="divbutton divbutton_unsel send" id="clear">Clear</div>
            </td>
        </tr>
            
        <tr>
        <td class="label">
            Website Title:
        </td>
        <td class="dataarea">
            <input id='collectionname' type="text" name="collection" size="100" value='<?=$collection?>'>
        </td>
        </tr>
        
        <tr>
        <td class="label">
            Website Public Link:
        </td>
        <td class="dataarea">
            <input id='link' type="text" name="link" size="100" value='<?=$url?>' readonly="readonly" style="font-size:11px" />
            <a href="<?=$url?>" target="_blank" style="text-decoration:none"><div class="divbutton divbutton_unsel preview">Preview</div></a>
        </td>
        </tr>
        
        
        <tr>
        <td class="label">
            Photo
        </td>
        <td class="dataarea">
            <img id="webphotoimg" src='<?=$avatarurl?>' style='height:250px;width:auto;' />
            <input id="webphoto" name="proxyphoto" type="text" size="50" value="<?=$proxy?>" style='display:none' /><br>
            <div class='divbuttonnew_small divbuttoncolor3 divbuttoncolor3_unsel photoselect' 
                id='photoselect_web' data-target='#webphoto' data-src="#webphotoimg" data-filename='' data-mode=''  data-caller='web' >
                    Select Website  Photo
                </div>
        </td>
        </tr>
        
        
        
        
        <tr>
        <td class="label">
            Description:
        </td>
        <td class="dataarea">
            <textarea id="description" class="description"  name="description" rows="5" cols="50"><?=$description?></textarea>
        </td>
        </tr>
        

        <?php
        for($i=1;$i<=$max;$i++)
        {
        ?>
        <tr>
        <td class="label">Album-<?=$i?>:
        </td>
        <td class="dataarea">
        <select id="album<?=$i?>" name="album<?=$i?>">
        <?=$album_selected[$i]?>
        <?=$albumselect?>
        </td>
        </tr>
        <?php
        }
        ?>
        
        
        
   </table>
    </FORM>

</body></html>
