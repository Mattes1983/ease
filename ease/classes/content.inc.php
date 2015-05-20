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

    class _Content extends _GlobalFunctions 
    {
	
	public function fctSaveContent( $intLinID,$ConName,$ConValue )
	{
	    if( $intLinID )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."extension ON lin_ext_id = ext_id WHERE lin_id = '". $intLinID ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    
		    $arrLink = $this->arrSQL[0];
		    
		    if( !is_array( $ConName ) ) $ConName = Array( $ConName );
		    if( !is_array( $ConValue ) ) $ConValue = Array( $ConValue );
		    
		    // Send Extension Delete
		    //$strClassName = _Extensions::fctExtensionClass( $this->arrSQL[0]['ext_name'] );
		    //$_Class = new $strClassName;
		    //$_Class->fctDeleteItem( $intLinID );
		    
		    foreach( $ConName as $key=>$elem )
		    {
			$ConValue[$key] = $this->fctReplacePathToDatabase( $ConValue[$key] );

			$this->fctQuery( "SELECT con_id FROM ". $this->Config['database']['table_prefix'] ."content LEFT JOIN ". $this->Config['database']['table_prefix'] ."link ON lin_id = con_lin_id WHERE con_lin_id = '". $intLinID ."'  AND con_name = '". $elem ."'" );
			if( count( $this->arrSQL ) )    // Update Entry
			{
			    $this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."content SET con_value = '". addslashes( $ConValue[$key] ) ."' WHERE con_id = '". $this->arrSQL[0]['con_id'] ."'" );
			}
			else // Insert new
			{
			    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."content (con_lin_id,con_name,con_value) VALUES ('". $intLinID ."','". $elem ."','". addslashes( $ConValue[$key] ) ."')" );
			}
		    }

		    // Document Changed!
		    _Documents::fctDocChanged( $arrLink['lin_doc_id'] );
		    
		    // Update Document-Title
		    _Documents::fctSetTitle();
		    _Documents::fctSaveFirstText();

		    // Generate?
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Generate-Mode'" );
		    switch( $this->arrSQL[0]['set_value'] )
		    {
			// Generate every Page-Request, if Document changed
			case "1": 
			    $_PD = new _ParseDoc();
			    $_PD->fctParseDocGenerate();
			    break;
		    }
		    
		}
	    }
	}
	
	public function fctReplacePathToDatabase( $strValue )
	{
            // Documents
	    $strPath = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'];
	    $strPath = str_replace( "\\","\\\\",$strPath );
	    $strPath = str_replace( "/","\/",$strPath );
	    $strValue = preg_replace( "/". $strPath ."\/parse.php\?easedoc\=([0-9]+)/i",'<ease:url id="\\1" />',$strValue );
            
            // Files
	    $strPath = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'];
	    $strPath = str_replace( "\\","\\\\",$strPath );
	    $strPath = str_replace( "/","\/",$strPath );
            $strValue = preg_replace( "/". $strPath ."\/files\/[0-9]+\-([0-9a-zA-Z\-\_]+)(\.[0-9a-zA-Z]{2,4})/e",'"<ease:file id=\""._Files::fctGetFilID("\\1","\\2")."\" />"',$strValue );
            
	    return $strValue;
	}
	
	public function fctReplacePathFromDatabase( $strValue )
	{
	    if( $_SESSION['easevars']['generatemode'] == true )
	    {
		$strValue = preg_replace( "/\<ease\:url id\=\"([0-9]+)\" \/\>/e",'_Documents::fctGetGenerateURL("\\1")',$strValue );
                $strValue = preg_replace( "/\<ease\:file id\=\"([0-9]+)\" \/\>/e",'_Files::fctGetFileURL2("\\1");',$strValue );
	    }
	    else
	    {
                $strPath = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'];
                $strPath = str_replace( "\\","\\\\",$strPath );
                $strPath = str_replace( "/","\/",$strPath );
                
		$strValue = preg_replace( '/\<ease\:url id\=\"([0-9]+)\" \/\>/i', $strPath ."/parse.php?easedoc=\\1",$strValue );
                $strValue = preg_replace( "/\<ease\:file id\=\"([0-9]+)\" \/\>/e",'_Files::fctGetFileURL2("\\1");',$strValue );
	    }
	    return $strValue;
	}
	
	public function fctGetContent( $intLinID,$strConName )
	{
	    $this->fctQuery( "SELECT con_value FROM ". $this->Config['database']['table_prefix'] ."content WHERE con_lin_id = '". $intLinID ."' AND con_name = '". $strConName ."'" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		return stripslashes( _Content::fctReplacePathFromDatabase( $this->arrSQL[0]['con_value'] ) );
	    }
	}	

	public function fctGetContentArray( $intLinID,$strConName=false )
	{
	    if( $strConName )
		$this->fctQuery( "SELECT con_name,con_value FROM ". $this->Config['database']['table_prefix'] ."content WHERE con_lin_id = '". $intLinID ."' AND con_name = '". $strConName ."'" );
	    else
		$this->fctQuery( "SELECT con_name,con_value FROM ". $this->Config['database']['table_prefix'] ."content WHERE con_lin_id = '". $intLinID ."'" );
	    
	    $arrValues = array();
	    if( count( $this->arrSQL ) )
	    {
		foreach( $this->arrSQL as $arrValue )
		{
		    $arrValues[$arrValue['con_name']] = _Content::fctReplacePathFromDatabase( $arrValue['con_value'] );
		}
	    }
		
	    return $arrValues;
	}
	
	// Return Content
	/*
	public function fctGetContentOnLink( $intLinID )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content ON lin_lco_id = lco_id WHERE lin_id = '". $intLinID ."'" );
	    if( count( $this->arrSQL ) == 1 ) return $this->arrSQL[0];
	}
	*/
	
	public function fctHTMLEntities( $strValue )
	{
	    $strValue = str_replace( "ü", "&uuml;", $strValue );
	    $strValue = str_replace( "ö", "&ouml;", $strValue );
	    $strValue = str_replace( "ä", "&auml;", $strValue );
	    $strValue = str_replace( "Ü", "&Uuml;", $strValue );
	    $strValue = str_replace( "Ö", "&Ouml;", $strValue );
	    $strValue = str_replace( "Ä", "&Auml;", $strValue );
	    $strValue = str_replace( "ß", "&szlig;", $strValue );
	    return $strValue;
	}

    }
?>
