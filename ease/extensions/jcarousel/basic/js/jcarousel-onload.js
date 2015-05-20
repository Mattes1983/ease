var newJCarouselHTML;
var newJCarouselA;
var newJCarouselS;
var newJCarouselV;
var newJCarouselW;
var newJCarouselP;
var newJCarouselIS;

function fctEaseJCarouselEditStart( id )
{
    $('#JCarouselEdit-'+id).css("display","block");
    $('#JCarouselNotEdit-'+id).css("display","none").html('');
}

function fctJCCheckIfSend()
{
    if( newJCarouselP == 9 )
    {
	window.clearInterval( jcCheckifsend );
	top.fctEaseReload();
    }
}

function fctEaseInitCallback(carousel) {
    jQuery('.jcarousel-control a').bind('click', function() {
        carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
        return false;
    });
}

function fctEaseJCarouselEditEnd( id )
{
    
    // Save
    $.ajax({
	url: '../action.php',
	type: "POST",
	data: ({ 
	    action: 'save_content_request',
	    lin_id: id,
	    'name[]': ['swidth','sheight','iwidth','iheight','ispace','type','wrap','scroll','autoscroll'],
	    'value[]': [
		document.forms['JCarouselEditForm-'+id].JCsliderwidth.value,
		document.forms['JCarouselEditForm-'+id].JCsliderheight.value,
		document.forms['JCarouselEditForm-'+id].JCitemwidth.value,
		document.forms['JCarouselEditForm-'+id].JCitemheight.value,
		document.forms['JCarouselEditForm-'+id].JCitemspace.value,
		document.forms['JCarouselEditForm-'+id].JCtype.options[document.forms['JCarouselEditForm-'+id].JCtype.selectedIndex].value,
		document.forms['JCarouselEditForm-'+id].JCwrap.options[document.forms['JCarouselEditForm-'+id].JCwrap.selectedIndex].value,
		document.forms['JCarouselEditForm-'+id].JCscroll.value,
		document.forms['JCarouselEditForm-'+id].JCautoscroll.options[document.forms['JCarouselEditForm-'+id].JCautoscroll.selectedIndex].value
	    ]
	}),
	success: function() {
	    
	    top.fctEaseReload();
	    
	    /*
	    $.ajax({
		url: '../action.php',
		type: "GET",
		data: ({ 
		    action: 'get_link_request',
		    lin_id: id
		}),
		contentType: "application/json; charset=utf-8",
		dataType: 'json',
		success: function( data ){
		    
		    //newJCarouselHTML = '<div class="jcarousel-control"><a href="#">1</a><a href="#">2</a><a href="#">3</a></div>';
		    
		    if( data.image )
		    {
			newJCarouselHTML = '<ul id="mycarousel'+ id +'" class="jcarousel-skin-ease">';
			for( i=0 ; i<data.image.length ; i++ )
			    newJCarouselHTML += "<li>"+data.image[i]+"</li>";
			newJCarouselHTML += "</ul>";
		    }
		    else
		    {
			newJCarouselHTML = '<ul id="mycarousel'+ id +'" class="jcarousel-skin-ease">';
			newJCarouselHTML += "<li><div><img src='../extensions/jcarousel/images/vorschau.gif' alt=\"\" /></div></li>";
			newJCarouselHTML += "</ul>";
		    }

		    if( document.forms['JCarouselEditForm-'+id].JCtype.options[document.forms['JCarouselEditForm-'+id].JCtype.selectedIndex].value == "vertical" )
			newJCarouselV = true;
		    else
			newJCarouselV = false;
		    if( document.forms['JCarouselEditForm-'+id].JCwrap.options[document.forms['JCarouselEditForm-'+id].JCwrap.selectedIndex].value == "nowrap" )
			newJCarouselW = null;
		    else
			newJCarouselW = 'circular';
		    if( document.forms['JCarouselEditForm-'+id].JCautoscroll.options[document.forms['JCarouselEditForm-'+id].JCautoscroll.selectedIndex].value == "1" )
			newJCarouselA = 0;
		    else
			newJCarouselA = 5;

		    newJCarouselS = parseInt( document.forms['JCarouselEditForm-'+id].JCscroll.value );
		    if ( newJCarouselS > 10 ) newJCarouselS = 10;

		    newJCarouselIS = parseInt( document.forms['JCarouselEditForm-'+id].JCitemspace.value );

		    $('#JCarouselNotEdit-'+ id +' .jcarousel-item-horizontal').css("margin-right",newJCarouselIS+"px");
		    $('#JCarouselNotEdit-'+ id +' .jcarousel-item-vertical').css("margin-bottom",newJCarouselIS+"px");

		    $('#JCarouselEdit-'+id).css("display","none");
		    $('#JCarouselNotEdit-'+id).css("display","block").html(newJCarouselHTML);
		    $('#mycarousel'+ id).jcarousel({
			//initCallback: fctEaseInitCallback,
			wrap: newJCarouselW,
			scroll: newJCarouselS,
			vertical: newJCarouselV,
			auto: newJCarouselA
		    });
		    window.top.fctGlobalLoadingEnd();
		}
	    });
	    */
	}
    });    
}