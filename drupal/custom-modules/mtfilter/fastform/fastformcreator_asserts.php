<? 

function fformbot_assert_var($aVar, $aType, $aMessage = null, $aNullAllowedFlag = false)
{
	if($aNullAllowedFlag && $aVar === null)
	{
		return;
	}

	switch($aType)
	{
		case "livedir":
			fformbot_assert_true(fformbot_string_starts_with($aVar, "http://"), "($aVar) should start with http:// -- $aMessage");
			fformbot_assert_true(fformbot_string_ends_with($aVar, "/"), "($aVar) should end with / -- $aMessage");
			fformbot_assert_true(!fformbot_string_contains_substring($aVar, " "), "($aVar) should not contain a space -- $aMessage");
			break;
		case "absdir":
			fformbot_assert_true(fformbot_string_starts_with($aVar, "/"), "($aVar) should start with '/' -- $aMessage");
			fformbot_assert_true(fformbot_string_ends_with($aVar, "/"), "($aVar) should end with / -- $aMessage");
			break;
		case "email":
			fformbot_assert_true(fformbot_check_email_address($aVar), "($aVar) should be an email address -- $aMessage");
			break;
		case "array:email":
			fformbot_assert_true(is_array($aVar), "($aVar) should be an array -- $aMessage");
			foreach($aVar as $aValue)
			{	
				fformbot_assert_var($aValue, "email",  $aMessage);
			}
			break;
		default:
			throw new Exception("Tried to assert that a var ($aVar) is of an unknown type ($aType) -- $aMessage");
			break;
	}
}

function fformbot_assert_true($aExpression, $aMessage)
{
	if(!$aExpression)
	{
		throw new Exception("An expression was evaluated to false when we expected true. Message: $aMessage");
	}
}

?>