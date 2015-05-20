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
    $('.BlackBackground').unbind("click");
    $('.MessageDialog').css("display","none");
    $('.MessageDialog .DialogTop .Pad').html('');
}

function fctUpdate()
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'look_update'
	}),
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	success: function( data ){
	    alert("URL:"+data['url']);
	},
	error: function(){
	    alert("Error");
	}
    });
}

$(document).ready(function() {
    fctSetHeight();
});

window.onresize = function() {
   fctSetHeight();
}