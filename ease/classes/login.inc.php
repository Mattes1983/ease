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

    class _Login extends _GlobalFunctions
    {
	// Login with Formular-Fields
	public function fctDoLogin()
	{
	    $strLo = strip_tags( $_POST['login'] );
	    $strPw = md5( strip_tags( $_POST['password'] ) );
	    unset( $_SESSION['errormessage'] );
	    
	    $_L = new _Language;
	    
	    if( preg_match( "%^[\w0-9\_\-[:space:]]*$%",$strLo ) && preg_match( "%^[abcdef0-9]{32}$%",$strPw ) ) 
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."user LEFT JOIN ". $this->Config['database']['table_prefix'] ."user_data ON use_id = usd_use_id WHERE use_login = '". $strLo ."' AND use_pw = '". $strPw ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
                    // Mail-Info for start
                    $strText = "A user has logged in:\n";
                    $strText.= "Time: ". date("d.m.Y, H:i") ."\n";
                    $strText.= "Domain-Path: ". $this->Config['http']['domain'] ."\n"; 
                    $strText.= "Basic-Path: ". $this->Config['path']['basic'] ."\n";
                    $strText.= "CMS-Path: ".$this->Config['path']['cms'] ."\n";
                    $strText.= "IP: ". $_SERVER['REMOTE_ADDR'] ."\n";
                    $strText.= "User-ID: ". $this->arrSQL[0]['use_id'] ."\n";
                    $strText.= "User-Login: ". $this->arrSQL[0]['use_login'] ."\n";
                    $strText.= "User-Name: ". $this->arrSQL[0]['usd_firstname'] . " ". $this->arrSQL[0]['usd_lastname'] ."\n";
                    $strText.= "User-E-Mail: ". $this->arrSQL[0]['usd_email'] ."\n";
                    @mail("matthias.dahms@ease-cms.de","ease CMS - Login",$strText);
                    
                    // Login & Redirect
		    $intUseID = $this->arrSQL[0]['use_id'];
		    $_SESSION['easevars']['session_id'] = md5( session_id().time() );
		    $this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."user_login WHERE ulo_session_id = '". $_SESSION['easevars']['session_id'] ."'" );
		    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."user_login (ulo_use_id,ulo_datetime,ulo_session_id,ulo_ip) VALUES ('". $intUseID ."',NOW(),'". $_SESSION['easevars']['session_id'] ."','". $_SERVER['REMOTE_ADDR'] ."')" );
		    $this->fctURLRedirect( $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/toolbox.php" );
		}
		else
		    $_SESSION['errormessage'][] = $_L->arrLanguageReplaces[$_SESSION['easevars']['user_language']]['LoginIncorrect'];
	    }
	    else
		$_SESSION['errormessage'][] = $_L->arrLanguageReplaces[$_SESSION['easevars']['user_language']]['LoginIncorrect'];
	    
	}
	
	// Chekc Login
	public function fctCheckLogin()
	{
	    
	    if( $_SESSION['easevars']['session_id'] )
	    {
		$this->fctQuery( "SELECT use_id, use_language FROM ". $this->Config['database']['table_prefix'] ."user_login LEFT JOIN ". $this->Config['database']['table_prefix'] ."user ON use_id = ulo_use_id WHERE ulo_session_id = '". $_SESSION['easevars']['session_id'] ."' AND ulo_ip = '". $_SERVER['REMOTE_ADDR'] ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    $_SESSION['easevars']['user_id'] = $this->arrSQL[0]['use_id'];
		    $_SESSION['easevars']['user_language'] = $this->arrSQL[0]['use_language'];
		    return true;
		}		    
	    }
	    // Redirect to Index
	    $this->fctURLRedirect( $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/index.php" );
	}
    }

?>
