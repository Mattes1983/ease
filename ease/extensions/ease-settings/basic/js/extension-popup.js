function fctSetHeight()
{
    $('.Center').css("height",($(window).height()-64)+"px");
}

function fctOpenMessage( Message,Buttons )
{
    if( $('.MessageDialog').css("display") == "none" )
    {
	$('.MessageDialog .DialogTop .Pad').html( Message );
	if( Buttons )	$('.MessageDialog .DialogFooter').html( Buttons );
	else		$('.MessageDialog .DialogFooter').html( '<a href="javascript:;" onclick="fctCloseMessage()" class="Button"><span class="ButtonText">'+ EaseEaseSettingsTxT['Ok'] +'</span></a>' );
	
	$('.BlackBackground').css("opacity","0");
	$('.BlackBackground').css("display","block");
	$('.BlackBackground').animate({
	    opacity: .8
	},200,'linear',function(){
	    $('.MessageDialog').css("display","block");
	})
    }
}

function fctCloseMessage()
{
    $('.BlackBackground').css("display","none");
    $('.MessageDialog').css("display","none");
    $('.MessageDialog .DialogTop .Pad').html('');
}

$(document).ready(function() {
    $('.BlackBackground').bind("click",function(){
	fctCloseMessage();
    });   
    fctSetHeight();
});

window.onresize = function() {
   fctSetHeight();
}