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

    if( isset( $_GET['action'] ) )
    {
	if( $_GET['action'] == "login" )
	{
	    // Ease-Includes
	    include_once(dirname(__FILE__)."/globals/functions.inc.php");
	    $_GF = new _GlobalFunctions();
	    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/language.inc.php");
	    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/login.inc.php");
	    $_LOG = new _Login();
	    $_LOG->fctDoLogin();
	    $_GF->fctURLRedirect( "index.php" );
	}
    }
	
    // Ease-Includes
    include_once(dirname(__FILE__)."/globals/functions.inc.php");
    $_GF = new _GlobalFunctions();
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/language.inc.php");
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/file.inc.php");
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/parser.inc.php");
    unset( $_SESSION['easevars'] );
    
    // Ease-Classes
    $_PD = new _ParseDoc;
    $_PD->fctRenderLogin();
?>