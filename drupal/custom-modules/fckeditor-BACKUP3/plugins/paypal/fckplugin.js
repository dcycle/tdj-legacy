/*
 * File Name: fckplugin.js
 * Plugin to launch the Insert Code dialog in FCKeditor
 */

// Register the related command.
FCKCommands.RegisterCommand( 'paypal', new FCKDialogCommand( 'paypal', FCKLang.InsertHtmlCode, FCKPlugins.Items['paypal'].Path + 'fck_insertHtmlCode.html', 415, 300 ) ) ;

// Create the "insertHtmlCode" toolbar button.
var oinsertHtmlCodeItemP = new FCKToolbarButton( 
	'paypal', //drupalbreak
	FCKLang.InsertHtmlCode, // break 
	FCKLang.InsertHtmlCode, // null
	FCK_TOOLBARITEM_ICONTEXT, // fck_toolbaritem_icontext
	true, 
	true) ; 

	// the next to last "true" makes the name (video) visible next to the button, which i think will make users more likely to understand what the command is

oinsertHtmlCodeItem.IconPath = FCKPlugins.Items['paypal'].Path + 'insertHtmlCode.gif' ;

FCKToolbarItems.RegisterItem( 'paypal', oinsertHtmlCodeItemP ) ;

