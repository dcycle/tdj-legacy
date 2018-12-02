<?php
/**
* file fastformcreator.php*/

function fformbot_get_version()
{
	return "1.0.0a100";
}

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
* 20071120 100a100 started
* 
* Copyright (C) 2007 Mediatribe.net All Rights Reserved
* license http://www.gnu.org/copyleft/gpl.html
*
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

// register our main function
if($gMambo) $_MAMBOTS->registerFunction( 'onPrepareContent', 'fformbot_mambot' );

// require the appropriate file for our version of php. This mambot does not 
// work with php4, but since many systems work on php4, we want to exit 
// gracefully, not bring down the entire system. If php4 is detected, 
// the included function will simply state that the mambot should be
// disabled or php5 installed. (eventually the php4 file could actually be
// made to work if someone wants to program it!)

require_once(fformbot_get_file_for_php_version());
require_once("configuration.php");

/**
* Mambot that Cloaks all emails in content from spambots via javascript
*/

function fformbot_mambot( $published, &$row, &$params, $page=0 )
{
		// quick test to see if we should continue.
	if(!strpos($row->text, "fastform "))
	{
		return;
	}

	/* Called by the Joomla! site */ 

	/* this is called by joomla before content is displayed on the website 
	The content itself in the database is never modified. Every time it is
	displayed, it is modified just before the user sees it. This is the
	way MAMBOTs function within Joomla in 1.0.x. */

		// this is where we want to be sending the contents of the form.
	$theFormMailTo = fformbot_where_to_send_contents_email();
	
		// this is the email to which to send errors and technical stuff. It is used in cases where an error has occured
	$theErrorMailTo = "ffc071123@mediatribe.net";
	
	$theAbsDir = fformbot_joomla_abs_dir();
	$theLiveDir = fformbot_joomla_live_dir();
	
	
		// "{fastform " is present in the text, let's attempt to create a form
		// (we tested it earlier)
	if($published)
	{
		$row->text = fformbot_attempt_to_create_form($row->text, $row->id, true, true, $theFormMailTo, $theErrorMailTo, $theAbsDir, $theLiveDir);
	}
}

function fformbot_joomla_abs_dir()
{
	global $mosConfig_absolute_path;
	
	return $mosConfig_absolute_path."/";
}

function fformbot_joomla_live_dir()
{
	global $mosConfig_live_site;
	
	return $mosConfig_live_site;
}

function fformbot_get_system_email()
{
	/* Joomla Globals */

	global $mosConfig_mailfrom;

		// this is specified in global configuration. It is the mail from address to send form results and also technical notices. 	
	$theReturn = $mosConfig_mailfrom;
	
	return $theReturn;
}

function fformbot_where_to_send_contents_email()
{
	return "fastform_for_testing_destination@mediatribe.net";
}

function fformbot_get_file_for_php_version()
{
	$theFullVersion = phpversion();
	
	$theGeneration = substr($theFullVersion, 0, 1);
	
	if($theGeneration >= 5)
	{
		$theGeneration = 5;
	}
	else
	{
		$theGeneration = 4;
	}
	
	$theJoomlaMambotPath = "mambots/content/";
	
	$theReturn = $theJoomlaMambotPath."fastformcreator_php".$theGeneration.".php";
	
	return $theReturn;
}

function fformbot_get_product_name()
{
	/* utility returning info about the system*/
	
	return "Fast Form Creator";
}

function fformbot_text($aText)
{
	global $mosConfig_lang;
	$theLang = $mosConfig_lang;
	$theText = $aText;
	$theLang2CharCode = strtolower(substr($theLang, 0, 2));
		
	/* utility function to display text in the correct language. 
	There are two systems to identify a concept:
	
	(1) internal system for this mambot: "__text" as a string 
	(2) the joomla system, eg _CMN_YES (not a string, a constant), as defined in the languag file of joomla. French, Spanish, English are supported
	
	*/

	switch($theLang2CharCode)
	{
		case "en":
			switch($theText)
			{	
				case "__form_filled_email_subject":
					return 'Form filled report';
				case "__cannot_submit_form":
					return 'Cannot submit the form';
					break;
				case "__tech_dept_notified":
					return 'Our technical department has been notified of the error and we hope to correct it as soon as possible';
					break;
				case "__thanks": 
					return "Thanks for filling out this form!"; 
					break;
				case "__php4_no_good": 
					return "Sorry, but you are attempting to use the ".fformbot_get_product_name()." mambot, but it does not work under php4, the version of php installed on your system. To avoid getting this error, please take one of the following actions (a) contact your system administrator to upgrade your version of php if you want to use this mambot, (b) disable (unpublish) the ".fformbot_get_product_name()." mambot it in your Joomla! backend; or (c) do not use \"fastform\" in any of your articles."; 
					break;
				case "__no_form_because_of_error": 
					return "Sorry. The form could not be created because of an error";
					break;
				default:
					break;
			};
			break;
		case "fr":
			switch($theText)
			{	
				case "__form_filled_email_subject":
					return 'FRForm filled report';
				case "__cannot_submit_form":
					return 'FRCannot submit the form';
					break;
				case "__tech_dept_notified":
					return 'FROur technical department has been notified of the error and we hope to correct it as soon as possible';
				case "__thanks":
					return "Merci d'avoir rempli ce formulaire";
					break;
				case "__no_form_because_of_error": 
					return "FRSorry. The form could not be created because of an error";
					break;
				case "__php4_no_good": 
					return "D&eacute;sol&eacute;, vous ne pouvez pas utiliser le mambot ".fformbot_get_product_name()." sous PHP 4, la version de PHP install&eacute; sur votre syst&egrave;me. Pour ne plus voir ce message d'erreur, SVP faire une des choses suivantes (a) demandez &agrave; votre administrateur syst&egrave;me d'installer PHP5, (b) suspendre la publication de ".fformbot_get_product_name()." dans le module d'administration Joomla; ou (c) ne pas &eacute;crire \"fastform\" null part dans vos articles."; 
					break;
				default:
					break;
			};
			break;
		case "sp":
			switch($theText)
			{	
				case "__email_format_not_good":
					return 'Email invalido ';
					break;
				case "__must_enter_sth_BEF":
					return 'Deben entrar algo en ';
					break;
				case "__must_enter_sth_AFT":
					return '.';
					break;
				case "__form_filled_email_subject":
					return 'esForm filled report';
					break;
				case "__cannot_submit_form":
					return 'No puede someter la forma';
					break;
				case "__tech_dept_notified":
					return 'ESOur technical department has been notified of the error and we hope to correct it as soon as possible';
				case "__no_form_because_of_error": 
					return "ESSorry. The form could not be created because of an error";
					break;
				case "__thanks":
					return "Gracias!";
					break;
					return "No es posible usar ".fformbot_get_product_name()." con PHP 4, la versi&oacute;n de PHP sobre su sistema."; 
				default:
					break;
			};
			break;
		default:
			return $aText;
			break;
	};
	
	return $aText;
}

?>