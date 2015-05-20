
function fctEaseLinkEditStart( id )
{
    $('#EASELinkEdit-'+id).css("display","block");
    $('#EASELinkNotEdit-'+id).css("display","none").html('');
}

function fctEaseLinkEditEnd( id )
{  
    
    $.ajax({
	url: '../action.php',
	type: "GET",
	data: ({ 
	    action: 'get_content_request',
	    lin_id: id
	}),
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	success: function( data ){
	    switch( data.type )
	    {
		case '1':
		    fctEaseLinkGetDocURL( id,data.doc );
		    break;
		case '2': 
		    fctEaseLinkGetFileURL( id,data.file );
		    break;
	    }
	    
	}
    });

}

function fctEaseLinkGetFileURL( id,intFilID )
{
    $.ajax({
	url: '../action.php',
	type: "GET",
	data: ({ 
	    action: 'ease_function',
	    ease_class: '_Files',
	    ease_function: 'fctGetFileURL',
	    ease_param_1: intFilID
	}),
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	success: function( data ){
	    fctEaseLinkSetView( id,data.url,false );
	}
    });

}

function fctEaseLinkGetDocURL( id,intDocID )
{
    $.ajax({
	url: '../action.php',
	type: "GET",
	data: ({ 
	    action: 'ease_function',
	    ease_class: '_Documents',
	    ease_function: 'fctGetDocURL',
	    ease_param_1: intDocID
	}),
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	success: function( data ){
	    fctEaseLinkSetView( id,data.url,data.js );
	}
    });
}

function fctEaseLinkSetView( id,strURL,strJSURL,strTarget )
{
    $.ajax({
	url: '../action.php',
	type: "GET",
	data: ({ 
	    action: 'get_link_request',
	    lin_id: id
	}),
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	success: function( data ){

	    if( strJSURL )
		$('#EASELinkNotEdit-'+id).html( '<a href="javascript:;" onclick="'+ strJSURL +'">'+ data.imageedit[0] +'</a>' );
	    else
		$('#EASELinkNotEdit-'+id).html( '<a href="'+ strURL +'">'+ data.imageedit[0] +'</a>' );

	    $('#EASELinkEdit-'+id).css("display","none");
	    $('#EASELinkNotEdit-'+id).css("display","block");
	    window.top.fctGlobalLoadingEnd();
	},
	error: function(){
	    window.top.fctGlobalLoadingEnd();
	}
    });
}