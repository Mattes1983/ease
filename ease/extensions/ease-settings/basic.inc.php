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

    class EASESettingsBasic extends _Extensions
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
		case "save":	$this->fctSaveEdit(); break;
		default:	$this->fctGetEdit(); break;
	    }
	}
	
	public function fctGetEdit()
	{
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup'] );
	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup'];
	    	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );

	    // Document-Auto-Title
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Document-Auto-Title'" );
    	    $strContent = _ParseDoc::fctTagReplace( "DocumentAutoTitleChecked", ($this->arrSQL[0]['set_value'] == 1 )? "checked":"" ,$strContent );
	    
	    // Document-Auto-Title
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Generate-Mode'" );
    	    $strContent = _ParseDoc::fctTagReplace( "GenerateModeSelected1", ($this->arrSQL[0]['set_value'] == 1 )? "selected":"" ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "GenerateModeSelected2", ($this->arrSQL[0]['set_value'] == 2 )? "selected":"" ,$strContent );

	    // ShowLinkInfo
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'ShowLinkInfo'" );
    	    $strContent = _ParseDoc::fctTagReplace( "ShowLinkInfoChecked", ($this->arrSQL[0]['set_value'] == 1 )? "checked":"" ,$strContent );
	    
	    // Theme
		// Actual Theme
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Theme'" );
		if( $this->arrSQL[0]['set_value'] > 0 ) $intThemeID = $this->arrSQL[0]['set_value'];
		
		// Get All Themes
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."theme" );
		if( count( $this->arrSQL ) )
		{
		    foreach( $this->arrSQL as $arrThemes )
		    {
			if( $arrThemes['the_id'] == $intThemeID )
			    $strSelectOptions .= '<option value="'. $arrThemes['the_id'] .'" selected="selected" >'. $arrThemes['the_name'] .'</option>';
			else
			    $strSelectOptions .= '<option value="'. $arrThemes['the_id'] .'">'. $arrThemes['the_name'] .'</option>';
		    }
		}
		$strContent = _ParseDoc::fctTagReplace( "themeselect", $strSelectOptions ,$strContent );
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='document.EditForm.submit()'>{Save}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Language
	    $this->arrDocument['js_language']['EaseSettingsLangTxt'] = $this->fctSetJSLangTxT( 'EaseSettings' );
	    $strContent = $this->fctReplaceLang( $strContent );
	    
	    $this->arrDocument['body'][]  = $strContent;
	}
		
	// Save Item-Info
	public function fctSaveEdit()
	{
	    // Document-Auto-Title
	    if( $_POST['DocumentAutoTitle'] == 1 )
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."setting SET set_value = '1' WHERE set_name = 'Document-Auto-Title'" );
	    else
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."setting SET set_value = '0' WHERE set_name = 'Document-Auto-Title'" );
	    
	    // Generate-Mode
	    if( $_POST['GenerateMode'] == 1 )
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."setting SET set_value = '1' WHERE set_name = 'Generate-Mode'" );
	    else if( $_POST['GenerateMode'] == 2 )
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."setting SET set_value = '2' WHERE set_name = 'Generate-Mode'" );
	    
	    // Generate-Mode
	    if( $_POST['ShowLinkInfo'] == 1 )
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."setting SET set_value = '1' WHERE set_name = 'ShowLinkInfo'" );
	    else
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."setting SET set_value = '0' WHERE set_name = 'ShowLinkInfo'" );
	    
	    // Theme
	    if( $_POST['Theme'] )
	    {
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."setting SET set_value = '". $_POST['Theme'] ."' WHERE set_name = 'Theme'" );
	    }
	    
	    $this->fctURLRedirect( $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/extension-popup.php" );
	}
	
	public function fctClearName( $strName )
	{
	    // In Array umwandeln
	    for( $i=0 ; $i < strlen( $strName ) ; $i++ )
	    {
		$character = substr( $strName,$i,1 );
		if( preg_match( "/[a-zA-Z0-9\_\-]/",$character ) )
		    $arrName[$i] = $character;
	    }
	    unset( $strName );
	    $strName = implode( "",$arrName );
	    
	    return $strName;
	}

    }

?>
