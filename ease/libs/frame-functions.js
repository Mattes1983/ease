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

// Drag
function fctEaseStartDrag(e,param)
{    
    $('.easeDrop').css("display","block");
    $('.easeDrag').css('display','block').css('top',e.pageY).css('left',e.pageX);
    $('.easeDrag').draggable({
	cursor: 'move',
	refreshPositions: true,
	start: function(){ 
	    window.top.easeDragAction = param;
	},
	stop: function(){ 
	    fctEaseStopDrag();
	    window.top.easeMod = window.top.easeModLast;
	    fctEaseInit();
	}
    });
    window.top.easeModLast = window.top.easeMod;
    fctEaseReset();
    $('.easeDrag').trigger(e);
}

function fctEaseStopDrag()
{
    $(".easeDrag").css("display","none");
    $(".easeDrop").css("display","none");
    if( window.top.easeMod == 'Move' )	    $('.easeMove:not(.easeEditBox .easeNotMove)').css("display","block");
    if( window.top.easeMod == 'Delete' )    $('.easeDelete').css("display","block");
    if( window.top.easeMod == 'Edit' )	    $('.easeEdit').css("display","block");
    if( window.top.easeMod == 'Copy' )	    $('.easeCopy').css("display","block");
    if( window.top.easeMod == 'Join' )	    $('.easeJoin').css("display","block");
    
    fctSetRelated();
}

// Move
function fctEaseMove()
{
    if( window.top.easeMod == 'Move' )
    {
        window.top.easeMod = '';
	$('.easeEditBox .easeMove:not(.easeEditBox .easeNotMove)').css("display","none");
	$('.easeEditBox div.easeMove').unbind('mousedown');
    }
    else
    {
	
	fctEaseReset();
	
	// Mod-Var
	window.top.easeMod = 'Move';
	
	$('.easeEditBox div.easeMove').bind("mousedown",function(e){
	    $('.easeDrag').html( $(this).parent().html() );
	    $(this).parent().css("display","none");
	    window.top.easeDragAction = $(this).attr("rel");
	    
	    $('.easeDrop').css("display","block");
	    $('.easeDrag').css('display','block').css('top',e.pageY).css('left',e.pageX);
	    $('.easeDrag').draggable({
		cursor: 'move',
		refreshPositions: true,
		start: function(){ 
		    $('.easeMove:not(.easeEditBox .easeNotMove)').css("display","none");
		    $('.easeDrag .easeDrop').css("display","none");
		},
		stop: function(){ 
		    $(".easeDrag").css("display","none");
		    $(".easeDrop").css("display","none");
		    $('.easeMove:not(.easeEditBox .easeNotMove)').css("display","block");
		    window.top.easeMod = 'Move';
		    fctSetRelated();
		    $('.easeEditBox').css("display","block");
                    $(".easeDrag").html("");
		}
	    });
	    window.top.easeModLast = window.top.easeMod;
	    $('.easeDrag').trigger(e);
	    
	    arrParams = $(this).parent().attr("rel").split("-");
	    $("#drop-"+ arrParams[1] +"-"+ arrParams[2] +"-"+ arrParams[3]).css("display","none");
	    $("#drop-"+ arrParams[1] +"-"+ (parseInt(arrParams[2])+1) +"-"+ arrParams[3] ).css("display","none");
	});

        $('.easeEditBox .easeMove:not(.easeEditBox .easeNotMove)').css("display","block");
	$('.easeEditBox .easeMove:not(.easeEditBox .easeNotMove)').each(function(){
            if( window.top.easeEditID.length > 0 )
                for( i in window.top.easeEditID )
                    if( $(this).attr("id") == 'Move-'+ window.top.easeEditID[i] )
                        $(this).css("display","none");
	});
    }
    fctSetRelated();
}

// Delete
function fctEaseDelete()
{
    if( window.top.easeMod == 'Delete' )
    {
        window.top.easeMod = '';
	$('.easeEditBox .easeDelete').css("display","none");
    }
    else
    {
	fctEaseReset();
        window.top.easeMod = 'Delete';
        $('.easeEditBox .easeDelete').css("display","block");
	$('.easeEditBox .easeDelete').each(function(){
            if( window.top.easeEditID.length > 0 )
                for( i in window.top.easeEditID )
                    if( $(this).attr("id") == 'Delete-'+ window.top.easeEditID[i] )
                        $(this).css("display","none");
	});
    }
    fctSetRelated();
}

// Edit
function fctEaseEdit()
{
    if( window.top.easeMod == 'Edit' )
    {
        window.top.easeMod = '';
	$('.easeEdit2').each(function(){
            var doTrigger = true;
            if( window.top.easeEditID.length > 0 )
                for( i in window.top.easeEditID )
                    if( $(this).attr("id") == 'Edit2-'+ window.top.easeEditID[i] || $(this).attr("id") == 'Edit4-'+ window.top.easeEditID[i] )
                        doTrigger = false;
            if( $(this).css("display") == "block" && doTrigger == true )
                $(this).trigger("click");

	});
	$('.easeEditBox .easeEdit').css("display","none");
	$('.easeEditBox .easeEdit3').css("display","none");
    }
    else
    {
	fctEaseReset();
        window.top.easeMod = 'Edit';
        $('.easeEditBox .easeEdit').css("display","block");
	$('.easeEditBox .easeEdit').each(function(){
            if( window.top.easeEditID.length > 0 )
                for( i in window.top.easeEditID )
                    if( $(this).attr("id") == 'Edit-'+ window.top.easeEditID[i] )
                        $(this).css('display','none');
	});
	
	$('.easeEditBox .easeEdit3').css("display","block");
    }
    fctSetRelated();
}

// Copy
function fctEaseCopy()
{
    if( window.top.easeMod == 'Copy' )
    {
        window.top.easeMod = '';
	$('.easeCopy').css("display","none");
    }
    else if( window.top.easeMod == 'CopyDrop' )
    {
        window.top.easeMod = '';
	$('.easeCopyDrop').css("display","none");
    }
    else
    {
	fctEaseReset();
        window.top.easeMod = 'Copy';
	$('.easeCopy').css("display","block");
    }
    fctSetRelated();
}

// Copy Drop
function fctEaseCopyDrop()
{
    if( window.top.easeMod == 'CopyDrop' )
    {
        window.top.easeMod = '';
	$('.easeCopyDrop').css("display","none");
    }
    else
    {
	fctEaseReset();
        window.top.easeMod = 'CopyDrop';
	$('.easeCopyDrop').css("display","block");
    }
    fctSetRelated();
}

// Join
function fctEaseJoin()
{
    if( window.top.easeMod == 'Join' )
    {
        window.top.easeMod = '';
	$('.easeJoin').css("display","none");
    }
    else if( window.top.easeMod == 'JoinDrop' )
    {
        window.top.easeMod = '';
	$('.easeJoinDrop').css("display","none");
    }
    else
    {
	fctEaseReset();
        window.top.easeMod = 'Join';
	$('.easeJoin').css("display","block");
    }
    fctSetRelated();
}

// Join Drop
function fctEaseJoinDrop()
{
    if( window.top.easeMod == 'JoinDrop' )
    {
        window.top.easeMod = '';
	$('.easeJoinDrop').css("display","none");
    }
    else
    {
	fctEaseReset();
        window.top.easeMod = 'JoinDrop';
	$('.easeJoinDrop').css("display","block");
    }
    fctSetRelated();
}

function fctEaseDropStart()
{
    $(".easeDrop").droppable({
	tolerance: 'pointer',
	over: function( event, ui ) {
		$(this).addClass( "easeDrop2" );
		$(this).html( $('.easeDrag').html() );
		$('.easeDrag').css('display','none');
	},
	out: function( event, ui ) {
		$(this).removeClass( "easeDrop2" );
		$(this).html("");
		$('.easeDrag').css('display','block');
	},
	drop: function( event, ui ) {
		$(this).removeClass( "easeDrop2" )
		fctEaseStopDrag();
		arrParams = $(this).attr("id").split("-");
		window.top.easeDragAction += "&lin_parent="+arrParams[1]+"&lin_order="+arrParams[2]+"&lin_name="+arrParams[3];
		window.top.easeMod = window.top.easeModLast;
		window.top.fctGlobalLoadingStart(function(){
		    window.location.href = window.top.easeDragAction; 
		});
	}
    });
    fctSetRelated();
}

function fctSetRelated( boolHide )
{
    switch ( window.top.easeMod )
    {
	case 'Edit':
	case 'Move':
	case 'Delete':
		$('.easeRelatedHide').addClass('easeRelated');
		$('.easeRelatedHide').removeClass('easeRelatedHide');
		break;
	default:
		$('.easeRelated').addClass('easeRelatedHide');
		$('.easeRelated').removeClass('easeRelated');
		break;
    }
    
    if( boolHide == true )
    {
	$('.easeRelated').addClass('easeRelatedHide');
	$('.easeRelated').removeClass('easeRelated');
    }
}

function fctEaseInit()
{
    if( window.top.easeMod == "Move" )	    {window.top.easeMod = "";fctEaseMove();}
    if( window.top.easeMod == "Delete" )    {window.top.easeMod = "";fctEaseDelete();}
    if( window.top.easeMod == "Edit" )	    {window.top.easeMod = "";fctEaseEdit();}
    if( window.top.easeMod == "Copy" )	    {window.top.easeMod = "";fctEaseCopy();}
    if( window.top.easeMod == "CopyDrop" )  {window.top.easeMod = "";fctEaseCopyDrop();}
    if( window.top.easeMod == "Join" )	    {window.top.easeMod = "";fctEaseJoin();}
    if( window.top.easeMod == "JoinDrop" )  {window.top.easeMod = "";fctEaseJoinDrop();}
    fctSetRelated();
}

function fctEaseReset()
{
    if( window.top.easeMod == 'Move' )	    fctEaseMove();
    if( window.top.easeMod == 'Delete' )    fctEaseDelete();
    if( window.top.easeMod == 'Edit' )	    fctEaseEdit();
    if( window.top.easeMod == 'Copy' )	    fctEaseCopy();
    if( window.top.easeMod == 'CopyDrop' )  fctEaseCopyDrop();
    if( window.top.easeMod == 'Join' )	    fctEaseJoin();
    if( window.top.easeMod == 'JoinDrop' )  fctEaseJoinDrop();
    fctSetRelated();
}

$(document).ready(function() {
    
    fctEaseDropStart();
    fctEaseInit();
    
    $('.easeDelete').click(function(){
	window.top.easeDeleteAction = $(this).attr("rel");
	window.top.fctGlobalLoadingStart(function(){
	    window.location.href = window.top.easeDeleteAction;
	});
    });
    
    $('.easeEdit,.easeEdit2,.easeEdit3,.easeMove,.easeJoin,.easeCopy,.easeDelete').bind("mouseover",function(){
	$(this).parent().addClass("easeEditBoxActive");
    });

    $('.easeEdit,.easeEdit2,.easeEdit3,.easeMove,.easeJoin,.easeCopy,.easeDelete').bind("mouseout",function(){
	$(this).parent().removeClass("easeEditBoxActive");
    });
    
    $('.easeEdit').click(function(){
	if( $(this).attr("id") )
	{
	    arrParams = $(this).attr("id").split("-");
	    window.top.easeEditID[arrParams[1]] = arrParams[1];
	    $("#Edit2-"+arrParams[1]).css("display","block");
            $("#Edit4-"+arrParams[1]).css("display","block");
	    $('#'+$(this).attr("id")).css("display","none");

	    // Reset open Edit
	    /*
	    var EditID = $(this).attr("id");
	    $('.easeEdit2').parent().has( '#'+ EditID ).addClass('EditNotClose');
	    $('.easeEdit2:not(.EditNotClose .easeEdit2)').each(function(){
		if( $(this).css("display") == "block" )
		    $(this).trigger("click");
	    });
	    $('.EditNotClose').removeClass('EditNotClose');
	    */
	}
    });
    
    $('.easeEdit2:not(.easeEdit2[onclick])').click(function(){
	if( $(this).attr("id") )
	{
	    //$(this).find('.easeEdit2:visible').trigger("click");
	    arrParams = $(this).attr("id").split("-");
	    delete( window.top.easeEditID[arrParams[1]] );
	    $(this).css("display","none");
	    if( window.top.easeMod == 'Edit' )  $("#Edit-"+arrParams[1]).css("display","block");
	}
    });

    $('.easeEdit2[onclick]').each(function(){
	$(this).attr('rel', $(this).attr('onclick') );
	$(this).attr('onclick','');
    });

    $('.easeEdit2[onclick]').click(function(){
        arrParams = $(this).attr("id").split("-");
	var strRel = $(this).attr('rel');
	window.top.fctGlobalLoadingStart(function(){
	    delete( window.top.easeEditID[arrParams[1]] );
	    if( window.top.easeMod == 'Edit' )  $("#Edit-"+arrParams[1]).css("display","block");
	    $("#Edit2-"+arrParams[1]).css("display","none");
            $("#Edit4-"+arrParams[1]).css("display","none");
	    eval( strRel );
	});
    });
    
    $('.easeEdit3').click(function(){
	arrParams = $(this).attr("id").split("-");
	window.top.fctEaseOpenExtensionPopup( arrParams[1],arrParams[2],arrParams[3],arrParams[4],arrParams[5],arrParams[6] );
    });
    
    $('.easeCopy').click(function(){
	arrParams = $(this).attr("id").split("-");
	window.top.easeCopyID = arrParams[1];
	fctEaseReset();
	fctEaseCopyDrop();
    });
    
    $('.easeCopyDrop').click(function(){
	CopyURL = $(this).attr("rel") + "&cid="+window.top.easeCopyID;
	window.top.easeMod = "Copy";
	window.top.fctGlobalLoadingStart(function(){
	   window.location.href = CopyURL;  
	});
    });
    
    $('.easeJoin').click(function(){
	arrParams = $(this).attr("id").split("-");
	window.top.easeJoinID = arrParams[1];
	fctEaseReset();
	fctEaseJoinDrop();
    });
    
    $('.easeJoinDrop').click(function(){
	JoinURL = $(this).attr("rel") + "&jid="+window.top.easeJoinID;
	window.top.easeMod = "Join";
	window.top.fctGlobalLoadingStart(function(){
	   window.location.href = JoinURL;  
	});
    });
    
    if( window.top.easeEditID.length > 0 )
    {
        for( i in window.top.easeEditID )
            $('#Edit-'+window.top.easeEditID[i]).trigger("click");
    }
    
    if( top.easeExternPopupType == 2 )
    {
	top.$('#easeExtensionPopupSizeBorder').css("display","none");
	top.$('#easeExtensionPopupSize').attr("src", "extension-popup.php");
    }
    
    window.top.fctGlobalLoadingEnd(false);
    
});