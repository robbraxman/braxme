$(document).ready( function() {

        
        
        $('body').on('click','.vpnmanage', function()
        {
                AbortAjax();
                PanelShow(4);
                var accountid = $(this).data('accountid');
                var newproviderid = $(this).data('providerid');
                var newusername = $(this).data('username');
                var newname = $(this).data('name');
                var mode = $(this).data('mode');
                
                var name = $('#vpnname').val();
                var username = $('#vpnusername').val();
                var password = $('#vpnpassword').val();
                var email = $('#vpnemail').val();
                var startdate = $('#vpnstartdate').val();
                var access = $('#vpnaccess').val();
                var providerid = $('#vpnproviderid').val();
                  
                    
                var notes = $('#vpnnotes').val();
                
                
                if( mode === 'D'){
                    alertify.confirm('Inactivate this Account?',function(ok){
                        if( ok ){
                        
                            $('#roominnerwindow').hide().load( rootserver+"vpnsetup.php",  {
                                'vpnproviderid' : providerid,
                                'mode' : mode,
                                'vpnaccountid' : accountid,
                                'vpnname' : name,
                                'vpnusername' : username,
                                'vpnpassword' : password,
                                'vpnemail' : email,
                                'vpnaccess' : access,
                                'vpnstartdate' : startdate,
                                'vpnnotes' : notes

                            }, function(html, status){
                                    $("#roominnerwindow").scrollTop(0);
                            }).fadeIn(800);
                        }
                    });
                    return;
                    
                }
                
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"vpnsetup.php",  {
                        'vpnproviderid' : providerid,
                        'newproviderid' : newproviderid,
                        'newusername' : newusername,
                        'newname' : newname,
                        'mode' : mode,
                        'vpnaccountid' : accountid,
                        'vpnname' : name,
                        'vpnusername' : username,
                        'vpnpassword' : password,
                        'vpnemail' : email,
                        'vpnaccess' : access,
                        'vpnstartdate' : startdate,
                        'vpnnotes' : notes
                
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
        });
        
        $('body').on('click','.postwipe', function()
        {
                AbortAjax();
                var userid = $(this).data('userid');

            
                $.ajax({
                    url: rootserver+'moderate.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 
                        'mode': 'W', 
                        'userid' : userid
                    }

                }).done(function( data, status ) {
                    alertify.alert('Wipe '+data+' '+userid);
                });
            
        });        
        $('body').on('click','.postrestrict', function()
        {
                AbortAjax();
                var userid = $(this).data('userid');

            
                $.ajax({
                    url: rootserver+'moderate.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 
                        'mode': 'R', 
                        'userid' : userid
                    }

                }).done(function( data, status ) {
                    alertify.alert('Restrict '+data+' '+userid);
                });
     
        });        
        $('body').on('click','.hardrestrict', function()
        {
                AbortAjax();
                var userid = $(this).data('userid');

            
                $.ajax({
                    url: rootserver+'moderate.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 
                        'mode': 'H', 
                        'userid' : userid
                    }

                }).done(function( data, status ) {
                    alertify.alert('Hard Restrict '+data+' '+userid);
                });
     
        });        
        $('body').on('click','.profilerestrict', function()
        {
                AbortAjax();
                var userid = $(this).data('userid');

            
                $.ajax({
                    url: rootserver+'moderate.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 
                        'mode': 'P', 
                        'userid' : userid
                    }

                }).done(function( data, status ) {
                    alertify.alert('Profile Restrict '+data+' '+userid);
                });
     
        });        
        $('body').on('click','.shadowban', function()
        {
                AbortAjax();
                var userid = $(this).data('userid');

            
                $.ajax({
                    url: rootserver+'moderate.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 
                        'mode': 'S', 
                        'userid' : userid
                    }

                }).done(function( data, status ) {
                    alertify.alert('Shadow Ban '+data+' '+userid);
                });
     
        });        
        
    
        $('body').on('click','.vpnlist', function()
        {
                AbortAjax();
                PanelShow(4);
                var find = $('#vpnfind').val();
                
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"vpnlist.php",  {
                    'find' : find
                
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
        });        
        $('body').on('click','.emaillist', function()
        {
                AbortAjax();
                PanelShow(4);
                var find = $('#emailfind').val();
                
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"emaillist.php",  {
                    'find' : find
                
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
        });        
        
        $('body').on('click','.emailmanage', function()
        {
                AbortAjax();
                PanelShow(4);
                var providerid = $(this).data('providerid');
                var mode = $(this).data('mode');
                
                var newhandle = $('#emailhandle').val();
                var userid = $('#emailuserid').val();
                var expiration = $('#emailexpiration').val();
                var password = $('#emailpassword').val();
                var confirmpassword = $('#confirmpassword').val();
                var status = $('#emailstatus').val();
                if(password!==confirmpassword){
                    alertify.alert('Passwords do not match.');
                    return;
                }
                  
                    
                
                
                if( mode === 'D'){
                    alertify.confirm('Inactivate this Account?',function(ok){
                        if( ok ){
                        
                            $('#roominnerwindow').hide().load( rootserver+"emailsetup.php",  {
                                'providerid' : providerid,
                                'newhandle' : newhandle,
                                'mode' : mode,
                                'emailuserid' : userid,
                                'emailpassword' : password,
                                'confirmpassword' : confirmpassword,
                                'emailexpiration' : expiration,
                                'emailstatus' : status

                            }, function(html, status){
                                    $("#roominnerwindow").scrollTop(0);
                            }).fadeIn(800);
                        }
                    });
                    return;
                    
                }
                
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"emailsetup.php",  {
                        'providerid' : providerid,
                        'newhandle' : newhandle,
                        'emailuserid' : userid,
                        'mode' : mode,
                        'emailpassword' : password,
                        'emailexpiration' : expiration,
                        'emailstatus' : status
                
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
        });
        
        
        
        $('body').on('click','.stats', function()
        {
            AbortAjax();
            $('.mainview').scrollTop(0);
            PanelShow(30);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#statsform').submit();
        });
        $('body').on('click','.statsplus', function()
        {
            AbortAjax();
            $('.mainview').scrollTop(0);
            $('.settingsview').scrollTop(0);
            PanelShow(30);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#superstatsform').submit();
        });
        $('body').on('click','.statsuser', function()
        {
            AbortAjax();
            PanelShow(20);
            ShowBanner();
            $('.settingsview').scrollTop(0);
            $('.mainview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#userstatsform').submit();
        });
        $('body').on('click','.testzone', function()
        {
            
            AbortAjax();
            PanelShow(20);
            $('.settingsview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#testzoneform').submit();
        });
        $('body').on('click','.testzone2', function()
        {
                PanelShow(14);
                $('#socialwindow').load( rootserver+"testzone.php");
        });
        
        $('body').on('click','.report1', function()
        {
            
            AbortAjax();
            PanelShow(30);
            $('.settingsview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#report1form').submit();
        });
        $('body').on('click','.tokenreport', function()
        {
            
            AbortAjax();
            PanelShow(30);
            $('.settingsview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#tokenreportform').submit();
        });
        $('body').on('click','.tokenstore', function()
        {
            
            AbortAjax();
            PanelShow(30);
            $('.settingsview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#tokenstoreform').submit();
        });
        
                
    
       
});



