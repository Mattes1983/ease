function fctEASEFormEditStart( id )
{
    $('#EASEFormEdit-'+id).css("display","block");
    $('#EASEFormNotEdit-'+id).css("display","none");
}

function fctEASEFormEditEnd( id )
{
    $.ajax({
	url: '../action.php',
	type: "POST",
	data: ({ 
	    action: 'save_content_request',
	    lin_id: id,
	    'name[]': ['formname','emailrecipient','emailfrom','messagesuccess','messageerror','messageerrorrequired','messageerrorcaptcha'],
	    'value[]': [
		document.forms['EASEFormEditForm-'+id].formname.value,
		document.forms['EASEFormEditForm-'+id].emailrecipient.value,
		document.forms['EASEFormEditForm-'+id].emailfrom.value,
		document.forms['EASEFormEditForm-'+id].messagesuccess.value,
		document.forms['EASEFormEditForm-'+id].messageerror.value,
		document.forms['EASEFormEditForm-'+id].messageerrorrequired.value,
		document.forms['EASEFormEditForm-'+id].messageerrorcaptcha.value
	    ]
	}),
	success: function() {
	    top.fctEaseReload();
	}
    });
}