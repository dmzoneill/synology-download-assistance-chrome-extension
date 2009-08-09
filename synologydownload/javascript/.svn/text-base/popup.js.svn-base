
var downloadids = new Array();
var refreshDownloads = false;	
var imageBase = "http://www.synologydownloadassistant.com/themes/default/";
var defaultStation = localStorage.getItem( "defaultStation" );


function downloadStatistics()
{

    this.maxSize = 50;
    this.currentSize = 0;
    this.downloadIntervals = new Array();
    this.uploadIntervals = new Array();
    
    this.add = function( val )
    {
    
        var up = round( val.up );
        var down = round( val.down );
        this.currentSize++;
        
        if( this.currentSize < this.maxSize )
        {
        
            this.downloadIntervals.push( down );
            this.uploadIntervals.push( up ); 
                       
        }
        else
        {
        
            this.downloadIntervals.shift();
            this.uploadIntervals.shift();
            this.downloadIntervals.push( down );
            this.uploadIntervals.push( up );
            
        } 
               
    }
    
    this.get = function( index )
    {
        
        return [ this.downloadIntervals[ index ] , this.uploadIntervals[ index ] ];
                
    }
    
}


function deleteTask( id , multi , page )
{

	var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var durl = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/downloadman.cgi";	

	$.ajax(
	{
		type: "POST",
	   	url: durl,
	   	data: "action=delete&idList=" + id,
	   	success: function( msg )
		{
			if( multi == 0 )
			{
				clearTimeout( refreshDownloads );
	     		extend( 'downloads' , page );						
			}
	   	}
	});
	
}



function deleteAll( page )
{

	clearTimeout( refreshDownloads );

	$.each( downloadids , function( index , item )
	{
		deleteTask( item , 1 , page ); 					
	});	

	extend( 'downloads' , page );	
					
}



function pauseTask( id , multi , page )
{

	var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var durl = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/downloadman.cgi";

	$.ajax(
	{
		type: "POST",
	   	url: durl,
	   	data: "action=stop&idList=" + id,
	   	success: function( msg )
		{
			if( multi == 0 )
			{
				clearTimeout( refreshDownloads );
	     		extend( 'downloads' , page );
			}
	   	}
	});	
				
}


function pauseAll( page )
{

	clearTimeout( refreshDownloads );

	$.each( downloadids , function( index , item )
	{
		pauseTask( item , 1 , page );				
	});	

	extend( 'downloads' , page );
			
}



function resumeTask( id , multi , page )
{

	var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var durl = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/downloadman.cgi";	

	$.ajax(
	{
		type: "POST",
	   	url: durl,
	   	data: "action=resume&idList=" + id,
	   	success: function( msg )
		{
			if( multi == 0 )
			{
				clearTimeout( refreshDownloads );
	     			extend( 'downloads' , page );
			}
	   	}
	});
	
}


function resumeAll( page )
{

	clearTimeout( refreshDownloads );

	$.each( downloadids , function( index , item )
	{
		resumeTask( item , 1 , page );				
	});	

	extend( 'downloads' , page );	
		
}



function clearTask()
{

	var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var url = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/downloadman.cgi";	

	$.post( url , 
		{ 
			action: "clear" , 
			idList: ""
		},
		function( data )
		{
			clearTimeout( refreshDownloads );
			extend( 'downloads' , 1 );
		}
	);
	
}



function changeDefaultStation()
{
    var station = $( "#diskstations option:selected" ).val();    
    localStorage.setItem( "defaultStation" , station );
    defaultStation = ( localStorage.getItem( "defaultStation" ) === null ) ? "1" : localStorage.getItem( "defaultStation" );
    chrome.extension.getBackgroundPage().updateDefaultStation();
    window.location.reload();
}



function populateStations()
{
    $( "#diskstations" ).empty();
    var count = localStorage.getItem( "DiskstationCount" );
    var station = localStorage.getItem( "defaultStation" );
    
    for( var g = 1; g < parseInt( count ) + 1; g++ )
    {
        var host = localStorage.getItem( "host" + g );
        
        if( g == station )
        {
            $( "#diskstations" ).append( "<option selected='selected' value=\"" + g + "\">" + host + "</option>" );
        }
        else
        {
            $( "#diskstations" ).append( "<option value=\"" + g + "\">" + host + "</option>" );
        }
    }   
}



function debug( msg )
{
	if( localStorage.getItem( "debug" ) == 1 )
	{
		console.log( msg );
	}
}



function downloadALL()
{

	extend( 'downloads' , 1 );
	debug( "Donwload all" );
	$( ".filedownloads" ).each( function( index )
	{
		var bgpage = chrome.extension.getBackgroundPage();
		var token = bgpage.getToken();
		$( this ).parent().next().html( "" );
		$( this ).parent().html( "" );
		bgpage.redirectDownload( token , encodeURIComponent( $( this ).val() ) );
	});
	
}


function downloadALLImages()
{

	extend( 'downloads' , 1 );
	debug( "Donwload Images" );
	$( ".imagedownloads" ).each( function( index )
	{
		var bgpage = chrome.extension.getBackgroundPage();
		var token = bgpage.getToken();
		$( this ).parent().next().html( "" );
		$( this ).parent().html( "" );
		bgpage.redirectDownload( token , encodeURIComponent( $( this ).val() ) );
	});
	
}



function extend( block , blockopt )
{

	clearTimeout( refreshDownloads );

	var blocks = new Array( 'simple', 'pluginsettings' , 'settings' , 'downloadall' , 'downloadimages' , 'downloads' );

	$.each( blocks , function ( index , blockid )
	{
		if( blockid == block )
		{
			$( '#' + blockid ).slideDown( 'slow' );
							
			if( block == "downloadall" )
			{	
			
				debug( "Request pages downloads" );

				chrome.tabs.getSelected( null , function( tab ) 
				{
				    if( tab.url.indexOf( "chrome://" ) == -1 )
				    {
				        chrome.tabs.sendRequest( tab.id, { method: "downloadall", types: localStorage.getItem( "downloadtypes" ) }, function( response ) 
					    {
			        		var downloads = response.data;
						    var bgpage = chrome.extension.getBackgroundPage();	

						    var types = [ 'aac','rar','rtf','bat','bmp','wav','mp4','midi','mov','cmd','mpa','7z','psd','other','mpg','iso','gz','avi','chm','psp','readme','doc','dll','sitx','mp3','png','ini','dmg','tar','ppt','img','htm','ocx','xls','gif','aiff','zip','tar','sys','inf','jpeg','txt' ];

						    debug( downloads.length );

						    if( downloads.length > 0 )
						    {	
							    $( "#downlist" ).empty();
							    $( "#downlist" ).append( "<fieldset><legend>" + chrome.i18n.getMessage( "page_popup_download" ) + "</legend><table id='downloadalltable' style='padding:0px;margin:0px;width:600px;'><tr id='filesrow1'></tr></table></fieldset>" );
							
							    var cols = 3;
							    var count = 0;
							    var row = 1;

							    $.each( downloads , function( index , data )
							    {
								    var details = data.split( "/" );
								    var ext = details[ details.length - 1 ].split( '.' );

								    if( count > 0 && count % cols == 0 )
								    {
									    row++;
									    $( "#downloadalltable" ).append( "<tr id='filesrow" + row + "'></tr>" );											
									    debug( row );
								    }
								

								    var icon = "other";
								    if( $.inArray( ext[ ext.length  - 1 ].toLowerCase() , types ) > -1 )
								    {
									    icon = ext[ ext.length  - 1 ].toLowerCase();
									    debug( 'in array:' + ext[ ext.length  - 1 ].toLowerCase() );
								    }
								    debug( icon );
								    $( "#filesrow" + row ).append( "<td width='20'><input type='checkbox' checked='true' class='filedownloads' value='" + data + "'></td><td width='180'><img style='max-width: 140px;max-height:140px;' src='" + imageBase + "filetypes/" + icon + ".png'><br><p class='word_wrap' style='width:140px'>" + details[ details.length - 1 ] + "</p></div></td>" );
								
								    count++;
								    debug( data );
							    });
							    $( "#downlist" ).append( "<br><div align='right'><img src='" + imageBase + "icons/accept-icon-32.png' class='popup_action_button' onclick='downloadALL()'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>" );
						    }
						    else
						    {
							    $( "#downlist" ).empty();
							    $( "#downlist" ).append( "<fieldset><legend>" + chrome.i18n.getMessage( "contentscript_downloadallfiles" ) + "</legend><br><br><br></fieldset>" );
						    }							    
			      		});
			      	}
			      	else
			      	{
			      	    $( "#downlist" ).empty();
					    $( "#downlist" ).append( "<fieldset><legend>" + chrome.i18n.getMessage( "page_popup_download" ) + "</legend><table id='downloadalltable' style='padding:0px;margin:0px;width:600px;'><tr id='filesrow1'></tr></table></fieldset>" );
			      	}
				});
			}
			else if( block == "downloadimages" )
			{
				chrome.tabs.getSelected( null , function( tab ) 
				{		
				    if( tab.url.indexOf( "chrome://" ) == -1 )
				    {					
		      			chrome.tabs.sendRequest( tab.id, { method: "downloadimages", types: localStorage.getItem( "imagetypes" ) }, function( response ) 
					    {
			        		var downloads = response.data;
						    var bgpage = chrome.extension.getBackgroundPage();

						    if( downloads.length > 0 )
						    {	
							    $( "#imglist" ).empty();
							    $( "#imglist" ).append( "<fieldset><legend>" + chrome.i18n.getMessage( "contentscript_downloadallimages" ) + "</legend><table id='downloadimagestable' style='padding:0px;margin:0px;width:600px;'><tr id='imagesrow1'></tr></table></fieldset>" );

							    var cols = 3;
							    var count = 0;
							    var row = 1;

							    $.each( downloads , function( index , data )
							    {
								    if( count > 0 && count % cols == 0 )
								    {
									    row++;
									    $( "#downloadimagestable" ).append( "<tr id='imagesrow" + row + "'></tr>" );											
									    debug( row );
								    }
								
								    $( "#imagesrow" + row ).append( "<td width='20'><input type='checkbox' checked='true' class='imagedownloads' value='" + data + "'></td><td width='180'><img style='max-width: 170px;max-height:170px;' src='" + data + "'></td>" );
								    count++;
								    debug( data );
							    });
							    $( "#imglist" ).append( "<br><div align='right'><img src='" + imageBase + "icons/accept-icon-32.png' class='popup_action_button' onclick='downloadALLImages()'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>" );
						    }
						    else
						    {
							    $( "#imglist" ).empty();
							    $( "#imglist" ).append( "<fieldset><legend>" + chrome.i18n.getMessage( "contentscript_downloadallimages" ) + "</legend><br><br><br></fieldset>" );
						    }
			      		});
			      	}
			      	else
			      	{
			      	    $( "#imglist" ).empty();
					    $( "#imglist" ).append( "<fieldset><legend>" + chrome.i18n.getMessage( "contentscript_downloadallimages" ) + "</legend><table id='downloadimagestable' style='padding:0px;margin:0px;width:600px;'><tr id='imagesrow1'></tr></table></fieldset>" );
			      	}
				});
			}
			else if( block == "downloads" )
			{		
				var protocol = localStorage.getItem( "ssl" + defaultStation );
				if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }				

				var checkurl = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/downloadman.cgi?_dc=1279970294489&action=getall&start=0&limit=1000&sort=task_id&dir=ASC";	
				var count = 0;

				downloadids = new Array();

				if( $( "#loaded" ).val() == "0" )
				{
					var loginurl = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/webman/modules/login.cgi?username=" + localStorage.getItem( "username" + defaultStation ) + "&passwd=" + localStorage.getItem( "password" + defaultStation );
					$( "body" ).append( "<iframe style=\"width:0px;height:0px;visibility:hidden;\" src='" + loginurl + "'></iframe>" );
					$( "#loaded" ).val( "1" );
				}
				
				$.getJSON( checkurl , function( data ) 
				{	
				console.log( data.items.length );
					if( data.items.length < 1 )
					{
						$( "#downloads" ).empty();
						$( "#downloads" ).append( "<fieldset><legend>" + chrome.i18n.getMessage( "page_popup_download" ) + "</legend><br>" + chrome.i18n.getMessage( "page_popup_queue_empty" ) + "<br><br></fieldset>" );
					}
					else
					{
						count = data.items.length;
						var pagecount = Math.ceil( count / 10 );

						if( blockopt < 1 )
						{
							blockopt = 1;
							var c_page = 1;
						}
						else if( blockopt > pagecount )
						{
							blockopt = pagecount;
							var c_page = pagecount;
						}
						else
						{
							var c_page = blockopt;
						}									

						var itemnum = 1;
						$( "#downloads" ).empty();
						$( "#downloads" ).append( "<fieldset><legend>" + chrome.i18n.getMessage( "page_popup_download" ) + "</legend><table id='downloadtable' style='padding:0px;margin:0px;width:600px;table-layout:fixed;word-wrap:break-word;'><tr><td width='18'></td><td width='18'></td><td style='padding-left:10px'><b>" + chrome.i18n.getMessage( "page_popup_filename" ) + "</b></td><td width='120' align='right'><b>" + chrome.i18n.getMessage( "page_popup_progress" ) + "</b></td></tr></table></fieldset>" );				
						$.each( data.items , function( index , itemdata )
						{	
							downloadids.push( itemdata.task_id );						
																
							if( itemnum < ( ( blockopt * 10 ) - 9 ) || itemnum > ( blockopt * 10 ) )
							{										
								itemnum++;
								return;
							}
							
							var progress = itemdata.progress;
							var text = itemdata.filename;
							var del = "<img src='" + imageBase + "icons/remove.png' class='popup_action_button' onclick=\"deleteTask('" + itemdata.task_id + "' , '0' , " + c_page + " )\">";
							var resume = "<img src='" + imageBase + "icons/next.png' class='popup_action_button' onclick=\"resumeTask('" + itemdata.task_id + "' , '0' , " + c_page + " )\">";
							var pause = "<img src='" + imageBase + "icons/pause.png' class='popup_action_button' onclick=\"pauseTask('" + itemdata.task_id + "' , '0' , " + c_page + " )\">";									
							var playpause = "";
							var speeds = "";

							if( itemdata.status == "TASK_PAUSED" )
							{
								playpause = resume;
								
							}
							else
							{
								playpause = pause;
								if( itemdata.status != "TASK_FINISHED" )
								{
								    speeds = "<span style='font-size:8pt'>" + itemdata.current_rate + " / " + itemdata.upload_rate + "</span>";
								}
							}						
							
							var row = "<tr><td width='20' valign='top'>" + del + "</td><td width='25' valign='top'>" + playpause + "</td><td style='padding-left:10px;font-size:10pt' valign='top'>" + text.toLowerCase() + "<br>";
							row += "&nbsp;&nbsp;&nbsp;" + speeds + "</td><td width='120' align='right' valign='top'>( " + progress + " ) " + itemdata.total_size + "</td></tr>";	
						
							$( "#downloadtable" ).append( row );		

							itemnum++;						
						}); 

												

						var del = "<img src='" + imageBase + "icons/remove.png' class='popup_action_button' onclick=\"deleteAll( " + c_page + " )\">";
						var resume = "<img src='" + imageBase + "icons/next.png' class='popup_action_button' onclick=\"resumeAll( " + c_page + " )\">";
						var pause = "<img src='" + imageBase + "icons/pause.png' class='popup_action_button' onclick=\"pauseAll( " + c_page + " )\">";				

						var next = "<img src='" + imageBase + "icons/skip_forward.png' class='popup_action_button' onclick=\"extend( 'downloads' , " + ( c_page + 1 ) + " )\">";
						var previous = "<img src='" + imageBase + "icons/skip_backward.png' class='popup_action_button' onclick=\"extend( 'downloads' , " + ( c_page - 1 ) + " )\">";								

						$( "#downloads" ).append( "<br><table id='downloadoptions' style='padding:0px;margin:0px;width:600px;'></table>" );
						$( "#downloadoptions" ).append( "<tr id='optrow'></tr>" );
						$( "#optrow" ).append( "<td width='20'>" + del + "</td>" );
						$( "#optrow" ).append( "<td width='20'>" + resume + "</td>" );
						$( "#optrow" ).append( "<td width='20'>" + pause + "</td>" );
						$( "#optrow" ).append( "<td align='right'>" + chrome.i18n.getMessage( "page_popup_page" ) + " : <input type='text' size='2' value='" + c_page + "' class='pagebox'> " + chrome.i18n.getMessage( "page_popup_pageof" ) + " " + "<input type='text' size='2' value='" + pagecount + "' class='pagebox' readonly>" + " " + previous + " " + next + "</td>" );													
						refreshDownloads = setTimeout( "extend( \'downloads\' , " + c_page + " )" , 7000 );
					}									
				});						
			}
		}
		else if( $( '#' + blockid ).css( 'display' ) == "block" )
		{
			$( '#' + blockid ).slideUp( 'fast' );
		}
		else
		{
			$( '#' + blockid ).css( 'display' , 'none' );
		}
	});	

}



function hijackChange()
{

	if( $( '#hijackcheckbox' ).is( ':checked' ) )
	{
		localStorage.setItem( "hijack" , "1" );
	}
	else
	{
		localStorage.setItem( "hijack" , "0" );
	}

	chrome.tabs.getAllInWindow( null , function( tabs ) 
	{
		$.each( tabs , function( index , selectedTab )
		{
  			chrome.tabs.sendRequest( selectedTab.id, { method: "hijackChange", hijacked: localStorage.getItem( "hijack" ) , types: localStorage.getItem( "hijacked" ) }, function( response ) 
			{
    		    debug( response.data );
  			});
		});
	});
	
}



function contextChange()
{

    var bgpage = chrome.extension.getBackgroundPage();

    if( $( "#contextCheckbox" ).is( ":checked" ) )
	{
		localStorage.setItem( "contextmenus" , "1" );
		bgpage.connectContextMenus();
	}
	else
	{
		localStorage.setItem( "contextmenus" , "0" );
		bgpage.disconnectContextMenus();
	} 
	 
}



function pluginSettingsLoad()
{

	var dtypes = localStorage.getItem( "downloadtypes" );
	var htypes = localStorage.getItem( "hijacked" );
	var itypes = localStorage.getItem( "imagetypes" );

	var arrDtypes = dtypes.split( ',' );

	for( var t = 0; t < arrDtypes.length; t++ )
	{
		$( "#downloadtypesSelect" ).append( "<option value='" + arrDtypes[ t ] + "'>" + arrDtypes[ t ] + "</option>" );
	}

	var arrHtypes = htypes.split( ',' );

	for( var t = 0; t < arrHtypes.length; t++ )
	{
		$( "#hijackedSelect" ).append( "<option value='" + arrHtypes[ t ] + "'>" + arrHtypes[ t ] + "</option>" );
	}	
	
	var arrItypes = itypes.split( ',' );

	for( var t = 0; t < arrItypes.length; t++ )
	{
		$( "#imageSelect" ).append( "<option value='" + arrItypes[ t ] + "'>" + arrItypes[ t ] + "</option>" );
	}		
	
	if( localStorage.getItem( "hijack" ) == "1" )
	{
	    $( '#hijackcheckbox' ).attr( 'checked' , true );
	}
	else
	{
		$( '#hijackcheckbox' ).attr( 'checked' , false );
	}
	
	if( localStorage.getItem( "contextmenus" ) == "1" )
	{
	    $( '#contextCheckbox' ).attr( 'checked' , true );
	}
	else
	{
		$( '#contextCheckbox' ).attr( 'checked' , false );
	}
	
}



function dsmSettingsLoad()
{

    var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }

	var url = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/webman/modules/ControlPanel/modules/downloadstation.cgi";	

	$.post( url , { action: "load" } , function( data )
	{
	    var json = $.parseJSON( data );
	    
	    var emuleEnabled = json.data.enable_emule;		    
	    
	    if( json.data.enabledownload == true )
	    {
	        $( '#downloadEnabledCheckbox' ).attr( 'checked' , true );		    
	    }
	    else
	    {
	        $( '#downloadEnabledCheckbox' ).attr( 'checked' , false );	
	    }
	
	});
	
	
	var url = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/generalsetting.cgi";	

	$.post( url , { action: "load" } , function( data )
	{
	    var json = $.parseJSON( data );	
	        
	    
	    if( json.data.email_enable == 1 )
	    {
	        $( '#emailEnabledCheckbox' ).attr( 'checked' , true );		    
	    }
	    else
	    {
	        $( '#emailEnabledCheckbox' ).attr( 'checked' , false );	
	    }
	    
	    if( json.data.dl_when_type == "now" )
	    {
	        $( "#scheduleNow" ).attr( "checked" , true );
	        $( "#fromhour" ).attr( "disabled" , "disabled" );
	        $( "#frommin" ).attr( "disabled" , "disabled" );
	        $( "#tohour" ).attr( "disabled" , "disabled" );
	        $( "#tomin" ).attr( "disabled" , "disabled" );
	    }
	    else
	    {
	        $( "#scheduleWhen" ).attr( "checked" , true );
	        $( "#fromhour" ).attr( "disabled" , "" );
	        $( "#frommin" ).attr( "disabled" , "" );
	        $( "#tohour" ).attr( "disabled" , "" );
	        $( "#tomin" ).attr( "disabled" , "" );
	    }
	    
	    
	    var fromhour = $( "#fromhour" );
        var frommin = $( "#frommin" );
        var tohour = $( "#tohour" );
        var tomin = $( "#tomin" );
        
        fromhour.empty();  		
        frommin.empty();  
        tohour.empty();  
        tomin.empty();  
        
        var selectedFromHour = "";	
        var selectedToHour = "";	
        
        for( var r = 0; r < 24; r++ )
        {								        
            if( r < 10 )
            {
                var thehour = "0" + r;       								        
            }
            else
            {
                var thehour = r; 							        
            }
            
            if( json.data.from_hr == r )
            {
                selectedFromHour = "selected"
            }
            else
            {
                selectedFromHour = ""
            }
            
            if( json.data.to_hr == r )
            {
                selectedToHour = "selected"
            }
            else
            {
                selectedToHour = ""
            }
            
            fromhour.append( "<option value='" + r + "' " + selectedFromHour + ">" + thehour + "</option>" );
            tohour.append( "<option value='" + r + "' " + selectedToHour + ">" + thehour + "</option>" );				    
        }
        
        
        var selectedFromMin = "";	
        var selectedToMin = "";
        
        for( var r = 0; r < 60; r++ )
        {
            if( r < 10 )
            {
                var themin = "0" + r;       								        
            }
            else
            {
                var themin = r; 							        
            }
            
            if( json.data.from_min == r )
            {
                selectedFromMin = "selected"
            }
            else
            {
                selectedFromMin = ""
            }
            
            if( json.data.to_min == r )
            {
                selectedToMin = "selected"
            }
            else
            {
                selectedToMin = ""
            }
            
            frommin.append( "<option value='" + r + "' " + selectedFromMin + ">" + themin + "</option>" );
            tomin.append( "<option value='" + r + "' " + selectedToMin + ">" + themin + "</option>" );				    
        }
    });
    
        
    var url = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/btsetting.cgi";	

    $.post( url , { action: "load" } , function( data )
    {
        var json = $.parseJSON( data );	
        
        var selectedDownloadMax = "";
        $( "#maximumDownloadTasksNumField" ).empty(); 
         		
        for( var r = 1; r < 21; r++ )
        {
            if( json.data.dl_max_tasks == r )
            {
                selectedDownloadMax = "selected";
            }
            else
            {
                selectedDownloadMax = "";
            }
            
            $( "#maximumDownloadTasksNumField" ).append( "<option value='" + r + "' " + selectedDownloadMax + ">" + r + "</option>" );				    
        }		
        
        
        $( "#rapidshareUser" ).val( json.data.rapidshare_user );
        $( "#rapidsharePass1" ).val( json.data.rapidshare_pass );
        $( "#rapidsharePass2" ).val( json.data.rapidshare_pass2 );
        
        
        $( "#megauploadUser" ).val( json.data.megaupload_user );
        $( "#megauploadPass1" ).val( json.data.megaupload_pass );
        $( "#megauploadPass2" ).val( json.data.megaupload_pass2 );
        
        
        if( json.data.dl_port_type == "default_port" )
        {
            $( "#portRangeDefault" ).attr( "checked" , true );
	        $( "#portFrom" ).attr( "disabled" , "disabled" );
	        $( "#portTo" ).attr( "disabled" , "disabled" );  		                     
        }
        else
        {
            $( "#portRangeCustom" ).attr( "checked" , true );
            $( "#portFrom" ).attr( "disabled" , "" );
	        $( "#portTo" ).attr( "disabled" , "" );   
        }
        
        $( "#portFrom" ).val( json.data.dl_start_port );
	    $( "#portTo" ).val( json.data.dl_end_port );   
        
        $( "#maxUploadSpeed" ).val( json.data.dl_upload_rate );
        $( "#maxDownloadSpeed" ).val( json.data.dl_download_rate );
        
        var btencrypt = json.data.btencrypt;
        var auto = ( btencrypt == "auto" ) ? "selected" : "";
        var always = ( btencrypt == "always" ) ? "selected" : "";
        var disable = ( btencrypt == "disable" ) ? "selected" : "";
        
        $( "#downloadProtocol" ).empty();
        $( "#downloadProtocol" ).append( "<option value='auto' " + auto + ">" + chrome.i18n.getMessage( "page_popup_auto" ) + "</option>" );
        $( "#downloadProtocol" ).append( "<option value='always' " + always + ">" + chrome.i18n.getMessage( "page_popup_always" ) + "</option>" );
        $( "#downloadProtocol" ).append( "<option value='disable' " + disable + ">" + chrome.i18n.getMessage( "page_popup_disable" ) + "</option>" );

        $( "#maxPeers" ).val( json.data.btmaxpeers );
        
        if( json.data.enablebtdht == true )
        {
            $( "#enableDHT" ).attr( "checked" , true );
            $( "#DHTport" ).attr( "disabled" , "" );
        }
        else
        {
            $( "#enableDHT" ).attr( "checked" , false );
            $( "#DHTport" ).attr( "disabled" , "disabled" );
        }
        
        $( "#DHTport" ).val( json.data.btdhtport );
        
        $( "#sharePercentage" ).val( json.data.seeding_ratio );
        
        var mins = chrome.i18n.getMessage( "page_popup_minutes" );
        var hours = chrome.i18n.getMessage( "page_popup_hours" );
        
        var timeEntries = [ 
            chrome.i18n.getMessage( "page_popup_ignore" ) + "|0" , 
            "90 " + mins + "|90" , 
            "2 " + hours + "|120" , 
            "3 " + hours + "|180" , 
            "4 " + hours + "|240" , 
            "5 " + hours + "|300" , 
            "6 " + hours + "|360" , 
            "7 " + hours + "|420" , 
            "8 " + hours + "|480" , 
            "9 " + hours + "|560" , 
            "10 " + hours + "|600" , 
            "12 " + hours + "|720" , 
            "16 " + hours + "|960" , 
            "20 " + hours + "|1200" , 
            "24 " + hours + "|1440" , 
            "30 " + hours + "|1800" , 
            "36 " + hours + "|2160" , 
            "48 " + hours + "|2880" , 
            "60 " + hours + "|3600" , 
            "72 " + hours + "|4320" , 
            "96 " + hours + "|5760" , 
            chrome.i18n.getMessage( "page_popup_forever" ) + "|-1" 
        ];
        
        var timeVal = "";
        var timeSelected = "";
        var timeText = "";
        
        $( "#shareTime" ).empty();
        
        for( var g = 0; g < timeEntries.length; g++ )
        {
            var bits = timeEntries[ g ].split( "|" );
            timeVal = bits[ 1 ];
            timeText = bits[ 0 ];
            
            timeSelected = ( timeVal == json.data.seeding_interval ) ? "selected" : "" ;

            $( "#shareTime" ).append( "<option value='" + timeVal + "' " + timeSelected + ">" + timeText + "</option>" );
        }

        	
    });		
    
    populateStations();       
	
}



function dsmSettingsChanged( element )
{

	debug( "changed " + $( element ).attr( 'id' ) );
	
	if( $( element ).attr( 'id' ) == "scheduleWhen" )
	{
	    $( "#fromhour" ).attr( "disabled" , "" );
	    $( "#frommin" ).attr( "disabled" , "" );
	    $( "#tohour" ).attr( "disabled" , "" );
	    $( "#tomin" ).attr( "disabled" , "" );
	}
	
	if( $( element ).attr( 'id' ) == "scheduleNow" )
	{
	    $( "#fromhour" ).attr( "disabled" , "disabled" );
	    $( "#frommin" ).attr( "disabled" , "disabled" );
	    $( "#tohour" ).attr( "disabled" , "disabled" );
	    $( "#tomin" ).attr( "disabled" , "disabled" );
	}
	
	
	if( $( element ).attr( 'id' ) == "portRangeDefault" )
	{
	    $( "#portFrom" ).attr( "disabled" , "disabled" );
	    $( "#portTo" ).attr( "disabled" , "disabled" );  
	}		
	
	if( $( element ).attr( 'id' ) == "portRangeCustom" )
	{
	    $( "#portFrom" ).attr( "disabled" , "" );
	    $( "#portTo" ).attr( "disabled" , "" );  
	}
	
	
	if( $( element ).attr( 'id' ) == "enableDHT" && $( element ).attr( 'checked' ) == false )
	{
	    $( "#DHTport" ).attr( "disabled" , "disabled" ); 
	}
	else
	{
	    $( "#DHTport" ).attr( "disabled" , "" ); 
	}
	
	var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }
	
	
	var url = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/webman/modules/ControlPanel/modules/downloadstation.cgi";	
	
	if( $( "#downloadEnabledCheckbox" ).attr( "checked" ) == true )
	{		
	    $.post( url , 
	        {
	           action: 'apply' ,
	           enabledownload: ( $( "#downloadEnabledCheckbox" ).attr( "checked" ) == true ) ? "on" : "off" ,
	           download_volume: 1		    
	        } ,
	        function( response )
	        {
	            debug( response );
	        }			
	    );
	}
	else
	{
	    $.post( url , 
	        {
	           action: 'apply'	    
	        } ,
	        function( response )
	        {
	            debug( response );
	        }			
	    );
	    return;
	}
	
	
	var url = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/generalsetting.cgi";
	
	if( $( "#emailEnabledCheckbox" ).attr( "checked" ) == true )
	{			
	    $.post( url , 
	        {
	            action: 'apply' ,
	            enabledownload: 'on' ,
                dl_when_type: ( $( "#scheduleNow" ).attr( 'checked' ) == true ) ? "now" : "schedule" ,
                from_hr: $( "#fromhour option:selected" ).val() ,
                to_hr: $( "#tohour option:selected" ).val() ,
                from_min: $( "#frommin option:selected" ).val() ,
                to_min: $( "#tomin option:selected" ).val() ,
                email_enable: "on"
	        
	        } ,
	        function( response )
	        {
	            debug( response );
	        }			
	    );
	}
	else
	{
	    $.post( url , 
	        {
	            action: 'apply' ,
	            enabledownload: 'on' ,
                dl_when_type: ( $( "#scheduleNow" ).attr( 'checked' ) == true ) ? "now" : "schedule" ,
                from_hr: $( "#fromhour option:selected" ).val() ,
                to_hr: $( "#tohour option:selected" ).val() ,
                from_min: $( "#frommin option:selected" ).val() ,
                to_min: $( "#tomin option:selected" ).val() 
	        
	        } ,
	        function( response )
	        {
	            debug( response );
	        }			
	    );    		
	}		
	
	
	var url = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/btsetting.cgi";	
	
	if( $( "#enableDHT" ).attr( "checked" ) == true )
	{
	    $.post( url , 
	        {
	            action: 'apply',
	            dl_ordering: 'request',
	            dl_max_tasks: $( "#maximumDownloadTasksNumField option:selected" ).val(),
	            rapidshare_user: $( "#rapidshareUser" ).val(), 
                rapidshare_pass: $( "#rapidsharePass1" ).val(),
                rapidshare_pass2: $( "#rapidsharePass2" ).val(),
                megaupload_user: $( "#megauploadUser" ).val(), 
                megaupload_pass: $( "#megauploadPass1" ).val(), 
                megaupload_pass2: $( "#megauploadPass2" ).val(), 
                nzbserver: '',
                nzbport: '119',
                nzbnumconn: '2',
                enabledownload: 'on', 
                emule_tcp_port: '4662', 
                emule_udp_port: '4672', 
                dl_port_type: ( $( "#portRangeDefault" ).attr( "checked" ) == true ) ? "default_port" : "manual_port", 
                dl_start_port: $( "#portFrom" ).val(), 
                dl_end_port: $( "#portTo" ).val(), 
                dl_upload_rate: $( "#maxUploadSpeed" ).val(), 
                dl_download_rate: $( "#maxDownloadSpeed" ).val(), 
                btencrypt: $( "#downloadProtocol option:selected" ).val(), 
                btmaxpeers: $( "#maxPeers" ).val(), 
                enablebtdht: 'on',
                btdhtport: $( "#DHTport" ).val(), 
                seeding_ratio: $( "#sharePercentage" ).val(), 
                seeding_interval: $( "#shareTime option:selected" ).val()		    
	        } ,
	        function( response )
	        {
	            debug( response );
	        }			
	    );			
	}
	else
	{		
	    $.post( url , 
	        {
	            action: 'apply',
	            dl_ordering: 'request',
	            dl_max_tasks: $( "#maximumDownloadTasksNumField option:selected" ).val(),
	            rapidshare_user: $( "#rapidshareUser" ).val(), 
                rapidshare_pass: $( "#rapidsharePass1" ).val(),
                rapidshare_pass2: $( "#rapidsharePass2" ).val(),
                megaupload_user: $( "#megauploadUser" ).val(), 
                megaupload_pass: $( "#megauploadPass1" ).val(), 
                megaupload_pass2: $( "#megauploadPass2" ).val(), 
                nzbserver: '',
                nzbport: '119',
                nzbnumconn: '2',
                enabledownload: 'on', 
                emule_tcp_port: '4662', 
                emule_udp_port: '4672', 
                dl_port_type: ( $( "#portRangeDefault" ).attr( "checked" ) == true ) ? "default_port" : "manual_port", 
                dl_start_port: $( "#portFrom" ).val(), 
                dl_end_port: $( "#portTo" ).val(), 
                dl_upload_rate: $( "#maxUploadSpeed" ).val(), 
                dl_download_rate: $( "#maxDownloadSpeed" ).val(), 
                btencrypt: $( "#downloadProtocol option:selected" ).val(), 
                btmaxpeers: $( "#maxPeers" ).val(), 
                seeding_ratio: $( "#sharePercentage" ).val(), 
                seeding_interval: $( "#shareTime option:selected" ).val()		    
	        } ,
	        function( response )
	        {
	            debug( response );
	        }			
	    );	
	}	
		
}



function pluginSettingsChanged()
{

	var downtypes = "";
	$( "#downloadtypesSelect option" ).each(function () 
	{
		downtypes += $( this ).val() + ",";
    });

	downtypes = downtypes.substr( 0 , downtypes.length - 1 );

	
	var jacktypes = "";
	$( "#hijackedSelect option" ).each(function () 
	{
		jacktypes += $( this ).val() + ",";
    });

	jacktypes = jacktypes.substr( 0 , jacktypes.length - 1 );
	
	var imagetypes = "";
	$( "#imageSelect option" ).each(function () 
	{
		imagetypes += $( this ).val() + ",";
    });

	imagetypes = imagetypes.substr( 0 , imagetypes.length - 1 );

	localStorage.setItem( "downloadtypes" , downtypes );
	localStorage.setItem( "hijacked" , jacktypes );
	localStorage.setItem( "imagetypes" , imagetypes );
	
}



function downloadLink()
{
    
    $( '#downloadlink' ).val( chrome.i18n.getMessage( "page_popup_standby" ) );
	var bgpage = chrome.extension.getBackgroundPage();
	var token = bgpage.getToken();
	bgpage.redirectDownload( token , encodeURIComponent( $( '#downloadlink' ).val() ) );
	$( '#downloadlink' ).val( chrome.i18n.getMessage( "page_popup_downloading" ) );
	
}



function adjustTypes( what , action )
{

	if( what == "downloadall" )
	{
		if( action == "add" )
		{
			var valadd = $( "#downloadTypesadd" ).val();
			$( "#downloadtypesSelect" ).prepend( "<option value='" + valadd + "'>" + valadd + "</option>" );
			$( "#downloadTypesadd" ).val( "" );
		}
		else
		{
			$( "#downloadtypesSelect option:selected" ).each(function () 
			{
				var next = $( this ).next();
				$( this ).remove();
				$( next ).attr( 'selected' , 'selected' );
		    });
		}
	}
	else if( what == "downloadimages" )
	{
		if( action == "add" )
		{
			var valadd = $( "#imageTypesadd" ).val();
			$( "#imageSelect" ).prepend( "<option value='" + valadd + "'>" + valadd + "</option>" );
			$( "#imageTypesadd" ).val( "" );
		}
		else
		{
			$( "#imageSelect option:selected" ).each(function () 
			{
				var next = $( this ).next();
				$( this ).remove();
				$( next ).attr( 'selected' , 'selected' );
		    });
		}
	}
	else
	{
		if( action == "add" )
		{
			var valadd = $( "#hijackTypesadd" ).val();
			$( "#hijackedSelect" ).prepend( "<option value='" + valadd + "'>" + valadd + "</option>" );
			$( "#hijackTypesadd" ).val( "" );
		}
		else
		{
			$( "#hijackedSelect option:selected" ).each(function () 
			{
				var next = $( this ).next();
				$( this ).remove();
				$( next ).attr( 'selected' , 'selected' );
		    });
		}
	}
	
	pluginSettingsChanged();
	
}



function documentReady()
{

    setTimeout( "dsmSettingsLoad()" , 10 ); // workaround for disk spin up
    pluginSettingsLoad();		
	
	// Tranlsations
	$( "#page_popup_url" ).text( chrome.i18n.getMessage( "page_popup_url" ).toLowerCase() );
	$( "#page_popup_download" ).val( chrome.i18n.getMessage( "page_popup_download" ).toLowerCase() );
	$( "#page_popup_downloadall" ).text( chrome.i18n.getMessage( "page_popup_downloadall" ).toLowerCase() );
	$( "#page_popup_downall" ).text( chrome.i18n.getMessage( "page_popup_downloadall" ).toLowerCase() );
	$( "#page_popup_hijack" ).text( chrome.i18n.getMessage( "page_popup_hijack" ).toLowerCase() );
	$( "#page_popup_settings" ).text( "dsm " + chrome.i18n.getMessage( "page_popup_settings" ).toLowerCase() );	
	$( "#page_popup_settings_plugin" ).text( "plugin settings" + chrome.i18n.getMessage( "page_popup_settings_plugin" ).toLowerCase() );		
	$( "#page_popup_downloadallimages" ).text( chrome.i18n.getMessage( "page_popup_downloadallimages" ).toLowerCase() );
	$( "#page_popup_downallimages" ).text( chrome.i18n.getMessage( "page_popup_downloadallimages" ).toLowerCase() );
	$( "#hijack" ).text( chrome.i18n.getMessage( "page_popup_hijacklinks" ).toLowerCase() );
	$( "#page_popup_downloadmanager" ).text( chrome.i18n.getMessage( "page_popup_downloadmanager" ).toLowerCase() );
	
	$( "#page_popup_download_settings" ).text( chrome.i18n.getMessage( "page_popup_downloadmanager" ).toLowerCase() );
	$( "#page_popup_download_schedule" ).text( chrome.i18n.getMessage( "page_popup_download_schedule" ).toLowerCase() );
	$( "#page_popup_station_enabled" ).text( chrome.i18n.getMessage( "page_popup_station_enabled" ).toLowerCase() );
	$( "#page_popup_email_enabled" ).text( chrome.i18n.getMessage( "page_popup_email_enabled" ).toLowerCase() );
	$( "#page_popup_maximum_downloads" ).text( chrome.i18n.getMessage( "page_popup_maximum_downloads" ).toLowerCase() );
	$( "#page_popup_immediately" ).text( chrome.i18n.getMessage( "page_popup_immediately" ).toLowerCase() );
	$( "#page_popup_from" ).text( chrome.i18n.getMessage( "page_popup_from" ).toLowerCase() );
	$( "#page_popup_to" ).text( chrome.i18n.getMessage( "page_popup_to" ).toLowerCase() );
	$( "#page_popup_daily" ).text( chrome.i18n.getMessage( "page_popup_daily" ).toLowerCase() );
	$( "#page_popup_rapidshare_download" ).text( chrome.i18n.getMessage( "page_popup_rapidshare_download" ).toLowerCase() );
	$( "#page_popup_username" ).text( chrome.i18n.getMessage( "page_options_username" ).toLowerCase() );
	$( "#page_popup_password" ).text( chrome.i18n.getMessage( "page_options_password" ).toLowerCase() );
	$( "#page_popup_confirmpassword" ).text( chrome.i18n.getMessage( "page_popup_confirmpassword" ).toLowerCase() );		
	$( "#page_popup_megaupload_download" ).text( chrome.i18n.getMessage( "page_popup_megaupload_download" ).toLowerCase() );
	$( "#page_popup_username2" ).text( chrome.i18n.getMessage( "page_options_username" ).toLowerCase() );
	$( "#page_popup_password2" ).text( chrome.i18n.getMessage( "page_options_password" ).toLowerCase() );
	$( "#page_popup_confirmpassword2" ).text( chrome.i18n.getMessage( "page_popup_confirmpassword" ).toLowerCase() );
	$( "#page_popup_bittorrent_download_settings" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_download_settings" ).toLowerCase() );
	$( "#page_popup_bittorrent_portrange" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_portrange" ).toLowerCase() );
	$( "#page_popup_default_port_range" ).text( chrome.i18n.getMessage( "page_popup_default_port_range" ).toLowerCase() );
	$( "#page_popup_following_port_range" ).text( chrome.i18n.getMessage( "page_popup_following_port_range" ).toLowerCase() );
	$( "#page_popup_from2" ).text( chrome.i18n.getMessage( "page_popup_from" ).toLowerCase() );
	$( "#page_popup_to2" ).text( chrome.i18n.getMessage( "page_popup_to" ).toLowerCase() );
	$( "#page_popup_bittorrent_rates" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_rates" ).toLowerCase() );
	$( "#page_popup_max_upload" ).text( chrome.i18n.getMessage( "page_popup_max_upload" ).toLowerCase() );
	$( "#page_popup_max_download" ).text( chrome.i18n.getMessage( "page_popup_max_download" ).toLowerCase() );
	$( "#page_popup_bittorent_network_settings" ).text( chrome.i18n.getMessage( "page_popup_bittorent_network_settings" ).toLowerCase() );
	$( "#page_popup_bittorrent_protocol_encryption" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_protocol_encryption" ).toLowerCase() );
	$( "#page_popup_bittorrent_peers_torrent" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_peers_torrent" ).toLowerCase() );
	$( "#page_popup_bittorrent_enable_dht" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_enable_dht" ).toLowerCase() );
	$( "#page_popup_bittorrent_dht_port" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_dht_port" ).toLowerCase() );
	$( "#page_popup_bittorrent_autostop" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_autostop" ).toLowerCase() );
	$( "#page_popup_bittorrent_autostop_when" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_autostop_when" ).toLowerCase() );
	$( "#page_popup_bittorrent_share_ratio" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_share_ratio" ).toLowerCase() );
	$( "#page_popup_or" ).text( chrome.i18n.getMessage( "page_popup_or" ).toLowerCase() );
	$( "#page_popup_bittorrent_time_reaches" ).text( chrome.i18n.getMessage( "page_popup_bittorrent_time_reaches" ).toLowerCase() );
	$( "#page_popup_browser_integration" ).text( chrome.i18n.getMessage( "page_popup_browser_integration" ).toLowerCase() );
	$( "#page_popup_control_panel" ).text( chrome.i18n.getMessage( "page_popup_control_panel" ).toLowerCase() );
	$( "#page_popup_download_station" ).text( chrome.i18n.getMessage( "page_popup_download_station" ).toLowerCase() );
	$( "#page_popup_right_click_menus" ).text( chrome.i18n.getMessage( "page_popup_right_click_menus" ).toLowerCase() + " ( beta )" );
	$( "#page_popup_translate" ).text( chrome.i18n.getMessage( "page_popup_translate" ).toLowerCase() );
	
	// Images
	$( "#imageSettings" ).attr( 'src' , imageBase + "icons/process.png" );
	$( "#imageSettingsPlugin" ).attr( 'src' , imageBase + "icons/process.png" );
	$( "#imageDownloadManager" ).attr( 'src' , imageBase + "icons/database.png" );
	$( "#imageDownloadAll" ).attr( 'src' , imageBase + "icons/folder_down.png" );
	$( "#imageDownloadImages" ).attr( 'src' , imageBase + "icons/image_multi.png" );
	$( ".imageRemove" ).attr( 'src' , imageBase + "icons/remove.png" );
	$( ".imageAdd" ).attr( 'src' , imageBase + "icons/add.png" );
	$( "#imageBottomLogo" ).attr( 'src' , imageBase + "general/dsm.png" );
	$( "#imageHijack" ).attr( 'src' , imageBase + "icons/attachment.png" );
	$( "#imageAccept" ).attr( 'src' , imageBase + "icons/accept.png" );
	
	$( "#controlPanelLinkImage" ).attr( 'src' , imageBase + "icons/Control-Panel.png" );
	$( "#downloadStationLinkImage" ).attr( 'src' , imageBase + "icons/Download-Station.png" );
	$( "#translateLinkImage" ).attr( 'src' , imageBase + "icons/multilingual.png" );
	
	var protocol = localStorage.getItem( "ssl" + defaultStation );
	if( protocol == 1 ) { protocol = "https"; } else { protocol = "http"; }		
	
	var url = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/";		
	$( "#controlPanelLink" ).attr( 'href' , url );
	
	var url = protocol + "://" + localStorage.getItem( "host" + defaultStation ) + ":" + localStorage.getItem( "port" + defaultStation ) + "/download/";		
	$( "#downloadStationLink" ).attr( 'href' , url );
	
	var url = "http://www.synologydownloadassistant.com/community.php?action=translate";
	$( "#translateLink" ).attr( 'href' , url );  
	
	$( "#downloadTypesadd" ).keypress( function( event ) 
	{
        if( event.which == '13' ) 
        {
            event.preventDefault();
            adjustTypes( 'downloadall' , 'add' );
        }        
    });
    
    $( "#imageTypesadd" ).keypress( function( event ) 
	{
        if( event.which == '13' ) 
        {
            event.preventDefault();
            adjustTypes( 'downloadimages' , 'add' );
        }        
    });
    
    $( "#hijackTypesadd" ).keypress( function( event ) 
	{
        if( event.which == '13' ) 
        {
            event.preventDefault();
            adjustTypes( 'hijack' , 'add' );
        }        
    });
    
    $( "#downloadlink" ).keypress( function( event ) 
	{
        if( event.which == '13' ) 
        {
            event.preventDefault();
            downloadLink();
        }        
    });

}



$( document ).ready( function()
{

    documentReady();  

    var bgpage = chrome.extension.getBackgroundPage();
    
    if( bgpage.getToken() == false )
    {
        chrome.tabs.create( { 'url' : chrome.extension.getURL( 'options.html' ) } , function( tab ) 
	    {
	    });
	    
	    $( "body" ).html( "<center><br/>" + chrome.i18n.getMessage( "page_background_loginerror" ) + "</center>" );
    }
    
});


