
      
      
var toggleDone = false;
var debuggingMode = <?php print isset( $_GET[ 'debug' ] ) ? "true" : "false";  ?>;

$( document ).ready( function()
{

});


function debug( msg )
{
    if( debuggingMode == true )
    {
        console.log( msg );
    }
}


function toggleDoneRows()
{
    if( toggleDone == false )
    {
        $( "#toggleDone" ).text( "Show done" );
        $( ".rowDone" ).css( "display" , "none" );
        toggleDone = true;
        debug( "toggleDoneRows() : true" );
        
    }
    else
    {
        $( "#toggleDone" ).text( "Hide done" );
        $( ".rowDone" ).css( "display" , "block" );
        toggleDone = false;
        debug( "toggleDoneRows() : false" );
    }	    
}


function sendPassword()
{
    var email = $( "#newEmail" ).val();
    debug( "sendPassword() email : "  + email );
    
    $.post( "index.php" , { newEmail: email } , function( data )
    {
        if( data == "1" )
        {	            
            $( "#result" ).text( "Password Sent to this email address" );	                
        }
        else if( data == "-1" )
        {
            $( "#result" ).text( "Email address already registered.  I sent you a new password :)" );
        } 
        else
        {
            $( "#result" ).text( "I was unable to send an email to this address :(" );
        } 
        debug( "sendPassword() post result : "  + data );  	        
    });	    
}


function sendMesssage()
{
    var sub = $( "#subject" ).val();
    var msg = $( "#message" ).val();
    
    $.post( "index.php" , { subject: sub , body: msg } , function( data )
    {
    
        $( "#sendfeedback" ).val( "Email sent, thanks for your feedback" );	
        $( "#sendfeedback" ).css( "width" , "400px" );	
        $( "#sendfeedback" ).attr( "disabled" , "disabled" );	

        debug( "sendMessage() post result : "  + data );  
        	        
    });	    
}


function login()
{
    var email = $( "#loginEmail" ).val();
    var pass = $( "#password" ).val();
    debug( "login() email : "  + email ); 
    debug( "login() pass : "  + pass ); 
    
    var redirect = document.location.href.split( 'redirect=' );
    redirect = "document.location.href='" + redirect[ 1 ] + "'";
    
    console.log( redirect );
    
    $.post( "index.php" , { loginEmail: email , password: pass } , function( data )
    {
        if( data == "1" )
        {	            
            $( "#result" ).text( "Successful login..  Standy..." );
            setTimeout( '' + redirect + '' , 1500 );
        }
        else
        {
            $( "#result" ).text( "Incorrect login details :(" );
        } 
        debug( "login() post result : "  + data );   	        
    });	    
}


function updateTranslation( ele , lang )
{	        
    var num = $( ele ).attr( 'id' ).split( 'sage' );
    num = num[ 1 ];
    
    $( "#updating" + num ).empty();	        
    $( "#updating" + num ).add( "img" ).attr( "id" , "loadingimg" + num );
    $( "#loadingimg" + num ).attr( "src" , "images/loading.gif" );
    
    var randomnumber = Math.floor( Math.random() * 1111111 );
    var thesubject = $( "#subject" + num ).text();
    var themessage = $( "#message" + num ).val();
    
    debug( "updateTranslation( " + ele + " , " + lang + " ) subject : " + thesubject );   
    debug( "updateTranslation( " + ele + " , " + lang + " ) message : " + themessage );   

    $.post( "index.php?num=" + randomnumber , { language: lang , subject: thesubject , text: themessage } , function( resp )
    {
        $( "#updating" + num ).html( "" ); 	   
        $( ele ).removeClass( "changeThis" );
        $( ele ).addClass( "changeThisDone" );
        $( "#number" + num ).removeClass( "h5notDone" );
        $( "#number" + num ).addClass( "h5Done" );
        $( "#number" + num ).removeClass( "h5notDone" );
        $( "#number" + num ).addClass( "h5Done" );
        $( "#row" + num ).removeClass( "rowNotDone" );
        $( "#row" + num ).addClass( "rowDone" );
        debug( "updateTranslation( " + ele + " , " + lang + " ) post result : " + resp ); 
    });	    
}	   
