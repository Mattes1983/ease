
/*******************************************************************

    Copyright notice

    (c) 2012 Matthias Dahms <matthias.dahms@ease-cms.com>

    This file is part of ease CMS.

    ease CMS is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    ease CMS is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with ease CMS.  If not, see <http://www.gnu.org/licenses/>.

    This copyright notice MUST APPEAR in all copies of the script!

*******************************************************************/

var easeDragAction = '';
var easeDeleteAction = '';
var easeMod = 'Edit';
var easeInitMod = '';
var easeModLast = '';
var easeEditID = new Array();
var easeCopyID;
var easeJoinID;
var easeGoSearch;
var easeExternPopupType;
var easeExtensionTop;
var easeExtensionLeft;
var easeToolbarTop;
var easeToolbarLeft;
var easeToolbarMinWidth;
var easeToolbarMinHeight;
var easeToolbarNormalWidth;
var easeToolbarNormalHeight;
var easeToolbarMaxWidth;
var easeToolbarMaxHeight;
var easeToolbarHeight;
var easeToolbarMode = 'normal';
var easeToolbarModeOld;
var easeToolbarHeight;
var easeLvlHeight;

function fctInitToolbar()
{
    // Init
    $('#easeToolbox').after('<div id="InitToolbar" style="display:none;"></div>');
    
    // Min-Size
    $('#InitToolbar').addClass("easeMinimize");
    easeToolbarMinWidth = $('#InitToolbar').width();
    easeToolbarMinHeight = $('#InitToolbar').height();
    $('#InitToolbar').removeClass("easeMinimize");
    
    // Normal-Size
    $('#InitToolbar').addClass("easeNormal");
    easeToolbarNormalWidth = $('#InitToolbar').width();
    easeToolbarNormalHeight = $('#InitToolbar').height();
    $('#InitToolbar').removeClass("easeNormal");
    
    // Max-Size
    $('#InitToolbar').addClass("easeMaximize");
    easeToolbarMaxWidth = $('#InitToolbar').width();
    easeToolbarMaxHeight = "100%";
    $('#InitToolbar').removeClass("easeMaximize");

    // Check for 100%
    if( easeToolbarMinHeight == $(document).height() ) easeToolbarMinHeight = "100%";
    if( easeToolbarNormalHeight == $(document).height() ) easeToolbarNormalHeight = "100%";
    if( easeToolbarMaxHeight == $(document).height() ) easeToolbarMaxHeight = "100%";
    
    if( easeToolbarMinWidth == $(document).width() ) easeToolbarMinWidth = "100%";
    if( easeToolbarNormalWidth == $(document).width() ) easeToolbarNormalWidth = "100%";
    if( easeToolbarMaxWidth == $(document).width() ) easeToolbarMaxWidth = "100%";

    // Remove
    $('#InitToolbar').remove();
}

function fctSetToolbarPosition()
{
    if( easeToolbarMode == 'normal' )
    {
	// Top
	if( $(window).height() < ($('#easeToolbox').height()+ $('#easeToolbox').offset().top ) )
	{
	    if( ($(window).height()-$('#easeToolbox').height()) >= 0 )
		easeToolbarTop = ($(window).height() - $('#easeToolbox').height() );
	    else
		easeToolbarTop = 0;
	    $('#easeToolbox').css("top",easeToolbarTop+"px");

	}
	else if( $('#easeToolbox').offset().top < 0 )
	{
	    $('#easeToolbox').css("top","0px");
	}
	
	// Left
	if( $(window).width() < ($('#easeToolbox').width()+ $('#easeToolbox').offset().left ) )
	{
	    if( ($(window).width()-$('#easeToolbox').width()) >= 0 )
		easeToolbarLeft = ($(window).width() - $('#easeToolbox').width() );
	    else
		easeToolbarLeft = 0;
	    $('#easeToolbox').css("left",easeToolbarLeft+"px");
	}
	else if( $('#easeToolbox').offset().left < 0 )
	{
	    $('#easeToolbox').css("left","0px");
	}
    }
}

function fctSetExtensionPosition()
{
    // Top
    if( $(window).height() < ($('#easeExtensionPopupSizeBorder').height()+ $('#easeExtensionPopupSizeBorder').offset().top ) )
    {
	if( ($(window).height()-$('#easeExtensionPopupSizeBorder').height()) >= 0 )
	    easeExtensionTop = ($(window).height() - $('#easeExtensionPopupSizeBorder').height() );
	else
	    easeExtensionTop = 0;
	$('#easeExtensionPopupSizeBorder').css("top",easeExtensionTop+"px");

    }
    else if( $('#easeExtensionPopupSizeBorder').offset().top < 0 )
    {
	$('#easeExtensionPopupSizeBorder').css("top","0px");
    }

    // Left
    if( $(window).width() < ($('#easeExtensionPopupSizeBorder').width()+ $('#easeExtensionPopupSizeBorder').offset().left ) )
    {
	if( ($(window).width()-$('#easeExtensionPopupSizeBorder').width()) >= 0 )
	    easeExtensionLeft = ($(window).width() - $('#easeExtensionPopupSizeBorder').width() );
	else
	    easeExtensionLeft = 0;
	$('#easeExtensionPopupSizeBorder').css("left",easeExtensionLeft+"px");
    }
    else if( $('#easeExtensionPopupSizeBorder').offset().left < 0 )
    {
	$('#easeExtensionPopupSizeBorder').css("left","0px");
    }
}

function fctToolbarMin()
{
    // Position
    if( easeToolbarMode == 'normal' )
    {
	easeToolbarTop = $('#easeToolbox').css("top"); // Save old Position (Top)
	easeToolbarLeft = $('#easeToolbox').css("left"); // Save old Position (Left)
    }
    
    // Animation
    $('#easeToolbox').draggable('destroy');    
    $('#easeToolbox').animate({
	height: easeToolbarMinHeight,
	width: easeToolbarMinHeight,
	top: '0',
	left: '0'
    },200,function(){
	// Show minimize content
	$('#easeToolbox').removeClass("easeNormal").removeClass("easeMaximize");
	$('#easeToolbox').addClass("easeMinimize");
	
	// "Back"-Link
	$('#easeToolbox').css("cursor","pointer").bind('click',function(){
	   if( easeToolbarModeOld == 'normal' )
	       fctToolbarNormal();
	   else
	       fctToolbarMax();
	});
    });
    
    easeToolbarModeOld = easeToolbarMode;
    easeToolbarMode = 'min';
    fctSetEaseMainSize();
}

function fctToolbarNormal()
{
    // Show normal content
    $('#easeToolbox').removeClass("easeMinimize").removeClass("easeMaximize");
    $('#easeToolbox').addClass("easeNormal");
    
    $('#easeToolbox').css("cursor","default").unbind("click");
    
    // Animation
    if( easeToolbarNormalWidth && easeToolbarNormalHeight && easeToolbarTop && easeToolbarLeft )
    {
	$('#easeToolbox').animate({
	    height: easeToolbarNormalHeight,
	    width: easeToolbarNormalWidth,
	    top: easeToolbarTop,
	    left: easeToolbarLeft
	},200,function(){
	    $('#easeToolbox').draggable({
		cursor: 'move',
		handle: 'h1',
		iframeFix: true,
		start: function(){
		    $(document).bind("mousemove",function(){
			fctSetToolbarPosition();
		    })
		},
		stop: function(){
		    $(document).unbind("mousemove");
		}
	    });
	    fctSetEaseLvlHeight();
	    fctSetToolbarPosition();
	});
    }
    easeToolbarModeOld = easeToolbarMode;
    easeToolbarMode = 'normal';
    fctSetEaseMainSize();
}

function fctToolbarMax()
{
    // Position
    if( easeToolbarMode == 'normal' )
    {
	easeToolbarTop = $('#easeToolbox').css("top"); // Save old Position (Top)
	easeToolbarLeft = $('#easeToolbox').css("left"); // Save old Position (Left)
    }
    
    // Show normal content
    $('#easeToolbox').removeClass("easeMinimize").removeClass("easeNormal");
    $('#easeToolbox').addClass("easeMaximize");
    
    $('#easeToolbox').css("cursor","default").unbind("click");
    $('#easeToolbox').draggable('destroy');    
    
    easeToolbarModeOld = easeToolbarMode;
    easeToolbarMode = 'max';
    
    // Animation
    $('#easeToolbox').animate({
	height: easeToolbarMaxHeight,
	width: easeToolbarMaxWidth,
	top: '0',
	left: '0'
    },200,function(){
	fctSetEaseMainSize();
	fctSetEaseLvlHeight();
    });
}

function fctSetEaseMainSize()
{
    if( easeToolbarMode == 'max' )
	$('#easeMain').css("width", (top.$(window).width()-easeToolbarMaxWidth)+"px" ).css("marginLeft",easeToolbarMaxWidth+"px");
    else
	$('#easeMain').css("width", top.$(window).width()+"px" ).css("marginLeft","0");
}

function fctSetEaseLvlHeight()
{
    difference = top.$(document).height() - parseInt( easeToolbarHeight );
    if( easeToolbarMode == 'max' )
	$('.easeLvl').css("height",(difference+easeLvlHeight)+"px");
    else
	$('.easeLvl').css("height",easeLvlHeight+"px");
}

function fctEaseStartDrag(e,param)
{
    $('.easeDrag').draggable({
	cursor: 'move',
	distance: 5,
	start: function(){
	    $('#easeToolbox').unbind('mouseup');
	    $('#easeMain').mouseover(function(){
		$('#easeMain').ready(function(){
		    fctEaseStopDrag();
		    window.easeMain.fctEaseStartDrag(e,param);
		});
	    });
	},
	stop: function() {
	    fctEaseStopDrag();
	}
    });
    $('.easeDrag').trigger(e);
    $('.easeDrag').css('display','block').css('top',(e.pageY+5)).css('left',e.pageX);
    
    $('#easeToolbox').bind('mouseup',function(){
	$('#easeToolbox').unbind('mouseup');
	fctEaseStopDrag();
    });
}

function fctEaseStopDrag()
{
    $('.easeDrag').css("display","none");
    $('#easeMain').unbind("mouseover");
}

function fctEaseShowURL( url )
{
    fctGlobalLoadingStart(function(){
	$('#easeMain').attr("src", url);
    });
}

function fctEaseReload()
{
    fctGlobalLoadingStart(function(){
	$('#easeMain').attr("src", "parse.php");
    });
}

function fctEaseOpenExtensionPopup( intExtID,strName,strValue,intLinID,intW,intH )
{    
    // Title
    $('#easeExtensionPopupSizeBorder h1').html( "&nbsp;" );
    $.ajax({
	url: "action.php",
	type: "GET",
	data: ({ 
	    action: 'get_extension_item_title_request',
	    lin_id: intLinID,
	    ext_id: intExtID,
	    ext_name: strName,
	    ext_value: strValue
	}),
	success: function( data ){
	    if( data )
	    {
		$('#easeExtensionPopupSizeBorder h1').html( data );
	    }
	}
    });
    
    $('#easeMain').after('<div id="easeBlackBG"></div>');
    $('#easeBlackBG').css('opacity','0.4');
    
    top.$('#easeToolbox').fadeOut(200,function(){
	if( intW == 0 || intH == 0 )
	{
	    easeExternPopupType = 1;
	    
	    if( navigator.userAgent.indexOf("Firefox") != -1 )
	    {
	        $('#easeMain').animate({
		    opacity: '0.1'
		},200,function(){
		    easeExtensionPopup.location.href = 'extension-popup.php?ext_id='+ intExtID+'&lin_id='+ intLinID+"&name="+strName+"&value="+strValue;
		});
	    }
	    else
	    {
	        $('#easeMain').fadeOut(200,function(){
		    easeExtensionPopup.location.href = 'extension-popup.php?ext_id='+ intExtID+'&lin_id='+ intLinID+"&name="+strName+"&value="+strValue;
		});
	    }
	}
	else
	{	    
	    easeExternPopupType = 2;
	    window.top.easeModLast = window.top.easeMod;
	    top.easeMain.fctEaseReset();
	    $('#easeExtensionPopupSizeBorder').css("width",(parseInt(intW))+"px").css("height",(parseInt(intH)+39)+"px").css("left",(($(window).width()/2)-Math.round(intW/2))+"px").css("top",(($(window).height()/2)-Math.round(intH/2))+"px");
	    $('#easeExtensionPopupSize').css("width",intW+"px").css("height",intH+"px");
	    top.easeExtensionPopupSize.location.href = 'extension-popup.php?ext_id='+ intExtID+'&lin_id='+ intLinID+"&name="+strName+"&value="+strValue;
	    $('#easeExtensionPopupSizeBorder').draggable({
		cursor: 'move',
		handle: 'h1',
		iframeFix: true,
		start: function(){
		    $(document).bind("mousemove",function(){
			fctSetExtensionPosition();
		    })
		},
		stop: function(){
		    $(document).unbind("mousemove");
		}
	    });
	}
    });
    $('#easeMain').css("width", top.$(window).width()+"px" ).css("marginLeft","0");
}

function fctEaseCloseExtensionPopup()
{
    $('#easeBlackBG').remove();
    if( top.easeExternPopupType == 1 )
    {
        fctSetEaseMainSize();
	$('#easeExtensionPopup').fadeOut(200,function(){
	    $('#easeToolbox').fadeIn(500);
	    $('#easeMain').fadeIn(500,function(){
		if( navigator.userAgent.indexOf("Firefox") != -1 )
		{
		    $('#easeMain').animate({
			opacity: '1'
		    },1);
		}
	    });
	});
    }
    else
    {
	top.easeMod = top.easeModLast;
	top.easeMain.fctEaseInit();
	$('#easeExtensionPopupSizeBorder').fadeOut(200,function(){
	    $('#easeToolbox').fadeIn(500,function(){
		fctSetEaseMainSize();
		fctEaseReload();
	    });
	    $('#easeExtensionPopupSizeBorder').draggable('destroy');
	});
    }
    window.top.easeExternPopupType = 0;
}

function fctEaseCloseReloadExtensionPopup()
{
    $('#easeBlackBG').remove();
    if( top.easeExternPopupType == 1 )
    {
        fctSetEaseMainSize();
	
	if( navigator.userAgent.indexOf("Firefox") != -1 )
	{
	    $('#easeMain').animate({
		opacity: '1'
	    },200,function(){
		$('#easeExtensionPopup').fadeOut(200,function(){
		    $('#easeToolbox').fadeIn(200);
		    easeMain.location.href = "parse.php";
		});	
	    });
	}
	else
	{	
	    $('#easeExtensionPopup').fadeOut(200,function(){
		$('#easeToolbox').fadeIn(200);
		easeMain.location.href = "parse.php";
	    });
	}
    }
    else
    {
	window.top.easeMod = window.top.easeModLast;
	top.easeMain.fctEaseInit();
	$('#easeExtensionPopupSizeBorder').fadeOut(200,function(){
	    $('#easeToolbox').fadeIn(200,function(){
		fctSetEaseMainSize();
	    });
	    $('#easeMain').fadeOut(200, function(){
		easeMain.location.href = "parse.php";
		$('#easeExtensionPopupSizeBorder').draggable('destroy');
	    })
	});
    }
    top.easeExternPopupType = 0;
}

function fctEaseSaveExtensionPopup( url )
{
    $('#easeBlackBG').remove();
    if( top.easeExternPopupType == 1 )
    {
	$('#easeExtensionPopup').fadeOut(200,function(){
	    $('#easeToolbox').fadeIn(200);
	    easeMain.location.href = url;
	});
    }
    else
    {
	window.top.easeMod = window.top.easeModLast;
	$('#easeExtensionPopupSizeBorder').fadeOut(200,function(){
	    $('#easeExtensionPopupSizeBorder').draggable('destroy');
	    $('#easeToolbox').fadeIn(200,function(){
		fctSetEaseMainSize();
	    });
	    $('#easeMain').fadeOut(200, function(){
		easeMain.location.href = "parse.php";
	    })
	});
    }
    top.easeExternPopupType = 0;
}

$(document).ready(function() {
    
    easeMain.location.href = "parse.php";
    
    $('#easeToolbox').draggable({
	cursor: 'move',
	handle: 'h1',
	iframeFix: true,
	start: function(){
	    $(document).bind("mousemove",function(){
		fctSetToolbarPosition();
	    })
	},
	stop: function(){
	    $(document).unbind("mousemove");
	}
    });
    $('a.Dragger').mousedown(function(e) {
	fctEaseStartDrag(e,$(this).attr("rel"));
	$('.easeDrag').html( "loading..." );
	window.easeMain.$('.easeDrag').html( "loading..." );
	$.ajax({
	    url: $(this).attr("rev"),
	    type: "GET",
	    success: function(data){
		$('.easeDrag').html( data );
		window.easeMain.$('.easeDrag').html( data );
	    }
	});
    });
    
        
    // Menu-Active
    $('ul.Menu li a').each(function(index) {
	
	$(this).attr("rel", $(this).attr("href") ).attr("href","javascript:;");
	
	$(this).click(function(){
	    
	    $("ul.Menu li a").removeClass("Active");
	    $(this).addClass("Active");
	    
	    $(".easeLvl").removeClass("Active");
	    $(".easeLvl:eq("+ index +")").addClass("Active");
	    
	    // Page-Reload
	    //fctEaseShowURL( $(this).attr("rel") );
	    
	});
    });
    
    // Items
    $('.Item a.Button:not(a.Dragger,a.ExtensionPopup)').each(function(index) {
	$(this).attr("rel", $(this).attr("href") ).attr("href","javascript:;");
	$(this).click(function(){
	    fctEaseShowURL( $(this).attr("rel") );
	});
    });
    
    // Items Active
    /*
    $('.Item').each(function(index) {
	$(".Item:eq("+ index +") .Button").click(function(){
	    $(".Item:eq("+ index +") .Button").removeClass("Active");
	    $(this).addClass("Active");
	});
    });
    */
    
    // Action-Active
    $('.Action a:not(li.ButtonLinkInfo a)').each(function(index) {
		
	$(this).click(function(){
	    
	    if( $(this).hasClass( "Active" ) == true )
	    {
		$(this).removeClass("Active");
	    }
	    else
	    {
		$(".Action a").removeClass("Active");
		$(this).addClass("Active");
	    }
	    
	});
    });   
    
    fctInitToolbar();
    easeLvlHeight = $('.easeLvl:eq(0)').height();
    easeToolbarHeight = $('#easeToolbox').height();
    fctSetToolbarPosition();
    fctSetExtensionPosition(); 
});

function fctEasePreSearch()
{
    if( easeGoSearch ) window.clearTimeout( easeGoSearch );
    easeGoSearch = window.setTimeout( "fctEaseSearchDoc();", 600 );
}

function fctEaseSearchDoc()
{
    if( $('.Search .List').css("display") == "block" )
	$('.Search .List').css("display","none");

    if( $('.SearchInput').attr("value") != EaseToolbarTxT['Search'] )
    {
	$.ajax({
	    url: "action.php",
	    type: "GET",
	    data: ({ 
		action: 'search_document_request',
		search: $('.SearchInput').attr("value")
	    }),
	    success: function( data ){
		if( data )
		{
		    $('.Search .List').html(data);
		    $('.Search .List').fadeIn(200,function(){
			$('.Search .List').css("display","block");
			$('.Search .List').bind("mouseleave",function(){
			    $('.Search .List').css("display","none");
			});
		    });
		}
	    }
	});
    }
}

function fctCheckMainFrame( strMode )
{
    window.easeMain.$('window').ready(function(){
	switch( strMode )
	{
	    case "Edit": window.easeMain.fctEaseEdit(); break;
	    case "Move": window.easeMain.fctEaseMove(); break;
	    case "Join": window.easeMain.fctEaseJoin(); break;
	    case "Copy": window.easeMain.fctEaseCopy(); break;
	    case "Delete": window.easeMain.fctEaseDelete(); break;
	    case "LinkInfo": fctEaseShowURL('parse.php?showlinkinfo=1'); break;
	}
    });
}

window.onresize = function()
{
    fctSetEaseMainSize();
    fctSetEaseLvlHeight();
    fctSetToolbarPosition();
    fctSetExtensionPosition();
}

function fctGlobalLoadingStart( funcObj )
{    
    if( $('#easeLoading').css('display') == "none" )
    {
	$('#easeLoading').fadeIn(200, function(){
	    $('#easeMain,#easeResetbox').hide();
	    if( funcObj ) funcObj.apply();
	});
    }
    else
    {
	if( funcObj ) funcObj.apply();
    }
}

function fctGlobalLoadingEnd( funcObj )
{
    $('#easeMain,#easeResetbox').show();
    $('#easeLoading').fadeOut(500,function(){
	if( funcObj ) funcObj.apply();
	$('#easeLoading').css('display','none');
    });
}