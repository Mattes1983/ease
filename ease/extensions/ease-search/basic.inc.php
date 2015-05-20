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

    class EASESearchBasic extends _Extensions
    {
	
	public function __construct()
	{
	    parent::__construct(); 
	    
	    // Basics
	    $this->arrConf['MaxPerPage'] = 5;		// Max Results per Page
	    $this->arrConf['HeadlineLength'] = 50;	// Max length Headline-Characters
	    $this->arrConf['TextLength'] = 300;		// Max length Text-Charachters
	    $this->arrConf['PagingSpace'] = 5;		// Max pages before & after actual page
	    $this->arrConf['DocIDForResult'] = 1;	// The Document-ID with the resultlist
            
            // Templates
            $this->arrConf['tmpl']['searchfield'] = "basic/templates/searchfield.tmpl";
            $this->arrConf['tmpl']['result'] = "basic/templates/result.tmpl";
            $this->arrConf['tmpl']['result-edit'] = "basic/templates/result-edit.tmpl";
            
            // CSS
            $this->arrConf['css']['searchfield'] = "basic/css/searchfield.css";
            $this->arrConf['css']['result'] = "basic/css/result.css";
            $this->arrConf['css']['result-edit'] = "basic/css/result-edit.css";
            
            // JS
            $this->arrConf['js']['easesearch-onload'] = "basic/js/easesearch-onload.js";
            
	    // PHP
            $this->arrConf['php']['search'] = "basic/php/search.inc.php";
	}
	
	public function fctRenderCode( $arrParams=array() )
	{	    
	    switch( $arrParams['item'] )
	    {
		// Searchfield
		case 'searchfield': $strContent = $this->fctGetSearchfield( $arrParams ); break;
		// Result
		case 'result': $strContent = $this->fctGetResultBox( $arrParams ); break;
	    }
	    
	    // Language
	    $this->arrDocument['js_language']['EASESearchLangTxt'] = $this->fctSetJSLangTxT( 'EASESearch' );
	    $strContent = $this->fctReplaceLang( $strContent );
	    
	    return $strContent;
	}
	
	public function fctGetSearchfield( $arrParams=array() )
	{
	    $this->arrDocument['css']['searchfield'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['searchfield'] );
	    $this->arrDocument['php_include']['easesearch'] = $this->arrConf['php']['search'];
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['searchfield'] );
	    $strContent = _ParseDoc::fctTagReplace( "resulturl", '<ease:url id="'. $this->arrConf['DocIDForResult'] .'" />' ,$strContent );
	    return $strContent;
	}
	
	public function fctGetResultBox( $arrParams=array() )
	{
	    if( $this->EaseVars['generatemode'] == false ) 
		    $this->arrDocument['css']['searchresultedit'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['result-edit'] );
	    $this->arrDocument['css']['searchresult'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['result'] );

	    if( $this->EaseVars['generatemode'] == false ) 
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['result-edit'] );
	    $strContent .= self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['result'] );

	    $strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );

	    if( $this->EaseVars['generatemode'] == false )	$this->arrDocument['js_include']['easesearchonload'] = $this->arrConf['js']['easesearch-onload'];

	    // Parameter
		// MaxPerPage
		$intMaxPerPage = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'maxperpage' ) );
		if( $intMaxPerPage <= 0 || $intMaxPerPage > 30 )   $intMaxPerPage = $this->arrConf['MaxPerPage'];
		$strContent = _ParseDoc::fctTagReplace( "maxperpage", $intMaxPerPage ,$strContent );

		// Headline Length
		$intHeadlineLength = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'headlinelength' ) );
		if( $intHeadlineLength <= 0 )   $intHeadlineLength = $this->arrConf['HeadlineLength'];
		$strContent = _ParseDoc::fctTagReplace( "headlinelength", $intHeadlineLength ,$strContent );

		// Text Length
		$intTextLength = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'textlength' ) );
		if( $intTextLength <= 0 )   $intTextLength = $this->arrConf['TextLength'];
		$strContent = _ParseDoc::fctTagReplace( "textlength", $intTextLength ,$strContent );

		// Paging Space
		$intPagingSpace = intval( _Content::fctGetContent($arrParams['Link']['lin_id'],'pagingspace' ) );
		if( $intPagingSpace <= 0 )   $intPagingSpace = $this->arrConf['MaxPerPage'];
		$strContent = _ParseDoc::fctTagReplace( "pagingspace", $intPagingSpace ,$strContent );


	    $this->arrDocument['php_include']['easesearch'] = $this->arrConf['php']['search'];

	    // JavaScript-Function for Edit
	    $this->ExtensionVars['JSEditStart'] = "fctEASESearchEditStart(". $arrParams['Link']['lin_id'] .")";
	    $this->ExtensionVars['JSEditEnd'] = "fctEASESearchEditEnd(". $arrParams['Link']['lin_id'] .")";

	    // Language
	    $this->arrDocument['js_language']['EASESearchLangTxt'] = $this->fctSetJSLangTxT( 'EASESearch' );
	    $strContent = $this->fctReplaceLang( $strContent );
	    
	    return $strContent;
	}

    }
?>