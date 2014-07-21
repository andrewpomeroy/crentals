<?php // create $mhfiles array ?>
<?php
 if (get_field('enable_files') && get_field('files')) {
	$mhfiles = get_field('files');
	$filecount = 0;
	?>
	<div class="work-files-list work-content-list">
		<h3 id="files_list_link" class="files-list-link content-list-link"><?php echo get_field('files_heading') ?> (<?php echo count($mhfiles); ?>)</h3>
		<ol id="project-files" class="project-files project-content" class="c">
			<?php foreach ($mhfiles as $mhfile ) { ?>
			<li class="c pdf">
		
				<?php // Grab actual file title, URL ?>
		 		<?php 
				 	$mhfileurl = $mhfile['file_upload']['url'];
				 	$mhfiletitle = $mhfile['file_title'];
				 	?>	
				 	

				<?php echo '<a href="'. $mhfileurl .'" target="_blank">' . $mhfiletitle ?>
			
				<span class="downloadPDFlink">Click to Download</span></a>
			
				<?php $filecount++; ?>
			</li>
			<?php 
			}
			?>
		</ol>
	</div>
	<?php
	} 

	?>