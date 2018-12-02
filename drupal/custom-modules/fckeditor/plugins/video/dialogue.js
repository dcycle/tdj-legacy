/*
 * File Name: dialogue.js
 * 	Scripts for the fck_insertCode.html page.
 * 
 * File Authors:
 * 		Michel Staelens (michel.staelens@wanadoo.fr)
 * 		Bernadette Cierzniak
 * 		Abdul-Aziz Al-Oraij (top7up@hotmail.com)
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 *      Nico Piponides
 */

function CheckText(aText, aField)
{
	if(aText.length <= 0) {
	
		return 'You haven\'t entered any text';

	} else if(GetTextType(aText, aField) == null) {

		return 'You have entered an unknown type';

	} else {

		return false;
	}
}

function ParseText(aText, aField)
{
	theType = GetTextType(aText);
	
	switch(theType) {
	
		case 'GoogleVideo':
			return MakeGoogleVideo(aText)
			break;
		case 'YouTube':
			return MakeYouTubeVideo(aText)
			break;
		case 'YouTubeHttp':
			return MakeYouTubeVideo(ExtractYouTubeFromHttp(aText))
			break;
		case 'GoogleVideoHttp':
			return MakeGoogleVideo(ExtractGoogleVideoFromHttp(aText))
			break;
		case 'Unknown':
		case 'ObjectOrEmbed':
		default:
			return aText;
			break;
	}
}

function GetTextType(aText, aField)
{
	/* check what type is the text */
	
	if(false) {
	
	} else if(((aText.match(/^-[0-9]*$/) ) || (aText.match(/^[0-9]*$/) )) && aText.length > 10 && aText.length < 25) {
		
		return 'GoogleVideo';

	} else if((aText.match(/^[a-zA-Z0-9_]*$/) ) && aText.length > 10 && aText.length < 25) {
	
		return 'YouTube';	
		
	} else if(aText.match(/^http:\/\/.*\.youtube\.com\//) ) {

		return 'UnknownType';	

	} else if(aText.match(/^http:\/\/.*\.video\.google\.com\//) ) {

		return 'UnknownType';	

	} else if(aText.match(/^<object>.*<\/object>$/)  || aText.match(/^<embed>.*<\/embed>$/) ) {

		return 'ObjectOrEmbed';	
	
	} else if(aText.length > 0) {

		return 'UnknownType';	
	
	} else {

		return null;	
	
	}
}

function MakeYouTubeVideo(aText) {

//	return '<object width="425" height="355"><param name="movie" value="http://www.youtube.com/v/'+aText+'&hl=en"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/'+aText+'&hl=en" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed></object>'

//not compatible with FCK (just does not appear ?)

	return '{youtube id="'+aText+'"}';

}

function ExtractYouTubeFromHttp(aText) {

	return 'nyi';
}

function MakeGoogleVideo(aText) {

	return '<embed id="VideoPlayback" style="width:400px;height:326px" allowFullScreen="true" flashvars="fs=true" src="http://video.google.com/googleplayer.swf?docid='+aText+'&hl=en" type="application/x-shockwave-flash"> </embed>';
}

function ExtractGoogleVideoFromHttp(aText) {

	return 'nyi';
}
