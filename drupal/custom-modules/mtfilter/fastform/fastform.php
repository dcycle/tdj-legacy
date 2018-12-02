<?

DEFINE('_VALID_MOS', 1);
require_once("fastformcreator_php5.php");

class FormTag extends Tag
{
	public function _Process_()
	{
		throw new Exception("In FormTag, _Process_, being abstract otherwise, must be implemented, but is not used. This should never be called");
	}

	public function Process()
	{
			// rather than overriding _Process_ which processes just one tag, we override Process, which processes the whole text. We need to do it all at once to be able to create the form.
	
			$theText = str_replace("{formulaire ", "{fastform ", $this->itsText);
			$theId = md5($theText);
			$theDisplayErrorFlag = true;
			$theSendErrorFlag = true;
			$theMailTo = "mustgetcorrectaddress@mediatribe.net";
			$theErrTo = "fff.drupal.error@mediatribe.net";
			$theAbsSysPath = "/must/find/correct/sys/path/";
			$theLiveUrl = "http://must.find.live.url";
	
		if($itsError)
		{	
			return $itsError."--".$this->itsText;
		}

		return fformbot_attempt_to_create_form($theText, $theId, $theDisplayErrorFlag, $theSendErrorFlag, $theMailTo, $theErrTo, $theAbsSysPath, $theLiveUrl);
	}
}

?>