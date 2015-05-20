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

    class EASEDocumentBasic extends _Extensions
    {
        
        public function __construct()
	{
	    parent::__construct();
            
            // Classes
            $this->arrConf['class']['generate'] = "/basic/generate/generate.inc.php";
            $this->arrConf['class']['generate_all'] = "/basic/generate_all/generate.inc.php";
            $this->arrConf['class']['seo'] = "/basic/seo/seo.inc.php";
            $this->arrConf['class']['delete'] = "/basic/delete/delete.inc.php";
        }
	
	// Optional
	public function fctExtensionPopup( $intLinID,$arrParams=array() )
	{
	    switch( $arrParams['type'] )
	    {
		// Generate This
		case "generate": 
		    include( dirname(__FILE__) . $this->arrConf['class']['generate'] );
		    $strClassName = $this->fctExtensionClass( "EASEDocumentGenerate" );
		    $_G = new $strClassName;
		    $_G->ExtensionVars = $this->ExtensionVars;
		    $_G->fctSetLang();
		    $_G->fctExtensionPopup();
		    $this->ExtensionVars = $_G->ExtensionVars;
		    $this->arrDocument = self::fctTransArrData( $_G->arrDocument,$this->arrDocument );
		
		    break;
		
		// Generate All
		case "generate_all": 
		    include( dirname(__FILE__) . $this->arrConf['class']['generate_all'] );
		    $strClassName = $this->fctExtensionClass( "EASEDocumentGenerate" );
		    $_G = new $strClassName;
		    $_G->ExtensionVars = $this->ExtensionVars;
		    $_G->fctSetLang();
		    $_G->fctExtensionPopup();
		    $this->ExtensionVars = $_G->ExtensionVars;
		    $this->arrDocument = self::fctTransArrData( $_G->arrDocument,$this->arrDocument );
		    break;
			    
		// SEO
		case "seo": 
		    include( dirname(__FILE__) . $this->arrConf['class']['seo'] );
		    $strClassName = $this->fctExtensionClass( "EASEDocumentSeo" );
		    $_Seo = new $strClassName;
		    $_Seo->ExtensionVars = $this->ExtensionVars;
		    $_Seo->fctSetLang();
		    $_Seo->fctExtensionPopup();
		    $this->ExtensionVars = $_Seo->ExtensionVars;
		    $this->arrDocument = self::fctTransArrData( $_Seo->arrDocument,$this->arrDocument );
		    break;
                
		// Delete Page
		case "delete": 
		    include( dirname(__FILE__) . $this->arrConf['class']['delete'] );
		    $strClassName = $this->fctExtensionClass( "EASEDocumentDelete" );
		    $_Seo = new $strClassName;
		    $_Seo->ExtensionVars = $this->ExtensionVars;
		    $_Seo->fctSetLang();
		    $_Seo->fctExtensionPopup();
		    $this->ExtensionVars = $_Seo->ExtensionVars;
		    $this->arrDocument = self::fctTransArrData( $_Seo->arrDocument,$this->arrDocument );
		    break;
	    }
	}

    }

?>
