<? 
require_once("fastformcreator_utilities.php");

abstract class fformbot_Command
{
	private $itsTag;
	private $itsForm;

	function IsAFormInput()
	{
		return false; // by default.
	}

	function GetFromEmail()
	{
		return null;
	}
	
	abstract function GetEmailTextAsLine();

	function SetTag($aTag)
	{
		if(!$aTag)
		{
			throw new Exception("You cannot set a command with a null tag");
		}
	
		$this->itsTag = $aTag;
	}

	function SetForm($aValue)
	{
		if(!$aValue)
		{
			throw new Exception("You cannot set a command with a null form associated.");
		}
	
		$this->itsForm = $aValue;
	}

	function GetForm()
	{
		$theReturn = $this->itsForm;

		if(!$theReturn)
		{
			throw new Exception("A command object's form cannot be null");
		}
		
		return $theReturn;
	}

	static function sGetForDisplay($aString)
	{
		// note that we can have ; and :, special characters, in our string
		
		// eg en#house|fr#maison
		
		$theExplodedString = explode("|", $aString);
		
		/*global $mosConfig_lang;
		$theLang = $mosConfig_lang;
		$theLang2CharCode = strtolower(substr($theLang, 0, 2));
		*/ 
		
		$theLang2CharCode = 'en';
		
		foreach($theExplodedString as $thePossibleStringInLang)
		{
			$theExplodedLangStr = explode("#", $thePossibleStringInLang);
			
			if($theExplodedLangStr[0] == $theLang2CharCode)
			{
				return $theExplodedLangStr[1];
			}
		}

		return $aString;		
	}

	function GetTag()
	{
		$theReturn = $this->itsTag;

		if(!$theReturn)
		{
			throw new Exception("A command object's tag cannot be null");
		}
		
		return $theReturn;
	}

	function GetOnSubmitValidator()
	{
		return null;		
	}

	function GetRuntimeValidator()
	{
		return null;		
	}

	function fformbot_Command($aTag, $aForm)
	{
		$this->SetTag($aTag);
		$this->SetForm($aForm);
	}

	static function sNewCommand($aTag, $aForm)
	{
		$theTag = $aTag;
		$theForm = $aForm;
			
		$theCommand = fformbot_Command::sTagCreateCommandFromType($theTag, $theForm);

		return $theCommand;
	}
	
	static function IsTag($aTag)
	{
		// it should start with fastform
		
		return fformbot_string_starts_with($aTag, "{fastform ");
	}
	
	static function sDetermineType($aTag)
	{
		$theExplicitType = fformbot_Command::sTagIncludes($aTag, "type");
		
		if($theExplicitType)
		{
			return $theExplicitType;
		}
		else
		{
			return fformbot_Command::IsTag($aTag)?"field":null;	
				// field is the type if no type is set.
		}
	}
	
	static function sTagIncludes($aTag, $aType, $aValue = null)
	{
		$theTag = fformbot_cleaned_tag($aTag);
		
		if(!fformbot_Command::IsTag($aTag))
		{
			return null;
		}
	
		$theReturn = fformbot_infostring_includes($aTag, $aType, $aValue);

		return $theReturn;
	}
	
	function TagIncludes($aType, $aValue = null)
	{
		$theTag = $this->GetTag();
		
		return $this->sTagIncludes($theTag, $aType, $aValue);
	}
	
	
	
	static function sTagCreateCommandFromType($aTag, $aForm)
	{
		static $sMaxTags = 3000;
		static $sDone = -1;
		static $sDoneTags = array();
		
		$sDone++;
		
		array_push($sDoneTags, $aTag);
		
		if($sDone > $sMaxTags)
		{
			throw new Exception("Over $sMaxTags tags processed, not allowed: ".implode("-----",$sDoneTags));
		}
		
		$theTag = $aTag;
		$theForm = $aForm;
		
		if(fformbot_Command::sDetermineType($theTag) == "field")
		{
			return new fformbot_Field($aTag, $theForm);
		}
		if(fformbot_Command::sDetermineType($theTag) == "thanks")
		{
			$theReturn = new fformbot_Thanks($aTag, $theForm);
			
			$theForm->SetVar("itsThanksObject", $theReturn);
			
			return $theReturn;
		}
		if(fformbot_Command::sDetermineType($theTag) == "mailto")
		{
			$theReturn = new fformbot_Mailto($aTag, $theForm);
			
			$theForm->SetVar("itsMailtoObject", $theReturn);

			return $theReturn;
		}
		if(fformbot_Command::sDetermineType($theTag) == "file")
		{
			return new fformbot_File($aTag, $theForm);
		}
		if(fformbot_Command::sTagIncludes($theTag, "type", "textarea"))
		{
			return new fformbot_TextArea($aTag, $theForm);
		}
		if(fformbot_Command::sTagIncludes($theTag, "type", "submit"))
		{
			return new fformbot_Submit($aTag, $theForm);
		}
		if(fformbot_Command::sTagIncludes($theTag, "type", "list"))
		{
			return new fformbot_List($aTag, $theForm);
		}
		if(fformbot_Command::sTagIncludes($theTag, "type", "Liste"))
		{
			return new fformbot_List($aTag, $theForm);
		}
		if(fformbot_Command::sTagIncludes($theTag, "type", "liste"))
		{
			return new fformbot_List($aTag, $theForm);
		}
		if(fformbot_string_starts_with($theTag, "{fastform "))
		{
			return new fformbot_UnknownTag($aTag, $theForm);
		}
		
		// default
		
		$theReturn = new fformbot_Unknown($aTag, $theForm);
		
		$theReturn->AssertIntegrity();
		
		return $theReturn;
	}
	
	abstract public function GetHtml();
	abstract public function GetError();
	
	function AssertIntegrity($aMessage = null)
	{
		if(!$this->GetTag())
		{
			throw new Exception("A command's tag cannot be null. ".$aMessage);
		}
	}
}

class fformbot_Unknown extends fformbot_Command
{

	function GetEmailTextAsLine()
	{
		return null; // not emailed to user.
	}

	function AssertIntegrity($aMessage = null)
	{
		if(!$this->GetTag())
		{
			throw new Exception("fformbot_Unknown->itsTag cannot return null; because an unkown command by definition is a piece of text which is not parsed by the fastform system. If there was no piece of text, an uknown command would not have been created. Our job is just to return whatever text we have, never an empty string. ".$aMessage);
		}

		if(fformbot_string_starts_with($this->GetTag(), "{fastform "))
		{
			throw new Exception("fformbot_Unknown->itsTag (".$this->GetTag().") cannot begin with the string '{fastform ', or else it would not be unknown. ".$aMessage);
		}
	}

	function GetHtml()
	{

		$theReturn = $this->GetTag();
			// an unknown command is anything we can't parse. We just return it as is. 
			
		return $theReturn;
	}
	
	function GetError()
	{
		return null;
			// an unknown command is anything we can't parse. We will just ignore it and there is no error. 
	}
}

class fformbot_List extends fformbot_Input
{
	function AssertIntegrity($aMessage = null)
	{
	}

	function GetHtml()
	{
		// we want something like
		
		/*		  <select name="select" size="1" multiple>
				    <option value="b" selected>a</option>
				    <option value="d">c</option>
				  </select>
		*/

		$theName = $this->GetName();
		$theLabel = $this->GetLabel();
		$theOptionalMenuArg = $this->TagIncludes("type", "menu")?" size=\"1\"":" size=\"9\" multiple";

		$theDivId = fformbot_name_to_colordiv_id($theName);

		$theReturn = "<li>";

		$theReturn .= fformbot_input_label($theName, $this->GetRequiredFlag(), $theLabel);

		$theReturn .= "<select ".fformbot_input_id($theName)."$theOptionalMenuArg".$this->GetRuntimeOnChangeCode().">";  

		$theValuesFlatList = $this->TagIncludes("values");
		$theValuesFlatList = $theValuesFlatList?$theValuesFlatList:$this->TagIncludes("options");


		$theValues = array_unique(explode(", ", $theValuesFlatList));

		foreach($theValues as $theValue)
		{
			$theSelectedString = fformbot_get_selected_string($theValue);
			$theValue = fformbot_remove_selected_modifier($theValue);
	
			$theReturn .= '<option value="'.$theValue.'"'.$theSelectedString.'>'.$theValue.'</option>';
		}
		

		$theReturn .= "</select></li>";  

		return $theReturn;
	}
	
	function GetError()
	{
		return null;
			// an unknown command is anything we can't parse. We will just ignore it and there is no error. 
	}
}

class fformbot_UnknownTag extends fformbot_Command
{

	function GetEmailTextAsLine()
	{
		return $this->GetTag()." is not a valid tag for this version.";
	}

	function AssertIntegrity($aMessage = null)
	{
		if(!$this->GetTag())
		{
			throw new Exception("fformbot_UnknownTag->itsTag cannot return null; because an unkown command by definition is a piece of text which is not parsed by the fastform system. If there was no piece of text, an uknown command would not have been created. Our job is just to return whatever text we have, never an empty string. ".$aMessage);
		}

		if(!fformbot_string_starts_with($this->GetTag(), "{fastform "))
		{
			throw new Exception("fformbot_UnknownTag->itsTag (".$this->GetTag().") must begin with the string '{fastform ', or else it would be unknown. ".$aMessage);
		}
	}

	function GetHtml()
	{

		$theReturn = "<!-- (".fformbot_text("___unknown_tag_not_displayed_to_user").") ".$this->GetTag()."-->";
			// an unknown tag should be ignored; we don't know what to do with it. 
			
		return $theReturn;
	}
	
	function GetError()
	{
		return null;
			// an unknown command is anything we can't parse. We will just ignore it and there is no error. 
	}
}

class fformbot_Input extends fformbot_Command
{
	private $itsValidators;
	
	function IsAFormInput()
	{
		return true;
	}

	function GetEmailTextAsLine()
	{
		$theLabel = $this->GetLabel();
		$thePostedValueArray = $this->GetPostedValueArray();
		$thePostedValueCount = count($thePostedValueArray);
		
		if(!$thePostedValueCount)
		{
			return $theLabel.": ".fformbot_text("__no_value_provided")."\n";
		}

		if($thePostedValueCount >Ê1)
		{
			$theDisplayCountFlag = true;;
		}
		
		foreach($thePostedValueArray as $thePostedValue)
		{
			$theReturn = $theLabel;
			if($theDisplayCountFlag)
			{
				$theReturn .= " (# ".++$theCounter.")";
			}	
			$theReturn .= ": $thePostedValue\n";
		}
	
		return $theReturn;
	}

	function GetHtml()
	{
		// we want something like
		
		/*		    <input type="text" name="textfield">

		*/

		$theName = $this->GetName();
		$theLabel = $this->GetLabel();

		$theDivId = fformbot_name_to_colordiv_id($theName);

		$theReturn = "<li>"; /*<div class=\"formsmall\" id=\"$theDivId\" style=\"background-color:#ccc\">"; 8/ this was removed when styling see
		http://www.sitepoint.com/article/fancy-form-design-css/2 */

		$theReturn .= fformbot_input_label($theName, $this->GetRequiredFlag(), $theLabel);

		$theReturn .= "<input size=\"40\" ".fformbot_input_id($theName).$this->GetRuntimeOnChangeCode()."/></li>";  

		return $theReturn;
	}

	function GetLabel()
	{
		$theName = $this->TagIncludes("name");
		$theNom = $this->TagIncludes("nom");
	
		$theName = $theName?$theName:$theNom;
	
		$theReturn = $this->sGetForDisplay($theName);
		
		return $theReturn;
	}

	function GetName()
	{
		// all we need is a unique name. Let's take the whole fastform tag and md5 it.
				
		return "a".md5($this->GetTag());
	}
	
	function GetRequiredFlag()
	{	
		$theValidatorsArray = $this->GetValidatorsArray();
	
		return fformbot_see_if_validator_list_includes_required($theValidatorsArray);
	}
	
	function fformbot_Input($aTag, $aForm)
	{
		$this->fformbot_Command($aTag, $aForm);
	
		$this->InitValidators();
	}

	function GetOnSubmitValidator()
	{
		foreach($this->itsValidators as $theValidator)
		{
			$theJS .= $theValidator->GetSubmitCode();
		}

		return $theJS;		
	}

	function GetRuntimeJSFuncName()
	{
		return "runtimeval".$this->GetName()."()";
	}

	function GetRuntimeOnChangeCode()
	{
		$theName = $this->GetName();
	
		return ' onBlur="'.$this->GetRuntimeJSFuncName().';" ';
	}

	function GetRuntimeValidator()
	{
		$theJS = "\nfunction ".$this->GetRuntimeJSFuncName() . "{ ";
		
		foreach($this->itsValidators as $theValidator)
		{
			$theJS .= $theValidator->GetOnTheFlyCode();
		}
	
		$theJS .= " { object.style.color = \"green\" } }";

		return $theJS;		
	}

	function GetValidatorsArray()
	{
		$this->itsValidators = array();
		
		$theTag = $this->GetTag();
	
		$aValidatorsFlatList = fformbot_infostring_includes($theTag, "validators");
		
		$theValidatorsArray = fformbot_flat_list_to_array($aValidatorsFlatList);
		
		return $theValidatorsArray;
	}

	function InitValidators()
	{
		$theValidatorsArray = $this->GetValidatorsArray();
		
		foreach($theValidatorsArray as $theValidatorName)
		{
			if($theValidatorObject = fformbot_Validator::sNewValidatorFromName($theValidatorName, $this))
			{
				array_push($this->itsValidators, $theValidatorObject);
			}
		}
	}
	
	function GetPostedValueArray()
	{
		$theReturn = $_POST[$this->GetName()];
		
		return array($theReturn);
	}
	
	function GetError()
	{
		$thePostedValueArray = $this->GetPostedValueArray();
		$theReturnArray = array();
		
		foreach($this->itsValidators as $theValidator)
		{
			foreach($thePostedValueArray as $thePostedValue)
			{
				if($theError = $theValidator->ErrorFor($thePostedValue))
				{
					array_push($theReturnArray, $theError); 
				}
			}
		}
		
		if(count($theReturnArray))
		{
			return implode(", ",$theReturnArray);
		}
	}
}

class fformbot_TextArea extends fformbot_Input
{
	function GetHtml()
	{
		// we want something like
		
		/*		    <input type="text" name="textfield">

		*/

		$theName = $this->GetName();
		$theLabel = $this->GetLabel();

		$theDivId = fformbot_name_to_colordiv_id($theName);

		$theReturn = "<li>";

		$theReturn .= fformbot_input_label($theName, $this->GetRequiredFlag(), $theLabel);

		$theReturn .= "<textarea cols=\"38\" rows=\"18\" ".fformbot_input_id($theName).$this->GetRuntimeOnChangeCode()."></textarea></li>";  

		return $theReturn;
	}

}

class fformbot_File extends fformbot_Input
{
	private $itsFileServerAddressArray;

	function GetPostedValueArray()
	{
		$theName = $this->GetName();
	
		static $theTransferedToServerFlag;
		static $theNewPictureHome;
		static $theNewPictureLive;
	
		$theError = $_FILES[$theName]['error'];
		
		if($theError)
		{
			return array(fformbot_text("__err_in_file").": $theError\n");
		}
	
		$theFileNameOnClientMachine = $_FILES[$theName]['name'];
		$theRealFileFlag = is_uploaded_file($_FILES[$theName]['tmp_name']);
		
		if($theFileNameOnClientMachine && !$theRealFileFlag)
		{
			throw new Exception("($theFileNameOnClientMachine) is not a real file.");
		}

		if(!$theTransferedToServerFlag)
		{
			
			$theUniqueImgName = $origImg[$i] = (time().$theRandFileNumber."ORIG.jpg");
			$theTransferedToServerFlag = true;
			$theRandFileNumber = rand(1000,9999);
			$theNewPictureHome = /*/Users/albertalbala/Sites/mediatribe.net.cur/ultramart/design002/"/Users/albertalbala/Sites/mediatribe.net.cur".fformbot_get_this_script(false, true)."images/stories/".($theUniqueImgName);*/
			"/Users/albertalbala/Sites/mediatribe.net.cur/ultramart/design002/images/stories/".$theUniqueImgName;
			
			$theNewPictureLive = "http://www.mediatribe.net/ultramart/design002/images/stories/".$theUniqueImgName;
				
			copy($_FILES[$theName]['tmp_name'], $theNewPictureHome);
		}
			 
/*		$theReturn = "<a href=\"$theNewPictureLive\">$theFileNameOnClientMachine</a>";*/
		$theReturn = $theNewPictureLive."\n";
		
		return array($theReturn);
	}
	

	function GetRuntimeValidator()
	{
		$theMaxNumFiles = $this->GetMaxElements();
		if($theMaxNumFiles > 1)
		{
			$theMultiScript = "</script>\n<script  src=\"mambots/content/fastformcreator_3rdparty/multiple-file-element/multifile.js\"></script>\n".$this->GetForm()->GetScriptOpeningTag();
		}
	
		$theJS = $theMultiScript."function ".$this->GetRuntimeJSFuncName() . "{ ";
		
		/*foreach($this->itsValidators as $theValidator)
		{
			$theJS .= $theValidator->GetOnTheFlyCode();
		} on 071205 this doesn't work validators is not an array...*/
	
		$theJS .= " { object.style.color = \"green\" } }";

		return $theJS;		
	}

	function GetHtml()
	{
		// we want something like
		
		/*		    <input type="text" name="textfield">

		*/

		$theMaxNumFiles = $this->GetMaxElements();
		if($theMaxNumFiles > 1)
		{
			$theMaxText = " (max. $theMaxNumFiles)";
			$theMaxScript = "<div id=\"".$this->GetFilesListName()."\"></div><script>var multi_selector = new MultiSelector( document.getElementById( '".$this->GetFilesListName()."' ), ".$this->GetMaxElements()." ); \nmulti_selector.addElement( document.getElementById( '".$this->GetName()."' ) ); </script>";
		}
		$theName = $this->GetName();
		$theLabel = $this->GetLabel();

		$theDivId = fformbot_name_to_colordiv_id($theName);

		$theReturn = "<li>";

		$theReturn .= fformbot_input_label($theName, $this->GetRequiredFlag(), $theLabel.$theMaxText);

		$theReturn .= "<input ".fformbot_input_id($theName)." type=\"file\"/>$theMaxScript</li>";  

		return $theReturn;
	}

	function GetFilesListName()
	{
		return $this->GetName()."files_list";
	}
	
	function GetMaxElements()
	{
		return 1;
	}
}

class fformbot_Field extends fformbot_Input
{
	
}

class fformbot_Thanks extends fformbot_Command
{
	function GetThankYouMessage()
	{
		$theReturn = $this->sGetForDisplay($this->TagIncludes("text"));
		
		if(!$theReturn)
		{
			$theReturn = fformbot_text("__thanks");
		}
		
		return $theReturn;
	}

	function GetEmailTextAsLine()
	{
		return null;
	}
	
	function GetHtml()
	{
		return null;
	}
	
	function GetError()
	{
		return null;
	}
}

class fformbot_Mailto extends fformbot_Command
{
	function GetMailToEmailArray()
	{
		$theEmailList = $this->TagIncludes("text");
	
		$theEmailArray = explode(",", $theEmailList);
	
		if(!count($theEmailArray)) 
		{
			throw new Exception("You have to set up some emails in your email array.");
		}

		$theReturn = array_unique($theEmailArray);
		
		return $theReturn;
	}

	function GetEmailTextAsLine()
	{
		return null;
	}
	
	function GetHtml()
	{
		return null;
	}
	
	function GetError()
	{
		return null;
	}
}










?>