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

    class _ParseDoc extends _GlobalFunctions
    {
	
	var $arrDocument;
	
	// Create Document
	public function fctCreateDocument( $boolInclude=false )
	{
	    
	    if( $this->EaseVars['generatemode'] == true && $boolInclude == false )
	    {		
                // Check Page-Name
		$this->arrDocument['php'][] = "\$_EaseGlobalFunctions = new _EaseGlobalFunctions; \$_EaseGlobalFunctions->fctCheckGeneratePage(". $_SESSION['easevars']['document'] .");";
                
                // HTML-Copyright
                $strHTMLCopyright = "<!--\n/////////////////////////////////////////////\nease CMS\nDiese Website wurde mit Hilfe des Drag & Drop\nContent Management Systems ease CMS erstellt.\nEntwickler: Matthias Dahms\nWeb: http://www.ease-cms.de\n/////////////////////////////////////////////\n";
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'HTMLCopyright'" );
		if( count( $this->arrSQL ) )
		    $strHTMLCopyright .= $this->arrSQL[0]['set_value'] ."\n/////////////////////////////////////////////\n-->";
		$this->arrDocument['head'][] = $strHTMLCopyright;
	    }
	    
	    // PHP
	    if( count( $this->arrDocument['php_include'] ) || count( $this->arrDocument['php'] ) || count( $this->arrDocument['php_include_init'] ) || count( $this->arrDocument['php_init'] ) )
	    {
		$strParsedDocument = "<?php\r";
		
		// Basic Functions
		$strParsedDocument .= self::fctGetHTMLLink2File( $this->Config['server']['domain'].$this->Config['path']['basic'].$this->Config['path']['generate'].$this->Config['path']['extern-functions']."/functions.inc.php" )."\r";
		
		// Init
		    // Includes
		    if( count( $this->arrDocument['php_include_init'] ) )
			foreach ( $this->arrDocument['php_include_init'] as $strPartP ) 
			    $strParsedDocument .= self::fctGetHTMLLink2File( $strPartP )."\r";
		    
		    // Code
		    if( count( $this->arrDocument['php_init'] ) )
			foreach ( $this->arrDocument['php_init'] as $strPartP ) 
			    $strParsedDocument .= $strPartP ."\r";
		    

		// Execute
		    // Includes
		    if( count( $this->arrDocument['php_include'] ) )
			foreach ( $this->arrDocument['php_include'] as $strPartP ) 
			    $strParsedDocument .= self::fctGetHTMLLink2File( $strPartP )."\r";

		    // Code
		    if( count( $this->arrDocument['php'] ) )
			foreach ( $this->arrDocument['php'] as $strPartP ) 
			    $strParsedDocument .= $strPartP ."\r";
		
		$strParsedDocument .= "?>";
	    }
	    
	    // HTML
            if( $boolInclude == true )
            {
                $this->arrDocument['html'][] = self::fctLoadFile( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-basic/include.tmpl" );
            }
	    else if( count( $this->arrDocument['html'] ) == 0 )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'HTMLVersion'" );
		$this->arrDocument['html'][] = self::fctLoadFile( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-basic/". $this->arrSQL[0]['set_value'] .".tmpl" );
	    }
            
	    foreach ( $this->arrDocument['html'] as $strPart )	$strParsedDocument .= $strPart."\r";
	    
	    // CSS
	    foreach ( $this->arrDocument['css'] as $strPartC )	$strCSS .= $strPartC."\n";
	    if( $strCSS )
	    {
		if( $this->EaseVars['generatemode'] == false )
		    $this->arrDocument['css_include'][] = self::fctCreateFile( $this->Config['path']['cms'] . $this->Config['path']['project']."/css/". $_SESSION['easevars']['document'] .".css",$strCSS );
		else
		    $this->arrDocument['css_include'][] = self::fctCreateFile( $this->Config['path']['generate'] . "/css/". $_SESSION['easevars']['document'] .".css",$strCSS );
	    }

	    // CSS-Includes
	    foreach ( $this->arrDocument['css_include'] as $strPartC )	$this->arrDocument['head'][] = self::fctGetHTMLLink2File( $strPartC );
	    
	    // CSS (IE-Comments)
	    foreach ( $this->arrDocument['css_ie'] as $intVersion=>$strPartC )	$arrCSSIE[$intVersion] .= $strPartC."\n";
	    if( $arrCSSIE )
	    {
		foreach( $arrCSSIE as $intVersion=>$strCSS )
		{
		    if( $this->EaseVars['generatemode'] == false )
			$this->arrDocument['css_include_ie'][$intVersion][] = self::fctCreateFile( $this->Config['path']['cms'] . $this->Config['path']['project']."/css/". $_SESSION['easevars']['document'] ."-". _Documents::fctCleanName( $intVersion ) .".css",$strCSS );
		    else
			$this->arrDocument['css_include_ie'][$intVersion][] = self::fctCreateFile( $this->Config['path']['generate'] ."/css/". $_SESSION['easevars']['document'] ."-". _Documents::fctCleanName( $intVersion ) .".css",$strCSS );
		}
	    }
	    
	    // CSS-Includes (IE-Comments)
	    foreach ( $this->arrDocument['css_include_ie'] as $intVersion=>$arrPartC )
	    {
		if( count( $arrPartC ) )
		{
		    $strIEVersion = "<!--[if ". $intVersion ."]>\n";
		    foreach( $arrPartC as $strPartC )
			$strIEVersion .= self::fctGetHTMLLink2File( $strPartC )."\n";
		    $strIEVersion .= "<![endif]-->\n";
		    $this->arrDocument['head'][] = $strIEVersion;;
		}
	    }
	    
	    // JavaScript

		// First
		unset( $strJS );
		foreach ( $this->arrDocument['js_language'] as $strPartJ )	$strJS .= $strPartJ."\n";
		if( $strJS )
		{
		    if( $this->EaseVars['generatemode'] == false )
			array_unshift( $this->arrDocument['js_include'], self::fctCreateFile( $this->Config['path']['cms'] . $this->Config['path']['project']."/js/". $_SESSION['easevars']['document'] ."-first.js",$strJS ) );
		    else
			array_unshift( $this->arrDocument['js_include'], self::fctCreateFile( $this->Config['path']['generate'] ."/js/". $_SESSION['easevars']['document'] ."-first.js",$strJS ) );
		}
	    
		// Last
		unset( $strJS );
		foreach ( $this->arrDocument['js'] as $strPartJ )	$strJS .= $strPartJ."\n";
		if( $strJS )
		{
		    if( $this->EaseVars['generatemode'] == false )
			$this->arrDocument['js_include'][] = self::fctCreateFile( $this->Config['path']['cms'] . $this->Config['path']['project']."/js/". $_SESSION['easevars']['document'] ."-last.js",$strJS );
		    else
			$this->arrDocument['js_include'][] = self::fctCreateFile( $this->Config['path']['generate'] ."/js/". $_SESSION['easevars']['document'] ."-last.js",$strJS );
		}
	    
	    // JavaScript-Includes
	    foreach ( $this->arrDocument['js_include'] as $strPartJ )	$this->arrDocument['body'][] = self::fctGetHTMLLink2File( $strPartJ );

	    // Head
	    foreach ( $this->arrDocument['head'] as $strPartH )	$strParsedHead .= $strPartH."\r";
	    $strParsedDocument = self::fctTagReplace( "head",$strParsedHead,$strParsedDocument );

	    // Body
	    foreach ( $this->arrDocument['body'] as $strPartB )	$strParsedBody .= $strPartB."\r";
	    $strParsedDocument = self::fctTagReplace( "body",$strParsedBody,$strParsedDocument );

	    return $strParsedDocument;
	}
	
	// Parse & create Document for CMS & Redirect to File
	public function fctParseDoc( $intDocID=false )
	{
	    // Vars
	    $intDocID = intval( $intDocID );
	    if( $intDocID )
	    {
		$intSaveDocID = $_SESSION['easevars']['document'];
		$_SESSION['easevars']['document'] = $intDocID;
	    }
	    
	    // Delete Dokument-Files
	    _Files::fctDeleteExtDocFiles();
	    
	    // Render Level
	    self::fctRenderLevel();
	    
	    // Create Document
	    self::fctCreateFile( $this->Config['path']['cms'] . $this->Config['path']['project']."/". $_SESSION['easevars']['document'] .".php", self::fctCreateDocument() );
	    
	    // Generate-Mode
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Generate-Mode'" );
	    switch( $this->arrSQL[0]['set_value'] )
	    {
		// Generate every Page-Request, if Document changed
		case "1":
		    $this->fctQuery( "SELECT doc_changed FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $_SESSION['easevars']['document'] ."'" );
		    if( $this->arrSQL[0]['doc_changed'] == 1 )
		    {
			$this->fctParseDocGenerate();
		    }
		    break;
	    }
	    
	    // Vars
	    if( $intDocID )
	    {
		$_SESSION['easevars']['document'] = $intSaveDocID;
	    }

	    // Redirect
	    $this->fctURLRedirect( $this->Config['http']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'].$this->Config['path']['project']."/". $_SESSION['easevars']['document'] .".php",true );
	}
	
	// Parse & create document for generate
	public function fctParseDocGenerate( $intDocID=false )
	{
	    // Classes
	    $_PD = new _ParseDoc;
	    
	    // Vars
	    $intDocID = intval( $intDocID );
	    if( $intDocID )
	    {
		$intSaveDocID = $_SESSION['easevars']['document'];
		$_SESSION['easevars']['document'] = $intDocID;
	    }
	    
	    // Max Level for this Document
	    $_PD->fctQuery( "SELECT max(lin_lvl_id) as max FROM ". $this->Config['database']['table_prefix'] ."link LIMIT 0,1" );
	    $intGenerateLvl = $_PD->arrSQL[0]['max'];
	    
	    // Vars
	    $intLevel = $_SESSION['easevars']['level'];
	    $_PD->EaseVars['level'] = $intGenerateLvl;
	    $_PD->EaseVars['generatemode'] = true;
	    $_SESSION['easevars']['level'] = $intGenerateLvl;
	    $_SESSION['easevars']['generatemode'] = true;
	    
	    // Render Level
	    $_PD->fctRenderLevel();
	    
	    // Create Document
	    $_PD->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $_SESSION['easevars']['document'] ."'" );
	    if( count( $_PD->arrSQL ) == 1 )
	    {
		// Title & Meta-Tags
                if( $_PD->arrSQL[0]['doc_title'] )
                    $_PD->arrDocument['head'][] = '<title>'. $_PD->arrSQL[0]['doc_title'] .'</title>';
                if( $_PD->arrSQL[0]['doc_meta_description'] )
                    $_PD->arrDocument['head'][] = '<meta name="description" content="'. str_replace( '"',"'",$_PD->arrSQL[0]['doc_meta_description'] ) .'" />';
                if( $_PD->arrSQL[0]['doc_meta_keywords'] )
                    $_PD->arrDocument['head'][] = '<meta name="keywords" content="'. str_replace( '"',"'",$_PD->arrSQL[0]['doc_meta_keywords'] ) .'" />';
		
		// Create
		$_PD->fctCreateFile( $_PD->Config['path']['generate'] ."/". $_PD->arrSQL[0]['doc_name'] . $_PD->arrSQL[0]['doc_suffix'] ,$_PD->fctCreateDocument() );
		_Files::GenerateDocFiles( $intDocID );
		
		// Refresh Generate-Data
		_Generate::fctInsertData( $intDocID );
		
		// Update
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."document SET doc_changed = '0',doc_changed_use_id = '0', doc_changed_date = '0' WHERE doc_id = '". $_SESSION['easevars']['document'] ."'" );
	    }
	    
	    // Reset-Vars
	    $_SESSION['easevars']['generatemode'] = false;
	    $_SESSION['easevars']['level'] = $intLevel;
	    if( $intDocID ) $_SESSION['easevars']['document'] = $intSaveDocID;
	}
	
	// Parse & create include for generate
	public function fctParseIncGenerate( $intLinID )
	{
	    $intLinID = intval( $intLinID );
	    if( $intLinID )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_ID = '". $intLinID ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    $arrLink = $this->arrSQL[0];
		    
		    // Vars
		    $intSaveDocID = $_SESSION['easevars']['document'];
		    $_SESSION['easevars']['document'] = $arrLink['lin_doc_id'];

		    // Max Level for this Document
		    $this->fctQuery( "SELECT max(lin_lvl_id) as max FROM ". $this->Config['database']['table_prefix'] ."link LIMIT 0,1" );
		    $intGenerateLvl = $this->arrSQL[0]['max'];

		    // Vars
		    $intLevel = $_SESSION['easevars']['level'];
		    $this->EaseVars['level'] = $intGenerateLvl;
		    $_SESSION['easevars']['generatemode'] = true;
		    $this->EaseVars['generatemode'] = true;
		    $_SESSION['easevars']['level'] = $intGenerateLvl;
		    
		    $arrAttributes[$arrLink['lin_name']]['attribute'] = _Link::fctGetLinkAttributes( $arrLink['lin_id'] );
		    $strInclude = array_shift( $this->fctRenderLinks( $arrLink['lin_doc_id'], false,array(),false,$arrLink['lin_id'] ) );
		    self::fctCreateFile( $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $intLinID .".php", $strInclude  );
		    
		    // Reset-Vars
		    $_SESSION['easevars']['level'] = $intLevel;
		    $_SESSION['easevars']['document'] = $intSaveDocID;
		    $_SESSION['easevars']['generatemode'] = false;
		}
	    }
	}
	
	// Render-Level
	private function fctRenderLevel()
	{
	    
	    // Include CMS-Tools/Scripts
	    if( $this->EaseVars['generatemode'] == false )
	    {	    
		
		$strPathTheme = $this->Config['http']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'].$this->Config['path']['theme']['root'].$this->Config['path']['theme']['theme'];
		$strPathCSS = $strPathTheme.$this->Config['path']['theme']['css'];
		$strPathJS = $this->Config['http']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'].$this->Config['path']['libs'];
		
	    // JQuery
		// CSS
		$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-CSS'" );
		if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['css_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];

		// JS
		$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-JS'" );
		if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['js_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];

	    // JQuery-UI
		// CSS
		$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-UI-CSS'" );
		if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['css_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];

		// JS
		$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-UI-JS'" );
		if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['js_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];
		
		// Basic-Function
		$this->arrDocument['js_include'][] = $strPathJS."/frame-functions.js";

		// Dragger
		$this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] ."/page.css";
		$this->arrDocument['body'][] = '<div class="easeDrag"></div>';	
	    }
		
	    // Render all Links
	    $arrContent = $this->fctRenderLinks( $_SESSION['easevars']['document'],0 );
	    if( count( $arrContent ) )
		foreach( $arrContent as $elem ) $strContent .= $elem;
	    if( $strContent ) $strPContent = $strContent;
	    $this->arrDocument['body'][] = $strPContent;
		
	}
	
	// Login
	public function fctRenderLogin()
	{
	    
	    // HTML
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'HTMLVersion'" );
	    $this->arrDocument['html'][0] = self::fctLoadFile( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-basic/". $this->arrSQL[0]['set_value'] .".tmpl" );

	    /* Read-Template*/
		// Theme-Paths
		$strPathTemplate = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['templates'];
		$strPathTheme = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'];
		$strPathCSS = $strPathTheme.$this->Config['path']['theme']['css'];
		$strPathJS = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['libs'];

		// Read File
		if( file_exists( $strPathTemplate."/login.tmpl" ) )
		{
		    
		$strPathJS = $this->Config['http']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'].$this->Config['path']['libs'];
		
		    // JQuery
			// CSS
			$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-CSS'" );
			if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['css_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];

			// JS
			$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-JS'" );
			if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['js_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];

		    // JQuery-UI
			// CSS
			$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-UI-CSS'" );
			if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['css_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];

			// JS
			$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-UI-JS'" );
			if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['js_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];
		    
		    // Theme
		    $this->arrDocument['css_include'][] = $strPathCSS ."/reset.css";
		    $this->arrDocument['css_include'][] = $strPathCSS ."/basic.css";
		    $this->arrDocument['css_include'][] = $strPathCSS ."/login.css";
		    
		    $this->arrDocument['js'][] = "if( window.parent.length > 0 ) window.parent.location.href = '". $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/index.php';";
		    $this->arrDocument['js'][] = "$(document).ready(function(){ $('input[name=\"login\"]').focus(); });";
			
		    // Templates
		    if( $this->fctCheckBrowser() )
			$strBasicC = self::fctLoadFile( $strPathTemplate."/login.tmpl" );	    
		    else
			$strBasicC = self::fctLoadFile( $strPathTemplate."/browser-update.tmpl" );
		    
		    // Error-Messages
		    if( count( $_SESSION['errormessage'] ) )
		    {
			foreach( $_SESSION['errormessage'] as $strErrorMessage )
			    $strErrorMessages .= $strErrorMessage;
		    }
		    unset( $_SESSION['errormessage'] );
		    
		    // Tag-Replaces
		    $strBasicC = self::fctTagReplace( "action","index.php?action=login",$strBasicC );
		    $strBasicC = self::fctTagReplace( "errormessage",$strErrorMessages,$strBasicC );
		    
		    // Language
		    $_L = new _Language;
		    $strBasicC = $_L->fctReplaceLang( $strBasicC );

		    $this->arrDocument['body'][] = _Files::fctUpdateExtContent( $strBasicC, $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] );

		    $this->arrDocument = _Files::fctUpdateExtDocFiles( $this->arrDocument, $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] );

		}

	    echo self::fctCreateDocument();
	}
	
	// Extension-Popup
	public function fctRenderExtensionPopup( $intExtID=0,$intLinID=0,$strName=false,$strValue=false )
	{

	    // HTML
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'HTMLVersion'" );
	    $this->arrDocument['html'][0] = self::fctLoadFile( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-basic/". $this->arrSQL[0]['set_value'] .".tmpl" );

	    // Theme-Paths
	    $strPathTemplate = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['templates'];
	    $strPathTheme = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'];
	    $strPathCSS = $strPathTheme.$this->Config['path']['theme']['css'];
	    $strPathJS = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['libs'];

	    // JQuery
		// CSS
		$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-CSS'" );
		if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['css_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];

		// JS
		$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-JS'" );
		if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['js_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];

	    // JQuery-UI
		// CSS
		$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-UI-CSS'" );
		if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['css_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];

		// JS
		$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-UI-JS'" );
		if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['js_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];
		
	    // Basic-Function
	    $this->arrDocument['js_include'][] = $strPathJS."/extension-popup-functions.js";

	    if( $intLinID > 0 )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content ON lin_lco_id = lco_id LEFT JOIN ". $this->Config['database']['table_prefix'] ."extension ON lin_ext_id = ext_id WHERE lin_id = '". $intLinID ."'");
		if( $this->arrSQL[0]['ext_id'] != $intExtID )
		{
		    if( $intExtID > 0 )
		    {
			$_GF = new _GlobalFunctions();
			$_GF->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_id = '". $intExtID ."'");		
			$this->arrSQL[0]['ext_id'] = $_GF->arrSQL[0]['ext_id'];
			$this->arrSQL[0]['ext_name'] = $_GF->arrSQL[0]['ext_name'];
		    }
		}
		
		$_SESSION['easevars']['extension_popup']['ext_id'] = $this->arrSQL[0]['ext_id'];
		$_SESSION['easevars']['extension_popup']['lin_id'] = $this->arrSQL[0]['lin_id'];
				
		// Extension
		if( count( $this->arrSQL ) )
		{		    
		    $strClassName = _Extensions::fctExtensionClass( $this->arrSQL[0]['ext_name'] );
		    $_Class = new $strClassName;
		    $_Class->ExtensionVars['ext_id'] = $this->arrSQL[0]['ext_id'];
		    $_Class->fctSetLang();
		    $_Class->fctExtensionPopup( $this->arrSQL[0]['lin_id'],array( $this->arrSQL[0]['lco_name']=>$this->arrSQL[0]['lco_value'] ) );	    
		    
		    // Relocate
		    if( count( $_Class->arrDocument ) )
		    {
			foreach( $_Class->arrDocument as $key=>$elem )
			    $_Class->arrDocument[$key] = _Files::fctUpdateExtContent( $_Class->arrDocument[$key], $this->Config['path']['extension'] . $this->arrSQL[0]['ext_include'] );
		    }
		    $_Class->arrDocument = _Files::fctUpdateExtDocFiles( $_Class->arrDocument, $this->Config['path']['extension'] . $this->arrSQL[0]['ext_include'] );
		    
		    $this->ExtensionVars = $_Class->ExtensionVars;
		    $this->arrDocument = self::fctTransArrData( $_Class->arrDocument,$this->arrDocument );
		}
	    }
	    else if( $intExtID > 0 )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_id = '". $intExtID ."'");
		
		if( count( $this->arrSQL ) )
		{
		    $_SESSION['easevars']['extension_popup']['ext_id'] = $this->arrSQL[0]['ext_id'];

		    // Extension
		    $strClassName = _Extensions::fctExtensionClass( $this->arrSQL[0]['ext_name'] );
		    $_Class = new $strClassName;
		    $_Class->ExtensionVars['ext_id'] = $this->arrSQL[0]['ext_id'];
		    $_Class->fctSetLang();
		    $_Class->fctExtensionPopup(false,array($strName=>$strValue));
		    
		    // Relocate
		    if( count( $_Class->arrDocument['body'] ) )
		    {
			foreach( $_Class->arrDocument['body'] as $key=>$elem )
			{			
			    $_Class->arrDocument['body'][$key] = _Files::fctUpdateExtContent( $_Class->arrDocument['body'][$key], $this->Config['path']['extension'] . $this->arrSQL[0]['ext_include'] );
			}
		    }
		    $_Class->arrDocument = _Files::fctUpdateExtDocFiles( $_Class->arrDocument, $this->Config['path']['extension'] . $this->arrSQL[0]['ext_include'] );
		    
		    $this->ExtensionVars = $_Class->ExtensionVars;
		    $this->arrDocument = self::fctTransArrData( $_Class->arrDocument,$this->arrDocument );
		}
	    }
	    
	    $this->arrDocument['php_include']['ease_basic_globals'] = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/globals/includes.inc.php";
	    
    	    self::fctCreateFile( $this->Config['path']['cms'] . $this->Config['path']['project']."/extension-popup-". $_SESSION['easevars']['user_id'] .".php", self::fctCreateDocument() );
	    
	    // Redirect
	    $this->fctURLRedirect( $this->Config['http']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'].$this->Config['path']['project']."/extension-popup-". $_SESSION['easevars']['user_id'] .".php",true );
	}
	
	// Toolbar
	public function fctRenderTools()
	{
	    
	    // HTML
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'HTMLVersion'" );
	    $this->arrDocument['html'][0] = self::fctLoadFile( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-basic/". $this->arrSQL[0]['set_value'] .".tmpl" );

	    /* Read-Template*/
		// Theme-Paths
		$strPathTemplate = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['templates'];
		$strPathTheme = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'];
		$strPathCSS = $strPathTheme.$this->Config['path']['theme']['css'];
		$strPathJS = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['libs'];
		
		// Read File
		if( file_exists( $strPathTemplate."/toolbox.tmpl" ) )
		{
		    // Theme
		    $this->arrDocument['css_include'][] = $strPathCSS ."/reset.css";
		    $this->arrDocument['css_include'][] = $strPathCSS ."/basic.css";
		    $this->arrDocument['css_include'][] = $strPathCSS ."/toolbox.css";
		    $this->arrDocument['css_include'][] = $strPathCSS ."/extension.css";
		    
		    // JQuery
			// CSS
			$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-CSS'" );
			if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['css_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];
		    
			// JS
			$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-JS'" );
			if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['js_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];
			
		    // JQuery-UI
			// CSS
			$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-UI-CSS'" );
			if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['css_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];
		    
			// JS
			$this->fctQuery( "SELECT set_value FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'JQuery-UI-JS'" );
			if( $this->arrSQL[0]['set_value'] ) $this->arrDocument['js_include'][] = $strPathJS.$this->arrSQL[0]['set_value'];
			
		    // Templates
		    $strBasicC = self::fctLoadFile( $strPathTemplate."/toolbox.tmpl" );
		    $strSearchC = self::fctLoadFile( $strPathTemplate."/toolbox-search.tmpl" );
		    $strButtonC = self::fctLoadFile( $strPathTemplate."/toolbox-button.tmpl" );
		    $strMenuC = self::fctLoadFile( $strPathTemplate."/toolbox-menu.tmpl" );
		    $strItemC = self::fctLoadFile( $strPathTemplate."/toolbox-item.tmpl" );
		    $strActionC = self::fctLoadFile( $strPathTemplate."/toolbox-action.tmpl" );
		    
		    // Sub-Parts
			// Menu
			$strMenu = self::fctGetToolbarMenu( $strMenuC );

			// Items
			$_GF = new _GlobalFunctions;
			$_GF->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."level ORDER BY lvl_order" );

			if( count( $_GF->arrSQL ) )
			{
			    foreach( $_GF->arrSQL as $arrLvL )
			    {

				if( $arrLvL['lvl_id'] == $this->EaseVars['level'] )
				    $strItem .= '<div class="easeLvl Active">';
				else
				    $strItem .= '<div class="easeLvl">';
				
				unset( $arrButtonsSort );

				// Get all Extensions from this Level
				$this->fctQuery( "SELECT ext_id, ext_name FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_active = '1'" );
				if( count( $this->arrSQL ) )
				{
				    foreach( $this->arrSQL as $arrExtension )
				    {
					
					// Start Extension-Class
					$strClassName = _Extensions::fctExtensionClass( $arrExtension['ext_name'] );
					$_Class = new $strClassName;

					// Give all Attributes to Extension
					$_Class->EaseVars = $this->EaseVars;
					$_Class->ExtensionVars['ext_id'] = $arrExtension['ext_id'];
					$_Class->ExtensionVars['level'] = $arrLvL['lvl_id'];
					
					$_Class->fctSetLang();
					
					$arrButtons = $_Class->fctGetToolButtons( $arrLvL['lvl_id'] );

					if( count( $arrButtons ) )
					{
					    foreach( $arrButtons as $arrButtons2 )
					    {
						$arrButtons2['groupname'] = $_Class->fctReplaceLang( $arrButtons2['groupname'] );
						$arrButtons2['text'] = $_Class->fctReplaceLang( $arrButtons2['text'] );
						$arrButtons2['description'] = $_Class->fctReplaceLang( $arrButtons2['description'] );
						$arrButtons2['ext_id'] = $arrExtension['ext_id'];
						
						if( $arrButtons2['groupname'] )
						{
						    $arrButtonsSort[$arrButtons2['groupname']][] = $arrButtons2;
						}
						else if( $arrButtons2['headline'] )
						{
						    $arrButtonsSort[$arrButtons2['headline']][] = $arrButtons2;
						}
						else
						    $arrButtonsSort[$arrExtension['ext_name']][] = $arrButtons2;
					    }
					}
				    }
				    
				    foreach( $arrButtonsSort as $strHeadline=>$arrFields )
				    {
					$strItemCopy = $strItemC;
					$strItemCopy = self::fctTagReplace( "groupname",$strHeadline,$strItemCopy );

					$arrTagReplace = $this->fctSetToolbarButtons( $arrFields );

					if( count( $arrTagReplace ) > 0 ) $strItem .= self::fctGetToolbarItem( $strItemCopy,$arrTagReplace );
				    }
				}
				$strItem .= "</div>";
			    }
			}

			// Action
			$strAction = self::fctGetToolbarAction( $strActionC );
		    
		    
		    // Tag-Replaces
		    $strBasicC = self::fctTagReplace( "search",$strSearchC,$strBasicC );
		    $strBasicC = self::fctTagReplace( "button",$strButtonC,$strBasicC );
		    $strBasicC = self::fctTagReplace( "menu",$strMenu,$strBasicC );
		    $strBasicC = self::fctTagReplace( "item",$strItem,$strBasicC );
		    $strBasicC = self::fctTagReplace( "action",$strAction,$strBasicC );
		    $strBasicC = self::fctTagReplace( "logout", $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ,$strBasicC );
		    
		    // Language
		    $_L = new _Language;
		    $this->arrDocument['js_language']['EaseToolbarLangTxt'] = $_L->fctSetJSLangTxT( 'Toolbar' );
		    $strBasicC = $_L->fctReplaceLang( $strBasicC );
		    
		    $this->arrDocument['body'][] = _Files::fctUpdateExtContent( $strBasicC, $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] );
		    //$this->arrDocument['body'][] = $strBasicC;
		    
		    // Basic-Function
		    $this->arrDocument['js_include'][] = $strPathJS."/main-functions.js";
		    
		    // Dragger
		    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] ."/page.css";
		    $this->arrDocument['body'][] = '<div class="easeDrag"></div>';
		    
		    // Main-Elements
		    $this->arrDocument['body'][] = _Files::fctUpdateExtContent( $_L->fctReplaceLang( self::fctLoadFile( $strPathTemplate."/main.tmpl" ) ), $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] );

		    $this->arrDocument = _Files::fctUpdateExtDocFiles( $this->arrDocument, $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] );

		}

	    echo self::fctCreateDocument();
	}
	
	// Set Menu
	private function fctGetToolbarMenu( $strTemplate )
	{
	    unset( $this->arrTags, $this->arrBlocks );
	    
	    self::fctGetTags( $strTemplate );					// Create Array $this->arrTags with all Tags
	    self::fctGetBlocks( $strTemplate );					// Create Array $this->arrBlocks width all Blogs
	    self::fctDelBlockInTagArr();					// Delete all Block-Tags in $this->arrTags
	    
	    //print_r( $this->arrTags );
	    //print_r( $this->arrBlocks );
	    
	    // Replace Tags
		// Values
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."level ORDER BY lvl_order" );
		foreach( $this->arrSQL as $i=>$arrResult )
		{
		    $arrTagReplace[$i]['id'] = $arrResult['lvl_id'];
		    $arrTagReplace[$i]['href'] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . "/action.php?action=level&lvl=".$arrResult['lvl_id'];
		    $arrTagReplace[$i]['target'] = "easeMain";
		    $arrTagReplace[$i]['text'] = "{".$arrResult['lvl_name']."}";
		    if( $this->EaseVars['level'] == $arrResult['lvl_id'] )
			$arrTagReplace[$i]['active'] = "Active";
		}
	    
		// Replace
		$strTemplate = self::fctBlockReplace( $strTemplate, $arrTagReplace );
	    
	    return $strTemplate;
	}
	
	// Set Items
	private function fctGetToolbarItem( $strTemplate, $arrTagReplace ) 
	{
	    unset( $this->arrTags, $this->arrBlocks );
	    
	    self::fctGetTags( $strTemplate );					// Create Array $this->arrTags with all Tags
	    self::fctGetBlocks( $strTemplate );					// Create Array $this->arrBlocks width all Blogs
	    self::fctDelBlockInTagArr();					// Delete all Block-Tags in $this->arrTags
	    
	    //print_r( $this->arrTags );
	    //print_r( $this->arrBlocks );
	    
	    // Replace Tags
	    $strTemplate = self::fctBlockReplace( $strTemplate, $arrTagReplace );
	    
	    return $strTemplate;
	}
	
	// Set Action
	private function fctGetToolbarAction( $strTemplate )
	{
	    unset( $this->arrTags, $this->arrBlocks );
	    
	    self::fctGetTags( $strTemplate );					// Create Array $this->arrTags with all Tags
	    self::fctGetBlocks( $strTemplate );					// Create Array $this->arrBlocks width all Blogs
	    self::fctDelBlockInTagArr();					// Delete all Block-Tags in $this->arrTags
	    
	    //print_r( $this->arrTags );
	    //print_r( $this->arrBlocks );
            
            $i = 0;
	    
	    // Replace Tags
		// Values
		$arrTagReplace[$i]['href'] = "javascript:;";
		$arrTagReplace[$i]['onclick'] = "fctCheckMainFrame('Edit');";
		$arrTagReplace[$i]['text'] = "{EditMode}";
		$arrTagReplace[$i]['liclass'] = "ButtonEdit";
		$arrTagReplace[$i]['aclass'] = "Active";
		
		$arrTagReplace[++$i]['href'] = "javascript:;";
		$arrTagReplace[$i]['onclick'] = "fctCheckMainFrame('Move');";
		$arrTagReplace[$i]['text'] = "{MoveMode}";
		$arrTagReplace[$i]['liclass'] = "ButtonMove";
		
		$arrTagReplace[++$i]['href'] = "javascript:;";
		$arrTagReplace[$i]['onclick'] = "fctCheckMainFrame('Join');";
		$arrTagReplace[$i]['text'] = "{JoinMode}";
		$arrTagReplace[$i]['liclass'] = "ButtonJoin";
				
		$arrTagReplace[++$i]['href'] = "javascript:;";
		$arrTagReplace[$i]['onclick'] = "fctCheckMainFrame('Copy');";
		$arrTagReplace[$i]['text'] = "{CopyMode}";
		$arrTagReplace[$i]['liclass'] = "ButtonCopy";
		
		$arrTagReplace[++$i]['href'] = "javascript:;";
		$arrTagReplace[$i]['onclick'] = "fctCheckMainFrame('Delete');";
		$arrTagReplace[$i]['text'] = "{DeleteMode}";
		$arrTagReplace[$i]['liclass'] = "ButtonDelete";
		
		if( _User::fctIsUserAdmin() )
		{
		    $arrTagReplace[++$i]['href'] = "javascript:;";
		    $arrTagReplace[$i]['onclick'] = "fctCheckMainFrame('LinkInfo');";
		    $arrTagReplace[$i]['text'] = "{LinkInfoMode}";
		    $arrTagReplace[$i]['liclass'] = "ButtonLinkInfo";
		}
	    
		// Replace
		$strTemplate = self::fctBlockReplace( $strTemplate, $arrTagReplace );
	    
	    return $strTemplate;
	}
	
	public function fctRenderLinks( $intDocID, $intParentLinID, $arrParentTags=array(),$boolDrop=true,$intLinID=false )
	{    
                       
	    // Classes
	    $_L = new _GlobalFunctions;

	    // Vars
	    $intI = 0;
	    $intOrder = 0;
	    $strLinName = false;
	    $arrContent = array();
	    
	    if( $intDocID ) $strWhere .= " AND lin_doc_id = '". $intDocID ."'";
            
            // Find only links for replacable fields
            $arrParentTagsClear = $arrParentTags;
            unset( $arrParentTagsClear['edit'] );
            if( count( $arrParentTagsClear ) )
            {
                $strWhere .= " AND (";
                foreach( $arrParentTagsClear as $strKeyTag=>$arrTagAttributes )
                {
                    $strWhere .= $strOr . "lin_name = '". $strKeyTag ."'";
                    $strOr = " OR ";
                }
                $strWhere .= ")";
            }
	    
	    // Sort Parent-Attributes
	    if( count( $arrParentTags ) )
	    {
		foreach( $arrParentTags as $elem )
		{
		    $arrParentAttr[$elem['name']] = $elem['attribute'];
		}
		unset( $arrParentTags );
	    }

	    // Get one Link with Childs
	    if( $intLinID )
		$_L->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."extension ON lin_ext_id = ext_id WHERE lin_id = '". $intLinID ."'" );
	    // Get Content from this Link/Extension
	    else
		$_L->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."extension ON lin_ext_id = ext_id WHERE lin_lvl_id <= '". $this->EaseVars['level'] ."' AND lin_parent = '". $intParentLinID ."' ". $strWhere ." ORDER BY lin_name, lin_order" );
	    if( count( $_L->arrSQL ) > 0 )
	    {
                
                // Count Entries
                foreach( $_L->arrSQL as $arrLink )
		{
                    $arrTagCount[$arrLink['lin_name']]++;
                }
                
		foreach( $_L->arrSQL as $arrLink )
		{
                    
                    if( !$arrParentAttr[$arrLink['lin_name']]['max_elements'] ) 
                    {
                        $arrParentAttr[$arrLink['lin_name']]['max_elements'] = 99;
                    }
		    
		    if( !$arrParentAttr[$arrLink['lin_name']]['mode'] || ($arrParentAttr[$arrLink['lin_name']]['mode'] == 'generate' && $this->EaseVars['generatemode'] == true ) || ( $arrParentAttr[$arrLink['lin_name']]['mode'] == 'cms' && $this->EaseVars['generatemode'] == false ) )
		    {
		    
			// Related Link
			if( $arrLink['lin_relation'] > 0 )
			{

			    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $arrLink['lin_relation'] ."'" );
			    if( count( $this->arrSQL ) == 1 )
			    {

				// If generatemode -> php-include
				if( $this->EaseVars['generatemode'] == true ) 
				{
				    //$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $this->arrSQL[0]['lin_id'] ."'" );
				    //if( count( $this->arrSQL ) > 0 )
				    //{
					//foreach( $this->arrSQL as $arrLinks )
					//{
					    if( $this->boolReturnContentArray == true )
					    {
						_Generate::fctInsertInclude( $arrLink['lin_relation'] );
						$arrContent[$arrLink['lin_name']][$intI] .= "<?php if(file_exists( ". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_relation'] .".php )) include( ". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_relation'] .".php); ?>";
						$intI++;
					    }
					    else
					    {
						_Generate::fctInsertInclude( $arrLink['lin_relation'] );
						$arrContent[$arrLink['lin_name']] .= "<?php if(file_exists( '". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_relation'] .".php' )) include( '". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_relation'] .".php' ); ?>";
					    }
					//}
				    //}
                                            
                                    // Transport Includes/Formats
                                    $_PD = new _ParseDoc();
                                    $_PD->fctRenderLinks( $this->arrSQL[0]['lin_doc_id'], false,array(),false,$this->arrSQL[0]['lin_id'] );
                                    $this->arrDocument = $this->fctTransArrData( $_PD->arrDocument, $this->arrDocument,array('body') );
                                            
				}
				else
				{
				    
				    $arrRelation = $this->fctRenderLinks( $this->arrSQL[0]['lin_doc_id'], false,array(),false,$this->arrSQL[0]['lin_id'] );
				    if( count( $arrRelation ) )
				    {
                                        
                                        if( $arrParentAttr[$strLinName]['relation'] )
                                        {
                                            $arrEdit['edit']['attribute']['edit'] = 'false';
                                            $arrEdit['edit']['attribute']['delete'] = 'false';
                                            $arrEdit['edit']['attribute']['move'] = 'false';
                                            $arrEdit['edit']['attribute']['join'] = 'false';
                                            $arrEdit['edit']['attribute']['copy'] = 'false';
                                        }
                                        else
                                        {
                                            $arrEdit['edit']['attribute']['edit'] = 'false';
                                            $arrEdit['edit']['attribute']['delete'] = 'true';
                                            $arrEdit['edit']['attribute']['move'] = 'true';
                                            $arrEdit['edit']['attribute']['join'] = 'false';
                                            $arrEdit['edit']['attribute']['copy'] = 'true';
                                        }

					foreach( $arrRelation as $strContent )
					{
					    // Return each element in an Array
					    if( $this->boolReturnContentArray == true )
					    {
						// Dropbox (Before)
                                                if( !$arrParentAttr[$strLinName]['relation'] && $this->EaseVars['generatemode'] == false && $this->boolNoEditmode == false && !$arrContent[$arrLink['lin_name']] && $arrParentAttr[$arrLink['lin_name']]['drop'] != 'false' && $arrTagCount[$arrLink['lin_name']] < $arrParentAttr[$arrLink['lin_name']]['max_elements'] )
                                                {
                                                    $arrContent[$arrLink['lin_name']][$intI] = self::fctGetDropBox( $arrLink['lin_name'],$arrLink['lin_parent'],($intOrder++) );
                                                }

						if( $this->boolNoEditmode == false ) 
						    $arrContent[$arrLink['lin_name']][$intI] .= self::fctGetEditBox( '<div class="easeRelated">'.$strContent."</div>",$arrLink,$arrEdit,true );
						else
						    $arrContent[$arrLink['lin_name']][$intI] .= $strContent;

						// Dropbox (After)
                                                if( !$arrParentAttr[$strLinName]['relation'] &&  $this->EaseVars['generatemode'] == false && $this->boolNoEditmode == false && $arrParentAttr[$arrLink['lin_name']]['drop'] != 'false' && $arrTagCount[$arrLink['lin_name']] < $arrParentAttr[$arrLink['lin_name']]['max_elements'] )
                                                {
                                                    $arrContent[$arrLink['lin_name']][$intI] .= self::fctGetDropBox( $arrLink['lin_name'],$arrLink['lin_parent'],($intOrder++) );
                                                }

						$intI++;
					    }
					    else    // Return the Content in Array
					    {
						// Dropbox (Before)
                                                if( !$arrParentAttr[$strLinName]['relation'] && $this->EaseVars['generatemode'] == false && $this->boolNoEditmode == false && !$arrContent[$arrLink['lin_name']] && $arrParentAttr[$arrLink['lin_name']]['drop'] != 'false' && $arrTagCount[$arrLink['lin_name']] < $arrParentAttr[$arrLink['lin_name']]['max_elements'] )
                                                {
                                                    $arrContent[$arrLink['lin_name']] = self::fctGetDropBox( $arrLink['lin_name'],$arrLink['lin_parent'],($intOrder++) );
                                                }

						if( $this->boolNoEditmode == false ) 
						    $arrContent[$arrLink['lin_name']] .= self::fctGetEditBox( '<div class="easeRelated">'.$strContent."</div>",$arrLink,$arrEdit,true );
						else
						    $arrContent[$arrLink['lin_name']] .= $strContent;

						// Dropbox (After)
                                                if( !$arrParentAttr[$strLinName]['relation'] && $this->EaseVars['generatemode'] == false && $this->boolNoEditmode == false && $arrParentAttr[$arrLink['lin_name']]['drop'] != 'false' && $arrTagCount[$arrLink['lin_name']] < $arrParentAttr[$arrLink['lin_name']]['max_elements'] )
                                                {
                                                    $arrContent[$arrLink['lin_name']] .= self::fctGetDropBox( $arrLink['lin_name'],$arrLink['lin_parent'],($intOrder++) );
                                                }
					    }
					}
				    }
				}
			    }
			    else
			    {
				$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $arrLink['lin_id'] ."'" );
			    }
			}
			// Normal Link
			else if ( $arrLink['ext_active'] == '1' )
			{

			    // php-include?
			    if( $arrParentAttr[$arrLink['lin_name']]['include'] && $this->EaseVars['generatemode'] == true )
			    {
				if( $this->boolReturnContentArray == true )
				{
				    if( $arrParentAttr[$strLinName]['wrapstart'] ) $arrContent[$strLinName][$intI] .= $arrParentAttr[$strLinName]['wrapstart'];

				    _Generate::fctInsertInclude( $arrLink['lin_id'] );
				    $arrContent[$arrLink['lin_name']][$intI] .= "<?php if(file_exists( ". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_id'] .".php )) include( ". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_id'] .".php); ?>";

				    if( $arrParentAttr[$strLinName]['wrapend'] )$arrContent[$strLinName][$intI] .= $arrParentAttr[$strLinName]['wrapend'];
				    $intI++;
				}
				else
				{
				    if( $arrParentAttr[$strLinName]['wrapstart'] ) $arrContent[$strLinName] .= $arrParentAttr[$strLinName]['wrapstart'];

				    _Generate::fctInsertInclude( $arrLink['lin_id'] );
				    $arrContent[$arrLink['lin_name']] .= "<?php if(file_exists( '". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_id'] .".php' )) include( '". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_id'] .".php' ); ?>";

				    if( $arrParentAttr[$strLinName]['wrapend'] )$arrContent[$strLinName] .= $arrParentAttr[$strLinName]['wrapend'];
				}
			    }
			    else
			    {
				unset( $strSubContent );

				if( $arrParentAttr[$arrLink['lin_name']]['drop'] == 'false' )
				    $boolDrop = false;
				else
				    $boolDrop = true;

				// Check if new Element-Tag
				if( $arrLink['lin_name'] !== $strLinName )
				{
				    $intOrder = 0;
				}
				$strLinName = $arrLink['lin_name'];

				// Vars
				$strClassName = _Extensions::fctExtensionClass( $arrLink['ext_name'] );
				$_Class = new $strClassName;

				// Content From Link
				$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link_content WHERE lco_id = '". $arrLink['lin_lco_id'] ."'" );

				// Send Basic-Vars to class
				$_Class->EaseVars = $this->EaseVars;
				$_Class->ExtensionVars['ext_id'] = $arrLink['lin_ext_id'];
				$_Class->ExtensionVars['level'] = $arrLink['lin_lvl_id'];

				$_Class->fctSetLang();

				// Show Link-Info for User
				if( $_SESSION['easevars']['show_link_info'] == true && $this->EaseVars['generatemode'] == false )
				{
				    $strSubContent .= '<div class="easeInfobox">';
					$strSubContent .= '<strong>Link-ID:</strong> '. $arrLink['lin_id'] .'<br />';
					if( $strLinName )
					$strSubContent .= '<strong>Tag-Element:</strong> '. $strLinName .'<br />';
					$strSubContent .= '<strong>Extension-Name:</strong> '. $arrLink['ext_name'];
					$strSubContent .= '<br /><strong>Extension-Pfad:</strong> '. $arrLink['ext_include'];
					if( $this->arrSQL[0]['lco_name'] && $this->arrSQL[0]['lco_value'] )
					$strSubContent .= '<br /><strong>Parameter:</strong> '. $this->arrSQL[0]['lco_name'] .'=>'. $this->arrSQL[0]['lco_value'];
				    $strSubContent .= '</div>';
				}

				// Width: % => px
				if( preg_match( '/([0-9]+)\%/',$arrParentAttr[$arrLink['lin_name']]['width'],$arrTreffer) ){
				    $arrParentAttr[$arrLink['lin_name']]['width'] = floor( ($arrParentAttr['edit']['width']/100)*$arrTreffer[1] );
				}

				// Height: % => px
				if( preg_match( '/([0-9]+)\%/',$arrParentAttr[$arrLink['lin_name']]['height'],$arrTreffer) ){
				    $arrParentAttr[$arrLink['lin_name']]['height'] = floor( ($arrParentAttr['edit']['height']/100)*$arrTreffer[1] );
				}

				// Class-Content
				$strSubContent .= $_Class->fctRenderCode( array( $this->arrSQL[0]['lco_name']=>$this->arrSQL[0]['lco_value'], "ParentAttributes"=>$arrParentAttr[$arrLink['lin_name']],"Link"=>$arrLink ) );

				_LINK::fctSetLinkAttributes( $arrLink['lin_id'],$arrParentAttr[$arrLink['lin_name']] );

				// Relocate - Extension-Files
				$strSubContent = _Files::fctUpdateExtContent( $strSubContent, $this->Config['path']['extension'] . $arrLink['ext_include'] );
				$_Class->arrDocument = _Files::fctUpdateExtDocFiles( $_Class->arrDocument, $this->Config['path']['extension'] . $arrLink['ext_include'] );

				// Find-Tags
				$this->fctGetTags( $strSubContent );
				$arrTagsFind = $this->arrNameTags;

				// Replace URLs
				if( count( $this->arrTags ) )
				{
				    foreach( $this->arrTags as $arrTag )
				    {
					if( $arrTag['name'] == "url" )
					{
					    $arrURL = _Documents::fctGetDocURL( $arrTag['attribute']['id'] );
					    if( $arrTag['attribute']['type'] == "js" )
						$strSubContent = self::fctTagReplace( $arrTag['name'], $arrURL['js'],$strSubContent,$arrTag['tag'] );
					    else
						$strSubContent = self::fctTagReplace( $arrTag['name'], $arrURL['url'],$strSubContent,$arrTag['tag'] );
					}
				    }
				}

				// Get Extension Vars
				$arrLink['ExtensionVars'] = $_Class->ExtensionVars;

				// Transport Parent
				if( $arrParentAttr[$arrLink['lin_name']]['delete'] === false ) $arrTagsFind['edit']['attribute']['delete'] = 'false';
				if( $arrParentAttr[$arrLink['lin_name']]['move'] === false ) $arrTagsFind['edit']['attribute']['move'] = 'false';
				if( $arrParentAttr[$arrLink['lin_name']]['join'] === false ) $arrTagsFind['edit']['attribute']['join'] = 'false';
				if( $arrParentAttr[$arrLink['lin_name']]['copy'] === false ) $arrTagsFind['edit']['attribute']['copy'] = 'false';

				// If Link is under Join-Link
				if( $intLinID )
				{
				    $arrTagsFind['edit']['attribute']['delete'] = 'false';
				    $arrTagsFind['edit']['attribute']['move'] = 'false';
				    $arrTagsFind['edit']['attribute']['join'] = 'false';
				    $arrTagsFind['edit']['attribute']['copy'] = 'false';
				    $boolNoEditTag = true;
				}
				
				if( $strSubContent && $this->boolNoEditmode == false && $this->EaseVars['generatemode'] == false ) 
				    $strSubContent = self::fctGetEditBox( $strSubContent,$arrLink,$arrTagsFind,$boolDrop,$boolNoEditTag );

				$strSubContent = $this->fctTagReplace( "edit", "" ,$strSubContent );

				// Get Basic Vars
				//$this->EaseVars = $_Class->EaseVars;

				// Insert Document Parameter
                                $this->arrDocument = $this->fctTransArrData( $_Class->arrDocument, $this->arrDocument );

				unset($arrExtensionReplace,$arrReplaceTags,$arrReplacedTags);

				// Parent-Attributes -> Child-Attributes
				$arrDoNotTransport = array( "drop","extension","param_name","param_value","edit","wrapstart","wrapend","include","mode","max_elements" );
				if( count( $arrTagsFind ) )
				{
				    foreach( $arrTagsFind as $keyChild=>$elemChild )
				    {
					if( count( $arrParentAttr[$arrLink['lin_name']] ) )
					{
					    foreach( $arrParentAttr[$arrLink['lin_name']] as $keyParent=>$elemParent )
					    {

						if( !$elemChild['attribute'][$keyParent] && !in_array( $keyParent,$arrDoNotTransport ) )
						{
						    $arrTagsFind[$keyChild]['attribute'][$keyParent] = $elemParent;
						}
					    }
					}
				    }
				}

				// Child Extensions
				_Link::fctUpdateLinkChilds( $arrLink,$arrTagsFind );
				$arrExtensionReplace = $this->fctRenderLinks( $intDocID, $arrLink['lin_id'], $arrTagsFind );

				// Create Replace Array with CMS-Anker
				if( count( $arrExtensionReplace ) )
				{
				    foreach( $arrExtensionReplace as $key=>$elem )
				    {
					// Return each element of Content in an Array
					if( $this->boolReturnContentArray == true )
					{
					    unset( $strElem );
					    if( count( $elem ) )
					    {
						foreach( $elem as $elem2 )
						    $strElem .= $elem2;
					    }
					    $arrReplaceTags[$key] .= $strElem."\n";
					}
					else    // Return the Content in Array
					{
					    $arrReplaceTags[$key] .= $elem."\n";
					}
				    }
				}

				// Replace
				if( count( $arrReplaceTags ) )
				{
				    foreach( $arrReplaceTags as $key=>$elem )
				    {			
					$strSubContent = self::fctTagReplace( $key,$elem."\n",$strSubContent,$arrTagsFind[$key]['tag'] );
					$arrReplacedTags[] = $key;
				    }
				}

				// Find Tags & Clear
				if( count( $arrTagsFind ) > 0 )
				{
				    foreach( $arrTagsFind as $arrTag )
				    {

					if( !in_array( $arrTag['name'], $arrReplacedTags ) && $arrTag['name'] != "edit" && $arrTag['name'] != "url" )
					{
					    // Check if no content in other Lvl for this link
					    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $arrLink['lin_id'] ."' AND lin_name = '". $arrTag['name'] ."'" );
                                            if( $this->EaseVars['generatemode'] == false && $this->boolNoEditmode == false && $arrLink['lin_lvl_id'] <= ($this->EaseVars['level']+1) && count( $this->arrSQL ) == 0 && $arrTag['attribute']['drop'] != 'false' && $arrTagCount[$arrLink['lin_name']] < $arrParentAttr[$arrLink['lin_name']]['max_elements'] )
						$strSubContent = self::fctTagReplace( $arrTag['name'],self::fctGetDropBox( $arrTag['name'],$arrLink['lin_id'] ),$strSubContent,$arrTag['tag'] );
					    else
						$strSubContent = self::fctTagReplace( $arrTag['name'],"",$strSubContent,$arrTag['tag'] );
					}
				    }
				}

				// Time
				if( $this->EaseVars['generatemode'] == true )
				{
				    if( $arrLink['lin_startdate'] && $arrLink['lin_startdate'] > 0 )
					$strSubContent = "<?php if( time() > \"". $arrLink['lin_startdate'] ."\" ) { ?>".$strSubContent."<?php } ?>";

				    if( $arrLink['lin_enddate'] && $arrLink['lin_enddate'] > 0 )
					$strSubContent = "<?php if( time() < \"". $arrLink['lin_enddate'] ."\" ) { ?>".$strSubContent."<?php } ?>";
				}

				// Return each element in an Array
				if( $this->boolReturnContentArray == true )
				{
				    // Set Content Array
					// Wrap Before
					if( $arrParentAttr[$strLinName]['wrapstart'] )$arrContent[$strLinName][$intI] .= $arrParentAttr[$strLinName]['wrapstart'];

					// Dropbox (Before)
					if( $this->EaseVars['generatemode'] == false && $this->boolNoEditmode == false && $arrParentAttr[$arrLink['lin_name']]['drop'] != 'false' && !$arrContent[$strLinName] && $intLinID == false && $arrTagCount[$arrLink['lin_name']] < $arrParentAttr[$arrLink['lin_name']]['max_elements'] )
					{
					    $arrContent[$strLinName][$intI] = self::fctGetDropBox( $strLinName,$intParentLinID,($intOrder++) );
					}

					// Content
					if( $this->EaseVars['generatemode'] == true && $arrTagsFind[$strLinName]['include'] )
					{
					    _Generate::fctInsertInclude( $arrLink['lin_id'] );
					    $arrContent[$strLinName][] .= "<?php if(file_exists( '". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_id'].".php' )) include( '". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_id'].".php' ); ?>";
					}
					else
					{
					    $arrContent[$strLinName][] .= $strSubContent;
					}

					// Dropbox (After)
					if( $this->EaseVars['generatemode'] == false && $this->boolNoEditmode == false && $arrParentAttr[$strLinName]['drop'] != 'false' && $intLinID == false && $arrTagCount[$arrLink['lin_name']] < $arrParentAttr[$arrLink['lin_name']]['max_elements'] )
					{
					    $arrContent[$strLinName][$intI] .= self::fctGetDropBox( $strLinName,$intParentLinID,($intOrder++) );
					}

					// Wrap After
					if( $arrParentAttr[$strLinName]['wrapend'] )$arrContent[$strLinName][$intI] .= $arrParentAttr[$strLinName]['wrapend'];

					$intI++;
				}
				else    // Return the Content in Array
				{
					// Wrap Before
					if( $arrParentAttr[$strLinName]['wrapstart'] )$arrContent[$strLinName] .= $arrParentAttr[$strLinName]['wrapstart'];

					// Dropbox (Before)
					if( $this->EaseVars['generatemode'] == false && $this->boolNoEditmode == false && $arrParentAttr[$strLinName]['drop'] != 'false' && !$arrContent[$strLinName] && $intLinID == false && $arrTagCount[$arrLink['lin_name']] < $arrParentAttr[$arrLink['lin_name']]['max_elements'] )
					{
					    if( $intParentLinID != 0 )
					    $arrContent[$strLinName] = self::fctGetDropBox( $strLinName,$intParentLinID,($intOrder++) );
					}

					// Content
					if( $this->EaseVars['generatemode'] == true && $arrParentAttr[$strLinName]['include'] )
					{
					    _Generate::fctInsertInclude( $arrLink['lin_id'] );
					    $arrContent[$strLinName] .= "<?php if(file_exists( '". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_id'].".php' )) include( '". $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] . $this->Config['path']['extern-includes'] ."/". $arrLink['lin_id'].".php' ); ?>";
					}
					else
					{
					    $arrContent[$strLinName] .= $strSubContent;
					}

					// Dropbox (After)
					if( $this->EaseVars['generatemode'] == false && $this->boolNoEditmode == false && $arrParentAttr[$strLinName]['drop'] != 'false' && $intLinID == false && $arrTagCount[$arrLink['lin_name']] < $arrParentAttr[$arrLink['lin_name']]['max_elements'] )
					{
					    if( $intParentLinID != 0 )
					    $arrContent[$strLinName] .= self::fctGetDropBox( $strLinName,$intParentLinID,($intOrder++) );
					}

					// Wrap After
					if( $arrParentAttr[$strLinName]['wrapend'] )$arrContent[$strLinName] .= $arrParentAttr[$strLinName]['wrapend'];
				}
			    }
			}
		    }
		}
	    }
	    
	    if( $intParentLinID == 0 && $this->EaseVars['generatemode'] == false && $this->boolDropBoxSet == false && $intLinID == false )
	    {
		//$arrContent[] = self::fctGetDropBox();
	    }
	    
	    return $arrContent;
	}
	
	private function fctGetDropBox( $strTag='',$intLinID=0,$intOrder=0 )
	{
	    if( $this->EaseVars['generatemode'] == false )
	    {
		$this->boolDropBoxSet = true;
		$strDropField = '<div class="easeDrop" id="drop-'. $intLinID .'-'. $intOrder .'-'. $strTag .'"></div>';
		$strDropField.= '<div class="easeCopyDrop" rel="'. $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] .'/action.php?action=copy&lin_parent='.  $intLinID .'&lin_order='. $intOrder .'&lin_name='. $strTag .'"></div>';
		$strDropField.= '<div class="easeJoinDrop" rel="'. $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] .'/action.php?action=join&lin_parent='.  $intLinID .'&lin_order='. $intOrder .'&lin_name='. $strTag .'"></div>';
	    }
	    return $strDropField;
	}
	
	public function fctGetEditBox( $strContent, $arrLink=array(), $arrTagsFind=array(),$boolDrop=true, $boolNoEditTag=false )
	{
	    
	    // Set Vars
	    if( $arrTagsFind['edit']['attribute']['edit'] === 'false' )			$boolEdit = false; else $boolEdit = true;
	    if( $arrTagsFind['edit']['attribute']['editopenwindow'] === 'true' )	$boolEditOpenWindow = true; else $boolEditOpenWindow = false;
	    if( $arrTagsFind['edit']['attribute']['editopenwindowwidth'] )		$intW = intval( $arrTagsFind['edit']['attribute']['editopenwindowwidth'] );
	    if( $arrTagsFind['edit']['attribute']['editopenwindowheight'] )		$intH = intval( $arrTagsFind['edit']['attribute']['editopenwindowheight'] );
	    if( $arrTagsFind['edit']['attribute']['delete'] === 'false' )		$boolDelete = false; else $boolDelete = true;
	    if( $arrTagsFind['edit']['attribute']['move'] === 'false' || !$boolDrop )	$boolMove = false; else $boolMove = true;
	    if( $arrTagsFind['edit']['attribute']['join'] === 'false' || !$boolDrop )	$boolJoin = false; else $boolJoin = true;
	    if( $arrTagsFind['edit']['attribute']['copy'] === 'false' || !$boolDrop )	$boolCopy = false; else $boolCopy = true;
	    
		// EditBox
		$strBox = '<div class="easeEditBox" id="Editbox-'. $arrLink['lin_id'] .'" rel="P-'. $arrLink['lin_parent'] .'-'. $arrLink['lin_order'] .'-'. $arrLink['lin_name'] .'">';
	    
		// Move
		if( $boolMove )
		    $strBox.= '<div class="easeMove" id="Move-'. $arrLink['lin_id'] .'" rel="'. $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] .'/action.php?action=move&move_id='. $arrLink['lin_id'] .'" rev="'. $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] .'/dragger.php?lin_id='. $arrLink['lin_id'] .'"></div>';
		else
		    $strBox.= '<div class="easeMove easeNotMove" id="Move-'. $arrLink['lin_id'] .'" rel="'. $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] .'/action.php?action=move&move_id='. $arrLink['lin_id'] .'" rev="'. $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] .'/dragger.php?lin_id='. $arrLink['lin_id'] .'"></div>';
		
		// Join
		if( $boolJoin )
		    $strBox.= '<div class="easeJoin" id="Join-'. $arrLink['lin_id'] .'"></div>';

		// Copy
		if( $boolCopy )		
		    $strBox.= '<div class="easeCopy" id="Copy-'. $arrLink['lin_id'] .'"></div>';
		
		// Delete
		if( $boolDelete )
		    $strBox.= '<div class="easeDelete" id="Delete-'. $arrLink['lin_id'] .'" rel="'. $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] .'/action.php?action=delete&delete_id='. $arrLink['lin_id'] .'"></div>';		
		
		if( $boolEdit )
		{
		    if( $boolEditOpenWindow )
		    {
			if( !$intW ) $intW = 0;
			if( !$intH ) $intH = 0;
			
			$strName = '';
			$strValue = '';
			
			if( $arrLink['lin_lco_id'] )
			{
			    $_GF = new _GlobalFunctions();
			    $_GF->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link_content WHERE lco_id = '". $arrLink['lin_lco_id'] ."'" );
			    if( count( $_GF->arrSQL ) == 1 )
			    {
				$strName = $_GF->arrSQL[0]['lco_name'];
				$strValue = $_GF->arrSQL[0]['lco_value'];
			    }
			}
			
			$strEdit.= '<div class="easeEdit3" id="Edit-'. $arrLink['lin_ext_id'] .'-'. $strName .'-'. $strValue .'-'. $arrLink['lin_id'] ."-". $intW ."-". $intH .'"></div>';
		    }
		    else
		    {
			$strEdit.= '<div class="easeEdit" id="Edit-'. $arrLink['lin_id'] .'" onclick="'. $arrLink['ExtensionVars']['JSEditStart'] .'"></div>';
			$strEdit.= '<div class="easeEdit2" id="Edit2-'. $arrLink['lin_id'] .'" onclick="'. $arrLink['ExtensionVars']['JSEditEnd'] .'"></div>';
                        
                        $strEdit4= '<div class="easeEdit2" id="Edit4-'. $arrLink['lin_id'] .'" onclick="'. $arrLink['ExtensionVars']['JSEditEnd'] .'"></div>';
		    }
		}

		if( count( $arrTagsFind['edit'] ) && $boolNoEditTag == false )
		    $strContent = $this->fctTagReplace( "edit", $strEdit ,$strContent );
		else
		    $strBox .= $strEdit;
		
		$strContent = $strBox . $strContent . $strEdit4 .'</div>';
	    
	    $strContent = $this->fctTagReplace( "edit", "" ,$strContent );

	    return $strContent;
	}
	
	// Return Extension-URL
	public function fctGetExtensionLink( $arrParams=array()  )
	{
	    if( !$arrParams['ext_id'] && $arrParams['lin_id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content ON lin_lco_id = lco_id WHERE lin_id = '". $arrParams['lin_id'] ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    $arrParams['ext_id'] = $this->arrSQL[0]['lin_ext_id'];
		    $arrParams['name'] = $this->arrSQL[0]['lco_name'];
		    $arrParams['value'] = $this->arrSQL[0]['lco_value'];
		}		
	    }
	    
	    if( $arrParams['ext_id'] )
	    {
		$arrURL['url'] = "javascript:window.top.fctEaseOpenExtensionPopup( '". $arrParams['ext_id'] ."','". $arrParams['name'] ."','". $arrParams['value'] ."','". $arrParams['lin_id'] ."','". $arrParams['extension_popup_width'] ."','". $arrParams['extension_popup_height'] ."' );";
		$arrURL['js'] = "window.top.fctEaseOpenExtensionPopup( '". $arrParams['ext_id'] ."','". $arrParams['name'] ."','". $arrParams['value'] ."','". $arrParams['lin_id'] ."','". $arrParams['extension_popup_width'] ."','". $arrParams['extension_popup_height'] ."' );";
		return $arrURL;
	    }	
	    
	}
	
	public function fctBlockReplace( $strTemplate, $arrTagReplace )
	{
	    if ( count( $this->arrBlocks ) )
	    {
		// All Blocks
		foreach( $this->arrBlocks as $arrBlock )
		{
		    // Set Block-Item
		    foreach( $arrTagReplace as $arrContents )
		    {
			$strBlockCopy = $arrBlock['content'];

			foreach( $this->arrTags as $arrTag )
			{
			    $arrTagName = explode( " ", substr( substr( $arrTag['tag'],0,strlen( $arrTag['tag'] )-strlen( $this->EaseVars['endtag'] ) ),strlen( $this->EaseVars['starttag'] ) ) );
			    $strBlockCopy = str_replace( $arrTag['tag'], $arrContents[$arrTagName[0]] ,$strBlockCopy );
			}

			$strBlockParsed .= $strBlockCopy;
		    }

		    $strTemplate = str_replace( $arrBlock['tag_start'].$arrBlock['content'].$arrBlock['tag_end'],$strBlockParsed,$strTemplate );
		}
	    }
	    return $strTemplate;
	}
	
	// Delete all Block-Tags in $this->arrTags
	public function fctDelBlockInTagArr()
	{
	    if( count( $this->arrTags ) )
	    {
		foreach( $this->arrTags as $key=>$elem )
		{
		    if ( strpos( $elem['tag'], 'block' ) < 1 )
		    {
			$arrNewTags[] = $elem;
		    }
		}
		$this->arrTags = $arrNewTags;
	    }
	}
	
	// Find all Blocks in $this->arrTags
	// and return Content-String
	public function fctGetBlocks( $strTemplate, $arrInternTags=array() )
	{ 
	    if( count( $arrInternTags ) == 0 ) $arrInternTags = $this->arrTags;

	    if( count( $arrInternTags ) )
	    {
		// Vars
		$intIgnoreEnd = 0;
		for( $i=0; $i<count( $arrInternTags ); $i++ )
		{
		    if( strpos( $arrInternTags[$i]['tag'], 'blockstart' ) == strlen( $this->EaseVars['starttag'] ) )
		    {
			// If first Block
			if( !$intSubStart )
			{
			    $intSubStartPos = $arrInternTags[$i]['position'];
			    $strSubStartPos = $arrInternTags[$i]['tag'];
			}   
			// if next Block
			else
			{
			    $intIgnoreEnd++;
			    self::fctGetBlocks($strTemplate,array_slice( $arrInternTags,$i ));
			}
		    }
		    else if( strpos( $arrInternTags[$i]['tag'], 'blockend' ) == strlen( $this->EaseVars['starttag'] ) )
		    {
			if( $intIgnoreEnd == 0 )
			{
			    $intSubEndPos = $arrInternTags[$i]['position'];
			    $strSubEndPos = $arrInternTags[$i]['tag'];
			    // End For-Loop
			    $i = count( $arrInternTags );
			}
			else
			    $intIgnoreEnd--;
		    }
		}
		
		
		if( $strSubStartPos && $strSubEndPos )
		{
		    $this->arrBlocks[] = array(
						"tag_start" => $strSubStartPos,
						"tag_end" => $strSubEndPos,
						"content"=> substr( $strTemplate, ($intSubStartPos+strlen($strSubStartPos)), ($intSubEndPos-($intSubStartPos+strlen($strSubStartPos)))) 
					);
		}
	    }
	}
	
	// Get all Tags
	public function fctGetTags( $strTemplate )
	{
	    unset( $this->arrTags,$this->arrNameTags );	    
	    self::fctFindTag( $strTemplate,$this->EaseVars['starttag'],$this->EaseVars['endtag'] );
	}
	
	// Find all Tags
	public function fctFindTag( $strTemplate, $strStartTag, $strEndTag, $intAddStartPos=0 )
	{
	    
	    unset( $arrHits, $strAttributes );
	    
	    $intStartPos = strpos( $strTemplate, $strStartTag );
	    if( $intStartPos > -1 )
	    {
		
		// Find: EndTag & next StartTag
		$intEndPos = strpos( $strTemplate, $strEndTag, ($intStartPos+strlen( $strStartTag )) );
		$intStartPos2 = strpos( $strTemplate, $strStartTag, ($intStartPos+strlen( $strStartTag ))  );
		$intStartAttributes = strpos( $strTemplate, " ", $intStartPos );

		if( $intStartPos2 > -1 )
		{
		    while( $intStartPos2 < $intEndPos && $i < 10 )
		    {
			$intEndPos = strpos( $strTemplate, $strEndTag, ($intEndPos+strlen( $strEndTag )) );
			$intStartPos2 = strpos( $strTemplate, $strStartTag, ($intStartPos2+strlen( $strStartTag ))  );
			$i++;
		    }
		}
		
		// Attributes
		$strAttributes = substr( $strTemplate, $intStartAttributes, ($intEndPos-$intStartAttributes) );
		if( strlen( $strAttributes ) > 2 )
		{
		    preg_match_all( "/([\w\-\'\(\)\.\s[:space:]\?\!\"\;\_]+)\=\"([\w\-\'\(\)\.\,\s[:space:]\?\!\"\%\;\:\_\<\>\/\&\[\]]+)\"/", $strAttributes, $arrHits );
		    if( count( $arrHits ) )
		    {
			for( $i=0 ; $i<count( $arrHits[1] ) ; $i++ )
			{
			    $arrAttributes[trim($arrHits[1][$i])] = $arrHits[2][$i];
			}
		    }
		}

		// Save Tags
		$strName = substr( $strTemplate, $intStartPos+strlen( $strStartTag ), ($intStartAttributes-($intStartPos+strlen( $strStartTag ))) );
		$this->arrNameTags[$strName] = array("position"=>($intStartPos+$intAddStartPos),
					"name"=> $strName,
					"tag"=>substr( $strTemplate, $intStartPos, ($intEndPos+strlen( $strEndTag ))-$intStartPos),
					"attribute" => $arrAttributes );
		$this->arrTags[] = $this->arrNameTags[$strName];
		
		// Find Next Tag
		$intNextStartPos = strpos( $strTemplate, $strStartTag, $intEndPos+strlen( $strEndTag )  );
		if( $intNextStartPos > -1 )
		    self::fctFindTag( substr( $strTemplate, ($intEndPos+strlen( $strEndTag )) ), $strStartTag, $strEndTag, (($intEndPos+strlen( $strEndTag ))+$intAddStartPos) );
		
	    }
	}
	
	// Replace <ease:tags \>
	public function fctTagReplace( $strSearch, $strReplace, $strString, $strFullTag=false )
	{
	    if( $strFullTag )
		return str_replace( $strFullTag ,$strReplace,$strString );
	    else
		return preg_replace( "/".  $this->EaseVars['starttag'] . $strSearch ." ([\w\-\'\(\)\.\s[:space:]\?\!\"\%\;\_\=]*)\\". $this->EaseVars['endtag'] ."/i" ,$strReplace,$strString );
	}
	
	// Return Include-Tag
	public function fctGetHTMLLink2File( $strPath )
	{
	    switch( strrchr( $strPath ,".") )
	    {
		case ".php": return 'include("'. $strPath .'");'; break;
		case ".css": return '<link rel="stylesheet" type="text/css" href="'. $strPath ."?". substr( md5(time().$_SERVER['REMOTE_ADDR'].rand(0,99)),0,10 ) .'" />'; break;
		case ".js": return '<script type="text/javascript" src="'. $strPath ."?". substr( md5(time().$_SERVER['REMOTE_ADDR'].rand(0,99)),0,10 ) .'"></script>'; break;
	    }
	}
	
	private function fctSetToolbarButtons( $arrFields )
	{
	    if( count( $arrFields ) )
	    {
		$i=0;
		foreach( $arrFields as $arrParams )
		{
                    if( $arrParams['paramname'] ) $arrParams['name'] = $arrParams['paramname'];
                    if( $arrParams['paramvalue'] ) $arrParams['value'] = $arrParams['paramvalue'];
                    if( $arrParams['description'] ) $arrParams['title'] = $arrParams['description'];
		    if( $arrParams['popup_width'] ) $arrParams['extension_popup_width'] = $arrParams['popup_width'];
		    if( $arrParams['popup_height'] ) $arrParams['extension_popup_height'] = $arrParams['popup_height'];
                    
		    switch( $arrParams['type'] )
		    {
                        case "popup":
			case "extension_popup":
				$arrResult[$i]['type'] = "ExtensionPopup";
				$arrResult[$i]['href'] = "javascript:;";
				$arrResult[$i]['onclick'] = "window.top.fctEaseOpenExtensionPopup( '". $arrParams['ext_id'] ."','". $arrParams['name'] ."','". $arrParams['value'] ."','','". $arrParams['extension_popup_width'] ."','". $arrParams['extension_popup_height'] ."' );";
				$arrResult[$i]['title'] =  $arrParams['title'];
				$arrResult[$i]['button'] =  $arrParams['button'];
				$arrResult[$i]['text'] =  $arrParams['text'];
				break;
                        case "drag":
			case "intern_drag":
				$arrContent = self::fctGetContentOnDoc( $arrParams['ext_id'] );
				$arrResult[$i]['type'] = "Dragger";
				$arrResult[$i]['href'] = "javascript:;";
				$arrResult[$i]['rel'] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/action.php";
				$arrResult[$i]['rel'] .= "?action=intern&drag=1&ext=". $arrParams['ext_id'] ."&name=". $arrParams['name'] ."&value=". $arrParams['value']; 
				$arrResult[$i]['rev'] .= $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/dragger.php"; 
				$arrResult[$i]['rev'] .= "?ext=". $arrParams['ext_id'] ."&name=". $arrParams['name'] ."&value=". $arrParams['value']; 
				$arrResult[$i]['title'] =  $arrParams['title'];
				$arrResult[$i]['button'] =  $arrParams['button'];
				$arrResult[$i]['text'] =  $arrParams['text'];
				break;
			case "click":
			case "intern":
			default:
				$arrContent = self::fctGetContentOnDoc( $arrParams['ext_id'] );
				$arrResult[$i]['href'] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/action.php?action=intern";
				$arrResult[$i]['href'] .= "&ext=". $arrParams['ext_id'] ."&name=". $arrParams['name'] ."&value=". $arrParams['value']; 
				$arrResult[$i]['target'] = "easeMain"; 
				$arrResult[$i]['onclick'] = ""; 
				//if( $arrContent['lco_value'] == $arrParams['value'] ) $arrResult[$i]['active'] = "Active";
				$arrResult[$i]['title'] =  $arrParams['title'];
				$arrResult[$i]['button'] =  $arrParams['button'];
				$arrResult[$i]['text'] =  $arrParams['text'];
				break;
		    }
		    $i++;
		}
		return $arrResult;
	    }
	}
	
	public function fctGetContentOnDoc( $intExtID, $intDocID=false )
	{
	    
	    if( !$intDocID ) $intDocID = $_SESSION['easevars']['document'];
	    
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content ON lin_lco_id = lco_id WHERE lin_parent = '0' AND lin_doc_id = '". $intDocID ."' AND lin_ext_id = '". $intExtID ."'" );
	    if( count( $this->arrSQL ) == 1 ) return $this->arrSQL[0];
	}
	
	// Create a File
	private function fctCreateFile( $strPath, $strContent )
	{
	    $strPathServer = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strPath;
	    $strPathHTTP = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $strPath;
	    
	    $this->fctMkdir( $strPath );

	    $objCreateFile = fopen( $strPathServer, "w+"); 
	    fwrite( $objCreateFile, $strContent ); 
	    fclose( $objCreateFile );
	    
	    return $strPathHTTP;
	}
	
	public function fctMkdir( $strPath,$boolFile=true )
	{
	    // Make Directory
	    $arrPath = explode( "/",$strPath );
	    if( count( $arrPath ) )
	    {
		if( $boolFile == true )
		    unset( $arrPath[(count($arrPath)-1)] );
		$strNewPath = $this->Config['server']['domain'].$this->Config['path']['basic'];
		foreach( $arrPath as $strFolder )
		{
		    if( strlen( $strFolder ) > 0 )
		    {
			$strNewPath .= "/".$strFolder;
			if( !file_exists( $strNewPath ) ) @mkdir( $strNewPath );
		    }
		}
	    }	    
	}
	
	public function fctDragger( $intExtensionID=false,$intLinID=false,$strName=false,$strValue=false, $boolSubReturn=false, $arrParentAttributes=array() )
	{
	    // Classes
	    $_D = new _Documents;
	    $_F = new _Files();
	    
	    // If Extension-Drag
	    if( $intExtensionID )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_id = '". $intExtensionID ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    $strClassName = _Extensions::fctExtensionClass( $this->arrSQL[0]['ext_name'] );
		    $_Class = new $strClassName;
		    $_Class->EaseVars = $this->EaseVars;
		    $_Class->EaseVars['dragmode'] = true;
		    $_Class->ExtensionVars['ext_id'] = $this->arrSQL[0]['ext_id'];
		    $_Class->ExtensionVars['level'] = $this->arrSQL[0]['ext_lvl_id'];
		    $_Class->fctSetLang();
		    $this->arrDocument['html'][] = "<ease:head /><ease:body />";
                    
		    $strCode = $_Class->fctRenderCode( array( $strName=>$strValue,'ParentAttributes'=>$arrParentAttributes ) );
                    
                    unset( $arrParentAttributes['drop'],$arrParentAttributes['include'],$arrParentAttributes['extension'],$arrParentAttributes['param_name'],$arrParentAttributes['param_value']);

		    // Find-Tags
		    $this->fctGetTags( $strCode );
		    
		    // Replace URL-Tags & Hide other
		    if( count( $this->arrTags ) )
		    {
			foreach( $this->arrTags as $arrTag )
			{
                                                       
			    if( $arrTag['name'] == "url" )
			    {
				$arrURL = $_D->fctGetDocURL( $arrTag['attribute']['id'] );
				$strCode = $this->fctTagReplace( $arrTag['name'], $arrURL['url'],$strCode,$arrTag['tag'] );
			    }
			    else if( $arrTag['attribute']['extension'] )
			    {                                
                                foreach( $arrParentAttributes as $strKey=>$strValue )
                                {
                                    if( !$arrTag['attribute'][$strKey] )
                                        $arrTag['attribute'][$strKey] = $strValue;
                                }
                                
				$_PD = new _ParseDoc();
				$strSubElement = $_PD->fctDragger( _Extensions::fctGetExtensionID( $arrTag['attribute']['extension'] ),false,$arrTag['attribute']['param_name'],$arrTag['attribute']['param_value'],true,$arrTag['attribute'] );
				$this->arrDocument = self::fctTransArrData( $_PD->arrDocument,$this->arrDocument );
				$strCode = $this->fctTagReplace( $arrTag['name'], $strSubElement,$strCode,$arrTag['tag'] );
			    }
			    else
				$strCode = $this->fctTagReplace( $arrTag['name'], '',$strCode,$arrTag['tag'] );
			}
		    }

		    $strCode = $_F->fctUpdateExtContent( $strCode, $this->Config['path']['extension'] . $this->arrSQL[0]['ext_include'] );
		    $_Class->arrDocument = $_F->fctUpdateExtDocFiles( $_Class->arrDocument, $this->Config['path']['extension'] . $this->arrSQL[0]['ext_include'] );

		    $this->arrDocument['body'][] = $strCode;

		    // Insert Document Parameter
		    if( count( $_Class->arrDocument ) )
		    {
			foreach( $_Class->arrDocument as $key=>$arrValues ) 
			{
			    if( count( $arrValues ) )
			    {
				foreach( $arrValues as $elem )
				    $this->arrDocument[$key][] = $elem;
			    }
			}
		    }
		    
		    // Sub-Return or Redirect?
		    if( $boolSubReturn == true )
		    {
			$this->arrDocumentTmp = $this->arrDocument;
			unset( $this->arrDocument );
			$this->arrDocument['html'] = $this->arrDocumentTmp['html'];
			$this->arrDocument['body'] = $this->arrDocumentTmp['body'];
			unset( $this->arrDocumentTmp['html'],$this->arrDocumentTmp['head'],$this->arrDocumentTmp['body'] );
			$strContent = $this->fctCreateDocument();
			$this->arrDocument = $this->arrDocumentTmp;
			return $strContent;
		    }
		    else
		    {
			$this->arrDocument['php'][] = '@error_reporting(1);';
			
			// Create Document
			$this->fctCreateFile( $this->Config['path']['cms'] ."/dragger-content.php", $this->fctCreateDocument() );

			// Redirect
			$this->fctURLRedirect( $this->Config['http']['domain'] . $this->Config['path']['basic'] .$this->Config['path']['cms'] ."/dragger-content.php?EASETMP=".md5( $intExtensionID.$_GET['name'].$_GET['value'] ) );
		    }
		}
	    }

	    // If Link-Drag
	    if( $intLinID )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content ON lin_lco_id = lco_id LEFT JOIN ". $this->Config['database']['table_prefix'] ."extension ON lin_ext_id = ext_id WHERE lin_id = '". $inLinID ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    $strClassName = _Extensions::fctExtensionClass( $this->arrSQL[0]['ext_name'] );
		    $_Class = new $strClassName;
		    $_Class->EaseVars = $this->EaseVars;
		    $_Class->ExtensionVars['ext_id'] = $this->arrSQL[0]['ext_id'];
		    $_Class->ExtensionVars['level'] = $this->arrSQL[0]['ext_lvl_id'];
		    $_Class->fctSetLang();
		    $this->arrDocument['html'][] = "<ease:head /><ease:body />";
		    $this->arrDocument['body'][] = $_Class->fctRenderCode( array( $this->arrSQL[0]['lco_name']=>$this->arrSQL[0]['lco_value'] ) );

		    // Insert Document Parameter
		    if( count( $_Class->arrDocument ) )
		    {
			foreach( $_Class->arrDocument as $key=>$arrValues ) 
			{
			    if( count( $arrValues ) )
			    {
				foreach( $arrValues as $elem )
				    $this->arrDocument[$key][] = $elem;
			    }
			}
		    }
		    
		    // Sub-Return or Redirect?
		    if( $boolSubReturn == true )
		    {
			$this->arrDocumentTmp = $this->arrDocument;
			unset( $this->arrDocument );
			$this->arrDocument['html'] = $this->arrDocumentTmp['html'];
			$this->arrDocument['body'] = $this->arrDocumentTmp['body'];
			unset( $this->arrDocumentTmp['html'],$this->arrDocumentTmp['head'],$this->arrDocumentTmp['body'] );
			$strContent = $this->fctCreateDocument();
			$this->arrDocument = $this->arrDocumentTmp;
			return $strContent;
		    }
		    else
		    {
			$this->arrDocument['php'][] = '@error_reporting(1);';
			
			// Create Document
			$this->fctCreateFile( $this->Config['path']['cms'] ."/dragger-content.php", $this->fctCreateDocument() );

			// Redirect
			$this->fctURLRedirect( $this->Config['http']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'] ."/dragger-content.php?EASETMP=".md5( $intLinID ) );
		    }
		}
	    }
	}
	
    }

?>