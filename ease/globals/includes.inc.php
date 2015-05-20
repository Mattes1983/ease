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

    /* Basic-Include */
    include_once( dirname(__FILE__) ."/functions.inc.php" );                     // Global-Functions
    
    // Basic-Class
    $_GF = new _GlobalFunctions();
    
    // More Includes
    
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] .$_GF->Config['path']['classes'] ."/login.inc.php" );
    
    // Login-Check
    $_LOG = new _Login;
    $_LOG->fctCheckLogin();
    
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/user.inc.php" );		    // User
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/document.inc.php" );          // Document
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/extension.inc.php" );         // Extension
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/file.inc.php" );		    // File
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/content.inc.php" );           // Content
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/link.inc.php" );		    // Link
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/search.inc.php" );	    // Search
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/generate.inc.php" );         // Generated
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/language.inc.php" );          // Language
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/parser.inc.php" );            // Parser
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/action.inc.php" );            // Action
    include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['classes'] ."/theme.inc.php" );             // Theme
    
    // Level-Includes
    $_GF->fctQuery( "SELECT ext_include FROM ". $_GF->Config['database']['table_prefix'] ."extension WHERE ext_active = '1'" );
    if( count( $_GF->arrSQL ) > 0 )
    {
	foreach( $_GF->arrSQL as $arrInclude )
	{
	    if( file_exists( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['extension'] . $arrInclude['ext_include'] . "/basic.inc.php" ) ) 
		include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['extension'] . $arrInclude['ext_include'] . "/basic.inc.php" );
	    if( file_exists( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['extension'] . $arrInclude['ext_include'] . "/extends.inc.php" ) )
		include_once( $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['extension'] . $arrInclude['ext_include'] . "/extends.inc.php" );
	}
    }
    
?>