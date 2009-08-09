


$( document ).ready( function()
{    

    $('.slides ul').jcarousel(
    {
        scroll: 1,
        wrap: 'both',
        initCallback: _init_carousel,
        buttonNextHTML: null,
        buttonPrevHTML: null
    });

    $( '.prev,.next' ).fadeTo( 'slow' , 0.4 );

    $( '.prev,.next' ).hover(
        function()
        {
            $( this ).fadeTo( 'slow' , 1.0 );
        },
        function()
        {
            $( this ).fadeTo( 'slow' , 0.4 );
        }
    );
    
    $( window ).bind( "beforeunload" , function()
    {
        $( ".slider-holder" ).fadeOut( 'fast' , function()
        {
            $( "#slider" ).slideUp( 'fast' );
        });    
    });  
    	
});

function _init_carousel( carousel ) 
{
	$( '.slider-nav .next' ).bind( 'click' , function() 
	{
		carousel.next();
		return false;
	});
	
	$( '.slider-nav .prev' ).bind( 'click' , function() 
	{
		carousel.prev();
		return false;
	});
};


