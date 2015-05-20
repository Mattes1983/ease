<?php

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

    class EASELinkBasic extends _Extensions
    {
	
	public function __construct()
	{
	    parent::__construct();
            
            // Templates
            $this->arrConf['tmpl']['link'] = "basic/templates/link.tmpl";
            $this->arrConf['tmpl']['link-edit'] = "basic/templates/link-edit.tmpl";
            $this->arrConf['tmpl']['extension-popup'] = "basic/templates/extension-popup.tmpl";
            $this->arrConf['tmpl']['extension-popup-message'] = "basic/templates/extension-popup-message.tmpl";
            $this->arrConf['tmpl']['extension-popup-upload'] = "basic/templates/extension-popup-upload.tmpl";
            $this->arrConf['tmpl']['extension-popup-preview'] = "basic/templates/extension-popup-preview.tmpl";
            
            // CSS
            $this->arrConf['css']['link'] = "basic/css/link.css";
            $this->arrConf['css']['link-edit'] = "basic/css/link-edit.css";
            $this->arrConf['css']['extension-popup'] = "basic/css/extension-popup.css";
            
            // JS
            $this->arrConf['js']['onload'] = "basic/js/onload.js";
            $this->arrConf['js']['functions'] = "basic/js/functions.js";
	}
	
	public function fctRenderCode( $arrParams=array() )
	{
	    
	    // Ask if in Drag&Drop-Mode
	    if( $this->EaseVars['dragmode'] == true )
	    {
		return '<ease:preview extension="EASEImage" drop="false" param_name="type" param_value="preview" />';
	    }
	    
	    // Template
	    if( $this->EaseVars['generatemode'] == false )
		$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['link-edit'] );
	    else
		$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['link'] );

	    $this->arrDocument['css']['easelink'] =  self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['link'] );
	    if( $this->EaseVars['generatemode'] == false ) $this->arrDocument['css']['easelinkedit'] =  self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['link-edit'] );
	    if( $this->EaseVars['generatemode'] == false ) $this->arrDocument['js_include']['easelink'] = $this->arrConf['js']['onload'];

	    $strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
            
	    // Target
	    $strURL = _ParseDoc::fctGetExtensionLink( array( 'lin_id'=>$arrParams['Link']['lin_id'] ) );
	    $strContent = _ParseDoc::fctTagReplace( "target", $strURL['url'] ,$strContent );

	    // Image	    
	    $arrValues = _Content::fctGetContentArray( $arrParams['Link']['lin_id'] );

	    switch( $arrValues['type'] )
	    {
		case '1': 
		    $arrURL = _Documents::fctGetDocURL( $arrValues['doc'] );
		    $strLink1 = '<a href="javascript:;" onclick="'. $arrURL['js'] .'">';
		    $strLink2 = '</a>';
		    break;
		case '2': 
		    $arrURL = _Files::fctGetFileURL( $arrValues['file'] );
		    $strLink1 = '<a href="'. $arrURL['url'] .'">';
		    $strLink2 = '</a>';
		    break;
	    }
            
	    $_PD = new _ParseDoc();
	    $_PD->boolNoEditmode = true;
	    $_PD->boolReturnContentArray = true;
            $_PD->EaseVars = $this->EaseVars;
	    $arrContent = $_PD->fctRenderLinks( $arrParams['Link']['lin_doc_id'],$arrParams['Link']['lin_id'],array( "imageedit" => array( "name"=>"imageedit","attribute"=>array())));

	    // Replace Image
	    if( count( $arrContent['imageedit'] ) )
		$strContent = _ParseDoc::fctTagReplace( "image", $strLink1.$arrContent['imageedit'][0].$strLink2 ,$strContent );
	    else
		$strContent = _ParseDoc::fctTagReplace( "image", '<ease:preview extension="EASEImage" drop="false" param_name="type" param_value="preview" />' ,$strContent );


	    // JavaScript-Function for Edit
	    $this->ExtensionVars['JSEditStart'] = "fctEaseLinkEditStart(". $arrParams['Link']['lin_id'] .")";
	    $this->ExtensionVars['JSEditEnd'] = "fctEaseLinkEditEnd(". $arrParams['Link']['lin_id'] .")";
	    
	    // Language
	    $this->arrDocument['js_language']['LinkLangTxt'] = $this->fctSetJSLangTxT( 'Link' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    return $strContent;
	}
	
	// Optional
	public function fctDeleteItem( $intLinkID, $arrParams=array() )
	{
	    
	}
	
	// Optional
	public function fctExtensionPopup( $intLinID,$arrParams=array() )
	{
	    switch( $_GET['action'] )
	    {
		case "documents":   $this->fctGetDocuments( $intLinID ); break;
		case "files":	    $this->fctGetFiles( $intLinID ); break;
		case "delete_file": $this->fctDeleteFile(); break;
		case "upload":	    $this->fctUploadFile(); break;
		case "savelink":    $this->fctSaveLinkBrowser(); break;
		case "type_change": $_SESSION['easevars']['easelink']['viewtype'] = $_GET['type']; break;
		default:	    $this->fctGetLinkBrowser(); break;
	    }
	}
	
	public function fctGetLinkBrowser()
	{
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup'] );
	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
    	    $this->arrDocument['js_include']['ckeditoronload'] = $this->arrConf['js']['functions'];
	    
	    if( $_SESSION['easevars']['easelink']['search'] )
		$strContent = _ParseDoc::fctTagReplace( "searchvalue", $_SESSION['easevars']['easelink']['search'] ,$strContent );
	    else
		$strContent = _ParseDoc::fctTagReplace( "searchvalue", '{Search}' ,$strContent );
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "upload", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-upload'] ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "preview", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-preview'] ) ,$strContent );
	    	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='top.fctEaseCloseExtensionPopup()'>{Cancel}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    $this->arrDocument['js_language']['EaseLinkLangTxt'] = $this->fctSetJSLangTxT( 'Link' );
	    $strContent = $this->fctReplaceLang( $strContent );
	    
	    $this->arrDocument['body'][]  = $strContent;
	    
	}
	
	public function fctSaveLinkBrowser()
	{
	    
	}
	
	public function fctGetDocuments( $intLinID )
	{
	    // GET
	    if( $_GET['start'] ) $intStart = $_GET['start'];
	    else		 $intStart = 0;
	    if( $_GET['search'] == $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']]['Search'] )
		unset( $_SESSION['easevars']['easelink']['search'] );
	    else
		$_SESSION['easevars']['easelink']['search'] = $_GET['search'];
	    
	    $arrDocuments = _Search::fctDocumentSearch( $_SESSION['easevars']['easelink']['search'] );
	    
	    if( count( $arrDocuments ) )
	    {
		foreach( $arrDocuments as $arrDoc )
		{
		    //$arrDocURL = _Documents::fctGetDocURL( $arrDoc['doc_id'] );
		    $strDocs .= "<div class='Document'>";
			$strDocs .= "<div class='ButtonView' onclick='fctOpenPreview(\"". _Documents::fctGetGenerateURL( $arrDoc['doc_id'] ) ."\")'></div>";
			$strDocs .= "<div class='ButtonUse' onclick='fctSetLinkURL(\"". $intLinID ."\",\"". $arrDoc['doc_id'] ."\",\"\")'></div>";
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
	
	public function fctGetFiles( $intLinID )
	{
	    // GET
	    if( $_GET['start'] ) $intStart = $_GET['start'];
	    else		 $intStart = 0;
	    if( $_GET['search'] == $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']]['Search'] )
		unset( $_SESSION['easevars']['easelink']['search'] );
	    else
		$_SESSION['easevars']['easelink']['search'] = $_GET['search'];
	    
	    $arrFiles = _Search::fctFileSearch( $_SESSION['easevars']['easelink']['search'] );
	    
	    if( count( $arrFiles ) )
	    {
		foreach( $arrFiles as $arrFiles )
		{
		    $arrFileURL = _Files::fctGetFileURL( $arrFiles['fil_id'] );
		    $strFiles .= "<div class='Files' id='File-". $arrFiles['fil_id'] ."'>";
			$strFiles .= "<div class='ButtonView' onclick='window.open(\"". $arrFileURL['url'] ."\")'></div>";
			$strFiles .= "<div class='ButtonUse' onclick='fctSetLinkURL(\"". $intLinID ."\",\"\",\"". $arrFiles['fil_id'] ."\")'></div>";
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
	    unset( $_SESSION['easevars']['easelink']['search'] );
	    $_File = new _FILES;
	    $_File->fctUploadFile( 'file',array() );
	    $this->fctURLRedirect( $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/extension-popup.php" );
	}
    }
?>