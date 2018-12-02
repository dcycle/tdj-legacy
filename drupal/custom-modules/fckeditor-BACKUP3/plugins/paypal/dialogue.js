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

function CheckText(
	theTextLANG,
	theTextTRANS_TYPE, 
	theTextPAYPAL_EMAIL, 
	theTextTRANSACTION_AMOUNT_TYPE, 
	theTextTRANSACTION_AMOUNT, 
	theTextITEM_TITLE, 
	theTextSELECTED_CURRENCY, 
	theTextOTHER_CURRENCY) {
	
	if(CheckTextTRANS_TYPE(theTextTRANS_TYPE)) {
		
		return CheckTextTRANS_TYPE(theTextTRANS_TYPE); 
		
	}
	if(CheckTextPAYPAL_EMAIL(theTextPAYPAL_EMAIL)) {
		
		return CheckTextPAYPAL_EMAIL(theTextPAYPAL_EMAIL); 
		
	}
	if(CheckTextTRANSACTION_AMOUNT_TYPE(theTextTRANSACTION_AMOUNT_TYPE)) {
		
		return CheckTextTRANSACTION_AMOUNT_TYPE(theTextTRANSACTION_AMOUNT_TYPE); 
		
	}
	if(CheckTextTRANSACTION_AMOUNT(theTextTRANSACTION_AMOUNT)) {
		
		return CheckTextTRANSACTION_AMOUNT(theTextTRANSACTION_AMOUNT); 
		
	}
	if(CheckTextITEM_TITLE(theTextITEM_TITLE)) {
		
		return CheckTextITEM_TITLE(theTextITEM_TITLE); 
		
	}
	if(CheckTextSELECTED_CURRENCY(theTextSELECTED_CURRENCY)) {
		
		return CheckTextSELECTED_CURRENCY(theTextSELECTED_CURRENCY); 
		
	}
	if(CheckTextOTHER_CURRENCY(theTextOTHER_CURRENCY)) {
		
		return CheckTextOTHER_CURRENCY(theTextOTHER_CURRENCY); 
		
	}
}

function CheckTextTRANS_TYPE(theText)
{
	if(theText != 'donation' && theText != 'purchase' && theText != 'subscription') {
	
		return 'tTypeBad';

	} else {

		return false;
	}
}

function CheckTextPAYPAL_EMAIL(theText)
{
	if(!Validate_Email_Address(theText)) {
	
		return 'tAccountBad';

	} else {

		return false;
	}
}

function CheckTextTRANSACTION_AMOUNT_TYPE(theText)
{
	if(theText != 'userdef' && theText != 'fixed') {
	
		return 'tTransAmoutTypeBad';

	} else {

		return false;
	}
}

function CheckTextTRANSACTION_AMOUNT(theText)
{
	return false;
}

function CheckTextSELECTED_CURRENCY(theText)
{
	return false;
}

function CheckTextITEM_TITLE(theText)
{
	if(!theText) {
	
		return 'tTitleBad';

	} else {

		return false;
	}
}

function CheckTextOTHER_CURRENCY(theText)
{
	return false;
}

function GetReturnUrl() {

	return location.href;
}

function ParseText(
	theTextLANG,
	theTextTRANS_TYPE, 
	theTextPAYPAL_EMAIL, 
	theTextTRANSACTION_AMOUNT_TYPE, 
	theTextTRANSACTION_AMOUNT, 
	theTextITEM_TITLE, 
	theTextSELECTED_CURRENCY, 
	theTextOTHER_CURRENCY) {

	theTextCurrency = (theTextSELECTED_CURRENCY == 'other')?theTextOTHER_CURRENCY:theTextSELECTED_CURRENCY;

	switch(theTextLANG) {
		case 'fr':
			theTxt = 'Faites vos paiements avec PayPal - C\'est rapide, gratuit et sécuritaire!';
			thePPLangDir = 'fr_FR/FR';
			break;
		case 'en':
		default:
			theTxt = 'Make payments with PayPal - it\'s fast, free and secure!';
			thePPLangDir = 'en_US';
			break;
	}
	
	switch(theTextTRANS_TYPE) {
		case 'subscription':
			theCmd = '_xclick-subscriptions';
			theBn = 'PP-SubscriptionsBF';
			theSrc = 'https://www.paypal.com/'+thePPLangDir+'/i/btn/btn_subscribeCC_LG.gif';
			theMoreInputs = '<input type="hidden" name="p3" value="1"><input type="hidden" name="t3" value="M"><input type="hidden" name="src" value="1"><input type="hidden" name="sra" value="1">';
			break;
		case 'purchase':
			theCmd = '_xclick';
			theBn = 'PP-BuyNowBF';
			theSrc = 'https://www.paypal.com/'+thePPLangDir+'/i/btn/btn_buynowCC_LG.gif';
			break;
		case 'donation':
		default:
			theCmd = '_donations';
			theBn = 'PP-DonationsBF';
			theSrc = 'https://www.paypal.com/'+thePPLangDir+'/i/btn/btn_donateCC_LG.gif';
			break;
	}
	

	theAmountInput = theTextTRANSACTION_AMOUNT?'<input type="hidden" name="amount" value="'+theTextTRANSACTION_AMOUNT+'">':'';

	r = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">'+
	'<input type="hidden" name="cmd" value="'+theCmd+'">'+
	'<input type="hidden" name="business" value="'+theTextPAYPAL_EMAIL+'">'+
	'<input type="hidden" name="item_name" value="'+theTextITEM_TITLE+'">'+
	theAmountInput+
	'<input type="hidden" name="no_shipping" value="0">'+
	'<input type="hidden" name="no_note" value="1">'+
	'<input type="hidden" name="currency_code" value="'+theTextCurrency+'">'+
	'<input type="hidden" name="lc" value="CA">'+                                '<input type="hidden" name="bn" value="'+theBn+'">'+
	'<input type="image" src="'+theSrc+'" border="0" name="submit" alt="'+theTxt+'">'+
	'<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">'+
	'</form>';
	
	return r;
}
/* taken from http://scripts.franciscocharrua.com/validate-email-address.php */

function Validate_String(string, return_invalid_chars)
         {
         valid_chars = '1234567890-_.^~abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
         invalid_chars = '';
         
         if(string == null || string == '')
            return(true);
         
         //For every character on the string.   
         for(index = 0; index < string.length; index++)
            {
            char = string.substr(index, 1);                        
            
            //Is it a valid character?
            if(valid_chars.indexOf(char) == -1)
              {
              //If not, is it already on the list of invalid characters?
              if(invalid_chars.indexOf(char) == -1)
                {
                //If it's not, add it.
                if(invalid_chars == '')
                   invalid_chars += char;
                else
                   invalid_chars += ', ' + char;
                }
              }
            }                     
            
         //If the string does not contain invalid characters, the function will return true.
         //If it does, it will either return false or a list of the invalid characters used
         //in the string, depending on the value of the second parameter.
         if(return_invalid_chars == true && invalid_chars != '')
           {
           last_comma = invalid_chars.lastIndexOf(',');
           
           if(last_comma != -1)
              invalid_chars = invalid_chars.substr(0, $last_comma) + 
              ' and ' + invalid_chars.substr(last_comma + 1, invalid_chars.length);
                      
           return(invalid_chars);
           }
         else
           return(invalid_chars == ''); 
         }


function Validate_Email_Address(email_address)
         {
         
         var str = email_address;
         
		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    return false
		 }

 		 return true					
         }
         
 /// taken from http://www.smartwebby.com/DHTML/email_validation.asp
         
         
function isUrl(s) {
	var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
	return regexp.test(s);
}