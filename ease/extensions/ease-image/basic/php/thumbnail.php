<?php

    // Ease-Includes
    include_once(dirname(__FILE__)."/../../../../globals/includes.inc.php");
    
    function fctCheckSize( $strPath )
    {
	# Informationen zum Bild holen
	$info = getimagesize( $strPath );
	switch($info['mime']){ 
	    // Faktor festlegen  
	    case 'image/png': $overh = 5.05;
		break;
	    case 'image/gif': $overh = 0.69;
		break;
	    case 'image/jpeg': $overh = 1.69;
		break;
	    default: $overh = 1;
	}
	$info['channels'] = isset($info['channels']) ? $info['channels'] : 1;

	# den durch das Bild benötigten Speicher berechnen
	$memo_use_img = round( ($info[0] * $info[1] * $info['bits'] * $info['channels'])  * $overh);

	# memory_limit auslesen
	$memo_limit = ini_get('memory_limit');
	$unit = strtoupper(substr($memo_limit, -1));
	$memo_limit = (int)$memo_limit;
	
	switch($unit)
	{
	    case 'G': $memo_limit *= 1024;
	    case 'M': $memo_limit *= 1024;
	    case 'K': $memo_limit *= 1024;
	}

	# wenn das Bild zu viel Speicher beanspruchen wird:
	if(memory_get_usage()+$memo_use_img > $memo_limit)
	    return false;
	else
	    return true;
    }

    if( isset( $_GET['eii_id'] ) )
    {
        
        // Classes
        $_GF = new _GlobalFunctions();

        // Parameter
        $_GF->fctQuery( "SELECT * FROM ". $_GF->Config['database']['table_prefix'] ."ext_easeimage_images WHERE eii_id = '". $_GET['eii_id'] ."'" );

	// Image Source
	$strImgSource = $_GF->Config['server']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['extension'] ."/ease-image/basic/content-images/".$_GF->arrSQL[0]['eii_id'].".".$_GF->arrSQL[0]['eii_suffix'];

	// Width
	if( isset( $_GET['width'] ) )
	    $intWidth = $_GET['width'];
	else
	    $intWidth = 200;
	
	// Height
	if( isset( $_GET['height'] ) )
	    $intHeight = $_GET['height'];
	else
	    $intHeight=200;
	
	$intBaseWidth = $intWidth;
	$intBaseHeight = $intHeight;
	
	if ( file_exists( $strImgSource ) )
	{
	    $arrImageSize = getimagesize( $strImgSource );
	    	    
	    if ( $arrImageSize[0] > $intWidth || $imagedata[1] > $intHeight )
	    {
		
		// Min-Image-Size
		if ( $arrImageSize[0]/$arrImageSize[1] < $intWidth/$intHeight) 
		{
		    $intHeight = floor ($intWidth * $arrImageSize[1] / $arrImageSize[0]);
		}
		else 
		{
		    $intWidth = floor( $intHeight * $arrImageSize[0] / $arrImageSize[1]);
		}
		
	    }	    
	    
	    switch( strtolower( strrchr( $strImgSource , ".") ) )
	    {
		case ".jpg":
		case ".jpeg":

				if( fctCheckSize( $strImgSource ) )
				    $ImageObj = @imagecreatefromjpeg( $strImgSource );
				else
				    $_GF->fctURLRedirect( $_GF->Config['http']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['extension'] ."/ease-image/basic/content-images/".$_GF->arrSQL[0]['eii_id'].".".$_GF->arrSQL[0]['eii_suffix'] );
				break;
		case ".gif":	//$ImageObj = imagecreatefromgif( $strImgSource ); break;
                                $_GF->fctURLRedirect( $_GF->Config['http']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['extension'] ."/ease-image/basic/content-images/".$_GF->arrSQL[0]['eii_id'].".".$_GF->arrSQL[0]['eii_suffix'] );
                                break;
		case ".png":	if( fctCheckSize( $strImgSource ) )
				    $ImageObj = @imagecreatefrompng( $strImgSource );
				else
                                    $_GF->fctURLRedirect( $_GF->Config['http']['domain'] . $_GF->Config['path']['basic'] . $_GF->Config['path']['cms'] . $_GF->Config['path']['extension'] ."/ease-image/basic/content-images/".$_GF->arrSQL[0]['eii_id'].".".$_GF->arrSQL[0]['eii_suffix'] );
				break;
	    }
	    $TempObj = imagecreatetruecolor( $intWidth, $intHeight );
	    
	    if( $intBaseWidth < $intWidth )
		$x = floor(($intWidth-$intBaseWidth)/2);
	    else
		$x = 0;
	    
	    if( $intBaseHeight < $intHeight )
		$y = floor(($intHeight-$intBaseHeight)/2);
	    else
		$y = 0;
	    imagecopyresampled( $TempObj, $ImageObj, 0, 0, 0, 0, $intWidth, $intHeight, $arrImageSize[0],$arrImageSize[1] );
                
	    $TempObj2 = imagecreatetruecolor( $intBaseWidth, $intBaseWidth );
                       
	    imagecopyresized( $TempObj2, $TempObj, 0, 0, $x, $y, $intBaseWidth, $intBaseHeight, $intBaseWidth, $intBaseWidth );
            
	    switch( strtolower( strrchr( $strImgSource , ".") ) )
	    {
		case ".jpg":
		case ".jpeg":	header( "Content-Type: image/jpeg" ); imagejpeg( $TempObj2,NULL,100); break;
		case ".gif":	header( "Content-type: image/gif" ); imagegif( $TempObj ); break;
		case ".png":	header( "Content-Type: image/png" ); imagepng( $TempObj2,NULL,0); break;
	    }
	    imagedestroy( $TempObj );
	    imagedestroy( $TempObj2 );
	}
    }

?>