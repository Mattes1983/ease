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

    class _THEME extends _GlobalFunctions
    {
	public function fctGetUserTheme()
	{
	    if( $_SESSION['easevars']['user_id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."user WHERE use_id = '". $_SESSION['easevars']['user_id'] ."'" );
		if( $this->arrSQL[0]['use_theme'] != "0" ) // take user theme
		{
		    $intThemeID = $this->arrSQL[0]['use_theme'];
		}
		else // take default cms theme
		{
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Theme'" );
		    $intThemeID = $this->arrSQL[0]['set_value'];
		}
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."theme WHERE the_id = '". $intThemeID ."'" );
		return $this->arrSQL[0];
	    }
	    else
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Theme'" );
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."theme WHERE the_id = '". $this->arrSQL[0]['set_value'] ."'" );
		return $this->arrSQL[0];
	    }
	}
    }
?>
