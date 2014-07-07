<?php
$workvideos = get_field('work-videos-files');
$videoid = 0;
if (isset($_GET['videoid'])) {
	$videoid = $_GET['videoid'];
}
if (( $workvideos ) && (count($workvideos) > 1)) { ?>

 <div class="work-videos-list">
 	<?php 
	$videocount = 0;
		?>
		<h3 id="videos_list_link">Videos (<?php echo count($workvideos); ?>)</h3>
		<ol id="project-videos" class="c">
	<?php foreach ($workvideos as $workvideo ) { ?>
	
		<?php if ( $videocount == $videoid ) {
		echo '<li class="c current">';
		} 
		else echo '<li class="c">'
		?>

			<?php echo '<a href="' . get_permalink() . '?videoid=' . $videocount . '">' . $workvideo['video-title'] ?>
	
			<?php echo '<span class="runtime">TRT: ' . $workvideo['runtime'] . '</a>' ?>
	
			<?php $videocount++;
		echo '</li>';
	}
	echo '</ol>';
	?>
</div>
	<?php 
	} 
	?>