<?php
    // Class: EaseForm
    class _EaseForm extends _EaseGlobalFunctions 
    {
	
	var $strFormName;
	var $strEmailRecipient;
	var $strEmailFrom;
	var $arrFields;
	var $arrErrorFields;
	var $boolSave;
	var $strMessageSucces;
	var $strMessageError;
	var $strMessageErrorRequired;
	var $strMessageErrorCaptcha;
	
	public function __construct()
	{
	    parent::__construct();
	}
	
	// Add Field to Form
	public function fctAddField( $arrSettings )
	{
	    $this->arrFields[] = $arrSettings;
	}
	
	// Save Data
	public function fctCheckForm( $intID )
	{
	    if( count( $this->arrFields ) > 0 && $_POST )
	    {
		// Check Fields
		foreach( $this->arrFields as $arrField )
		{
		    // Required Field?
		    switch( $arrField['type'] )
		    {
			case "checkbox":
				if( $arrField['required'] == '1' )
				{
				    $boolRequiredFieldSend = false;
				    for( $i=1 ; $i<=$arrField['values_count'] ; $i++ )
				    {
					if( $_POST[$arrField['fieldname-'.$i]] )
					    $boolRequiredFieldSend = true;
				    }
				    if( !$boolRequiredFieldSend )
				    {
					$this->arrErrorFields[$arrField['id']] = $arrField;
					$this->arrErrorMsg['required'] = $this->strMessageErrorRequired;
				    }
				}
			    break;
			default:
			    if( $arrField['required'] == '1' && !$_POST[$arrField['fieldname']] )
			    {
				$this->arrErrorFields[$arrField['id']] = $arrField;
				$this->arrErrorMsg['required'] = $this->strMessageErrorRequired;
			    }
			    break;
		    }
		}
		
		// Check Captcha
		if( $_POST['captcha'] != $_SESSION['EASEFORM_CAPTCHA'] )
		{
		    $this->arrErrorMsg[] = $this->strMessageErrorCaptcha;
		    $this->arrErrorFields['captcha'] = array("fieldname"=>"Captcha");
		}

		// Save
		if( count( $this->arrErrorFields ) == 0 )
		{
		    // Save in Database
		    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easeform_data (eed_name,eed_date,eed_ip,eed_document) VALUES ('". $this->strFormName ."','NOW()','". addslashes( $_SERVER['REMOTE_ADDR'] ) ."','". addslashes( $_SERVER['PHP_SELF'] ) ."')" );
		    $intDataID = $this->mysql_insert_id;
		    foreach( $this->arrFields as $arrField )
		    {
			switch( $arrField['type'] )
			{
			    case "checkbox":
				unset( $strCheckboxes,$strSpace );
				for( $i=1 ; $i<=$arrField['values_count'] ; $i++ )
				{
				    if( $_POST[$arrField['fieldname']."-".$i] )
				    {
					$strCheckboxes .= $strSpace . $this->fctClearValue( $_POST[$arrField['fieldname']."-".$i] );
					$strSpace = ", ";
				    }
				}
				if( $strCheckboxes )
				    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easeform_fields (eef_eed_id,eef_name,eef_value) VALUES ('". $intDataID ."','". $arrField['name'] ."','". $strCheckboxes ."')" );
				break;
			    default:
				if( $_POST[$arrField['fieldname']] )
				    $this->fctQuery( "INSERT INTO ". $this->Config['database']['table_prefix'] ."ext_easeform_fields (eef_eed_id,eef_name,eef_value) VALUES ('". $intDataID ."','". $arrField['name'] ."','". $this->fctClearValue( $_POST[$arrField['fieldname']] ) ."')" );
				break;
			}
		    }
		    
		    // Send E-Mail
		    $_Mail = new PHPMailer();
		    $_Mail->IsSendmail();
		    
		    // From
		    if( $this->strEmailRecipient )
			$_Mail->SetFrom( $this->strEmailRecipient,'' );

		    // Reply
		    //$_Mail->AddReplyTo("info@rotary-oberhausen.de","Rotary Oberhausen");

		    // Recipient
		    if( $this->strEmailRecipient )
			$_Mail->AddAddress( $this->strEmailRecipient,'' );

		    // Subject
		    $_Mail->Subject = "Mail-Form: ". $this->strFormName;

		    // Text
		    $_Mail->AltBody = "";
		    
		    // Attachment
		    //$mail->AddAttachment("images/phpmailer.gif");
		    
		    foreach( $this->arrFields as $arrField )
		    {
			switch( $arrField['type'] )
			{
			    case "checkbox":
				unset( $strCheckboxes,$strSpace );
				for( $i=1 ; $i<=$arrField['values_count'] ; $i++ )
				{
				    if( $_POST[$arrField['fieldname']."-".$i] )
				    {
					$strCheckboxes .= $strSpace . $this->fctClearValue( $_POST[$arrField['fieldname']."-".$i] );
					$strSpace = ", ";
				    }
				}
				if( $strCheckboxes )
				    $strHTML .= $arrField['name'] ." = ". $strCheckboxes ."<br />";
				break;
			    default:
				if( $_POST[$arrField['fieldname']] )
				    $strHTML .= $arrField['name'] ." = ". $this->fctClearValue( $_POST[$arrField['fieldname']] ) ."<br />";
				break;
			}
		    }
		    
		    // HTML
		    $_Mail->MsgHTML( $strHTML );

		    // Send
		    if(!$_Mail->Send())
		    {
			$this->arrErrorMsg[] =  $_Mail->ErrorInfo;
		    }
		    
		    $this->arrErrorMsg[] = $this->strMessageSuccess;
		    $this->boolSave = true;
		    return true;
		}
		else
		    return false;
	    }
	    else
		return false;
	}
	
	private function fctClearValue( $strText )
	{
	    $strText = addslashes( strip_tags( $strText ) );
	    return $strText;
	}

    }
?>