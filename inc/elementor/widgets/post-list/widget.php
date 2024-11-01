<?php
namespace Devise_Elementor\Widgets\PostList;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;

use WP_Query;


if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * Elementor widget for PostList.
 */
class Devise_PostList extends Widget_Base {

	/**
	 * Presets
	 * @access protected
	 * @var array $presets Array objects presets.
	 */

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		if ( ! defined( 'DEVISE_ELEMENTOR_WIDGET_POST_LIST_DIR' ) ) {
			define( 'DEVISE_ELEMENTOR_WIDGET_POST_LIST_DIR', rtrim( __DIR__, ' /\\' ) );
		}

		if ( ! defined( 'DEVISE_ELEMENTOR_WIDGET_POST_LIST_URL' ) ) {
			define( 'DEVISE_ELEMENTOR_WIDGET_POST_LIST_URL', rtrim( plugin_dir_url( __FILE__ ), ' /\\' ) );
		}
		wp_register_style( 'devise-post-list', DEVISE_ELEMENTOR_WIDGET_POST_LIST_URL . '/assets/css/devise-post-list.min.css', array(), null );
	}


	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'devise-post-list';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post List', 'web-devise-ebptw' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'web_devise_elements' ];
	}

	public function get_style_depends() {
		return [ 'devise-post-list' ];
	}

	/*Show reload button*/
	public function is_reload_preview_required() {
		return true;
	}

	/**
	 * Make options select post categories
	 * @access protected
	 * @return array
	 */
	protected function select_post_categories() {
		$out   = [ '0' => __( 'All', 'web-devise-ebptw' ) ];
		$terms = get_terms( [
			'taxonomy' => 'category',
			'hide_empty' => true,
		] );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return $out;
		}

		foreach ( (array) $terms as $term ) {
			if ( ! empty( $term->name ) ) {
				$out[ $term->slug ] = $term->name;
			}
		}

		return $out;
	}


	/**
	 * Register the widget controls.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'_content_settings',
			[
				'label' => __( 'Content', 'web-devise-ebptw' ),
			]
		);

		$this->add_control(
			'select_post_cat',
			[
				'label' => __( 'Select Post Category', 'web-devise-ebptw' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->select_post_categories(),
				'frontend_available' => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'items_per_page',
			[
				'label' => __( 'Items per page', 'web-devise-ebptw' ),
				'description' => __( 'Use - 1 to show all', 'web-devise-ebptw' ),
				'label_block' => false,
				'type' => Controls_Manager::NUMBER,
				'default' => 8,
				'min' => - 1,
				'max' => 100,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'thumbnail_position',
			[
				'label' => __( 'Thumbnail Position', 'web-devise-ebptw' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'   => __( 'Left', 'web-devise-ebptw' ),
					'right'  => __( 'Right', 'web-devise-ebptw' ),
				],
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Pagination', 'web-devise-ebptw' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'web-devise-ebptw' ),
				'label_off' => __( 'Off', 'web-devise-ebptw' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_author_avatar',
			[
				'label' => __( 'Show Author Avatar', 'web-devise-ebptw' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'web-devise-ebptw' ),
				'label_off' => __( 'Hide', 'web-devise-ebptw' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->end_controls_section();

		$this->add_styles_controls( $this );

	}

	/**
	 * Controls call
	 * @access public
	 */
	public function add_styles_controls( $control ) {

		$this->control = $control;

		/**
		 *  Post Title Styles
		 */
		$this->post_title_styles( $control );

		/**
		 *  Excerpt Styles
		 */
		$this->excerpt_styles( $control );

		/**
		 *  Author Styles
		 */
		$this->author_styles( $control );

		/**
		 *  Date Styles
		 */
		$this->date_styles( $control );

	}


	/**
	 * Post Title Styles
	 * @access protected
	 */
	protected function post_title_styles( $control ) {

		$control->start_controls_section(
			'post_title_style_section',
			[
				'label' => __( 'Post Title Style', 'web-devise-ebptw' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'web-devise-ebptw' ),
				'name' => 'post_title_typography',
				'selector' => '{{WRAPPER}} .post-list .entry-title',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => '20',
						],
					],
					'font_weight' => [
						'default' => '600',
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => '28',
						],
					]
				]
			]
		);

		$control->add_responsive_control(
			'post_title_color',
			[
				'label' => __( 'Color', 'web-devise-ebptw' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-list .entry-title' => 'color: {{VALUE}};',
				],
				'default' => '#0f172a',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Excerpt Styles
	 * @access protected
	 */
	protected function excerpt_styles( $control ) {

		$control->start_controls_section(
			'excerpt_style_section',
			[
				'label' => __( 'Excerpt Style', 'web-devise-ebptw' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'web-devise-ebptw' ),
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .post-list .post-list-link-excerpt',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => '16',
						],
					],
					'font_weight' => [
						'default' => '500',
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => '24',
						],
					]
				]
			]
		);

		$control->add_responsive_control(
			'excerpt_color',
			[
				'label' => __( 'Color', 'web-devise-ebptw' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-list .post-list-link-excerpt' => 'color: {{VALUE}};',
				],
				'default' => '#64748b',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Author Styles
	 * @access protected
	 */
	protected function author_styles( $control ) {

		$control->start_controls_section(
			'author_style_section',
			[
				'label' => __( 'Author Style', 'web-devise-ebptw' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'web-devise-ebptw' ),
				'name' => 'author_typography',
				'selector' => '{{WRAPPER}} .post-list .post-list-item-author-name a',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => '16',
						],
					],
					'font_weight' => [
						'default' => '500',
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => '20',
						],
					]
				]
			]
		);

		$control->add_responsive_control(
			'author_color',
			[
				'label' => __( 'Color', 'web-devise-ebptw' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-list .post-list-item-author-name a' => 'color: {{VALUE}};',
				],
				'default' => '#0f172a',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Date Styles
	 * @access protected
	 */
	protected function date_styles( $control ) {

		$control->start_controls_section(
			'date_style_section',
			[
				'label' => __( 'Date Style', 'web-devise-ebptw' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'web-devise-ebptw' ),
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .post-list .post-list-item-date .entry-date',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => '14',
						],
					],
					'font_weight' => [
						'default' => '300',
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => '20',
						],
					]
				]
			]
		);

		$control->add_responsive_control(
			'date_color',
			[
				'label' => __( 'Color', 'web-devise-ebptw' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-list .post-list-item-date .entry-date' => 'color: {{VALUE}};',
				],
				'default' => '#334155',
			]
		);
		$this->end_controls_section();
	}


	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render() {

		$settings = $this->get_settings_for_display();

		$pl_thumbnail = __DIR__ . '/templates/parts/pl-thumbnail.php';

		$this->add_render_attribute( 'post_wrapper', 'class', [ 'post-list clearfix row' ] );

		if ( in_array( "0", $settings['select_post_cat'] ) ) {
			$cat = '';
		} elseif ( ! empty ( $settings['select_post_cat'] ) ) {
			$cat = implode( ',', $settings['select_post_cat'] );
		} else { ?>
			<div class="post-not-found">
				<?php echo esc_attr__( 'Please select post categories', 'web-devise-ebptw' ); ?>
			</div>
			<?php return;
		}

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$posts = new WP_Query(array(
			'post_type' => 'post',
			'category_name' => $cat,
			'post_status' => 'publish',
			'posts_per_page' => $settings['items_per_page'],
			'paged' => $paged,
		));

		$next_page = 0;

		if ( $posts->have_posts() ) {

			$preset_path = __DIR__ . '/templates/output-post-list.php'; ?>

			<div <?php echo $this->get_render_attribute_string( 'post_wrapper' ); ?>>
			<?php
			while ( $posts->have_posts() ) {
				$posts->the_post();
					if ( ! empty( $preset_path ) && file_exists( $preset_path ) ) {
						include $preset_path;
					}
			}
			?>

			<?php if ( 'yes' === ( $settings['show_pagination'] ) ) : ?>
				<div class="devise-pagination">
				<?php
				$big = 999999999;
					echo paginate_links( array(
						'base'    => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
						'format'  => '?paged = %#%',
						'current' => max( 1, get_query_var( 'paged' ) ),
						'total'   => $posts->max_num_pages,
					) );
				?>
				</div>
			<?php endif; ?> 

			<?php } else { ?>
				<div class="post-not-found">
					<?php echo esc_attr__( 'No posts found', 'web-devise-ebptw' ); ?>
				</div>
			<?php }	?>
			</div>
		<?php
		wp_reset_postdata();
	}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Devise_PostList() );
