<?php

    if( isset( $_GET[ 'source' ] ) && isset( $_GET[ 'brush' ] ) )
    {
    
        print "<html><head>\n";
        print "<script src=\"js/jquery-1.4.1.min.js\" type=\"text/javascript\"></script>\n";
        print "<script type=\"text/javascript\" src=\"syntaxhighlighter/scripts/shCore.js\"></script>\n";
	    print "<script type=\"text/javascript\" src=\"syntaxhighlighter/scripts/shBrushXml.js\"></script>\n";
	    print "<script type=\"text/javascript\" src=\"syntaxhighlighter/scripts/shBrushPlain.js\"></script>\n";
	    print "<script type=\"text/javascript\" src=\"syntaxhighlighter/scripts/shBrushJScript.js\"></script>\n";
	    print "<script type=\"text/javascript\" src=\"syntaxhighlighter/scripts/shBrushCss.js\"></script>\n";
	    print "<script type=\"text/javascript\" src=\"syntaxhighlighter/scripts/shBrushBash.js\"></script>\n";
	    print "<link type=\"text/css\" rel=\"stylesheet\" href=\"syntaxhighlighter/styles/shCoreDefault.css\"/>\n";
	    print "<link rel=\"stylesheet\" href=\"css/style.css\" type=\"text/css\" media=\"all\" />";
	    print "</head><body>\n";
        print "<script type=\"syntaxhighlighter\" class=\"brush: " . $_GET[ 'brush' ] . "\">\n<![CDATA[\n";
        
        if( stristr( $_GET[ 'source' ] , '..' ) )
        {
            exit;
        }
        
        $file = file_get_contents( $_GET[ 'source' ] );
        $file = ereg_replace( "</script>" , "&lt;/script&gt;" , $file );
        
        print "$file\n]]>\n</script>\n";
        print "<script type=\"text/javascript\">\n";
        
        ?>
            $( document ).ready( function()
            {
                
                SyntaxHighlighter.defaults[ 'gutter' ] = true;
                SyntaxHighlighter.defaults[ 'smart-tabs' ] = false;
                SyntaxHighlighter.defaults[ 'tab-size' ] = 2;
                SyntaxHighlighter.defaults[ 'toolbar' ] = false;
                SyntaxHighlighter.all();  
            
            });
            
        <?php
        
        print "\n</script>\n";
        print "</body></html>";
    
        exit;
    }
    else
    {
    
        if( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == "subversion" )
        {
            ?>                
                
                <br>	        
                <img src='images/svn.png' alt='subversion'> 
                <br><br> 

                <h3>Standard Users</h3><br> 

                <h4>Check out the current version</h4>
                <script type="syntaxhighlighter" class="brush: js"><![CDATA[
                svn co http://www.synologydownloadassistant.com/subversion/  
                ]]></script>
                <br> <br> 

                <h3>Developers</h3><br> 

                <h4>Request update</h4>
                <script type="syntaxhighlighter" class="brush:bash"><![CDATA[
                svn update
                ]]></script> <br> 

                <h4>Add Dir ( recursive - client side )</h4>
                <script type="syntaxhighlighter" class="brush:bash"><![CDATA[
                svn add ./dir  
                ]]></script> <br> 

                <h4>Add New File ( client side ) </h4>
                <script type="syntaxhighlighter" class="brush:bash"><![CDATA[
                svn add ./myfile.txt  
                ]]></script> <br> 

                <h4>See changes ( client side )</h4>
                <script type="syntaxhighlighter" class="brush:bash"><![CDATA[
                svn diff
                ]]></script> <br> 

                <h4>Get status </h4>
                <script type="syntaxhighlighter" class="brush:bash"><![CDATA[
                svn status -v
                ]]></script> <br> 

                <h4>Commit change to online repository </h4>
                <script type="syntaxhighlighter" class="brush:bash"><![CDATA[
                svn commit -m "message for update description"
                ]]></script> <br> 

                <h4>Delete from online repository </h4>
                <script type="syntaxhighlighter" class="brush:bash"><![CDATA[
                svn delete http://www.synologydownloadassistant.com/subversion/text.html -m "cleaning up. keep repository clean"
                ]]></script> <br> 

                <h4>See revision change log </h4>
                <script type="syntaxhighlighter" class="brush:bash"><![CDATA[
                svn log
                ]]></script> <br> 

                <h4>Import ( add and commit ) </h4>
                <script type="syntaxhighlighter" class="brush:bash"><![CDATA[
                svn import ./mydir http://www.synologydownloadassistant.com/subversion/ -m "importing some new files"
                ]]></script> <br> <br> 
                
                <script type="text/javascript">                
                
                    $( document ).ready( function()
                    {
                        
                        SyntaxHighlighter.defaults[ 'gutter' ] = true;
                        SyntaxHighlighter.defaults[ 'smart-tabs' ] = false;
                        SyntaxHighlighter.defaults[ 'tab-size' ] = 2;
                        SyntaxHighlighter.defaults[ 'toolbar' ] = false;
                        SyntaxHighlighter.all();  
                    
                    });
                
                </script>
                
            <?php            
        }
        else if( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == "code" )
        {  

?>
            
            <div style='margin-left:50px'>
            <ul id="browser" class="filetree treeview-famfamfam">
	            <li><h3> Synology Download Assistant</h3>
		            <ul>
		                <?php
		                    
		                    function getDirectory( $path = '.' )
		                    { 

                                $ignore = array( 'cgi-bin', '.', '..' , '.svn' ); 
                                $dh = @opendir( $path ); 
                                 
                                while( false !== ( $file = readdir( $dh ) ) )
                                { 
                                 
                                    if( !in_array( $file, $ignore ) )
                                    { 

                                        if( is_dir( "$path/$file" ) )
                                        { 
                                         
                                            echo "<li class=\"closed\"><span class=\"folder\"> &nbsp;&nbsp;$file</span><ul>"; 
                                            getDirectory( "$path/$file" ); 
                                            echo "</ul></li>";
                                         
                                        } 
                                        else 
                                        {       
                                            $path_parts = explode( "." , $file );
                                            $ext = $path_parts[ count( $path_parts ) -1 ];
                                            if( $ext == "json" ) $ext = "js";
                                            if( $ext == "CHANGELOG" ) $ext = "text";
                                            if( $ext == "png" )
                                            {
                                                echo "<li><span class='file'> $file</span></li>"; 
                                            }
                                            else
                                            {
                                                echo "<li><span class='file'><a style='text-decoration:none' href=\"javascript:previewCode( '$path/$file' , '" . $ext . "' );\"> &nbsp;&nbsp;$file</a></span></li>";                                             
                                            }                        
                                        } 
                                     
                                    } 
                                 
                                } 
                                 
                                closedir( $dh ); 
                                
                            } 
                            
                            getDirectory( './code' );
		                    
		                ?>
		            </ul>
	            </li>
            </ul>
            </div>
            <br><br>


            <div id='codeview'>

            </div>


            <script type="text/javascript">

                function previewCode( path , brush )
                {
                    var pagename = path.split( '/' );
                    var len = pagename.length -1;
                    var html = "<h3 style='margin-left:60px'>" + pagename[ len ] + "</h3>";
                    html += "<iframe id='sourceview' src='svn.php?source=" + path + "&brush=" + brush + "' style='width:100%;height:500px;border:0px' scrolling=\"no\"></iframe></center>";
                    $( "#codeview" ).html( html );
                    
                    $( "#sourceview" ).load(function () 
                    {            
                        var helpFrame = $( "#sourceview" );
                        var innerDoc = ( helpFrame.get(0).contentDocument ) ? helpFrame.get(0).contentDocument : helpFrame.get(0).contentWindow.document;
                        helpFrame.height( innerDoc.body.scrollHeight + 35 );
                    });
                 
                }                
               
            </script>

<?php
        }        
    }
    
?>
	            
