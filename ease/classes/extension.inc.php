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

    class _Extensions extends _GlobalFunctions {
	
	function __construct()
	{
	    parent::__construct();
	}
	
	public function fctGetExtensionID( $strClassName )
	{
	    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_name = '". $strClassName ."'" );
	    if( $this->arrSQL[0]['ext_id'] > 0 )
		return $this->arrSQL[0]['ext_id'];
	}
	
	public function fctExtensionClass( $strClassName )
	{
	    if( class_exists( $strClassName ."Extends" ) )
		$strClassName .= "Extends";
	    else if( class_exists( $strClassName ."Basic" ) )
		$strClassName .= "Basic";
	    return $strClassName;
	}
	
	public function fctRenderCode( $arrParams=array() )
	{
	    
	}
	
        public function fctGetToolButtons( $intLvL=1 )
        {
            $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_id = '". $this->ExtensionVars['ext_id'] ."'" );            
            if( count( $this->arrSQL ) == 1 )
            {
               
                $xmlBasicPath = $this->Config['server']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'].$this->Config['path']['extension'].$this->arrSQL[0]['ext_include'].$this->Config['path']['extension-basic']."/ease-button.xml";
                $xmlExtensPath = $this->Config['server']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'].$this->Config['path']['extension'].$this->arrSQL[0]['ext_include'].$this->Config['path']['extension-extends']."/ease-button.xml";

                if (file_exists( $xmlExtensPath )) 
                    $xmlContent = simplexml_load_file( $xmlExtensPath );
                else if (file_exists( $xmlBasicPath )) 
                    $xmlContent = simplexml_load_file( $xmlBasicPath );

                $i=0;
                if( count( $xmlContent->{'level'.$intLvL}->button ) )
                {
                    foreach( $xmlContent->{'level'.$intLvL}->button as $arrButton )
                    {                        
			$arrButtons[$i]['groupname'] = (string) $arrButton->groupname;
                        $arrButtons[$i]['text'] = (string) $arrButton->text;
                        $arrButtons[$i]['description'] = (string) $arrButton->description;
                        $arrButtons[$i]['paramname'] = (string) $arrButton->paramname;
                        $arrButtons[$i]['paramvalue'] = (string) $arrButton->paramvalue;
                        $arrButtons[$i]['type'] = (string) $arrButton->type;
			$arrButtons[$i]['popup_width'] = (string) $arrButton->popup_width;
			$arrButtons[$i]['popup_height'] = (string) $arrButton->popup_height;
                        $i++;
                    }
                }
            }
            return $arrButtons;
        }
	
	public function fctDeleteItem( $intLinkID, $arrParams=array() )
	{
	    
	}
	
	// Optional
	public function fctExtensionPopup( $intLinID,$arrParams=array() )
	{
	    
	}
	
	public function fctSetLang( $arrValues=array(),$strLanguage=false )
	{

	    $_GF = new _GlobalFunctions();
	    
	    if( $this->ExtensionVars['ext_id'] )
	    {
		$this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_id = '". $this->ExtensionVars['ext_id'] ."'" );            
		if( count( $this->arrSQL ) == 1 )
		{

		    $xmlBasicPath = $this->Config['server']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'].$this->Config['path']['extension'].$this->arrSQL[0]['ext_include'].$this->Config['path']['extension-basic']."/ease-language.xml";
		    $xmlExtensPath = $this->Config['server']['domain'].$this->Config['path']['basic'].$this->Config['path']['cms'].$this->Config['path']['extension'].$this->arrSQL[0]['ext_include'].$this->Config['path']['extension-extends']."/ease-language.xml";

		    if (file_exists( $xmlBasicPath )) 
			$xmlContent = simplexml_load_file( $xmlBasicPath );
		    
		    if (file_exists( $xmlExtensPath )) 
			$xmlContent2 = simplexml_load_file( $xmlExtensPath );

		    if( count( $xmlContent ) || count( $xmlContent2 ) )
		    {
			if( count( $xmlContent ) )
			{
			    foreach( $xmlContent->language as $arrLanguage )
			    {     
				if( count( $arrLanguage->label ) )
				{
				    unset( $arrValues );
				    $strLanguage = (string) $arrLanguage->attributes()->index;
				    foreach( $arrLanguage->label as $arrLabel )
				    {
					$arrValues[(string)$arrLabel->attributes()->index] = (string)$arrLabel;
				    }
				    if( count( $arrValues ) )
				    {
					$_GF->arrLanguageReplaces = $this->arrLanguageReplaces;
					$_GF->fctSetLang( $arrValues,$strLanguage );
					$this->arrLanguageReplaces = $_GF->arrLanguageReplaces;
				    }
				}			
			    }
			}
			
			if( count( $xmlContent2) )
			{
			    foreach( $xmlContent2->language as $arrLanguage )
			    {     
				if( count( $arrLanguage->label ) )
				{
				    unset( $arrValues );
				    $strLanguage = (string) $arrLanguage->attributes()->index;
				    foreach( $arrLanguage->label as $arrLabel )
				    {
					$arrValues[(string)$arrLabel->attributes()->index] = (string)$arrLabel;
				    }
				    if( count( $arrValues ) )
				    {
					$_GF->arrLanguageReplaces = $this->arrLanguageReplaces;
					$_GF->fctSetLang( $arrValues,$strLanguage );
					$this->arrLanguageReplaces = $_GF->arrLanguageReplaces;
				    }
				}			
			    }
			}
		    }
		    else
		    {
			$_GF->arrLanguageReplaces = $this->arrLanguageReplaces;
			$_GF->fctSetLang( $arrValues,$strLanguage );
			$this->arrLanguageReplaces = $_GF->arrLanguageReplaces;
		    }
		}
		else
		{
		    $_GF->arrLanguageReplaces = $this->arrLanguageReplaces;
		    $_GF->fctSetLang( $arrValues,$strLanguage );
		    $this->arrLanguageReplaces = $_GF->arrLanguageReplaces;
		}
	    }
	    else
	    {
		$_GF->arrLanguageReplaces = $this->arrLanguageReplaces;
		$_GF->fctSetLang( $arrValues,$strLanguage );
		$this->arrLanguageReplaces = $_GF->arrLanguageReplaces;
	    }
	}
	
    }

?>