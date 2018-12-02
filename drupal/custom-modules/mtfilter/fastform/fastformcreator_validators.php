<? 
require_once("fastformcreator_core.php");

abstract class fformbot_Validator extends fformbot_Object
{
	private $itsInput;
	
	public function GetSubmitCode()
	{
		return $this->GetJavascript(true);
	}
	
	public function GetOnTheFlyCode()
	{
		return $this->GetJavascript(false);
	}
	
	abstract public function GetJavascript($aOnSubmitFlag);
	
	abstract public function ErrorFor($aValue);
	
	public function fformbot_Validator($aInput)
	{
		$this->itsInput = $aInput;
	}
	
	static function sNewValidatorFromName($aString, $aInput)
	{
		$thePossibleValidatorName = $aString;

		/* called by fformbot_add_validators_to_code() for each validator (aValidator) is one validator with one of the values below. See the comment
		inside fformbot_create_input() for more about validators . name is the name of the Input (input element) to which this validation pertains. The onSubmitFlag is set only of the code is for submit, in which case alerts are produced. Otherwise, in the course of filling a form, alerts would be unwieldly, so we just change the color of the div around the input element. */

		// return must in the format if(x) { }else 

		switch($thePossibleValidatorName)
		{
			case "required":
				return new fformbot_Validator_Required($aInput);
			case "several":
				break;
			case "email":
				return new fformbot_Validator_Email($aInput);
			case "name":
				break;
			case "country":
				break;
			case "telephone":
				break;
			default:
				case "min_x":;
				case "max_x":; 
		}
	}
	
	function GetForm()
	{
		$theInput = $this->itsInput;
		
		return $theInput->GetForm();
	}
	
	function GetFormName()
	{
		$theForm = $this->GetForm();
		
		return $theForm->GetName();
	}
	
	function GetInputName()
	{
		$theInput = $this->itsInput;
		
		return $theInput->GetName();
	}
	
	function GetInputLabel()
	{
		$theInput = $this->itsInput;
		
		return $theInput->GetLabel();
	}
	
	function DomValue()
	{
		$theFormName = $this->GetFormName();
		$theInputName = $this->GetInputName();
	
		return 'document.'.$theFormName.'.'.$theInputName.'.value';
	}
	
	function AlertOrModifyColorCode($aMessage, $aOnSubmitFlag)
	{
		/* called by fformbot_get_code_for_validator(). The idea is that if we are submitting the form, an alert with a message is presented, otherwise, the unobtrusive changing of colors is used. */
	
		if($aOnSubmitFlag) 
		{
			return "alert(\"".fformbot_text("__cannot_submit_form").": $aMessage\");";
		}
		else
		{	
			return $this->GetChangeColorValidationCode("red");
		}
	}

	function GetChangeColorValidationCode($aColor)
	{
		$theColorDivId = $this->NameToColorDivId();
		
		return "{ object = document.getElementById(\"$theColorDivId\"); object.style.color = \"$aColor\"; }";
	}

	function NameToColorDivId()
	{
		$theName  = $this->GetInputName();

		/* utility function which returns the internal id of the color scheme used by js to determine if an item is validated or not */
	
		return "colordiv".fformbot_alphanum($theName);
	}
	
	

}

class fformbot_Validator_Required extends fformbot_Validator
{
	public function ErrorFor($aValue)
	{
		if(!$aValue) return $this->ErrorMessage();

	}
	
	public function ErrorMessage()
	{
		return fformbot_text("__must_enter_sth_BEF").$this->GetInputLabel().fformbot_text("__must_enter_sth_AFT");
	}
	
	public function GetJavascript($aSubmitFlag)
	{
		return 'if ('.$this->DomValue().' == "") 
			{'.
				$this->AlertOrModifyColorCode($this->ErrorMessage(), $aSubmitFlag)
			.'} else ';

	}

}

class fformbot_Validator_Email extends fformbot_Validator
{
	public function ErrorFor($aValue)
	{
		if(!fformbot_check_email_address($aValue))
			return $this->ErrorMessage();
	}
	
	public function ErrorMessage()
	{
		return fformbot_text("__email_format_not_good");
	}
	
	public function GetJavascript($aSubmitFlag)
	{
		return 'if ('.$this->DomValue().'.search("@") == -1 && 
			'.$this->DomValue().' != "") 
			{'.
				$this->AlertOrModifyColorCode($this->ErrorMessage(), $aSubmitFlag)
			.'} else ';
	}
}

function fformbot_name_to_colordiv_id($aName)
{
	return "colordiv".fformbot_alphanum($aName);
}

?>