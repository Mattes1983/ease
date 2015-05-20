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

    class _Documents extends _GlobalFunctions {
	
	public function fctSetDoc()
	{
	    
	    // Show Link Info
	    if( $_GET['showlinkinfo'] == 1 )
	    {
		if( !$_SESSION['easevars']['show_link_info'] )
		    $_SESSION['easevars']['show_link_info'] = true;
		else
		    unset( $_SESSION['easevars']['show_link_info'] );
	    }
	    
	    if( $_GET['easedoc'] == "new" )	    // New Document
	    {
		$this->fctNewDoc();
	    }
	    else if( $_GET['easedoc'] == "back" )    // Back
	    {
		if( $_SESSION['easevars']['history_step'] > 0 )
		{
		    $_SESSION['easevars']['history_step']--;
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $_SESSION['easevars']['history'][$_SESSION['easevars']['history_step']] ."'" );
		    if( $this->arrSQL[0]['doc_id'] )
		    {
			$_SESSION['easevars']['history'][] = $_SESSION['easevars']['document'];
			$_SESSION['easevars']['document'] = $this->arrSQL[0]['doc_id'];
		    }
		    else
			$_SESSION['easevars']['history_step']++;
		}
	    }
	    else if( $_GET['easedoc'] == "next" )    // Next
	    {
		if( $_SESSION['easevars']['history'][($_SESSION['easevars']['history_step']+1)] )
		{
		    $_SESSION['easevars']['history_step']++;
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $_SESSION['easevars']['history'][$_SESSION['easevars']['history_step']] ."'" );
		    if( $this->arrSQL[0]['doc_id'] )
		    {
			$_SESSION['easevars']['document'] = $this->arrSQL[0]['doc_id'];
		    }
		    else
			$_SESSION['easevars']['history_step']--;
		}
	    }
	    else if( $_GET['easedoc'] == "startpage" )    // Startpage
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting LEFT JOIN ". $this->Config['database']['table_prefix'] ."document ON set_value = doc_id WHERE set_name = 'Document-Start'" );
		if( $this->arrSQL[0]['doc_id'] )
		{
		    // Set $_SESSION['easevars']['document']
		    if( $_SESSION['easevars']['document'] != $this->arrSQL[0]['doc_id'] )
		    {
			$_SESSION['easevars']['history_step']++;
			$_SESSION['easevars']['history'][] = $_SESSION['easevars']['document'];
		    }
		    $_SESSION['easevars']['document'] = $this->arrSQL[0]['doc_id'];
		}
	    }
	    else if( $_GET['easedoc'] )	// GoTo
	    {
		$intDocID = intval( $_GET['easedoc'] );
		if( $intDocID )
		{
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $intDocID ."'" );
		    if( count( $this->arrSQL ) == 1 )
		    {
			if( $_SESSION['easevars']['document'] != $this->arrSQL[0]['doc_id'] )
			{
			    $_SESSION['easevars']['history'] = array_slice( $_SESSION['easevars']['history'],0,$_SESSION['easevars']['history_step'] );
			    $_SESSION['easevars']['history_step']++;
			    $_SESSION['easevars']['history'][] = $_SESSION['easevars']['document'];
			}
			$_SESSION['easevars']['document'] = $this->arrSQL[0]['doc_id'];
		    }
		}
	    }
	    else if( !$_SESSION['easevars']['document'] )   // Find StartPage
	    {
		// Have a Start-Document?
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting LEFT JOIN ". $this->Config['database']['table_prefix'] ."document ON set_value = doc_id WHERE set_name = 'Document-Start'" );
		if( $this->arrSQL[0]['doc_id'] )
		{
		    // Set $_SESSION['easevars']['document']
		    $_SESSION['easevars']['document'] = $this->arrSQL[0]['doc_id'];
		    
		    // Get Max-Level
		    /*
		    $this->fctQuery("SELECT max(lin_lvl_id) as max FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_doc_id = '". $this->arrSQL[0]['doc_id'] ."'");
		    if( count( $this->arrSQL ) )
			$_SESSION['easevars']['level'] = $this->arrSQL[0]['max'];
		    else
		    */
			$_SESSION['easevars']['level'] = 1;
		    
		}
		else	// Create new Document
		{
		    $this->fctNewDoc();
		}
	    }
	    unset( $_GET['easedoc'] );
	}
	
	function fctNewDoc()
	{
	    // Create New Document
	    $this->fctQuery( "SELECT max(doc_id) as max_id FROM ". $this->Config['database']['table_prefix'] ."document" );
	    $intMaxDocId = $this->arrSQL[0]['max_id']+1;
	    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."document (doc_id,doc_name,doc_suffix,doc_create_use_id,doc_create_date,doc_auto_name,doc_auto_title) VALUES ('". $intMaxDocId ."','". $intMaxDocId ."','". $this->EaseVars['document_suffix'] ."','". $_SESSION['easevars']['user_id'] ."',NOW(),'1','1')" );
	    if( $_SESSION['easevars']['document'] )
	    {
		$_SESSION['easevars']['history_step']++;
		$_SESSION['easevars']['history'][] = $_SESSION['easevars']['document'];
	    }
	    $_SESSION['easevars']['document'] = $intMaxDocId;

	    // If the first one
	    if( $intMaxDocId == 1 )
		$this->fctQuery( "REPLACE INTO ". $this->Config['database']['table_prefix'] ."setting (set_name,set_value) VALUES ('Document-Start','". $intMaxDocId ."')" );
	}
	
	function fctGetDocURL( $intDocID,$arrParams=array() )
	{
	    $intDocID = intval( $intDocID );
	    if( $intDocID )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $intDocID ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    if( count( $arrParams ) )
		    {
			foreach( $arrParams as $strKey=>$strElem )
			{
			    if( $strParams ) $strParams .= "&";
			    $strParams .= $strKey."=".$strElem;
			}
		    }
		    
		    if( $this->EaseVars['generatemode'] == false && $_SESSION['easevars']['generatemode'] == false )
		    {
			if( $strParams ) $strParams = "&".$strParams;
			$arrURL['url'] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/parse.php?easedoc=". $this->arrSQL[0]['doc_id'] . $strParams;
			$arrURL['js'] = "javascript:top.fctEaseShowURL('". $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/parse.php?easedoc=". $this->arrSQL[0]['doc_id'] . $strParams ."');";
		    }
		    else
		    {
			if( $strParams ) $strParams = "?".$strParams;
			$arrURL['url'] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] ."/". $this->arrSQL[0]['doc_name'].$this->arrSQL[0]['doc_suffix'] . $strParams;
			$arrURL['js'] = "window.location.href='".$this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] ."/". $this->arrSQL[0]['doc_name'].$this->arrSQL[0]['doc_suffix'] . $strParams."';";
		    }
		    return $arrURL;
		}
	    }
	}
	
	function fctGetGenerateURL( $intDocID )
	{
	    $intDocID = intval( $intDocID );
	    if( $intDocID )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $intDocID ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    return $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] ."/". $this->arrSQL[0]['doc_name'].$this->arrSQL[0]['doc_suffix'];
		}
	    }
	}
	
	public function fctSetTitle()
	{
	    // Classes
	    $_C = new _Content();
	    $_PD = new _ParseDoc();
	    
	    $_PD->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Document-Auto-Title'" );
	    if( $_PD->arrSQL[0]['set_value'] == 1 && $_SESSION['easevars']['document'] )
	    {
		$_PD->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $_SESSION['easevars']['document'] ."'" );
		if( $_PD->arrSQL[0]['doc_auto_title'] == "1" || $_PD->arrSQL[0]['doc_auto_name'] == 1 )
		{
		    $_C->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."content LEFT JOIN ". $this->Config['database']['table_prefix'] ."link ON con_lin_id = lin_id WHERE lin_doc_id = '". $_SESSION['easevars']['document'] ."' AND (con_value like '%<h1%' OR con_value like '%<h2%' OR con_value like '%<h3%' OR con_value like '%<h4%' OR con_value like '%<h5%' OR con_value like '%<h6%') AND con_name = 'text' ORDER BY lin_order, con_id" );
		    if( count( $_C->arrSQL ) )
		    {
			foreach( $_C->arrSQL as $arrContents )
			{
			    unset( $_PD->arrTags,$_PD->arrNameTags );
			    $_PD->fctFindTag( $arrContents['con_value'],'<h1','</h1>' );
			    if( !$_PD->arrTags )
			    {
				$_PD->fctFindTag( $arrContents['con_value'],'<h2','</h2>' );
				if( !$_PD->arrTags )
				{
				    $_PD->fctFindTag( $arrContents['con_value'],'<h3','</h3>' );
				    if( !$_PD->arrTags )
				    {
					$_PD->fctFindTag( $arrContents['con_value'],'<h4','</h4>' );
					if( !$_PD->arrTags )
					{
					    $_PD->fctFindTag( $arrContents['con_value'],'<h5','</h5>' );
					    if( !$_PD->arrTags )
					    {
						$_PD->fctFindTag( $arrContents['con_value'],'<h6','</h6>' );
						if( $_PD->arrTags ) $arrTags['6'][] = $_PD->arrTags;
					    }
					    else $arrTags['5'][] = $_PD->arrTags;
					}
					else $arrTags['4'][] = $_PD->arrTags;
				    }
				    else $arrTags['3'][] = $_PD->arrTags;
				}
				else $arrTags['2'][] = $_PD->arrTags;
			    }
			    else $arrTags['1'][] = $_PD->arrTags;

			    if( $arrTags['1'] ) break;
			}

			if( count( $arrTags ) )
			{
			    ksort( $arrTags );
			    foreach( $arrTags as $arrHeadlines )
			    {
				$strTitle = substr( trim( strip_tags( $arrHeadlines[0][0]['tag'] ) ),0 ,100 ) ;
				break;
			    }
			    // Update Document Title
			    if( $strTitle ) 
			    {
				if( $_PD->arrSQL[0]['doc_auto_name'] == 1 )
				    $strSet = "doc_name = '". self::fctCheckDocName( self::fctCleanName( $strTitle ),$_SESSION['easevars']['document'] ) ."'";
				
				if( $_PD->arrSQL[0]['doc_auto_title'] == "1" )
				{
				    if( $strSet ) $strSet .= ",";
				    $strSet .= "doc_title = '". $strTitle ."'";
				}
				
				$_PD->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."document SET ". $strSet ." WHERE doc_id = '". $_SESSION['easevars']['document'] ."'" );
			    }
			}
		    }
		}
	    }
	}
	
	// Find und Save first Text
	public function fctSaveFirstText()
	{
	    if( $_SESSION['easevars']['document'] )
	    {
		$this->fctQuery( "SELECT con_value FROM ". $this->Config['database']['table_prefix'] ."content LEFT JOIN ". $this->Config['database']['table_prefix'] ."link ON con_lin_id = lin_id WHERE lin_doc_id = '". $_SESSION['easevars']['document'] ."' AND con_name = 'text' ORDER BY lin_order, con_id LIMIT 0,1" );
		if( count( $this->arrSQL ) == 1 )
		{
		    $strText = trim( strip_tags( $this->arrSQL[0]['con_value'] ) );
		    if( strlen( $strText ) > 100 )
			$strText = substr( $strText,0,97 )."...";
		    $strText = _Content::fctHTMLEntities( $strText );
		    $this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."document SET doc_first_text = '". $strText ."' WHERE doc_id = '". $_SESSION['easevars']['document'] ."'" );
		}
	    }
	}
	
	// Clean Name an Return
	public function fctCleanName( $strName )
	{
	    $strName = str_replace(array("&uuml;","&auml;","&ouml;","&Uuml;","&Auml;","&Ouml;","&szlig;"), array("ue","ae","oe","UE","AE","OE","ss"), $strName);
	    
	    $arrChars = str_split( strip_tags( $strName ) );
	    for( $i=0 ; $i<count( $arrChars ) ; $i++ )
	    {
		if( preg_match( "/[a-z0-9\_\-]/i",$arrChars[$i] ) )
		{
		    $strNewName .= strtolower( $arrChars[$i] );
		}
		else
		{
		    if( $i < (count( $arrChars )-1) )
			$strNewName .= "-";
		}
	    }
	    $strNewName = str_replace(array("--","---"), array("",""), $strNewName);
	    return $strNewName;
	}
	
	public function fctCheckDocName( $strDocName,$intDocID=false )
	{
	    if( $intDocID )
		$strWhere = " AND doc_id != '". $intDocID ."'";
	    
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_name = '". $strDocName ."'".$strWhere );
	    if( count( $this->arrSQL ) )
	    {
		while( $boolTitleFound == false )
		{
		    $i++;
		    $strNewDocName = $strDocName . "-". $i;
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_name = '". $strDocName ."'".$strWhere );
		    if( count( $this->arrSQL ) )
			$boolTitleFound = true;
		}
		$strDocName = $strNewDocName;
	    }
	    return $strDocName;
	}
	
	// Document Changed!
	public function fctDocChanged( $intDocID=false )
	{
	    if( !$intDocID ) $intDocID = $_SESSION['easevars']['document'];
	    $_GF = new _GlobalFunctions();
	    $_GF->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."document SET doc_changed = '1', doc_changed_use_id = '". $_SESSION['easevars']['user_id'] ."', doc_changed_date = now() WHERE doc_id = '". $intDocID ."'" );
	}
        
        public function fctDeleteDoc( $intDocID=false )
        {
            if( !$intDocID ) $intDocID = $_SESSION['easevars']['document'];

            $_GF = new _GlobalFunctions();
            $_GF->fctQuery( "SELECT doc_id FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $intDocID ."'" );
            
            if( count( $_GF->arrSQL ) )
            {

                // Delete Links on Document
                $_GF->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_doc_id = '". $intDocID ."' AND lin_parent = 0" );
                if( count( $_GF->arrSQL ) )
                {
                    foreach( $_GF->arrSQL as $arrLink )
                    {
                        _Link::fctDeleteLink( $arrLink['lin_id'] );
                    }
                }

                // Delete Document
                $_GF->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $intDocID ."'" );
                
                if( $_SESSION['easevars']['document'] == $intDocID )
                {
                    $_SESSION['easevars']['document'] = $_SESSION['easevars']['history'][ (count( $_SESSION['easevars']['history'] )-1) ];
                    $_SESSION['easevars']['history_step']--;
                }                
                return true;
            }
            else
                return false;
        }
	
    }

?>
