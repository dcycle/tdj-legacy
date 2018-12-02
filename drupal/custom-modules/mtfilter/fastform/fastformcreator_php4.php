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
* 20071120 100a100 started
* 
* Copyright (C) 2007 Mediatribe.net All Rights Reserved
* license http://www.gnu.org/copyleft/gpl.html
*
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* Mambot that Cloaks all emails in content from spambots via javascript
*/

function fformbot_attempt_to_create_form($aText)
{
	// if someone is attempting to use our mambot under php4, inform them that
	// it won't work, but only if the story contains {fastform...

	if(strpos($aText, "fastform"))
	{
		$theIntro = "<span style=\"color:red;background-color:white;font-weight:bold;\">".fformbot_text("__php4_no_good")."</span>";
	}
	
	return $theIntro."<br/>".$aText;
}


?>