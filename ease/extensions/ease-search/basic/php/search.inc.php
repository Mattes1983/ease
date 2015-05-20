<?php
    // Class: EaseSearch
    class _EaseSearch extends _EaseGlobalFunctions
    {
	var $intMaxPerPage = 20;
	var $intHeadlineLength = 50;
	var $intTextLength = 300;
	var $intPagingSpace = 3;
	var $intMaxPages;
	
	public function __construct()
	{
	    parent::__construct();	    
	}
	
	// Show Search Results
	public function fctGetResults()
	{
	    if( !$_SESSION['EASESearch']['start'] ) $_SESSION['EASESearch']['start'] = 0;
	    
	    if( $_SESSION['EASESearch']['searchfield'] )
	    {
		$strSearchValue = $_SESSION['EASESearch']['searchfield'];
		$this->fctQuery( "SELECT gec_content_plaintext,ged_path,ged_doc_title, ged_doc_first_text FROM ". $this->Config['database']['table_prefix'] ."generated_content LEFT JOIN ". $this->Config['database']['table_prefix'] ."generated_document ON gec_doc_id = ged_doc_id WHERE (gec_content_plaintext like '%". $strSearchValue ."%' OR ged_doc_title like '%". $strSearchValue ."%' OR ged_doc_meta_description like '%". $strSearchValue ."%' OR ged_doc_keywords like '%". $strSearchValue ."%') GROUP BY ged_doc_id" );

		$this->intResults = count( $this->arrSQL );
		$this->intMaxPages = ceil( $this->intResults / $this->intMaxPerPage );
		if( count( $this->arrSQL ) )
		{
		    $i=0;
		    $j=0;
		    foreach( $this->arrSQL as $arrResult )
		    {
			if( $i >= ($_SESSION['EASESearch']['start']*$this->intMaxPerPage) && $i < (($_SESSION['EASESearch']['start']*$this->intMaxPerPage)+$this->intMaxPerPage) )
			{
			    $arrResults[$j]["title"] = $this->fctSetLength( $arrResult['ged_doc_title'], $this->intHeadlineLength );
			    if( $arrResult['gec_content_plaintext'] )
			    {
				$arrResults[$j]["text"] = $this->fctGetMarkedText( $strSearchValue, $this->fctSetLength( $arrResult['gec_content_plaintext'], $this->intTextLength ) );
			    }
			    else
			    {
				$arrResults[$j]["text"] = $this->fctGetMarkedText( $strSearchValue, $this->fctSetLength( $arrResult['ged_doc_first_text'], $this->intTextLength ) );
			    }
			    $arrResults[$j]["link"] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $arrResult['ged_path'];
			    $j++;
			}
			$i++;
		    }
		}
	    }
	    return $arrResults;
	}
	
	// Paging
	public function fctPaging()
	{
	    if( $this->intResults > $this->intMaxPerPage )
	    {
		$intPages = ceil( $this->intResults/$this->intMaxPerPage );
		
		$j = 0;
		
		// Back to Start
		if( ($_SESSION['EASESearch']['start']-$this->intPagingSpace) > 0 )
		{
		    $arrPages[$j]["text"] = "&lt;&lt;";
		    $arrPages[$j]["link"] = "?easesearchstart=0";
		    $j++;
		}

		// Back
		if( $_SESSION['EASESearch']['start'] > 0 )
		{
		    $arrPages[$j]["text"] = "&lt;";
		    $arrPages[$j]["link"] = "?easesearchstart=". ($_SESSION['EASESearch']['start']-1);
		    $j++;
		}
		
		for( $i=0 ; $i<$intPages ; $i++,$j++ )
		{			
		    if( $i > ($_SESSION['EASESearch']['start']-$this->intPagingSpace) && $i < ($_SESSION['EASESearch']['start']+$this->intPagingSpace) )
		    {
			$arrPages[$j]["text"] = $i+1;
			$arrPages[$j]["link"] = "?easesearchstart=". $i;
			if( $_SESSION['EASESearch']['start'] == $i )
			$arrPages[$j]["active"] = "Active";
		    }
		}

		// Next
		if( ($_SESSION['EASESearch']['start']+1) < $this->intMaxPages )
		{
		    $arrPages[$j]["text"] = "&gt;";
		    $arrPages[$j]["link"] = "?easesearchstart=". ($_SESSION['EASESearch']['start']+1);
		    $j++;
		}
		
		// Next to End
		if( (($_SESSION['EASESearch']['start']+$this->intPagingSpace)+1) < $this->intMaxPages )
		{
		    $arrPages[$j]["text"] = "&gt;&gt;";
		    $arrPages[$j]["link"] = "?easesearchstart=".($this->intMaxPages-1);
		    $j++;
		}
		
		return $arrPages;
	    }
	}
	
	private function fctGetMarkedText( $strMarker,$strText )
	{	    
	    $strPosStart = strpos( $strText, $strMarker);
	    
	    $intTextPartLenght = ($this->intTextLength-strlen( $strMarker ))/2;
	    
	    if( $strPosStart > $intTextPartLenght )
		$strText = substr( $strText, ($strPosStart-$intTextPartLenght),$this->intTextLength );
	    else
		$strText = substr( $strText, 0,$this->intTextLength );
	    
	    $strText = str_replace( $strMarker, "<strong>". $strMarker ."</strong>", $strText );
	    
	    return $strText;
	}
    }
    
    // Page-Position
    if( $_GET['easesearchstart'] !== false )
    {
	$_SESSION['EASESearch']['start'] = intval( $_GET['easesearchstart'] );
    }

    // Search-Value
    if( $_POST['EASESearchSubmit'] )
    {
	$_SESSION['EASESearch']['searchfield'] = htmlentities( utf8_decode( stripslashes( strip_tags ( $_POST['EASESearchField'] ) ) ) );
	unset( $_SESSION['EASESearch']['start'] );
    }
?>