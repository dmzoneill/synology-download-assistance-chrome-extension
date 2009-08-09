
var loggedon = false;
var downloadAllContext;
var downloadImagesContext;
var lastKnownEmail = "";
var laststate = null;
var laststatetime = null;
var alerts = new Array();
var browserLanguage = null;
var _gaq = _gaq || [];
var version = null;
var onlineVersion = null;
var lastKnownDownload = null;
var lastKnownCallback = null;
var lastKnownMessage = null;
var speedDirection = "down";
var defaultStation = ( localStorage.getItem( "defaultStation" ) === null ) ? "1" : localStorage.getItem( "defaultStation" );



function debug( msg )
{

	if( localStorage.getItem( "debug" ) == 1 )
	{
		console.log( msg );
	}
	
}


function getToken()
{	
	
	var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var loginurl = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":";
	loginurl += localStorage.getItem( "port" + defaultStation );
	loginurl += "/download/download_redirector.cgi?action=login&username=";
	loginurl += encodeURIComponent( localStorage.getItem( "username" + defaultStation ) );
	loginurl += "&passwd=" + encodeURIComponent( localStorage.getItem( "password" + defaultStation ) );

	var token = "";
	debug( "Backgroundpage : getToken() : loginurl = " );
	debug( loginurl );

	var data = $.ajax({ type: "GET", url: loginurl , async: false }).responseText;

	if ( data.indexOf( "true" ) > -1 )
	{
		var stuff = data.split( "," );
		var tokenstr = stuff[ 0 ].split( "\"" );
		token = tokenstr[ 3 ];	
	}
	else
	{
		token = false;
	}	
	
	debug( "Backgroundpage : getToken() : " + "Session Token : " + token );

	return token;
	
}


function redirectDownload( token , url )
{	
	
	var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var downloadurl = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":";
	downloadurl += localStorage.getItem( "port" + defaultStation ) + "/download/download_redirector.cgi?id=";
	downloadurl += token + "&action=addurl&username=";
	downloadurl += encodeURIComponent( localStorage.getItem( "username" + defaultStation ) ) + "&url=" + url;

	var data = $.ajax({ type: "GET", url: downloadurl , async: false }).responseText;

	_gaq.push( ['_trackEvent' , 'Download' , 'url' , url ] );

	debug( "Backgroundpage : redirectDownload( token , url ) : " + "Requested : " + downloadurl );
	debug( "Backgroundpage : redirectDownload( token , url ) : " + "Response : " + data );

	return data;	
		
}


function backgroundPageSignalHandler( request , sender , callback )
{

	if( loggedon == false )
	{			
		var token = getToken( encodeURIComponent( localStorage.getItem( "username" + defaultStation ) ) , encodeURIComponent( localStorage.getItem( "password" ) ) );
		loggedon = true;
	}

	if( request.action == 'add' )
	{		
		var token = getToken( encodeURIComponent( localStorage.getItem( "username" + defaultStation ) ) , encodeURIComponent( localStorage.getItem( "password" ) ) );

		if( !token )
		{		
			var msg = {
				title: chrome.i18n.getMessage( "page_background_loginerror" ),
				msg: request.download,
				sticky: true,
				type: "error"
			};	
			
			if( request.notifySource )
			{
			    chrome.tabs.getSelected( null , function( tab ) 
			    {					    
			        chrome.tabs.sendRequest( tab.id, { method: "contextMenuSignal", data: msg }, function() 
				    {
				        
				    });
				});
			}
			else
			{
			    callback( msg );					
			}							

			debug( "Backgroundpage : backgroundPageSignalHandler( request , sender , callback ) : Login Error" );

			return;
		}
		else
		{

			var data = redirectDownload( token , encodeURIComponent( request.download ) );

			var msg = {
			    title: chrome.i18n.getMessage( "contentscript_sucesssubmit" ),
				msg: request.download,
				sticky: false,
				type: "success"
			};
			
			if( request.notifySource )
			{
			    chrome.tabs.getSelected( null , function( tab ) 
			    {					    
			        chrome.tabs.sendRequest( tab.id, { method: "contextMenuSignal", data: msg }, function() 
				    {
				        
				    });
				});
			}
			else
			{
			    callback( msg );					
			}				

			_gaq.push( [ '_trackEvent' , 'Download' , 'torrent', request.download ] );
			debug( "Backgroundpage : backgroundPageSignalHandler( request , sender , callback ) : Login Successful" );

			callback( msg ); 				
		}
	}
	else if( request.action == 'logemail' )
	{
	    lastKnownEmail = request.email;       			
	}
	else if( request.action == 'notifyupdate' )
	{
	    var msg = {
	        title: request.title,			        
			msg: request.body,
			sticky: false,
			type: "success"
		};	

	    chrome.tabs.getSelected( null , function( tab ) 
	    {					    
	        chrome.tabs.sendRequest( tab.id, { method: "updateNotify", data: msg }, function() 
		    {

		    });
		});
	          			
	}
	else if( request.action == 'notifyTranslate' )
	{
	    var msg = {
	        title: request.title ,			        
			msg: request.body ,
			sticky: true,
			type: "success"
		};	

	    chrome.tabs.getSelected( null , function( tab ) 
	    {					    
	        chrome.tabs.sendRequest( tab.id, { method: "updateTranslate", data: msg }, function() 
		    {

		    });
		});
	          			
	}
	else if( request.action == 'loadJavascript' )
	{
	    
	    $.ajax(
	    {
	        type: "GET",
            url: request.url,
            dataType: "text",
            success: function(data) 
            {
                callback( data );
            }
        });
	          			
	}
	else if( request.action == 'status' )
	{
		var protocol = localStorage.getItem( "ssl" + defaultStation );
		if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

		var checkurl = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/downloadman.cgi?_dc=1279970294489&action=getall&start=0&limit=1000&sort=task_id&dir=ASC";			

		debug( "Backgroundpage : backgroundPageSignalHandler() : status request " );

		$.getJSON( checkurl , function( data ) 
		{		
			debug( data );	
            
			$.each( data.items , function( index , itemdata )
			{
				if( itemdata.status == "TASK_FINISHED" )
				{	
					var alertmsg = { 
					    title: chrome.i18n.getMessage( "page_popup_download" ) + " " + chrome.i18n.getMessage( "page_background_completed" ),
						msg: itemdata.filename,
						sticky: false,
						type: "status"
					};								
					
					$.merge( alerts , [ alertmsg ] );
					debug( "Backgroundpage : backgroundPageSignalHandler() : request.action status " );		
					debug( alertmsg );
	
				}
			}); 	
		
			if( alerts.length > 0 )
			{		
			    for( var t = 0; t < alerts.length; t++ )
			    {
			        if( downloadNotificationTracker( data.items , alerts[ t ].msg ) == true )
			        {
			            alerts[ t ] = null;
			        }
			    }
			    	
				callback( alerts );
				alerts = new Array();
			}						
		});			
	}
	else if( request.action == 'translate' )
	{
		var data = { 
			key: request.option,
			value: chrome.i18n.getMessage( request.option )
		};	
		callback( data ); 			
	}
	else if( request.action == 'setting' )
	{	
		if( request.option == "hijack" )
		{
			var data = { 
				val: localStorage.getItem( request.option ),
				types : localStorage.getItem( "hijacked" )
			};	
			callback( data ); 	
			debug( "Backgroundpage : backgroundPageSignalHandler() : request.setting " + request.option );	
		}
		else 
		{
			var data = { 
				val: localStorage.getItem( request.option )
			};	
			callback( data ); 	
			debug( "Backgroundpage : backgroundPageSignalHandler() : request.setting " + request.option );	
		}				
	}	
	else
	{
		// do nothing
	}
	
}	


function toInteger( number )
{ 

	return Math.round( parseFloat( number ) ); 
	
}



function notifyUserLocale() 
{     
   
    if( lastKnownEmail != "" )
    {
        var chromeVersion = navigator.userAgent.toLowerCase().match(/chrome\/[0-9.]+/)[0];
        $.post( "http://www.synologydownloadassistant.com/index.php" , { synologyUser: lastKnownEmail , synologyUserLocale: browserLanguage , pluginVersion: version , chromeVersion: chromeVersion } );                              
    }  
          
}


function showSpeed( direction , showvalue , speed )
{
    
    if( showvalue == true )
	{
		if( direction == 'down' )
		{
			var iconBadgeColor = {
				color: [ 0 , 255 , 0 , 255 ]
			}
		}
		else
		{
			var iconBadgeColor = {
				color: [ 255 , 0 , 0 , 255 ]
			}
		}

		chrome.browserAction.setBadgeBackgroundColor( iconBadgeColor );	

		var iconBadge = {
			text: speed
		}

		chrome.browserAction.setBadgeText( iconBadge );				
	}
    
}


function beginSpeedUpdater()
{	
	
	var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }
	
	var checkurl = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/downloadman.cgi?_dc=1279970294489&action=getall&start=0&limit=1000&sort=task_id&dir=ASC";			

	$.getJSON( checkurl , function( data ) 
	{	
	    var speed;
		if( speedDirection == "down" )
		{
		    var speed = toInteger( data.total_up );
		    
		    if( !isNaN( speed ) )
		    {
		        showSpeed( "up" , true , "" + speed + "" );     
		    }		    
		}
		else
		{
		    var speed = toInteger( data.total_down );
		    
		    if( !isNaN( speed ) )
		    {
		        showSpeed( "down" , true , "" + speed + "" );
		    }	
		}
		
		speedDirection = ( speedDirection == "down" ) ? "up" : "down";
		
	});	
			
}


function initInstallation()
{

    if( localStorage.getItem( "username" + defaultStation ) === null || localStorage.getItem( "password" + defaultStation ) === null || localStorage.getItem( "host" + defaultStation ) === null || localStorage.getItem( "port" + defaultStation ) === null || localStorage.getItem( "hijack" ) === null )
    {

	    chrome.tabs.create( { 'url' : chrome.extension.getURL( 'options.html' ) } , function( tab ) 
	    {
		    debug( "Backgroundpage : backgroundPageSignalHandler() : Settings not ready " );
		    _gaq.push( [ '_trackEvent' , 'Installation' , 'Version' , version ] );
		    _gaq.push( [ '_trackEvent' , 'Installation' , 'Language' , browserLanguage ] );
		    localStorage.setItem( "version" , version );
	    });
	
    }   

}


function connectContextMenus()
{

    if( localStorage.getItem( "contextmenus" ) == "1" )	
    {     
       
        var types = localStorage.getItem( "downloadtypes" ).split( ',' );
        var downloadAllpatterns = new Array();
        
        for( var b = 0; b < types.length; b++ )
        {
            downloadAllpatterns.push( "*://*/*" + types[ b ] );
        }              
        
        downloadAllContext = chrome.contextMenus.create(
        {
            "title": chrome.i18n.getMessage( "page_popup_download" ), 
            "contexts":[ "link" , "video" , "audio" ],
            "targetUrlPatterns": downloadAllpatterns,
            "onclick": function( itemClicked ) 
            { 
                var href = itemClicked.linkUrl;
                var src = itemClicked.srcUrl;    
                
                if( href != undefined )
                    link = href;
                else if( src != undefined )
                    link = src;
                else
                    link = null;

                if( link != null )
                {
                    backgroundPageSignalHandler( { 'action' : 'add' , download: link , notifySource: 'self' } , 'notify' );
                }                 
            }
        });            
        
                        
        var types = localStorage.getItem( "imagetypes" ).split( ',' );
        var downloadImagepatterns = new Array();
        
        for( var b = 0; b < types.length; b++ )
        {
            downloadImagepatterns.push( "*://*/*" + types[ b ] );
        }  
        
        downloadImagesContext = chrome.contextMenus.create(
        {
            "title": chrome.i18n.getMessage( "page_popup_download" ), 
            "contexts":[ "image" ],
            "targetUrlPatterns": downloadImagepatterns,
            "onclick": function( itemClicked ) 
            { 
                backgroundPageSignalHandler( { 'action' : 'add' , download: itemClicked.srcUrl , notifySource: 'self' } , 'notify' ); 
            } 
        }); 
          
    }   
              
}



function disconnectContextMenus()
{

    chrome.contextMenus.remove( downloadAllContext );
    chrome.contextMenus.remove( downloadImagesContext );
    
}



function checkForUpdate()
{
	debug( "checkForUpdate : checking for update" );	
    $.ajax(
    {
	    type: 'GET',
	    url: "http://www.synologydownloadassistant.com/version.txt",
	    dataType: "text",
	    success: function( html )
	    {
			debug( "checkForUpdate : got update version file" );
		    _gaq.push( [ '_trackEvent' , 'Version' , 'check' , '' ] );
		    var re1 = new RegExp( "[0-9]\.[0-9].[0-9]" );
			var m1 = re1.exec( html );
			
			var re2 = new RegExp( "[0-9]\.[0-9]\.[0-9]\.[0-9]" );
			var m2 = re2.exec( html );
			
			if ( m1 != null || m2 != null ) 
		    {
			    var s = "";
			    
			    if( m1 != null )
			    {
			        s = "";
			        
			        for ( var i = 0; i < m1.length; i++ ) 
			        {
      					s = s + m1[ i ];
				    }
			    }	
			    
			    if( m2 != null )
			    {
			        s = "";
			        
			        for ( var i = 0; i < m2.length; i++ ) 
			        {
      					s = s + m2[ i ];
				    }
			    }			    
			    
				debug( "checkForUpdate : online version : " + s );
				
				onlineVersion = s;	
				
			    if( onlineVersion != version )
			    {		
			        var header = chrome.i18n.getMessage( "page_background_newupdate" );
			        var main = chrome.i18n.getMessage( "page_background_newupdate_help" );
				    backgroundPageSignalHandler( { 'action' : 'notifyupdate' , title: header , body: main } , 'notify' ); 
				    _gaq.push( [ '_trackEvent' , 'Version' , 'outdated' , version ] );		
					debug( "checkForUpdate : notify user of update" );
			    }
			}
	    }   
    });	        

}


function backgroundForceConnect()
{

    var protocol = localStorage.getItem( "ssl" + defaultStation );				
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var loginurl = protocol + "://" + localStorage.getItem( "host" + defaultStation );
	loginurl += ":" + localStorage.getItem( "port" + defaultStation ) + "/webman/modules/login.cgi?username=";
	loginurl += localStorage.getItem( "username" + defaultStation ) + "&passwd=";
	loginurl += localStorage.getItem( "password" + defaultStation );

	$( "body" ).append( "<iframe style=\"width:0px;height:0px\" src='" + loginurl + "'></iframe>" );
	
}


function checkTranslation()
{           
            
    var chromeVersion = navigator.userAgent.toLowerCase().match(/chrome\/[0-9.]+/)[0];      
    var url = "http://www.synologydownloadassistant.com/index.php?translationLocale=" + browserLanguage + "&chromeVersion=" + chromeVersion + "&pluginVersion=" + version; 
       
    $.get( url , function( data )
    {
        
        var parts = data.split( "|" );
        var lang = parts[ 0 ];
        var exists = parseInt( parts[ 1 ] );
        var entries = parseInt( parts[ 2 ] );
        var entriesDone = parseInt( parts[ 3 ] );
        var percent = parseInt( parts[ 4 ] );
        
        var head = lang + " " + percent + "%";
        var translate = chrome.i18n.getMessage( "page_popup_translate" ).substring(0, 1).toUpperCase() + chrome.i18n.getMessage( "page_popup_translate" ).substring(1).toLowerCase();
        var main = "<a href='http://www.synologydownloadassistant.com/community.php?action=translate' style='color:yellow;'>" + head + "</a>";
        
        if( exists == 1 && percent < 100 )
        {
            backgroundPageSignalHandler( { 'action' : 'notifyTranslate' , title: translate , body: main } , 'notify' );                    
        }
    
    });                 

}        


function getLanguage()
{

    chrome.i18n.getAcceptLanguages( function( languageList ) 
    {
        var langs = [ 'am' , 'ar' , 'bg' , 'bn' , 'ca' , 'cs' , 'da' , 'de' , 'el' , 'en' , 'en_GB' , 'en_US' , 'es' , 'es_419' , 'et' , 'fi' , 'fil' , 'fr' , 'gu' , 'he' , 'hi' , 'hr' , 'hu' , 'id' , 'it' , 'ja' , 'kn' , 'ko' , 'lt' , 'lv' , 'ml' , 'mr' , 'nb' , 'nl' , 'or' , 'pl' , 'pt' , 'pt_BR' , 'pt_PT' , 'ro' , 'ru' , 'sk' , 'sl' , 'sr' , 'sv' , 'sw' , 'ta' , 'te' , 'th' , 'tr' , 'uk' , 'vi' , 'zh' , 'zh_CN' , 'zh_TW' ];
    
        var languages = languageList.join( "," ).split( "," );
        
        var userLang;
        
        for( var c = 0; c < languages.length; c++ )
        {
            var temp = languages[ c ].replace( "-", "_" );
            if( $.inArray( temp , langs ) > -1 )
            {
                userLang = temp;
                break;
            }
        }
        
        browserLanguage = userLang;
    });
    
}



function detectExtensionVersion()
{
	chrome.management.getAll( function( extensions )
    {    
        for( var g = 0; g < extensions.length; g++ )
        {
            if( extensions[ g ].id == chrome.i18n.getMessage("@@extension_id") )
            {
                version = extensions[ g ].version;
            }
        }        
    });
    
}



function trackOnline()
{
    
    var chromeVersion = navigator.userAgent.toLowerCase().match(/chrome\/[0-9.]+/)[0];
    var url = "http://www.synologydownloadassistant.com/index.php?pluginTrack=true&chromeVersion=" + chromeVersion + "&pluginVersion=" + version;        
    $.get( url );     
       
}



function downloadNotificationTracker( queue , link )
{

    var exists = -1;
    
    // add all current downloads into an array
    var temp = new Array();    
    for( var r = 0; r < queue.length; r++ )
    {
        temp.push( queue[ r ].filename );    
    }
    
    var notificationCount = 1;
    var notificationNotified = false;
    var next = localStorage.getItem( "downloadNotification" + notificationCount );
    
    while( next != null )
    {
        
        if( next == link )
        {
            notificationNotified = true;    
        }      
        
        exists = $.inArray( next , temp );
        
        if( exists == -1 )
        {         
                    
            var currentCount = notificationCount + 1;
            
            var delnext = localStorage.getItem( "downloadNotification" + currentCount );
            
            while( delnext != null )
            {
                localStorage.setItem( "downloadNotification" + ( currentCount - 1 ) , delnext );
                currentCount++;
                delnext = localStorage.getItem( "downloadNotification" + currentCount );
            }
            
            if( ( currentCount - 1 ) == notificationCount )
            {
                localStorage.removeItem( "downloadNotification" + notificationCount );
            }
            else
            {
                localStorage.removeItem( "downloadNotification" + ( currentCount - 1 ) );    
            }
            
            notificationCount--;            
        }   
               
        notificationCount++;   
        next = localStorage.getItem( "downloadNotification" + notificationCount );  
    }
    
    
    if( notificationNotified == false )
    {
        localStorage.setItem( "downloadNotification" + notificationCount , link );    
    }
         
    return notificationNotified;
}



function updateDefaultStation()
{
    defaultStation = ( localStorage.getItem( "defaultStation" ) === null ) ? "1" : localStorage.getItem( "defaultStation" );
}



function init()
{

    localStorage.setItem( "version" , version );
    
    _gaq.push( [ '_setAccount' , 'UA-1014155-5' ] );
    _gaq.push( [ '_trackPageview' ] );
    _gaq.push( [ '_trackEvent' , 'Version' , 'Current' , version ] );	
    _gaq.push( [ '_trackEvent' , 'Language' , 'Current' , browserLanguage ] );

    var ga = document.createElement( 'script' ); 
    ga.type = 'text/javascript'; 
    ga.async = true;
    ga.src = ( 'https:' == document.location.protocol ? 'https://ssl' : 'http://www' ) + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; 
    s.parentNode.insertBefore( ga , s );
    
    var seconds = 1000;
    var minutes = seconds * 60;

    initInstallation();

    chrome.extension.onRequest.addListener( backgroundPageSignalHandler );					
					
    connectContextMenus(); 	
    		
	beginSpeedUpdater();
	
	window.setInterval( beginSpeedUpdater , 4000 );
			
	notifyUserLocale();
	
    window.setInterval( notifyUserLocale , ( minutes * 15 ) );
    
    checkForUpdate();
    
    window.setInterval( checkForUpdate , ( minutes * 60 ) );
    
    backgroundForceConnect();		
    
    setTimeout( "checkTranslation()" , 1000 );
    
    window.setInterval( checkTranslation , ( minutes * 60 ) );
    
    trackOnline();
    
    window.setInterval( trackOnline , ( minutes * 10 ) );
    
    updateDefaultStation();

}



$( document ).ready( function ()
{		

    detectExtensionVersion();
    getLanguage();
    setTimeout( "init()" , 2000 );    
   				
});	

