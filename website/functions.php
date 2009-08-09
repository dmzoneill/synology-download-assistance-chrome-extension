<?php

function createLanguagePack( $language )
{
    
    global $stream;
    
    $sql = "select * from translations where lang='$language' order by title ASC";    
   
    $translations = $stream->do_query( $sql , "array" );
    
    print "<br><h1>$language</h1>";
    
    $contents = "{\n\n";
    
    $y = 0;
    
    for( ; $y < count( $translations ) - 1; $y++ )
    {
        
        $row = $translations[ $y ];
        $id = $row[ 0 ];
        $title = $row[ 1 ];
        $message = eregi_replace( "\"" , "'" , $row[ 2 ] );
        $lang = $row[ 3 ];
        $editable = $row[ 4 ];
        $lastuser = $row[ 5 ];
        $ip = $row[ 6 ];

        $contents .= "\t\"$title\" : \n";
        $contents .= "\t{\n";
        $contents .= "\t\t\"message\": \"$message\"\n";
        $contents .= "\t},\n\n";
    }
    
    $row = $translations[ $y ];
    $id = $row[ 0 ];
    $title = $row[ 1 ];
    $message = $row[ 2 ];
    $lang = $row[ 3 ];
    $editable = $row[ 4 ];
    $lastuser = $row[ 5 ];
    $ip = $row[ 6 ];
    
    $contents .= "\t\"$title\" : \n";
    $contents .= "\t{\n";
    $contents .= "\t\t\"message\": \"$message\"\n";
    $contents .= "\t}\n\n";
    
    $contents .= "}";
    
    //print "<pre style='border:1px dashed #666666;padding:20px;margin:50px;width:1200px'>" . $contents . "</pre>";
    
    shell_exec( "mkdir /home/proxykillah/feeditout.com/chrome/SynologyDownloadAssistant/translate/translations/" . $language );
    
    $file = "/home/proxykillah/feeditout.com/chrome/SynologyDownloadAssistant/translate/translations/" . $language . "/messages.json";
    
    $handle = fopen( $file , "w" );
    
    if( $handle )
    {
        fwrite( $handle , $contents );
        fclose( $handle );
    }   

}


function createLanguagePacks()
{

    shell_exec( "rm -rvf /home/proxykillah/feeditout.com/chrome/SynologyDownloadAssistant/translate/translations/*" );

    global $stream;
    
    $sql = "select count(*) from translations where lang='en'";
    
    $count = $stream->do_query( $sql , "one" );
    
    $sql = "select lang from languages order by lang ASC";
    
    $languages = $stream->do_query( $sql , "array" );
    
    foreach( $languages as $language )
    {
        
        $language = $language[ 0 ];
        
        $sql = "select count(*) from translations where lang='$language'";       
        
        $completed = $stream->do_query( $sql , "one" );
        
        if( $completed == $count )
        {
            
            createLanguagePack( $language );       
            
        }
        
    }
    
    shell_exec( "zip -r /home/proxykillah/feeditout.com/chrome/SynologyDownloadAssistant/translate/locales.zip /home/proxykillah/feeditout.com/chrome/SynologyDownloadAssistant/translate/translations/" );

}


function getOnline( $scope )
{

    global $stream;
    
    $datescope = time() - ( 60 * 60 );
    $sql = "select count(*) from trackonline where scope = '$scope' and stamp > '$datescope'";
    
    return $stream->do_query( $sql , "one" );
    
}


function trackOnline( $scope = "site" , $browserV = "" , $pluginV = "" )
{

    global $stream;
    
    $ip = $_SERVER[ 'REMOTE_ADDR' ];
    $stamp = time();
    $oldstamp = $stamp - 3600;
    
    $browserV = mysql_real_escape_string( $browserV );
    $pluginV = mysql_real_escape_string( $pluginV );
    
    $sql_new = "insert into trackonline values( NULL , '$ip' , '$stamp' , '$scope' , '$pluginV' , '$browserV' )";
    $sql_update = "update trackonline set stamp='$stamp', browserVersion='$browserV', pluginVersion='$pluginV' where ip='$ip' and scope='$scope'";
    $sql_check = "select count(*) from trackonline where ip = '$ip' and scope = '$scope'";
    $sql_dropold = "delete from trackonline where stamp<$oldstamp";  
    $check = $stream->do_query( $sql_check , "one" );
    
    if( $check == "1" )
    {
        $stream->do_query( $sql_update , "one" );
    }
    else
    {
        $stream->do_query( $sql_new , "one" );
    }
    
    $stream->do_query( $sql_dropold , "one" );
}


function createRandomPassword() 
{ 

    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 

    return $pass; 

} 


?>
