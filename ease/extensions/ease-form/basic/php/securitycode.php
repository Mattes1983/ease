<?php

    session_start();
    unset( $_SESSION['EASEFORM_CAPTCHA'] );

    function fctCaptchaCode( $intLength )
    { 
        function make_seed(){ 
	    list($usec , $sec) = explode (' ', microtime()); 
		return (float) $sec + ((float) $usec * 100000);
	} 
	srand(make_seed());  
	$possible="ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789"; 
	$str = "";

	while( strlen($str) < $intLength )
	{
	    $str .= substr($possible,(rand()%(strlen($possible))),1); 
	}

	return $str;
    }

    $_SESSION['EASEFORM_CAPTCHA'] = fctCaptchaCode( 5 );
    header('Content-type: image/png');
    $img = ImageCreateFromPNG( "../images/securitycodebg.png" );
    $color = ImageColorAllocate( $img, rand(0,255), rand(0,255), rand(0,255) );
    $ttf = "../font/arial.ttf";
    $ttfsize = 18;
    $angle = rand(0,5); 
    $t_x = rand(15,40); 
    $t_y = 25; 
    imagettftext( $img, $ttfsize, $angle, $t_x, $t_y, $color, $ttf, $_SESSION['EASEFORM_CAPTCHA'] ); 
    imagepng( $img ); 
    imagedestroy( $img );

?> 