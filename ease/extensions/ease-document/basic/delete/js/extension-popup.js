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
	else		$('.MessageDialog .DialogFooter').html( '<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseDeleteTxT['Ok'] +'</a></div><div class="ButtonRight"></div></div></div>' );
	
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

function fctDeleteDoc()
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'delete',
	    type: 'delete'
	}),
	success: function( data ){
	    if( data['error'] )
		alert( "Error:"+ data['error'] );
	    else
	    {
                $('.Footer .ButtonBlack:eq(0)').css('display','none');
                if( data['repsone'] == "delete" )
                {
                    fctOpenMessage( 
                        '<h1>'+ EaseDeleteTxT['Deleted'] +'</h1><div class="Pad"><p>'+ EaseDeleteTxT['Deleted2'] +'</p></div>',
                        '<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="window.parent.fctEaseCloseExtensionPopup()">'+ EaseDeleteTxT['Ok'] +'</a></div><div class="ButtonRight"></div></div></div>'
                    );
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
});

window.onresize = function() {
   fctSetHeight();
}