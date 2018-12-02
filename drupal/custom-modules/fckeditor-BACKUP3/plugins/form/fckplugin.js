/*
 * File Name: fckplugin.js
 * Plugin to launch the Insert Code dialog in FCKeditor
 */

// Register the related command.
FCKCommands.RegisterCommand( 'form', new FCKDialogCommand( 'form', FCKLang.InsertHtmlCode, FCKPlugins.Items['form'].Path + 'fck_insertHtmlCode.html', 415, 300 ) ) ;

// Create the "insertHtmlCode" toolbar button.
var oinsertHtmlCodeItem = new FCKToolbarButton( 
	'form', //drupalbreak
	FCKLang.InsertHtmlCode, // break 
	FCKLang.InsertHtmlCode, // null
	FCK_TOOLBARITEM_ICONTEXT, // fck_toolbaritem_icontext
	true, 
	true) ; 

	// the next to last "true" makes the name (video) visible next to the button, which i think will make users more likely to understand what the command is

oinsertHtmlCodeItem.IconPath = FCKPlugins.Items['form'].Path + 'insertHtmlCode.gif' ;

FCKToolbarItems.RegisterItem( 'form', oinsertHtmlCodeItem ) ;

