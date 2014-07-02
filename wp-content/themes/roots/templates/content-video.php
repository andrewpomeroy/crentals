<?php // create $workvideos array ?>
 	<?php
 	$workvideos = get_field('work-videos-files');
 	// $pretty = print_r($workvideos);
 	// echo '<pre>'.json_decode($pretty).'</pre>';
 	?>	

<?php // Pull VideoID from URL arg, set to 0 by default ?>
	<?php
	$videoid = 0;
	if (isset($_GET['videoid'])) {
		$videoid = $_GET['videoid'];
	}
	?>
 	
<div id="content" class="c">
	<div id="project-wrapper" class="c">

<?php // Grab actual video URL ?>
 <?php if ( $workvideos[$videoid] ) {
 	echo '<p>Video URL: ';
 	$workvideo = $workvideos[$videoid];
 	echo $workvideo['work-video-upload']['url'];
 	$workvideourl = $workvideo['work-video-upload']['url'];
 	$workvideotitle = $workvideo['video-title'];
 	echo '</p>';
 	$customheight = get_field('customheight');
 	?>
 	<div id="project-video-wrapper"<?php if ($customheight) { echo 'style="height:'. ( (int) $customheight + 16 ).'px"';} ?>>
		<script type="text/javascript">
		// <![CDATA[
			
			// create the qtobject and write it to the page, this includes plugin detection
			// be sure to add 15px to the height to allow for the controls
			<?php echo 'var myQTObject = new QTObject("' . $workvideourl . '", "' . $workvideotitle . '", "100%", "100%");'; ?>
			myQTObject.addParam("autostart", "true");
			myQTObject.addParam("scale", "aspect");
			myQTObject.addParam("loop", "false");
			myQTObject.addParam("kioskmode", "true");
			myQTObject.addParam("ShowDisplay", "false");
			myQTObject.addParam("ShowControls", "true");
			myQTObject.addParam("WMODE", "transparent");
			myQTObject.write();
			
		// ]]>
		</script>
		<noscript>
			<p>You must enable Javascript to view this content.</p>
		</noscript>
	</div>
	
	<?php
 } ?>	