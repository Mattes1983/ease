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

    class _Search extends _GlobalFunctions 
    {
	public function fctDocumentSearch( $strSearch )
	{
	    // Content Search
	    $this->fctQuery( "SELECT ". $this->Config['database']['table_prefix'] ."document.* FROM ". $this->Config['database']['table_prefix'] ."content LEFT JOIN ". $this->Config['database']['table_prefix'] ."link ON con_lin_id = lin_id LEFT JOIN ". $this->Config['database']['table_prefix'] ."document ON lin_doc_id = doc_id WHERE con_value like '%". $strSearch ."%' GROUP BY doc_id ORDER BY doc_id DESC,doc_name" );
	    if( count( $this->arrSQL ) )
	    {
		foreach( $this->arrSQL as $arrD )
		{
		    $arrDocs[$arrD['doc_id']] = $arrD;
		}
	    }
	    
	    // Document Search
	    $this->fctQuery( "SELECT ". $this->Config['database']['table_prefix'] ."document.* FROM  ". $this->Config['database']['table_prefix'] ."document WHERE (doc_id like '%". $strSearch ."%' OR doc_name like '%". $strSearch ."%' OR doc_title like '%". $strSearch ."%') ORDER BY doc_id DESC,doc_name" );
	    if( count( $this->arrSQL ) )
	    {
		foreach( $this->arrSQL as $arrD )
		{
		    $arrDocs[$arrD['doc_id']] = $arrD;
		}
	    }
	    
	    // User Search
	    $this->fctQuery( "SELECT ". $this->Config['database']['table_prefix'] ."document.* FROM  ". $this->Config['database']['table_prefix'] ."document LEFT JOIN ". $this->Config['database']['table_prefix'] ."user as creater ON doc_create_use_id = creater.use_id LEFT JOIN ". $this->Config['database']['table_prefix'] ."user as changer ON doc_changed_use_id = changer.use_id WHERE (creater.use_login like '%". $strSearch ."%' OR changer.use_login like '%". $strSearch ."%') ORDER BY doc_id DESC,doc_name" );
	    if( count( $this->arrSQL ) )
	    {
		foreach( $this->arrSQL as $arrD )
		{
		    $arrDocs[$arrD['doc_id']] = $arrD;
		}
	    }
	    
	    return $arrDocs;
	}
	
	public function fctFileSearch( $strSearch )
	{
	    
	    // File Search
	    $this->fctQuery( "SELECT ". $this->Config['database']['table_prefix'] ."file.* FROM  ". $this->Config['database']['table_prefix'] ."file WHERE (fil_name like '%". $strSearch ."%' OR fil_suffix like '%". $strSearch ."%') ORDER BY fil_id DESC,fil_name" );
	    if( count( $this->arrSQL ) )
	    {
		foreach( $this->arrSQL as $arrD )
		{
		    $arrDocs[$arrD['fil_id']] = $arrD;
		}
	    }
	    
	    // User Search
	    $this->fctQuery( "SELECT ". $this->Config['database']['table_prefix'] ."file.* FROM  ". $this->Config['database']['table_prefix'] ."file LEFT JOIN ". $this->Config['database']['table_prefix'] ."user as creater ON fil_create_use_id = creater.use_id WHERE creater.use_login like '%". $strSearch ."%' ORDER BY fil_id DESC,fil_name" );
	    if( count( $this->arrSQL ) )
	    {
		foreach( $this->arrSQL as $arrD )
		{
		    $arrDocs[$arrD['fil_id']] = $arrD;
		}
	    }
	    
	    return $arrDocs;
	}
    }

?>
