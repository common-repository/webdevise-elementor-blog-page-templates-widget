<div class="post-list-image-container">
	<a href="<?php the_permalink(); ?>">
	<?php
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( 'blog-list' );
	} else {
		echo '<div class="image-placeholder"><h4>No Image</h4></div>';
	}
	?>
	</a>
</div>
