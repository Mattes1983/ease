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

    class _Generate extends _GlobalFunctions 
    {
	
	// Insert all documents for generate in $_SESSION
	public function fctPrepareGenerate( $arrDocuments=array() )
	{
	    // Reset
	    unset( 
		$_SESSION['easevars']['generate']['documents'],
		$_SESSION['easevars']['generate']['includes'],
		$_SESSION['easevars']['generate']['includes_done'],
		$_SESSION['easevars']['generate']['count']
	    );
	    $_SESSION['easevars']['generate']['percent'] = 0;
	    
	    // Generate all documents in $arrDocuments
	    if( count( $arrDocuments ) > 0 )
	    {
		foreach( $arrDocuments as $intDoc )
		{
		    $this->fctQuery( "SELECT doc_id FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $intDoc ."'" );
		    if( count( $this->arrSQL ) > 0 )
		    {
			$_SESSION['easevars']['generate']['documents'][] = $this->arrSQL[0]['doc_id'];
			$_SESSION['easevars']['generate']['count']++;
		    }
		}
	    }
	    // Generate all documents
	    else
	    {
		$this->fctQuery( "SELECT doc_id FROM ". $this->Config['database']['table_prefix'] ."document ORDER BY doc_id" );
		if( count( $this->arrSQL ) )
		{
		    $_SESSION['easevars']['generate']['count'] = count( $this->arrSQL );
		    foreach( $this->arrSQL as $arrDocument )
		    {
			$_SESSION['easevars']['generate']['documents'][] = $arrDocument['doc_id'];
		    }
		}
	    }
	    
	    // Create Basic Function (MySQL-Connection, Paths,...)
	    $this->fctCreateExternBasicFunctions();

	}
	
	// Generate actual document or include
	public function fctGenerateDocument()
	{
	    if( $_SESSION['easevars']['generate']['count'] > 0 )
	    {
		if( count( $_SESSION['easevars']['generate']['includes'] ) )
		{
		    $_PD = new _ParseDoc;
		    $intLinID = array_shift( $_SESSION['easevars']['generate']['includes'] );
		    $_PD->fctParseIncGenerate( $intLinID );
		    $_SESSION['easevars']['generate']['includes_done'][$intLinID] = $intLinID;
		}
		else
		{
		    $_PD = new _ParseDoc;
		    $_PD->fctParseDocGenerate( array_shift( $_SESSION['easevars']['generate']['documents'] ) );
		}
		
		$_SESSION['easevars']['generate']['percent'] = floor( 100-( ( ( count( $_SESSION['easevars']['generate']['documents'] ) + count( $_SESSION['easevars']['generate']['includes'] ) ) / $_SESSION['easevars']['generate']['count'] ) * 100 ) );
		
		if( $_SESSION['easevars']['generate']['percent'] == 100 )
		    unset( 
			$_SESSION['easevars']['generate']['documents'],
			$_SESSION['easevars']['generate']['includes'],
			$_SESSION['easevars']['generate']['includes_done'],
			$_SESSION['easevars']['generate']['count']
		    );
	    }
	    else
		$_SESSION['easevars']['generate']['percent'] = 100;
	}
	
	public function fctInsertInclude( $intLinID )
	{
	    if( !$_SESSION['easevars']['generate']['includes_done'][$intLinID] && !$_SESSION['easevars']['generate']['includes'][$intLinID] )
	    {
		$_SESSION['easevars']['generate']['includes'][$intLinID] = $intLinID;
		$_SESSION['easevars']['generate']['count']++;
	    }
	}
	
	public function fctInsertData( $intDocID=false )
	{
	    // Vars
	    if( !$intDocID ) $intDocID = $_SESSION['easevars']['document'];
	    $arrPossibleTypes = array("text");
	    
	    // Value Resets
	    _GlobalFunctions::fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."generated_content WHERE gec_doc_id = '". $intDocID ."'" );
	    _GlobalFunctions::fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."generated_document WHERE ged_doc_id = '". $intDocID ."'" );
	    
	    // SQL - Get Contents
	    if( count( $arrPossibleTypes ) )
	    {
		unset( $strOR );
		$strWhere = "AND (";
		foreach( $arrPossibleTypes as $strType )
		{
		    $strWhere .= $strOR ." con_name = '". $strType ."'";
		    $strOR = " OR ";
		}
		$strWhere .= ")";
	    }
	    $this->fctQuery( "SELECT con_name, con_value FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."content ON lin_id = con_lin_id WHERE lin_doc_id = '". $intDocID ."'". $strWhere );
	    
	    // SQL - Save Generated Contents
	    if( count( $this->arrSQL ) )
	    {
		foreach( $this->arrSQL as $arrValues )
		    _GlobalFunctions::fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."generated_content (gec_doc_id,gec_typ,gec_content,gec_content_plaintext) VALUES ('". $intDocID ."','". $arrValues['con_name'] ."','". addslashes( $arrValues['con_value'] ) ."','". addslashes( trim( strip_tags( $arrValues['con_value'] ) ) ) ."')" );
	    }
	    
	    // Save Document-Infos
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $intDocID ."'" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		$arrDoc = $this->arrSQL[0];
		_GlobalFunctions::fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."generated_document (ged_doc_id,ged_path,ged_doc_name,ged_doc_suffix,ged_doc_title,ged_doc_meta_description,ged_doc_keywords,ged_doc_first_text,ged_date,ged_use_id) VALUES ('". $intDocID ."','". $this->Config['path']['generate'] ."/". $arrDoc['doc_name'] . $arrDoc['doc_suffix'] ."','". $arrDoc['doc_name'] ."','". $arrDoc['doc_suffix'] ."','". $arrDoc['doc_title'] ."','". $arrDoc['doc_meta_description'] ."','". $arrDoc['doc_meta_keywords'] ."','". $arrDoc['doc_first_text'] ."',now(),'". $_SESSION['easevars']['user_id'] ."')" );
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."generated_path WHERE geu_doc_id = '". $intDocID ."' AND geu_path ='". $this->Config['path']['generate'] ."/". $arrDoc['doc_name'] . $arrDoc['doc_suffix'] ."'" );
		if( count( $this->arrSQL ) == 0 )
		    _GlobalFunctions::fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."generated_path (geu_doc_id,geu_path) VALUES ('". $intDocID ."','". $this->Config['path']['generate'] ."/". $arrDoc['doc_name'] . $arrDoc['doc_suffix'] ."')" );
	    }
	    
	}
	
    }
	
?>