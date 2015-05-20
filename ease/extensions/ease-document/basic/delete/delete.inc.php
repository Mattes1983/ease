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

    class EASEDocumentDelete extends _Extensions
    {
        
        public function __construct()
	{
	    parent::__construct();
            
            // Templates
            $this->arrConf['tmpl']['extension-popup'] = "templates/extension-popup.tmpl";
            $this->arrConf['tmpl']['extension-popup-message'] = "templates/extension-popup-message.tmpl";
            
            // CSS
            $this->arrConf['css']['extension-popup'] = "basic/delete/css/extension-popup.css";            
            
            // JS
            $this->arrConf['js']['extension-popup'] = "basic/delete/js/extension-popup.js";
        }
	
	public function fctExtensionPopup()
	{
	    switch( $_GET['action'] )
	    {
		case "delete":	$this->fctDelete(); break;
		default:	$this->fctGetDelete(); break;
	    }	
	}
	
	public function fctGetDelete()
	{	    
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
            $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup'];
	    
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $_SESSION['easevars']['document'] ."'" );
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    
	    // Values
            $strContent = _ParseDoc::fctTagReplace( "id", $this->arrSQL[0]['doc_id'] ,$strContent );
    	    $strContent = _ParseDoc::fctTagReplace( "name", $this->arrSQL[0]['doc_name'] ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "suffix", $this->arrSQL[0]['doc_suffix'] ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "title", $this->arrSQL[0]['doc_title'] ,$strContent );

                // Document
                $strDoc .= '<div class="Doc">';
                    if( $this->arrSQL[0]['doc_title'] )
                        $strDoc .= "<h2>". $this->fctSetLength( $this->arrSQL[0]['doc_title'],20) ."</h2>";
                    else
                        $strDoc .= "<h2>{NoTitle}</h2>";
                    $strDoc .= "<div class='Pad'><p><b>{Author}:</b> ". _USER::fctGetUserName( $this->arrSQL[0]['doc_create_use_id'] ) .", ". _GlobalFunctions::fctFormatMysqlDate( $this->arrSQL[0]['doc_create_date'] ) ."</p>";
                    // Last Change
                    if( $this->arrSQL[0]['doc_changed_use_id'] != 0 ) $strDoc .= "<p><b>{LastChange}:</b> ". _USER::fctGetUserName( $this->arrSQL[0]['doc_changed_use_id'] ) .", ". _GlobalFunctions::fctFormatMysqlDate( $this->arrSQL[0]['doc_changed_date'] ) ."</p>";
                    if( $this->arrSQL[0]['doc_meta_description'] ) $strDoc .= "<p><b>{Description}:</b> ". $this->fctSetLength( $this->arrSQL[0]['doc_meta_description'],100) ."</p>";
                    if( $this->arrSQL[0]['doc_first_text'] ) $strDoc .= "<p><b>{Content}:</b> ". $this->fctSetLength( $this->arrSQL[0]['doc_first_text'],100) ."</p>";
                $strDoc .= '</div></div>';
                $strContent = _ParseDoc::fctTagReplace( "document", $strDoc ,$strContent );
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='fctDeleteDoc()'>{Delete}</a></div><div class='ButtonRight'></div></div><div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='window.parent.fctEaseCloseExtensionPopup()'>{Cancel}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
            
	    // Language
            $this->arrDocument['js_language']['EaseDeleteLangTxt'] = $this->fctSetJSLangTxT( 'Delete' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}
		
	// Save Item-Info
	public function fctDelete()
	{
	    if( $_SESSION['easevars']['document'] )
	    {
                if( _Documents::fctDeleteDoc( $_SESSION['easevars']['document'] ) )
                    echo json_encode( array( 'repsone'=>'delete' ) );
                else
                    echo json_encode( array( 'error'=>'Sorry, an error occurred.' ) );
                exit;   
	    }
	}
	
    }

?>
