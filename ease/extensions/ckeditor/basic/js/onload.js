CKEDITOR.disableAutoInline = true;

function fctEaseCKEditorEditStart( id, strStyleSet, strStyleSetSrc, strToolbar )
{        
    $('#easeCKEditor'+ id).attr('contenteditable',true);
    
    // Attributes
    var strAttribut = '';
    
    // StylesSet
    if( strStyleSet && strStyleSetSrc )
    {
        if( strAttribut ) strAttribut += ", ";
        strAttribut += "stylesSet : '"+ strStyleSet +":../../"+ strStyleSetSrc +"'";
    }
    
    // Toolbar
    if( strToolbar )
    {
        if( strAttribut ) strAttribut += ", ";
        strAttribut += "toolbar : [ { name: 'basicstyles', items: ["+ strToolbar +"] } ]";
        
    }
    
    if( strAttribut )
        eval("easeCKE"+ id +" = CKEDITOR.inline( document.getElementById( 'easeCKEditor"+ id +"' ), { "+ strAttribut +" } );");   
    else
        eval("easeCKE"+ id +" = CKEDITOR.inline( document.getElementById( 'easeCKEditor"+ id +"' ), { stylesSet : 'ease:../styleset/default.js' } );");   
}

function fctEaseCKEditorEditEnd( id )
{    
    var postData = false;

    eval("if( easeCKE"+ id +" ) postData = true;")

    if( postData == true )
    {
	eval("EditorContent = easeCKE"+ id +".getData();");
	EditURL = $('#easeCKEditor'+id).attr("rel");

	$.ajax({
	    url: EditURL,
	    type: "POST",
	    data: ({ name: 'text',value: EditorContent }),
	    success: function( data ){
		window.top.fctGlobalLoadingEnd(function(){
		    if( data ) alert("Error: "+data);
		});
	    }
	});
	eval("easeCKE"+ id +".destroy();");
    }
    $('#easeCKEditor'+id).attr('contenteditable',false);
}

// Browse-Function
CKEDITOR.on( 'dialogDefinition', function( ev )
{
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;

    if ( dialogName == 'link' )
    {
	var infoTab = dialogDefinition.getContents( 'info' );
	infoTab.add( {
		type : 'html',
		html: '<a href="javascript:;" onclick="top.fctEaseOpenExtensionPopup( \'11\',\'\',\'\',\'\',\'0\',\'0\' );" role="button" class="cke_dialog_ui_button"><span class="cke_dialog_ui_button">'+ EaseCKEditorTxT['Browse'] +'</span></a>'
	});
	
	dialogDefinition.removeContents( 'advanced' )
    }
});