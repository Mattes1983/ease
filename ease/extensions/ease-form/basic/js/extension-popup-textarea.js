function fctSetHeight()
{
    $('.Center').css("height",($(window).height()-50)+"px");
}

function fctOpenMessage( Message,Buttons )
{
    if( $('.MessageDialog').css("display") == "none" )
    {
	$('.MessageDialog .DialogTop').html( Message );
	if( Buttons )	$('.MessageDialog .DialogFooter').html( Buttons );
	else		$('.MessageDialog .DialogFooter').html( '<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseFormTxT['Ok'] +'</a></div><div class="ButtonRight"></div></div></div>' );
	
	$('.BlackBackground').css("opacity","0");
	$('.BlackBackground').css("display","block");
	$('.BlackBackground').animate({
	    opacity: .8
	},200,'linear',function(){
	    $('.MessageDialog').css("display","block");
	    $('.BlackBackground').bind("click",function(){
		fctCloseMessage();
	    });
	})
	
    }
}

function fctCloseMessage()
{
    $('.BlackBackground').css("display","none");
    $('.BlackBackground').unbind("click");
    $('.MessageDialog').css("display","none");
    $('.MessageDialog .DialogTop .Pad').html('');
}

function fctSave(id)
{   
    if( document.forms['EditForm'].required.checked ) 
	intRequired = "1";
    else
	intRequired = "0";
    
    $.ajax({
	url: '../action.php',
	type: "POST",
	data: ({ 
	    action: 'save_content_request',
	    lin_id: id,
	    'name[]': ['fieldname','required','fieldvalue'],
	    'value[]': [
		document.forms['EditForm'].fieldname.value,
		intRequired,
		document.forms['EditForm'].fieldvalue.value
	    ]
	}),
	success: function() {
	    fctOpenMessage( "<h1>"+EaseFormTxT['Saved1']+"</h1><div class='Pad'><p>"+EaseFormTxT['Saved2']+"</p></div>" );
	}
    });
}

$(document).ready(function() {
    fctSetHeight();
});

window.onresize = function() {
   fctSetHeight();
}