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

    class EASEUpdateBasic extends _Extensions
    {
        
        public function __construct()
	{
	    parent::__construct();
            
            // Templates
            $this->arrConf['tmpl']['extension-popup'] = "basic/templates/extension-popup.tmpl";
            $this->arrConf['tmpl']['extension-popup-message'] = "basic/templates/extension-popup-message.tmpl";
            
            // JS
            $this->arrConf['js']['extension-popup'] = "basic/js/extension-popup.js";
        }
	
	// Optional
	public function fctExtensionPopup( $intLinID,$arrParams=array() )
	{
	    switch( $_GET['action'] )
	    {
		case "look_update":	$this->fctLook4Update(); break;
		default:		$this->fctLoadPage(); break;
	    }
	}

	public function fctLoadPage()
	{
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup'];
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    	    
	    // Footer-Buttons
	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='top.fctEaseCloseExtensionPopup()'>{Close}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Language
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}
	
	public function fctLook4Update()
	{
	    // Load Information    
	    $strJson = file_get_contents( 'http://update.ease-cms.com/index.php' );
	    $arrJson = json_decode( $strJson );
	    
	    $strJson2 = file_get_contents( 'http://update.ease-cms.com/index.php?url='.urlencode($arrJson->{'url'}) );
	    $arrJson2 = json_decode( $strJson2 );	    
	    
	    $strFile = $this->Config['server']['domain'].$this->Config['path']['basic']."/test.php";
	    $handle2 = fopen( $strFile,"w" );
	    fseek( $handle2,0 );
	    $contents = fwrite( $handle2, $arrJson2->{'content'} );
	    fclose( $handle2 );
	    
	    // Return Information
	    echo $strJson;
	    exit;
	}
	
    }

?>
