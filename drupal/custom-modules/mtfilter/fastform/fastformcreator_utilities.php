<? 

function fformbot_cleaned_tag($aData)
{
	$theReturn = fformbot_remove_non_alphanum_at_extermities($aData);
	$theReturn = fformbot_whitespace_to_space($theReturn);
	
	return $theReturn;
}

function fformbot_strip_from_end_everything_excluding_last($aNeedle, $aHaystack)
{
	// must find the last occurence of $aHaystack in $aNeedle...
	// where is it?
		
	$theOccurrencePosition = strripos($aHaystack, $aNeedle);

	if($theOccurrencePosition === false) return $aHaystack;

	$theFirstPositionToDelete = $theOccurrencePosition + 1;

	return fformbot_strip_from_end_including_position($aHaystack, $theFirstPositionToDelete);
}

function fformbot_strip_from_end_including_position($aHaystack, $aFirstPosToDelete)
{
	$theSubstringLength = $aFirstPosToDelete;
	
	return substr($aHaystack, 0, $theSubstringLength);
}


function fformbot_remove_non_alphanum_at_extermities($aData)
{
	$thePattern = "/^[^a-zA-Z0-9-_]*/";

	$theReturn = preg_replace($thePattern, "", $aData);
	
	$thePattern = "/[^a-zA-Z0-9-_]*$/";
	
	$theReturn = preg_replace($thePattern, "", $theReturn);

	return $theReturn;
}

function fformbot_whitespace_to_space($aData)
{
	$thePattern = '/\s\s+/';
	$theReplacement = ' ';
	$theReturn = $aData;

	$theReturn = preg_replace($thePattern, $theReplacement, $theReturn);

	return $theReturn;
}

function fformbot_infostring_includes($aTag, $aParam, $aValue = null)
{
	$theTag = ereg_replace("&quot;","\"",$aTag);

	$theParam = $aParam;

	$TRACE = "About to attempt to find the value for ($theParam) in ($theTag)";
	
	if(!($theAmountFound = preg_match_all("/$theParam=\"([^\"]*)\"/", $theTag, $theMatches)))
	{	
		$TRACE .= " / None ($theAmountFound) found. Matches contains (".count($theMatches).") items";

			// bizarrely, even if no matches found, matches contains some items...

		$theReturn = false;
	}		
	
	else foreach($theMatches as $theMatch)
	{
		$theValue = $theMatch[0];

		$TRACE .= " / Found ($theAmountFound). First is (".$theMatch[0]."). Let's return (".$theValue.").";

		if((!$aValue) || ($theValue == $aValue))
		{
			$theReturn = $theValue; 
				// the match [0] being param="value", the match [1]is only the value, which is what we want. We will use 0 even though as i understand the documentation we need to use 1. Works only with 0...
		}
	}

	return $theReturn;
}

?>