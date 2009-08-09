<?php

include( "header.php" );

?>

    <!-- Main -->
    <div id="main">
	    <div class="shell">
	    
		    <br><br><br><br><br><br>
		    
		    <center>
		        <a href='http://www.synologydownloadassistant.com/downloads/synologydownload.crx' border='0'><img src='images/download.jpg'></a>
		        <br>
		        <h3>Thanks for trying the Synology Download Assistant</h3>
		        <br>
		        Be quick to leave a rating and leave feedback!
		        <br>
		        <br>
		        <span id='downloadCountdownMessage'></span>
		    </center>	
		    
		    <script type='text/javascript'>
		    
		        var timeout = 11;
		    
		        function downloadCountdown()
		        {
		        
		            if( timeout == 1 )
		            {   
						window.open("http://www.synologydownloadassistant.com/downloads/synologydownload.crx");
                        //window.location.href = "http://www.synologydownloadassistant.com/downloads/synologydownload.crx";
		                $( "#downloadCountdownMessage" ).html( "<a style='text-decoration:none' href='http://www.synologydownloadassistant.com/downloads/synologydownload.crx' target='_blank'>Thanks for downloading</a>" );
		            }
		            else
		            {
		            
		                timeout = timeout - 1;
		                setTimeout( "downloadCountdown()" , 1000 );
		                $( "#downloadCountdownMessage" ).html( "<a style='text-decoration:none' href='http://www.synologydownloadassistant.com/downloads/synologydownload.crx' target='_blank'>Downloading extension in " + timeout + " seconds</a>" );
		                
		            }
		            
		        }
		        
		        downloadCountdown();
		    
		    </script>
		    
		    <br><br><br><br><br><br>
	    </div>	
    </div>    
    <!-- End Main -->
    
<?php

include( "footer.php" );

?>
