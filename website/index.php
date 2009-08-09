<?php

include( "header.php" );

if( isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) && ( strpos( $_SERVER[ 'HTTP_USER_AGENT' ] , 'MSIE' ) == false ) )
{
	include( "slider.php" );
}

?>

    <!-- Main -->
    <div id="main">
	    <div class="shell">
		
		    <!-- Cols -->
		    <div class="three-cols">
			    <div class="cl">&nbsp;</div>
			
			    <!-- Col -->
			    <div class="col">
			    
				    <h3 class="ico ico1">Synology meets &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;></h3>
				    <p>Synology DiskStations are complete small business solutions for backup, storage and file management.</p>
				    <p>The network attached storage implements a web interface controlling all aspects of the device along with some cool features like a download center.  </p>
				    <p class="more"><a href="http://www.synology.com">Synology</a></p>
				
			    </div>
			    <!-- End Col -->
			
			    <!-- Col -->
			    <div class="col">
			    
				    <h3 class="ico ico2">Google Chrome to form &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ></h3>
				    <p>The web browser is arguably the most important piece of software on your computer.  </p>
				    <p>You spend much of your time online inside a browser: When you search, chat, email, shop, bank, read the news, and watch videos online, you often do all this using a browser.  One of the great features of chrome is its plugin support!</p>
				    <p class="more"><a href="http://www.google.com/chrome/">Google Chrome</a></p>
				
			    </div>
			    <!-- End Col -->
			
			    <!-- Col -->
			    <div class="col col-last">
			    
				    <h3 class="ico ico3">Synology Download Assistant</h3>
				    <p>This plugin is in a league of its own, offering functionality and integration unparalleled by any other plugin.</p>
				    <p>This feature rich software is in a constant stage of development, meaning you get the best and most up to date features provided by any plugin on the web.</p>
				    <p class="more"><a href="features.php">More</a></p>
				
			    </div>
			    <!-- End Col -->
			
			    <div class="cl">&nbsp;</div>
			
		    </div>
		    <!-- End Cols -->
		
	    </div>
    </div>
    <!-- End Main -->

<?php

include( "footer.php" );

?>
