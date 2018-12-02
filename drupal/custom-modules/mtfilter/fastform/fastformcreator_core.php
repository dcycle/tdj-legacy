<? 

function fformbot_strip_one_char_at_extremities($aString)
{
	/* utility allowing us to remove {} from the beginning and end of a string. This function doesn't actually check if the curly quotes are present, just removes one char from the beginning and one from the end */
	
	return (substr($aString, 1, strlen($aString)-2));
}

function fformbot_get_this_script($aQueryStringFlag = true, $aOmitScriptFlag = false)
{
	if($aQueryStringFlag && $aOmitScriptFlag)
	{
		throw new Exception("cannot show the query string and omit the script");
	}

	$theQueryStringFlag = $aQueryStringFlag;

	/* Utility function returning the URL of the current script */ 

		// ** the following code taken from 
		// **http://www.weberdev.com/get_example-3662.html
		/***/	if($theQueryStringFlag && $_SERVER['QUERY_STRING']) { 
		/***/	$theReturn = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; 
		/***/	} else { 
		/***/	$theReturn = $_SERVER['PHP_SELF']; 
		/***/	} 
		
	if($aOmitScriptFlag)
	{
		$theReturn = fformbot_strip_from_end_everything_excluding_last("/", $theReturn);
	}
	
	return $theReturn;
}


function fformbot_string_contains_substring($aString, $aSubstring)
{
	return preg_match('/'.$aSubstring.'/', $aString);
}

function fformbot_string_starts_with($aString, $aSubstring)
{
	return (substr($aString, 0, strlen($aSubstring)) == $aSubstring);
}

function fformbot_string_ends_with($aString, $aSubstring)
{
	if(substr($aString, strlen($aString) - strlen($aSubstring), strlen($aSubstring)) == $aSubstring)
		return true;
	else
		return false;
}

function fformbot_input_id($aName)
{
	$theName = fformbot_alphanum($aName);

	return 'name="'.$theName.'" id="'.$theName.'"';
}

function
fformbot_flat_list_to_array($aFlatList)
{
	if(is_array($aFlatList))
	{
		throw new Exception("fformbot_flat_list_to_array() cannot be passed an array, but a flat list (;-delimited)");
	}

	/* utility which takes a ;-separated list and transforms it into an array of unique elements */
	
	return array_unique(explode(";", $aFlatList));
}

function fformbot_new_infoaray($aString)
{
	// return an array of arrays, eg(array(a, b), array(c,d)) for anything... a="b" c="d"...anything;

	$theReturn = array();
	$theString = $aString;
	
	while($theParamToValue = fformbot_find_once_in("/[^ =]\"[^\"]\"/", $theString))
	{	
		array_push($theReturn, fformbot_parse_param_to_value($theParamToValue));
		
		fformbot_remove_from_string($theParamToValue, $theString);
	}
	
	return $theReturn;
}

function fformbot_find_once_in($aRegex, $aHaystack)
{
	/* The idea is to find a pattern in the haystack once, and return it. */
	
	$theText = $aHaystack;
	$theRegex = $aRegex;

	$theMatch = array();
	
	preg_match($theRegex, $theText, $theMatch);

	return $theMatch[0];
}

class fformbot_Object
{
	public $itsVars;
	
	public function Init()
	{
		$this->itsVars = array();
	}

	function OneCall($aFunctionName)
	{
		$theParam = "____called".$aFunctionName;
	
		$TRACE .= " / We must make sure that the function ($aFunctionName) hasn't been called before. ";
	
		spexception_assert(!$aObj->GetVar($theParam), "You have attempted to call ($aFunctionName) a second time, which is illegal.");
	
		$this->SetVar($theParam, true);
	}

	function GetVar($aVar, $aExpectedTypeTestFunction = null)
	{
		$theVars = $this->itsVars;

		$theReturn = $theVars[$aVar];

		if($aExpectedTypeTestFunction) 
		{
			spexception_assert($aExpectedTypeTestFunction($theReturn), "We expected the return value, ($theReturn), to respond positively to the function ($aExpectedTypeTestFunction). It doesn't. Note that there are ".count($theVars)." variables defined for this object ($aObject): ".implode(", ", $theVars));
		}

		return $theReturn;
	}
	
	function SetVar($aVar, $aValue)
	{
		$this->itsVars[$aVar] = $aValue;
	}
	
	function __toString()
	{
		return "FastFormCreator Object"; // doesn't work-> of type ".$aObj->get_class();
	}
	
	function GetNumVars()
	{
		return count($this->itsVars);
	}
	
}

function fformbot_check_email_address($email) {
  // First, we check that there's one @ symbol, and that the lengths are right
  if (!ereg("[^@]{1,64}@[^@]{1,255}", $email)) {
    // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
     if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
      return false;
    }
  }  
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}
	

?>