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

    class _User extends _GlobalFunctions
    {
	
	public function fctGetUserName( $intUseID=false )
	{
	    $_GF = new _GlobalFunctions();
	    if( !$intUseID ) $intUseID = $_SESSION['easevars']['user_id'];
	    $_GF->fctQuery( "SELECT use_login FROM ". $_GF->Config['database']['table_prefix'] ."user WHERE use_id = '". $intUseID ."'" );
	    if( count( $_GF->arrSQL ) == 1 )
		return $_GF->arrSQL[0]['use_login'];
	}
	
	public function fctGetLanguage( $intUseID=false )
	{
	    $_GF = new _GlobalFunctions();
	    if( !$intUseID ) $intUseID = $_SESSION['easevars']['user_id'];
	    $_GF->fctQuery( "SELECT use_language FROM ". $_GF->Config['database']['table_prefix'] ."user WHERE use_id = '". $intUseID ."'" );
	    if( count( $_GF->arrSQL ) == 1 )
		return $_GF->arrSQL[0]['use_language'];
	}
	
	public function fctIsUserAdmin( $intUseID=false )
	{
	    $_GF = new _GlobalFunctions();
	    if( !$intUseID ) $intUseID = $_SESSION['easevars']['user_id'];
	    $_GF->fctQuery( "SELECT use_admin FROM ". $this->Config['database']['table_prefix'] ."user WHERE use_id = '". $intUseID ."'" );
	    if( count( $_GF->arrSQL ) == 1 && $_GF->arrSQL[0]['use_admin'] == 1 )
		return true;
	}
	
    }

?>
