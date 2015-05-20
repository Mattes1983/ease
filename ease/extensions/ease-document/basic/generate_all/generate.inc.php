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

    class EASEDocumentGenerate extends _Extensions
    {
        
        public function __construct()
	{
	    parent::__construct();
            
            // Templates
            $this->arrConf['tmpl']['extension-popup'] = "/templates/extension-popup.tmpl";
            
            // JS
            $this->arrConf['js']['extension-popup'] = "basic/generate_all/js/extension-popup.js";
            
            // CSS
            $this->arrConf['css']['extension-popup'] = "basic/generate_all/css/extension-popup.css";
        }
	
	public function fctExtensionPopup()
	{	    
	    switch( $_GET['action'] )
	    {
		case "generate":	$this->fctGenerate(); break;
		default:		$this->fctShowDetail(); break;
	    }	    
	}
	
	public function fctShowDetail()
	{
	    // Reset generate
	    _Generate::fctPrepareGenerate();
	    
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) . $this->arrConf['tmpl']['extension-popup'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup'];
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "" ,$strContent );
	    
	    // Language
	    $this->arrDocument['js_language']['CKGenerateTxt'] = $this->fctSetJSLangTxT( 'Generate' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}
	
	public function fctGenerate()
	{
	    _Generate::fctGenerateDocument();
	    echo json_encode( array( 'percent'=>$_SESSION['easevars']['generate']['percent'] ) );
	    exit;   
	}
    }
?>