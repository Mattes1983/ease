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

    /* Global Functions */
    @error_reporting(0);
    ini_set("display_errors","0");
    session_start();
    
    class _GlobalFunctions
    {
	
	var $Config;
	var $EaseVars;
        
        public function __construct()
        {	    

            /* Database-Configuration, Only MySQL */
	    $this->Config['database']['host'] = "";                             // Host
	    $this->Config['database']['database'] = "";                         // Database
	    $this->Config['database']['user'] = "";				// User
	    $this->Config['database']['password'] = "";				// Password
	    $this->Config['database']['table_prefix'] = "ease_";		// Table-Prefix
	    
	    /* Server-Paths */
	    $this->Config['server']['domain'] = "";                             // Root (example: C:/webserver/htdocs)
	    
	    /* HTTP-Paths */
	    $this->Config['http']['domain'] = "";                               // Root (example: http://localhost)
	    
	    /* Basic-Path */
	    $this->Config['path']['basic'] = "";				// Basic
	        
	    /* Generate-Folder */
	    $this->Config['path']['generate'] = "";				// Root

	    /* Main-Paths */
	    $this->Config['path']['cms'] = "/ease";				// CMS
	    $this->Config['path']['extern-functions'] = "/ease-extern";		// Extern-Functions
	    $this->Config['path']['extern-error'] = "/ease-error";		// Extern-Errors
	    $this->Config['path']['extern-includes'] = "/ease-includes";	// Extern-Includes
	    $this->Config['path']['project'] = "/project";			// Project
	    $this->Config['path']['classes'] = "/classes";			// Classes
	    $this->Config['path']['extension'] = "/extensions";			// Extensions
	    $this->Config['path']['extension-basic'] = "/basic";		// Extensions-Basics
	    $this->Config['path']['extension-extends'] = "/extends";		// Extensions-Extens
	    $this->Config['path']['libs'] = "/libs";				// Libary's

	    /* Theme-Paths */
	    $this->Config['path']['theme']['root'] = "/themes";			// Theme-Root
	    $this->Config['path']['theme']['images'] = "/images";		// Theme-Images
	    $this->Config['path']['theme']['css'] = "/css";			// Theme-StyleSheet
	    $this->Config['path']['theme']['templates'] = "/templates";		// Theme-Templates

		// load Actual 
		$arrTheme = $this->fctGetUserTheme();
		$this->Config['path']['theme']['theme'] = "/".$arrTheme['the_folder'];
	        
	    // Vars
	    if( !$_SESSION['easevars']['level'] )	    $_SESSION['easevars']['level'] = 1;
	    if( !$_SESSION['easevars']['document'] )	    $_SESSION['easevars']['document'] = "0";
	    if( !$_SESSION['easevars']['user_language'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Language'" );
		if( $this->arrSQL[0]['set_value'] )
		    $_SESSION['easevars']['user_language'] = $this->arrSQL[0]['set_value'];
		else
		    $_SESSION['easevars']['user_language'] = "en";
	    }
	    $this->EaseVars['level'] = $_SESSION['easevars']['level'];
	    if( $_SESSION['easevars']['generatemode'] == true )
		$this->EaseVars['generatemode'] = true;
	    else
		$this->EaseVars['generatemode'] = false;
	    $this->EaseVars['dragmode'] = false;
	    $this->EaseVars['starttag'] = "<ease:";
	    $this->EaseVars['endtag'] = "/>";
	    $this->EaseVars['document_suffix'] = ".php";
	    $this->EaseVars['ease_version'] = "1.5";
	    
	    // Language
	    $this->Config['Languages'] = array( "English"=>"en","Deutsch"=>"de" );
	    
        }
	
	// Set Language Fields
	public function fctSetLang( $arrValues=array(),$strLanguage=false )
	{
	    if( !$strLanguage || !in_array( $strLanguage,$this->Config['Languages'] ) )
	    {
		if( count( $this->arrLanguageReplaces[$this->Config['Languages'][0]] ) )
		    $this->arrLanguageReplaces[$this->Config['Languages'][0]] = array_merge( $this->arrLanguageReplaces[$this->Config['Languages'][0]],$arrValues );
		else
		    $this->arrLanguageReplaces[$this->Config['Languages'][0]] = $arrValues;
	    }
	    else
	    {
		if( count( $this->arrLanguageReplaces[$strLanguage] ) )
		    $this->arrLanguageReplaces[$strLanguage] = array_merge( $this->arrLanguageReplaces[$strLanguage], $arrValues );
		else
		    $this->arrLanguageReplaces[$strLanguage] = $arrValues;
	    }
	}
	
	// Replace Language-Fields
	public function fctReplaceLang( $Content )
	{

	    if( $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']] && $Content )
	    {
		foreach( $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']] as $key=>$elem )
		{
		    if( is_array( $Content ) )
		    {
			if( count( $Content ) > 0 )
			    foreach( $Content as $subkey=>$subelem )
				$Content[$subkey] = str_replace( "{".$key."}",$elem,$Content[$subkey] );
		    }
		    else
			$Content = str_replace( "{".$key."}",$elem,$Content );
		}
	    }
	    return $Content;
	}
	
	// Insert JS-Array with all Language-Fields
	public function fctSetJSLangTxT( $strExtName )
	{
	    if( count( $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']] ) )
	    {
		$strJS .= 'var Ease'. $strExtName .'TxT = {';
		foreach( $this->arrLanguageReplaces[$_SESSION['easevars']['user_language']] as $key=>$elem )
		{
		    $strJS .= $strKomma.'"'. $key .'":"'. $elem .'"';
		    $strKomma = ",";
		}
		$strJS .= '};';
	    }
	    return $strJS;
	}
	
	public function fctGetUserTheme()
	{
	    if( $_SESSION['easevars']['user_id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."user WHERE use_id = '". $_SESSION['easevars']['user_id'] ."'" );
		if( $this->arrSQL[0]['use_theme'] != "0" ) // take user theme
		{
		    $intThemeID = $this->arrSQL[0]['use_theme'];
		}
		else // take default cms theme
		{
		    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Theme'" );
		    $intThemeID = $this->arrSQL[0]['set_value'];
		}
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."theme WHERE the_id = '". $intThemeID ."'" );
		return $this->arrSQL[0];
	    }
	    else
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."setting WHERE set_name = 'Theme'" );
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."theme WHERE the_id = '". $this->arrSQL[0]['set_value'] ."'" );
		return $this->arrSQL[0];
	    }
	}
        
        // Database-Query
        public function fctQuery( $strQuery )
        {
            global $objOpenDB;
	    unset( $this->arrSQL );
            $objOpenDB = mysql_connect( $this->Config['database']['host'],
                                        $this->Config['database']['user'],
                                        $this->Config['database']['password']
            ) or die( "Database-Error" );

            if (mysql_select_db( $this->Config['database']['database'],$objOpenDB) )
	    {
		$objSQL = mysql_query( $strQuery ) or die( "SQL-ERROR: ".$strQuery );
			
		if( mysql_num_rows( $objSQL ) > 0 )
		{
		    while( $arrSQL = mysql_fetch_array( $objSQL ) )
		    {
			$this->arrSQL[] = $arrSQL;
		    }
		}
		if( mysql_insert_id() )
		    $this->mysql_insert_id = mysql_insert_id();
                return $objSQL;
	    }
            else
                die( "Database-Error" );
        }

        // Database-Close
        private function fctMysqlClose()
        {
            global $objOpenDB;
            mysql_close( $objOpenDB );
        }

        // URL-Redirect
        public function fctURLRedirect ( $strURL,$boolParam=false )
        {
	    if( $boolParam == true && ( count( $_GET ) || count( $_POST ) ) )
	    {
		if( count( $_GET ) )
		{
		    foreach( $_GET as $strKey=>$strElem )
		    {
			if( strpos( $strURL,"?" ) > 0 )
			    $strURL .= "&";
			else
			    $strURL .= "?";
			$strURL .= $strKey."=".$strElem;
		    }
		}
		?><html>
		    <head>
			<title>URL-Redirect</title>
		    </head>
		    <body onload="document.EASEredirect.submit()">
			<form name="EASEredirect" action="<?=$strURL?>" method="post">
			<?php 
			    if( count( $_POST ) )
				foreach( $_POST as $key=>$elem )
				    echo '<textarea name="'. $key .'" style="display:none;">'. $elem .'</textarea>';
			?>
			If you are not redirected automatically, click on this link:<br /><a href="javascript:;" onclick="document.EASEredirect.submit()">next Page</a>
			</form>
		    </body>
		</html><?php
		exit;		
	    }
	    else
	    {
		header ( "Location: ". $strURL );
		?><html>
		    <head>
			<title>URL-Redirect</title>
		    </head>
		    <body>
			If you are not redirected automatically, click on this link:<br /><a href="<?=$strURL?>">next Page</a>
		    </body>
		</html><?php
		exit;
	    }
        }
	
	// Load File from Server
	protected function fctLoadFile( $strFilePath )
	{
	    if( file_exists( $strFilePath ) )
	    {
		$objOpen = fopen( $strFilePath, "r" );
		$strFileContent = fread ($objOpen, filesize( $strFilePath ) );
		fclose( $objOpen );
		return $strFileContent;
	    }
	}
	
	public function fctTransArrData( $arr1=array(),$arr2=array(),$arrDoNotTransport=array() )
	{
            
	    if( count( $arr1 ) )
	    {
		foreach( $arr1 as $key=>$arrValues ) 
		{
		    if( count( $arrValues ) )
		    {
			foreach( $arrValues as $key2=>$elem )
                        {
                            if( !in_array( $key2,$arrDoNotTransport ) )
                            {
                                if( is_int( $key2 ) )
                                    $arr2[$key][] = $elem;
                                else
                                    $arr2[$key][$key2] = $elem;
                            }
                        }
		    }
		}
	    }
	    return $arr2;
	    
	}
	
	public function fctFormatMysqlDate( $strMysqlDate,$strLocale )
	{
	    $arrMysqlDate = explode( " ",$strMysqlDate );
	    $arrDate = explode( "-",$arrMysqlDate[0] );
	    $arrTime = explode( ":",$arrMysqlDate[1] );
	    
	    $intTimestamp = mktime($arrTime[0],$arrTime[1],0,$arrDate[1],$arrDate[2],$arrDate[0]);
	    
	    switch( $strLocale )
	    {
		case "de": return date("d.m.Y, H:i",$intTimestamp)." Uhr"; break;
		case "en":
		default: return date("M j,Y H:i",$intTimestamp); break;
	    }
	}
	
	public function fctSetLength( $strValue,$intLength )
	{
	    if( strlen($strValue) > 0 &&  $intLength > 0 && strlen($strValue) > $intLength )
	    {
		if( $intLength > 3 )
		    $strValue = substr( $strValue, 0, $intLength-3 )."...";
		else
		    $strValue = substr( $strValue, 0, $intLength );
	    }
	    return $strValue;
	}
	
	public function fctClearValue( $strText )
	{
	    $strText = addslashes( strip_tags( $strText ) );
	    return $strText;
	}
	
	public function fctCheckBrowser()
	{
	    if( eregi( "chrome",$_SERVER['HTTP_USER_AGENT'] ) )
	    {
		preg_match( "/Chrome\/([0-9]+)/i",$_SERVER['HTTP_USER_AGENT'],$arrMatch );
		if( $arrMatch[1] >= 14 ) return true;
	    }
	    else if( eregi( "firefox",$_SERVER['HTTP_USER_AGENT'] ) )
	    {
		preg_match( "/firefox\/([0-9]+)/i",$_SERVER['HTTP_USER_AGENT'],$arrMatch );
		if( $arrMatch[1] >= 5 ) return true;
	    }
	    else if( eregi( "safari",$_SERVER['HTTP_USER_AGENT'] ) )
	    {
		preg_match( "/version\/([0-9]+)/i",$_SERVER['HTTP_USER_AGENT'],$arrMatch );
		if( $arrMatch[1] >= 5 ) return true;
	    }
	    else if( eregi( "msie",$_SERVER['HTTP_USER_AGENT'] ) )
	    {
		preg_match( "/msie ([0-9]+)/i",$_SERVER['HTTP_USER_AGENT'],$arrMatch );
		if( $arrMatch[1] >= 9 ) return true;
	    }
	}
	
	// Create Extern Folder with Basic-Functions
	public function fctCreateExternBasicFunctions()
	{
	    // Basic
	    $strPath = $this->Config['server']['domain'].$this->Config['path']['basic'].$this->Config['path']['generate'];
	    $strBasicHTAccess = $strPath ."/.htaccess";

	    // Functions
	    $strFunctionsPath = $this->Config['server']['domain'].$this->Config['path']['basic'].$this->Config['path']['generate'].$this->Config['path']['extern-functions'];
	    $strFunctions = $strFunctionsPath ."/functions.inc.php";
	    $strFunctionsHTAccess = $strFunctionsPath ."/.htaccess";
	    
	    // Error Functions
	    $strErrorPath = $this->Config['server']['domain'].$this->Config['path']['basic'].$this->Config['path']['generate'].$this->Config['path']['extern-error'];
	    $str404 = $strErrorPath ."/404.php";
	    
	    // Error Functions
	    $strIncludePath = $this->Config['server']['domain'].$this->Config['path']['basic'].$this->Config['path']['generate'].$this->Config['path']['extern-includes'];
	    $strIncludeHTAccess = $strIncludePath ."/.htaccess";
	    
	    // Folder
	    if( !file_exists( $strFunctionsPath ) ) mkdir( $strFunctionsPath );
	    if( !file_exists( $strErrorPath ) ) mkdir( $strErrorPath );
	    if( !file_exists( $strIncludePath ) ) mkdir( $strIncludePath );
	    
	    // HT-ACCESS for functions-directory
	    if( !file_exists( $strFunctionsHTAccess ) )
	    {
		$handle = fopen( $strFunctionsHTAccess,"w" );
		fseek( $handle,0 );
		$contents = fwrite( $handle, 'order deny, allow
deny from all' );
		fclose( $handle );		
	    }
	    
	    // HT-Acccess for Base-Directory
	    if( !file_exists( $strBasicHTAccess ) )
	    {
		$handle = fopen( $strBasicHTAccess,"w" );
		fseek( $handle,0 );
		$contents = fwrite( $handle, 'ErrorDocument 404 '. $this->Config['path']['basic'] . $this->Config['path']['extern-error'] .'/404.php' );
		fclose( $handle );		
	    }
	    
	    // Functions
	    if( !file_exists( $strFunctions ) )
	    {
		
		$strContent = '<?php
    /* Global Functions */
    @error_reporting(1);
    ini_set("display_errors","1");
    session_start();
    
    class _EaseGlobalFunctions
    {
	
	var $Config;
	var $EaseVars;
        
        public function __construct()
        {
            /* Database-Configuration, Only MySQL */
	    $this->Config[\'database\'][\'host\'] = "'. $this->Config['database']['host'] .'";			// Host
	    $this->Config[\'database\'][\'database\'] = "'. $this->Config['database']['database'] .'";		// Database
	    $this->Config[\'database\'][\'user\'] = "'. $this->Config['database']['user'] .'";			// User
	    $this->Config[\'database\'][\'password\'] = "'. $this->Config['database']['password'] .'";		// Password
	    $this->Config[\'database\'][\'table_prefix\'] = "'. $this->Config['database']['table_prefix'] .'";	// Table-Prefix
	    
	    /* Server-Paths */
	    $this->Config[\'server\'][\'domain\'] = "'. $this->Config['server']['domain'] .'";	// Root
	    
	    /* HTTP-Paths */
	    $this->Config[\'http\'][\'domain\'] = "'. $this->Config['http']['domain'] .'";	// Root
	
	    /* Basic-Path */
	    $this->Config[\'path\'][\'basic\'] = "'. $this->Config['path']['basic'] .'";					// Basic
	}
	
	// Database-Query
	public function fctQuery( $strQuery )
	{
	    global $objOpenDB;
	    unset( $this->arrSQL );
	    $objOpenDB = mysql_connect( $this->Config[\'database\'][\'host\'],
					$this->Config[\'database\'][\'user\'],
					$this->Config[\'database\'][\'password\']
	    ) or die( "Database-Error" );

	    if (mysql_select_db( $this->Config[\'database\'][\'database\'],$objOpenDB) )
	    {
		$objSQL = mysql_query( $strQuery ) or die( "SQL-ERROR: ".$strQuery );

		if( mysql_num_rows( $objSQL ) > 0 )
		{
		    while( $arrSQL = mysql_fetch_array( $objSQL ) )
		    {
			$this->arrSQL[] = $arrSQL;
		    }
		}
		else
		    $this->mysql_insert_id = mysql_insert_id();
		return $objSQL;
	    }
	    else
		die( "Database-Error" );
	}

	// Database-Close
	private function fctMysqlClose()
	{
	    global $objOpenDB;
	    mysql_close( $objOpenDB );
	}

	// URL-Redirect
	public function fctRedirect ( $strURL )
	{
	    header ( "Location: ". $strURL );
	    ?><html>
		<head>
		    <title>URL-Redirect</title>
		</head>
		<body>
		    If you are not redirected automatically, click on this link:<br /><a href="<?=$strURL?>">next Page</a>
		</body>
	    </html><?php
	    exit;
	}

	public function fctFormatMysqlDate( $strMysqlDate,$strLocale )
	{
	    $arrMysqlDate = explode( " ",$strMysqlDate );
	    $arrDate = explode( "-",$arrMysqlDate[0] );
	    $arrTime = explode( ":",$arrMysqlDate[1] );
	    
	    $intTimestamp = mktime($arrTime[0],$arrTime[1],0,$arrDate[1],$arrDate[2],$arrDate[0]);
	    
	    switch( $strLocale )
	    {
		case "de": return date("d.m.Y, H:i",$intTimestamp)." Uhr"; break;
		case "en":
		default: return date("M j,Y H:i",$intTimestamp); break;
	    }
	}
	
	public function fctSetLength( $strValue,$intLength )
	{
	    if( strlen($strValue) > 0 &&  $intLength > 0 && strlen($strValue) > $intLength )
	    {
		if( $intLength > 3 )
		    $strValue = substr( $strValue, 0, $intLength-3 )."...";
		else
		    $strValue = substr( $strValue, 0, $intLength );
	    }
	    return $strValue;
	}
	
	public function fctCheckGeneratePage( $intDocID=false )
	{
	    if( !$intDocID )
	    {
		$strURL = substr( $_SERVER[\'REQUEST_URI\'],strlen( $this->Config[\'path\'][\'basic\'] ) );
		$this->fctQuery(\'SELECT * FROM \'. $this->Config[\'database\'][\'table_prefix\'] .\'generated_path WHERE geu_path = "\'. $strURL .\'" LIMIT 0,1\');
		if( count( $this->arrSQL ) == 1 )
		{
		    $intDocID = $this->arrSQL[0][\'geu_doc_id\'];
		}
	    }
	    else
		$boolDeleteOld = true;
	    
	    $this->fctQuery(\'SELECT * FROM \'. $this->Config[\'database\'][\'table_prefix\'] .\'generated_document WHERE ged_doc_id = "\'. $intDocID .\'"\');
	    if( count( $this->arrSQL ) == 1 )	    
	    {
		if ( strtolower( $_SERVER[\'SCRIPT_FILENAME\'] ) != strtolower( $this->Config[\'server\'][\'domain\'].$this->Config[\'path\'][\'basic\'].\'/\'.$this->arrSQL[0][\'ged_doc_name\'].$this->arrSQL[0][\'ged_doc_suffix\']) )
		{
		    if( $boolDeleteOld == true )
			unlink( $_SERVER[\'SCRIPT_FILENAME\'] );
		    $this->fctRedirect( $this->Config[\'http\'][\'domain\'].$this->Config[\'path\'][\'basic\'].$this->arrSQL[0][\'ged_path\'] );
		}
	    }
	    else
	    {
		if( $boolDeleteOld == true )
		    unlink( $_SERVER[\'SCRIPT_FILENAME\'] );
		$this->fctRedirect( $this->Config[\'http\'][\'domain\'].$this->Config[\'path\'][\'basic\'] );
	    }
	}
	
    }
?>';
		
		$handle = fopen( $strFunctions,"w" );
		fseek( $handle,0 );
		$contents = fwrite( $handle, $strContent );
		fclose( $handle );
	    }
	    
	    // Error-Functions
	    if( !file_exists( $str404 ) )
	    {
		$handle = fopen( $str404,"w" );
		fseek( $handle,0 );
		$contents = fwrite( $handle, '<?php
include("'. $strFunctions .'");
$_EaseGlobalFunctions = new _EaseGlobalFunctions;
$_EaseGlobalFunctions->fctCheckGeneratePage();
?>' );
		fclose( $handle );		
	    }
	    
	    // HT-ACCESS for functions-directory
	    if( !file_exists( $strIncludeHTAccess ) )
	    {
		$handle = fopen( $strIncludeHTAccess,"w" );
		fseek( $handle,0 );
		$contents = fwrite( $handle, 'order deny, allow
deny from all' );
		fclose( $handle );		
	    }
	    
	}
    
    }

?>
