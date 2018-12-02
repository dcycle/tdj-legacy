<?php
/**
* file fastformcreator.php*/

/* overview 
* --------

	This is a Mambot for use with Joomla 1.0.13 (not tested in other versions) designed to create simple validating forms and email the result to a list of recipients.
	
	This file is distributed with an .xml file called fastformcreator.xml; both files should exist within a zip archive.

* features 
* --------
	-> Uses progressive enhancement, meaning that javascript is used only if the browser supports, but JS is not required (introduced in 100a100). 
	->ÊWorks in spanish, english or french (introduced in 100a100).

* installation 
* ------------
	->Make sure you are using PHP 5 or later; PHP 4 won't work.
	->Make sure you have installed the mambot using the install feature in the Joomla backend. Select the .zip file which includes this file and the accompanying xml file. 
	->Make sure the Mambot is published or else it won't do anything at all.
	
* usage 
* -----
	->In your CONTENT ITEMS within JOOMLA, type in fastform tags "{fastform ...}" and they will be converted into form elements. You can use a variety of fastform tags; all the data collected in the form will be emailed to you when you click submit. No database integration.
	
	The format of a fastform tag is
	
	{fastform TYPE NAME VALUE VALIDATORS}
	
	Allowed values in these arguments are 
	
	TYPE
		field:
		file:
		textarea:
		menu:
		list:
		submit:
		[if you enter anything else here or if you use a type introduced in a subsequent version, ]

	NAME
		You must enter a unique name here.
		[if you enter anything else (a non-unique name) ]
	
	VALUE
		An initial value
		null: null will not enter the word "null" but rather nothing at all here. 
		
	VALIDATORS
		
	
* how it works 
* ------------
	->Note that a form is created and links back to this page (URL or script). Therefore we have only one place where we display the form and deal with it if it has been posted.

* version history
* ---------------
* 20080304 100a101 require now, fastformcreator.php, because this (php5) can also be called from a file other than fastformcreator.php, in Drupal for example
* 20071120 100a100 started
* 
* Copyright (C) 2007 Mediatribe.net All Rights Reserved
* license http://www.gnu.org/copyleft/gpl.html
*
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once('fastformcreator_validators.php');
require_once('fastformcreator_commands.php');
require_once('fastformcreator_asserts.php');
require_once('fastformcreator_Form.php');
require_once('fastformcreator_lg.php');
require_once('fastformcreator_core.php');

/**
* Mambot that Cloaks all emails in content from spambots via javascript
*/

Class fformbot_System extends fformbot_Object
{
	private function AddEmail($aEmailAddress, $aInternalVar)
	{
		fformbot_assert_var($aEmailAddress, "email");
	
		$theMailToEmailArray = $this->GetVar($aInternalVar);
		
		if(!is_array($theMailToEmailArray))
		{
			$theMailToEmailArray = array();
		}

		array_push($theMailToEmailArray, $aEmailAddress);
		
		fformbot_assert_var($theMailToEmailArray, "array:email", "9467");

		$this->SetVar($aInternalVar, $theMailToEmailArray);
	}

	private function GetEmailArray($aInternalVar)
	{
		$theMailToEmailArray = $this->GetVar($aInternalVar);
		
		if(!is_array($theMailToEmailArray))
		{
			$theMailToEmailArray = array();
		}
		
		fformbot_assert_var($theMailToEmailArray, "array:email");

		return $theMailToEmailArray;
	}

	function SetAbsolutePath($aPath)
	{
		fformbot_assert_var($aPath, "absdir", null, true);
	
		$this->SetVar("abspath", $aPath);
	}
	
	function SetLiveUrl($aUrl)
	{
		fformbot_assert_var($aPath, "livedir", null, true);

		$this->SetVar("liveurl", $aPath);
	}

	function GetAbsolutePath()
	{
		$theReturn = $this->GetVar("abspath");

		fformbot_assert_var($aPath, "absdir", null, true);

		return $theReturn;
	}
	
	function GetLiveUrl()
	{
		$theReturn = $this->GetVar("liveurl");

		fformbot_assert_var($aPath, "livedir", null, true);

		return $theReturn;
	}

	function AddMailToEmail($aEmailAddress)
	{
		$this->AddEmail($aEmailAddress, "mailto_array");
	}
	
	function GetMailToEmailsArray()
	{
		$theReturn = $this->GetEmailArray("mailto_array");
		
		return $theReturn;
	}
	
	function AddErrToEmail($aEmailAddress)
	{
		$this->AddEmail($aEmailAddress, "errto_array");
	}
	
	function GetErrToEmailsArray()
	{
		$theReturn = $this->GetEmailArray("errto_array");
		
		return $theReturn;
	}
	
}

function fformbot_attempt_to_create_form($aText, $aId, $aDisplayErrorFlag, $aSendErrorFlag, $aMailTo, $aErrorTo, $aAbsoluteSystemPath, $aLiveUrl)
{
	/* Called by fformbot_mambot() if we are using the required php5, just call the actual createform function and catch any errors. Optionally display them at the beginning of the text and/or send a report to the tech guy. */ 
	
	try {
	
		$theSystem = new fformbot_System();
		
		$theSystem->AddMailToEmail($aMailTo);
		$theSystem->AddErrToEmail($aErrorTo);
		$theSystem->SetAbsolutePath($aAbsoluteSystemPath);
		$theSystem->SetLiveUrl($aLiveUrl);
	
		$theForm = new fformbot_Form($aText, $aId, $theSystem);
			
		return $theForm->ToHtml();
	
	} catch (Exception $e) {
	
		$theReturn = $aText;
		
		if($aDisplayErrorFlag) 
		{
			$theIntro = fformbot_text('__no_form_because_of_error')."<br/>"; 
		}
		if($aSendErrorFlag) 
		{
			fformbot_send_error_message_to_tech_department($e, $aErrorTo);
		
			$theIntro .= fformbot_text('__tech_dept_notified')."<br/>"; 
		}
		
		$theReturn = $theIntro.$theReturn;
		
		return $theReturn;
	}
}

function fformbot_send_error_message_to_tech_department($aErrorObject, $aErrorTo)
{
	/* called by fformbot_attempt_to_send_form() in the case where an error occurs */

	$theTrace = $aErrorObject->getTraceAsString();
	$theErrorMessage = $aErrorObject->getMessage();
	$theTo = $aErrorTo;
	$theProduct = fformbot_get_product_name();
	$theSubject = $theProduct.": Error report";
	$theVersion = fformbot_get_version();
	$theEmailMessage = "Product: $theProductName; Version: $theVersion; Message: $theErrorMessage; Trace: $theTrace"; 
	$theHeaders = "From:".fformbot_get_system_email();
	
	mail($theTo, $theSubject, $theEmailMessage, $theHeaders);
}


?>