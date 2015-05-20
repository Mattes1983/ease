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

    class EASEImageBasic extends _Extensions
    {
        
        public function __construct()
	{
	    parent::__construct();
            
            // Basic
            $this->arrConf['basic']['content-images'] = "content-images";
            
            // Templates
            $this->arrConf['tmpl']['image-preview-noedit'] = "basic/templates/image-preview-noedit.tmpl";
            $this->arrConf['tmpl']['image'] = "basic/templates/image.tmpl";
            $this->arrConf['tmpl']['image-preview'] = "basic/templates/image-preview.tmpl";
            $this->arrConf['tmpl']['extension-popup'] = "basic/templates/extension-popup.tmpl";
            $this->arrConf['tmpl']['extension-popup-image'] = "basic/templates/extension-popup-image.tmpl";
            $this->arrConf['tmpl']['extension-popup-upload'] = "basic/templates/extension-popup-upload.tmpl";
            $this->arrConf['tmpl']['extension-popup-message'] = "basic/templates/extension-popup-message.tmpl";
            $this->arrConf['tmpl']['extension-popup-bigger'] = "basic/templates/extension-popup-bigger.tmpl";
            $this->arrConf['tmpl']['extension-popup-group'] = "basic/templates/extension-popup-group.tmpl";
            $this->arrConf['tmpl']['extension-popup-edit'] = "basic/templates/extension-popup-edit.tmpl";
            
            // CSS
            $this->arrConf['css']['image-preview'] = "basic/css/preview.css";
            $this->arrConf['css']['extension-popup'] = "basic/css/extension-popup.css";
            $this->arrConf['css']['jcarousel'] = "basic/css/jcarousel.css";
            $this->arrConf['css']['jcrop'] = "basic/js/jcrop/css/jquery.Jcrop.css";
            
            // JS
            $this->arrConf['js']['extension-popup'] = "basic/js/extension-popup.js";
            $this->arrConf['js']['jcarousel'] = "basic/js/jcarousel/lib/jquery.jcarousel.min.js";
            $this->arrConf['js']['jcrop'] = "basic/js/jcrop/js/jquery.Jcrop.js";
        }
	
	public function fctRenderCode( $arrParams=array() )
	{
	    
	    // Only Preview-Image and no Buttons
	    if( $arrParams['type'] == "preview" && $this->EaseVars['generatemode'] == false )
	    {
		$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['image-preview-noedit'] );
		$this->arrDocument['css']['easeimagepreview'] = self::fctLoadFile(dirname(__FILE__) ."/". $this->arrConf['css']['image-preview'] );

		// Size
		if( $arrParams['ParentAttributes']['width'] && $arrParams['ParentAttributes']['height'] )
		    $strContent = _ParseDoc::fctTagReplace( "styles", 'style="width: '. $arrParams['ParentAttributes']['width'] .'px;height: '. $arrParams['ParentAttributes']['height'] .'px;"' ,$strContent );
		else if( $arrParams['ParentAttributes']['width'] )
		    $strContent = _ParseDoc::fctTagReplace( "styles", 'style="width: '. $arrParams['ParentAttributes']['width'] .'px;"' ,$strContent );
		else if( $arrParams['ParentAttributes']['height'] )
		    $strContent = _ParseDoc::fctTagReplace( "styles", 'style="height: '. $arrParams['ParentAttributes']['height'] .'px;"' ,$strContent );
		else
		    $strContent = _ParseDoc::fctTagReplace( "styles", '' ,$strContent );
		
		return $strContent;
	    }
	    
	    // Ask if in Drag&Drop-Mode
	    if( $this->EaseVars['dragmode'] == false )
	    {
		
		// Classes
		$_C = new _Content();

		// Get Data
		$intImageID = $_C->fctGetContent($arrParams['Link']['lin_id'],'id' );
		if( $intImageID )		    
		{
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $intImageID ."'" );
		    if( count( $this->arrSQL ) == 1 )
		    {
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['image'] );
			$strImagePath = $this->fctPushImage( $intImageID, $arrParams['Link']['lin_id'], $arrParams['ParentAttributes'] );
			
			// Alt-Tag
			$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $intImageID ."'" );		    
			$strContent = _ParseDoc::fctTagReplace( "attributes", 'src="'. $strImagePath .'?'. md5(time()) .'" alt="'. $this->arrSQL[0]['eii_keywords'] .'"' ,$strContent );

			// Save Image-URL
			_Files::fctSaveDocFile( $strImagePath );
			
			return $strContent;
		    }
		}
	    }
            
            // No Preview in generatemode
            if( $this->EaseVars['generatemode'] == true )
                return false;
	    
	    // load Preview
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['image-preview'] );
	    $this->arrDocument['css']['easeimagepreview'] = self::fctLoadFile(dirname(__FILE__) ."/". $this->arrConf['css']['image-preview'] );

	    // Size
	    if( $arrParams['ParentAttributes']['width'] && $arrParams['ParentAttributes']['height'] )
		$strContent = _ParseDoc::fctTagReplace( "styles", 'style="width: '. $arrParams['ParentAttributes']['width'] .'px;height: '. $arrParams['ParentAttributes']['height'] .'px;"' ,$strContent );
	    else if( $arrParams['ParentAttributes']['width'] )
		$strContent = _ParseDoc::fctTagReplace( "styles", 'style="width: '. $arrParams['ParentAttributes']['width'] .'px;"' ,$strContent );
	    else if( $arrParams['ParentAttributes']['height'] )
		$strContent = _ParseDoc::fctTagReplace( "styles", 'style="height: '. $arrParams['ParentAttributes']['height'] .'px;"' ,$strContent );
	    else
		$strContent = _ParseDoc::fctTagReplace( "styles", '' ,$strContent );
	    
	    return $strContent;
	}
	
	public function fctGetAllGroups()
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_groups ORDER BY eig_name" );
	    if( count( $this->arrSQL ) )
	    {
		foreach( $this->arrSQL as $arrGroup )
		    $arrReturn[$arrGroup['eig_id']] = array('id'=>$arrGroup['eig_id'],'name'=>$arrGroup['eig_name']);
		return $arrReturn;
	    }
		else return false;
	}
	
	// Returns all images (optional: group selection)
	public function fctGetAllImages( $intLinID, $intGroupID=false )
	{
	    
	    // Group?
	    if( $intGroupID )	$strWhere .= "WHERE eii_eig_id = '". $intGroupID ."'";
	    
	    $_GF = new _GlobalFunctions();
	    
	    $_GF->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images ". $strWhere ." ORDER BY eii_id" );
	    if( count( $_GF->arrSQL ) )
	    {
		foreach( $_GF->arrSQL as $arrImage )
		    $arrReturn[$arrImage['eii_id']] = array(
			'id'=>$arrImage['eii_id'],
			'name'=>$arrImage['eii_name'],
			'suffix'=>$arrImage['eii_suffix'],
			'description'=>$arrImage['eii_description'],
			'width'=>$arrImage['eii_width'],
			'height'=>$arrImage['eii_height'],
			'url'=>$this->fctPushImage( $arrImage['eii_id'],$intLinID )
		    );
		return $arrReturn;
	    }
		else return false;
	}
	
	// Optional
	public function fctDeleteItem( $intLinID, $arrParams=array() )
	{
	    // Delete old Linked Image
	    $_C = new _Content();
	    $intImageID = $_C->fctGetContent( $intLinID,'id' );
	    if( !$intImageID )	$intImageID = 0;

	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $intImageID ."'" );
	    if( count( $this->arrSQl[0] ))
	    {
		$strOldFile = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/images/". $intLinID ."-". $this->arrSQL[0]['eii_id']."-". $this->arrSQL[0]['eii_name'] .".". $this->arrSQL[0]['eii_suffix'];
		if( file_exists( $strOldFile ) ) @unlink( $strOldFile );
	    }
	}
	
	public function fctPushImage( $intImageID, $intLinID, $arrParams )
	{

	    _ParseDoc::fctMkdir($this->Config['path']['cms'] . $this->Config['path']['project'] ."/images",false);
	    
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $intImageID ."'" );
	    $strSource = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-image/basic/". $this->arrConf['basic']['content-images'] ."/". $intImageID.".".$this->arrSQL[0]['eii_suffix'];
	    $strDestiny = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/images/". $intLinID ."-". $intImageID ."-". $this->arrSQL[0]['eii_name'] .".". $this->arrSQL[0]['eii_suffix'];

	    if( file_exists( $strDestiny ) )
	    {
		$arrImageSize = getimagesize( $strDestiny );
		if( $arrParams['width'] > 0 && $arrParams['width'] != $arrImageSize[0] )	unlink( $strDestiny );
		if( $arrParams['height'] > 0 && $arrParams['height'] != $arrImageSize[1] )	unlink( $strDestiny );
	    }

	    // Delete the old Image and copy the new Image
	    if( !file_exists( $strDestiny ) )
	    {
		copy( $strSource,$strDestiny );

		// Resize, if needed
		if( $arrParams['width'] > 0 && $arrParams['width'] < $this->arrSQL[0]['eii_width'] )	$intNewWidth = $arrParams['width'];
		if( $arrParams['height'] > 0 && $arrParams['height'] < $this->arrSQL[0]['eii_height'] )	$intNewHeight = $arrParams['height'];
		if( $intNewWidth || $intNewHeight )
		{
		    $this->fctCreateThumbnail( $strDestiny,$intNewWidth,$intNewHeight );
		}
	    }

	    return "images/". $intLinID ."-". $intImageID ."-". $this->arrSQL[0]['eii_name'] .".".$this->arrSQL[0]['eii_suffix'];
	}

	public function fctCreateThumbnail( $strImgSource,$intWidth,$intHeight,$intX=0,$intY=0 )
	{

	    if ( file_exists( $strImgSource ) )
	    {
		$arrImageSize = getimagesize( $strImgSource );
		
		if( $intWidth && $intHeight )
		{
		    // Maximize to Size
		    if ( $arrImageSize[0]/$arrImageSize[1] > $intWidth/$intHeight) 
		    {
			$intHeight = floor ($intWidth * $arrImageSize[1] / $arrImageSize[0]);
		    }
		    else 
		    {
			$intWidth = floor( $intHeight * $arrImageSize[0] / $arrImageSize[1]);
		    }
		}
		else if( $intWidth )
		{
		    if( $arrImageSize[0] > $arrImageSize[1] )
			$intHeight = floor ($intWidth / ( $arrImageSize[0] / $arrImageSize[1] ) );
		    else
			$intHeight = floor ($intWidth * ( $arrImageSize[1] / $arrImageSize[0] ) );
			
		}
		else if( $intHeight )
		{
		    if( $arrImageSize[0] > $arrImageSize[1] )
			$intWidth = floor ($intHeight * ( $arrImageSize[0] / $arrImageSize[1] ) );
		    else
			$intWidth = floor ($intHeight / ( $arrImageSize[1] / $arrImageSize[0] ) );
		}

		switch( strtolower( strrchr( $strImgSource , ".") ) )
		{
		    case ".jpg":
		    case ".jpeg":   $ImageObj = imagecreatefromjpeg( $strImgSource ); break;
		    case ".gif":    $ImageObj = imagecreatefromgif( $strImgSource ); break;
		    case ".png":    $ImageObj = imagecreatefrompng( $strImgSource ); break;
		}
		$TempObj = imagecreatetruecolor( $intWidth, $intHeight );

		imagecopyresampled( $TempObj, $ImageObj, 0, 0, $intX, $intH, $intWidth, $intHeight, $arrImageSize[0],$arrImageSize[1] );

		switch( strtolower( strrchr( $strImgSource , ".") ) )
		{
		    case ".jpg":
		    case ".jpeg":   imagejpeg( $TempObj,$strImgSource,100 ); break;
		    case ".gif":   imagegif( $TempObj,$strImgSource ); break;
		    case ".png":   imagepng( $TempObj,$strImgSource,0 ); break;
		}
	    }	    
	}
	
	// Optional
	public function fctExtensionPopup( $intLinID,$arrParams=array() )
	{

	    switch( $_GET['action'] )
	    {
		case "biggerinfo":	$this->fctBiggerInfo(); break;
		case "getimage":	$this->fctGetImage(); break;
		case "delete":		$this->fctDelete(); break;
		case "upload":		$this->fctUpload(); break;
		case "saveedit":	$this->fctSaveEdit(); break;
		case "getedit":		$this->fctGetEdit(); break;
		case "getgroups":	$this->fctGetGroups(); break;
		case "setgroup":	$this->fctSetGroup(); break;
		case "showgroups":	$this->fctShowGroups(); break;
		case "setimagetogroup":	$this->fctSetImageToGroup(); break;
		case "getgroup":	$this->fctGetGroup(); break;
		case "addgroup":	$this->fctAddGroup(); break;
		case "editgroup":	$this->fctEditGroup(); break;
		case "deletegroup":	$this->fctDeleteGroup(); break;
		default:		$this->fctShowImages( $intLinID,$arrParams=array() ); break;
	    }
	        
	}
	
	public function fctDeleteGroup()
	{
	    $this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easeimage_images SET eii_eig_id = '0' WHERE eii_eig_id = '". $_GET['group'] ."'" );
	    $this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_groups WHERE eig_id = '". $_GET['group'] ."'" );
	}
	
	public function fctGetGroup()
	{
	    if( $_GET['eig_id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_groups WHERE eig_id = '". $_GET['eig_id'] ."'" );
		if( count( $this->arrSQL ) )
		{
		    echo json_encode( array('eig_name'=>$this->arrSQL[0]['eig_name']) );
		}    
	    }
	    exit;
	}
	
	public function fctEditGroup()
	{
	    if( strlen( $_GET['eig_name'] ) > 0 )
	    {
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easeimage_groups SET eig_name = '". $_GET['eig_name'] ."' WHERE eig_id = '". $_GET['eig_id'] ."'" );
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_groups WHERE eig_id = '". $_GET['eig_id'] ."'" );
		echo json_encode( array( 'eig_name'=>$this->arrSQL[0]['eig_name'] ) );
	    }
	    else
	    {
		echo json_encode( array( 'message'=>$this->fctReplaceLang( '{NameLength}' ) ) );
	    }
	    exit;
	}
	
	public function fctAddGroup()
	{
	    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easeimage_groups (eig_name) VALUES ('". $_GET['group'] ."')" );
	    echo $this->mysql_insert_id;
	    exit;
	}
	
	public function fctSetImageToGroup()
	{	    
	    $this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easeimage_images SET eii_eig_id = '". $_GET['group'] ."' WHERE eii_id = '". $_GET['image'] ."'" );
	    exit;
	}
	
	public function fctSetGroup()
	{
	    $_SESSION['easevars']['easeimage']['group'] = $_GET['group'];
	}
	
	public function fctShowGroups()
	{
	    if( $_GET['showgroups'] == 'false' )
		unset( $_SESSION['easevars']['easeimage']['showgroups'] );
	    else
		$_SESSION['easevars']['easeimage']['showgroups'] = $_GET['showgroups'];
	}
	
	public function fctGetGroups()
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_groups" );
	    if( count( $this->arrSQL ) )
	    {
		$strContent .= '<ul id="mycarousel" class="jcarousel-skin-ease">';
		$strContent .= '<li><div class="Group';
		if( !$_SESSION['easevars']['easeimage']['group'] ) $strContent .= ' GroupActive';
		$strContent .= '" id="Group-0" onclick="fctShowGroup(0)"><span class="Table"><span class="TableCell">{NoGroup}</span></span></div></li>';
		foreach( $this->arrSQL as $arrGroups )
		{
		    $strContent .= '<li><div class="Group';
		    if( $_SESSION['easevars']['easeimage']['group'] == $arrGroups['eig_id'] )  $strContent .= ' GroupActive';
		    $strContent .= '" id="Group-'. $arrGroups['eig_id'] .'"><div class="Button"><div class="IconDelete" onclick="fctDeleteGroup('.$arrGroups['eig_id'].');"></div><div class="IconEdit" onclick="fctEditGroup('.$arrGroups['eig_id'].');"></div></div><span class="Table" onclick="fctShowGroup('. $arrGroups['eig_id'] .')"><span class="TableCell">'. $arrGroups['eig_name'] .'</span></span></div></li>';
		}
		$strContent .= '</ul>';
	    }
	    $strContent = $this->fctReplaceLang( $strContent );
	    echo $strContent;
	    exit;
	}
	
	// Image-Bigger Info for Ajax
	public function fctBiggerInfo()
	{
	    if( $_GET['id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $_GET['id'] ."'" );
		if( count( $this->arrSQL ) )
		{
		    $strInfo.= "<p><b>{Document}:</b> ". $this->arrSQL[0]['eii_name'] .".". $this->arrSQL[0]['eii_suffix'] ."</p>";
		    if( $this->arrSQL[0]['eii_keywords'] )
			$strInfo.= "<p><b>{Keywords1}:</b> ". $this->arrSQL[0]['eii_keywords'] ."</p>";
		    $strInfo.= "<p><b>{Width}:</b> ". $this->arrSQL[0]['eii_width'] ." Pixel</p>";
		    $strInfo.= "<p><b>{Height}:</b> ". $this->arrSQL[0]['eii_height'] ." Pixel</p>";
		    
		    // Language
		    $strInfo = $this->fctReplaceLang( $strInfo );
		    
		    echo $strInfo;
		}
	    }
	    exit;
	}
	
	// GetImage for Ajax
	public function fctGetImage()
	{
	    
	    // Vars
	    $strExtensionHTTP = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-image/basic";
	    $strExtensionServerImgSrc = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-image/basic/". $this->arrConf['basic']['content-images'];
	    
	    // GET
	    if( $_GET['start'] ) $intStart = $_GET['start'];
	    else		 $intStart = 0;
	    if( $_GET['search'] == $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']]['Search'] )
		unset( $_SESSION['easevars']['easeimage']['search'] );
	    else
		$_SESSION['easevars']['easeimage']['search'] = $_GET['search'];
	    
	    // Active?
	    if( $_SESSION['easevars']['easeimage']['lin_id'] )
	    $intImageID = _Content::fctGetContent( $_SESSION['easevars']['easeimage']['lin_id'] );
	    
	    if( $_SESSION['easevars']['easeimage']['search'] )
	    {
		if( !$strWhere )$strWhere = " WHERE ";
		else		$strWhere .= " AND ";
		$strWhere .= " (eii_name like '%". $_SESSION['easevars']['easeimage']['search'] ."%' OR eii_keywords like '%". $_SESSION['easevars']['easeimage']['search'] ."%')";
	    }
	    
	    if( $_SESSION['easevars']['easeimage']['group'] )
	    {
		if( !$strWhere )$strWhere = " WHERE ";
		else		$strWhere .= " AND ";
		$strWhere .= " eii_eig_id = '". $_SESSION['easevars']['easeimage']['group'] ."' ";
	    }
	    else
	    {
		if( !$strWhere )$strWhere = " WHERE ";
		else		$strWhere .= " AND ";
		$strWhere .= " eii_eig_id = '0' ";
	    }

	    // Get all Images & Replace
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images ". $strWhere ." ORDER BY eii_id DESC LIMIT ". $intStart .",1" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		$strImage = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-image'] );
		if( $this->arrSQL[0]['eii_id'] == $intImageID )
		    $strImage = _ParseDoc::fctTagReplace( "active",'ImageActive' ,$strImage );
                else
                    $strImage = _ParseDoc::fctTagReplace( "active",'' ,$strImage );
		$strImage = _ParseDoc::fctTagReplace( "id", $this->arrSQL[0]['eii_id'] ,$strImage );
		if( !$_SESSION['easevars']['easeimage']['lin_id'] )
		    $strImage = _ParseDoc::fctTagReplace( "usehide", " Hide" ,$strImage );
		else
		    $strImage = _ParseDoc::fctTagReplace( "usehide", "" ,$strImage );
		$strImage = _ParseDoc::fctTagReplace( "onclick", "fctEaseSave('". $_SESSION['easevars']['easeimage']['lin_id'] ."',". $this->arrSQL[0]['eii_id'] .");" ,$strImage );
		$strImage = _ParseDoc::fctTagReplace( "delete", "fctDeleteImage(". $this->arrSQL[0]['eii_id'] .");" ,$strImage );
		$strImage = _ParseDoc::fctTagReplace( "bigger", "fctOpenBigger(". $this->arrSQL[0]['eii_id'] .",'". $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-image/basic/". $this->arrConf['basic']['content-images'] ."/".$this->arrSQL[0]['eii_id'].".".$this->arrSQL[0]['eii_suffix'] ."?". md5(time()) ."',". $this->arrSQL[0]['eii_width'] .",". $this->arrSQL[0]['eii_height'] .");" ,$strImage );
		$strImage = _ParseDoc::fctTagReplace( "edit", "fctOpenEdit(". $this->arrSQL[0]['eii_id'] .",'". $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-image/basic/". $this->arrConf['basic']['content-images'] ."/".$this->arrSQL[0]['eii_id'].".".$this->arrSQL[0]['eii_suffix'] ."?". md5(time()) ."',". $this->arrSQL[0]['eii_width'] .",". $this->arrSQL[0]['eii_height'] .");" ,$strImage );
		$strImage = _ParseDoc::fctTagReplace( "src", $strExtensionHTTP."/php/thumbnail.php?eii_id=". $this->arrSQL[0]['eii_id'] ,$strImage );
		
		// Language
		$strImage = $this->fctReplaceLang( $strImage );
		
		echo $strImage;
	    }
	    exit;
	    
	}
	
	// Delete for AJAX-Request
	public function fctDelete()
	{	    
	    if( $_GET['id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $_GET['id'] ."'" );
		if( count( $this->arrSQL ) == 1 )
		{
		    
		    // Vars
		    $strFile = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-image/basic/". $this->arrConf['basic']['content-images'] ."/". $this->arrSQL[0]['eii_id'] .".". $this->arrSQL[0]['eii_suffix'];
		    @unlink( $strFile  );
		    $this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $this->arrSQL[0]['eii_id'] ."'" );
		}
	    }
	    exit;
	}
	
	public function fctUpload()
	{
	    
	    // Vars
	    $strExtensionServerImgSrc = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-image/basic/". $this->arrConf['basic']['content-images'];
	    $arrFormats = array( "jpg","jpeg","gif","png" );
	    
	    if( count( $_FILES ) )
	    {
		$intID = 1;
		$this->fctQuery( "SELECT max(eii_id) as max FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images" );
		if( count( $this->arrSQL ) ) $intID = ($this->arrSQL[0]['max']+1);
                		
		foreach( $_FILES as $key=>$elem )    // Get all Files
		{	
		    $arrFile = $_FILES[$key];
		    $arrName = explode( ".", $arrFile['name'] );	    
		    $strSuffix = strtolower( $arrName[(count($arrName)-1)] );
		    
		    if( in_array( $strSuffix,$arrFormats ) )	// Check Format
		    {

			if( $arrFile['tmp_name'] )
			{
			    if ( @is_uploaded_file( $arrFile['tmp_name'] ) )
			    {
				if( @move_uploaded_file( $arrFile['tmp_name'], $strExtensionServerImgSrc."/". $intID .".". $strSuffix ) )
				{
				    unset( $_SESSION['easevars']['easeimage']['search'] );
				    $arrImageSize = getimagesize( $strExtensionServerImgSrc."/". $intID .".". $strSuffix );
				    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easeimage_images (eii_id,eii_eig_id,eii_name,eii_suffix,eii_keywords,eii_width,eii_height) VALUES ('". $intID ."','". addslashes( $_POST['group'] ) ."','". $this->fctClearName( strtolower( $arrName[0] ) ) ."','".  strtolower( $arrName[(count($arrName)-1)] ) ."','". addslashes( $_POST['keywords'] ) ."','". $arrImageSize[0] ."','". $arrImageSize[1] ."')" );
				}
			    }
			}
		    }
		}
	    }
	    $this->fctURLRedirect( $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] ."/extension-popup.php" );
	}
	
	public function fctClearName( $strName )
	{
	    // In Array umwandeln
	    for( $i=0 ; $i < strlen( $strName ) ; $i++ )
	    {
		$character = substr( $strName,$i,1 );
		if( preg_match( "/[a-zA-Z0-9\_\-]/",$character ) )
		    $arrName[$i] = $character;
	    }
	    unset( $strName );
	    $strName = implode( "",$arrName );
	    
	    return $strName;
	}
	
	// Get ImageEdit-Values
	public function fctGetEdit()
	{
	    if( $_GET['eii_id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $_GET['eii_id'] ."'" );
		echo json_encode( array("eii_name"=>$this->arrSQL[0]['eii_name'],"eii_keywords"=>$this->arrSQL[0]['eii_keywords']) );
	    }
	    exit;
	}
	
	public function fctCopyImage( $intImageID )
	{
	    $strExtensionServerImgSrc = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-image/basic/". $this->arrConf['basic']['content-images'];

	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $intImageID ."'" );
	    if( count( $this->arrSQL ) == 1 )
	    {
		$strSuffix = $this->arrSQL[0]['eii_suffix'];
		$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easeimage_images (eii_name,eii_suffix,eii_keywords,eii_width,eii_height) VALUES ('". $this->arrSQL[0]['eii_name'] ."','".  $this->arrSQL[0]['eii_suffix'] ."','". $this->arrSQL[0]['eii_keywords'] ."','". $this->arrSQL[0]['eii_width'] ."','". $this->arrSQL[0]['eii_height'] ."')" );
		$intNewImageID = $this->mysql_insert_id;
		copy( $strExtensionServerImgSrc."/". $intImageID .".". $strSuffix,$strExtensionServerImgSrc."/". $intNewImageID .".". $strSuffix );
		return $intNewImageID;
	    }
	}
	
	// Save Image-Data
	public function fctSaveEdit()
	{
	    // Vars
	    $intImageID = $_POST['eii_id'];
	    
	    // Copy
	    if( $_POST['copy'] == 1 )
	    {
		$intImageID = $this->fctCopyImage( $intImageID );
	    }
	    
	    // Change Settings
	    if( $intImageID )
	    {
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easeimage_images SET eii_name = '". addslashes( strip_tags( $_POST['filename'] ) ) ."', eii_description = '". addslashes( strip_tags( $_POST['description'] ) ) ."', eii_keywords = '". addslashes( strip_tags( $_POST['keywords'] ) ) ."' WHERE eii_id = '". $intImageID ."'" );
	    }
	    
	    // Change size
	    if( $intImageID && $_POST['w'] && $_POST['h'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $intImageID ."'" );
		$strImgSource = $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['extension'] ."/ease-image/basic/". $this->arrConf['basic']['content-images'] ."/". $this->arrSQL[0]['eii_id'].".".$this->arrSQL[0]['eii_suffix'];
		
		switch( strtolower( strrchr( $strImgSource , ".") ) )
		{
		    case ".jpg":
		    case ".jpeg":   $ImageObj = imagecreatefromjpeg( $strImgSource ); break;
		    case ".gif":    $ImageObj = imagecreatefromgif( $strImgSource ); break;
		    case ".png":    $ImageObj = imagecreatefrompng( $strImgSource ); break;
		}
		$TempObj = imagecreatetruecolor( $_POST['w'], $_POST['h'] );

		imagecopyresampled($TempObj,$ImageObj,0,0,$_POST['x'],$_POST['y'],$_POST['w'],$_POST['h'],$_POST['w'],$_POST['h']);
		
		switch( strtolower( strrchr( $strImgSource , ".") ) )
		{
		    case ".jpg":
		    case ".jpeg":   imagejpeg( $TempObj,$strImgSource,100 ); break;
		    case ".gif":   imagegif( $TempObj,$strImgSource ); break;
		    case ".png":   imagepng( $TempObj,$strImgSource,0 ); break;
		}
		
		$this->fctQuery( "UPDATE ". $this->Config['database']['table_prefix'] ."ext_easeimage_images SET eii_width = '". $_POST['w'] ."',eii_height = '". $_POST['h'] ."' WHERE eii_id = '". $intImageID ."'" );
		
	    }
	    $this->fctURLRedirect( "extension-popup.php" );
	}
	
	public function fctShowImages( $intLinID=false,$arrParams=array() )
	{

	    // Extension-ID
	    $this->fctQuery( "SELECT ext_id FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_name = 'EASEImage'" );
	    $this->intExtID = $this->arrSQL[0]['ext_id'];
	    
	    // Vars
	    if( $intLinID )
		$_SESSION['easevars']['easeimage']['lin_id'] = $intLinID;
	    else
		unset( $_SESSION['easevars']['easeimage']['lin_id'] );
	    
	    // Groups?
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."ext_easeimage_groups LIMIT 0,1" );
	    if( count( $this->arrSQL ) == 1 )
		$_SESSION['easevars']['easeimage']['showgroups'] = 'true';
	    else
		unset( $_SESSION['easevars']['easeimage']['showgroups'] );
	    
	    // Actual ImageID
	    if( $_SESSION['easevars']['easeimage']['lin_id'] )
	    $intImageID = _Content::fctGetContent( $_SESSION['easevars']['easeimage']['lin_id'],"id" );
	    
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup'] );
	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup'];
	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];

	    // JCrop
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['jcrop'];
	    $this->arrDocument['js_include'][] = $this->arrConf['js']['jcrop'];
	    
	    // JCarousel
	    $this->arrDocument['css_include'][] =  $this->arrConf['css']['jcarousel'];
	    $this->arrDocument['js_include'][] = $this->arrConf['js']['jcarousel'];
	    
	    
	    // Replace Elements
	    $strContent = _ParseDoc::fctTagReplace( "imageid", $intImageID ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "upload", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-upload'] ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "bigger", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-bigger'] ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "group", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-group'] ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "edit", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-edit'] ) ,$strContent );
	    
	    if( $_SESSION['easevars']['easeimage']['search'] )
		$strContent = _ParseDoc::fctTagReplace( "searchvalue", $_SESSION['easevars']['easeimage']['search'] ,$strContent );
	    else
		$strContent = _ParseDoc::fctTagReplace( "searchvalue", '{Search}' ,$strContent );
	    
	    // Parent-Attributes
	    if( $intLinID )
		$arrParentAttributes = _Link::fctGetLinkAttributes( $intLinID );
	    
	    if( $arrParentAttributes['width'] || $arrParentAttributes['height'] )
	    {
		if( !$arrParentAttributes['width'] ) $arrParentAttributes['width'] = $arrParentAttributes['height'];
		if( !$arrParentAttributes['height'] ) $arrParentAttributes['height'] = $arrParentAttributes['width'];
		$this->arrDocument['head'][] = '<script type="text/javascript">var intActImgW = '. $arrParentAttributes['width'] .';var intActImgH = '. $arrParentAttributes['height'] .';</script>';
	    }
            else
                $this->arrDocument['head'][] = '<script type="text/javascript">var intActImgW;var intActImgH;</script>';
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='top.fctEaseCloseReloadExtensionPopup()'>{Close}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Language
	    $this->arrDocument['js_language']['EaseImageLangTxt'] = $this->fctSetJSLangTxT( 'Image' );
	    $strContent = $this->fctReplaceLang( $strContent );
	    
	    $this->arrDocument['body'][]  = $strContent;
	    
	}
	
    }

?>