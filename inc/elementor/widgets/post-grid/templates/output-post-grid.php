<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$category = get_the_category();

?>

<div class="post-grid-item-container col-sm-12 col-md-6 col-lg-4">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="post-grid-item">
			<div class="post-grid-image-container">
				<a href="<?php the_permalink(); ?>">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'blog-grid' );
				} else {
					echo '<div class="image-placeholder"><h4>No Image</h4></div>';
				}
				?>
				</a>
			</div>
			<div class="post-grid-item-content">
				<div class="post-grid-item-header">
					<?php if ( ! empty( $category ) ) : ?>
					<div class="post-grid-category">
						<a href="<?php echo esc_html( get_category_link( $category[0]->cat_ID ) ); ?>">
							<span>
								<?php echo esc_html( $category[0]->cat_name ); ?>
							</span>
						</a>
					</div>
					<?php endif; ?>
					<a href="<?php the_permalink(); ?>" class="post-grid-link">
						<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
					</a>
				</div>
				<div class="post-grid-item-footer">
					<div class="post-grid-item-footer-inner">
						<?php if ( 'yes' === ( $settings['show_author_avatar'] ) ) : ?>
							<div class="post-grid-item-author">
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
									<?php echo '<img class="author-img" src="' . esc_url( get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => 40 ) ) ) . '" alt=" ' . get_the_title() . ' " />'; ?>
								</a>
							</div>
						<?php endif; ?>
						<div class="post-grid-item-meta">
							<p class="post-grid-item-author-name">
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
									<?php echo esc_html( get_the_author() ); ?>
								</a>
							</p>
							<div class="post-grid-item-date">
								<?php
									$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
									echo $time_string = sprintf( $time_string, esc_attr( get_the_date( DATE_W3C ) ), esc_html( get_the_date() ) );
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article><!-- #post-<?php the_ID(); ?> -->
</div>
