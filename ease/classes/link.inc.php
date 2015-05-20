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

    class _Link extends _GlobalFunctions {
	
	public function fctUpdateLinkChilds( $arrLink,$arrTags )
	{
	    if( count( $arrTags ) )
	    {
		unset( $arrTags['edit'] );
		foreach( $arrTags as $arrTag )
		{
		    if( $arrTag['attribute']['extension'] )
		    {
			// Get Extension ID
			$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_name = '". $arrTag['attribute']['extension'] ."'" );
			$intExtID = $this->arrSQL[0]['ext_id'];
			
			$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $arrLink['lin_id'] ."' AND lin_name = '". $arrTag['name'] ."' AND lin_ext_id = '". $intExtID ."'" );
			if( count( $this->arrSQL ) == 0 )
			{
			    self::fctDeleteLinkChilds( $arrLink['lin_id'],$arrTag['name'] );
			    if( $arrTag['attribute']['param_name'] && $arrTag['attribute']['param_value'] )
			    {
				$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link_content (lco_name,lco_value) VALUES ('". $arrTag['attribute']['param_name'] ."','". $arrTag['attribute']['param_value'] ."')" );
				$this->fctQuery( "SELECT max(lco_id) as max FROM ". $this->Config['database']['table_prefix'] ."link_content" );
				$intContentID = $this->arrSQL[0]['max'];
			    }
			    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link (lin_parent,lin_doc_id,lin_lco_id,lin_lvl_id,lin_ext_id,lin_name) VALUES ('". $arrLink['lin_id'] ."','". $arrLink['lin_doc_id'] ."','". $intContentID ."','". $arrLink['lin_lvl_id'] ."','". $intExtID ."','". $arrTag['name'] ."')" );
			}
		    }
		    else if( $arrTag['attribute']['relation'] )
		    {
    			$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $arrLink['lin_id'] ."' AND lin_name = '". $arrTag['name'] ."' AND lin_relation = '". $arrTag['attribute']['relation'] ."'" );
			if( count( $this->arrSQL ) == 0 )
			{
			    self::fctDeleteLinkChilds( $arrLink['lin_id'],$arrTag['name'] );
			    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link (lin_parent,lin_relation,lin_doc_id,lin_lvl_id,lin_name) VALUES ('". $arrLink['lin_id'] ."','". $arrTag['attribute']['relation'] ."','". $arrLink['lin_doc_id'] ."','". $arrLink['lin_lvl_id'] ."','". $arrTag['name'] ."')" );
			}
		    }
		}
	    }
	}
	
	public function fctLinkOrder( $intLinParentID,$strLinName )
	{
	    $_GF = new _GlobalFunctions();
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $intLinParentID ."' AND lin_name = '". $strLinName ."' AND lin_order != '-1' ORDER BY lin_order ASC" );
	    for( $i=0 ; $i<count( $this->arrSQL ) ; $i++ )
		$_GF->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."link SET lin_order = '". $i ."' WHERE lin_id = '". $this->arrSQL[$i]['lin_id'] ."'" );
	}
	
	public function fctDeleteLinkChilds( $linParentID,$strLinName=false )
	{
	    // Classes
	    $_GF = new _GlobalFunctions();
	    
	    // Child-Links
	    if( $strLinName ) $strSQL = " AND lin_name = '". $strLinName ."'";
	    $_GF->fctQuery( "SELECT lin_id FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $linParentID ."'". $strSQL );
	    if( count( $_GF->arrSQL ) > 0 )
	    {
		foreach( $_GF->arrSQL as $arrLinks )
		    self::fctDeleteLinkwithChild( $arrLinks['lin_id'] );
	    }
	    
	}
	
	public function fctDeleteLinkwithChild( $linID,$strLinName=false )
	{
	    // Classes
	    $_GF = new _GlobalFunctions();
	    
	    // Child-Links
	    if( $strLinName ) $strSQL = " AND lin_name = '". $strLinName ."'";
	    $_GF->fctQuery( "SELECT lin_id FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $linID ."'". $strSQL );
	    if( count( $_GF->arrSQL ) > 0 )
	    {
		foreach( $_GF->arrSQL as $arrLinks )
		    self::fctDeleteLinkwithChild( $arrLinks['lin_id'] );
	    }
	    
	    // Delete-Link
	    $_GF->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content ON lin_lco_id = lco_id LEFT JOIN ". $this->Config['database']['table_prefix'] ."extension ON lin_ext_id = ext_id WHERE lin_id = '". $linID ."'" );
	    if( count( $_GF->arrSQL ) == 1 )
	    {
		// Extension-Update
		    // Classes
		    if( $_GF->arrSQL[0]['ext_name'] )
		    {
			$strClassName = _Extensions::fctExtensionClass( $_GF->arrSQL[0]['ext_name'] );
			$_Class = new $strClassName;
			$_Class->ExtensionVars['ext_id'] = $_GF->arrSQL[0]['ext_id'];
			$_Class->fctSetLang();

			// Give all Attributes to Extension
			$_Class->EaseVars = $this->EaseVars;
			$_Class->ExtensionVars['ext_id'] = $_GF->arrSQL[0]['lin_ext_id'];
			$_Class->ExtensionVars['level'] = $_GF->arrSQL[0]['lin_lvl_id'];
		    
			$_Class->fctDeleteItem( $_GF->arrSQL[0]['lin_id'] ,array( $_GF->arrSQL[0]['lco_name']=>$_GF->arrSQL[0]['lco_value'] ) );
		    }
		
		// Delete
		$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."content WHERE con_lin_id = '". $_GF->arrSQL[0]['lin_id'] ."'" );
		$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."link_content WHERE lco_id = '". $_GF->arrSQL[0]['lin_lco_id'] ."'" );
		$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $_GF->arrSQL[0]['lin_id'] ."'" );
		
	    }
	    
	}
	
	function fctDeleteLink( $intLinkID )
	{
	    $this->fctQuery( "SELECT lin_parent,lin_name,lin_doc_id FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intLinkID ."'" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		$arrLink = $this->arrSQL[0];

		_Link::fctDeleteLinkwithChild( $intLinkID );
		_Link::fctLinkOrder( $arrLink['lin_parent'],$arrLink['lin_name'] );

		// Document Changed!
		_Documents::fctDocChanged( $arrLink['lin_doc_id'] );
	    }
	}
	
	public function fctMoveLink( $arrParams )
	{
	    $_PD = new _ParseDoc();

	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $arrParams['move_id'] ."'" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		if( !$arrParams['lin_parent'] ) $arrParams['lin_parent'] = 0;
		if( !$arrParams['lin_order'] ) $arrParams['lin_order'] = 0;

		// New Order for old Parent-Childs
		$arrLink = $this->arrSQL[0];
		$intOldParentID = $this->arrSQL[0]['lin_parent'];
		$intOldOrder = $this->arrSQL[0]['lin_order'];
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."link SET lin_order = '-1' WHERE lin_id = '". $arrParams['move_id'] ."'" );
		_Link::fctLinkOrder( $intOldParentID, $this->arrSQL[0]['lin_name'] );

		// Max
		$this->fctQuery( "SELECT (MAX(lin_order)+1) as max FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $arrParams['lin_parent'] ."' AND lin_name = '". $arrParams['lin_name'] ."'" );
		if( $this->arrSQL[0]['max'] < $arrParams['lin_order'] ) $arrParams['lin_order'] = $this->arrSQL[0]['max'];

		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."link SET lin_order = lin_order+1 WHERE lin_parent='". $arrParams['lin_parent'] ."' AND lin_order >= '". $arrParams['lin_order'] ."' AND lin_name = '". $arrParams['lin_name'] ."'" );
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."link SET lin_parent = '". $arrParams['lin_parent'] ."', lin_order = '". $arrParams['lin_order'] ."', lin_name = '". $arrParams['lin_name'] ."' WHERE lin_id = '". $arrParams['move_id'] ."'" );
		_Link::fctLinkOrder( $intOldParentID, $arrParams['lin_name'] );

		// Document Changed!
		_Documents::fctDocChanged( $arrLink['lin_doc_id'] );
	    }
	}
	
	public function fctSaveIntern( $arrParams )
	{
	    $this->fctQuery( "SELECT ext_id FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_id = '". $arrParams['ext'] ."'" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		if( !$arrParams['document'] ) $arrParams['document'] = $_SESSION['easevars']['document'];

		// Get Link-ID
		$this->fctQuery( "SELECT lin_lco_id, lin_doc_id FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content on lin_lco_id = lco_id WHERE lin_doc_id = '". $arrParams['document'] ."' AND lin_parent = '". $arrParams['lin_parent'] ."' AND lin_lvl_id = '". $this->EaseVars['level'] ."' AND lin_ext_id = '". $arrParams['ext'] ."' AND lin_name = '". $arrParams['lin_name'] ."'" );
		if( $this->arrSQL[0]['lin_lco_id'] && !$arrParams['drag'] )
		{

		    $arrLink = $this->arrSQL[0];

		    // Replace Content-Value
		    $this->fctQuery( "REPLACE INTO ". $this->Config['database']['table_prefix'] ."link_content (lco_id,lco_name,lco_value) VALUES ('". $this->arrSQL[0]['lin_lco_id'] ."','". $arrParams['name'] ."','". $arrParams['value'] ."') " );
		    $intReturn = $this->mysql_insert_id;
		}				
		else
		{
		    // Create new Link & Content
		    $this->fctQuery( "SELECT max(lco_id) as max_lco_id FROM ". $this->Config['database']['table_prefix'] ."link_content" );
		    $intMaxConID = ( $this->arrSQL[0]['max_lco_id']+1 );
		    
		    // Parent
		    if( $arrParams['lin_parent'] > 0 )
		    {
			$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $arrParams['lin_parent'] ."'" );
			$arrLinkParent = $this->arrSQL[0];
		    }
		    else
			$arrLinkParent = array("lin_lvl_id"=>"1");

		    // With Drag...
		    if( $arrParams['drag'] ){
			$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."link SET lin_order = lin_order+1 WHERE lin_parent = '". $arrParams['lin_parent'] ."' AND lin_name = '". $arrParams['lin_name'] ."' AND lin_order >= '". $arrParams['lin_order'] ."'" );

			// If Paramter send
			if( $arrParams['name'] && $arrParams['value'] )
			{
			    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link (lin_parent,lin_order,lin_doc_id,lin_lco_id,lin_lvl_id,lin_ext_id,lin_name,lin_startdate,lin_enddate) VALUES ('". $arrParams['lin_parent'] ."','". $arrParams['lin_order'] ."','". $arrParams['document'] ."','". $intMaxConID ."','". $arrLinkParent['lin_lvl_id'] ."','". $arrParams['ext'] ."','". $arrParams['lin_name'] ."','". $arrParams['lin_startdate'] ."','". $arrParams['lin_enddate'] ."')" );
			    $intReturn = $this->mysql_insert_id;
			    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link_content (lco_id,lco_name,lco_value) VALUES ('". $intMaxConID ."','". $arrParams['name'] ."','". $arrParams['value'] ."') ");
			}
			else
			{
			    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link (lin_parent,lin_order,lin_doc_id,lin_lvl_id,lin_ext_id,lin_name,lin_startdate,lin_enddate) VALUES ('". $arrParams['lin_parent'] ."','". $arrParams['lin_order'] ."','". $arrParams['document'] ."','". $arrLinkParent['lin_lvl_id'] ."','". $arrParams['ext'] ."','". $arrParams['lin_name'] ."','". $arrParams['lin_startdate'] ."','". $arrParams['lin_enddate'] ."')" );
			    $intReturn = $this->mysql_insert_id;
			}

		    }
		    // ...or without Drag?
		    else
		    {
			// If Paramter send
			if( $arrParams['name'] && $arrParams['value'] )
			{
			    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link (lin_doc_id,lin_lco_id,lin_lvl_id,lin_ext_id,lin_name,lin_startdate,lin_enddate) VALUES ('". $arrParams['document'] ."','". $intMaxConID ."','". $arrLinkParent['lin_lvl_id'] ."','". $arrParams['ext'] ."','". $arrParams['lin_name'] ."','". $arrParams['lin_startdate'] ."','". $arrParams['lin_enddate'] ."')" );
			    $intReturn = $this->mysql_insert_id;
			    $this->fctQuery( "REPLACE INTO ". $this->Config['database']['table_prefix'] ."link_content (lco_id,lco_name,lco_value) VALUES ('". $intMaxConID ."','". $arrParams['name'] ."','". $arrParams['value'] ."') ");
			}
			else
			{
			    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link (lin_doc_id,lin_lvl_id,lin_ext_id,lin_name,lin_startdate,lin_enddate) VALUES ('". $arrParams['document'] ."','". $arrLinkParent['lin_lvl_id'] ."','". $arrParams['ext'] ."','". $arrParams['lin_name'] ."','". $arrParams['lin_startdate'] ."','". $arrParams['lin_enddate'] ."')" );
			    $intReturn = $this->mysql_insert_id;
			}
		    }
		}

		// Document Changed!
		_Documents::fctDocChanged( $arrParams['document'] );
		
		return $intReturn;
	    }
	}
	
	// Insert Parent-Attributes for link
	public function fctSetLinkAttributes( $intLinID, $arrAttributes=array() )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intLinID ."'" );
	    if( count( $this->arrSQL ) == 1 && count( $arrAttributes ) > 0 )
	    {
		$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."link_parentattributes WHERE lpa_lin_id = '". $intLinID ."'" );
		foreach( $arrAttributes as $strKey=>$strValue )
		{
		    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link_parentattributes (lpa_lin_id,lpa_name,lpa_value) VALUES ('". $intLinID ."','". addslashes( $strKey ) ."','". addslashes( $strValue ) ."')" );
		}
	    }
	}
	
	
	// Check if Link looped
	// looped returns TRUE
	// No loop returns FALSE
	public function fctCheckLoopLink( $intParentLinkID, $intCheckLinkId )
	{
	    if( $intParentLinkID == $intCheckLinkId ) return true;
	    
	    while( $intParentLinkID > 0 )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intParentLinkID ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    if( $this->arrSQL['lin_parent'] == $intCheckLinkId )
			return true;
		    else
			$intParentLinkID = $this->arrSQL['lin_parent'];
		}
		else
		    $intParentLinkID = 0;
	    }
	    return false;
	}
	
	// Get Parent-Attributes for Link
	public function fctGetLinkAttributes( $intLinID )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intLinID ."'" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		$this->fctQuery( "SELECT lpa_name,lpa_value FROM ". $this->Config['database']['table_prefix'] ."link_parentattributes WHERE lpa_lin_id = '". $intLinID ."'" );
		if( count( $this->arrSQL ) )
		{
		    for( $i=0 ; $i<count( $this->arrSQL ) ; $i++ )
		    {
			$arrAttributes[$this->arrSQL[$i]['lpa_name']] = $this->arrSQL[$i]['lpa_value'];
		    }
		    return $arrAttributes;
		}
	    }
	}
	
	// Join-Link
	public function fctJoinLink( $arrParams=array() )
	{
	    if( $arrParams['jid'] && $arrParams['lin_parent'] !== false && self::fctCheckLoopLink( $arrParams['lin_parent'],$arrParams['jid'] ) == false )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $arrParams['jid'] ."'" );
		if( count( $this->arrSQL ) == 1 )
		{		    
		    // Parent
		    if( $arrParams['lin_parent'] > 0 )
		    {
			$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $arrParams['lin_parent'] ."'" );
			$arrLinkParent = $this->arrSQL[0];
		    }
		    else
			$arrLinkParent = array("lin_lvl_id"=>"1");
		    
		    // Save
		    $this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."link SET lin_order = lin_order+1 WHERE lin_parent='". $arrParams['lin_parent'] ."' AND lin_order >= '". $arrParams['lin_order'] ."' AND lin_name = '". $arrParams['lin_name'] ."'" );
		    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link (lin_parent,lin_relation,lin_order,lin_doc_id,lin_lvl_id,lin_name) VALUES ('". $arrParams['lin_parent'] ."','". $arrParams['jid'] ."','". $arrParams['lin_order'] ."','". $_SESSION['easevars']['document'] ."','". $arrLinkParent['lin_lvl_id'] ."','". $arrParams['lin_name'] ."')" );
		    
		    // Document Changed!
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intParentID ."'" );
		    _Documents::fctDocChanged( $this->arrSQL[0]['lin_doc_id'] );
		}
	    }
	}
	
	// Copy a Link with Childs
	public function fctCopyLink( $arrParams=array() )
	{
	    if( $arrParams['cid'] && $arrParams['lin_parent'] !== false )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $arrParams['cid'] ."'" );
		if( count( $this->arrSQL ) )
		{
		    // Values of Copy-Element
		    $arrLink = $this->arrSQL[0];

		    // Copy Link
		    $intNewLinID = self::fctCopyLinkItem( $arrParams['cid'],$arrParams['lin_parent'],$arrParams['lin_order'],$arrParams['lin_name'] );
		    
		    // Copy Childs
		    self::fctCopyLinkWithChilds( $arrParams['cid'],$intNewLinID );
		    
		    // Document Changed!
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $arrParams['lin_parent'] ."'" );
		    _Documents::fctDocChanged( $this->arrSQL[0]['lin_doc_id'] );
		}
	    }
	}
	
	private function fctCopyLinkWithChilds( $intOldLinParent,$intNewLinParent )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $intOldLinParent ."'" );
	    if( count( $this->arrSQL ) )
	    {
		$arrLinks = $this->arrSQL;
		foreach( $arrLinks as $arrLink )
		{
		    // Copy Link
		    $strNewLinID = self::fctCopyLinkItem( $arrLink['lin_id'],$intNewLinParent,$arrLink['lin_order'],$arrLink['lin_name'] );
		    
		    // Childs
		    self::fctCopyLinkWithChilds( $arrLink['lin_id'],$strNewLinID );
		}
	    }
	}
	
	private function fctCopyLinkItem ( $intCopyLinID,$intParentID,$intOrder,$strLinName )
	{

	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intCopyLinID ."'" );
	    $arrLink = $this->arrSQL[0];
	    
	    if( count( $this->arrSQL ) == 1 && self::fctCheckLoopLink( $intParentID,$intCopyLinID ) == false )
	    {
		// Copy Link-Content
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link_content WHERE lco_id = '". $arrLink['lin_lco_id'] ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link_content (lco_name,lco_value) VALUES ('". $this->arrSQL[0]['lco_name'] ."','". $this->arrSQL[0]['lco_value'] ."')" );
		    $this->fctQuery( "SELECT max(lco_id) as max FROM ". $this->Config['database']['table_prefix'] ."link_content" );
		    $intContentID = $this->arrSQL[0]['max'];
		}
		
		// Parent
		if( $intParentID > 0 )
		{
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intParentID ."'" );
		    $arrLinkParent = $this->arrSQL[0];
		}
		else
		    $arrLinkParent = array("lin_lvl_id"=>"1","lin_doc_id"=>$_SESSION['easevars']['document']);
		
		// Insert New Link		    
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."link SET lin_order = lin_order+1 WHERE lin_parent='". $intParentID ."' AND lin_order >= '". $intOrder ."' AND lin_name = '". $strLinName ."'" );
		$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."link (lin_parent,lin_order,lin_doc_id,lin_lco_id,lin_lvl_id,lin_ext_id,lin_name) VALUES ('". $intParentID ."','". $intOrder ."','". $arrLinkParent['lin_doc_id'] ."','". $intContentID ."','". $arrLinkParent['lin_lvl_id'] ."','". $arrLink['lin_ext_id'] ."','". $strLinName ."')" );
		$this->fctQuery( "SELECT max(lin_id) as max FROM ". $this->Config['database']['table_prefix'] ."link" );
		$intNewLinID = $this->arrSQL[0]['max'];

		// Copy Content
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."content WHERE con_lin_id = '". $intCopyLinID ."'" );
		if( count( $this->arrSQL ) )
		{
		    foreach( $this->arrSQL as $arrContent )
		    {
			$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."content (con_lin_id,con_name,con_value) VALUES ('". $intNewLinID ."','". $arrContent['con_name'] ."','". $arrContent['con_value'] ."')" );
		    }
		}
		return $intNewLinID;
	    }
	    
	}
	
	// Find a Parent-Extension and return the Link-ID
	public function fctFindParentExtension( $intLinID, $intExtID=false, $strExtName=false, $strConName=false, $strConValue=false )
	{
	    $intLinID = intval( $intLinID );
	    if( $intExtID || $strExtName )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intLinID ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    $intParentID = $this->arrSQL[0]['lin_parent'];
		    $intFindID = 0;
		    while( $intFindID == 0 && $intParentID > 0 )
		    {
                        unset( $strWhere );
                        if( $strConName && $strConValue )
                        {
                            $strWhere .= " AND lco_name = '". $strConName ."' AND lco_value = '". $strConValue ."'";
                        }
                        
			$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."extension ON lin_ext_id = ext_id LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content ON lin_lco_id = lco_id WHERE lin_id = '". $intParentID ."'". $strWhere );
			if( count( $this->arrSQL ) == 1 )
			{
			    if( ($this->arrSQL[0]['ext_id'] == $intExtID && $intExtID != false) || ($this->arrSQL[0]['ext_name'] == $strExtName && $strExtName != false) )
			    {
				$intFindID = $this->arrSQL[0]['lin_id'];
				$intParentID = 0;
				break;
			    }
			    else
			    {
				if( $this->arrSQL[0]['lin_parent'] )
				    $intParentID = $this->arrSQL[0]['lin_parent'];
				else
				{
				    $intParentID = 0;
				    break;
				}
			    }
			}
			else
			{
			    $intParentID = 0;
			    break;
			}
		    }
		}
		return $intFindID;
	    }
	}
	
	public function fctGetLinkExtensionTitle( $intLinID=false,$intExtID=false,$strExtName=false,$strExtValue=false )
	{
	    if( $intLinID )
	    {
		$this->fctQuery( "SELECT lin_ext_id,lco_name,lco_value FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content ON lin_lco_id = lco_id WHERE lin_id = '". $intLinID ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    return $this->fctGetExtensionButtonTitle( $this->arrSQL[0]['lin_ext_id'],$this->arrSQL[0]['lco_name'],$this->arrSQL[0]['lco_value'] );
		}
	    }
	    else if( $intExtID )
	    {
		return $this->fctGetExtensionButtonTitle( $intExtID,$strExtName,$strExtValue );
	    }
	}
	
	public function fctGetExtensionButtonTitle( $intExtID,$strExtName=false,$strExtValue=false )
	{
	    if( $intExtID )
	    {
		// load Extension
		$this->fctQuery( "SELECT ext_name FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_id = '". $intExtID ."'" );
		if( count( $this->arrSQL ) )
		{		
		    // Search in each level
		    $_Level = new _GlobalFunctions;
		    $_Level->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."level" );
		    if( count( $_Level->arrSQL ) )
		    {
			foreach ( $_Level->arrSQL as $arrLevel )
			{
			    $strClassName = _Extensions::fctExtensionClass( $this->arrSQL[0]['ext_name'] );
			    $_Extension = new $strClassName;
			    $_Extension->ExtensionVars['ext_id'] = $this->arrSQL[0]['ext_id'];
			    $_Extension->fctSetLang();
			    $arrButtons = $_Extension->fctGetToolButtons( $arrLevel['lvl_id'] );
			    
			    foreach( $arrButtons as $arrButton )
			    {
				if( $arrButton['name'] == $strExtName && $arrButton['value'] == $strExtValue )
				    return strip_tags( $_Extension->fctReplaceLang( $arrButton['text'] ) );
			    }
			    
			}
		    }
		}
	    }
	}
	
	// Returns all links from all Link-Childs
	public function fctGetAllLinkChilds( $intLinID )
	{
	    $_Link = new _GlobalFunctions;
	    $_Link->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $intLinID ."' ORDER BY lin_order" );
	    if( count( $_Link->arrSQL ) )
	    {
		foreach( $_Link->arrSQL as $arrLink )
		{
		    $arrLinks[] = $arrLink;
		    $arrLinkChilds = _Link::fctGetAllLinkChilds( $arrLink['lin_id'] );
		    if( count( $arrLinkChilds ) ) $arrLinks = array_merge( $arrLinks,$arrLinkChilds );
		}
		return $arrLinks;
	    }
	}
	
    }

?>