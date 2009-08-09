<?php


if( isset( $_SESSION[ 'email' ] ) )
{
    $loggedin = "1";
}
else
{
    $loggedin = "0";
}


if( isset( $_GET[ 'translationLocale' ] ) )
{

    $locale = mysql_real_escape_string( ereg_replace( "-" , "_" , $_GET[ 'translationLocale' ] ) );
    
    $numtranslations = $stream->do_query( "select count(*) from translations where lang='en_US'" , "one" );
    $userlang = $stream->do_query( "select count(*) from translations where lang='" . $locale . "'" , "one" );
    $existlang = $stream->do_query( "select count(*) from languages where lang='" . $locale . "'" , "one" );
    $percent = ( $userlang > 0 ) ? round( ( $userlang / $numtranslations ) * 100 ) : 0;
    
    print $locale . "|" . $existlang . "|" . $numtranslations . "|" . $userlang . "|" . $percent;
    
    trackOnline( "plugin" , $_GET[ 'chromeVersion' ] , $_GET[ 'pluginVersion' ] );
    
    exit;
    
}


if( isset( $_GET[ 'pluginTrack' ] ) )
{
    
    trackOnline( "plugin" , $_GET[ 'chromeVersion' ] , $_GET[ 'pluginVersion' ] );
    
    exit;
    
}



if( isset( $_POST[ 'subject' ] ) && isset( $_POST[ 'body' ] ) )
{
    
    $to      = 'dave@synologydownloadassistant.com';
    $subject = htmlspecialchars( "SynologyDA : " .  $_POST[ 'subject' ] );
    $message = htmlspecialchars( $_POST[ 'body' ] );
    $headers = 'From: ' . $_SESSION[ 'email' ] . "\r\n" .
    'Reply-To: ' . $_SESSION[ 'email' ] . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    mail( $to , $subject , $message , $headers );
    
    
    exit;
}


if( isset( $_POST[ 'synologyUser' ] ) && isset( $_POST[ 'synologyUserLocale' ] ) )
{
    $email = mysql_real_escape_string( $_POST[ 'synologyUser' ] );
    $locale = mysql_real_escape_string( $_POST[ 'synologyUserLocale' ] );
    $pVersion = mysql_real_escape_string( isset( $_POST[ 'pluginVersion' ] ) ? $_POST[ 'pluginVersion' ] : "" );
    $bVersion = mysql_real_escape_string( isset( $_POST[ 'chromeVersion' ] ) ? $_POST[ 'chromeVersion' ] : "" );
    
    $exists = $stream->do_query( "select count(*) from knownusers where email='$email' and locale='$locale'" , "one" );
    
    if( $exists == 0 )
    {
        $stream->do_query( "insert into knownusers  values( NULL , '$email' , '$locale' , '$pVersion' , '$bVersion' )" , "one" );    
        print "1";
    }
    else
    {
        $sql = "update knownusers set pluginVersion='$pVersion' , browserVersion='$bVersion' where email='$email'";
        $stream->do_query( $sql , "one" );
        print "0";
    }
    
    trackOnline( "plugin" , $_GET[ 'chromeVersion' ] , $_GET[ 'pluginVersion' ] );
    
    exit;
}


if( isset( $argv[1] ) )
{
	$mysql_host = "mysql.feeditout.com"; // mysql.feeditout.com
	$mysql_username = "books"; // books
	$mysql_password = ""; 
	$mysql_dbname = "synology";

	require_once( "db_mysql.php" );
	
	$users = $stream->do_query( "select email from knownusers" , "array" );   
	
	$subject = "Synology Download Assistant - update released!";

	$message = "
	<html>
	<body>
	<p>Hi,</p> 
	<p>Firstly apologies for the unsolicited email</p>
	<p>I no longer distribute on the chrome extensions webstore.</p>
	<p>Please check <a href='http://www.synologydownloadassistant.com'>http://www.synologydownloadassistant.com</a> for updates.</p>
	<p>Version 2.0.1 is out with language updates.</p>
	<p>It checks http://www.synologydownloadassistant.com for future updates and you will be notified accordingly.</p>
	<p>Enjoy your day </p>
	</body>
	</html>
	";

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'From: dave <dave@synologydownloadassistant.com>' . "\r\n";  
	
	$y = 1;
	
	foreach( $users as $user )
	{            
		$user = $user[ 0 ];     
		mail( $user[ 0 ] , $subject , $message , $headers );    
		print "Mailed " . $user[ 0 ] . "\n";
		$y++;	
		if( $y % 50 == 0 )
		{
			sleep( 5 );
			print "Sleeping for 5, 100 emails sent\n";
		}
	}       
	
	if( mail( "dave@feeditout.com" , $subject , $message , $headers ) )
	{
		print "mailed\n";
	}
    
    exit;
}


if( isset( $_POST[ 'language' ] ) && isset( $_POST[ 'subject' ] ) && isset( $_POST[ 'text' ] )  && $loggedin == "1"  )
{
    $lang = mysql_real_escape_string( $_POST[ 'language' ] );
    $sub = mysql_real_escape_string( $_POST[ 'subject' ] );
    $text = mysql_real_escape_string( $_POST[ 'text' ] );
    $ip = $_SERVER[ 'REMOTE_ADDR' ];
    
    $user = $stream->do_query( "select id from users where email='" . $_SESSION[ 'email' ] . "'" , "one" );
    
    $sql = "select count(*) from translations where title='$sub' and lang='$lang'";
    print "look : $sql\n>";
    $count = $stream->do_query( $sql , "one" );
    
    if( $count > 0 )
    {
        $sql = "update translations set message='$text', ip='$ip', lastuser='$user' where title='$sub' and lang='$lang' and editable='1'";
        $stream->do_query( $sql , "one" );
        print "update|$sql";
    }
    else
    {
        $sql = "insert into translations values( NULL , '$sub' , '$text' , '$lang' , '1' , '$user' , '$ip' )";
        $stream->do_query( $sql , "one" );
        print "insert|$sql";
        
        $enCount = $stream->do_query( "select count(*) from translations where lang='en'" , "one" );
        $forCount = $stream->do_query( "select count(*) from translations where lang='$lang'" , "one" );  
        
        if( $enCount == $forCount )
        {

            $users = $stream->do_query( "select email from knownusers where locale='$lang'" , "array" );   
            
            $subject = "Synology Download Assistant - $lang translation completed!";

            $message = "
            <html>
            <body>
              <p>Hi,</p>  
              <p>Your language has been successfully translated and will be included in the next release!</p>
              <p>Editions to $lang can be made <a href='http://www.synologydownloadassistant.com/community.php?action=translate'>here!</a></p>
              <p>Thanks for your help! </p>
            </body>
            </html>
            ";

            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            // Additional headers
            $headers .= 'From: dave <dave@synologydownloadassistant.com>' . "\r\n";  
            
            foreach( $users as $user )
            {            
                $user = $user[ 0 ];     
                mail( $user[ 0 ] , $subject , $message , $headers );            
            }       
            
            mail( "dave@feeditout.com" , $subject , $message , $headers );
                    
        }     
        
    }    
    
    trackOnline();
    
    exit;
}
else if( isset( $_POST[ 'loginEmail' ] ) && isset( $_POST[ 'password' ] ) )
{
    $password = sha1( $_POST[ 'password' ] );
    $email = mysql_real_escape_string( $_POST[ 'loginEmail' ] );
    
    $exists = $stream->do_query( "select count(*) from users where email='$email' and pass='$password'" , "one" );
    
    if( $exists > 0 )
    {        
        $_SESSION[ 'email' ] = "$email";
        $loggedin = "1";
        print "1";
    }
    else
    {
        unset( $_SESSION[ 'email' ] );
        $loggedin = "0";
        print "0";
    }
    
    trackOnline();

    exit;
}
else if( isset( $_POST[ 'newEmail' ] ) )
{

    $to = $_POST[ 'newEmail' ];
    $to_Mysql = mysql_real_escape_string( $to );
    $textpwd = createRandomPassword();
    $password = sha1( $textpwd );      
    
    $exists = $stream->do_query( "select count(*) from users where email='$to_Mysql'" , "one" );
    
    if( $exists > 0 )
    {
        $stream->do_query( "update users set pass='$password' where email='$to_Mysql'" , "one" );
        
        $subject = 'Synology Download Assistant Translation Password Updated';

        $message = "
        <html>
        <body>
          <p>Hi,</p>  
          <p>Your new password : $textpwd</p>
          <p><a href='http://www.synologydownloadassistant.com/community.php?action=translate'>Translate</a></p>
          <p>Thanks for your help! </p>
        </body>
        </html>
        ";

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        $headers .= 'From: dave <dave@synologydownloadassistant.com>' . "\r\n";
        
        // Mail it
        mail( $to , $subject , $message , $headers );
        
        print "-1";
        exit;
    }
    

    $subject = 'Synology Download Assistant Translation Login';

    $message = "
    <html>
    <body>
      <p>Hi,</p>  
      <p>Your new password : $textpwd</p>
      <p><a href='http://www.synologydownloadassistant.com/community.php?action=translate'>Translate</a></p>
      <p>Thanks for your help! </p>
    </body>
    </html>
    ";

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Additional headers
    $headers .= 'From: dave <dave@synologydownloadassistant.com>' . "\r\n";
    
    // Mail it
    if( mail( $to , $subject , $message , $headers ) )
    {
        $stream->do_query( "insert into users values( NULL , '$to_Mysql' , '$password' )" , "one" );
        print "1";
    }
    else
    {
    
        print "0";
    }
    
    trackOnline();

    exit;
}


if( isset( $_GET[ 'logout' ] ) )
{
    unset( $_SESSION );
    session_destroy();
    $loggedin = "0";
}


if( isset( $_GET[ 'packs' ] ) )
{

    print "<!-- Main --><div id=\"main\"><div class=\"shell\">";
    
    createLanguagePacks();
    
    print "</div></div>";
    
    include( "footer.php" );
    
    exit;
    
}


function codeToLang( $from , $arr )
{
    $from = eregi_replace( "_" , "-" , $from );
    $lang = false;
    
    foreach( $arr as $key => $val )
    {
        $val = eregi_replace( "'" , "" , $val );
        if( $val == $from )
        {
            $lang = $key;
            break; 
        }
    }
    
    if( $lang != false )
    {
        $par = strtolower( $lang );
        return $par;
    }
    else
    {   
        return "false";
    }
}

if( isset( $_GET[ 'requesttranslate' ] ) )
{
    
    $langs = $stream->do_query( "select lang from languages order by lang ASC" , "array" );
	                
    $numtranslations = $stream->do_query( "select count(*) from translations where lang='en_US'" , "one" );
       
   
    for( $p = 0; $p < count( $langs ); $p++ )
    {
        $lang = $langs[ $p ];
                
        $langnum = $stream->do_query( "select count(*) from translations where lang='" . $lang[0] . "'" , "one" );
        $langusers = $stream->do_query( "select count(*) from knownusers where locale='" . $lang[0] . "'" , "one" );
                
        $langComplete = ( $langnum > 0 ) ? round( ( $langnum / $numtranslations ) * 100 ) . "%" : 0 . "%" ;
        $langper = ( $langnum > 0 ) ? round( ( $langnum / $numtranslations ) * 100 ) : 0;
        
        $flang = codeToLang( $lang[0] , $ini_array );
        
        $title = "Synology Download Assistant plugin needs your contribution!";
        $body = "<html><body>Hi,<br><br>Know English? Help translate the Synology Download Assistant chrome plugin<br><br>$flang is only $langComplete complete. <br><br><a href='http://www.synologydownloadassistant.com/community.php?action=translate'>Please help translate $flang now!</a><br><br>Thanks<br><br> - Synology Download Assistant</body></html>";
        
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        $headers .= 'From: dave <dave@synologydownloadassistant.com>' . "\r\n";     
        
        if( $langper != 100 && $langusers != 0 )
        {
            $users = $stream->do_query( "select email from knownusers where locale='" . $lang[0] . "'" , "array" );
            
            print "<h2>" . $lang[0] . "</h2>";            
            
            foreach( $users as $user )
            {
                $user = $user[0];
                print $lang[0] . " | $langper | $langusers | $user <br>";     
                mail( $user , $title , $body , $headers );   
            }
        }
        
    }
    exit;
}


if( isset( $_GET[ 'sendnewsletter' ] ) )
{
    
       
    $title = "Synology Download Assistant Newsletter!";
    $body = "<html><body><img src='http://www.techpowerup.com/reviews/Synology/DS207/images/logo.jpg'>
    
    <h3>Hi <b>Ladies</b> / <b>Gentlemen</b>,</h3>
    
    <div style='margin-left:20px'>
    
        A quick and painless update for the users of <b>Synology Download Assistant!</b><br>
        
        You may have noticed the slow down in release cycle, well there are good and bad reasons for this!<br><br>
        During the past fortnight i finished up my contract in Intel, i have purposely avoided major updates to the plugin,
        due primarily to me being very busy, but also i'm waiting on the roll out of <b>Chrome 9!</b><br><br>
    
    </div>
    
    <h4>Whats so cool about <b>Chrome 9?</b></h4>
    
    <div style='margin-left:20px'>
    
        <b>Chrome 9</b> will be pushed out soon with many new features, including the <b>File API</b>.
        <br><br>
                
    </div>
    
    
    <h4>Here, i just want it to work! do i really care about this?</h4>
    
    <div style='margin-left:20px'>
    
        Well for those of you that have been asking the question about why can't i download torrents<br> from X website that requires login?
        
        More about <a href='http://www.synologydownloadassistant.com/forum/viewtopic.php?f=7&t=10'><b>how the plugin works</b></a>.
        <br><br>
        
    </div>
        
    <h4>Community Feedback and support</h4>
    
    <div style='margin-left:20px'>
    
        I've setup these <a href='http://www.synologydownloadassistant.com/forum/'><b>forums</b></a> to help you and me improve this plugin.<br><br>
        
        Please post <b>questions / rants</b> to the forums rather than on the google estensions page.<br>
        It helps for a better community<br><br><br>  
    
    </div>   
    
    Thanks for your time and contribution!<br><br> - Synology Download Assistant<br><br><br>
    
    <a href='http://www.synologydownloadassistant.com'>Homepage</a><br>
    <a href='http://www.synologydownloadassistant.com/forum/'>Forums</a><br>
    <a href='https://chrome.google.com/extensions/detail/ejfggpgociiecmnjgaiofolejhpmndbb?hl=en'>Extension Homepage</a><br><br><br>

    <form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">
    <input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
    <input type=\"hidden\" name=\"hosted_button_id\" value=\"5SLAXLZUTHPCS\">
    <input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\">
    <img alt=\"\" border=\"0\" src=\"https://www.paypal.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\">
    </form><br><br>

    <a href=\"http://ie.linkedin.com/in/dmzoneill\" >
      <img src=\"http://www.linkedin.com/img/webpromo/btn_viewmy_160x33.png\" width=\"160\" height=\"33\" border=\"0\" alt=\"View David O Neill's profile on LinkedIn\">
    </a><br><br>
    
    </body></html>";
    

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: dave <dave@synologydownloadassistant.com>' . "\r\n";     
    
    $users = $stream->do_query( "select email from knownusers" , "array" );       
    
    foreach( $users as $user )
    {
        $user = $user[0];
        mail( $user , $title , $body , $headers ); 
        print "Mail sent to $user <br>";  

    }

    exit;
}


?>
