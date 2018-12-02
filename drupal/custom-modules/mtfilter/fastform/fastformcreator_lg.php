<?php

function fformbot_text($aText)
{
/*	global $mosConfig_lang;
	$theLang = $mosConfig_lang;
	$theText = $aText;
	$theLang2CharCode = strtolower(substr($theLang, 0, 2));
	*/
	$theLang2CharCode = "en";
	
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