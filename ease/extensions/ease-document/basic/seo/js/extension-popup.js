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

$(document).ready(function() {
    fctSetHeight();
    
    // Title
    $('input[name="title"]').bind("keyup",function(){
	if( $(this).val().length > 70  ) $(this).val( $(this).val().substring(0,70) );
	$('span.TitleChars').text($(this).val().length+"/70");
    })
    $('span.TitleChars').text($('input[name="title"]').val().length+"/70");
    
    // Description
    $('textarea[name="description"]').bind("keyup",function(){
	if( $(this).val().length > 156  ) $(this).val( $(this).val().substring(0,156) );
	$('span.DescriptionChars').text($(this).val().length+"/156");
    })
    $('span.DescriptionChars').text($('textarea[name="description"]').val().length+"/156");
});

window.onresize = function() {
   fctSetHeight();
}