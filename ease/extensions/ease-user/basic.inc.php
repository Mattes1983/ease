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

    class EASEUserBasic extends _Extensions
    {
        
        public function __construct()
	{
	    parent::__construct();
            
            // Templates
            $this->arrConf['tmpl']['extension-popup'] = "basic/templates/extension-popup.tmpl";
            $this->arrConf['tmpl']['extension-popup-edit-user'] = "basic/templates/extension-popup-edit-user.tmpl";
            $this->arrConf['tmpl']['extension-popup-message'] = "basic/templates/extension-popup-message.tmpl";
            
            // CSS
            $this->arrConf['css']['extension-popup'] = "basic/css/extension-popup.css";
            
            // JS
            $this->arrConf['js']['extension-popup'] = "basic/js/extension-popup.js";
        }
	
	// Optional
	public function fctExtensionPopup( $intLinID,$arrParams=array() )
	{
	    switch( $arrParams['type'] )
	    {
		// User
		case "user": 
		    switch( $_GET['action'] )
		    {
			case "ajax-userlist":	$this->fctUserList(); break;	// Returns the userlist for menu
			case "ajax-edit-user":	$this->fctEditUser(); break;	// Returns editfields for oser
			case "ajax-save-user":	$this->fctSaveUser(); break;	// Save userfields
			case "ajax-delete-user":$this->fctDeleteUser(); break;	// Delete user
			default:		$this->fctShowPage(); break;	// Returns main-page
		    }
		    break;
	    }
	}

	// Ajax/JSON: Returns the User-List for Menu
	public function fctUserList()
	{
	    $arrReturn['left'] = "<h1>{User1}</h1>";
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."user ORDER BY use_login" );
	    if( count( $this->arrSQL ) )
	    {
		$arrReturn['left'] .= "<ul>";
		foreach( $this->arrSQL as $arrUser )
		{
		    if( $arrUser['use_id'] == $_GET['uid'] )
			$arrReturn['left'] .= "<li><a href=\"javascript:;\" onclick=\"fctShowUser('". $arrUser['use_id'] ."')\" class=\"Active\">". $arrUser['use_login'] ."</a></li>";
		    else
			$arrReturn['left'] .= "<li><a href=\"javascript:;\" onclick=\"fctShowUser('". $arrUser['use_id'] ."')\">". $arrUser['use_login'] ."</a></li>";
		}
		$arrReturn['left'] .= "</ul>";
	    }

	    // Language
	    $arrReturn['left'] = $this->fctReplaceLang( $arrReturn['left'] );
	    
	    echo json_encode( $arrReturn );
	    exit;
	}

	// Ajax/JSON: Returns Edit-Fields for user
	public function fctEditUser()
	{
	    // Right
		$arrReturn['right'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-edit-user'] );

		// Load user-data if exists
		if( $_GET['uid'] )
		{
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."user LEFT JOIN ". $this->Config['database']['table_prefix'] ."user_data ON use_id = usd_use_id WHERE use_id = '". $_GET['uid'] ."'" );
		}
		
		// Language
		if( count( $this->Config['Languages'] ) )
		{
		    foreach( $this->Config['Languages'] as $key=>$elem )
		    {
			if( $this->arrSQL[0]['use_language'] == $elem || ( $this->arrSQL[0]['use_language'] == 0 && $elem == "en" ) )
			    $strLanguage .= "<option value=\"". $elem ."\" selected>". $key ."</option>";
			else
			    $strLanguage .= "<option value=\"". $elem ."\">". $key ."</option>";
		    }
		}
		
		// Theme
		$_Themes = new _GlobalFunctions();
		$_Themes->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."theme ORDER BY the_name" );
		if( count( $_Themes->arrSQL ) )
		{
		    foreach( $_Themes->arrSQL as $arrTheme )
		    {
			if( $this->arrSQL['use_theme'] == $arrTheme['the_id'] || ($this->arrSQL['use_theme'] == 0 && $arrTheme['the_name'] == "Default") )
			    $strTheme .= "<option value=\"". $arrTheme['the_id'] ."\" selected>". $arrTheme['the_name'] ."</option>";
			else
			    $strTheme .= "<option value=\"". $arrTheme['the_id'] ."\">". $arrTheme['the_name'] ."</option>";
		    }
		}

		// Values
		$arrReturn['right'] = _ParseDoc::fctTagReplace( "login", $this->arrSQL[0]['use_login'] ,$arrReturn['right'] );
		$arrReturn['right'] = _ParseDoc::fctTagReplace( "adminchecked", ($this->arrSQL[0]['use_admin'] == 1 )? ' checked':'' ,$arrReturn['right'] );
		$arrReturn['right'] = _ParseDoc::fctTagReplace( "language", $strLanguage ,$arrReturn['right'] );
		$arrReturn['right'] = _ParseDoc::fctTagReplace( "theme", $strTheme ,$arrReturn['right'] );
		$arrReturn['right'] = _ParseDoc::fctTagReplace( "firstname", $this->arrSQL[0]['usd_firstname'] ,$arrReturn['right'] );
		$arrReturn['right'] = _ParseDoc::fctTagReplace( "lastname", $this->arrSQL[0]['usd_lastname'] ,$arrReturn['right'] );
		$arrReturn['right'] = _ParseDoc::fctTagReplace( "email", $this->arrSQL[0]['usd_email'] ,$arrReturn['right'] );
		
		// Language
		$arrReturn['right'] = $this->fctReplaceLang( $arrReturn['right'] );

	    // Footer
		$arrReturn['footer'] = "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='fctSaveUser(\"". $_GET['uid'] ."\")'>{Save}</a></div><div class='ButtonRight'></div></div>";
		$arrReturn['footer'].= "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='fctDeleteUser(\"". $_GET['uid'] ."\")'>{DeleteUser}</a></div><div class='ButtonRight'></div></div>";
		
		// Language
		$arrReturn['footer'] = $this->fctReplaceLang( $arrReturn['footer'] );


	    echo json_encode( $arrReturn );
	    exit;
	}
	
	// Ajax: Save user-data
	public function fctSaveUser()
	{
	    $intUID = $this->fctClearValue( $_POST['uid'] );
	    $strLogin = $this->fctClearValue( $_POST['login'] );
	    $strPassword = $this->fctClearValue( $_POST['password'] );
	    if( $_POST['admin'] == true )
		$strAdmin = "1";
	    else
		$strAdmin = "0";
	    $strLanguage = $this->fctClearValue( $_POST['language'] );
	    $strTheme = $this->fctClearValue( $_POST['theme'] );
	    $strFirstname = $this->fctClearValue( $_POST['firstname'] );
	    $strLastname = $this->fctClearValue( $_POST['lastname'] );
	    $strEmail = $this->fctClearValue( $_POST['email'] );
	    
	    // Check if login exits
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."user WHERE use_login = '". $strLogin ."'" );
	    if( count( $this->arrSQL ) > 0 && $this->arrSQL[0]['use_id'] != $intUID )
	    {
		$arrReturn['error'] = 1;
		$arrReturn['message'] = $this->fctReplaceLang('<h1>{Error}</h1><div class="Pad"><p>{Login3}</p></div>');
		echo json_encode( $arrReturn );
		exit;
	    }
	    
	    // Check if exists
	    if( $intUID )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."user WHERE use_id = '". $intUID ."'" );
		if( count( $this->arrSQL ) == 0 ) unset( $intUID );
		
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."user_data WHERE usd_use_id = '". $intUID ."'" );
		if( count( $this->arrSQL ) == 1 ) $intUDID = $this->arrSQL[0]['usd_id'];
	    }
	    
	    // If new, create new!
	    if( !$intUID )
	    {
		$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."user (use_id) VALUES ('')" );
		$intUID = $this->mysql_insert_id;
	    }
	    
	    if( !$intUDID )
	    {
		$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."user_data (usd_use_id) VALUES ('". $intUID ."')" );
		$intUDID = $this->mysql_insert_id;
	    }
	    
	    // Save user
	    $sqlUpdateUser = "UPDATE ". $this->Config['database']['table_prefix'] ."user SET use_login = '". $strLogin ."'";
	    if( $strPassword ) $sqlUpdateUser.= ", use_pw = '". md5( $strPassword ) ."'";
	    $sqlUpdateUser.= ", use_admin = '". $strAdmin ."', use_language = '". $strLanguage ."', use_theme = '". $strTheme ."' WHERE use_id = '". $intUID ."'";	    
	    $this->fctQuery( $sqlUpdateUser );
	    
	    // Save user-data
	    $this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."user_data SET usd_firstname = '". $strFirstname ."', usd_lastname = '". $strLastname ."', usd_email = '". $strEmail ."' WHERE usd_id = '". $intUDID ."'" );
	    
	    $arrReturn['message'] = $this->fctReplaceLang('<h1>{Save}</h1><div class="Pad"><p>{Save2}</p></div>');
	    $arrReturn['uid'] = $intUID;
	    echo json_encode( $arrReturn );
	    exit;
	}
	
	public function fctDeleteUser()
	{
	    $intUID = $this->fctClearValue( $_GET['uid'] );
	    if( $intUID )
	    {
		$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."user WHERE use_id = '". $intUID ."'" );
		$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."user_data WHERE usd_use_id = '". $intUID ."'" );
		$arrReturn['message'] = '<h1>{DeleteUser}</h1><div class="Pad"><p>{DeleteUser3}</p></div>';
	    }
	    else
		$arrReturn['message'] = '<h1>{Error}</h1><div class="Pad"><p style="color:#f00;">{Error2}</p></div>';
		
	    $arrReturn['message'] = $this->fctReplaceLang('<h1>{Save}</h1><div class="Pad"><p>{Save2}</p></div>');
	    
	    echo json_encode( $arrReturn );
	    exit;
	}
	
	public function fctShowPage()
	{
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup'];

	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );

	    // Footer-Buttons
	    $strContent = _ParseDoc::fctTagReplace( "footer", "&nbsp;" ,$strContent );

	    // Language
	    $this->arrDocument['js_language']['EaseUserLangTxt'] = $this->fctSetJSLangTxT( 'User' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}

    }

?>
