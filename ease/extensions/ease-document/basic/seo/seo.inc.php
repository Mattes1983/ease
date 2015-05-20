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

    class EASEDocumentSeo extends _Extensions
    {
        
        public function __construct()
	{
	    parent::__construct();
            
            // Templates
            $this->arrConf['tmpl']['extension-popup'] = "/templates/extension-popup.tmpl";
            $this->arrConf['tmpl']['extension-popup-message'] = "/templates/extension-popup-message.tmpl";
            
            // JS
            $this->arrConf['js']['extension-popup'] = "basic/seo/js/extension-popup.js";            
        }
	
	public function fctExtensionPopup()
	{
	    switch( $_GET['action'] )
	    {
		case "save":	$this->fctSaveEdit(); break;
		default:	$this->fctGetEdit(); break;
	    }	
	}
	
	public function fctGetEdit()
	{	    
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) . $this->arrConf['tmpl']['extension-popup'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup'];
	    
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $_SESSION['easevars']['document'] ."'" );
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) . $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    
	    // Values
    	    $strContent = _ParseDoc::fctTagReplace( "name", $this->arrSQL[0]['doc_name'] ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "suffix", $this->arrSQL[0]['doc_suffix'] ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "autofilenamechecked", ($this->arrSQL[0]['doc_auto_name'] )?' checked':'' ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "title", $this->arrSQL[0]['doc_title'] ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "autotitlechecked", ($this->arrSQL[0]['doc_auto_title'] )?' checked':'' ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "description", $this->arrSQL[0]['doc_meta_description'] ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "keywords", $this->arrSQL[0]['doc_meta_keywords'] ,$strContent );
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='document.EditForm.submit()'>{Save}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Language
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}
		
	// Save Item-Info
	public function fctSaveEdit()
	{
	    if( $_SESSION['easevars']['document'] )
	    {
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."document SET doc_name = '". _Documents::fctCheckDocName( _Documents::fctCleanName( $_POST['name'] ),$_SESSION['easevars']['document'] ) ."',doc_title = '". _Content::fctHTMLEntities( $_POST['title'] ) ."',doc_meta_description = '". _Content::fctHTMLEntities( $_POST['description'] ) ."',doc_meta_keywords = '". _Content::fctHTMLEntities( $_POST['keywords'] ) ."', doc_auto_name = '". (( $_POST['autofilename'] )?'1':'0') ."', doc_auto_title = '". (( $_POST['autotitle'] )?'1':'0') ."' WHERE doc_id = '". $_SESSION['easevars']['document'] ."'" );
	    }
	    $this->fctURLRedirect( $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/extension-popup.php" );
	}
	
    }

?>
