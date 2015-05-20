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

    class EASENewsBasic extends _Extensions
    {
	
	public function __construct()
	{
	    parent::__construct(); 
	    
            // Basics
	    $this->arrConf['RelatedElements'] = array("overview"=>"overviewelement");	    // Related-Elements
	    $this->arrConf['RelatedImageExtensions'] = array( "EASEImage" );		    // Possible image-extensions for related-insert
	    $this->arrConf['RelatedTextExtensions'] = array( "CKEditor" );		    // Possible text-extensions for related-insert
	    $this->arrConf['headlinelength'] = 100;					    // Standard: max. Headline-length
	    $this->arrConf['textlength'] = 200;					    // Standard: max. Text-length
            $this->arrConf['detaillinktext'] = "more";					    // Standard: Text for Detaillink
            $this->arrConf['headlinetagstart'] = "<h1>";                                   // Standard: Headline-Tag - Start
            $this->arrConf['headlinetagend'] = "</h1>";                                    // Standard: Headline-Tag - End
            
            // Templates
            $this->arrConf['tmpl']['overview'] = "basic/templates/overview.tmpl";
            $this->arrConf['tmpl']['overview-edit'] = "basic/templates/overview-edit.tmpl";
            $this->arrConf['tmpl']['overview-element'] = "basic/templates/overview-element.tmpl";
            $this->arrConf['tmpl']['overview-element-edit'] = "basic/templates/overview-element-edit.tmpl";
            $this->arrConf['tmpl']['detail'] = "basic/templates/detail.tmpl";
            $this->arrConf['tmpl']['detail-edit'] = "basic/templates/detail-edit.tmpl";
            
            // CSS
            $this->arrConf['css']['overview-edit'] = "basic/css/overview-edit.css";
            $this->arrConf['css']['overview-element'] = "basic/css/overview-element.css";
            $this->arrConf['css']['detail-edit'] = "basic/css/detail-edit.css";
            
            // JS
            $this->arrConf['js']['overview-onload'] = "basic/js/overview-onload.js";
            $this->arrConf['js']['detail-onload'] = "basic/js/detail-onload.js";
	}
	
	public function fctRenderCode( $arrParams=array() )
	{
	    
	    switch( $arrParams['item'] )
	    {
		// News-Overview
		case 'overview':
		    $strContent = $this->fctOverview( $arrParams );
		    break;
		
		// News-Overview
		case 'overviewelement':
		    $strContent = $this->fctOverviewElement( $arrParams );
		    break;

		// News-Detail
		case 'detail':
		    $strContent = $this->fctDetail( $arrParams );
		    break;
	    }
	    
	    // Language
	    $this->arrDocument['js_language']['EASENewsLangTxt'] = $this->fctSetJSLangTxT( 'EASENews' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    return $strContent;
	}
	
	public function fctOverview( $arrParams=array() )
	{
	    // Classes
	    $_C = new _Content();
	    
	    if( $this->EaseVars['dragmode'] == false )
	    {
		if( $this->EaseVars['generatemode'] == false ) 
		    $this->arrDocument['css']['easenewsoverview'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['overview-edit'] );

		if( $this->EaseVars['generatemode'] == false ) 
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['overview-edit'] );
		else
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['overview'] );

		if( $this->EaseVars['generatemode'] == false )	$this->arrDocument['js_include']['easenewsdetailonload'] = $this->arrConf['js']['overview-onload'];

		// Parameter			
		$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
                
                    // Headline
                    $intHL = $_C->fctGetContent( $arrParams['Link']['lin_id'],'headlinelength' );
                    if( !$intHL ) $intHL = $this->arrConf['headlinelength'];
                    $strContent = _ParseDoc::fctTagReplace( "headlinelength", $intHL ,$strContent );
                    
                    // Text
                    $intTL = $_C->fctGetContent( $arrParams['Link']['lin_id'],'textlength' );
                    if( !$intTL ) $intTL = $this->arrConf['textlength'];
                    $strContent = _ParseDoc::fctTagReplace( "textlength", $intTL ,$strContent );
                    
                    // Detaillinktext
                    $strDetaillinktext = $_C->fctGetContent( $arrParams['Link']['lin_id'],'detaillinktext' );
                    if( !$strDetaillinktext ) $strDetaillinktext = $this->arrConf['detaillinktext'];
                    $strContent = _ParseDoc::fctTagReplace( "detaillinktext", $strDetaillinktext ,$strContent );

		// JavaScript-Function for Edit
		$this->ExtensionVars['JSEditStart'] = "fctEASENewsOverviewEditStart(". $arrParams['Link']['lin_id'] .")";
		$this->ExtensionVars['JSEditEnd'] = "fctEASENewsOverviewEditEnd(". $arrParams['Link']['lin_id'] .")";
	    }
	    else
	    {
		$strContent = "{NewsOverview}";
	    }
	    return $strContent;
	}
	
	public function fctOverviewElement( $arrParams=array() )
	{
	    
	    // Classes
	    $_C = new _Content();
	    
	    $this->arrDocument['css']['easenewsoverviewelement'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['overview-element'] );

	    if( $this->EaseVars['generatemode'] == false ) 
		$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['overview-element-edit'] );
	    else
		$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['overview-element'] );

	    // Get Detail-Link
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_related LEFT JOIN ". $this->Config['database']['table_prefix'] ."ext_easenews_news ON eer_een_id = een_id LEFT JOIN ". $this->Config['database']['table_prefix'] ."link ON een_lin_id = lin_id WHERE eer_lin_id = '". $arrParams['Link']['lin_id'] ."'" );
	    if( $this->arrSQL[0]['lin_doc_id'] )
	    {
		$_Doc = new _Documents;
		$arrURL = $_Doc->fctGetDocURL( $this->arrSQL[0]['lin_doc_id'] );
                $strDetaillinktext = $_C->fctGetContent( $arrParams['Link']['lin_parent'],'detaillinktext' );
                if( !$strDetaillinktext ) $strDetaillinktext = $this->arrConf['detaillinktext'];
		if( $this->EaseVars['generatemode'] == false ) 
		    $strDetailLink = '<a href="javascript:;" onclick="'. $arrURL['js'] .'">'. $strDetaillinktext .'</a>';
		else
		    $strDetailLink = '<a href="'. $arrURL['url'] .'">'. $strDetaillinktext .'</a>';
		$strDetailURL = $arrURL['url'];
	    }
	    
	    // Image?
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_parent = '". $arrParams['Link']['lin_id'] ."' AND lin_name = 'image' LIMIT 0,1" );
	    if( count( $this->arrSQL ) == 0 )
		$strContent = _ParseDoc::fctTagReplace( "image", "" ,$strContent );	    

	    // Replace
	    $strContent = _ParseDoc::fctTagReplace( "detaillink", $strDetailLink ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "detailurl", $strDetailURL ,$strContent );
	    
	    return $strContent;
	}
	
	public function fctDetail( $arrParams=array() )
	{
	    // Classes
	    $_C = new _Content();
	    
	    if( $this->EaseVars['dragmode'] == false )
	    {
		if( $this->EaseVars['generatemode'] == false ) 
		    $this->arrDocument['css']['easenewsdetail'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['detail-edit'] );

		if( $this->EaseVars['generatemode'] == false ) 
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['detail-edit'] );
		else
		    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['detail'] );

		if( $this->EaseVars['generatemode'] == false )	$this->arrDocument['js_include']['easenewsdetailonload'] = $this->arrConf['js']['detail-onload'];

		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_news WHERE een_lin_id = '". $arrParams['Link']['lin_id'] ."'" );
		if( $this->arrSQL[0]['een_startdate'] == "0" ) unset( $this->arrSQL[0]['een_startdate'] );
		if( $this->arrSQL[0]['een_enddate'] == "0" ) unset( $this->arrSQL[0]['een_enddate'] );

		// Parameter			
		$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
		$strContent = _ParseDoc::fctTagReplace( "startdate", $this->arrSQL[0]['een_startdate'] ,$strContent );
		$strContent = _ParseDoc::fctTagReplace( "enddate", $this->arrSQL[0]['een_enddate'] ,$strContent );

		// Elements
		if( $this->EaseVars['generatemode'] == false )
		{
		    $_PD = new _ParseDoc();
		    $_PD->boolNoEditmode = true;
		    $_PD->boolReturnContentArray = true;
		    $arrContent = $_PD->fctRenderLinks( 0,$arrParams['Link']['lin_id'],array( "news" => array( "name"=>"news")));

		    // Replace Elements
		    if( count( $arrContent['news'] ) )
		    {
			foreach( $arrContent['news'] as $strItem )
			    $strItems .= $strItem."\n";
		    }
		    $strContent = _ParseDoc::fctTagReplace( "news2", $strItems ,$strContent );
		}

		// JavaScript-Function for Edit
		$this->ExtensionVars['JSEditStart'] = "fctEASENewsEditStart(". $arrParams['Link']['lin_id'] .")";
		$this->ExtensionVars['JSEditEnd'] = "fctEASENewsEditEnd(". $arrParams['Link']['lin_id'] .")";
	    }
	    else
	    {
		$strContent = "{NewsDetail}";
	    }
	    
	    return $strContent;
	}
	
	// Optional
	public function fctDeleteItem( $intLinID, $arrParams=array() )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_news WHERE een_lin_id = '". $intLinID ."'" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		$intNewsID = $this->arrSQL[0]['een_id'];
		$this->fctDeleteRelated( $intNewsID );
		$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_news WHERE een_id = '". $intNewsID ."'" );
	    }
	}
	
	// Save News
	public function fctSaveNews()
	{
	    $intLinID = $this->fctClearValue( $_POST['id'] );
	    $strStartDate = $this->fctClearValue( $_POST['startdate'] );
	    $strEndDate = $this->fctClearValue( $_POST['enddate'] );
	    
	    if( !$strStartDate )$strStartDate = time();
	    else		$strStartDate = "'". $strStartDate ."'";

	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_news WHERE een_lin_id = '". $intLinID ."'" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		$intNewsID = $this->arrSQL[0]['een_id'];
		$this->fctDeleteRelated( $intNewsID );
		if( !$strStartDate )$strStartDate = $this->arrSQL[0]['een_startdate'];
		if( !$strEndDate )  $strEndDate = $this->arrSQL[0]['een_enddate'];
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenews_news SET een_startdate = ". $strStartDate .", een_enddate = '". $strEndDate ."' WHERE een_id = '". $intNewsID ."'" );
	    }
	    else
		$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easenews_news (een_lin_id,een_startdate,een_enddate) VALUES ('". $intLinID ."',". $strStartDate .",'". $strEndDate ."')" );

	    $this->fctCheckNews();
	}
	
	public function fctGetOrderID( $intNewsID )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_news ORDER BY een_startdate DESC" );
	    for( $i=0 ; $i<count( $this->arrSQL ) ; $i++ )
	    {
		if( $this->arrSQL[$i]['een_id'] == $intNewsID )
		    break;
	    }
	    return $i;
	}
	
	// Check if related created
	public function fctCheckNews()
	{
	    if( count( $this->arrConf['RelatedElements'] ) )
	    {
		// Extension-ID
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_name = 'EASENews'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    // All Pages with News-Related
		    $sqlRelated = " AND (";
		    unset( $strOR );
		    foreach( $this->arrConf['RelatedElements'] as $strRelated=>$strRelatedElement )
		    {
			$sqlRelated .= $strOR ."lco_value = '". $strRelated ."'";
			$strOR = " OR ";
		    }
		    $sqlRelated .= ")";

		    // Get all related links
		    $_Eer = new _GlobalFunctions;
		    $_Eer->fctQuery( "SELECT lin_id,lco_name,lco_value FROM ". $this->Config['database']['table_prefix'] ."link LEFT JOIN ". $this->Config['database']['table_prefix'] ."link_content ON lin_lco_id = lco_id WHERE lin_ext_id = '". $this->arrSQL[0]['ext_id'] ."'". $sqlRelated );
		    if( count( $_Eer->arrSQL ) )
		    {
			foreach( $_Eer->arrSQL as $RelLin )
			{
			    // Get all news without RelLinID
			    $_News = new _GlobalFunctions;
			    $_News->fctQuery( "SELECT een_id FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_news ORDER BY een_startdate" );
			    if( count( $_News->arrSQL ) )
			    {
				foreach( $_News->arrSQL as $arrNews )
				{
				    // Related exitsts?
				    $_Related = new _GlobalFunctions;
				    $_Related->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_related WHERE eer_een_id = '". $arrNews['een_id'] ."' AND eer_lin_parent = '". $RelLin['lin_id'] ."' LIMIT 0,1" );
				    if( count( $_Related->arrSQL ) == 0 )
				    {
					$this->fctInsertRelated( $arrNews['een_id'],$RelLin['lin_id'],$this->arrConf['RelatedElements'][$RelLin['lco_value']] );
				    }
				}
			    }
			}
		    }
		    
		}
	    }
	}
	
	// Insert Related
	public function fctInsertRelated( $intNewsID,$intLinParent,$strRelatedElement )
	{
	    // Classes
	    $_News = new _GlobalFunctions;
	    $_Content = new _GlobalFunctions;
	    $_Link = new _Link();
	    
	    // Vars
	    unset( $strText,$boolImagefound );

	    // Get all possible
	    if( count( $this->arrConf['RelatedImageExtensions'] ) || count( $this->arrConf['RelatedTextExtensions'] ) )
	    {
		// Parent-Link
		$_Link->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intLinParent ."'" );
		$arrParentLink = $_Link->arrSQL[0];
		
		// Get News-Content
		$_News->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_news WHERE een_id = '". $intNewsID ."'" );
		
		// Params for element-insert
		$arrParams['drag'] = "1";
		$arrParams['ext'] = $this->ExtensionVars['ext_id'];
		$arrParams['name'] = "item";
		$arrParams['value'] = $strRelatedElement;
		$arrParams['lin_parent'] = $arrParentLink['lin_id'];
		$arrParams['lin_name'] = "news";
		$arrParams['lin_order'] = $this->fctGetOrderID( $intNewsID );
		$arrParams['document'] = $arrParentLink['lin_doc_id'];
		$arrParams['lin_startdate'] = $_News->arrSQL[0]['een_startdate'];
		$arrParams['lin_enddate'] = $_News->arrSQL[0]['een_enddate'];
		$intElementLinID = $_Link->fctSaveIntern( $arrParams );
		unset( $arrParams );

		// Insert Related
		$_News->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easenews_related (eer_een_id,eer_lin_parent,eer_lin_id) VALUES ('". $intNewsID ."','". $intLinParent ."','". $intElementLinID ."')" );
		
		// Get News-Content
		$_News->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_news WHERE een_id = '". $intNewsID ."'" );
		if( count( $_News->arrSQL ) == 1 )
		{

		    // Get all link-childs
		    $arrLinkChilds = _LINK::fctGetAllLinkChilds( $_News->arrSQL[0]['een_lin_id'] );
		    if( count( $arrLinkChilds ) )
		    {			

			foreach( $arrLinkChilds as $arrLinkChild )
			{
			    // Text
			    if( count( $this->arrConf['RelatedTextExtensions'] ) )
			    {
				$sqlTextExtension = " AND (";
				unset( $strOR );
				foreach( $this->arrConf['RelatedTextExtensions'] as $strExtensionText )
				{
				    $sqlTextExtension .= $strOR ."ext_name = '". $strExtensionText ."'";
				    $strOR = " OR ";
				}
				$sqlTextExtension .= ")";
				$_Content->fctQuery( "SELECT con_value, ext_id FROM ". $this->Config['database']['table_prefix'] ."content LEFT JOIN ". $this->Config['database']['table_prefix'] ."link ON con_lin_id = lin_id LEFT JOIN ". $this->Config['database']['table_prefix'] ."extension ON lin_ext_id = ext_id WHERE lin_id = '". $arrLinkChild['lin_id'] ."'". $sqlTextExtension ." LIMIT 0,1" );
				if( count( $_Content->arrSQL ) == 1 )
				{
				    $strText .= $_Content->arrSQL[0]['con_value'];
				    $intTextExtID = $_Content->arrSQL[0]['ext_id'];
				}
			    }
			    
			    // Image
			    if( count( $this->arrConf['RelatedImageExtensions'] ) && $boolImagefound == false )
			    {
				$sqlImageExtension = " AND (";
				unset( $strOR );
				foreach( $this->arrConf['RelatedImageExtensions'] as $strExtensionImage )
				{
				    $sqlImageExtension .= $strOR ."ext_name = '". $strExtensionImage ."'";
				    $strOR = " OR ";
				}
				$sqlImageExtension .= ")";
				$_Content->fctQuery( "SELECT lin_id FROM ". $this->Config['database']['table_prefix'] ."content LEFT JOIN ". $this->Config['database']['table_prefix'] ."link ON con_lin_id = lin_id LEFT JOIN ". $this->Config['database']['table_prefix'] ."extension ON lin_ext_id = ext_id WHERE lin_id = '". $arrLinkChild['lin_id'] ."'". $sqlImageExtension ." LIMIT 0,1" );
				if( count( $_Content->arrSQL ) == 1 )
				{
				    $boolImagefound = true;
				    unset( $arrParams );
				    $arrParams['cid'] = $_Content->arrSQL[0]['lin_id'];
				    $arrParams['lin_parent'] = $intElementLinID;
				    $arrParams['lin_order'] = 0;
				    $arrParams['lin_name'] = 'image';
				    $_Link->fctCopyLink( $arrParams );
				}
			    }			    
			}
			
			if( $strText && $intTextExtID )
			{
			    $_C = new _Content;
			    
			    // Params from Parent-Link
			    $_Link->fctQuery( "SELECT lin_parent FROM ". $this->Config['database']['table_prefix'] ."link WHERE lin_id = '". $intElementLinID ."'" );
			    
			    // Headline

				// Params for element-insert & inser
				$arrParams['drag'] = "1";
				$arrParams['ext'] = $intTextExtID;
				$arrParams['name'] = "";
				$arrParams['value'] = "";
				$arrParams['lin_parent'] = $intElementLinID;
				$arrParams['lin_name'] = "headline";
				$arrParams['lin_order'] = "0";
				$arrParams['document'] = $arrParentLink['lin_doc_id'];
				$intHeadlineLinID = $_Link->fctSaveIntern( $arrParams );
				
				// Find
				$_PD = new _ParseDoc;
				unset( $_PD->arrTags,$_PD->arrNameTags );
				$_PD->fctFindTag( $strText,'<h1','</h1>' );
				if( !$_PD->arrTags )
				{
				    $_PD->fctFindTag( $strText,'<h2','</h2>' );
				    if( !$_PD->arrTags )
				    {
					$_PD->fctFindTag( $strText,'<h3','</h3>' );
					if( !$_PD->arrTags )
					{
					    $_PD->fctFindTag( $strText,'<h4','</h4>' );
					    if( !$_PD->arrTags )
					    {
						$_PD->fctFindTag( $strText,'<h5','</h5>' );
						if( !$_PD->arrTags )
						{
						    $_PD->fctFindTag( $strText,'<h6','</h6>' );
						    if( $_PD->arrTags ) $arrTags['6'][] = $_PD->arrTags;
						}
						else $arrTags['5'] = $_PD->arrTags;
					    }
					    else $arrTags['4'] = $_PD->arrTags;
					}
					else $arrTags['3'] = $_PD->arrTags;
				    }
				    else $arrTags['2'] = $_PD->arrTags;
				}
				else $arrTags['1'] = $_PD->arrTags;

				$intHeadlineLength = $_C->fctGetContent( $arrParentLink['lin_id'],'headlinelength' );
				if( !$intHeadlineLength ) $intHeadlineLength = $this->arrConf['headlinelength'];
				if( count( $arrTags ) )
				{
				    ksort( $arrTags );
				    foreach( $arrTags as $arrHeadlines )
				    {
					if( strlen( $arrHeadlines[0]['tag'] ) > ($intHeadlineLength-3) ) $strHeadline = $this->arrConf['headlinetagstart'].substr( trim( strip_tags( $arrHeadlines[0]['tag'] ) ),0,$intHeadlineLength )."...".$this->arrConf['headlinetagend'];
					else $strHeadline = $this->arrConf['headlinetagstart'].trim( strip_tags( $arrHeadlines[0]['tag'] ) ).$this->arrConf['headlinetagend'];
					break;
				    }
				}
				else 
				    $strHeadline = substr( strip_tags( trim( $strText ) ),0, ($intHeadlineLength-3) );
				
				// Save Content
				$_C->fctSaveContent( $intHeadlineLinID,'text', $strHeadline );
				
				// Delete headline from text
				$strText = str_replace( $arrHeadlines[0]['tag'] , "", $strText);
			    
			    // Text
				// Params for element-insert & insert
				$arrParams['drag'] = "1";
				$arrParams['ext'] = $intTextExtID;
				$arrParams['name'] = "";
				$arrParams['value'] = "";
				$arrParams['lin_parent'] = $intElementLinID;
				$arrParams['lin_name'] = "text";
				$arrParams['lin_order'] = "0";
				$arrParams['document'] = $arrParentLink['lin_doc_id'];
				$intTextLinID = $_Link->fctSaveIntern( $arrParams );
				
				// Save Content
				$intTextLength = $_C->fctGetContent( $arrParentLink['lin_id'],'textlength' );
				if( !$intTextLength ) $intTextLength = $this->arrConf['textlength'];
				if( strlen( $strText ) > ($intTextLength-3) ) $strText = substr( trim( strip_tags( $strText ) ),0,$intTextLength )."...";
				$_C->fctSaveContent( $intTextLinID,'text', '<p>'. strip_tags( $strText ) .'</p>' );
			}
		    }
		}
	    }
	}
	
	public function fctDeleteRelated( $intNewsID )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_related WHERE eer_een_id = '". $intNewsID ."'" );
	    if( count( $this->arrSQL ) )
	    {
		// Link
		$_Link = new _Link;
		foreach( $this->arrSQL as $arrRelated )
		{
		    $_Link->fctDeleteLink( $arrRelated['eer_lin_id'] );
		    $this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."ext_easenews_related WHERE eer_id = '". $arrRelated['eer_id'] ."'" );
		}
	    }
	}

    }
?>