<?php

    class CKEditorBasic extends _Extensions
    {
	
	public function __construct()
	{
	    parent::__construct();
	    
            // Basic
	    $this->arrConf['defaulttext'] = "basic/text/defaulttext.html";
            
            // Templates
            $this->arrConf['tmpl']['editor-edit'] = "basic/templates/editor-edit.tmpl";
            $this->arrConf['tmpl']['editor'] = "basic/templates/editor.tmpl";
            $this->arrConf['tmpl']['extension-popup'] = "basic/templates/extension-popup.tmpl";
            $this->arrConf['tmpl']['extension-popup-message'] = "basic/templates/extension-popup-message.tmpl";
            $this->arrConf['tmpl']['extension-popup-upload'] = "basic/templates/extension-popup-upload.tmpl";
            $this->arrConf['tmpl']['extension-popup-preview'] = "basic/templates/extension-popup-preview.tmpl";

            // JavaScript
            $this->arrConf['js']['editor'] = "basic/ckeditor/ckeditor.js";
            $this->arrConf['js']['editoronload'] = "basic/js/onload.js";
            $this->arrConf['js']['functions'] = "basic/js/functions.js";
            
            // CSS
            $this->arrConf['css']['extension-popup'] = "basic/css/extension-popup.css";
	}
	
	public function fctRenderCode( $arrParams=array() )
	{
	    
            if( $arrParams['ParentAttributes']['defaulttext'] )
            {
                $this->arrConf['defaulttext'] = $arrParams['ParentAttributes']['defaulttext'];
            }
            
	    
            if( $arrParams['ParentAttributes']['text'] )
                $strEditorContent = $arrParams['ParentAttributes']['text'];
            else
                $strEditorContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['defaulttext'] );


	    // If Drag-Mode
	    if( $this->EaseVars['dragmode'] == true )
	    {
		return $strEditorContent;
	    }
	    
	    if( $this->EaseVars['generatemode'] == false ) 
		$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['editor-edit'] );
	    else
		$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['editor'] );
	    
	    if( $this->EaseVars['generatemode'] == false )
	    {
		$this->arrDocument['js_include']['ckeditor'] = $this->arrConf['js']['editor'];
		$this->arrDocument['js_include']['ckeditoronload'] = $this->arrConf['js']['editoronload'];
		$this->arrDocument['head']['ckeditor'] = '<!--[if IE 9]><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" ><![endif]-->';
	    }

	    // Get Data
		// Extension-ID
		$this->fctQuery( "SELECT ext_id FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_name = 'CKEditor'" );
		$intExtID = $this->arrSQL[0]['ext_id'];

		// Get
		$strEditorContent = _Content::fctGetContent( $arrParams['Link']['lin_id'],'text' );
		if( !$strEditorContent )
                {
                    if( $arrParams['ParentAttributes']['text'] )
                        $strEditorContent = $arrParams['ParentAttributes']['text'];
                    else
                        $strEditorContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['defaulttext'] );
                }

	    // Replace Data
	    if( $this->EaseVars['generatemode'] == false )
	    {
		$strContent = _ParseDoc::fctTagReplace( "rel", $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/action.php?action=save_content_request&lin_id=".$arrParams['Link']['lin_id']."&name=". $arrParams['Name'] ,$strContent );
		$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
	    }
	    else
	    {
		$strContent = _ParseDoc::fctTagReplace( "rel", '' ,$strContent );
		$strContent = _ParseDoc::fctTagReplace( "id", '' ,$strContent );
	    }
	    $strContent = _ParseDoc::fctTagReplace( "content", $strEditorContent ,$strContent );
	    
	    // JavaScript-Function for Edit
	    $this->ExtensionVars['JSEditStart'] = "fctEaseCKEditorEditStart(". $arrParams['Link']['lin_id'] .",'". $arrParams['ParentAttributes']['styleset'] ."','". $arrParams['ParentAttributes']['stylesetsrc'] ."','". addslashes( $arrParams['ParentAttributes']['toolbar'] ) ."')";
	    $this->ExtensionVars['JSEditEnd'] = "fctEaseCKEditorEditEnd(". $arrParams['Link']['lin_id'] .")";
	    
	    // Language
	    $this->arrDocument['js_language']['CKEditorLangTxt'] = $this->fctSetJSLangTxT( 'CKEditor' );
	    
	    return $strContent;
	}
	
	// Optional
	public function fctExtensionPopup( $intLinID,$arrParams=array() )
	{
	    switch( $_GET['action'] )
	    {
		case "documents":   $this->fctGetDocuments(); break;
		case "files":	    $this->fctGetFiles(); break;
		case "delete_file": $this->fctDeleteFile(); break;
		case "upload":	    $this->fctUploadFile(); break;
		case "savelink":    $this->fctSaveLinkBrowser(); break;
		case "type_change": $_SESSION['easevars']['ckeditor']['viewtype'] = $_GET['type']; break;
		default:	    $this->fctGetLinkBrowser(); break;
	    }
	}
	
	public function fctGetLinkBrowser()
	{
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup'] );
	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
    	    $this->arrDocument['js_include']['functions'] = $this->arrConf['js']['functions'];
	    
	    if( $_SESSION['easevars']['ckeditor']['search'] )
		$strContent = _ParseDoc::fctTagReplace( "searchvalue", $_SESSION['easevars']['ckeditor']['search'] ,$strContent );
	    else
		$strContent = _ParseDoc::fctTagReplace( "searchvalue", '{Search}' ,$strContent );
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "upload", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-upload'] ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "preview", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-preview'] ) ,$strContent );
	    	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='top.fctEaseCloseExtensionPopup()'>{Cancel}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    $this->arrDocument['js_language']['EaseCKEditorLangTxt'] = $this->fctSetJSLangTxT( 'CKEditor' );
	    $strContent = $this->fctReplaceLang( $strContent );
	    
	    $this->arrDocument['body'][]  = $strContent;
	    
	}
	
	public function fctSaveLinkBrowser()
	{
	    
	}
	
	public function fctGetDocuments()
	{
	    // GET
	    if( $_GET['start'] ) $intStart = $_GET['start'];
	    else		 $intStart = 0;
	    if( $_GET['search'] == $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']]['Search'] )
		unset( $_SESSION['easevars']['ckeditor']['search'] );
	    else
		$_SESSION['easevars']['ckeditor']['search'] = $_GET['search'];
	    
	    $arrDocuments = _Search::fctDocumentSearch( $_SESSION['easevars']['ckeditor']['search'] );
	    
	    if( count( $arrDocuments ) )
	    {
		foreach( $arrDocuments as $arrDoc )
		{
		    $arrDocURL = _Documents::fctGetDocURL( $arrDoc['doc_id'] );
		    $strDocs .= "<div class='Document'>";
			$strDocs .= "<div class='ButtonView' onclick='fctOpenPreview(\"". _Documents::fctGetGenerateURL( $arrDoc['doc_id'] ) ."\")'></div>";
			$strDocs .= "<div class='ButtonUse' onclick='fctSetLinkURL(\"". $arrDocURL['url'] ."\")'></div>";
			if( $arrDoc['doc_title'] )
			    $strDocs .= "<h1>". $this->fctSetLength( $arrDoc['doc_title'], 20 ) ."</h1>";
			else
			    $strDocs .= "<h1>{NoTitle}</h1>";
			$strDocs .= "<div class='Pad'>";
			    $strDocs .= "<p><b>{Author}:</b><br />". _USER::fctGetUserName( $arrDoc['doc_create_use_id'] ) .", ". _GlobalFunctions::fctFormatMysqlDate( $arrDoc['doc_create_date'] ) ."</p>";
			    // Last Change
			    if( $arrDoc['doc_changed_use_id'] != 0 ) $strDocs .= "<p><b>{LastChange}:</b><br />". _USER::fctGetUserName( $arrDoc['doc_changed_use_id'] ) .", ". _GlobalFunctions::fctFormatMysqlDate( $arrDoc['doc_changed_date'] ) ."</p>";
			    if( $arrDoc['doc_meta_description'] ) $strDocs .= "<p><b>{Description}:</b><br />". $this->fctSetLength( $arrDoc['doc_meta_description'],100 ) ."</p>";
			    if( $arrDoc['doc_first_text'] ) $strDocs .= "<p><b>{Content}:</b><br />". $this->fctSetLength( $arrDoc['doc_first_text'],100 ) ."</p>";
			$strDocs .= "</div>";
		    $strDocs .= "</div>";
		}
		$strDocs = $this->fctReplaceLang( $strDocs );
		echo $strDocs;
	    }
	    exit;
	}
	
	public function fctGetFiles()
	{
	    // GET
	    if( $_GET['start'] ) $intStart = $_GET['start'];
	    else		 $intStart = 0;
	    if( $_GET['search'] == $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']]['Search'] )
		unset( $_SESSION['easevars']['ckeditor']['search'] );
	    else
		$_SESSION['easevars']['ckeditor']['search'] = $_GET['search'];
	    
	    $arrFiles = _Search::fctFileSearch( $_SESSION['easevars']['ckeditor']['search'] );
	    
	    if( count( $arrFiles ) )
	    {
		foreach( $arrFiles as $arrFiles )
		{
		    $arrFileURL = _Files::fctGetFileURL( $arrFiles['fil_id'] );
		    $strFiles .= "<div class='Files' id='File-". $arrFiles['fil_id'] ."'>";
			$strFiles .= "<div class='ButtonView' onclick='window.open(\"". $arrFileURL['url'] ."\")'></div>";
			$strFiles .= "<div class='ButtonUse' onclick='fctSetLinkURL(\"". $arrFileURL['url'] ."\")'></div>";
			$strFiles .= "<div class='ButtonDelete' onclick='fctDeleteLink(\"". $arrFiles['fil_id'] ."\")'></div>";
			if( $arrFiles['fil_title'] )
			    $strFiles .= "<h1>". $this->fctSetLength( $arrFiles['fil_title'], 20 ) ."</h1>";
			else
			    $strFiles .= "<h1>{NoTitle}</h1>";
			$strFiles .= "<div class='Pad'>";
			    $strFiles .= "<p><b>{Name}:</b><br />". $arrFiles['fil_name'] . $arrFiles['fil_suffix'] ."</p>";
			    $strFiles .= "<p><b>{Author}:</b><br />". _USER::fctGetUserName( $arrFiles['fil_create_use_id'] ) .", ". _GlobalFunctions::fctFormatMysqlDate( $arrFiles['fil_create_date'] ) ."</p>";
			$strFiles .= "</div>";
		    $strFiles .= "</div>";
		}
		$strFiles = $this->fctReplaceLang( $strFiles );
		echo $strFiles;
	    }
	    exit;
	}
	
	public function fctDeleteFile()
	{
	    _Files::fctDeleteFile( $_GET['id'] );
	    exit;
	}
	
	public function fctUploadFile()
	{
	    unset( $_SESSION['easevars']['ckeditor']['search'] );
	    $_File = new _FILES;
            for( $i=1;$i<100;$i++ )
            {                
                if( $_FILES['file'.$i] )
                    $_File->fctUploadFile( 'file'.$i,array() );
                else
                    $i = 100;
            }
	    $this->fctURLRedirect( $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/extension-popup.php" );
	}
    }
?>