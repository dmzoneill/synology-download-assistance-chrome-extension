<?php

include( "header.php" );

?>

    <!-- Main -->
    <div id="main">
	    <div class="shell">
		
		    <center>
                
                <br><br><br><br>            
                
                 <fieldset class="login" style='height:150px;width:600px'> 
                 
			        <legend>Login Details</legend> 
			        <div> <br>
				        <label for="username">Email address :</label> <input type='text' id='loginEmail' class='inputBox'> 
			        </div> 
			        <div> <br>
				        <label for="password">Password :</label> <input type='password' id='password' class='inputBox'>
			        </div> 
			        <div> <br>
				        <label for="password">&nbsp;</label> <input type='button' class='button' value='Login' onclick='login()'>
			        </div> 
			        
		        </fieldset> 
		        
		        <fieldset class="contact" style='height:150px;width:600px'> 			
		        
                    <legend>New user</legend> 
			        <div> <br>
				        <label for="username">Email address :</label> <input type='text' id='newEmail' class='inputBox'>
			        </div> 
			        <div> <br>
				        <label for="password">&nbsp;</label> <input type='button' class='button' value='Send Password' onclick='sendPassword()'>
			        </div> 
			        
			    </fieldset> 
			    
			    <fieldset style='height:150px;width:600px'> 			
		        
                    <legend>Status</legend> 
			        <div> <br>
				        <label for="username">&nbsp;</label> <span id='result'>Register again to have a new password sent to you!</span>
				    </div> 			        
			        
			    </fieldset>
			
            </center>
            
	    </div>	
    </div>    
    <!-- End Main -->
    
<?php

include( "footer.php" );

?>
