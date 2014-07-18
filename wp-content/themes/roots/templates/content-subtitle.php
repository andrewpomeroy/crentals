<?php if (get_field('has_subtitle') && (get_field('custom_subtitle'))) { ?>
<h3 class="title subtitle">
	<?php echo get_field('custom_subtitle') ?>
</h3>
<?php } ?>