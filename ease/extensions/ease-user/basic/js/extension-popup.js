function fctSetHeight()
{
    $('.Center').css("height",($(window).height()-110)+"px");
    $('.Left').css("height",($(window).height()-110)+"px");
}

function fctOpenMessage( Message,Buttons )
{
    if( $('.MessageDialog').css("display") == "none" )
    {
	$('.MessageDialog .DialogTop').html( Message );
	if( Buttons )	$('.MessageDialog .DialogFooter .Pad').html( Buttons );
	else		$('.MessageDialog .DialogFooter .Pad').html( '<div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseUserTxT['Ok'] +'</a></div><div class="ButtonRight"></div></div>' );
	
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

function fctGetUserList( UID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'ajax-userlist',
	    uid: UID
	}),
	success: function( data ){
	    $('.Left .Pad').html( data.left );
	    fctSetMenuLinks();
	},
	error: function(){
	    fctOpenMessage( '<h1>'+ EaseUserTxT['Error'] +'</h1><div class="Pad"><p style="color:#f00;">'+ EaseUserTxT['Error2'] +'</p></div>' );
	}
    });
}

function fctSetMenuLinks()
{
    $('.Left ul a').bind("click",function(){
	$('.Left ul a').removeClass( "Active" );
	$(this).addClass( "Active" );
    });
}

function fctShowUser( UID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'ajax-edit-user',
	    uid: UID
	}),
	success: function( data ){
	    $('.Right .Pad').html( data.right );
	    $('.Footer .Pad').html( data.footer );
	},
	error: function(){
	    fctOpenMessage( '<h1>'+ EaseUserTxT['Error'] +'</h1><div class="Pad"><p style="color:#f00;">'+ EaseUserTxT['Error2'] +'</p></div>' );
	}
    });
}

function fctSaveUser( UID )
{
    if( document.forms['EditForm'].password1.value != document.forms['EditForm'].password2.value )
    {
	fctOpenMessage( '<h1>'+ EaseUserTxT['Password1'] +'</h1><div class="Pad"><p>'+ EaseUserTxT['Password3'] +'</p></div>' );
    }
    else if( !document.forms['EditForm'].login.value )
    {
	fctOpenMessage( '<h1>'+ EaseUserTxT['Password1'] +'</h1><div class="Pad"><p>'+ EaseUserTxT['Login2'] +'</p></div>' );
    }
    else if( UID == false && !document.forms['EditForm'].password1.value )
    {
	fctOpenMessage( '<h1>'+ EaseUserTxT['Password1'] +'</h1><div class="Pad"><p>'+ EaseUserTxT['Password4'] +'</p></div>' );
    }
    else 
    {
	if( document.forms['EditForm'].admin.checked == true )
	    intAdmin = 1;
	else
	    intAdmin = 0;	    
	
	// Save
	$.ajax({
	    url: '../extension-popup.php?action=ajax-save-user',
	    type: "POST",
	    dataType: 'json',
	    data: ({ 
		uid: UID,
		login: document.forms['EditForm'].login.value,
		password: document.forms['EditForm'].password1.value,
		admin: intAdmin,
		language: document.forms['EditForm'].language[document.forms['EditForm'].language.selectedIndex].value,
		theme: document.forms['EditForm'].theme[document.forms['EditForm'].theme.selectedIndex].value,
		firstname: document.forms['EditForm'].firstname.value,
		lastname: document.forms['EditForm'].lastname.value,
		email: document.forms['EditForm'].email.value
	    }),
	    success: function( data ) {
		fctOpenMessage( data.message );
		if( data.error != 1 )
		{
		    fctGetUserList( data.uid );
		    fctShowUser( data.uid );
		}
	    },
	    error:function(){
		fctOpenMessage( '<h1>'+ EaseUserTxT['Error'] +'</h1><div class="Pad"><p style="color:#f00;">'+ EaseUserTxT['Error2'] +'</p></div>' );
	    }
	});
    }
}

function fctDeleteUser( UID )
{
    fctOpenMessage( '<h1>'+ EaseUserTxT['DeleteUser'] +'</h1><div class="Pad"><p>'+ EaseUserTxT['DeleteUser2'] +'</p></div>','<div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctDeleteUser2(\''+ UID +'\')">'+ EaseUserTxT['Yes'] +'</a></div><div class="ButtonRight"></div></div><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseUserTxT['No'] +'</a></div><div class="ButtonRight"></div></div>' );
}

function fctDeleteUser2( UID )
{
    fctCloseMessage();
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'ajax-delete-user',
	    uid: UID
	}),
	success: function( data ){
	    $('.Right .Pad').html( '' );
	    $('.Footer .Pad').html( '' );
	    fctGetUserList('');
	    fctOpenMessage( data.message );
	},
	error: function(){
	    fctOpenMessage( '<h1>'+ EaseUserTxT['Error'] +'</h1><div class="Pad"><p style="color:#f00;">'+ EaseUserTxT['Error2'] +'</p></div>' );
	}
    });
}

$(document).ready(function() {
    fctSetHeight();
    fctGetUserList();
});

window.onresize = function() {
   fctSetHeight();
}