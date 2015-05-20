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

    class JCarouselBasic extends _Extensions
    {
	
	function __construct()
	{
	    parent::__construct();
	    
	    // Basic
	    $this->arrConf['SliderWidth'] = 400;
	    $this->arrConf['SliderHeight'] = 200;
	    $this->arrConf['ItemWidth'] = 400;
	    $this->arrConf['ItemHeight'] = 200;
            
            // Templates
            $this->arrConf['tmpl']['slider'] = "basic/templates/slider.tmpl";
            $this->arrConf['tmpl']['slider-edit'] = "basic/templates/slider-edit.tmpl";
            
            // CSS
            $this->arrConf['css']['skin'] = "basic/jcarousel/skins/tango/skin.css";
            $this->arrConf['css']['slider'] = "basic/css/slider.css";
            $this->arrConf['css']['slider-edit'] = "basic/css/slider-edit.css";
            
            // JS
            $this->arrConf['js']['jquery'] = "basic/jcarousel/lib/jquery-1.7.1.min.js";
            $this->arrConf['js']['jcarousel'] = "basic/jcarousel/lib/jquery.jcarousel.js";
            $this->arrConf['js']['jcarousel-onload'] = "basic/js/jcarousel-onload.js";
	}
	
	public function fctRenderCode( $arrParams=array() )
	{
        
	    // Ask if in Drag&Drop-Mode
	    if( $this->EaseVars['dragmode'] == false )
	    {
                
                // Parent Width
                if( $arrParams['ParentAttributes']['width'] )	$this->arrConf['SliderWidth'] = $arrParams['ParentAttributes']['width'];
                if( $arrParams['ParentAttributes']['height'] )	$this->arrConf['SliderHeight'] = $arrParams['ParentAttributes']['height'];
                if( $arrParams['ParentAttributes']['width'] )	$this->arrConf['ItemWidth'] = $arrParams['ParentAttributes']['width'];
                if( $arrParams['ParentAttributes']['height'] )	$this->arrConf['ItemHeight'] = $arrParams['ParentAttributes']['height'];
		
		// Template
		if( $this->EaseVars['generatemode'] == false )
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['slider-edit'] );
		else
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['slider'] );
		
		// Files
		if( $this->EaseVars['generatemode'] == true )	$this->arrDocument['js_include']['jquery'] = $this->arrConf['js']['jquery'];
		if( $this->EaseVars['generatemode'] == true )	$this->arrDocument['css_include']['jcarouseltango'] = $this->arrConf['css']['skin'];
		$this->arrDocument['js_include']['jcarousel'] = $this->arrConf['js']['jcarousel'];
		if( $this->EaseVars['generatemode'] == false )	$this->arrDocument['js_include']['jcarouselonload'] = $this->arrConf['js']['jcarousel-onload'];
		$this->arrDocument['css']['jcarousel'] =  self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['slider'] );
		if( $this->EaseVars['generatemode'] == false )   $this->arrDocument['css']['jcarouseledit'] =  self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['slider-edit'] );

		$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
		
		// Content
		    // Vertical or Horizontal
		    switch(_Content::fctGetContent($arrParams['Link']['lin_id'],'type' ) )
		    {
			case "vertical": 
			    if( $strParams ) $strParams .= ",";
			    $strParams .= "vertical: true";
			    $strContent = _ParseDoc::fctTagReplace( "verticalselected", 'selected' ,$strContent );
			    break;
			default:
			    $strContent = _ParseDoc::fctTagReplace( "verticalselected", '' ,$strContent );
			    break;
		    }

		    // Circular or NoWrap
		    switch( _Content::fctGetContent($arrParams['Link']['lin_id'],'wrap' ) )
		    {
			case "nowrap": 
			    if( $strParams ) $strParams .= ",";
			    $strParams .= "wrap: null";
			    $strContent = _ParseDoc::fctTagReplace( "nowrapselected", 'selected' ,$strContent );
			    break;
			default:
			    if( $strParams ) $strParams .= ",";
			    $strParams .= "wrap: 'circular'";
			    $strContent = _ParseDoc::fctTagReplace( "nowrapselected", '' ,$strContent );
			    break;
		    }
		    
		    // Auto Scrolling
		    switch(_Content::fctGetContent($arrParams['Link']['lin_id'],'autoscroll' ) )
		    {
			default:
			    if( $strParams ) $strParams .= ",";
			    $strParams .= "auto: 5";
			    $strContent = _ParseDoc::fctTagReplace( "autoscrollnoselected", '' ,$strContent );
			    break;
			case "1":
			    $strContent = _ParseDoc::fctTagReplace( "autoscrollnoselected", 'selected' ,$strContent );
			    break;
		    }

		    $intScroll = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'scroll' ) );
		    if( $intScroll < 1 ) $intScroll = 1;
		    if( $strParams ) $strParams .= ",";
		    $strParams .= "scroll: ".$intScroll;
		    $strContent = _ParseDoc::fctTagReplace( "scroll", $intScroll ,$strContent );

		    // Slider-Width
		    $intSWidth = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'swidth' ) );
		    if( $intSWidth < 20 )   $intSWidth = $this->arrConf['SliderWidth'];
		    $strContent = _ParseDoc::fctTagReplace( "swidth", $intSWidth ,$strContent );
		    
		    // Slider-Height
		    $intSHeight = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'sheight' ) );
		    if( $intSHeight < 20 )   $intSHeight = $this->arrConf['SliderHeight'];
		    $strContent = _ParseDoc::fctTagReplace( "sheight", $intSHeight ,$strContent );
		    
		    // Item-Width
		    $intIWidth = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'iwidth' ) );
		    if( $intIWidth < 20 )   $intIWidth = $this->arrConf['ItemWidth'];
		    $strContent = _ParseDoc::fctTagReplace( "iwidth", $intIWidth ,$strContent );
		    
		    // Item-Height
		    $intIHeight = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'iheight' ) );
		    if( $intIHeight < 20 )   $intIHeight = $this->arrConf['ItemHeight'];
		    $strContent = _ParseDoc::fctTagReplace( "iheight", $intIHeight ,$strContent );
		    
		    // Item Space
		    $intISpace = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'ispace' ) );
		    $strContent = _ParseDoc::fctTagReplace( "ispace", $intISpace ,$strContent );
		    
		    $this->arrDocument['css'][] = "#JCarousel". $arrParams['Link']['lin_id'] ." .jcarousel-container-horizontal,#JCarousel". $arrParams['Link']['lin_id'] ." .jcarousel-container-vertical { width: ". $intSWidth ."px; height: ". $intSHeight ."px; }";
		    $this->arrDocument['css'][] = "#JCarousel". $arrParams['Link']['lin_id'] ." .jcarousel-clip-horizontal,#JCarousel". $arrParams['Link']['lin_id'] ." .jcarousel-clip-vertical { width: ". $intSWidth ."px; height: ". $intSHeight ."px; }";
		    $this->arrDocument['css'][] = "#JCarousel". $arrParams['Link']['lin_id'] ." .jcarousel-item { width: ". $intIWidth ."px; height: ". $intIHeight ."px; }";		
		    $this->arrDocument['css'][] = "#JCarousel". $arrParams['Link']['lin_id'] ." .jcarousel-item-horizontal { margin-right: ". $intISpace ."px;}";
		    $this->arrDocument['css'][] = "#JCarousel". $arrParams['Link']['lin_id'] ." .jcarousel-item-vertical { margin-bottom: ". $intISpace ."px;}";
		    $strContent = _ParseDoc::fctTagReplace( "image", '<ease:image width="'. $intIWidth .'" height="'. $intIHeight .'" />' ,$strContent,"<ease:image />" );
		    
		    //if( $strParams ) $strParams .= ",";
		    //$strParams .= "initCallback: fctEaseInitCallback";
		
		    $this->arrDocument['js'][] = "$(document).ready(function() { $('#mycarousel". $arrParams['Link']['lin_id'] ."').jcarousel({ ". $strParams ." }); });";
		
		// Images
		$_PD = new _ParseDoc();
		$_PD->boolNoEditmode = true;
		$_PD->boolReturnContentArray = true;
                $_PD->EaseVars = $this->EaseVars;
		$arrContent = $_PD->fctRenderLinks( $arrParams['Link']['lin_doc_id'],$arrParams['Link']['lin_id'],array( "image" => array( "name"=>"image","attribute"=>array("width"=>$intIWidth,"height"=>$intIHeight))));

		// Replace Images
		if( count( $arrContent['image'] ) )
		{
		    foreach( $arrContent['image'] as $strIMG )
			$strImages .= "<li>". $strIMG ."</li>\n";
		}
		else
		{
		    $strImages = '<li><ease:preview width="'. $this->arrConf['ItemWidth'] .'" height="'. $this->arrConf['ItemHeight'] .'" extension="EASEImage" drop="false" param_name="type" param_value="preview" /></li>';
		}
		$strContent = _ParseDoc::fctTagReplace( "carouselimage", $strImages ,$strContent );
		
	    }
	    else
	    {
		$strContent = '<ease:preview width="'. $this->arrConf['ItemWidth'] .'" height="'. $this->arrConf['ItemHeight'] .'" extension="EASEImage" drop="false" param_name="type" param_value="preview" />';
	    }

	    // JavaScript-Function for Edit
	    $this->ExtensionVars['JSEditStart'] = "fctEaseJCarouselEditStart(". $arrParams['Link']['lin_id'] .")";
	    $this->ExtensionVars['JSEditEnd'] = "fctEaseJCarouselEditEnd(". $arrParams['Link']['lin_id'] .")";
	    
	    // Language
	    $this->arrDocument['js_language']['JCarouselLangTxt'] = $this->fctSetJSLangTxT( 'JCarousel' );
	    $strContent = $this->fctReplaceLang( $strContent );
		    
	    return $strContent;
	}
	
    }

?>