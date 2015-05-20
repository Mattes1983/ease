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

    class WebBasic extends _Extensions
    {
        
	public function __construct()
	{
	    parent::__construct();
            
            // Templates
            $this->arrConf['tmpl']['meta'] = "basic/templates/meta.tmpl";
            $this->arrConf['tmpl']['startpage'] = "basic/templates/startpage.tmpl";
            $this->arrConf['tmpl']['header'] = "basic/templates/header.tmpl";
            $this->arrConf['tmpl']['footer'] = "basic/templates/footer.tmpl";
            $this->arrConf['tmpl']['image'] = "basic/templates/image.tmpl";
            $this->arrConf['tmpl']['imagelink'] = "basic/templates/imagelink.tmpl";
            $this->arrConf['tmpl']['text'] = "basic/templates/text.tmpl";
            $this->arrConf['tmpl']['col-2'] = "basic/templates/cols-2.tmpl";
            $this->arrConf['tmpl']['col-2-dragmode'] = "basic/templates/cols-2-dragmode.tmpl";
            $this->arrConf['tmpl']['col-3'] = "basic/templates/cols-3.tmpl";
            $this->arrConf['tmpl']['col-3-dragmode'] = "basic/templates/cols-3-dragmode.tmpl";
            $this->arrConf['tmpl']['google-analytics'] = "basic/templates/google-analytics.tmpl";
            $this->arrConf['tmpl']['menu'] = "basic/templates/menu.tmpl";
            
            // CSS
            $this->arrConf['css']['basic'] = "basic/css/basic.css";
            $this->arrConf['css']['startpage'] = "basic/css/startpage.css";
            $this->arrConf['css']['image'] = "basic/css/image.css";
            $this->arrConf['css']['text'] = "basic/css/text.css";
            $this->arrConf['css']['col-2'] = "basic/css/cols-2.css";
            $this->arrConf['css']['col-3'] = "basic/css/cols-3.css";
        }
	
	public function fctRenderCode( $arrParams=array() )
	{
            
            // IE6-8 CSS:
            //$this->arrDocument['css_include_ie']['IE 6']['basic'] = "basic/css/ie6.css";
            //$this->arrDocument['css_include_ie']['IE 7']['basic'] = "basic/css/ie7.css";
            //$this->arrDocument['css_include_ie']['IE 8']['basic'] = "basic/css/ie8.css";
            //$this->arrDocument['css_include_ie']['IE 8']['basic'] = "basic/css/ie9.css";
	    
	    switch( $arrParams['layout'] )
	    {

		// Main-Templates: Start
		case 'startpage':
                    
                    // HEAD
                    $this->arrDocument['exclude']['favicon'] = $this->arrConf['image']['favicon'];
                    $this->arrDocument['head']['meta-information'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['meta'] );
		    
		    // CSS		
		    $this->arrDocument['css_include']['basic'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['basic'] );
		    $this->arrDocument['css_include']['start'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['startpage'] );
		
		    // JS
		    //$this->arrDocument['js_include']['basic'] = "basic/js/basic.js";
		    
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['startpage'] );
		    break;
		
		// Content-Elements: Header
		case 'header':
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['header'] );
		    break;
				
		// Content-Elements: Footer
		case 'footer':
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['footer'] );
		    break;
		
		// Content-Elements: Image
		case 'image':
		    $this->arrDocument['css']['image'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['image'] );
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['image'] );
		    break;
		
		// Content-Elements: Image
		case 'imagelink':
		    $this->arrDocument['css']['image'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['image'] );
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['imagelink'] );
		    break;

		// Content-Elements: Textfield
		case 'text':
		    $this->arrDocument['css']['text'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['text'] );
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['text'] );
		    break;
		
		// Content-Elements: 2 Cols
		case 'cols-2':		    
		    $this->arrDocument['css']['col-2'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['col-2'] );
		    
		    // Im Drag-Modus
		    if( $this->EaseVars['dragmode'] == true )
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['col-2-dragmode'] );
		    else
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['col-2'] );
		    break;
		    
		// Content-Elements: 3 Cols
		case 'cols-3':		    
		    $this->arrDocument['css']['col-3'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['col-3'] );
		    
		    // Im Drag-Modus
		    if( $this->EaseVars['dragmode'] == true )
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['col-3-dragmode'] );
		    else
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['col-3'] );
		    break;
		    
		// Content-Element: Google Analytics
		case 'google-analytics':
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['google-analytics'] );
		    break;
		
		// Content-Elements: Menu
		case 'menu':
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['menu'] );
		    
		    // Return <ul>-List
		    $strClassName = _Extensions::fctExtensionClass( "EASENavigation" );
		    $_Class = new $strClassName;
		    $arrNavigation = $_Class->fctLoadMenuData();
		    $strContent = _ParseDoc::fctTagReplace( "menu", $_Class->fctReturnMenu( $arrNavigation ) ,$strContent );
		    break;
	    }
	    
	    return $strContent;
	}
    }

?>