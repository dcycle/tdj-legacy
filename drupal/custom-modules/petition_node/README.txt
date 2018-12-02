/* $Id: README.txt,v 1.1.2.7 2009/05/23 10:19:18 gauvain Exp $ */

Petition module the creation of petition nodes to collect signatures to a cause.
The signers have to confirm their signatures by clicking to a link included in an email they automatically received upon signing the petition.
The Signatures can be exported as a .csv file.
If you have the ip2cc and countries_api modules installed, benefit from extended functionalities such as restricting the petition to a geographic area, say you want to create a petition that only should concern citizens living in a given country.


Themer
-------
You can create a node-petition.tpl.php file to your theme directory.
The default $content variable contains both the Petition body and the petition form.
However, you may wish distinguish between the body and the from.

If so, please:
1/ remove the $content variable from the node-petition.tpl.php file.
2/ replace it with the 2 following variables : $petition_body and $petition_form

You can use  the following other variables:
 - $signature_count => refers to the amount of signatures
 - $activation => is the signature form active? 1 == yes, 0 == no
 - $inactivation_message => message to display instead of the signature form if it is inactivated 
 - $limit_date => unix timestamp refering to the date untill which the petition campaign is running. This up to the themer to theme it.
 - $signature_goal => Amount of sigantures aimed to be reached.
 - $campaign_settings => campaign settings: 1 == Time based, 2 == Signature goal based

Theming the signature page
--------------------------
On each petition node, you can choose the signature display.
The Petition Node project includes a couple of possible dispalys.

You can create your own signature display by creating your own module
1/ create in the module directory a directory called <MySignatureDisplay> with <MySignatureDisplay>.info and a <MySignatureDisplay>.module files.
You can copy the signaturedisplay.info and signaturedispaly.module files and change the names with <MySignatureDisplay>

2/ In the <MySignatureDisplay>.module, write the following functions
a) the hook_signaturedisplay_info() with the following form:

function <MySignatureDisplay>_signaturedisplay_info() {

 $signaturedisplay_info['OnlyNamesNotSortable'] = array(
   'name' => t('The Name / description of my signature dispaly), // The Name dislayed on the petition form, when choosing the layout
   'default' => FALSE, // If True, the petition nodes created on the website will have this display mode as the default
   'callback' => 'MySignatureDisplayCallback' // Name of the function that returns the signatures
   );

  return $signaturedisplay_info;
}

b) The callback function to render the signatures.

function MySignatureDisplayCallback($node) {
 // write your function here. You can inspire yourself with the existing functions.
 // It can be smart to call a theme('MySignatureDisplayCallback', $node) and create a theme_MySignatureDisplayCallback function instead.
 // In this case don't forget to include a hook_theme

}

Installation
------------

Copy petition module to your module directory and then enable on the admin
modules page.  You can change the general petition settings on admin/settings/petition.

It is strongly recommended you also install at least the countries_api module, which enable the selection of country from a drop down list in the signature form.

Author
------
Gauvain
gauvain@kth.se