var GoSearch;
var boolStartDrag = false;
var countUploadFile = 1;
var maxUploads = 30;

function fctSetHeight()
{
    $('.Center').css("height",($(window).height()-114)+"px");
}

function fctOpenMessage( Message,Buttons )
{
    if( $('.MessageDialog').css("display") == "none" )
    {
	$('.MessageDialog .DialogTop').html( Message );
	if( Buttons )	$('.MessageDialog .DialogFooter').html( Buttons );
	else		$('.MessageDialog .DialogFooter').html( '<a href="javascript:;" onclick="fctCloseMessage()" class="Button"><span class="ButtonText">'+ EaseCKEditorTxT['Ok'] +'</span></a>' );
	
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

function fctAddFileUpload()
{
    $('.UploadDialog .File:last-child').bind('change',function(){
        $(this).unbind('change');
        $('.UploadDialog .File:first')
            .clone()
            .appendTo('.UploadDialog form');
        $('.UploadDialog .File:last-child').addClass('FileCopy')
        countUploadFile++;
        $('.UploadDialog .File:last-child input').replaceWith('<input type="file" name="file'+ countUploadFile +'" />');
        if( countUploadFile < maxUploads )
            fctAddFileUpload();
    });
    
}

function fctOpenUpload()
{
    $('.UploadDialog p.FileCopy').remove();
    fctAddFileUpload();
    
    if( $('.UploadDialog').css("display") == "none" )
    {
	$('.BlackBackground').css("opacity","0");
	$('.BlackBackground').css("display","block");
	$('.BlackBackground').animate({
	    opacity: .8
	},500,'linear',function(){
	    $('.UploadDialog').css("display","block").css("opacity","1");
	    $('.BlackBackground').bind("click",function(){
		fctCloseUpload();
	    });
	})
	
    }
}

function fctCloseUpload()
{
    $('.BlackBackground').css("display","none");
    $('.BlackBackground').unbind("click");
    $('.UploadDialog').css("display","none");
    $('.UploadDialog2').css("display","none");
}

function fctDoUpload()
{
    $('.UploadDialog').css("display","none");
    $('.UploadDialog2').css("display","block");
    document.upload.submit();
}

function fctPreSearch()
{
    if( GoSearch ) window.clearTimeout( GoSearch );
    GoSearch = window.setTimeout( "fctSearch(true);", 1000 );
}

function fctSearch( boolReset )
{
    var strAction;

    switch( intType )
    {
	case '1': strAction = 'documents'; break;
	case '2': strAction = 'files'; break;
    }

    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: strAction,
	    search: document.formSearch.search.value
	}),
	success: function( data ){
	    $('.Center .Pad').html(data);
	}
    });
}

function fctGetLinkURL()
{
    return top.easeMain.CKEDITOR.dialog.getCurrent().getValueOf('info', 'url');
}

function fctSetLinkURL( strValue )
{
    top.easeMain.CKEDITOR.dialog.getCurrent().getContentElement( 'info', 'url' ).setValue( strValue );
    top.fctEaseCloseExtensionPopup();
}

function fctDeleteLink( intID )
{
    fctOpenMessage( "<h1>"+ EaseCKEditorTxT['FileDelete1'] +"</h1><div class='Pad'><p>"+EaseCKEditorTxT['FileDelete2']+"</p></div>" ,'<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage();fctDeleteLink2('+ intID +');">'+ EaseCKEditorTxT['Yes'] +'</a></div><div class="ButtonRight"></div></div><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseCKEditorTxT['No'] +'</a></div><div class="ButtonRight"></div></div></div>');
}

function fctDeleteLink2( intID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'delete_file',
	    id: intID
	}),
	success: function(){
	    $('#File-'+intID).remove();
	},
	error: function(){
	    $('.MessageDialog .DialogTop').html( '<h1>'+ EaseCKEditorTxT['Error'] +'</h1><div class="Pad"><p style="color:#f00;">'+ EaseCKEditorTxT['Error2'] +'</p></div>' );
	}
    });
}

function fctShowType( objFieldType )
{
    switch( objFieldType.options[objFieldType.options.selectedIndex].value )
    {
	case '1': 
	    $('.TypeFiles').css('display','none');
	    intType = "1";
	    break;
	case '2': 
	    $('.TypeFiles').css('display','block');
	    intType = "2";
	    break;
    }

    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'type_change',
	    type: intType
	}),
	success: function(){
	    fctPreSearch();
	},
	error: function(){
	    fctPreSearch();
	}
    });
}

function fctOpenPreview( strURL )
{
    if( $('.PreviewDialog').css("display") == "none" )
    {
	$('.PreviewDialog .DialogTop .Preview').html( '<iframe name="Preview" src="'+ strURL +'" scrolling="no"></iframe>' );
	
	$('.BlackBackground').css("opacity","0");
	$('.BlackBackground').css("display","block");
	$('.BlackBackground').animate({
	    opacity: .8
	},200,'linear',function(){
	    $('.PreviewDialog').css("display","block");
	    $('.BlackBackground').bind("click",function(){
		fctClosePreview();
	    }); 
	})
    }
}

function fctClosePreview()
{
    $('.BlackBackground').css("display","none");
    $('.BlackBackground').unbind("click");
    $('.PreviewDialog').css("display","none");
}

$(document).ready(function() {  
    fctSetHeight();
    fctSearch(false);
});

window.onresize = function() {
   fctSetHeight();
}