var DocID;
var EnlID;
var GoSearch;
var boolDragStart = false;
var arrMenuActive = new Array();
var objMouseOver = false;
var objMouseOver2 = false;
var maxLvl = 10;
var intLvlID;

$(document).ready(function() {  
    fctSetHeight();
    fctSetItemPosition();
    fctBindItems();
    fctSetDrops();
    fctSetUlLvl();
    fctSearch();
});

window.onresize = function() {
    fctSetHeight();
    fctSetItemPosition();
    fctResizeDocSlider();
}

// Resize-Items
function fctSetHeight()
{
    $('.Menu').css("height",($(window).height()-325)+"px");
}

function fctPreSearch()
{
    if( GoSearch ) window.clearTimeout( GoSearch );
    GoSearch = window.setTimeout( "fctSearch(true);", 1000 );
}

function fctSearch( boolReset )
{
    if( boolReset ) $('.Documents .Content').html("");

    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'docs',
	    search: document.formSearch.search.value
	}),
	success: function( data ){
	    if( data )
	    {
		$('.Documents .Content').append(data);
		fctSetDocDrags();
		$('#mycarousel').jcarousel();
		fctResizeDocSlider();
	    }
	}
    });
}

function fctResizeDocSlider()
{
    var strWidth;
    intWidth = $(window).width()-100;
    if( intWidth < 400 )
	intWidth = 400;
    $('.jcarousel-container-horizontal').css('width',intWidth+'px');
    $('.jcarousel-clip-horizontal').css('width',(intWidth-20)+'px');
    $('#mycarousel').jcarousel();
}

function fctSetDocDrags()
{
    // Add Dragging
    $('.Documents .Doc').bind('mousedown',function(e) {
	arrDoc = $(this).attr("id").split("-");
	DocID = arrDoc[1];
	EnlID = "";
	fctStartDocDrag(e,DocID);
    });
}

function fctStartDocDrag( e,intDocID )
{
    // Add new Dragger
    $('body .Drag').draggable('destroy').remove();
    $('body').append('<div class="Drag"></div>');
    $('body > .Drag').draggable({
	cursor: 'move',
	opacity: 0.30,
	cursorAt: { left: 86, top: 58},
	refreshPositions: true,
	start: function(){
	    
	    // Copy Doc-Content to Dragger
	    $('body > .Drag').append( '<div class="Doc">'+ $('#Doc-'+ intDocID).html() +'</div>');
	    $('body > .Drag .IconMove').remove();
	    
	    fctStartItemDrop();
	    fctDrawLines();
	    
	},
	stop: function(){
	    // Remove-Dragger
	    $('body > .Drag').draggable('destroy');
	    $('body > .Drag').remove();
	    
	    // Remove Under
	    $('#Under').parent().remove();
	    
	    // Redraw Lines
	    fctDrawLines();
	    
	    // Stop Drop
	    fctEndItemDrop();
	    
	}
    });
    $('body .Drag').trigger(e);
}

function fctBindItems()
{
    
    $('.Menu .Item').unbind('mouseenter mouseleave mousedown');
    
    // Open
    $('.Menu .Item').bind('mouseenter',function(){
	intLvlID = fctGetLvlClass( $(this).parent().parent().attr('class') );
	arrItemID = $(this).attr('id').split('-');
	if( objMouseOver )
	    clearTimeout( objMouseOver );
	if( $(this).next().find('li').length > 0 && arrMenuActive[intLvlID] != arrItemID[1] )
	{
	    $(this).css('cursor','wait');
	    objMouseOver = setTimeout('fctMenuActivate( '+intLvlID+','+arrItemID[1]+' );',1000);
	}
	else
	    $(this).css('cursor','move');
	$('.Menu .IconMove, .Menu .Icons').remove();
	$(this).before('<div class="IconMove"></div>');
	$(this).append('<div class="Icons"><div class="IconDelete" onclick="fctDeleteItem('+ arrItemID[1] +')"></div><div class="IconEdit" onclick="fctOpenEdit('+ arrItemID[1] +')"></div></div>');
    });
    
    // Clear Open
    $('.Menu .Item').bind('mouseleave',function(){ 
	$('.Menu .IconMove, .Menu .Icons').remove();
	$(this).css('cursor','move');
	if( objMouseOver ) 
	    clearTimeout( objMouseOver );
    });
    
    // Add Dragging
    $('.Menu .Item').bind('mousedown',function(e) {
	if( objMouseOver ) 
	    clearTimeout( objMouseOver );
	arrID = $(this).attr('id').split('-');
	EnlID = arrID[1];
	DocID = false;
	fctStartItemDrag(e,arrID[1]);
    });
}

// Activate Menu
function fctMenuActivate( intRow,intEnlID )
{
    if( arrMenuActive[intRow] != intEnlID )
    {
	fctMenuDeactivate(intRow,function(){
	    arrMenuActive[intRow] = intEnlID;
	    $('#Item-'+intEnlID).next().addClass('Active');
	    fctSetItemPosition();
	    $('.Menu #Item-'+ intEnlID).css('cursor','move');
	});
    }
    else
	$('.Menu #Item-'+ intEnlID).css('cursor','move');
}

// Deactivate Menu 
function fctMenuDeactivate( intRow,funcObj )
{
    for( i=intRow ; i<10 ; i++ )
    {
	if( arrMenuActive[i] > 0 ) 
	    $('#Item-'+arrMenuActive[i]).next().removeClass('Active');
	arrMenuActive[i] = 0;
    }
    
    if( funcObj ) funcObj.apply();
}

// Set Menu-Positions
function fctSetItemPosition()
{
    
    $('.Menu ul.Ebene-0').css('left','20px');
    var intLvl = 1;
    for( intLvL=1 ; intLvl<maxLvl ; intLvl++ )
    {
	if( $('.Menu ul.Ebene-'+ intLvl +'.Active > li').length > 0 )
	{
	    fctSetItemPositionDivs( intLvl );
	}
    }
}

function fctSetItemPositionDivs( intLvl )
{
    var intCenter = Math.round( $('.Menu ul.Ebene-'+ intLvl +'.Active > li:not(.DropLi)').length/2 )-1;
    
    // ActualUl-Position
    if( $('.Menu ul.Ebene-'+ intLvl +'.Active li').length > 1 )
    $('.Menu ul.Ebene-'+ intLvl +'.Active').css( 'left', '-'+((200*intCenter)+25)+'px' )
    
    // FirstUL-Position
    var offset = $('.Menu ul.Ebene-'+ intLvl +'.Active > li:first').offset();
    k=0;
    while( offset.left < 0 && k < 50 )
    {
	$('.Menu ul.Ebene-0').css('left', (parseInt($('ul.Ebene-0').css('left'))+200)+'px' );
	offset = $('.Menu ul.Ebene-'+ intLvl +'.Active > li:first').offset();
	k++;
    }
    
    fctDrawLines();
}

function fctDrawLines()
{

    var j = 0;
    var ShowLine = true;
    for( j=0 ; j<$('.Menu ul.Ebene-0.Active > li').length ; j++  )
    {
	var strLiClass = $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +')').attr('class');
	if( strLiClass )
	{
	    if( strLiClass.indexOf('DropLi') != -1 )
		ShowLine = false;
	    else
		ShowLine = true;
	}
	else
	    ShowLine = true;
	
	if( ShowLine == true )
	{
	    $('.Menu ul.Ebene-0.Active > li:eq('+ j +') > canvas').remove();
	    if( $('.Menu ul.Ebene-0.Active > li:eq('+ j +') ul.Active').length > 0 )
	    {
		$('.Menu ul.Ebene-0.Active > li:eq('+ j +')').append('<canvas width="200" height="300" />');
		/*
		$('.Menu ul.Ebene-0.Active > li:eq('+ j +') > canvas').drawLine({
		    strokeStyle: "#fff",
		    strokeWidth: 2,
		    x1: 87, y1: 215,
		    x2: 87, y2: 265
		});
		*/
		
		$('.Menu ul.Ebene-0.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		var canvasNode = document.getElementById("canvasnode").getContext("2d");
		canvasNode.moveTo(87, 215);
		canvasNode.lineTo(87, 265);
		canvasNode.lineWidth = 2;
		canvasNode.strokeStyle = "#ffffff";
		canvasNode.stroke();
		$('.Menu ul.Ebene-0.Active > li:eq('+ j +') > canvas').attr('id');
	    }
	}
    }
    var intLvl = 1;
    var l = 0;
    var j = 0;
    for( intLvl=1 ; intLvl<maxLvl ; intLvl++ )
    {
	var intCenter = Math.round( $('.Menu ul.Ebene-'+ intLvl +'.Active > li:not(.DropLi)').length/2 )-1;
	for( j=0,l=0 ; j<$('.Menu ul.Ebene-'+ intLvl +'.Active > li').length ; j++ )
	{
	    var strLiClass = $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +')').attr('class');
	    if( strLiClass )
	    {
		if( strLiClass.indexOf('DropLi') != -1 )
		    ShowLine = false;
		else
		    ShowLine = true;
	    }
	    else
		ShowLine = true;
		
	    if( ShowLine == true )
	    {
		$('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').remove();
		$('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +')').append('<canvas width="200" height="300" />');

		boolDoNothing = true;
		/*
		$('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').drawLine({
		    strokeStyle: "#fff",
		    strokeWidth: 2,
		    x1: 87, y1: 50,
		    x2: 87, y2: 100
		});
		*/
		$('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		var canvasNode = document.getElementById("canvasnode").getContext("2d");
		canvasNode.moveTo(87, 50);
		canvasNode.lineTo(87, 100);
		canvasNode.lineWidth = 2;
		canvasNode.strokeStyle = "#ffffff";
		canvasNode.stroke();
		$('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','');
		

		// If only 1
		if( $('.Menu ul.Ebene-'+ intLvl +'.Active > li:not(.DropLi)').length == 1 )
		{
		    /*
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').drawLine({
			strokeStyle: "#fff",
			strokeWidth: 2,
			x1: 87, y1: 50,
			x2: 87, y2: 100
		    }); 
		    */
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		    var canvasNode = document.getElementById("canvasnode").getContext("2d");
		    canvasNode.moveTo(87, 50);
		    canvasNode.lineTo(87, 100);
		    canvasNode.lineWidth = 2;
		    canvasNode.strokeStyle = "#ffffff";
		    canvasNode.stroke();
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','');
		}

		// Left
		else if( l < intCenter && l > 0 )
		{
		    /*
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').drawLine({
			strokeStyle: "#fff",
			strokeWidth: 2,
			x1: 0, y1: 50,
			x2: 200, y2: 50
		    });
		    */
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		    var canvasNode = document.getElementById("canvasnode").getContext("2d");
		    canvasNode.moveTo(0, 50);
		    canvasNode.lineTo(200, 50);
		    canvasNode.lineWidth = 2;
		    canvasNode.strokeStyle = "#ffffff";
		    canvasNode.stroke();
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','');
		}
		
		// Left
		else if( l < intCenter )
		{
		    /*
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').drawLine({
			strokeStyle: "#fff",
			strokeWidth: 2,
			x1: 87, y1: 50,
			x2: 200, y2: 50
		    });
		    */
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		    var canvasNode = document.getElementById("canvasnode").getContext("2d");
		    canvasNode.moveTo(87, 50);
		    canvasNode.lineTo(200, 50);
		    canvasNode.lineWidth = 2;
		    canvasNode.strokeStyle = "#ffffff";
		    canvasNode.stroke();
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','');
		}


		// Left == Center
		else if( l == 0 && intCenter == l )
		{
		    /*
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').drawLine({
			strokeStyle: "#fff",
			strokeWidth: 2,
			x1: 87, y1: 50,
			x2: 200, y2: 50
		    });
		    */
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		    var canvasNode = document.getElementById("canvasnode").getContext("2d");
		    canvasNode.moveTo(87, 50);
		    canvasNode.lineTo(200, 50);
		    canvasNode.lineWidth = 2;
		    canvasNode.strokeStyle = "#ffffff";
		    canvasNode.stroke();
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','');
		}

		// Center
		else if( l == intCenter )
		{
		    /*
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').drawLine({
			strokeStyle: "#fff",
			strokeWidth: 2,
			x1: 0, y1: 50,
			x2: 200, y2: 50
		    });
		    */
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		    var canvasNode = document.getElementById("canvasnode").getContext("2d");
		    canvasNode.moveTo(0, 50);
		    canvasNode.lineTo(200, 50);
		    canvasNode.lineWidth = 2;
		    canvasNode.strokeStyle = "#ffffff";
		    canvasNode.stroke();
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','');
		}

		    
		// Right and not Last
		else if( l > intCenter && l < ($('.Menu ul.Ebene-'+ intLvl +'.Active > li:not(.DropLi)').length-1) )
		{
		    /*
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').drawLine({
			strokeStyle: "#fff",
			strokeWidth: 2,
			x1: 87, y1: 50,
			x2: 0, y2: 50
		    }).drawLine({
			strokeStyle: "#fff",
			strokeWidth: 2,
			x1: 87, y1: 50,
			x2: 200, y2: 50
		    });
		    */
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		    var canvasNode = document.getElementById("canvasnode").getContext("2d");
		    canvasNode.moveTo(87, 50);
		    canvasNode.lineTo(0, 50);
		    canvasNode.lineWidth = 2;
		    canvasNode.strokeStyle = "#ffffff";
		    canvasNode.stroke();
		    canvasNode.moveTo(87, 50);
		    canvasNode.lineTo(200, 50);
		    canvasNode.stroke();
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','');
		}


		// Right
		else if( l > intCenter )
		{
		    /*
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').drawLine({
			strokeStyle: "#fff",
			strokeWidth: 2,
			x1: 87, y1: 50,
			x2: 0, y2: 50
		    });
		    */
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		    var canvasNode = document.getElementById("canvasnode").getContext("2d");
		    canvasNode.moveTo(87, 50);
		    canvasNode.lineTo(0, 50);
		    canvasNode.lineWidth = 2;
		    canvasNode.strokeStyle = "#ffffff";
		    canvasNode.stroke();
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','');
		}

		
		// Line to Child-Ul
		if( $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') ul.Active').length > 0 )
		{
		    
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','canvasnode');
		    var canvasNode = document.getElementById("canvasnode").getContext("2d");
		    canvasNode.moveTo(87, 215);
		    canvasNode.lineTo(87, 265);
		    canvasNode.lineWidth = 2;
		    canvasNode.strokeStyle = "#ffffff";
		    canvasNode.stroke();
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').attr('id','');
		    /*
		    $('.Menu ul.Ebene-'+ intLvl +'.Active > li:eq('+ j +') > canvas').drawLine({
			strokeStyle: "#fff",
			strokeWidth: 2,
			x1: 87, y1: 215,
			x2: 87, y2: 265
		    });
		    */
		}
		
		l++;
	    }
	}
    }
}

function fctGetLvlClass( strClass )
{
    intPos = strClass.indexOf('Ebene-');
    if( intPos != -1 )
    {
	strLvl = strClass.slice(intPos,99);
	intSpacePos = strClass.indexOf(' ');
	if( intSpacePos != -1 )
	{
	    arrClasses = strLvl.split(' ');
	    arrClass = arrClasses[0].split('-');
	    return arrClass[1];
	}
	else
	{
	    arrClass = strLvl.split('-');
	    return arrClass[1];
	}
    }
}

function fctStartItemDrag( e,intEnlID )
{
    // Add new Dragger
    $('.Menu .Drag').draggable('destroy').remove();
    $('.Menu').append('<div class="Drag"></div>');
    $('.Menu .Drag').draggable({
	cursor: 'move',
	opacity: 0.30,
	cursorAt: { left: 86, top: 58},
	distance: 15,
	refreshPositions: true,
	start: function(){
	    
	    $('.Menu .IconMove, .Menu .Icons').remove();
	    
	    // Copy Item-Content to Dragger
	    $('.Menu .Drag').append( '<div class="Item">'+ $('#Item-'+ intEnlID).html() +'</div>');
	    
	    // Hide Item
	    $('#Item-'+ intEnlID).hide();
	    $('#Item-'+ intEnlID).next().hide();
	    $('#Item-'+ intEnlID).next().next().hide();
	    $('#Item-'+ intEnlID).before('<div class="ItemBox"></div>');
	    
	    // HideNextToItem
	    $('#Item-'+ intEnlID).parent().next().addClass('NotDropLi');
	    $('#Item-'+ intEnlID).parent().prev().addClass('NotDropLi');
	    
	    if( $('#Item-'+intEnlID).parent().parent().attr('class') )
	    {
		intLvlId = fctGetLvlClass( $('#Item-'+intEnlID).parent().parent().attr('class'));
		fctMenuDeactivate(intLvlId);  
	    }
	    
	    fctStartItemDrop();
	    fctDrawLines();
	},
	stop: function(){
	    // Remove-Dragger
	    $('.Menu .Drag').draggable('destroy');
	    $('.Menu .Drag').remove();
	    
	    // Show Item
	    $('#Item-'+ intEnlID).show();
	    $('#Item-'+ intEnlID).next().show();
	    $('#Item-'+ intEnlID).next().next().show();
	    $('.ItemBox,.ItemBox2').remove();
	    $('.NotDropLi').removeClass('NotDropLi');
	    
	    // Remove Under
	    $('#Under').parent().remove();
	    
	    // Redraw Lines
	    fctDrawLines();
	    
	    // Stop Drop
	    fctEndItemDrop();
	}
    });
    fctSetItemPosition();
    $('.Menu .Drag').trigger(e);
    
}

// Start Drop
function fctStartItemDrop()
{    
    $('.DropLi:not(.NotDropLi)').droppable({
	    tolerance: 'pointer',
	    over: function() {
		
		// Remove Under
		$('#Under').parent().remove();
		
		// Animation
		$(this).removeClass('DropLi');
		$(this).html( '<div class="ItemBox2"></div>' );
		$(this).css('width','175px');
		$(this).find('.ItemBox2').css('marginLeft','87px').css('marginTop','57px').css('width','1px').css('height','1px').animate({
		    width: 147,
		    height: 107,
		    marginTop: 0,
		    marginLeft: 10
		},200);

		// Redraw Lines
		fctDrawLines();
		
	    },
	    out: function() {
		// Reset Animation
		$(this).css('width','25px');
		$(this).html('');
		$(this).addClass('DropLi');
		
		// Redraw Lines
		fctDrawLines();
	    },
	    drop: function() {
		// Reset Animation
		$(this).css('width','25px');
		$(this).addClass('DropLi');
		$(this).html('');
		$('.ItemBox,.ItemBox2').remove();
		$('.NotDropLi').removeClass('NotDropLi');
		$(this).addClass('Loading');
				
		switch( $(this).attr('id') )
		{
		    // Insert Before
		    case 'Before': 
				    arrID = $(this).next().find('> .Item').attr('id').split('-');
				    if( DocID )
				    {
					$.ajax({
					    url: "../extension-popup.php",
					    type: "GET",
					    contentType: "application/json; charset=utf-8",
					    dataType: 'json',
					    data: ({ 
						action: 'insert',
						doc_id: DocID,
						position_id: arrID[1],
						position: 'Before'
					    }),
					    success: function( data ){
						if( data.enl_name && data.enl_id && data.position_id && data.position )
						{
						    $('#Item-'+arrID[1]).parent().before( '<li><div class="Item" id="Item-'+ data.enl_id +'"><div class="Content"><h2>'+ data.enl_name +'</h2>'+ data.content +'</div></div></li>' );
						    fctSetDrops();
						    fctSetUlLvl();
						    fctBindItems();
						    fctSetItemPosition();
						}
					    },
					    error: function(){
						alert("Error");
					    }
					});
				    }
				    else
				    {
					$.ajax({
					    url: "../extension-popup.php",
					    type: "GET",
					    contentType: "application/json; charset=utf-8",
					    dataType: 'json',
					    data: ({ 
						action: 'move',
						enl_id: EnlID,
						position_id: arrID[1],
						position: 'Before'
					    }),
					    success: function( data ){
						var strLi = $('#Item-'+ EnlID).parent().html();
						$('#Item-'+ EnlID).parent().remove();
						$('#Item-'+arrID[1]).parent().before( "<li>"+ strLi +"</li>" );
						fctSetDrops();
						fctSetUlLvl();
						fctBindItems();
						fctSetItemPosition();
					    },
					    error: function( data ){
						alert('Error');
					    }
					});
				    }
				    break;
		    // Insert After
		    case 'After': 
				    arrID = $(this).prev().find('> .Item').attr('id').split('-');
				    if( DocID )
				    {
					$.ajax({
					    url: "../extension-popup.php",
					    type: "GET",
					    contentType: "application/json; charset=utf-8",
					    dataType: 'json',
					    data: ({ 
						action: 'insert',
						doc_id: DocID,
						position_id: arrID[1],
						position: 'After'
					    }),
					    success: function( data ){
						if( data.enl_name && data.enl_id && data.position_id && data.position )
						{
						    $('#Item-'+arrID[1]).parent().after( '<li><div class="Item" id="Item-'+ data.enl_id +'"><div class="Content"><h2>'+ data.enl_name +'</h2>'+ data.content +'</div></div></li>' );
						    fctSetDrops();
						    fctSetUlLvl();
						    fctBindItems();
						    fctSetItemPosition();
						}
					    },
					    error: function(){
						alert("Error");
					    }
					});
				    }
				    else
				    {
					$.ajax({
					    url: "../extension-popup.php",
					    type: "GET",
					    contentType: "application/json; charset=utf-8",
					    dataType: 'json',
					    data: ({ 
						action: 'move',
						enl_id: EnlID,
						position_id: arrID[1],
						position: 'After'
					    }),
					    success: function( data ){
						var strLi = $('#Item-'+ EnlID).parent().html();
						$('#Item-'+ EnlID).parent().remove();
						$('#Item-'+arrID[1]).parent().after( "<li>"+ strLi +"</li>" );
						fctSetDrops();
						fctSetUlLvl();
						fctBindItems();
						fctSetItemPosition();
					    },
					    error: function( data ){
						alert('Error');
					    }
					});
				    }
				    break;
		}
		
		$('#Under').parent().remove();
		
		// Redraw Lines
		//fctDrawLines();
		

	    }
    });
    $('.Menu .Item').droppable({
	    tolerance: 'pointer',
	    over: function() {
		
		// Remove Under
		$('#Under').parent().remove();
		
		intLvlID = fctGetLvlClass( $(this).parent().parent().attr('class') );

		arrItemID = $(this).attr('id').split('-');

		if( objMouseOver2 )
		    clearTimeout( objMouseOver2 );
		
		$('.Drag').css('cursor','wait');

		objMouseOver2 = window.setTimeout(function(){
                    
		    if( $('.Menu #Item-'+arrItemID[1]).parent().find('li').length > 0 )
		    {
			fctMenuActivate( intLvlID,arrItemID[1] );
		    }
		    else
		    {
			$('.Menu #Item-'+arrItemID[1]).after('<ul class="Ebene-'+ (parseInt(intLvlID)+1) +'"><li id="Under" style="width:175px;"><div class="ItemBox2"></div></li></ul>');
			$('.Menu #Item-'+arrItemID[1]).next().find('.ItemBox2').css('marginLeft','87px').css('marginTop','57px').css('width','1px').css('height','1px').animate({
                            width: 147,
                            height: 107,
                            marginTop: 0,
                            marginLeft: 10
                        },200);
			fctMenuActivate( intLvlID,arrItemID[1] );
		    }
		},1000);
	    },
	    out: function() {
		$('.Drag').css('cursor','default');
		
		if( objMouseOver2 )
		    clearTimeout( objMouseOver2 );
		
		if( $(this).parent().find('> ul > li').length == 1 )
		{
		    intLvlID = fctGetLvlClass( $(this).parent().parent().attr('class') );
		    fctMenuDeactivate( intLvlID,false );
		}
		
		// Redraw Lines
		fctDrawLines();
		
	    },
	    drop: function() {

		if( objMouseOver2 )
		    clearTimeout( objMouseOver2 );

		if( $(this).parent().find('li').length == 1 )
		{
		    // Remove Under
		    $('#Under').parent().remove();

		    arrID = $(this).attr('id').split('-');
		    
		    if( DocID )
		    {
			$.ajax({
			    url: "../extension-popup.php",
			    type: "GET",
			    contentType: "application/json; charset=utf-8",
			    dataType: 'json',
			    data: ({ 
				action: 'insert',
				doc_id: DocID,
				position_id: arrID[1],
				position: 'Under'
			    }),
			    success: function( data ){
				if( data.enl_name && data.enl_id && data.position_id && data.position )
				{
				    $('#Item-'+arrID[1]).parent().append( '<ul class="Ebene-'+ (parseInt(intLvlID)+1) +' Active"><li><div class="Item" id="Item-'+ data.enl_id +'"><div class="Content"><h2>'+ data.enl_name +'</h2>'+ data.content +'</div></div></li></ul>' );
				    fctSetDrops();
				    fctSetUlLvl();
				    fctBindItems();
				    fctSetItemPosition();
				}
			    },
			    error: function(){
				alert("Error");
			    }
			});
		    }
		    else
		    {
		    
			$.ajax({
			    url: "../extension-popup.php",
			    type: "GET",
			    contentType: "application/json; charset=utf-8",
			    dataType: 'json',
			    data: ({ 
				action: 'move',
				enl_id: EnlID,
				position_id: arrID[1],
				position: 'Under'
			    }),
			    success: function( data ){
				var strLi = $('#Item-'+ EnlID).parent().html();
				$('#Item-'+ EnlID).parent().remove();

				intLvlID = fctGetLvlClass( $('#Item-'+arrID[1]).parent().parent().attr('class') );

				$('#Item-'+arrID[1]).parent().append( '<ul class="Ebene-'+ (parseInt(intLvlID)+1) +' Active"><li>'+ strLi +'</li></ul>' );
				fctSetDrops();
				fctSetUlLvl();
				fctBindItems();
				fctSetItemPosition();
			    },
			    error: function( data ){
				alert('Error');
			    }
			});
		    }
		}
	    }
    });
}

// End Drop
function fctEndItemDrop()
{
    $('.DropLi:not(.NotDropLi)').droppable('destroy');
    $('.Menu .Item').droppable('destroy');
}

function fctSetDrops()
{
    fctEndItemDrop();
    $('.Menu .DropLi').remove();
    $('.Menu ul').each(function(){
	$(this).find('li:first').before('<li class="DropLi" id="Before"></li>');
    });
    $('.Menu li:not(.DropLi)').after('<li class="DropLi" id="After"></li>');
    $('.Menu ul').each(function(){
	if( $(this).find('li').length == 0 )
	    $(this).remove();
    });
    
    $('.Menu .IconSub').remove();
    $('.Menu li > ul').after('<div class="IconSub"></div>');
}

// Add Ul-Lvl's'
function fctSetUlLvl()
{
    //Remove Class
    var i = 0;
    for( i=0 ; i<maxLvl ; i++ )
    {
	$('.Ebene-'+i).removeClass('Ebene-'+i);
    }
    
    // Add Class
    var strUl = " > li > ul";
    var i = 0;
    for( i=0 ; i<maxLvl ; i++ )
    {	
	var strSelect = ".Menu > ul";
	var j = 0;
	var arrClass;
	for( j=0 ;j<i ; j++ )
	    strSelect += strUl;
	
	$(strSelect).addClass('Ebene-'+j);
    }
}

function fctOpenMessage( Message,Buttons )
{
    if( $('.MessageDialog').css("display") == "none" )
    {
	$('.MessageDialog .DialogTop').html( Message );
	if( Buttons )	$('.MessageDialog .DialogFooter').html( Buttons );
	else		$('.MessageDialog .DialogFooter').html( '<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()" class="Button"><span class="ButtonText">'+ EaseEaseNavigationTxT['Ok'] +'</span></a></div><div class="ButtonRight"></div></div></div>' );
	
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

function fctOpenEdit( ItemID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'edit',
	    enl_id: ItemID
	}),
	success: function( data ){
	    if( data )
	    {
		// ID
		if( data.enl_id )
		    document.EditForm.edit_id.value = data.enl_id;
		
		// Name
		if( data.enl_name )
		    document.EditForm.edit_name.value = data.enl_name;
		
		// Document or Extern URL?
		if( data.enl_type == 0 )
		{
		    $('.EditFormURL,.EditFormTarget').css("display","none");
		    $('.EditFormDocument').css("display","block");
		    
		    // Document-Name
		    $('.EditFormDocument .content').html( data.doc_name );
		}
		else
		{
		    $('.EditFormDocument').css("display","none");
		    $('.EditFormURL').css("display","block");
		    
		    // URL
		    if( data.enl_url )
			document.EditForm.edit_url.value = data.enl_url;
		    
		    // Target
		    if( data.enl_target )
			document.EditForm.edit_target.value = data.enl_target;
		}
		
		if( $('.EditDialog').css("display") == "none" )
		{
		    $('.BlackBackground').css("opacity","0");
		    $('.BlackBackground').css("display","block");
		    $('.BlackBackground').animate({
			opacity: .8
		    },500,'linear',function(){
			$('.EditDialog').css("display","block").css("opacity","1");
			$('.BlackBackground').bind("click",function(){
			    fctCloseEdit();
			}); 
		    })

		}
	    }
	}
    });
}

function fctCloseEdit()
{
    $('.BlackBackground').css("display","none");
    $('.BlackBackground').unbind("click");
    $('.EditDialog').css("display","none");
}

function fctSaveEdit()
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	contentType: "application/json; charset=utf-8",
	dataType: 'json',
	data: ({ 
	    action: 'editsave',
	    enl_id: document.EditForm.edit_id.value,
	    enl_name: document.EditForm.edit_name.value,
	    enl_url: document.EditForm.edit_url.value,
	    enl_target: document.EditForm.edit_target.value
	}),
	success: function( data ){
	    if( !data.message )
	    {
		if( data.enl_name )
		    $('#Item-'+data.enl_id+' h2').text( data.enl_name );
		if( data.content )
		    $('#Item-'+data.enl_id.value+' .Pad').replaceWith( data.content );
		fctCloseEdit();
	    }
	    else
		alert( data.message );
	},
	error: function( data){
	    alert('Error');
	}
    });
}

function fctDeleteItem( ItemID )
{
    fctOpenMessage('<h1>'+ EaseEaseNavigationTxT['Delete1'] +'</h1><div class="Pad"><p>'+ EaseEaseNavigationTxT['Delete2'] +'</p>','<div class="Pad"><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage();fctDeleteItem2('+ ItemID +');">'+ EaseEaseNavigationTxT['Yes'] +'</a></div><div class="ButtonRight"></div></div><div class="ButtonBlack"><div class="ButtonLeft"></div><div class="ButtonCenter"><a href="javascript:;" onclick="fctCloseMessage()">'+ EaseEaseNavigationTxT['No'] +'</a></div><div class="ButtonRight"></div></div></div>');
}

function fctDeleteItem2( ItemID )
{
    $.ajax({
	url: "../extension-popup.php",
	type: "GET",
	data: ({ 
	    action: 'delete',
	    id: ItemID
	}),
	success: function(){
	    $('#Item-'+ItemID).parent().remove();
	    fctSetDrops();
	    fctSetUlLvl();
	    fctBindItems();
	    fctSetItemPosition();
	},
	error: function(){
	    $('.MessageDialog .DialogTop').html( '<h1>Error</h1><div class="Pad"><p style="color:#f00;">'+ EaseEaseNavigationTxT['Delete2'] +'</p></div>' );
	}
    });
}