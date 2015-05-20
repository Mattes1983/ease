<?php 
    if( count( $_EASEF<ease:id />->arrErrorMsg ) > 0 ) 
    {
	unset( $strBR );
	foreach( $_EASEF<ease:id />->arrErrorMsg as $strMessage )
	{
	    echo $strBR . $strMessage;
	    $strBR = "<br />";
	}
	echo "<br /><br />";
    }
?>