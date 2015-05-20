function fctEASENewsEditStart( id )
{
    $('#EASENewsDetailEdit-'+id).css("display","block");
    $('#EASENewsDetailNotEdit-'+id).css("display","none");
}

function fctEASENewsEditEnd( id )
{
    $.ajax({
	url: '../action.php',
	type: "POST",
	data: ({ 
	    action: 'extension_function',
	    extension_class: 'EASENews',
	    extension_function: 'fctSaveNews',
	    id: id,
	    startdate: document.forms['EASENewsDetailEditForm-'+id].startdate.value,
	    enddate: document.forms['EASENewsDetailEditForm-'+id].enddate.value
	}),
	success: function( data ) {
	    top.fctEaseReload();
	},
	error: function() {
	    top.fctEaseReload();
	}
    });
}

function fctShowCal( id,strName,intTimeStamp )
{
    
    if( strName == "StartDate" )
    {
	$('.CalendarStartDate').css('display','block');
	$('.CalendarButtonStartDate').css('display','none');
	if( intTimeStamp ) document.forms['EASENewsDetailEditForm-'+id].startdate.value = intTimeStamp;
	else intTimeStamp = document.forms['EASENewsDetailEditForm-'+id].startdate.value;
    }
    else if( strName == "EndDate" )
    {
	$('.CalendarEndDate').css('display','block');
	$('.CalendarButtonEndDate').css('display','none');
	if( intTimeStamp ) document.forms['EASENewsDetailEditForm-'+id].enddate.value = intTimeStamp;
	else intTimeStamp = document.forms['EASENewsDetailEditForm-'+id].enddate.value;
    }

    if( intTimeStamp )
	data = {id: id, timestamp: intTimeStamp, strName: strName};
    else
	data = {id: id, strName: strName};
    $.ajax({
	url: '../extensions/ease-news/basic/php/calendar.inc.php',
	type: "POST",
	data: data,
	success: function( data ) {
	    $('.Calendar'+strName).html( data );
	}
    });
}