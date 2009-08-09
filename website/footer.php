		
    <center>
	<script type="text/javascript"><!--
		google_ad_client = "ca-pub-5778014405992846";
		/* 468x60, created 11/05/10 */
		google_ad_slot = "1459255560";
		google_ad_width = 468;
		google_ad_height = 60;
		//-->
		</script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
    <table>
    <tr>
    <td>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="5SLAXLZUTHPCS">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </td>
    <td>
        <a href="http://ie.linkedin.com/in/dmzoneill" >
          <img src="http://www.linkedin.com/img/webpromo/btn_viewmy_160x33.png" width="160" height="33" border="0" alt="View David O Neill's profile on LinkedIn">
        </a>
    </td>
    </tr>
    </table>
	<br>
	A <a href='http://www.feeditout.com'>feeditout.com</a> production
    <br>
    </center>
    <div class="push"></div> 
   
</div>

<!-- Footer -->
<div id="footer">	
    <div class="shell">
	    <p class="left">
		    <a href="index">Home</a>
		    <span>|</span>
		    <a href="features">Features</a>
		    <span>|</span>
		    <a href="community">Community</a>
		    <span>|</span>
		    <a href="download">Download</a>
		    <span>|</span>
		    <a href="contact">Contact</a>
		    <?php
		    
		        if( $loggedin == "1" )
		        {
		            print " <span>|</span> <a href=\"?logout=true\">Logout</a>";
		        }
	        
	            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	            print "Online : <b>" . getOnline( "plugin" ) . "</b> plugin users,  <b>" . getOnline( "site" ) . "</b> website users";
	        
	        ?>
	    </p>	        
	    <p class="right">&copy; 2010 Synology Download Assistant.</p>
    </div>
</div>
<!-- End Footer -->

</body>
</html>
