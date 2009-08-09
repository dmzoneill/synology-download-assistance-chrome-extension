<?php

include( "header.php" );

?>
    <!-- Main -->
    <div id="main">
	    <div class="shell">		
	    
	    <?php
	        
	        if( !isset( $_GET[ 'action' ] ) )
	        {
	        
	    ?>
	        <!-- Cols -->
		    <div class="three-cols">
			    <div class="cl">&nbsp;</div>
			
			    <!-- Col -->
			    <div class="col">
    
				
			    </div>
			    <!-- End Col -->
			
			    <!-- Col -->
			    <div class="col">
				
					<br><br>
				    <h3 class="ico ico1">Translate</h3>
				    <p>Help translate Synology Download Assistant</p>
				    <p>Chrome supports 43 languages and your help can make accessibility easier for people in your region</p>
				    <p class="more"><a href="community.php?action=translate">Translate</a><br><br></p>
				    <p><img src='images/community/worldmap.png' width='130'><br><br></p>
			    
				
			    </div>
			    <!-- End Col -->
			
			    <!-- Col -->
			    <div class="col col-last">
			   				
			    </div>
			    <!-- End Col -->
			
			    <div class="cl">&nbsp;</div>
			
		    </div>
		    <!-- End Cols -->	
		    
	    <?php
	    
	        }
	        else if( $_GET[ 'action' ] == "translate" )
	        {
	        
	    ?>
	    
			<div id="content" style="padding-left:15px">

            <?php

                if( isset( $_GET[ 'lang' ] ) && $loggedin == "1" )
                {
                
            ?>
                <table width='980'>	   
	                <tr><td colspan='3' style='border-bottom: 1px dashed #cccccc'><h3 style='margin-left:60px'>Editing <?php print $_GET[ 'lang' ]; ?></h3><br></td></tr>
	                <tr><td colspan='3'><br></td></tr>
	                <?php
	                 
	                    $lang = mysql_real_escape_string( $_GET[ 'lang' ] );
	                    $langExists = $stream->do_query( "select count(*) from languages where lang='$lang'" , "one" );  
	                    
	                    if( $langExists == "1" )
	                    {
	                        $messagesEng = $stream->do_query( "select * from translations where lang='en_US' order by title ASC" , "array" );  
	                        $messagesTarget = $stream->do_query( "select * from translations where lang='$lang' order by title ASC" , "array" );  
	                        
	                        $yTarget = 0;
	                        
	                        for( $y = 0; $y < count( $messagesEng ); $y++ )
	                        {
	                            $msgEng = $messagesEng[ $y ];
	                            $titleEng = $msgEng[ 1 ];
	                            $messageEng = $msgEng[ 2 ];
	                            $update = ""; 
	                            
	                            if( $r % 2 == 0 )
	                            {          
	                                $color = "tdColor1";	        
	                            }	 
	                            else
	                            {
	                                $color = "tdColor2";
	                            }  
	                            
	                            if( count( $messagesTarget ) > 0 )
	                            {
	                                $msgTarget = $messagesTarget[ $yTarget ];
	                                $titleTarget = $msgTarget[ 1 ];
	                                $messageTarget = $msgTarget[ 2 ];	       
	                                $emailTarget = $msgTarget[ 5 ];	     
	                                $ipTarget = $msgTarget[ 6 ];	                    
	                            }	                            
	                            
	                            
	                            if( count( $messagesTarget ) > 0 && $titleEng == $titleTarget )
	                            {
	                                $donethis = "changeThisDone";
	                                $h5donethis = "h5Done";	                    
	                                $translateThis = $messageTarget;	                    
	                                $donerow = "rowDone";
	                                
	                                $ip = explode( "." , $ipTarget );    
	                                $ip = $ip[ 0 ] . ".*.*.*";
	                                $emailTarget =  explode( "@" , $stream->do_query( "select email from users where id='$emailTarget'" , "one" ) );	
	                                $emailUser = $emailTarget[ 0 ];	 
	                                $update = "Last update : $emailUser@$ip";
	                                
	                                $yTarget++;
	                            }
	                            else
	                            {
	                                $h5donethis = "h5notDone";	 
	                                $donethis = "changeThis";
	                                $translateThis = $messageEng;
	                                $donerow = "rowNotDone";
	                            }
	                            
	                            
	                            print "<tr class='$donerow' id='row$y'>\n";
	                                print "<td class='" . $color . "' style='padding-top:0px;padding-left:15px'><h5 class='$h5donethis' id='number$y'>" . ( $y + 1 ) . "</h5></td>\n";
	                                print "<td class='" . $color . "' style='padding-top:0px'><b id='subject$y' style='font-size:7pt;font-weight:bold;padding-left:40px'>$titleEng</b><br>\n";
	                                print "<div class='untranslated'>$messageEng</div>\n";
	                                print "<input type='text' name='translation' id='message$y' value=\"$translateThis\" class='$donethis' onkeyup=\"updateTranslation( this , '" . $_GET[ 'lang' ] . "' );\">";
	                                print "<div class='lastupdate'>$update</div>";
	                                print "</td>\n";
	                                print "<td class='" . $color . "' style='padding-top:0px' id='updating$y'></td>";
	                            print "</tr>\n";	                
	                            
	                        }
	                    
	                    } 
	                    else
	                    {
	                        print "<tr><td colspan='3'>Error selecting language</td></tr>\n";
	                    }
	                    
	                    
	                ?>
	                
	                <tr><td colspan='3'></td></tr>	    
                </table>
                <br>
                <br>	    

            <?php

                }
                else if( isset( $_GET[ 'lang' ] ) && $loggedin == "0" )
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
                else
                {

            ?>
	            <table width='980'>	   
	                <tr><td colspan='10' style='padding-left:20px;font-size:12pt;border-bottom: 1px dashed #cccccc'><span id='editLang'></span><br><br></td></tr>
	                <tr><td colspan='10'><br></td></tr>
	                <tr><td><br></td><td><br></td><td style='width:20px;text-align:center'><img src='images/user.png'></td><td><br></td><td><br></td><td><br></td><td><br></td><td style='width:20px;text-align:center'><img src='images/user.png'></td><td><br></td><td><br></td></tr>
	                <?php
	
	                $languages = array();
	
	                $langs = $stream->do_query( "select lang from languages order by lang ASC" , "array" );
	                
	                $numtranslations = $stream->do_query( "select count(*) from translations where lang='en_US'" , "one" );
	
	                $r = 1;
	
	                for( $p = 0; $p < count( $langs ); $p = $p + 2 )
	                {
	                    $lang1 = $langs[ $p ];
	                    $lang2 = $langs[ $p + 1 ];
	                    
	                    $lang1num = $stream->do_query( "select count(*) from translations where lang='" . $lang1[0] . "'" , "one" );
	                    $lang1users = $stream->do_query( "select count(*) from knownusers where locale='" . $lang1[0] . "'" , "one" );
	                    $lang2num = $stream->do_query( "select count(*) from translations where lang='" . $lang2[0] . "'" , "one" );
	                    $lang2users = $stream->do_query( "select count(*) from knownusers where locale='" . $lang2[0] . "'" , "one" );
	                    
	                    $lang1Complete = ( $lang1num > 0 ) ? round( ( $lang1num / $numtranslations ) * 100 ) . "%" : 0 . "%" ;
	                    $lang2Complete = ( $lang2num > 0 ) ? round( ( $lang2num / $numtranslations ) * 100 ) . "%" : 0 . "%" ;
	                    $lang1per = ( $lang1num > 0 ) ? round( ( $lang1num / $numtranslations ) * 100 ) : 0;
	                    $lang2per = ( $lang2num > 0 ) ? round( ( $lang2num / $numtranslations ) * 100 ) : 0;
	                    
	                    if( $lang1per >= 0  && $lang1per < 25  )
	                        $progress1 = "blockProgression1";
	                    else if( $lang1per >= 25  && $lang1per < 50  )
	                        $progress1 = "blockProgression2";
	                    else if( $lang1per >= 50  && $lang1per < 75  )
	                        $progress1 = "blockProgression3";
	                    else 
	                        $progress1 = "blockProgression4";

	                        
	                        
	                    if( $lang2per >= 0  && $lang2per < 25  )
	                        $progress2 = "blockProgression1";
	                    else if( $lang2per >= 25  && $lang2per < 50  )
	                        $progress2 = "blockProgression2";
	                    else if( $lang2per >= 50  && $lang2per < 75  )
	                        $progress2 = "blockProgression3";
	                    else 
	                        $progress2 = "blockProgression4";

	                        
	                        
	                        
	                        
	                    
	                    if( $r % 2 == 0 )
	                    {
	                        print "<tr>\n";		            
	                        $color = "tdColor1";	        
	                    }	 
	                    else
	                    {
	                        $color = "tdColor2";
	                    }   
	                    
	                    if( $lang1 == "" )
	                    {
	                        $lang1Complete = "";	
	                        $lang1bar = "";
	                        $lang1edit = "";
	                        $lang1users = "";
	                    }    
	                    else
	                    {
	                        $lang1bar = "<div class='$progress1' style='width:" . $lang1Complete . ";'></div>\n"; 
	                        $lang1edit = "<a href='community.php?action=translate&lang=" . $lang1[0] ."'><img src='http://www.synologydownloadassistant.com/themes/default/icons/note_edit.png' width='16'></a> \n";
	                        $languages[] = $lang1[0];  	        
	                    } 
	                    
	                    
	                    if( $lang2 == "" )
	                    {
	                        $lang2Complete = "";
	                        $lang2bar = ""; 
	                        $lang2edit = "";
	                        $lang2users = "";
	                    } 
	                    else
	                    {
	                        $lang2bar = "<div class='$progress2' style='width:" . $lang2Complete . ";'></div>";  	  
	                        $lang2edit = "<a href='community.php?action=translate&lang=" . $lang2[0] ."'><img src='http://www.synologydownloadassistant.com/themes/default/icons/note_edit.png' width='16'></a> \n";  
	                        $languages[] = $lang2[0];      
	                    }         
	                       
	                    
	                    print "<td width='25' class='" . $color . "'> $lang1edit </td>";
	                    print "<td width='55' class='" . $color . "'><a style='text-decoration:none' href='community.php?action=translate&lang=" . $lang1[0] ."'>" . $lang1[0] . "</a></td>";
	                    print "<td style='width:20px;text-align:center' class='" . $color . "'>$lang1users</td>";
	                    print "<td width='50' class='" . $color . "'>&nbsp;$lang1Complete </td>";
	                    print "<td width='250' class='" . $color . "Bar'> $lang1bar </td>";
	                    
	                    print "<td width='25' class='" . $color . "'> $lang2edit </td>";
	                    print "<td width='55' class='" . $color . "'><a style='text-decoration:none' href='community.php?action=translate&lang=" . $lang2[0] ."'>" . $lang2[0] . "</a></td>";
	                    print "<td style='width:20px;text-align:center' class='" . $color . "'>$lang2users</td>";
	                    print "<td width='50' class='" . $color . "'>&nbsp;$lang2Complete </td>";
	                    print "<td width='250' class='" . $color . "Bar'> $lang2bar </td>\n";	
	                    
	                    if( $r % 2 == 0 )
	                    {
	                        print "</tr>\n";	        
	                    }
	                    
	                    $r++;
	                }
	
	                ?>
	                
	                <tr><td colspan='8'></td></tr>	    
                </table>
	
            <?php
                
                    print "<script type=\"text/javascript\">\n";
                    print "var langs = [ \n";
                    
                    $f = 0;
                    
                    while( $f < count( $languages ) - 1 )
                    {
                        print "'" . $languages[ $f ] . "' , \n";
                        $f++;
                    }
                    
                    print "'" . $languages[ $f ] . "'\n";
                    
                    print " ];\n";
            ?>
					var nav = navigator.language ? navigator.language : navigator.userLanguage;

                    var nav = nav.replace( "-" , "_" );
                    if( $.inArray( nav , langs ) > -1 )
                    {
                        $( "#editLang" ).html( "<a style='text-decoration:none' href='community.php?action=translate&lang=" + nav + "'><img src='http://www.synologydownloadassistant.com/themes/default/icons/note_edit.png' width='20'> Edit " + nav.toUpperCase() + "</a>" );
                    }

            <?php
                    
                    print "</script>\n";

                }
                
                print "</div>";
	    
	        }
	        else if( $_GET[ 'action' ] == "theme" )
	        {
	        
	        }
	        else if( $_GET[ 'action' ] == "code" )
	        {
	        
	            
	            print "<div style='padding-left:20px;width:100%'>";
                
                include( "svn.php" );
                
                print "</div>";
                
	            
	        }
	        else if( $_GET[ 'action' ] == "bug" )
	        {
	        
	        }
	        else if( $_GET[ 'action' ] == "feature" )
	        {
	        
	        }
	    ?>
	
            
            <img src='images/loading.gif' style='display:none'>
			
	    </div>	
    </div>    
    <!-- End Main -->   
    
    
<?php

include( "footer.php" );

?>
