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

    class _Files extends _GlobalFunctions
    {
	
	// Save File
	public function fctSaveDocFile( $strSource,$intDocID=false )
	{
	    if( !$intDocID ) $intDocID = $_SESSION['easevars']['document'];
	    
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document_files WHERE dof_doc_id = '". $intDocID ."' AND dof_path = '". $strSource ."'" );
	    if( count( $this->arrSQL ) == 0 )
		$this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."document_files (dof_doc_id,dof_path) VALUES ('". $intDocID ."','". $strSource ."')" );
	}
	
	// Upload Document-Files for Generate-Mode
	public function GenerateDocFiles( $intDocID )
	{
	    if( !$intDocID ) $intDocID = $_SESSION['easevars']['document'];
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."document_files WHERE dof_doc_id = '". $intDocID ."'" );
	    if( count( $this->arrSQL ) )
	    {
		foreach( $this->arrSQL as $arrFiles )
		{
		    if( file_exists( $this->Config['server']['domain'] . $this->Config['path']['basic'] ."/". $arrFiles['dof_path'] ) )
			@unlink( $this->Config['server']['domain'] . $this->Config['path']['basic'] ."/". $arrFiles['dof_path'] );
		    $arrFolder = explode( "/",$arrFiles['dof_path'] );
		    $strFolderPath = $this->Config['server']['domain'] . $this->Config['path']['basic'];
		    foreach( $arrFolder as $strFolder )
		    {
			if( strpos( $strFolder,"." ) == false )
			{
			    $strFolderPath .= "/".$strFolder;
			    if( file_exists( $strFolderPath ) == false ) mkdir( $strFolderPath );
			}
		    }
		    @copy( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/". $arrFiles['dof_path'],$this->Config['server']['domain'] . $this->Config['path']['basic'] ."/". $arrFiles['dof_path'] );
		}
	    }
	}
	
	public function fctUpdateExtDocFiles( $arrValues, $strBasePath, $noKeySwitch=false )
	{
	    if( count( $arrValues ) )
	    {
		foreach( $arrValues as $strKey => $arrSubValues )
		{
		    unset( $arrReplaced );
		    if( $strKey == "css_include_ie" )
			$arrValues[$strKey] = _Files::fctUpdateExtDocFiles ( $arrSubValues, $strBasePath,true );
		    else if( is_array( $arrSubValues ) && ( $strKey == "css" || $strKey == "js" ) )
			$arrValues[$strKey] = _Files::fctUpdateExtDocFiles ( $arrSubValues, $strBasePath );
		    else
		    {
			if( $noKeySwitch == true || $strKey == "css_include" || $strKey == "js_include" || $strKey == "php_include" || $strKey == "php_include_init" || $strKey == "exclude" )
			{
			    foreach( $arrSubValues as $strSubKey=>$strSubElem )
			    {
				$arrMatch[1] = array( $strSubElem );
				if( count( $arrMatch[1] ) )
				{
				    foreach( $arrMatch[1] as $strPath )
				    {
					if( substr( $strPath,0,1 ) != "/" && substr( $strPath,0,4 ) != "http" )
					{
					    $strNewCMSSource = $this->Config['path']['cms'] . $strBasePath . "/" .$strPath;    
					    $strNewGenerateSource = $this->Config['path']['generate'] . $strBasePath . "/" .$strPath;

					    if( $_SESSION['easevars']['generatemode'] == true ) // Generatemode
					    {	
						// Delete existing File
						if( !file_exists( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource ) )
						{
						    @unlink( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource );
						}

						// Create Folder
						_ParseDoc::fctMkdir( $strNewGenerateSource );
						
						// Folder?
						if( $strKey == "exclude" )
						{
						    if( is_dir( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewCMSSource ) )
						    {
							_Files::fctCopyFolder( $strNewCMSSource, $strNewGenerateSource );
						    }
						}
						
						@copy( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewCMSSource, $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource );
						if( $strKey == "php_include" || $strKey == "php_include_init" || $strKey == "exclude" )
						    $arrValues[$strKey][$strSubKey] = str_replace( $strPath, $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource, $arrValues[$strKey][$strSubKey] );
						else
						    $arrValues[$strKey][$strSubKey] = str_replace( $strPath, $this->Config['http']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource, $arrValues[$strKey][$strSubKey] );
					    }
					    else
					    {
						if( $strKey == "php_include" || $strKey == "php_include_init" || $strKey == "exclude" )
						    $arrValues[$strKey][$strSubKey] = str_replace( $strPath, $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewCMSSource, $arrValues[$strKey][$strSubKey] );
						else
						    $arrValues[$strKey][$strSubKey] = str_replace( $strPath, $this->Config['http']['domain'] . $this->Config['path']['basic'] . $strNewCMSSource, $arrValues[$strKey][$strSubKey] );
					    }
					}
				    }
				}
			    }
			}
			else
			{
			    preg_match_all( "/url\([\']*([a-zA-Z0-9\.\/\\\_\-]+)[\']*\)/i" , $arrSubValues, $arrMatch );
			    
			    if( count( $arrMatch[1] ) )
			    {

				foreach( $arrMatch[1] as $strPath )
				{
				    if( !in_array( $strPath,$arrReplaced ) )
				    {
					if( substr( $strPath,0,1 ) != "/" && substr( $strPath,0,4 ) != "http" )
					{
					    $strNewCMSSource = $this->Config['path']['cms'] . $strBasePath . "/" .$strPath;    
					    $strNewGenerateSource = $this->Config['path']['generate'] . $strBasePath . "/" .$strPath;

					    if( $_SESSION['easevars']['generatemode'] == true ) // Generatemode
					    {	
						if( !file_exists( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource ) )
						{
						    @unlink( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource );
						}
						_ParseDoc::fctMkdir( $strNewGenerateSource );
						@copy( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewCMSSource, $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource );
						if( $strKey == "php_include" || $strKey == "php_include_init" || $strKey == "exclude" )
						    $arrValues[$strKey] = str_replace( $strPath, $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource, $arrValues[$strKey] );
						else
						    $arrValues[$strKey] = str_replace( $strPath, $this->Config['http']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource, $arrValues[$strKey] );
					    }
					    else
					    {
						if( $strKey == "php_include" || $strKey == "php_include_init" || $strKey == "exclude" )
						    $arrValues[$strKey] = str_replace( $strPath, $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewCMSSource, $arrValues[$strKey] );
						else
						    $arrValues[$strKey] = str_replace( $strPath, $this->Config['http']['domain'] . $this->Config['path']['basic'] . $strNewCMSSource, $arrValues[$strKey] );
					    }

					    $arrReplaced[] = $strPath;
					}
				    }
				}
			    }
			}
		    }
		}
	    }
	    return $arrValues;
	}
	
	private function fctCopyFolder( $strSourceFolder,$strDestinyFolder )
	{
	    if( is_dir( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strSourceFolder ) )
	    {
		_ParseDoc::fctMkdir( $strDestinyFolder,false );
		$objHandle = @opendir( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strSourceFolder );
		while ( $strFile = @readdir ( $objHandle ) )
		{
		    if( eregi( "^\.{1,2}$", $strFile ) )
		    {
			continue;
		    }
		    if( is_dir( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strSourceFolder ."/". $strFile ) )
		    {
			_Files::fctCopyFolder( $strSourceFolder."/".$strFile,$strDestinyFolder."/".$strFile );
		    }
		    else
		    {
			@copy( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strSourceFolder."/".$strFile,$this->Config['server']['domain'] . $this->Config['path']['basic'] . $strDestinyFolder."/".$strFile );
		    }
		}
		@closedir( $objHandle );
	    }
	}
	
	public function fctUpdateExtContent( $strValues, $strBasePath )
	{
	    if( $strValues )
	    {
		
		if( is_array( $strValues ) )
		{
		    foreach( $strValues as $key=>$elem )
			$strValues[$key] = _Files::fctUpdateExtContent( $elem, $strBasePath );
		}
		else
		{
		    preg_match_all( "/[src|href]+[\=]+[\"\']+([a-zA-Z0-9\/\\\_\-]+\.[a-zA-Z0-9]{3,4})[\"\']+/i" , $strValues, $arrMatch );
		    
		    if( count( $arrMatch[1] ) )
		    {
			foreach( $arrMatch[1] as $strPath )
			{
			    if( !in_array( $strPath,$arrReplaced ) )
			    {
				$arrReplaced[] = $strPath;
				if( substr( $strPath,0,1 ) != "/" && substr( $strPath,0,4 ) != "http" )
				{
				    $strNewCMSSource = $this->Config['path']['cms'] . $strBasePath . "/" .$strPath;    
				    $strNewGenerateSource = $this->Config['path']['generate'] . $strBasePath . "/" .$strPath;
				    if( $_SESSION['easevars']['generatemode'] == true ) // Generatemode
				    {	
					if( !file_exists( $this->Config['server']['domain'] .$this->Config['path']['basic'] . $strNewGenerateSource ) )
					{
					    _ParseDoc::fctMkdir( $strNewGenerateSource );
					    @copy( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewCMSSource, $this->Config['server']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource );
					}
					$strValues = str_replace( $strPath, $this->Config['http']['domain'] . $this->Config['path']['basic'] . $strNewGenerateSource, $strValues );
				    }
				    else
				    {
					$strValues = str_replace( $strPath, $this->Config['http']['domain'] . $this->Config['path']['basic'] . $strNewCMSSource, $strValues );
				    }
				}
			    }
			}
		    }
		}
	    }
	    return $strValues;
	}
	
	public function fctDeleteExtDocFiles()
	{
	    $this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."document_files WHERE dof_doc_id = '". $_SESSION['easevars']['document'] ."'" );
	}
	
	// Get File-Path (Return an array)
	public function fctGetFileURL( $intFileID=false, $strFileName=false,$strFileSuffix=false )
	{
            unset( $this->arrSQL );
            
            $intFileID = intval( $intFileID );
	    if( $intFileID )
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."file WHERE fil_id = '". $intFileID ."'" );
            else if( $strFileName && $strFileSuffix )
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."file WHERE fil_name = '". addslashes( $strFileName ) ."' AND fil_suffx = '". addslashes( $strFileSuffix ) ."'" );

            if( count( $this->arrSQL ) == 1 )
            {
                if( $this->EaseVars['generatemode'] == false && $_SESSION['easevars']['generatemode'] == false )
                {
                    $arrURL['url'] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/files/". $this->arrSQL[0]['fil_id'] . '-' . $this->arrSQL[0]['fil_name'] . $this->arrSQL[0]['fil_suffix'];
                }
                else
                {
                    self::fctGenerateFile( $this->arrSQL[0]['fil_id'] );
                    $arrURL['url'] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] ."/files/". $this->arrSQL[0]['fil_id'] . '-' . $this->arrSQL[0]['fil_name'] . $this->arrSQL[0]['fil_suffix'];
                }
                return $arrURL;
            }   
	}
        
	// Get File-Path (return no array)
	public function fctGetFileURL2( $intFileID=false, $strFileName=false,$strFileSuffix=false )
	{
            unset( $this->arrSQL );
            
            $intFileID = intval( $intFileID );
	    if( $intFileID )
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."file WHERE fil_id = '". $intFileID ."'" );
            else if( $strFileName && $strFileSuffix )
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."file WHERE fil_name = '". addslashes( $strFileName ) ."' AND fil_suffx = '". addslashes( $strFileSuffix ) ."'" );

            if( count( $this->arrSQL ) == 1 )
            {
                if( $this->EaseVars['generatemode'] == false && $_SESSION['easevars']['generatemode'] == false )
                {
                    $strURL = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/files/". $this->arrSQL[0]['fil_id'] . '-' . $this->arrSQL[0]['fil_name'] . $this->arrSQL[0]['fil_suffix'];
                }
                else
                {
                    self::fctGenerateFile( $this->arrSQL[0]['fil_id'] );
                    $strURL = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] ."/files/". $this->arrSQL[0]['fil_id'] . '-' . $this->arrSQL[0]['fil_name'] . $this->arrSQL[0]['fil_suffix'];
                }
                return $strURL;
            }   
	}
        
        // Returns the fil_id from filename
        public function fctGetFilID( $strFileName=false,$strFileSuffix=false )
        {
            $this->fctQuery( "SELECT fil_id FROM ". $this->Config['database']['table_prefix'] ."file WHERE fil_name = '". addslashes( $strFileName ) ."' AND fil_suffix = '". addslashes( $strFileSuffix ) ."'" );
            if( count( $this->arrSQL ) == 1 )
            {
                return $this->arrSQL[0]['fil_id'];
            }
        }
	
	// Delete file
	public function fctDeleteFile( $intFileID )
	{
	    $intFileID = intval( $intFileID );
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."file WHERE fil_id = '". $intFileID ."'" );
	    if( count( $this->arrSQL[0] ) )
	    {
		@unlink( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/files/". $this->arrSQL[0]['fil_id'] . '-' . $this->arrSQL[0]['fil_name'] . $this->arrSQL[0]['fil_suffix'] );
		@unlink( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] ."/files/". $this->arrSQL[0]['fil_id'] . '-' . $this->arrSQL[0]['fil_name'] . $this->arrSQL[0]['fil_suffix'] );
		$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."file WHERE fil_id = '". $intFileID ."'" );
	    }
	}
	
	// New File / Upload
	public function fctUploadFile( $strFileName,$arrFormats=array( "pdf","doc" ) )
	{	    
	    if( count( $_FILES[$strFileName] ) )
	    {		
		$arrFile = $_FILES[$strFileName];
		$arrName = explode( ".", $arrFile['name'] );	    
		$strSuffix = strtolower( $arrName[(count($arrName)-1)] );
		if( in_array( $strSuffix,$arrFormats ) || count( $arrFormats) == 0 )	// Check Format
		{
		    if( $arrFile['tmp_name'] )
		    {
			if ( @is_uploaded_file( $arrFile['tmp_name'] ) )
			{   
                            self::fctCheckFileFolder();
			    $strNewName = $this->fctClearFileName( strtolower( $arrName[0] ) );
			    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."file (fil_name,fil_suffix,fil_title,fil_create_use_id,fil_create_date) VALUES ('". $strNewName ."','.".  $strSuffix ."','". $arrName[0] ."','". $_SESSION['easevars']['user_id'] ."',NOW())" );
			    if( @move_uploaded_file( $arrFile['tmp_name'], $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/files"."/". $this->mysql_insert_id . "-" . $strNewName .".". $strSuffix ) )
				return true;
			    else
				$this->fctQuery( "DELETE FROM ". $this->Config['database']['table_prefix'] ."file WHERE fil_id = '". $this->mysql_insert_id ."'" );
			}
		    }
		}
	    }
	    return false;
	}
        
        public function fctGenerateFile( $intFileID )
        {
	    $intFileID = intval( $intFileID );
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."file WHERE fil_id = '". $intFileID ."'" );
	    if( count( $this->arrSQL[0] ) )
	    {
                self::fctCheckFileFolder();
                copy( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/files/". $this->arrSQL[0]['fil_id'] . '-' . $this->arrSQL[0]['fil_name'] . $this->arrSQL[0]['fil_suffix'],$this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] ."/files/". $this->arrSQL[0]['fil_id'] . '-' . $this->arrSQL[0]['fil_name'] . $this->arrSQL[0]['fil_suffix'] );
            }
        }
        
        public function fctCheckFileFolder()
        {
            if( !file_exists( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/files" ) )
                mkdir( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['project'] ."/files" );

            if( !file_exists( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] ."/files/" ) )
                mkdir( $this->Config['server']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['generate'] ."/files/" );
        }
	
	private function fctClearFileName( $strName )
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
    }

?>
