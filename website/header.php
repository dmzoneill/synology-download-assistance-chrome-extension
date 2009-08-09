<?php

session_start();

$mysql_host = "mysql.feeditout.com"; // mysql.feeditout.com
$mysql_username = "books"; // books
$mysql_password = ""; 
$mysql_dbname = "synology";

require_once( "db_mysql.php" );
require_once( "functions.php" );
require_once( "ajax.php" );

trackOnline();

$title = $_SERVER['REQUEST_URI'];
$title = explode( '/' , $title );
$title = $title[ count($title) - 1 ];
$title = explode( '.' , $title );
$title = $title[0];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<title>Synology Download Assistant- <?php print $title ; ?></title>
	<meta name="description" content="Synology Download Assistant helps to redirect downloads to Synology DiskStations, complete small business solutions for backup, storage and file management." />
	<meta name="keywords" content="Synology Download Assistant,Synology chrome plugin,Synology plugin,Synology chrome,DS3612xs,RS3412xs/RS3412RPxs,DS2411+,DS1812+,DS1512+,DS712+,DS411+II,DS212+,RS2211+/RS2211RP+,RS810+/RS810RP+,RS812,RS212,DS411,DS212 ,DS111,DS411slim,DS411j,DS212j,DS112j ,USB Station 2,Surveillance,DX1211,DX510,RX1211/RX1211RP,RX410" />
	<meta name="author" content="David O Neill"/>
	<meta name="robots" content="index, follow" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<!--[if lte IE 6]><link rel="stylesheet" href="css/ie6.css" type="text/css" media="all" /><![endif]-->	
	<script src="js/jquery-1.4.1.min.js" type="text/javascript"></script>
	<script src="js/jquery.jcarousel.pack.js" type="text/javascript"></script>
	<script src="js/jquery-func.js" type="text/javascript"></script>	
	<script type="text/javascript" src="syntaxhighlighter/scripts/shCore.js"></script>
	<script type="text/javascript" src="syntaxhighlighter/scripts/shBrushXml.js"></script>
	<script type="text/javascript" src="syntaxhighlighter/scripts/shBrushPlain.js"></script>
	<script type="text/javascript" src="syntaxhighlighter/scripts/shBrushJScript.js"></script>
	<script type="text/javascript" src="syntaxhighlighter/scripts/shBrushCss.js"></script>
	<script type="text/javascript" src="syntaxhighlighter/scripts/shBrushBash.js"></script>
	<script src="js/jquery.treeview.js" type="text/javascript"></script> 
	<link type="text/css" rel="stylesheet" href="syntaxhighlighter/styles/shCoreDefault.css"/>	
	<link rel="stylesheet" href="css/jquery.treeview.css" /> 
    <script type="text/javascript" src="http://www.synologydownloadassistant.com/js/main.php"></script>
    
    <script type="text/javascript">
    
	    $(document).ready( function()
	    {
		    $( "#browser" ).treeview(
		    {
			    toggle: function() 
			    {
				    //console.log("%s was toggled.", $(this).find(">span").text());
			    }
		    });		
	    });
	    
	    
 var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1014155-5']);
  _gaq.push(['_setDomainName', '.synologydownloadassistant.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
	    
	</script>

</head>
<body>
	
<div class="wrapper"> 	
	
    <!-- Header -->
    <div id="header">
	    <div class="shell">
		
		    <!-- Logo -->
		    <h1 id="logo"><a href="#">Synology Download Assistant</a></h1>
		    <!-- End Logo -->
		
		    <!-- Navigation -->
		    <div id="navigation">
			    <ul>
			        <li><a href="index">Home</a></li>
			        <li><a href="features">Features</a></li>
			        <li><a href="community">Community</a></li>
			        <li><a href="download">Download</a></li>
			        <li><a href="contact">Contact</a></li>
			    </ul>
		    </div>
		    <!-- End Navigation -->
		
	    </div>
    </div>
    <!-- End Header -->

