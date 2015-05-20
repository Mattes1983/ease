function fctEASESearchEditStart( id )
{
    $('#EASESearchEdit-'+id).css("display","block");
}

function fctEASESearchEditEnd( id )
{
    // Slider-Width
    $.ajax({
	url: '../action.php',
	type: "POST",
	data: ({ 
	    action: 'save_content_request',
	    lin_id: id,
	    'name[]': ['maxperpage','headlinelength','textlength','pagingspace'],
	    'value[]': [
		document.forms['EASESearchEditForm-'+id].maxperpage.value,
		document.forms['EASESearchEditForm-'+id].headlinelength.value,
		document.forms['EASESearchEditForm-'+id].textlength.value,
		document.forms['EASESearchEditForm-'+id].pagingspace.value
	    ]
	}),
	success: function() {
	    $('#EASESearchEdit-'+id).css("display","none");
	    top.fctEaseReload();
	}
    });
}