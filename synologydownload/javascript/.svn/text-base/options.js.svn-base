
if( localStorage.getItem( "hijack" ) === null ) localStorage.setItem( "hijack" , "1" );
if( localStorage.getItem( "contextmenus" ) === null ) localStorage.setItem( "contextmenus" , "1" );
if( localStorage.getItem( "debug" ) === null ) localStorage.setItem( "debug" , "0" );
if( localStorage.getItem( "downloadtypes" ) == null ) localStorage.setItem( "downloadtypes" , "rar,zip,tar,tar.gz,torrent,jpg,gif,png,jpeg,mpg,avi,mpeg,ram,rm,mp3,mp4,mov,iso" );
if( localStorage.getItem( "hijacked" ) == null ) localStorage.setItem( "hijacked" , "rar,zip,tar,tar.gz,torrent,jpg,gif,png,jpeg,mpg,avi,mpeg,ram,rm,mp3,mp4,mov,iso" );
if( localStorage.getItem( "imagetypes" ) == null ) localStorage.setItem( "imagetypes" , "jpeg,png,gif,jpg,bmp,tiff" );
if( localStorage.getItem( "selectedDiskstation" ) === null ) localStorage.setItem( "selectedDiskstation" , "1" );
if( localStorage.getItem( "DiskstationCount" ) === null ) localStorage.setItem( "DiskstationCount" , "0" );


var adding = false;


if( localStorage.getItem( "username" ) !== null )
{

    localStorage.setItem( "username" + localStorage.getItem( "selectedDiskstation" ) , localStorage.getItem( "username" ) );
    localStorage.setItem( "password" + localStorage.getItem( "selectedDiskstation" ) , localStorage.getItem( "password" ) );
    localStorage.setItem( "host" + localStorage.getItem( "selectedDiskstation" ) , localStorage.getItem( "host" ) );
    localStorage.setItem( "port" + localStorage.getItem( "selectedDiskstation" ) , localStorage.getItem( "port" ) );
    localStorage.setItem( "ssl" + localStorage.getItem( "selectedDiskstation" ) , localStorage.getItem( "ssl" ) );
    
    localStorage.removeItem( "username" );
    localStorage.removeItem( "password" );
    localStorage.removeItem( "host" );
    localStorage.removeItem( "port" );
    localStorage.removeItem( "ssl" );
    
    localStorage.setItem( "DiskstationCount" , "1" );
    localstorage.setItem( "defaultStation" , "1" );
    
}



function populateDiskstations()
{
    $( "#diskstations" ).empty();
    
    var count = localStorage.getItem( "DiskstationCount" );
    
    if( parseInt( count ) == 0 )
    {
        $( "#diskstations" ).append( "<option selected='selected' value=\"1\">xxx.xxx.xxx.xxx</option>" );
        adding = true;
    }   
    else
    {
        for( var g = 1; g < parseInt( count ) + 1; g++ )
        {
            var host = localStorage.getItem( "host" + g );
            
            if( g == 1 )
            {
                $( "#diskstations" ).append( "<option selected='selected' value=\"" + g + "\">" + host + "</option>" );
            }
            else
            {
                $( "#diskstations" ).append( "<option value=\"" + g + "\">" + host + "</option>" );
            }
        }        
    }    
    
    localStorage.setItem( "selectedDiskstation" , "1" );

}



function addDiskstation()
{
    if( adding == false )
    {
        adding = true;        
        var count = parseInt( localStorage.getItem( "DiskstationCount" ) ) + 1;    
        $( "#diskstations" ).append( "<option selected='selected' value=\"" + count + "\">xxx.xxx.xxx.xxx</option>" );
        localStorage.setItem( "selectedDiskstation" , count );
        $( "#username" ).val( "" );
	    $( "#password" ).val( "" );
	    $( "#host" ).val( "" );
	    $( "#port" ).val( "" );
	    $( "#ssl" ).attr( "checked" );
	    $( "#addStation" ).attr( "disabled" , "disabled" );
	    $( "#removeStation" ).attr( "disabled" , "disabled" );
    }
}



function removeDiskstation()
{
    if( $( "#diskstations option" ).size() >= 1 )
    {
        adding = false;
        var current = parseInt( $( "#diskstations option:selected" ).val() );
        var total = parseInt( localStorage.getItem( "DiskstationCount" ) ); 
        $( "#diskstations option:selected" ).remove(); 
        
        var t;
        
        for( t = current; t < total; t++ )
        {
            localStorage.setItem( "username" + t , localStorage.getItem( "username" + ( t + 1 ) ) );
	        localStorage.setItem( "password" + t , localStorage.getItem( "password" + ( t + 1 ) ) );
	        localStorage.setItem( "host" + t , localStorage.getItem( "host" + ( t + 1 ) ) );
	        localStorage.setItem( "port" + t , localStorage.getItem( "port" + ( t + 1 ) ) );	
	        localStorage.setItem( "ssl" + t , localStorage.getItem( "ssl" + ( t + 1 ) ) );    
        }
        
        localStorage.removeItem( "username" + localStorage.getItem( "DiskstationCount" ) );
        localStorage.removeItem( "password" + localStorage.getItem( "DiskstationCount" ) );
        localStorage.removeItem( "host" + localStorage.getItem( "DiskstationCount" ) );
        localStorage.removeItem( "port" + localStorage.getItem( "DiskstationCount" ) );
        localStorage.removeItem( "ssl" + localStorage.getItem( "DiskstationCount" ) );
        
        localStorage.setItem( "selectedDiskstation" ,  current - 1 );
        localStorage.setItem( "DiskstationCount" ,  total - 1 );   
        populateDiskstations();
        restore_options();     
        
    }
}



function selectDiskstation()
{
    if( adding == true && ( $( "#host" ).val() == "" || $( "#port" ).val() == "" || $( "#username" ).val() == "" || $( "#password" ).val() == "" ) )
    {
        localStorage.setItem( "selectedDiskstation" , parseInt( localStorage.getItem( "selectedDiskstation" ) ) - 1 );
        $( "#diskstations option:last" ).remove();     
        
    }
    else
    {
        localStorage.setItem( "selectedDiskstation" , $( "#diskstations" ).val() );  
    } 
    
    $( "#addStation" ).attr( "disabled" , "" );
	$( "#removeStation" ).attr( "disabled" , "" );
    adding = false;    
    restore_options();
}



function save_options() 
{
    if( adding == true )
    {
        var count = parseInt( localStorage.getItem( "DiskstationCount" ) ) + 1;  
        localStorage.setItem( "DiskstationCount" , count );          
        adding = false;
    }
    else
    {
        localStorage.setItem( "defaultStation" , localStorage.getItem( "selectedDiskstation" ) );
    }

	var un = $( "#username" ).val();
	var pw = $( "#password" ).val();
	var host = $( "#host" ).val();
	var port = $( "#port" ).val();
	var debug = $( "#debug" ).attr( "checked" );
	var ssl = $( "#ssl" ).attr( "checked" );
	
	if( debug == true ) { debug = 1; } else { debug = 0; }
	if( ssl == true ) { ssl = 1; } else { ssl = 0; }
	
	localStorage.setItem( "username" + localStorage.getItem( "selectedDiskstation" ) , un );
	localStorage.setItem( "password" + localStorage.getItem( "selectedDiskstation" ) , pw );
	localStorage.setItem( "host" + localStorage.getItem( "selectedDiskstation" ) , host );
	localStorage.setItem( "port" + localStorage.getItem( "selectedDiskstation" ) , port );	
	localStorage.setItem( "ssl" + localStorage.getItem( "selectedDiskstation" ) , ssl );
	localStorage.setItem( "debug" , debug );	
	
}



function restore_options() 
{

	var un = localStorage.getItem( "username" + localStorage.getItem( "selectedDiskstation" ) );
	var pw = localStorage.getItem( "password" + localStorage.getItem( "selectedDiskstation" ) );
	var host = localStorage.getItem( "host" + localStorage.getItem( "selectedDiskstation" ) );
    var port = localStorage.getItem( "port" + localStorage.getItem( "selectedDiskstation" ) );
    var ssl = localStorage.getItem( "ssl" + localStorage.getItem( "selectedDiskstation" ) );    
	var debug = localStorage.getItem( "debug" );	

	$( "#username" ).val( un );
	$( "#password" ).val( pw );
	$( "#host" ).val( host );
	$( "#port" ).val( port );	

	if( debug == 1 ) { $( "#debug" ).attr( "checked" , true ); }
	
	if( ssl == 1 ) 
	{ 
	    $( "#ssl" ).attr( "checked" , true ); 
	} 
	else 
	{ 
	    $( "#ssl" ).attr( "checked" , false ); 
	}
	
}



function checkCon()
{

	$( "#checkConButton" ).attr( "disabled" , "disabled" );
	$( "#checkConButton" ).text( chrome.i18n.getMessage( "page_options_connecting" ) );
	save_options();

	var protocol = localStorage.getItem( "ssl" + localStorage.getItem( "selectedDiskstation" ) );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var loginurl = protocol + "://" + localStorage.getItem( "host" + localStorage.getItem( "selectedDiskstation" ) ) + ":" + localStorage.getItem( "port" + localStorage.getItem( "selectedDiskstation" ) ) + "/index.cgi";

	var data = $.ajax(
	{ 
		type: "GET", 
		url: loginurl , 
		timeout: 3000,
		error: function( XMLHttpRequest, textStatus, errorThrown ) 
		{ 
			$( "#checkConButton" ).text( chrome.i18n.getMessage( "page_options_timeout" ) );
			setTimeout( function()
			{
				$( "#checkConButton" ).attr( "disabled" , "" );
				$( "#checkConButton" ).text( chrome.i18n.getMessage( "page_options_check_connection" ) );
			}, 2000 );				
		},	
		success: function(data) 
		{
		    if( data.indexOf( "400 Bad Request" ) > -1 )
		    {
		        $( "#checkConButton" ).text( chrome.i18n.getMessage( "page_options_timeout" ) );
			    setTimeout( function()
			    {
				    $( "#checkConButton" ).attr( "disabled" , "" );
				    $( "#checkConButton" ).text( chrome.i18n.getMessage( "page_options_check_connection" ) );
			    }, 2000 );	    
		    }
		    else
		    {
		        $( "#checkConButton" ).text( chrome.i18n.getMessage( "page_options_connected" ) );
			    setTimeout( function()
			    {
				    $( "#checkConButton" ).attr( "disabled" , "" );
				    $( "#checkConButton" ).text( chrome.i18n.getMessage( "page_options_check_connection" ) );
			    }, 2000 );	
		    }						
		}
	}).responseText;
			
}



function checkCred()
{

	$( "#checkCredButton" ).attr( "disabled" , "disabled" );
	$( "#checkCredButton" ).text( chrome.i18n.getMessage( "page_options_connecting" ) );
	save_options() ;

	var protocol = localStorage.getItem( "ssl" + localStorage.getItem( "selectedDiskstation" ) );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var loginurl = protocol + "://" + localStorage.getItem( "host" + localStorage.getItem( "selectedDiskstation" ) ) + ":" + localStorage.getItem( "port" + localStorage.getItem( "selectedDiskstation" ) ) + "/webman/modules/login.cgi?username=" + localStorage.getItem( "username" + localStorage.getItem( "selectedDiskstation" ) ) + "&passwd=" + localStorage.getItem( "password" + localStorage.getItem( "selectedDiskstation" ) );

	var token = "";

	var data = $.ajax(
	{ 
		type: "GET", 
		url: loginurl , 
		timeout: 3000,
		async: false,
		error: function( XMLHttpRequest, textStatus, errorThrown ) 
		{ 
			$( "#checkCredButton" ).text( chrome.i18n.getMessage( "page_options_timeout" ) );
			setTimeout( function()
			{
				$( "#checkCredButton" ).attr( "disabled" , "" );
				$( "#checkCredButton" ).text( chrome.i18n.getMessage( "page_options_check_credentials" ) );
			}, 2000 );
		}
	}).responseText;

	if ( data.indexOf( "success" ) > -1 )
	{
		$( "#checkCredButton" ).text( chrome.i18n.getMessage( "page_options_loggedin" ) );
		setTimeout( function()
		{
			$( "#checkCredButton" ).attr( "disabled" , "" );
			$( "#checkCredButton" ).text( chrome.i18n.getMessage( "page_options_check_credentials" ) );
		}, 2000 );
	}
	else
	{
		$( "#checkCredButton" ).text( chrome.i18n.getMessage( "page_options_incorrectlogin" ) );
		setTimeout( function()
		{
			$( "#checkCredButton" ).attr( "disabled" , "" );
			$( "#checkCredButton" ).text( chrome.i18n.getMessage( "page_options_check_credentials" ) );
		}, 2000 );
	}	
				
}



function debugMode()
{

	var checked = $( "#debug" ).attr( "checked" );

	if( checked == true )
	{
		localStorage.setItem( "debug" , "1" );
	}
	else
	{
		localStorage.setItem( "debug" , "0" );
	}
	save_options();
	
}		



$( document ).ready( function()
{

	$( "#pageTitle" ).text( chrome.i18n.getMessage( "page_options_title" ) );
	$( "#pageHeadingDiskStationDetails" ).text( chrome.i18n.getMessage( "page_options_diskstation_details" ) );
	$( "#pageOptionsHost" ).text( chrome.i18n.getMessage( "page_options_host" ) );
	$( "#pageOptionsPort" ).text( chrome.i18n.getMessage( "page_options_port" ) );
	$( "#checkConButton" ).text( chrome.i18n.getMessage( "page_options_check_connection" ) );
	$( "#pageHeadingLoginDetails" ).text( "Diskstation " + chrome.i18n.getMessage( "page_options_username" ) );
	$( "#pageOptionsUsername" ).text( chrome.i18n.getMessage( "page_options_username" ) );
	$( "#pageOptionsPassword" ).text( chrome.i18n.getMessage( "page_options_password" ) );
	$( "#checkCredButton" ).text( chrome.i18n.getMessage( "page_options_check_credentials" ) );
	$( "#pageOptionsPluginOptions" ).text( chrome.i18n.getMessage( "page_options_plugin_options" ) );
	$( "#pageOptionsDebug" ).text( chrome.i18n.getMessage( "page_options_debug" ) );
	$( "#pageOptionsConsoleOuput" ).text( chrome.i18n.getMessage( "page_options_console_output" ) );	
	
	restore_options();	
	populateDiskstations();	

	$( "#password,#username" ).keypress( function( event ) 
	{
        if( event.which == '13' ) 
        {
            event.preventDefault();
            save_options();
            checkCred();
        }        
    });
    
    $( "#port,#host" ).keypress( function( event ) 
	{
        if( event.which == '13' ) 
        {
            event.preventDefault();
            save_options();
            checkCon();
        }                
    });
    
    $( "#host" ).keyup( function( event ) 
	{                 
        $( "#diskstations option:selected" ).text( $( "#host" ).val() );         
    });
	
});

