<?php

include( "header.php" );

?>

    <!-- Main -->
    <div id="main">
	    <div class="shell">
	    
	    <?php
	    
	        if( isset( $_GET[ 'thanks' ] ) && $_GET[ 'thanks' ] == "complete" )
	        {
	        
	            ?>
	        	    <br><br><br><br><br><br>
		    
                    <center>
                        <br>
                        <h3>Thanks for donating!</h3>
                        <br>
                        Every cent will improve this plugin!<br><br>Your transaction has been completed, <br>and a receipt for your donation has been emailed to you. 
                        <br>
                    </center>	
                    
	            <?php   
	              
	        }
	        else if( isset( $_GET[ 'thanks' ] ) && $_GET[ 'thanks' ] == "cancelled" )
	        {
	            ?>
	        	    <br><br><br><br><br><br>
		    
                    <center>
                        <br>
                        <h3>Maybe next time!</h3>
                        <br>
                        Every cent will improve this plugin!
                        <br>
                    </center>	
                    
	            <?php   	       
	        }
	    
	    ?>
   
    	</div>	
    </div>    
    <!-- End Main -->
    
<?php

include( "footer.php" );

?>
