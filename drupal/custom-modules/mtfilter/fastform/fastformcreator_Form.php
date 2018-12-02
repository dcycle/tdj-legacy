<? 

class fformbot_Form extends fformbot_Object
{
	private $itsSystem;
	private $itsText;
	private $itsId;

		/* the only reason we are using the id is to have a unique id with which to identify the form, especially in cases where there are several forms (stories) on the same html page. This way we can validate the correct form and submit the correct form */
	private $itsName;
	
		/* we keep a list of its commants in an array of fformbot_Field objects. Commands can include fields, a thank you message, anything that can now or in future versions be parsed. A fastform command in the text is entered as {fastform...}, where ... can be anything. */
	private $itsCommands;
		
	private $itsJavascriptFunctions;
	private $itsJavascriptValidators;
		
	function GetName()
	{
		return $this->itsName;
	}

	function fformbot_Form($aText, $aId, $aSystem)
	{
		$this->itsSystem = $aSystem;
		$this->itsText = $aText;
		$this->itsId = $aId;
		$this->itsName = 'fastform'.$aId;
		$this->InitJavascript();
		$this->InitCommands(); 
	}
	
	function GetScriptOpeningTag()
	{
		return 	'<script language="JavaScript" type="text/javascript">';
	}
	
	function GetJavascript()
	{
		$theFormName = $this->itsName;
	
		$theJavascript = $this->GetScriptOpeningTag();
	
		$theJavascript .= fformbot_whitespace_to_space("

			function validate() 
			{ 
				object = document.getElementById(\"".fformbot_get_submit_button_id()."\"); 
				
				object.type=\"button\";
		
				".$this->GetAllOnSubmitValidators()."

				{ document.$theFormName.submit(); } 
			}

		").$this->GetAllRuntimeValidators();
		
		$theJavascript .= '</script>';
		
		return $theJavascript;
	}
	
	function GetAllOnSubmitValidators()
	{
		foreach($this->itsCommands as $theCommand)
		{
			$theJS .= $theCommand->GetOnSubmitValidator();
		}
		
		return $theJS;
	}
	
	function GetAllRuntimeValidators()
	{
		foreach($this->itsCommands as $theCommand)
		{
			$theJS .= $theCommand->GetRuntimeValidator();
		}
		
		return $theJS;
	}
	
	function InitJavascript()
	{
		$this->itsJavascriptFunctions = array();
		$this->itsJavascriptValidators = array();
	}
	
	function InitCommands()
	{
		// we want our entire text to be represented by a list (array) of commands, each of which will be either a special something like a field, or just a piece of text.
	
		$this->itsCommands = array();
		
		$theLocationInText = 0; // start at the beginning
		
		while($theLocationInText = $this->ParseOneCommand($theLocationInText))
		{
			; // do nothing; just loop through the text. When $theLocationInText
				// will be at the end of the text, nothing more to be done.
		}
	}
	
	function ParseOneCommand($aLocationInText)
	{
		// prevent going into a loop

		static $sPreviousLocation = -1;
	
		if($sPreviousLocation == $aLocationInText)
		{
			throw new Exception("infinite loop detected in Form->ParseOneCommand");
		} else
		{
			$sPreviousLocation = $aLocationInText;
		}
	
		$theText = $this->itsText;
		$theCursor = $aLocationInText;
		
		// check if we need to return null
	
		if($theCursor >= strlen($theText))
		{
			return null;
		}
	
		// see what's left to parse.
		
		$theRemainingText = substr($theText, $theCursor);
	
		/* Two possibilities:  A command can be either a {fastform...} tag or something between {fastform...} tags */
	
		$theCommandString = $this->FindOneCommandInText($theRemainingText);
	
		if(!$theCommandString) 
		{
			throw new Exception("We checked at the beginning of this function if we were at the end of the text, and we should have a command here. The Command String is empty, yet the cursor is at ($theCursor) and the text is (".strlen($theText).") chars long. (it is ($theText)).");
		}
	
		/* let's ask the commands class to create an object of this type */
	
		$theCommandObject = fformbot_Command::sNewCommand($theCommandString, $this);
	
		array_push($this->itsCommands, $theCommandObject);
	
		$theCmdStrLen = strlen($theCommandString);
	
		if(!$theCmdStrLen)
		{
			throw new Exception("theCommand string length cannot be zero!");
		}
	
		$theReturn = $theCursor+$theCmdStrLen;

		// infinite loop detector.

		if($theReturn == $aLocationInText)
		{
			throw new Exception("Form->ParseOneCommand was about to return ($theReturn), which would have resulted in an infinite loop");
		}

		return $theReturn;
	}
	
	function FindOneCommandInText($aText)
	{
		$theText = $aText;
	
		/* We should find in aText, something which looks like {fastform ....} and return it */
	
		$theLocationOfTag = strpos($aText, "{fastform ");
	
		if($theLocationOfTag === false)
		{
			return $aText;
				// the command is equal to the entire text
		}

		if($theLocationOfTag == 0)
		{
			$theReturn = fformbot_find_once_in('/{fastform[^}]*}/', $theText);
				// just return the first tag, it is for sure at the beginning of the text.
				
			if(!$theReturn)
			{
				throw new Exception("The return value can't be null here. We know that ({fastform ) exists in ($aText) at location 0. Therefore, the return value should be the whole {fastform...} tag.");
			}
			
			return $theReturn;
		}

		else
		{
			$theReturn = substr($aText, 0, $theLocationOfTag);
				// return everything before the tag

			if(!$theReturn)
			{
				throw new Exception("The return value can't be null here (2)");
			}
			
			return $theReturn;
		}
	}
		
	function ToHtml()
	{
		if($this->Submitted())
		{
			if($this->GetErrors()) 
			{
				return $this->DisplayForm(true);
			}
			else
			{
				$this->ProcessForm();
				return $this->DisplayThankYouMessage();
			}
		}
		else
		{
			return $this->DisplayForm(false);
		}
	}
	
	
	function Submitted()
	{
		/* determines if the form has been submitted. Since the actual form and the thank you message appear on the same page, we need a way to determine if the form was submitted. Note that the fact that a form was submitted does not necessarily mean that it is valid. 
	
		The technique to determine if a form was submitted is this: a form parameter is posted with the form name as a value. 
		*/ 
	
		return $_POST['form'] == $this->GetName();
	}
	
	function GetErrors()
	{
		$theErrorsArray = array();
	
		foreach($this->itsCommands as $theCommand)
		{
			$theError = $theCommand->GetError();
			
			if($theError)
			{
				array_push($theErrorsArray, $theError);
			}
		}
		
		if(count($theErrorsArray))
		{
			return $theErrorsArray;
		}
	}

	function GetAllInputsToHtml()
	{
		foreach($this->itsCommands as $theCommand)
		{
			if($theWithinFormFlag)
			{
				$theReturn .= $theCommand->GetHtml();
			}
			else
			{
				if($theCommand->IsAFormInput())
				{
					$theWithinFormFlag = true;
					$theReturn .= $theCommand->GetHtml();
				}
			}
		}
		
		if(!$theReturn)
		{
			throw new Exception("GetAllCommandsToHtml() should not return a null string. This would signal that there is absolutely no author-generated content in the form, which is not allowed.");
		}
		
		return $theReturn;
	}

	function GetAllBeforeToHtml()
	{
		foreach($this->itsCommands as $theCommand)
		{
			if($theCommand->IsAFormInput())
			{
				return $theReturn;
			}
			else
			{
				$theReturn .= $theCommand->GetHtml();
			}
		}
		
		return $theReturn;
	}

	function GetAllAfterToHtml()
	{
		foreach($this->itsCommands as $theCommand)
		{
			if($theCommand->IsAFormInput())
			{
				$theFormAlreadyPassedFlag = true;
			
				continue;
			}
			else if($theFormAlreadyPassedFlag)
			{
				$theReturn .= $theCommand->GetHtml();
			}
		}
		
		return $theReturn;
	}

	function DisplayForm($aDisplayErrorsFlag = false)
	{
		$theFormName = $this->itsName;
			// this in cases where we have more than one form on a page. We identify // it with the id of the story (article). So we can validate and submit // the correct form.

		$theReturn = $this->GetJavascript();
	
		/* now the form itself. Note the action goes back to the same script, because we want to make everything simple for the user and process the results ourselves */

		$theReturn .= $this->GetAllBeforeToHtml();
	
		$theReturn .= "<form enctype=\"multipart/form-data\" ".fformbot_input_id($theFormName)." action=\"".fformbot_get_this_script()."/clearcache\" method=\"post\"><input type=\"hidden\" name=\"form\" value=\"$theFormName\"/><fieldset><legend>form</legend><ol>";

		$theReturn .= $this->GetAllInputsToHtml();

		$theReturn .= $this->OptionalLastResortSubmitButton ($theReturn)."</ol></fieldset></form>";

		$theReturn .= $this->GetAllAfterToHtml();
	
		return $theReturn;
	}
	
	function ProcessForm()
	{
		/* the form has been filled and contains no errors */
		
		foreach($this->itsCommands as $theCommand)
		{
			$theEmailText .= $theCommand->GetEmailTextAsLine();
			
			$theTempPossibleFromEmail = $theCommand->GetFromEmail();
			
			if($theTempPossibleFromEmail)
			{
				if(!$thePossibleFromEmail)
				{
					$thePossibleFromEmail = $theTempPossibleFromEmail; 
				} else {
					throw new Exception("Check your script; two tags are attempting to define the from email. Only one from email can exist.");
				}
			}
		}
			
		$theMessage = $theEmailText;
	
		if(!$theMessage)
		{
			throw new Exception("Sending an empty form filled notification message is not allowed");
		}
	
		$theSystem = $this->itsSystem;
		$theMailToArray = $this->GetMailToEmailsArray();
	
		$theTo = implode(",",$theMailToArray);
	
		$theSubject = fformbot_text("__form_filled_email_subject");

		$theHeaders = "From:".$thePossibleFromEmail?$thePossibleFromEmail:fformbot_get_system_email()."\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\n";
	
		mail($theTo, $theSubject, $theMessage, $theHeaders);
		
	}
	
	function GetMailToEmailsArray()
	{
		$thePossibleMailToObject = $this->GetVar("itsMailtoObject", $theReturn);

		if($thePossibleMailToObject)
		{
			$theMailToObject = $thePossibleMailToObject;
			
			return $theMailToObject->GetMailToEmailArray();

		}
		else
		{
			$theSystem = $this->itsSystem;
			$theMailToArray = $theSystem->GetMailToEmailsArray();

			return $theMailToArray;
		}
	}
	
	function DisplayThankYouMessage()
	{
		$theThanksObject = $this->GetVar("itsThanksObject");
	
		if($theThanksObject)
		{
			return $theThanksObject->GetThankYouMessage();
		}
	
		return fformbot_text("__thanks");
	}

	function OptionalLastResortSubmitButton($aText)
	{
		/* called by fformbot_finalize_form() */
	
		/* this function returns code for a submit button (actually a button which triggers the js onclick=validate(), because we want to validate before submitting). The code is returned ONLY if it does not exist already. */
		
		if(fformbot_string_contains_substring($aText, 'onclick=validate()'))
		{
			return null;
		}
		
		return fformbot_submit_button_code(fformbot_Command::sGetForDisplay("en#Submit|es#Enviar|sp#Enviar|fr#Soumettre"));
	}
}

	




function fformbot_create_input($aType, $aName, $aValue, $aValidatorsFlatList, &$aJavascript, $aFormName, &$aJavascriptRoot)
{
	if(is_array($aValidatorsFlatList))
	{
		throw new Exception("fformbot_create_input() cannot be passed an array, but a flat list (;-delimited)");
	}

	/* Called by fformbot_insert_one_input() to create the input which will be inserted.  */ 

	/* Takes information in the form of paramaters (arguments) and returns the actual CODE for a form element, for example <input type="textfield".../>.  
	
	type = anything in the switch block in the function fformbot_generate_basic_code. 
	
	name = any valid name for a form element
	
	value = the initial value for the form element. 
	
	validators = a semicolon (; - NO SPACE AFTER!)-separated list of validators. see fformbot_get_code_for_validator for allowed validators. You can have something like "telephone;required". special validators include an UNDERSCORE (_). Everything after the first underscore and between subsequent underscores are ARGUMENTS. For example, max_1000 allows a maximum of 1000 chars. The abstract notation for these replace the argument with a variable name used internally, for example max_x. max_x is not a valid validator, but max_1000 is. You could have something like 'required;email;max_10' if you wanted only short email addresses.
	
	javascript: pointer to the inside of a js function. We can add our own code here to make sure everything validates
	
	*/ 

	/* take note of the info we need */
	
	$theType = $aType;
	$theName = $aName;
	$theValue = $aValue;
	$theValidatorsArray = fformbot_flat_list_to_array($aValidatorsFlatList);
	$theRequiredFlag = fformbot_see_if_validator_list_includes_required($theValidatorsArray);
	
	/* with this information, we must provide an html form element*/

	$theCode = fformbot_generate_basic_code($theType, $theName, $theValue, $theRequiredFlag);

	fformbot_add_validators_to_code($theName, $theValidatorsArray, $aJavascript, $aFormName, $aJavascriptRoot);

	fformbot_assert_not_null($aJavascriptRoot, "a javascript root cannot be empty in fformbot_create_input()");

	return $theCode;

}

function fformbot_see_if_validator_list_includes_required($aValidatorsArray)
{
	/* called by fformbot_create_input(). Passed a list of validators, 
	see if one of the validators is required */
	
	$theValidators = $aValidatorsArray;
	
	$theReturn = in_array("required", $theValidators);
	
	return $theReturn;
	
}

function fformbot_alphanum($aName)
{
	/* utility function which takes a string and removes everything which is not a-zA-Z0-9_- */
	
	return ereg_replace("[^0-9a-zA-Z_-]", "_", $aName);
}


function fformbot_input_label($aName, $aRequired, $aLabel)
{
	$theName = $aName;
	
	if($aRequired)
	{
		$theAsteriskIfRequired = "<span style=\"color:red;\">*</span> ";
	}
	
	return '<label for="'.$theName.'">'.$theAsteriskIfRequired.$aLabel.'</label>';
}

function fformbot_clean_value($aValue)
{
	/* utility function which returns aValue, except if aValue is exactly equal to "null", in which case null is returned */
	
	if($aValue == "null")
	{
		return null;
	}
	else {
		return $aValue;
	}
}

function fformbot_generate_basic_code($aType, $aName, $aValue, $aRequired)
{
	/* Called by fformbot_create_input() to create the basic code for the element without the validation. */ 

	$theType = $aType;
	$theName = $aName;
	$theDivId = fformbot_name_to_colordiv_id($aName);
	$theValue = fformbot_clean_value($aValue);

	$theReturn = "<div id=\"$theDivId\" style=\"background-color:#ccc\">";

	switch($theType)
	{
		case 'field':
			$theReturn .= fformbot_input_label($theName, $aRequired).'<input '.fformbot_input_id($theName).' type="text" value="'.$theValue.'"'.fformbot_on_change_code($theName).'>';
			break;
		case 'file': /* name is not used */
			$theReturn .=  fformbot_input_label($theName, $aRequired).'<input type="file"  '.fformbot_input_id($theName).' value="">';
			break;
		case 'textarea':
			$theReturn .=  fformbot_input_label($theName, $aRequired).'<textarea '.fformbot_input_id($theName).' cols="70" rows="20">'.$theValue.'</textarea>';
			break;
		case 'menu':
		case 'list':
			$theReturn .=  fformbot_generate_list_code( $theType, $theName, $theValue);
			break;
		case 'submit': /* we will ignore the name here because the name of the submit button is always the same to satisfy js and progressive enhancement stuff */
			$theReturn .= fformbot_submit_button_code($theValue); break;
		default:
			return null;
			break;
	}
	
	$theReturn .= "</div>";
	
	return $theReturn;
}


function fformbot_submit_button_code($aValue)
{
	$theValue = $aValue;

		/* note here that we could use the type button, since validate() will submit the form if all is well, even if the button is not of type submit. We will, however, use the type submit and change the type to button to ensure proressive enhancement, and to avoid the form breaking when js is inactive */
	return '<li><input class="submit" type="submit" id="'.fformbot_get_submit_button_id().'" onclick="validate()" value="'.$theValue.'"></li>';
}

function fformbot_get_submit_button_id()
{
	/* everyone wanting to get the submit button should use this function to do so*/
	
	return "submit_button";
}

function fformbot_generate_list_code($aType, $aName, $aValue)
{
	/* Called by fformbot_generate_list_code() because list code is a bit complex so we decided to have a separate function. */ 

	$theType = $aType;
	$theName = $aName;

	/* what differentiates a list and menu? list has size parameter and multiple*/

	if($theType == "list")
	{
		$theSelectParams = ' size = "5" multiple';
	}

	/* we must create a list with a name and list of values */
	
	$theReturn = fformbot_input_label($theName, $aRequired).'<select '.fformbot_input_id($theName).' '.$theSelectParams.'>';


	$theReturn .= '</select>';

	return $theReturn;
}

function fformbot_get_selected_modifier()
{
	/* a centralized place to store the selected modifier. The selected modifier is what denotes a selected item in a list of items, when prepended to an item. For example 'item1;item2;item3;item4;*item5;item6' is a list of 6 items with item5 selected. */

	return '*';
}

function fformbot_get_selected_string($aValue)
{
	/* called by fformbot_generate_list_code(). If the selected modifier is prepended to an item, return the ' selected' string which will 
	be inserted into the code to create a form element */

	$theValue = $aValue;
	$theModifier = fformbot_get_selected_modifier();

	if(substr($theValue, 0, strlen($theModifer)) == $theModifer)
		return ' selected';
	else
		return false;
}

function fformbot_remove_selected_modifier($aValue)
{
	/* called by fformbot_generate_list_code(). If the selected modifier is prepended to an item, return the item without said modifier.  */

	$theValue = $aValue;
	$theModifier = fformbot_get_selected_modifier();

    if(fformbot_get_selected_string($theValue))
    {
    	return (substr($theValue, strlen($theModifer)));
    }
    else
    {
    	return $theValue;
    }
}

function fformbot_add_validators_to_code($aName, $aValidators, &$aJavascriptInValidate, $aFormName, &$aJavascriptRoot)
{
	/* called by fformbot_create_input; we must create some js which 
	validates the input. a validators is an array of validators (see fformbot_get_code_for_validator() for a list of allowed validators). Javascript is a pointer to some code we can modify by adding to it our own code. In validate allows us to enter stuff in the final validate() function. Root is outsite the validate function and allows to create new functions. name is the name of the field (input element) to which this validation pertains */
	
	$theCleanName = fformbot_alphanum($aName);

	$aJavascriptRoot .= "function validate".$theCleanName."() {"; 

	foreach($aValidators as $theValidator)
	{
			/* add some code to the final on submit validation */
	
		$aJavascriptInValidate .= " ".fformbot_get_code_for_validator($aName, $theValidator, $aFormName, true)." ";
		
			/* add some (similar to above) code called while the form is being filled */
			
		$aJavascriptRoot .= " ".fformbot_get_code_for_validator($aName, $theValidator, $aFormName, false)." ";
	}

	$aJavascriptRoot .= " ".fformbot_get_js_to_change_validate_color($aName, "green")." }"; 
}

?>