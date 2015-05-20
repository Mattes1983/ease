var intStartSearch = 0;
var GoSearch;
var GoSelect;
var W;
var H;
var maxW;
var maxH;
var imgW;
var imgH;
var intPadding = 50; // Padding
var EditURL;
var AjaxError = false;
var intImageDragID;

function fctSetImage( intLinID,ImageID )
{
    //document.formImages.image_id.value = ImageID;
    //fctOpenMessage( "<h1>"+EaseImageTxT['ImageSelect1']+"</h1><div class='Pad'><p>"+EaseImageTxT['ImageSelect2']+"</p></div>" );
}

function fctDeleteImage( ImageID )
{
    fctOpenMessage( "<h1>"+ EaseImageTxT['ImageDelete1'] +"</h1><div class='Pad'><p>"+EaseImageTxT['ImageDelete2']+"</p></div>" ,'<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage();fctDeleteImage2('+ ImageID +');">'+ EaseImageTxT['Yes'] +'</a></div><div class="ButtonRight"></div></div><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseImageTxT['No'] +'</a></div><div class="ButtonRight"></div></div></div>');
}

function fctDeleteImage2( ImageID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'delete',
	    id: ImageID
	}),
	success: function(){
	    $('#Image-'+ImageID).remove();
	},
	error: function(){
	    $('.MessageDialog .DialogTop').html( '<h1>'+ EaseImageTxT['Error'] +'</h1><div class="Pad"><p style="color:#f00;">'+ EaseImageTxT['Error2'] +'</p></div>' );
	}
    });
}

function fctOpenEdit( imgID,imgURL,iW,iH )
{
    EditURL = imgURL;
    imgW = iW;
    imgH = iH;
    
    document.FormEdit.eii_id.value = imgID;
    
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'getedit',
	    eii_id: imgID
	}),
	success: function( data ){
	    if( data )
	    {
		$('header').css('display','none');
		
		$('textarea[name=keywords]').val(data['eii_keywords']);
		$('input[name=filename]').val(data['eii_name']);
		
		$('.ImagePreview').fadeOut(200,function(){
		$('.TopButtons').css("display","none");
		$('.Top form').css("display","none");
		if( intActImgW && intActImgH )
		{
		    $('.Automatic').css("display","block");
		    $('.AutomaticTxt').css("display","inline");
		}
		$('.Footer:not(.FooterEdit)').css("display","none");
		$('.FooterEdit').css("display","block");
		$('div.Edit div.EditImage').html('<img src="'+ EditURL +'" width="'+ iW +'" height="'+ iH +'" />');
		$('div.Edit div.EditImage img').ready(function()
		{
		    $('div.Edit div.EditImage img').Jcrop({
			onSelect: fctUpdateCoords,
			onChange: function(e){
			    if( e.w == 0 && e.h == 0 )
			    {
				$('#x').val('');
				$('#y').val('');
				$('#w').val('');
				$('#h').val('');
			    }
			}
		    });
		});
		$('div.Edit').fadeIn(500);
	    });
	    }
	}
    });

}

function fctCloseEdit()
{
    $('header').css('display','block');
    $('div.Edit').fadeOut(200,function(){
	$('.ImagePreview').fadeIn(500);
	$('.Top .TopButtons').css("display","block");
	$('.Top form').css("display","block");
	$('.Footer:not(.FooterEdit)').css("display","block");
	if( intActImgW && intActImgH ) {
	    $('.Automatic').css("display","none");
	    $('.AutomaticTxt').css("display","none");
	}
	$('.FooterEdit').css("display","none");
    });
}

function fctUpdateCoords(c)
{
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
};

function fctSetAutomatic()
{
    if( intActImgW && intActImgH )
    {
	$('#x').val( 0 );
	$('#y').val( 0 );
	$('#w').val( intActImgW );
	$('#h').val( intActImgH );
	fctSetSelect();
    }
}

function fctPreSetSelect()
{
    if( GoSelect ) window.clearTimeout( GoSelect );
    GoSelect = window.setTimeout( "fctSetSelect(true);", 1000 );
}

function fctSetSelect()
{
    var x = $('#x').attr("value");
    var x2 = parseInt($('#x').attr("value"))+parseInt($('#w').attr("value"));
    var y = $('#y').attr("value");
    var y2 = parseInt($('#y').attr("value"))+parseInt($('#h').attr("value"));
    
    $('div.Edit div.EditImage').html('<img src="'+ EditURL +'" width="'+ imgW +'" height="'+ imgH +'" alt="" />');
    $('div.Edit div.EditImage img').Jcrop({
	setSelect: [ x,y,x2,y2 ],
	onSelect: fctUpdateCoords,
	onChange: function(e){
	    if( e.w == 0 && e.h == 0 )
	    {
		$('#x').val('');
		$('#y').val('');
		$('#w').val('');
		$('#h').val('');
	    }
	}
    });
}

function fctOpenBigger( imgID,imgURL,iW,iH )
{
    
    $('.BiggerDialog div.Bigger').html('<img src="'+ imgURL +'" class="Bigger" onclick="fctCloseBigger()" alt="" />');
    $('.BiggerDialog img.Bigger').ready(function()
    {
	
	imgW = iW;
	imgH = iH;
	
	fctSetBiggerSize(false);

	$('.BiggerDialog').css("display","none");
	$('.BlackBackground').css("opacity","0");
	$('.BlackBackground').css("display","block");
	$('.BlackBackground').animate({
	    opacity: .8
	},200,'linear',function(){
	    $('.BiggerDialog').css("display","block").css("width","1px").css("height","1px").css("marginTop","0").css("marginLeft","0");
	    $('.BiggerDialog').animate({
		width: W,
		height: H,
		marginLeft: -( W/2 ),
		marginTop: -( H/2 )
	    },500,function(){
		fctBiggerInfo( imgID );
		$('.BlackBackground').bind("click",function(){
		    fctCloseBigger();
		});
	    });
	})
    });
    
}

function fctSetBiggerSize( animate )
{
    W = $(window).width();
    H = $(window).height();
    
    W = W-(intPadding*2);
    H = H-(intPadding*2);

    maxW = W;
    maxH = H;

    if( imgW < W ) W = imgW;
    if( imgH < H ) H = imgH;

    if( imgW > imgH )
	imgRatio = imgW/imgH;
    else
	imgRatio = imgH/imgW;

    if( imgW > W || imgH > H )
    {
	if( imgW > imgH )
	{
	    H = Math.floor( W/imgRatio );
	    if( ( W/imgRatio ) > maxH )
	    {
		if( H > maxH )
		{
		    H = maxH;
		    W = Math.floor( maxH*imgRatio );
		}
	    }
	}
	else
	{
	    W = Math.floor( H/imgRatio );
	    if( ( H/imgRatio ) > maxW )
	    {
		if( W > maxW )
		{
		    W = maxW;
		    H = Math.floor( maxW*imgRatio );
		}
	    }
	}
	
	
	$('.BiggerDialog img.Bigger').attr("width",W );
	$('.BiggerDialog img.Bigger').attr("height",H );
    }
    else
    {
	$('.BiggerDialog img.Bigger').attr("width", imgW );
	$('.BiggerDialog img.Bigger').attr("height", imgH );
    }
    
    if( W < 300) W = 300;
    if( H < 300) H = 300;
    
    if( animate == true )
    {
	$('.BiggerDialog').css("width",W+"px").css("height",H+"px").css("marginLeft","-"+ ( W/2 )+"px" ).css("marginTop","-"+ ( H/2 )+"px" );
    }
}

function fctCloseBigger()
{
    $('.BlackBackground').css("display","none");
    $('.BlackBackground').unbind("click");
    $('.BiggerDialog').css("display","none");
    $('.BiggerDialog .DialogLeft .Pad').html( "<p>"+EaseImageTxT['Loading'] +"...</p>" );
}

function fctBiggerInfo( ImageID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'biggerinfo',
	    id: ImageID
	}),
	success: function( data ){
	    $('.BiggerDialog .DialogLeft .Pad').html( data );
	},
	error: function(){
	    $('.BiggerDialog .DialogLeft .Pad').html( EaseImageTxT['Error'] );
	}
    });
}

function fctEaseSave( intLinID,ImageID )
{   
    window.top.fctGlobalLoadingStart(function(){
	$.ajax({
	    url: '../action.php',
	    type: "POST",
	    data: ({ 
		action: 'save_content_request',
		lin_id: intLinID,
		'name[]': ['id'],
		'value[]': [ImageID]
	    }),
	    success: function(){
		top.fctEaseCloseReloadExtensionPopup();
	    }
	});
    });
}

function fctSetHeight()
{
    var CB = window.innerHeight || (window.document.documentElement.clientHeight || window.document.body.clientHeight);
    $('.ImagePreview').css("height",(CB-325)+"px");
    $('.Edit').css("height",(CB-50)+"px");
}

function fctOpenMessage( Message,Buttons )
{
    if( $('.MessageDialog').css("display") == "none" )
    {
	$('.MessageDialog .DialogTop').html( Message );
	if( Buttons )	$('.MessageDialog .DialogFooter').html( Buttons );
	else		$('.MessageDialog .DialogFooter').html( '<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseImageTxT['Ok'] +'</a></div><div class="ButtonRight"></div></div></div>' );
	
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

function fctOpenUpload()
{
    document.upload.reset();
    if( $('.UploadDialog').css("display") == "none" )
    {
	$('.BlackBackground').css("opacity","0");
	$('.BlackBackground').css("display","block");
	$('.BlackBackground').animate({
	    opacity: .8
	},500,'linear',function(){
	    $('form[name="upload"] input[name="group"]').val( intGroup );
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

function fctActiveImage()
{
   $('.Image .ButtonUse a').bind("click",function(){
       $('.Image').removeClass("ImageActive");
       $(this).parent().parent().parent().addClass("ImageActive");
   })
}

function fctPreSearch()
{
    if( GoSearch ) window.clearTimeout( GoSearch );
    GoSearch = window.setTimeout( "fctSearch(true);", 1000 );
}

function fctSearch( boolReset )
{
    if( boolReset ) $('.ImagePreview .Pad').html('<div class="AddImage" onclick="fctOpenUpload()"><div class="Table"><div class="TableCell">+</div></div></div>');
    
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'getimage',
	    search: document.formSearch.search.value,
	    start: intStartSearch
	}),
	success: function( data ){
	    if( data )
	    {
		intStartSearch++;
		$('.ImagePreview .Pad').append(data);
                $('.ImagePreview .Pad img:last').ready(function(){
		    if ( $('.ImagePreview .Pad img:last').width() < $('.ImagePreview .Pad img:last').height() && $('.ImagePreview .Pad img:last').width() > 200 )
			$('.ImagePreview .Pad img:last').attr('width',"200");
		    else if ( $('.ImagePreview .Pad img:last').height() < $('.ImagePreview .Pad img:last').width() && $('.ImagePreview .Pad img:last').height() > 200 )
			$('.ImagePreview .Pad img:last').attr('height',"200");
		    fctSetImageDrag();
		    fctSearch(false);
                });
	    }
	    else
	    {
		intStartSearch = 0;
		$('.ImagePreview .Pad').append('<div class="clear"></div>');
		fctActiveImage();
	    }
	},
	error: function(){
	    window.setTimeout( 'fctSearch(false);', 300 );
	}
    });
}

function fctShowGroups()
{
}

function fctHideGroups()
{
}

function fctSetImageDrag()
{
    $('.Image:last').bind('mousedown',function(e){
	var arrID = $(this).attr('id').split('-');
	fctStartImageDrag(e,arrID[1]);
    });  
}

function fctStartImageDrag(e,intItemID)
{
    // AddDrops
    fctGroupDrops();

    // Add new Dragger
    $('body > .Drag').draggable('destroy').remove();
    $('body').append('<div class="Drag"></div>');
    $('body > .Drag').draggable({
	cursor: 'move',
	opacity: 0.30,
	cursorAt: { left: 100, top: 100},
	refreshPositions: true,
	distance: 15,
	start: function(){
	    intImageDragID = intItemID;
	    intGroupDragID = false;
	    $('body > .Drag').html( $('#Image-'+intItemID+' .ButtonBigger').html() ).addClass('Image');
	    $('.Group .Button').addClass('Hide');
	},
	stop: function(){
	    $('body .Drag').draggable('destroy').remove();
	    $('.Group .Button').removeClass('Hide');
	}
    });
    $('body .Drag').trigger(e);
}

function fctGroupDrops()
{
    $(".Group").droppable({
	tolerance: 'pointer',
	over: function( event, ui ) {
	    $(this).addClass( 'GroupOver' );
	    $('.Groups .Preview').remove();
	    $(this).find('.Table').before('<div class="Preview"></div>');
	    $('.Groups .Preview').html( $('#Image-'+intImageDragID +' .ButtonBigger').html() );
	    $('body > .Drag').addClass('Hide');
	},
	out: function( event, ui ) {
	    $(this).removeClass( 'GroupOver' );
	    $('.Groups .Preview').remove();
	    $('body > .Drag').removeClass('Hide');
	},
	drop: function( event, ui ) {
	    $('.Group .Button').removeClass('Hide');
	    $(this).removeClass( 'GroupOver' );
	    $('.Groups .Preview').remove();
	    $('body > .Drag').removeClass('Hide');
	    arrParams = $(this).attr('id').split('-');
	    intGroupDragID = arrParams[1];
	    fctSetImageToGroup();
	}
    });
    
    $(".AddGroup").droppable({
	tolerance: 'pointer',
	over: function( event, ui ) {
	    $(this).addClass( 'AddGroupOver' );
	    $('.Groups .Preview').remove();
	    $(this).find('.Table').before('<div class="Preview Image"></div>');
	    $('.Groups .Preview').html( $('#Image-'+intImageDragID +' .ButtonBigger').html() );
	    $('body > .Drag').addClass('Hide');
	},
	out: function( event, ui ) {
	    $(this).removeClass( 'AddGroupOver' );
	    $('.Groups .Preview').remove();
	    $('body > .Drag').removeClass('Hide');
	},
	drop: function( event, ui ) {
	    $(this).removeClass( 'AddGroupOver' );
	    $('.Groups .Preview').remove();
	    $('body > .Drag').removeClass('Hide');
	    fctAddGroup();
	}
    });
}

function fctSetImageToGroup()
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'setimagetogroup',
	    image: intImageDragID,
	    group: intGroupDragID
	}),
	success: function( data ){
	    if( intGroup != intGroupDragID )
		$( "#Image-"+intImageDragID ).remove();
	},
	error: function( data ){
	    //alert( data );
	}
    });
}

function fctGetGroups()
{
    $('.Group .Table').unbind('click');
    $('.Groups .Content').html('');
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'getgroups'
	}),
	success: function( data ){
	    if( data )
	    {
		$('.Groups .Content').append(data);
		$('.Group .Table').bind('click',function(){
		    $('.Group').removeClass('GroupActive');
		    $(this).parent().addClass('GroupActive');
		});
		$('#mycarousel').jcarousel();
		fctResizeFolderSlider();
	    }
	    $('.AddGroup').bind('click',function(){
		fctAddGroup();
	    });
	    
	    fctGroupDrops();
	},
	error: function( data ){
	    //alert( data );
	}
    });
}

function fctResizeFolderSlider()
{
    var strWidth;
    intWidth = $(window).width()-300;
    if( intWidth < 400 )
	intWidth = 400;
    $('.jcarousel-container-horizontal').css('width',intWidth+'px');
    $('.jcarousel-clip-horizontal').css('width',(intWidth-20)+'px');
    $('#mycarousel').jcarousel();
}

function fctShowGroup( intGroupID )
{
    intGroup = intGroupID;
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'setgroup',
	    group: intGroupID
	}),
	success: function( data ){
	    fctSearch( true );
	},
	error: function( data ){
	    //alert( data );
	}
    }); 
}

function fctEditGroup( GroupID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'getgroup',
	    eig_id: GroupID
	}),
	success: function(data){
	    fctOpenMessage('<h1>'+ EaseImageTxT['EditGroup'] +'</h1><div class="Pad"><h3>'+EaseImageTxT['AddGroup2']+'<h3><p><form onsubmit="return false"><input type="text" name="editgroup" id="EditGroupName" value="'+ data.eig_name +'" /></form></p></div>','<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctEditGroup2(\''+ GroupID +'\');">'+ EaseImageTxT['Ok'] +'</a></div><div class="ButtonRight"></div></div><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseImageTxT['Cancel'] +'</a></div><div class="ButtonRight"></div></div></div>');
	},
	error: function(){}
    }); 
}

function fctEditGroup2( GroupID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'editgroup',
	    eig_id: GroupID,
	    eig_name: $('#EditGroupName').val()
	}),
	success: function(data){
	    if( !data.message )
	    {
		$('#Group-'+GroupID+' .TableCell').text( data.eig_name );
		fctCloseMessage();
	    }
	    else
		alert( data.message );
	},
	error: function(){}
    }); 
}

function fctAddGroup()
{
    fctOpenMessage('<h1>'+ EaseImageTxT['AddGroup'] +'</h1><div class="Pad"><h3>'+EaseImageTxT['AddGroup2']+'<h3><p><form onsubmit="return false"><input type="text" name="addgroup" id="AddGroupName" /></form></p></div>','<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctAddGroup2();">'+ EaseImageTxT['Ok'] +'</a></div><div class="ButtonRight"></div></div><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseImageTxT['Cancel'] +'</a></div><div class="ButtonRight"></div></div></div>');
    $('input[name="addgroup"]').focus();
}

function fctAddGroup2()
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'addgroup',
	    group: $('#AddGroupName').val()
	}),
	success: function(data){
	    intGroupDragID = data;
	    fctSetImageToGroup();
	    fctGetGroups();
	    fctCloseMessage();
	},
	error: function(){}
    }); 
}

function fctDeleteGroup( intDeleteID )
{
    fctOpenMessage('<h1>'+ EaseImageTxT['DeleteGroup'] +'</h1><div class="Pad"><p>'+EaseImageTxT['DeleteGroup2']+'</p></div>','<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctDeleteGroup2('+ intDeleteID +');">'+ EaseImageTxT['Yes'] +'</a></div><div class="ButtonRight"></div></div><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseImageTxT['No'] +'</a></div><div class="ButtonRight"></div></div></div>');
}

function fctDeleteGroup2( intDeleteID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'deletegroup',
	    group: intDeleteID
	}),
	success: function(data){
	    fctGetGroups();
	    if( intDeleteID == intGroup || intGroup == 0 )
	    {
		fctShowGroup( 0 );   
	    }
	    fctCloseMessage();
	},
	error: function(){}
    }); 
}

$(document).ready(function() {
    fctSetHeight();
    fctSearch( false );
    fctGetGroups();
});

$('.ImagePreview').scroll(function(){
    $('.Groups').css('top', $('.ImagePreview').scrollTop()+"px" );
});

window.onresize = function() {
    fctSetHeight();
    fctSetBiggerSize(true);
    fctResizeFolderSlider();
    //fctSetEditSize(true);
}