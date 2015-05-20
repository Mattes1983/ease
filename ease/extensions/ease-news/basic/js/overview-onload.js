function fctEASENewsOverviewEditStart( id )
{
    $('#EASENewsOverviewEdit-'+id).css("display","block");
    $('#EASENewsOverviewEdit2-'+id).css("display","block");
}

function fctEASENewsOverviewEditEnd( id )
{
    $.ajax({
	url: '../action.php',
	type: "POST",
	data: ({ 
	    action: 'save_content_request',
	    lin_id: id,
	    'name[]': ['headlinelength','textlength','detaillinktext'],
	    'value[]': [
		document.forms['EASENewsOverviewEditForm-'+id].headlinelength.value,
		document.forms['EASENewsOverviewEditForm-'+id].textlength.value,
		document.forms['EASENewsOverviewEditForm-'+id].detaillinktext.value
	    ]
	}),
	success: function() {
	    top.fctEaseReload();
	}
    });
}