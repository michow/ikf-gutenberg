<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheet on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		$src    = 'style' . $suffix . '.css';

		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( $src ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
		wp_style_add_data(
			'twentytwentyfive-style',
			'path',
			get_parent_theme_file_path( $src )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

if ( ! function_exists( 'ikf_enqueue_assets' ) ) :
	/**
	 * Enqueues compiled SCSS and JS from the theme build (assets/css, assets/js).
	 *
	 * @since IKF 1.0
	 *
	 * @return void
	 */
	function ikf_enqueue_assets() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		$version = wp_get_theme()->get( 'Version' );

		$css_rel = 'assets/css/main' . $suffix . '.css';
		$css_path = get_theme_file_path( $css_rel );
		if ( file_exists( $css_path ) ) {
			wp_enqueue_style(
				'ikf-main',
				get_theme_file_uri( $css_rel ),
				array( 'twentytwentyfive-style' ),
				$version
			);
			wp_style_add_data( 'ikf-main', 'path', $css_path );
		}

		$js_rel = 'assets/js/main' . $suffix . '.js';
		$js_path = get_theme_file_path( $js_rel );
		if ( file_exists( $js_path ) ) {
			wp_enqueue_script(
				'ikf-main',
				get_theme_file_uri( $js_rel ),
				array(),
				$version,
				true
			);
		}
	}
endif;
add_action( 'wp_enqueue_scripts', 'ikf_enqueue_assets' );

if ( ! function_exists( 'ikf_enqueue_google_fonts' ) ) :
	/**
	 * Loads Playfair Display SC from Google Fonts on the front end and in the editor.
	 *
	 * @since IKF 1.0
	 *
	 * @return void
	 */
	function ikf_enqueue_google_fonts() {
		wp_enqueue_style(
			'ikf-playfair-display-sc',
			'https://fonts.googleapis.com/css2?family=Playfair+Display+SC:ital,wght@0,400;0,700;1,400;1,700&display=swap',
			array(),
			null
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'ikf_enqueue_google_fonts' );
add_action( 'enqueue_block_assets', 'ikf_enqueue_google_fonts' );

if ( ! function_exists( 'ikf_google_fonts_resource_hints' ) ) :
	/**
	 * Adds preconnect hints for Google Fonts.
	 *
	 * @since IKF 1.0
	 *
	 * @param array  $urls          URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed for.
	 * @return array
	 */
	function ikf_google_fonts_resource_hints( $urls, $relation_type ) {
		if ( 'preconnect' !== $relation_type ) {
			return $urls;
		}

		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);

		return $urls;
	}
endif;
add_filter( 'wp_resource_hints', 'ikf_google_fonts_resource_hints', 10, 2 );

if ( ! function_exists( 'ikf_filter_instructor_display_query' ) ) :
	/**
	 * Limits instructor Query Loops to posts with ACF instructor_display enabled.
	 *
	 * Note: query_loop_block_query_vars receives the post-template block, so parent
	 * Query block classNames are not available here — filter by post type instead.
	 *
	 * @since IKF 1.0
	 *
	 * @param array    $query Query vars for WP_Query.
	 * @param WP_Block $block The block instance (usually core/post-template).
	 * @return array
	 */
	function ikf_filter_instructor_display_query( $query, $block ) {
		$post_type = isset( $query['post_type'] ) ? $query['post_type'] : '';
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}
		if ( 'instructor' !== $post_type ) {
			return $query;
		}

		$meta_query   = isset( $query['meta_query'] ) && is_array( $query['meta_query'] ) ? $query['meta_query'] : array();
		$meta_query[] = array(
			'key'     => 'instructor_display',
			'value'   => '1',
			'compare' => '=',
		);
		$query['meta_query'] = $meta_query;

		return $query;
	}
endif;
add_filter( 'query_loop_block_query_vars', 'ikf_filter_instructor_display_query', 10, 2 );

if ( ! function_exists( 'ikf_filter_member_display_query' ) ) :
	/**
	 * Limits member Query Loops to posts with ACF member_display enabled.
	 *
	 * @since IKF 1.0
	 *
	 * @param array    $query Query vars for WP_Query.
	 * @param WP_Block $block The block instance (usually core/post-template).
	 * @return array
	 */
	function ikf_filter_member_display_query( $query, $block ) {
		$post_type = isset( $query['post_type'] ) ? $query['post_type'] : '';
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}
		if ( 'member' !== $post_type ) {
			return $query;
		}

		$meta_query   = isset( $query['meta_query'] ) && is_array( $query['meta_query'] ) ? $query['meta_query'] : array();
		$meta_query[] = array(
			'key'     => 'member_display',
			'value'   => '1',
			'compare' => '=',
		);
		$query['meta_query'] = $meta_query;

		return $query;
	}
endif;
add_filter( 'query_loop_block_query_vars', 'ikf_filter_member_display_query', 10, 2 );

if ( ! function_exists( 'ikf_register_instructor_meta_block' ) ) :
	/**
	 * Registers a block that outputs instructor ACF rank and position in Query Loops.
	 *
	 * @since IKF 1.0
	 *
	 * @return void
	 */
	function ikf_register_instructor_meta_block() {
		register_block_type(
			'ikf/instructor-meta',
			array(
				'api_version'     => 3,
				'title'           => __( 'Instructor Meta', 'ikf' ),
				'category'        => 'theme',
				'icon'            => 'id',
				'description'     => __( 'Displays instructor rank and position ACF fields.', 'ikf' ),
				'uses_context'    => array( 'postId', 'postType' ),
				'render_callback' => 'ikf_render_instructor_meta_block',
				'supports'        => array(
					'html'   => false,
					'align'  => false,
					'inserter' => true,
				),
			)
		);
	}
endif;
add_action( 'init', 'ikf_register_instructor_meta_block' );

if ( ! function_exists( 'ikf_render_instructor_meta_block' ) ) :
	/**
	 * Renders instructor_position and instructor_rank for the current loop post.
	 *
	 * @since IKF 1.0
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block instance.
	 * @return string
	 */
	function ikf_render_instructor_meta_block( $attributes, $content, $block ) {
		$post_id = 0;
		if ( ! empty( $block->context['postId'] ) ) {
			$post_id = (int) $block->context['postId'];
		} elseif ( get_the_ID() ) {
			$post_id = (int) get_the_ID();
		}

		if ( ! $post_id || ! function_exists( 'get_field' ) ) {
			return '';
		}

		$position = get_field( 'instructor_position', $post_id );
		$rank     = get_field( 'instructor_rank', $post_id );

		if ( ! $position && ! $rank ) {
			return '';
		}

		ob_start();
		?>
		<div class="ikf-instructor-meta">
			<?php if ( $position ) : ?>
				<p class="ikf-instructor-meta__position"><?php echo esc_html( $position ); ?></p>
			<?php endif; ?>
			<?php if ( $rank ) : ?>
				<p class="ikf-instructor-meta__rank"><?php echo esc_html( $rank ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		return (string) ob_get_clean();
	}
endif;

if ( ! function_exists( 'ikf_register_member_meta_block' ) ) :
	/**
	 * Registers a block that outputs member ACF position in Query Loops.
	 *
	 * @since IKF 1.0
	 *
	 * @return void
	 */
	function ikf_register_member_meta_block() {
		register_block_type(
			'ikf/member-meta',
			array(
				'api_version'     => 3,
				'title'           => __( 'Member Meta', 'ikf' ),
				'category'        => 'theme',
				'icon'            => 'groups',
				'description'     => __( 'Displays member position ACF field.', 'ikf' ),
				'uses_context'    => array( 'postId', 'postType' ),
				'render_callback' => 'ikf_render_member_meta_block',
				'supports'        => array(
					'html'     => false,
					'align'    => false,
					'inserter' => true,
				),
			)
		);
	}
endif;
add_action( 'init', 'ikf_register_member_meta_block' );

if ( ! function_exists( 'ikf_render_member_meta_block' ) ) :
	/**
	 * Renders member_position for the current loop post.
	 *
	 * @since IKF 1.0
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block instance.
	 * @return string
	 */
	function ikf_render_member_meta_block( $attributes, $content, $block ) {
		$post_id = 0;
		if ( ! empty( $block->context['postId'] ) ) {
			$post_id = (int) $block->context['postId'];
		} elseif ( get_the_ID() ) {
			$post_id = (int) get_the_ID();
		}

		if ( ! $post_id || ! function_exists( 'get_field' ) ) {
			return '';
		}

		$position = get_field( 'member_position', $post_id );

		if ( ! $position ) {
			return '';
		}

		ob_start();
		?>
		<div class="ikf-member-meta">
			<p class="ikf-member-meta__position"><?php echo esc_html( $position ); ?></p>
		</div>
		<?php
		return (string) ob_get_clean();
	}
endif;

if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

if ( ! function_exists( 'ikf_register_block_styles' ) ) :
	/**
	 * Registers IKF custom block styles.
	 *
	 * @since IKF 1.0
	 *
	 * @return void
	 */
	function ikf_register_block_styles() {
		register_block_style(
			'core/button',
			array(
				'name'         => 'fill-accent-1',
				'label'        => __( 'Fill - Gold', 'ikf' ),
				'inline_style' => '
				.wp-block-button.is-style-fill-accent-1 .wp-block-button__link {
					background-color: var(--wp--preset--color--accent-1);
					color: var(--wp--preset--color--contrast);
					font-weight: 400;
				}
				.wp-block-button.is-style-fill-accent-1 .wp-block-button__link:hover {
					background-color: color-mix(in srgb, var(--wp--preset--color--accent-1) 90%, transparent);
				}',
			)
		);

		register_block_style(
			'core/button',
			array(
				'name'         => 'fill-inverse',
				'label'        => __( 'Fill - Inverse', 'ikf' ),
				'inline_style' => '
				.wp-block-button.is-style-fill-inverse .wp-block-button__link {
					background-color: var(--wp--preset--color--base);
					color: var(--wp--preset--color--contrast);
				}
				.wp-block-button.is-style-fill-inverse .wp-block-button__link:hover {
					background-color: color-mix(in srgb, var(--wp--preset--color--base) 85%, transparent);
				}',
			)
		);
	}
endif;
add_action( 'init', 'ikf_register_block_styles' );

if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;

function current_year() {
    return date('Y');
}
add_shortcode('current_year', 'current_year');