/**
 * Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
        
	config.toolbar =
	[
            { name: 'styles',      items : [ 'Styles','RemoveFormat' ] },
	    { name: 'tools',       items : [ 'ShowBlocks','-','About' ] },
	    { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	    { name: 'links',       items : [ 'Link','Unlink','Anchor','HorizontalRule' ] },
	    { name: 'paragraph',   items : [ 'NumberedList','BulletedList','JustifyLeft','JustifyCenter','JustifyRight'] },
	    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike' ] },
	    { name: 'document', items : [ 'Source'] }
	];
        config.stylesSet = 'ease';
};

CKEDITOR.disableAutoInline = true;