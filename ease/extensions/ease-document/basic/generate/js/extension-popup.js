function fctSetHeight()
{
    $('.Center').css("height",($(window).height()-50)+"px");
}

function fctOpenMessage( Message,Buttons )
{
    if( $('.MessageDialog').css("display") == "none" )
    {
	$('.MessageDialog .DialogTop .Pad').html( Message );
	if( Buttons )	$('.MessageDialog .DialogFooter').html( Buttons );
	else		$('.MessageDialog .DialogFooter').html( '<a href="javascript:;" onclick="fctCloseMessage()" class="Button"><span class="ButtonText">Ok</span></a>' );
	
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
    $('.BlackBackground').bind("click");
    $('.MessageDialog').css("display","none");
    $('.MessageDialog .DialogTop .Pad').html('');
}

function fctGenerate()
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'generate',
	    type: 'generate'
	}),
	success: function( data ){
	    if( data['error'] )
		alert( "Error:"+ data['error'] );
	    else
	    {
	        $('.Loading').animate({
		    width: data['percent']+"%"
		},100,function(){
		    if( data['percent'] == 100 )
		    {
			$('h2').html( EaseGenerateTxT['Documentpublished'] );
			$('.Loading').addClass('LoadingDone');
		    }
		    $('.Percent').html( data['percent']+"%" );
		});

		if( data['percent'] < 100 )
		{
		    fctGenerate();
		}
	    }
	},
	error: function( data ){
	    if( data['error'] )
		alert( "Error:"+ data['error'] );
	    else if( data )
		alert( "Error:"+ data );
	}
    });
}

$(document).ready(function() {
    fctSetHeight();
    fctGenerate();
});

window.onresize = function() {
   fctSetHeight();
}