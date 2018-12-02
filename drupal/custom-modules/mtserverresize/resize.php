<?PHP
	// v. 110b101 (090107152615): files with a ' didn't work.

	// v. 110b100 (081211163730): return original image if gd image is not installed

	// v. 100b100 (081023153842): trim entry, init vars. 

    // v. a07: accept PNG

    // v. a06: accept PSD (code is here; does not work though...); allow to specify only width or height

    // v. a05: added str_replace(" ", "%20", ...) because an HTTP request cannot contain whitespace (for some reason this bug only affects http requests, not local requests...

    // v. a04: added TrueColor (see a04 in code) to prevent image from looking faded.
    
    // v. a03: use arguments (parameters) instead of hard-coded.. also change name since we are now dealing with anything. This project will become resize.php. 

$original_image = null;
$final_output_height = null;
$final_output_width = null;

    // a03: the original parameters are now gotten from the user
$original_image = trim($_GET["originalimage"]);
$final_output_height = trim($_GET["finalheight"]);
$final_output_width = trim($_GET["finalwidth"]);

ob_start();
phpinfo();
$data = ob_get_contents();
ob_end_clean();

if (!strstr($data, "GD Version")) {
  header( 'Location: ' . $original_image) ;
  die;
}

    // a06: accept different filetypes
function filename_ends_with($aSearchString, $aString)
{
    if(substr($aString, strlen($aString) - strlen($aSearchString), strlen($aSearchString)) == $aSearchString)
    
        return true;
    else
        return false;
}

$acceptedFileTypes = array("PNG", "png", "jpg", "jpeg", "JPG", "JPEG");

$filetype = null;

foreach($acceptedFileTypes as $type)
{
    if(filename_ends_with(".".$type, $original_image)) 
    {
        $filetype = $type;
    }
}

if(!$filetype)
{
    echo ("<p class=\"error\">le nom du document que vous avez fourni doit se terminer par ".implode(", ", $acceptedFileTypes)." (vous avez fourni ".$_FILES['pic']['name'].")</p>");
    exit();
}

    // psd does not work too well
if($filetype == "PSD" || $filetype == "psd")
{
    include("classPhpPsdReader.php");
    $original_image_as_gd_image_object = imagecreatefrompsd($original_image) ;
}
else if($filetype == "png" || $filetype == "PNG")
{
   $original_image_as_gd_image_object = imagecreatefrompng(str_replace('\\\'', '\'', str_replace(" ", "%20", $original_image)));
}
else
{
    $original_image_as_gd_image_object = imageCreateFromJPEG(str_replace('\\\'', '\'', str_replace(" ", "%20", $original_image)));

}    

    // a06: if height or width are not specified, assume we want orig size or proportional
    
    if(!$final_output_width && !$final_output_height)
    {
        $final_output_width = imagesx($original_image_as_gd_image_object);
        $final_output_height = imagesy($original_image_as_gd_image_object);
    }

    if(!$final_output_width && $final_output_height)
    {
        // get height ratio
        $orig_height = imagesy($original_image_as_gd_image_object);
        $orig_width = imagesx($original_image_as_gd_image_object);

        $final_output_width = $orig_width * $final_output_height / $orig_height;
    }
    
    if($final_output_width && !$final_output_height)
    {
        // get height ratio
        $orig_height = imagesy($original_image_as_gd_image_object);
        $orig_width = imagesx($original_image_as_gd_image_object);

        $final_output_height = $orig_height * $final_output_width / $orig_width;
    }
    
    // we want to resize the logo so it will fit in the layout, so we create a blank image that will fit.
$generated_image = imageCreateTrueColor($final_output_width, $final_output_height);

    // get an array with four items, the first two representing the size of the image.
$original_image_size = getImageSize(str_replace('\\\'', '\'', str_replace(" ", "%20", $original_image)));
//$original_image_as_gd_image_object_size[1] = 50; removed in a02
//$original_image_as_gd_image_object_size[0] = 50; removed in a02

    // copy the original image to the destination image. Both images will be treated from their (0,0) coordinates (hence the first four numerical params). the next two params correspond to the height and width of the destination image. The last arguments correspond to the height and width of the source image.
imageCopyResized($generated_image, $original_image_as_gd_image_object, 0, 0, 0, 0, $final_output_width,$final_output_height,  $original_image_size[0], $original_image_size[1]);

    // ???
header('Content-type: image/png');

    // display the image??
imagePNG($generated_image);

    // get rid of used memory.
imageDestroy($generated_image);
imageDestroy($original_image_as_gd_image_object);

?>