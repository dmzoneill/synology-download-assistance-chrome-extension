<?php

include( "header.php" );

?>

    <!-- Main -->
    <div id="main">
	    <div class="shell">
	    			
		    <?php
		      
		        if( $loggedin == "1" )
		        {
		        
		            ?>
		                <center>
                
                            <br><br><br><br>    
		                
		                    <fieldset class="contact" style='height:150px;width:800px'> 			
		                
                                <legend>Send Email</legend> 
			                    <div> <br><br><br><br>
				                    <label for="username">Subject :</label> <input type='text' id='subject' class='inputBox' style='width:480px'>
			                    </div> 
			                    <div> <br>
				                    <label for="username">Messsage :</label> <textarea style='width:480px;height:200px' id='message' class='inputBox'></textarea>
			                    </div>
			                    <div> <br>
				                    <label for="password">&nbsp;</label> <input type='button' class='button' value='Send Message' onclick='sendMesssage()' id='sendfeedback'> <br><br> <span id='result'></span>
			                    </div> 
			                
			                </fieldset> 
			                
			            
			            </center>
						 <br><br><br><br>  
						  <br><br><br><br>  
						   <br><br><br><br>  
						    <br><br><br><br>  
		            
		            <?php
		        
		        }
		        else
		        {    
		        
		    ?>
		    
		        <script type='text/javascript'>
                
                    function goToLogin()
                    {
                        console.log( "redirect" );
                        document.location.href = 'login.php?redirect=' + encodeURI( document.location.href );    
                    }
                    
                    setTimeout( "goToLogin()" , 100 );                    
                    
                </script>   
		    
		    <?php
		    
		        }
		        
		    ?>
			
	    </div>	
    </div>    
    <!-- End Main -->
    
<?php

include( "footer.php" );

?>
