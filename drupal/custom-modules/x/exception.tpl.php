<?php
// $Id: poll-results.tpl.php,v 1.2 2007/08/07 08:39:35 goba Exp $

/**
 * @file poll-results-block.tpl.php
 * Display the poll results in a block.
 *
 * Variables available:
 * - $exception_object: The Exception Object.
 *
 * @see template_preprocess_poll_results()
 */
?>
<div class="poll">
  <?php
	try {

		if($exception_object instanceof Exception) {
		  $e = $exception_object;
		} else { 
          $e = $exception_object->GetException();
		}

		$up = $up?$up:'index_images/up_arrow.gif';
		$down = $down?$down:'index_images/down_arrow.gif';

		$theTraceArray = explode("#", $e->getTraceAsString());
	
		echo '<div style="background-color:red;padding:20px;font-weight:bold;font-size:20px;font-family:sans-serif;color:white">';
		echo "<p>fatal error: </p><p>".$e->getMessage()."</p>";
		echo '</div>';
	
		echo '<table width="100%" border="2" cellspacing="0" cellpadding="0">';
	
	
		foreach($theTraceArray as $theTraceElement)
		{
			$theTraceLineArray = explode(": ", $theTraceElement);
		
			if(is_a($e, "SpException"))
			{
				$theMethodNameWithoutParamsArray = explode("(", $theTraceLineArray[1]);
				$theMethodNameWithoutParams = $theMethodNameWithoutParamsArray[0];
				
				$theParams = $theMethodNameWithoutParamsArray[1];
					// needed to display trace for recursive calls.
			
				$theMeasuresTakenArray = $e->retrieveInfoAsArray($theMethodNameWithoutParams,
				$theParams);
				$theTraceInfoArray = $e->retrieveTraceInfoAsArray($theMethodNameWithoutParams, $theParams);
				
				$theMeasuresTaken = "<span style=\"color:#777;font-size:12\">Corrective measures for $theMethodNameWithoutParams were:</span><br/>".implode(", ", $theMeasuresTakenArray);

			}

			echo '<tr>';
			echo '<td valign="top"><img src="' . $up . '"/></td>';
			echo '<td valign="top"><strong><span style="color:red">'.$theTraceLineArray[1].'</span></strong><br/><span style="color:#777;font-size:12">'.$theTraceLineArray[0].'</span>';
			if(is_a($e, "SpException"))
			{
				echo "<br/>".implode(", ", $theTraceInfoArray);
			}
			echo'</td>';
				echo '<td valign="top"><img src="' . $up . '"/></td>';
				echo '<td valign="top"><img src="' . $down . '"/></td>';
				echo '<td valign="top">'.$theMeasuresTaken.'</td>';
				echo '<td valign="top"><img src="' . $down . '"/></td>';
			echo '</tr>';

		}	
	
		echo '</table>';
	
	} catch(Exception $e) {

		echo "AN ERROR OCCURED WHILE TRYING TO DISPLAY AN ERROR --- " . $e->getMessage() . "   ---  " . $e->getTraceAsString;   
	}
  ?>
</div>
