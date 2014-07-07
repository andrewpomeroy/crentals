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
 <?php if ( (get_field('enable_video')) && ($workvideos[$videoid]) ) {
 	$workvideo = $workvideos[$videoid];
 	$workvideourl = $workvideo['work-video-upload']['url'];
 	$workvideotitle = $workvideo['video-title'];
 	$attachment = get_post( $workvideo['work-video-upload']['id'] );
 	$metadata = wp_get_attachment_metadata($workvideo['work-video-upload']['id']);
 	// echo '<p>Video URL: ';
 	// echo $workvideo['work-video-upload']['url'];
 	// echo '</p>';
 	// echo '<p>Video: </p>';
 	// echo '<pre>';
 	// echo print_r($workvideo);
 	// echo '</pre>';
 	// echo '<pre>';
 	// echo print_r(wp_get_attachment_metadata( $workvideo['work-video-upload']['id'] ) );
 	// echo '</pre>';
 	?>
 	<div id="project-video-wrapper">
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

		$(document).ready(function() {
			var videoHeight = <?php echo $metadata['height']; ?> + 32;
			var videoWidth = <?php echo $metadata['width']; ?>;
			var videoRatio = videoWidth / videoHeight;
			var resizeVideoWrapper = function(videoHeight, videoWidth, videoRatio) {
				var wrapWidth = $('#project-video-wrapper').width();
				$('#project-video-wrapper').css('height', ((wrapWidth / videoRatio) + 'px'));
				// debugger;
			}
			resizeVideoWrapper(videoHeight, videoWidth, videoRatio);
			$(window).on('resize', function() {
				resizeVideoWrapper(videoHeight, videoWidth, videoRatio);
			});

		});

		</script>
		<noscript>
			<p>You must enable Javascript to view this content.</p>
		</noscript>
	</div>
	
	<?php
 } ?>	