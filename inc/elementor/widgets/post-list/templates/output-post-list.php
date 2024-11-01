<?php
if ( ! defined( 'ABSPATH' ) ) exit;


$devise_classes = array();

if ( ! has_post_thumbnail() ) {
	$devise_classes[] = 'no-image';
}

$category = get_the_category(); 
$thumbnail_position = $settings['thumbnail_position'];

?>
<div class="col-sm-12 post-list-item-container" >
	<article id="post-<?php the_ID(); ?>" <?php post_class( $devise_classes ); ?>>
		<div class="post-list-<?php echo esc_attr( $thumbnail_position ); ?>-item">

			<?php if ( $thumbnail_position === 'right' && file_exists( $pl_thumbnail ) ) : include $pl_thumbnail; endif; ?>

			<div class="post-list-item-content post-excerpt">
				<div class="post-list-item-header">
					<?php if ( ! empty( $category ) ) : ?>
					<div class="post-list-category">
						<a href="<?php echo esc_html( get_category_link( $category[0]->cat_ID ) ); ?>">
							<span>
								<?php echo esc_html( $category[0]->cat_name ); ?>
							</span>
						</a>
					</div>
					<?php endif; ?>
					<a href="<?php the_permalink(); ?>" class="post-list-link">
						<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
					</a>              
					<p class="post-list-link-excerpt">
						<?php echo wp_trim_words( get_the_content(), 24, '' ); ?>
					</p>
				</div>

				<div class="post-list-item-footer">
					<div class="post-list-item-footer-inner">
						<?php if ( 'yes' === ( $settings['show_author_avatar'] ) ) : ?>
							<div class="post-list-item-author">
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
									<?php echo '<img class="author-img" src="' . esc_url( get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => 40 ) ) ) . '" alt=" ' . get_the_title() . ' " />'; ?>
								</a>
							</div>
						<?php endif; ?>
						<div class="post-list-item-meta">
							<p class="post-list-item-author-name">
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
									<?php echo esc_html( get_the_author() ); ?>
								</a>
							</p>
							<div class="post-list-item-date">
								<?php
								$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
								echo $time_string = sprintf( $time_string, esc_attr( get_the_date( DATE_W3C ) ), esc_html( get_the_date() ) );
								?>
							</div>
						</div>
					</div>
				</div>
			</div>  

			<?php if ( $thumbnail_position === 'left' && file_exists( $pl_thumbnail ) ) : include $pl_thumbnail; endif; ?>     

		</div>
	</article><!-- #post-<?php the_ID(); ?> -->
</div>
