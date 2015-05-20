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

    class EASENavigationBasic extends _Extensions
    {
        
	public function __construct()
	{
	    parent::__construct();
            
            // Templates
            $this->arrConf['tmpl']['navigation'] = "basic/templates/navigation.tmpl";
            $this->arrConf['tmpl']['extension-popup'] = "basic/templates/extension-popup.tmpl";
            $this->arrConf['tmpl']['extension-popup-message'] = "basic/templates/extension-popup-message.tmpl";
            $this->arrConf['tmpl']['extension-popup-edit'] = "basic/templates/extension-popup-edit.tmpl";            
            
            // CSS
            $this->arrConf['css']['extension-popup'] = "basic/css/extension-popup.css";
            $this->arrConf['css']['jcarousel'] = "basic/css/jcarousel.css";
            $this->arrConf['css']['jcarousellib'] = "basic/js/jcarousel/lib/jquery.jcarousel.min.js";
            
            // JS
            $this->arrConf['js']['extension-popup'] = "basic/js/extension-popup.js";
        }

	public function fctRenderCode( $arrParams=array() )
	{	    
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['navigation'] );
	    return $strContent;
	}
	
	// Optional
	public function fctExtensionPopup( $intLinID,$arrParams=array() )
	{
	    switch( $_GET['action'] )
	    {
		case "insert":	$this->fctInsert(); break;
		case "move":	$this->fctMove(); break;
		case "delete":	$this->fctDelete(); break;
		case "edit":	$this->fctGetEdit(); break;
		case "editsave":$this->fctSaveEdit(); break;
		case "docs":	$this->fctGetDocs(); break;
		default:	$this->fctEditMenu(); break;
	    }
	}
	
	// Save Item on Drop (Ajax-Request)
	public function fctInsert()
	{
	    if( $_GET['doc_id'] && $_GET['position'] && $_GET['position_id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $_GET['doc_id'] ."'" );
		if( count( $this->arrSQL ) )
		{
		    $_GF = new _GlobalFunctions();
		    $_GF->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links WHERE enl_id = '". $_GET['position_id'] ."'" );
		    
		    $arrDoc = $this->arrSQL[0];
		    if( !$arrDoc['doc_title'] ) $arrDoc['doc_title'] = $arrDoc['doc_name'].$arrDoc['doc_suffix'];
		    switch( $_GET['position'] )
		    {
			case "Before":
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_order = (enl_order+1) WHERE enl_parent = '". $_GF->arrSQL[0]['enl_parent'] ."' AND enl_order >= '". $_GF->arrSQL[0]['enl_order'] ."'" );
					$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links (enl_parent,enl_order,enl_name,enl_doc_id) VALUES ('". $_GF->arrSQL[0]['parent'] ."','". $_GF->arrSQL[0]['order'] ."','". $arrDoc['doc_title'] ."','". $arrDoc['doc_id'] ."')" );
					break;
			case "After":	    
   					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_order = (enl_order+1) WHERE enl_parent = '". $_GF->arrSQL[0]['enl_parent'] ."' AND enl_order >= '". ($_GF->arrSQL[0]['enl_order']+1) ."'" );
					$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links (enl_parent,enl_order,enl_name,enl_doc_id) VALUES ('". $_GF->arrSQL[0]['enl_parent'] ."','". ($_GF->arrSQL[0]['enl_order']+1) ."','". $arrDoc['doc_title'] ."','". $arrDoc['doc_id'] ."')" );
					break;
			case "Under":	
					$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links (enl_parent,enl_order,enl_name,enl_doc_id) VALUES ('". $_GF->arrSQL[0]['enl_id'] ."','0','". $arrDoc['doc_title'] ."','". $arrDoc['doc_id'] ."')" );
					break;
		    }
		    $this->fctQuery( "SELECT *,max(enl_id) as max FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links LEFT JOIN ". $this->Config['database']['table_prefix'] ."document ON enl_doc_id = doc_id" );
		    $arrReturn = array( "enl_name"=>$arrDoc['doc_title'],"enl_id"=>$this->arrSQL[0]['max'],"position"=>$_GET['position'],"position_id"=>$_GET['position_id'],"content"=>$this->fctGetLinkContent( $this->arrSQL[0] ) );
		}
	    }
	    else if( $_GET['doc_id'] && $_GET['position'] && $_GET['position_id'] == "0" )  // Insert First?
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links LIMIT 0,1" );
		if( count( $this->arrSQL ) == 0 )
		{
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document WHERE doc_id = '". $_GET['doc_id'] ."'" );
		    $arrDoc = $this->arrSQL[0];
		    if( !$arrDoc['doc_title'] ) $arrDoc['doc_title'] = $arrDoc['doc_name'].$arrD['doc_suffix'];
		    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links (enl_parent,enl_order,enl_name,enl_doc_id) VALUES ('0','0','". $arrDoc['doc_title'] ."','". $arrDoc['doc_id'] ."')" );
		    $this->fctQuery( "SELECT *,max(enl_id) as max FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links LEFT JOIN ". $this->Config['database']['table_prefix'] ."document ON enl_doc_id = doc_id" );
		    $arrReturn = array( "enl_name"=>$arrDoc['doc_title'],"enl_id"=>$this->arrSQL[0]['max'],"position"=>$_GET['position'],"position_id"=>$_GET['position_id'],"content"=>$this->fctGetLinkContent( $this->arrSQL[0] ) );
		}
	    }
	    echo json_encode( $arrReturn );
	    exit;
	}
	
	// Save Item on Drop (Ajax-Request)
	public function fctMove()
	{
	    if( $_GET['enl_id'] && $_GET['position'] && $_GET['position_id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links WHERE enl_id = '". $_GET['enl_id'] ."'" );
		if( count( $this->arrSQL ) )
		{
		    
		    $_GF = new _GlobalFunctions();
		    $_GF->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links WHERE enl_id = '". $_GET['position_id'] ."'" );
		    
		    $arrEnl = $this->arrSQL[0];
		    switch( $_GET['position'] )
		    {
			case "Before":
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_order = '9999' WHERE enl_id = '". $arrEnl['enl_id'] ."'" );
			    
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_order = (enl_order-1) WHERE enl_parent = '". $arrEnl['enl_parent'] ."' AND enl_order > '". $arrEnl['enl_order'] ."'" );
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_order = (enl_order+1) WHERE enl_parent = '". $_GF->arrSQL[0]['enl_parent'] ."' AND enl_order >= '". $_GF->arrSQL[0]['enl_order'] ."'" );
					
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_parent = '". $_GF->arrSQL[0]['enl_parent'] ."',enl_order = '". $_GF->arrSQL[0]['enl_order'] ."' WHERE enl_id = '". $arrEnl['enl_id'] ."'" );
					break;
			case "After":	    
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_order = '9999' WHERE enl_id = '". $arrEnl['enl_id'] ."'" );
			    
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_order = (enl_order-1) WHERE enl_parent = '". $arrEnl['enl_parent'] ."' AND enl_order > '". $arrEnl['enl_order'] ."'" );
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_order = (enl_order+1) WHERE enl_parent = '". $_GF->arrSQL[0]['enl_parent'] ."' AND enl_order > '". $_GF->arrSQL[0]['enl_order'] ."'" );
					
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_parent = '". $_GF->arrSQL[0]['enl_parent'] ."',enl_order = '". ($_GF->arrSQL[0]['enl_order']+1) ."' WHERE enl_id = '". $arrEnl['enl_id'] ."'" );
					break;
			case "Under":	
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_parent = '". $_GF->arrSQL[0]['enl_id'] ."',enl_order = '0' WHERE enl_id = '". $arrEnl['enl_id'] ."'" );
					$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_order = (enl_order-1) WHERE enl_parent = '". $arrEnl['enl_parent'] ."' AND enl_order > '". $arrEnl['enl_order'] ."'" );
					break;
		    }
		    $arrReturn = array( "enl_name"=>$arrEnl['enl_name'],"enl_id"=>$arrEnl['enl_id'],"position"=>$_GET['position'],"position_id"=>$_GET['position_id'] );
		}
	    }
	    echo json_encode( $arrReturn );
	    exit;
	}
	
	// Delete for AJAX-Request
	public function fctDelete()
	{
	    if( $_GET['id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links WHERE enl_id = '". $_GET['id'] ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    $this->fctDeleteChilds( $_GET['id'] );
    		    $this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links WHERE enl_id = '". $_GET['id'] ."'" );
		}
	    }
	    exit;
	}
	
	// Delete all Childs
	public function fctDeleteChilds( $intParentID )
	{
	    $_GF = new _GlobalFunctions();
	    if( $intParentID )
	    {
		$_GF->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links WHERE enl_parent = '". $intParentID ."'" );
		if( count( $this->arrSQL ) )
		{
		    foreach( $_GF->arrSQL as $arrEnl )
		    {
			$this->fctDeleteChilds( $arrEnl['enl_id'] );
			$_GF->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links WHERE enl_id = '". $arrEnl['enl_id'] ."'" );			
		    }
		}
	    }
	}
	
	// Get Item-Info for AJAX-Request
	public function fctGetEdit()
	{
	    if( $_GET['enl_id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links LEFT JOIN ". $this->Config['database']['table_prefix'] ."document ON enl_doc_id = doc_id WHERE enl_id = '". $_GET['enl_id'] ."'" );
		if( $this->arrSQL[0]['doc_title'] ) $strDocTitle = $this->arrSQL[0]['doc_title'].' ('. $this->arrSQL[0]['doc_name'].$this->arrSQL[0]['doc_suffix'] .')';
		else				    $strDocTitle = $this->arrSQL[0]['doc_name'].$this->arrSQL[0]['doc_suffix'];
		echo json_encode( array("enl_id"=>$this->arrSQL[0]['enl_id'],"enl_name"=>$this->arrSQL[0]['enl_name'],"enl_type"=>$this->arrSQL[0]['enl_type'],"doc_name"=>$strDocTitle,"enl_url"=>$this->arrSQL[0]['enl_url']) );
	    }
	    exit;
	}
	
	// Save Item-Info
	public function fctSaveEdit()
	{
	    if( $_GET['enl_id'] )
	    {
		if( strlen( $_GET['enl_name'] ) > 0 )
		{
		    $this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links SET enl_name = '". $_GET['enl_name'] ."',enl_url = '". $_GET['enl_url'] ."',enl_target = '". $_GET['enl_target'] ."' WHERE enl_id = '". $_GET['enl_id'] ."'" );
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links LEFT JOIN ". $this->Config['database']['table_prefix'] ."document ON enl_doc_id = doc_id WHERE enl_id = '". $_GET['enl_id'] ."'" );
		    echo json_encode( array("enl_id"=>$this->arrSQL[0]['enl_id'],"enl_name"=>$this->arrSQL[0]['enl_name'],"enl_type"=>$this->arrSQL[0]['enl_type'],"doc_name"=>$strDocTitle,"enl_url"=>$this->arrSQL[0]['enl_url'],'content'=>$this->fctGetLinkContent($this->arrSQL[0])) );   
		}
		else
		    echo json_encode( array("message"=>$this->fctReplaceLang( "{NameLength}" )) );   
	    }
	    exit;
	    //$this->fctURLRedirect( $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/extension-popup.php" );
	}
	
	public function fctGetDocs()
	{
	    // GET
	    if( $_GET['start'] ) $intStart = $_GET['start'];
	    else		 $intStart = 0;
	    if( $_GET['search'] == $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']]['Search'] )
		unset( $_SESSION['easevars']['easenavigation']['search'] );
	    else
		$_SESSION['easevars']['easenavigation']['search'] = $_GET['search'];
	    
	    // Documents
	    $arrDocuments = _Search::fctDocumentSearch( $_SESSION['easevars']['easenavigation']['search'] );
	    
	    if( count( $arrDocuments ) )
	    {
		$strDrags .= '<ul id="mycarousel" class="jcarousel-skin-ease">';
		foreach( $arrDocuments as $arrDoc )
		{
		    $strDrags .= '<li><div class="Doc" id="Doc-'. $arrDoc['doc_id'] .'">';
		    $strDrags .= '<div class="IconMove"></div>';
			if( $arrDoc['doc_title'] )
			    $strDrags .= "<h2>". $this->fctSetLength( $arrDoc['doc_title'],20) ."</h2>";
			else
			    $strDrags .= "<h2>{NoTitle}</h2>";
			$strDrags .= "<div class='Pad'><p><b>{Author}:</b> ". _USER::fctGetUserName( $arrDoc['doc_create_use_id'] ) .", ". _GlobalFunctions::fctFormatMysqlDate( $arrDoc['doc_create_date'] ) ."</p>";
			// Last Change
			if( $arrDoc['doc_changed_use_id'] != 0 ) $strDrags .= "<p><b>{LastChange}:</b> ". _USER::fctGetUserName( $arrDoc['doc_changed_use_id'] ) .", ". _GlobalFunctions::fctFormatMysqlDate( $arrDoc['doc_changed_date'] ) ."</p>";
			if( $arrDoc['doc_meta_description'] ) $strDrags .= "<p><b>{Description}:</b> ". $this->fctSetLength( $arrDoc['doc_meta_description'],100) ."</p>";
			if( $arrDoc['doc_first_text'] ) $strDrags .= "<p><b>{Content}:</b> ". $this->fctSetLength( $arrDoc['doc_first_text'],100) ."</p>";
		    $strDrags .= '</div></div></li>';
		}
		$strDrags .= '</ul>';
		
		// Language
		$strDrags = $this->fctReplaceLang( $strDrags );
	    }
	    echo $strDrags;
	    exit;
	}
	
	public function fctStringToLength( $strValue,$intLength )
	{
	    if( strlen( $strValue ) > $intLength )
	    {
		$strValue = substr( $strValue,0,($intLength-3) )."...";
	    }
	    return $strValue;
	}
	
	public function fctEditMenu()
	{
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup'] );
	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
	    $this->arrDocument['css_include'][] =  $this->arrConf['css']['jcarousel'];
	    $this->arrDocument['js_include'][] = $this->arrConf['css']['jcarousellib'];
	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup'];

	    // Message Dialog
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    
	    // Edit Dialog
	    $strContent = _ParseDoc::fctTagReplace( "edit", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-edit'] ) ,$strContent );
	    
	    if( $_SESSION['easevars']['easenavigation']['search'] )
		$strContent = _ParseDoc::fctTagReplace( "searchvalue", $_SESSION['easevars']['easenavigation']['search'] ,$strContent );
	    else
		$strContent = _ParseDoc::fctTagReplace( "searchvalue", '{Search}' ,$strContent );
	    
	    // Menu-Replace
	    $arrMenu = self::fctLoadMenuData();
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='top.fctEaseCloseReloadExtensionPopup()' class='Button'><span class='ButtonText'>{Close}</span></a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Menu
	    $strMenu = $this->fctGetDropData( $this->fctLoadMenuDataIntern() );
	    if( !$strMenu ) $strMenu = '<ul><li class="DropLi" id="After-0">{NoItem}</li></ul>';
	    $strContent = _ParseDoc::fctTagReplace( "menu", $strMenu ,$strContent );
	    
	    // Language
	    $this->arrDocument['js_language']['EaseNavigationLangTxt'] = $this->fctSetJSLangTxT( 'EaseNavigation' );
	    $strContent = $this->fctReplaceLang( $strContent );
	    
	    $this->arrDocument['body'][]  = $strContent;
	}
	
	public function fctGetDropData( $arrMenu,$intEbene=0 )
	{
	    if( count( $arrMenu ) )
	    {
		if( $intEbene == 0 )
		    $strMenu .= "<ul class='Active'>";
		else
		    $strMenu .= "<ul>";
		foreach( $arrMenu as $arrLinks )
		{
		    $strMenu .= '<li>'; 
		    $strMenu .= '<div class="Item" id="Item-'. $arrLinks['enl_id'] .'"><h2>'. $arrLinks['enl_name'] .'</h2>';
		    
		    $strMenu .= $this->fctGetLinkContent($arrLinks);
			
		    $strMenu .= '</div>';
		    $strMenu .= $this->fctGetDropData( $arrLinks['sub'],($intEbene+1) );
		    $strMenu .= '</li>';
		}
		$strMenu .= "</ul>";
		return $strMenu;
	    }
	}
	
	public function fctGetLinkContent( $arrLink )
	{
	    // Intern Document
	    if( $arrLink['enl_doc_id'] > 0 )
	    {
		$strContent .= '<div class="Pad">';
		    if( $arrLink['doc_title'] )
			$strContent .= "<p><b>{DocTitle}:</b> ". $this->fctSetLength( $arrLink['doc_title'], 20) ."</p>";
		    else
			$strContent .= "<p><b>{DocTitle}:</b> {NoTitle}</p>";
		    $strContent .= "<p><b>{Author}:</b> ". _USER::fctGetUserName( $arrLink['doc_create_use_id'] ) .", ". _GlobalFunctions::fctFormatMysqlDate( $arrLink['doc_create_date'] ) ."</p>";
		    // Last Change
		    if( $arrLink['doc_changed_use_id'] != 0 ) $strContent .= "<p><b>{LastChange}:</b> ". _USER::fctGetUserName( $arrLink['doc_changed_use_id'] ) .", ". _GlobalFunctions::fctFormatMysqlDate( $arrLink['doc_changed_date'] ) ."</p>";
		    if( $arrLink['doc_meta_description'] ) $strContent .= "<p><b>{Description}:</b> ". $this->fctSetLength( $arrLink['doc_meta_description'],100) ."</p>";
		    if( $arrLink['doc_first_text'] ) $strContent .= "<p><b>{Content}:</b> ". $this->fctSetLength( $arrLink['doc_first_text'],100) ."</p>";
		$strContent .= '</div>';
		
		// Language
		$strContent = $this->fctReplaceLang( $strContent );
		
		return $strContent;
	    }
	}
	
	// Load Menu Data from Database (Extern)
	public function fctLoadMenuData( $intParent=0,$boolStart=true, $boolActiveSub=false )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links WHERE enl_parent = '". $intParent ."' ORDER BY enl_order ASC" );
	    $intLinks = count( $this->arrSQL );
	    if( $intLinks > 0 )
	    {
		$i=0;
		foreach( $this->arrSQL as $arrLinks )
		{		    
		    // First or Last?
		    if( $i == 0 )
			$arrMenu[$i]['position'] = "First";
		    else if( $i == ($intLinks-1) )
			$arrMenu[$i]['position'] = "Last";
		    else
			$arrMenu[$i]['position'] = "Center";
		    
		    // Link
		    if( $arrLinks['enl_doc_id'] )
		    {
			// Active?
			if( $_SESSION['easevars']['document'] == $arrLinks['enl_doc_id'] || in_array( $_SESSION['easevars']['document'],$_SESSION['easevars']['easenavigation']['related_pages'][$arrLinks['enl_doc_id']] ) )
			{
			    $arrMenu[$i]['active'] = true;
			    $boolSetParentActive = true;
			}
			
			$arrURL = _Documents::fctGetDocURL( $arrLinks['enl_doc_id'] );
			$arrMenu[$i]['link_js'] = $arrURL['js'];
			$arrMenu[$i]['link_url'] = $arrURL['url'];
		    }
		    else
			$arrMenu[$i]['link'] = $arrLinks['enl_url'];
		    
		    // Name
		    $arrMenu[$i]['name'] = $arrLinks['enl_name'];
		    
		    // Sub
                    $arrMenu[$i]['sub'] = self::fctLoadMenuData( $arrLinks['enl_id'],false );
		    
		    // Active, if Sub has Active
		    if( $arrMenu[$i]['sub']['SetParentActive'] )
		    {
			$arrMenu[$i]['active'] = true;
			$boolSetParentActive = true;
			unset( $arrMenu[$i]['sub']['SetParentActive'] );
		    }
                    
                    if( $boolActiveSub == false && $arrMenu[$i]['active'] == false )
                        unset( $arrMenu[$i]['sub'] );
                    
		    $i++;
		}
		if( $boolSetParentActive == true && $boolStart == false )
		    $arrMenu['SetParentActive'] = true;
	    }
	    return $arrMenu;
	}
	
	// Load Menu Data from Database (Intern)
	public function fctLoadMenuDataIntern( $intParent=0 )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easenavigation_links LEFT JOIN ". $this->Config['database']['table_prefix'] ."document ON enl_doc_id = doc_id WHERE enl_parent = '". $intParent ."' ORDER BY enl_order ASC" );
	    if( count( $this->arrSQL ) )
	    {
		$i=0;
		foreach( $this->arrSQL as $arrLinks )
		{
		    $arrMenu[$i] = $arrLinks;
		    $arrMenu[$i]['sub'] = self::fctLoadMenuDataIntern( $arrLinks['enl_id'] );
		    $i++;
		}
	    }
	    return $arrMenu;
	}
	
	// Return the Menu-String
	public function fctReturnMenu( $arrNavigation,$intMaxLevel=99,$intLevel=1,$intStartLevel=0,$boolEdit=true )
	{
	    
	    if( count( $arrNavigation ) && $intLevel <= $intMaxLevel )
	    {
		if( $intStartLevel < $intLevel )
		    $strContent .= '<ul class="NavLevel'. $intLevel .'">';
		
		foreach( $arrNavigation as $arrNav )
		{
		    if( $intStartLevel < $intLevel )
		    {
			$strContent .= '<li class="'. $arrNav['position'];
			if( $arrNav['active'] )
			    $strContent .= ' Active';
			if( $this->EaseVars['generatemode'] == true )
			    $strContent .= '"><a href="'. $arrNav['link_url'] .'"';
			else
			    $strContent .= '"><a href="javascript:;" onclick="'. $arrNav['link_js'] .'"';
			if( $arrNav['active'] )
			    $strContent .= ' class="Active"';
			$strContent .= '><span>'. $arrNav['name'] .'</span></a>';
		    }
		    
		    if( $arrNav['sub'] )    $strContent .= $this->fctReturnMenu( $arrNav['sub'], $intMaxLevel, ($intLevel+1),$intStartLevel,false );
		    
		    if( $intStartLevel < $intLevel )		    
			$strContent .= '</li>';
		}
		if( $intStartLevel < $intLevel )
		$strContent .= "</ul>";
		
		// Menu: Edit-Button
		if( $boolEdit == true && $this->EaseVars['generatemode'] == false )
		{
		    $intExtID = _Extensions::fctGetExtensionID( "EASENavigation" );
		    $arrURL = _ParseDoc::fctGetExtensionLink( array( 'ext_id'=>$intExtID ) );
		    $strContent = '<div class="easeEditBox"><div class="easeEdit" onclick="'.$arrURL['js']  .'"></div>'. $strContent .'<div class="Clear"></div></div>';
		}
		
		return $strContent;
	    }
	}

    }

?>