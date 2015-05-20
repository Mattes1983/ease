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

    class EASEFormBasic extends _Extensions
    {
        
        public function __construct()
	{
	    parent::__construct();
            
            // Templates
            $this->arrConf['tmpl']['container'] = "basic/templates/container.tmpl";
            $this->arrConf['tmpl']['container-edit'] = "basic/templates/container-edit.tmpl";
            $this->arrConf['tmpl']['input'] = "basic/templates/input.tmpl";
            $this->arrConf['tmpl']['textarea'] = "basic/templates/textarea.tmpl";
            $this->arrConf['tmpl']['checkbox'] = "basic/templates/checkbox.tmpl";
            $this->arrConf['tmpl']['checkbox-value'] = "basic/templates/checkbox-values.tmpl";
            $this->arrConf['tmpl']['radio'] = "basic/templates/radio.tmpl";
            $this->arrConf['tmpl']['radio-values'] = "basic/templates/radio-values.tmpl";
            $this->arrConf['tmpl']['select'] = "basic/templates/select.tmpl";
            $this->arrConf['tmpl']['select-options'] = "basic/templates/select-options.tmpl";
            $this->arrConf['tmpl']['captcha'] = "basic/templates/captcha.tmpl";
            $this->arrConf['tmpl']['extension-popup-message'] = "basic/templates/extension-popup-message.tmpl";
            $this->arrConf['tmpl']['extension-popup-input'] = "basic/templates/extension-popup-input.tmpl";
            $this->arrConf['tmpl']['extension-popup-textarea'] = "basic/templates/extension-popup-textarea.tmpl";
            $this->arrConf['tmpl']['extension-popup-checkbox'] = "basic/templates/extension-popup-checkbox.tmpl";
            $this->arrConf['tmpl']['extension-popup-checkbox-values'] = "basic/templates/extension-popup-checkbox-values.tmpl";
            $this->arrConf['tmpl']['extension-popup-radio'] = "basic/templates/extension-popup-radio.tmpl";
            $this->arrConf['tmpl']['extension-popup-radio-values'] = "basic/templates/extension-popup-radio-values.tmpl";
            $this->arrConf['tmpl']['extension-popup-select'] = "basic/templates/extension-popup-select.tmpl";
            $this->arrConf['tmpl']['extension-popup-select-values'] = "basic/templates/extension-popup-select-values.tmpl";
            
            // CSS
            $this->arrConf['css']['container'] = "basic/css/container.css";
            $this->arrConf['css']['container-edit'] = "basic/css/container-edit.css";
            $this->arrConf['css']['input'] = "basic/css/input.css";
            $this->arrConf['css']['textarea'] = "basic/css/textarea.css";
            $this->arrConf['css']['checkbox'] = "basic/css/checkbox.css";
            $this->arrConf['css']['radio'] = "basic/css/radio.css";
            $this->arrConf['css']['select'] = "basic/css/select.css";
            $this->arrConf['css']['captcha'] = "basic/css/captcha.css";
            $this->arrConf['css']['extension-popup'] = "basic/css/extension-popup.css";
            
            // JS
            $this->arrConf['js']['easeformonload'] = "basic/js/easeform-onload.js";
            $this->arrConf['js']['easeform'] = "basic/js/form.js";
            $this->arrConf['js']['extension-popup-input'] = "basic/js/extension-popup-input.js";
            $this->arrConf['js']['extension-popup-textarea'] = "basic/js/extension-popup-textarea.js";
            $this->arrConf['js']['extension-popup-checkbox'] = "basic/js/extension-popup-checkbox.js";
            $this->arrConf['js']['extension-popup-radio'] = "basic/js/extension-popup-radio.js";
            $this->arrConf['js']['extension-popup-select'] = "basic/js/extension-popup-select.js";
            
            // PHP
            $this->arrConf['php']['container-message'] = "basic/php/container-message.inc.php";
            $this->arrConf['php']['phpmailer3'] = "basic/php/phpmailer-5-2-0/class.phpmailer.php";
            $this->arrConf['php']['phpmailer1'] = "basic/php/phpmailer-5-2-0/extras/htmlfilter.php";
            $this->arrConf['php']['phpmailer2'] = "basic/php/phpmailer-5-2-0/language/phpmailer.lang-de.php";
            $this->arrConf['php']['phpmailer4'] = "basic/php/phpmailer-5-2-0/class.pop3.php";
            $this->arrConf['php']['phpmailer5'] = "basic/php/phpmailer-5-2-0/class.smtp.php";
            $this->arrConf['php']['form'] = "basic/php/form.inc.php";
            $this->arrConf['php']['container'] = "basic/php/container.inc.php";
            $this->arrConf['php']['container2'] = "basic/php/container2.inc.php";
            $this->arrConf['php']['input'] = "basic/php/input.inc.php";
            $this->arrConf['php']['textarea'] = "basic/php/textarea.inc.php";
            $this->arrConf['php']['checkbox'] = "basic/php/checkbox.inc.php";
            $this->arrConf['php']['radio'] = "basic/php/radio.inc.php";
            $this->arrConf['php']['select'] = "basic/php/select.inc.php";
            $this->arrConf['php']['securitycode'] = "basic/php/securitycode.php";
            
            // Font
            $this->arrConf['ttf']['arial'] = "basic/font/arial.ttf";
            
            // Image
            $this->arrConf['img']['securitycodebg'] = "basic/images/securitycodebg.png";
        }

	public function fctRenderCode( $arrParams=array() )
	{
	    // Classes
	    $_C = new _Content();	    
	    
	    switch( $arrParams['item'] )
	    {
		// Form-Container
		case 'container':
		    if( $this->EaseVars['dragmode'] == false )
		    {
			if( $this->EaseVars['generatemode'] == false ) 
				$this->arrDocument['css']['easeformcontaineredit'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['container-edit'] );
			$this->arrDocument['css']['easeformcontainer'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['container'] );
			
			if( $this->EaseVars['generatemode'] == false ) 
			    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['container-edit'] );
			else
			    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['container'] );
			
			$strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['php']['container-message'] ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
			
			if( $this->EaseVars['generatemode'] == false )	$this->arrDocument['js_include']['easeformonload'] = $this->arrConf['js']['easeformonload'];
			
			// Parameter			
			$strContent = _ParseDoc::fctTagReplace( "formname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'formname' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "emailrecipient", $_C->fctGetContent( $arrParams['Link']['lin_id'],'emailrecipient' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "emailfrom", $_C->fctGetContent( $arrParams['Link']['lin_id'],'emailfrom' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "messagesuccess", $_C->fctGetContent( $arrParams['Link']['lin_id'],'messagesuccess' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "messageerror", $_C->fctGetContent( $arrParams['Link']['lin_id'],'messageerror' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "messageerrorrequired", $_C->fctGetContent( $arrParams['Link']['lin_id'],'messageerrorrequired' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "messageerrorcaptcha", $_C->fctGetContent( $arrParams['Link']['lin_id'],'messageerrorcaptcha' ) ,$strContent );

			// Init PHP Mailer
			$this->arrDocument['php_include_init']['phpmailer3'] = $this->arrConf['php']['phpmailer3'];
			$this->arrDocument['exclude']['phpmailer1'] = $this->arrConf['php']['phpmailer1'];
			$this->arrDocument['exclude']['phpmailer2'] = $this->arrConf['php']['phpmailer2'];
			$this->arrDocument['exclude']['phpmailer4'] = $this->arrConf['php']['phpmailer4'];
			$this->arrDocument['exclude']['phpmailer5'] = $this->arrConf['php']['phpmailer5'];
			
			$this->arrDocument['js_include']['easeform'] = $this->arrConf['js']['easeform'];
			$this->arrDocument['php_include_init']['easeform'] = $this->arrConf['php']['form'];
			
			$this->arrDocument['php_init']['easeformcontainer'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['php']['container'] );
			$this->arrDocument['php_init']['easeformcontainer'] = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$this->arrDocument['php_init']['easeformcontainer'] );
			$this->arrDocument['php_init']['easeformcontainer'] = _ParseDoc::fctTagReplace( "formname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'formname' ) ,$this->arrDocument['php_init']['easeformcontainer'] );
			$this->arrDocument['php_init']['easeformcontainer'] = _ParseDoc::fctTagReplace( "emailrecipient", $_C->fctGetContent( $arrParams['Link']['lin_id'],'emailrecipient' ) ,$this->arrDocument['php_init']['easeformcontainer'] );
			$this->arrDocument['php_init']['easeformcontainer'] = _ParseDoc::fctTagReplace( "emailfrom", $_C->fctGetContent( $arrParams['Link']['lin_id'],'emailfrom' ) ,$this->arrDocument['php_init']['easeformcontainer'] );
			$this->arrDocument['php_init']['easeformcontainer'] = _ParseDoc::fctTagReplace( "messagesuccess", $_C->fctGetContent( $arrParams['Link']['lin_id'],'messagesuccess' ) ,$this->arrDocument['php_init']['easeformcontainer'] );
			$this->arrDocument['php_init']['easeformcontainer'] = _ParseDoc::fctTagReplace( "messageerror", $_C->fctGetContent( $arrParams['Link']['lin_id'],'messageerror' ) ,$this->arrDocument['php_init']['easeformcontainer'] );
			$this->arrDocument['php_init']['easeformcontainer'] = _ParseDoc::fctTagReplace( "messageerrorrequired", $_C->fctGetContent( $arrParams['Link']['lin_id'],'messageerrorrequired' ) ,$this->arrDocument['php_init']['easeformcontainer'] );
			$this->arrDocument['php_init']['easeformcontainer'] = _ParseDoc::fctTagReplace( "messageerrorcaptcha", $_C->fctGetContent( $arrParams['Link']['lin_id'],'messageerrorcaptcha' ) ,$this->arrDocument['php_init']['easeformcontainer'] );
			
			$this->arrDocument['php']['easeformcontainer'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['php']['container2'] );
			$this->arrDocument['php']['easeformcontainer'] = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$this->arrDocument['php']['easeformcontainer'] );
			
			// JavaScript-Function for Edit
			$this->ExtensionVars['JSEditStart'] = "fctEASEFormEditStart(". $arrParams['Link']['lin_id'] .")";
			$this->ExtensionVars['JSEditEnd'] = "fctEASEFormEditEnd(". $arrParams['Link']['lin_id'] .")";
			
			// Elements
			if( $this->EaseVars['generatemode'] == false )
			{
			    $_PD = new _ParseDoc();
			    $_PD->boolNoEditmode = true;
			    $_PD->boolReturnContentArray = true;
                            $_PD->EaseVars = $this->EaseVars;
			    $arrContent = $_PD->fctRenderLinks( $arrParams['Link']['lin_doc_id'],$arrParams['Link']['lin_id'],array( "fields" => array( "name"=>"fields")));

			    // Replace Elements
			    if( count( $arrContent['fields'] ) )
			    {
				foreach( $arrContent['fields'] as $strField )
				    $strFields .= $strField."\n";
			    }
			}
                        $strContent = _ParseDoc::fctTagReplace( "fields2", $strFields ,$strContent );
		    }
		    else
		    {
			// Preview
			$this->arrDocument['css']['easeformcontainer'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['container'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['container'] );
			$strContent = _ParseDoc::fctTagReplace( "captcha", self::fctRenderCode( array( "item"=>"captcha" ) ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fields", 
									self::fctRenderCode( array( "item"=>"select" ) ).
									self::fctRenderCode( array( "item"=>"input" ) ).
									self::fctRenderCode( array( "item"=>"textarea" ) )
					,$strContent );
		    }
		    
		    // Language
		    $this->arrDocument['js_language']['EASEFormLangTxt'] = $this->fctSetJSLangTxT( 'EASEform' );
		    $strContent = $this->fctReplaceLang( $strContent );
		    break;
		    
		// Inputfield
		case 'input':	    
		    
		    if( $this->EaseVars['dragmode'] == false )
		    {
			$this->arrDocument['css']['easeforminput'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['input'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['input'] );
			$this->arrDocument['js_include']['easeform'] = $this->arrConf['js']['easeform'];
			$this->arrDocument['php_include_init']['easeform'] = $this->arrConf['php']['form'];
			
			// PHP-Settings
			$strSettings = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['php']['input'] );
			$strSettings = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? '1':'0' ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "fieldvalue", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue' ) ,$strSettings );
			$this->arrDocument['php_init'][] = $strSettings;

			$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldvalue", "<?php if( \$_POST['field". $arrParams['Link']['lin_id'] ."'] ) echo \$_POST['field". $arrParams['Link']['lin_id'] ."']; else echo \"".$_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue' )."\"; ?>" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? ' *':'' ,$strContent );
		    }
		    else
		    {
			$this->arrDocument['css']['easeforminput'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['input'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['input'] );
			$strContent = _ParseDoc::fctTagReplace( "id", "" ,$strContent );
                        $strContent = _ParseDoc::fctTagReplace( "parentid", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", "{FieldName}" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldvalue", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", " *" ,$strContent );
		    }
		    
		    // Language
		    $this->arrDocument['js_language']['EASEFormInputLangTxt'] = $this->fctSetJSLangTxT( 'EASEforminput' );
		    $strContent = $this->fctReplaceLang( $strContent );
		    
		    break;
		
		// Textarea
		case 'textarea':
		    if( $this->EaseVars['dragmode'] == false )
		    {
			$this->arrDocument['css']['easeformtextarea'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['textarea'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['textarea'] );
			$this->arrDocument['js_include']['easeform'] = $this->arrConf['js']['easeform'];
			$this->arrDocument['php_include_init']['easeform'] = $this->arrConf['php']['form'];
			
			// PHP-Settings
			$strSettings = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['php']['textarea'] );
			$strSettings = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? '1':'0' ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "fieldvalue", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue' ) ,$strSettings );
			$this->arrDocument['php_init'][] = $strSettings;

			$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldvalue", "<?php if( \$_POST['field". $arrParams['Link']['lin_id'] ."'] ) echo \$_POST['field". $arrParams['Link']['lin_id'] ."']; else echo \"".$_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue' )."\"; ?>" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? ' *':'' ,$strContent );
		    }
		    else
		    {
			$this->arrDocument['css']['easeformtextarea'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['textarea'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['textarea'] );
			$strContent = _ParseDoc::fctTagReplace( "id", "" ,$strContent );
                        $strContent = _ParseDoc::fctTagReplace( "parentid", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", "{FieldName}" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldvalue", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", " *" ,$strContent );
		    }
		    
		    // Language
		    $this->arrDocument['js_language']['EASEFormTextareaLangTxt'] = $this->fctSetJSLangTxT( 'EASEformtextarea' );
		    $strContent = $this->fctReplaceLang( $strContent );
		    break;
		    
		// Checkbox
		case 'checkbox':	    
		    
		    if( $this->EaseVars['dragmode'] == false )
		    {
			$this->arrDocument['css']['easeformcheckbox'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['checkbox'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['checkbox'] );
			$this->arrDocument['js_include']['easeform'] = $this->arrConf['js']['easeform'];
			$this->arrDocument['php_include_init']['easeform'] = $this->arrConf['php']['form'];
			
			// PHP-Settings
			$strSettings = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['php']['checkbox'] );
			$strSettings = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? '1':'0' ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "valuescount", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue_count' ) ,$strSettings );
			$this->arrDocument['php_init'][] = $strSettings;
		
			// Render
			
			    // Checkboxes
			    $intCountCheckboxes = $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue_count' );
			    if( !$intCountCheckboxes ) $intCountCheckboxes = 1;
			    $strReplaceCheckboxes = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['checkbox-value'] );    
			    for( $i=1 ; $i<=$intCountCheckboxes ; $i++ )
			    {
				$strCheckbox = $strReplaceCheckboxes;
				$strCheckbox = _ParseDoc::fctTagReplace( "count", $i ,$strCheckbox );
				$strCheckbox = _ParseDoc::fctTagReplace( "checked", "<?php if( \$_POST['field". $arrParams['Link']['lin_id'] ."-". $i ."'] ) echo \" checked\"; ?>" ,$strCheckbox );
				$strCheckbox = _ParseDoc::fctTagReplace( "fieldvalue", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue'.$i ) ,$strCheckbox );
				$strInputs .= $strCheckbox;
			    }
			
			$strContent = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? ' *':'' ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "input", $strInputs ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
		    }
		    else
		    {		
			$this->arrDocument['css']['easeformcheckbox'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['checkbox'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['checkbox'] );
			$strContent = _ParseDoc::fctTagReplace( "input", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['checkbox-value'] ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "id", "" ,$strContent );
                        $strContent = _ParseDoc::fctTagReplace( "parentid", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "count", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", "{FieldName}" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldvalue", "{FieldName}" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", " *" ,$strContent );
		    }
		    
		    // Language
		    $this->arrDocument['js_language']['EASEFormCheckboxLangTxt'] = $this->fctSetJSLangTxT( 'EASEformcheckbox' );
		    $strContent = $this->fctReplaceLang( $strContent );
		    
		    break;
		    
		// Radio
		case 'radio':	    
		    
		    if( $this->EaseVars['dragmode'] == false )
		    {
			$this->arrDocument['css']['easeformradio'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['radio'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['radio'] );
			$this->arrDocument['js_include']['easeform'] = $this->arrConf['js']['easeform'];
			$this->arrDocument['php_include_init']['easeform'] = $this->arrConf['php']['form'];
			
			// PHP-Settings
			$strSettings = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['php']['radio'] );
			$strSettings = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? '1':'0' ,$strSettings );
			$this->arrDocument['php_init'][] = $strSettings;
			
			// Render
			
			    // Radio
			    $intCountRadio = $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue_count' );
			    if( !$intCountRadio ) $intCountRadio = 1;
			    $strReplaceRadio = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['radio-values'] );
			    for( $i=1 ; $i<=$intCountRadio ; $i++ )
			    {
				$strRadio = $strReplaceRadio;
				$strRadio = _ParseDoc::fctTagReplace( "count", $i ,$strRadio );
				$strFieldValue = $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue'.$i );
				$strRadio = _ParseDoc::fctTagReplace( "fieldvalue", $strFieldValue ,$strRadio );
				$strRadio = _ParseDoc::fctTagReplace( "checked", "<?php if( \$_POST['field". $arrParams['Link']['lin_id']."'] == \"". $strFieldValue ."\" ) echo \" checked\"; ?>" ,$strRadio );
				$strInputs .= $strRadio;
			    }

                        $strContent = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "input", $strInputs ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? ' *':'' ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
		    }
		    else
		    {
			$this->arrDocument['css']['easeformcheckbox'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['radio'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['radio'] );
			$strContent = _ParseDoc::fctTagReplace( "input", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['radio-values'] ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "id", "" ,$strContent );
                        $strContent = _ParseDoc::fctTagReplace( "parentid", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "count", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", "{FieldName}" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldvalue", "{Value}" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", " *" ,$strContent );
		    }
		    
		    // Language
		    $this->arrDocument['js_language']['EASEFormRadioLangTxt'] = $this->fctSetJSLangTxT( 'EASEformradio' );
		    $strContent = $this->fctReplaceLang( $strContent );
		    
		    break;
		    
		// Select
		case 'select':	    
		    
		    if( $this->EaseVars['dragmode'] == false )
		    {
			$this->arrDocument['css']['easeformselect'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['select'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['select'] );
			$this->arrDocument['js_include']['easeform'] = $this->arrConf['js']['easeform'];
			$this->arrDocument['php_include_init']['easeform'] = $this->arrConf['php']['form'];
			
			// PHP-Settings
			$strSettings = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['php']['select'] );
			$strSettings = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strSettings );
			$strSettings = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? '1':'0' ,$strSettings );
			$this->arrDocument['php_init'][] = $strSettings;
			
			// Render
			
			    // Options
			    $intCountOption = $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue_count' );
			    if( !$intCountOption ) $intCountOption = 1;
			    $strReplaceOption = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['select-options'] );
			    
			    // Default Value
			    $strOption = $strReplaceOption;
			    $strOption = _ParseDoc::fctTagReplace( "value", "" ,$strOption );
			    $strOption = _ParseDoc::fctTagReplace( "title", "{Select3}" ,$strOption );
			    $strOption = _ParseDoc::fctTagReplace( "selected", "" ,$strOption );
			    $strOptions .= $strOption;
			    
			    for( $i=1 ; $i<=$intCountOption ; $i++ )
			    {
				$strOption = $strReplaceOption;
				$strFieldValue = $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldvalue'.$i );
				$strOption = _ParseDoc::fctTagReplace( "title", $strFieldValue ,$strOption );
				$strOption = _ParseDoc::fctTagReplace( "value", $strFieldValue ,$strOption );
				$strOption = _ParseDoc::fctTagReplace( "selected", "<?php if( \$_POST['field". $arrParams['Link']['lin_id']."'] == \"". $strFieldValue ."\" ) echo \" selected\"; ?>" ,$strOption );
				$strOptions .= $strOption;
			    }

			$strContent = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $arrParams['Link']['lin_id'],'fieldname' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "options", $strOptions ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $arrParams['Link']['lin_id'],'required' ))? ' *':'' ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
		    }
		    else
		    {
			$this->arrDocument['css']['easeformcheckbox'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['select'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['select'] );
			$strContent = _ParseDoc::fctTagReplace( "options", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['select-options'] ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "id", "" ,$strContent );
                        $strContent = _ParseDoc::fctTagReplace( "parentid", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "count", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "fieldname", "{FieldName}" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "title", "{Select3}" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "value", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "required", " *" ,$strContent );
		    }
		    
		    // Language
		    $this->arrDocument['js_language']['EASEFormSelectLangTxt'] = $this->fctSetJSLangTxT( 'EASEformselect' );
		    $strContent = $this->fctReplaceLang( $strContent );
		    
		    break;
		    
		// Captcha
		case 'captcha':	    
		    
		    if( $this->EaseVars['dragmode'] == false )
		    {
			$this->arrDocument['css']['easeformcaptcha'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['captcha'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['captcha'] );
			
			// Captcha-Pic
			$this->arrDocument['exclude']['phpcaptcha1'] = $this->arrConf['php']['securitycode'];
			$this->arrDocument['exclude']['phpcaptcha2'] = $this->arrConf['ttf']['arial'];
			$this->arrDocument['exclude']['phpcaptcha3'] = $this->arrConf['img']['securitycodebg'];

			$strContent = _ParseDoc::fctTagReplace( "id", $arrParams['Link']['lin_id'] ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "parentid", _Link::fctFindParentExtension( $arrParams['Link']['lin_id'],false,'EASEForm' ) ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "captchaurl", $this->arrConf['php']['securitycode'] ,$strContent );
		    }
		    else
		    {
			$this->arrDocument['css']['easeformcaptcha'] = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['css']['captcha'] );
			$strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['captcha'] );
			$strContent = _ParseDoc::fctTagReplace( "id", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "parentid", "" ,$strContent );
			$strContent = _ParseDoc::fctTagReplace( "captchaurl", $this->arrConf['php']['securitycode'] ,$strContent );
		    }
		    
		    // Language
		    $this->arrDocument['js_language']['EASEFormCaptchaLangTxt'] = $this->fctSetJSLangTxT( 'EASEformcaptcha' );
		    $strContent = $this->fctReplaceLang( $strContent );
		    
		    break;
	    }
	    return $strContent;
	}
	
	// Optional
	public function fctExtensionPopup( $intLinID,$arrParams=array() )
	{
	    switch( $arrParams['item'] )
	    {
		case "input": $this->fctInputEdit( $intLinID ); break;
		case "textarea": $this->fctTextareaEdit( $intLinID ); break;
		case "checkbox": $this->fctCheckboxEdit( $intLinID ); break;
		case "radio": $this->fctRadioEdit( $intLinID ); break;
		case "select": $this->fctSelectEdit( $intLinID ); break;
	    }
	}
	
	public function fctInputEdit( $intLinID )
	{
	    
	    // Classes
	    $_C = new _Content();
	    
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-input'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup-input'];
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    
	    // Values
    	    $strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $intLinID,'fieldname' ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $intLinID,'required' ) == '1' )? "checked":'' ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "fieldvalue", $_C->fctGetContent( $intLinID,'fieldvalue' ) ,$strContent );
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='fctSave(".$intLinID.")'>{Save}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Language
	    $this->arrDocument['js_language']['EaseFormLangTxt'] = $this->fctSetJSLangTxT( 'Form' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}
	
	public function fctTextareaEdit( $intLinID )
	{

	    // Classes
	    $_C = new _Content();
	    
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-textarea'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup-textarea'];
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    
	    // Replaces
    	    $strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $intLinID,'fieldname' ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $intLinID,'required' ))? "checked":'' ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "fieldvalue", $_C->fctGetContent( $intLinID,'fieldvalue' ) ,$strContent );
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='fctSave(".$intLinID.")'>{Save}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Language
	    $this->arrDocument['js_language']['EaseFormLangTxt'] = $this->fctSetJSLangTxT( 'Form' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}
	
	public function fctCheckboxEdit( $intLinID )
	{
	    
	    // Classes
	    $_C = new _Content();
	    
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-checkbox'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup-checkbox'];
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	        
	    // Values
	    $intCountValues = $_C->fctGetContent( $intLinID,'fieldvalue_count' );
	    if( !$intCountValues ) $intCountValues = 1;
	    $strReplaceValue = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-checkbox-values'] );
	    for( $i=1 ; $i<=$intCountValues ; $i++ )
	    {
		$strValue .= _ParseDoc::fctTagReplace( "fieldvalue", $_C->fctGetContent( $intLinID,'fieldvalue'.$i ) ,$strReplaceValue );
	    }
	    
	    // Replaces
    	    $strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $intLinID,'fieldname' ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $intLinID,'required' ) == '1' )? "checked":'' ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "value", $strValue ,$strContent );
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='fctSave(".$intLinID.")'>{Save}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Language
	    $this->arrDocument['js_language']['EaseFormLangTxt'] = $this->fctSetJSLangTxT( 'Form' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}
	
	public function fctRadioEdit( $intLinID )
	{
	    
	    // Classes
	    $_C = new _Content();
	    
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-radio'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup-radio'];
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    
	    // Values
	    $intCountValues = $_C->fctGetContent( $intLinID,'fieldvalue_count' );
	    if( !$intCountValues ) $intCountValues = 1;
	    $strReplaceValue = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-radio-values'] );
	    for( $i=1 ; $i<=$intCountValues ; $i++ )
	    {
		$strValue .= _ParseDoc::fctTagReplace( "fieldvalue", $_C->fctGetContent( $intLinID,'fieldvalue'.$i ) ,$strReplaceValue );
	    }
	    
	    // Replaces
    	    $strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $intLinID,'fieldname' ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $intLinID,'required' ) == '1' )? "checked":'' ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "value", $strValue ,$strContent );
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='fctSave(".$intLinID.")'>{Save}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Language
	    $this->arrDocument['js_language']['EaseFormLangTxt'] = $this->fctSetJSLangTxT( 'Form' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}
	
	public function fctSelectEdit( $intLinID )
	{
	    
	    // Classes
	    $_C = new _Content();
	    
	    // Create Content
	    $strContent = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-select'] );

	    $this->arrDocument['css_include'][] = $this->Config['http']['domain'] . $this->Config['path']['basic'] . $this->Config['path']['cms'] . $this->Config['path']['theme']['root'] . $this->Config['path']['theme']['theme'] . $this->Config['path']['theme']['css'] . "/extension-popup.css";
	    $this->arrDocument['css_include'][] = $this->arrConf['css']['extension-popup'];
    	    $this->arrDocument['js_include'][] = $this->arrConf['js']['extension-popup-select'];
	    
	    $strContent = _ParseDoc::fctTagReplace( "message", self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-message'] ) ,$strContent );
	    
	    // Values
	    $intCountValues = $_C->fctGetContent( $intLinID,'fieldvalue_count' );
	    if( !$intCountValues ) $intCountValues = 1;
	    $strReplaceValue = self::fctLoadFile( dirname(__FILE__) ."/". $this->arrConf['tmpl']['extension-popup-select-values'] );
	    for( $i=1 ; $i<=$intCountValues ; $i++ )
	    {
		$strValue .= _ParseDoc::fctTagReplace( "fieldvalue", $_C->fctGetContent( $intLinID,'fieldvalue'.$i ) ,$strReplaceValue );
	    }
	    
	    // Replaces
    	    $strContent = _ParseDoc::fctTagReplace( "fieldname", $_C->fctGetContent( $intLinID,'fieldname' ) ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "required", ($_C->fctGetContent( $intLinID,'required' ) == '1' )? "checked":'' ,$strContent );
	    $strContent = _ParseDoc::fctTagReplace( "value", $strValue ,$strContent );
	    
	    // Footer-Buttons
    	    $strContent = _ParseDoc::fctTagReplace( "footer", "<div class='ButtonBlack'><div class='ButtonLeft'></div><div class='ButtonCenter'><a href='javascript:;' onclick='fctSave(".$intLinID.")'>{Save}</a></div><div class='ButtonRight'></div></div>" ,$strContent );
	    
	    // Language
	    $this->arrDocument['js_language']['EaseFormLangTxt'] = $this->fctSetJSLangTxT( 'Form' );
	    $strContent = $this->fctReplaceLang( $strContent );

	    $this->arrDocument['body'][]  = $strContent;
	}
	
    }
?>