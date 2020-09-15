<?php

    

    $max = 100;
    $maxperline = 5;
    $picwidth1 = '800px';
    $picwidth = '140px';
    $picheight = '100px';
    
    $picwidthIn = '150px';
    $picheightIn = '120px';
    
    if(!isset($_SESSION['sizing'])){
        echo "Report to Tech Support - Sizing Null";
        exit();
    }
    if($_SESSION['sizing']=='2500')
    {
        $max = 100;
        $maxperline = 5;
        $picwidth1 = '1900px';
        $picwidth = '480px';
        $picheight = '320px';
        $picwidthIn = '550px';
        $picheightIn = '360px';
    }
    if($_SESSION['sizing']=='1900')
    {
        $max = 100;
        $maxperline = 5;
        $picwidth1 = '1600px';
        $picwidth = '380px';
        $picheight = '220px';
        $picwidthIn = '460px';
        $picheightIn = '310px';
    }
    if($_SESSION['sizing']=='1600')
    {
        $max = 100;
        $maxperline = 5;
        $picwidth1 = '1200px';
        $picwidth = '260px';
        $picheight = '170px';
        $picwidthIn = '290px';
        $picheightIn = '190px';
    }
    if($_SESSION['sizing']=='1400')
    {
        $max = 96;
        $maxperline = 5;
        $picwidth1 = '1200px';
        $picwidth = '240px';
        $picheight = '160px';
        $picwidthIn = '290px';
        $picheightIn = '190px';
    }
    if($_SESSION['sizing']=='1200')
    {
        $max = 100;
        $maxperline = 5;
        $picwidth1 = '1100px';
        $picwidth = '210px';
        $picheight = '150px';
        
        $picwidthIn = '300px';
        $picheightIn = '203px';
        
    }
    if($_SESSION['sizing']=='1000')
    {
        $max = 100;
        $maxperline = 5;
        $picwidth1 = '990px';
        $picwidth = '150px';
        $picheight = '80px';
        
        $picwidthIn = '200px';
        $picheightIn = '100px';
        
    }
    if($_SESSION['sizing']=='750')
    {
        $max = 100;
        $maxperline = 5;
        $picwidth1 = '740px';
        $picwidth = '130px';
        $picheight = '80px';
        
        $picwidthIn = '150px';
        $picheightIn = '100px';
        
    }
    if($_SESSION['sizing']=='600')
    {
        $max = 90;
        $maxperline = 3;
        $picwidth1 = '550px';
        $picwidth = '150px';
        $picheight = '100px';
        
        $picwidthIn = '180px';
        $picheightIn = '120px';
        
    }
    if($_SESSION['sizing']=='512')
    {
        $max = 90;
        $maxperline = 3;
        $picwidth1 = '502px';
        $picwidth = '150px';
        $picheight = '100px';
        
        $picwidthIn = '180px';
        $picheightIn = '130px';
        
    }
    if($_SESSION['sizing']=='414')
    {
        $max = 90;
        $maxperline = 3;
        $picwidth1 = '404px';
        $picwidth = '130px';
        $picheight = '90px';
        
        $picwidthIn = '140px';
        $picheightIn = '110px';
        
    }
    if($_SESSION['sizing']=='375')
    {
        $max = 100;
        $maxperline = 2;
        $picwidth1 = '370px';
        $picwidth = '185px';
        $picheight = '120px';
        
        $picwidthIn = '190px';
        $picheightIn = '140px';
        
    }
    if( $_SESSION['sizing']=='320' )
    {
        $max = 60;
        $maxperline = 2;
        $picwidth1 = '310px';
        $picwidth = '157px';
        $picheight = '100px';
        
        $picwidthIn = '170px';
        $picheightIn = '130px';
        
    }
    if($_SESSION['superadmin']=='Y'){
        //$max = 20;
    }
        
