<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'parallax', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'parallax' ) );

//* Add Image upload to WordPress Theme Customizer
add_action( 'customize_register', 'parallax_customizer' );
function parallax_customizer(){

	require_once( get_stylesheet_directory() . '/lib/customize.php' );

}

//* Include Section Image CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Rian' );
define( 'CHILD_THEME_URL', 'http://www.rrwd.nl/' );
define( 'CHILD_THEME_VERSION', '1.0' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'parallax_enqueue_scripts_styles' );
function parallax_enqueue_scripts_styles() {

	wp_enqueue_script( 'parallax-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );

	wp_register_script( 'skip-links',  GENESIS_JS_URL . "/skip-links.js" );
	wp_enqueue_script( 'skip-links' );

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'parallax-google-fonts', '//fonts.googleapis.com/css?family=Montserrat', array(), CHILD_THEME_VERSION );

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

add_theme_support( 'genesis-accessibility', array( 'headings', 'search-form' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Modify the header URL - HTML5 Version
add_filter( 'genesis_seo_title', 'rian_header_title', 10, 3 );
function rian_header_title( $title, $inside, $wrap ) {

    if ( is_home() || is_front_page() ) {
    	return sprintf( '<h1 class="site-title">%1$s</h1>', get_bloginfo( 'name' ) );
    } else {
    	$inside = sprintf( '<a href="%s">%s</a>', get_bloginfo( 'url' ),  get_bloginfo( 'name' ) );
 		return sprintf( '<p class="site-title">%1$s</p>', $inside );
    }

}

add_action( 'init', 'rian_disable_wp_emojicons' );
function rian_disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

}

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_nav' );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

//* add id to the content div for skip links
add_filter( 'genesis_attr_content', 'rian_genesis_attr_content' );
function rian_genesis_attr_content( $attributes ) {
    $attributes['id'] = 'rian-content';
    return $attributes;
}


add_action ( 'genesis_header', 'rian_skip_links', 5);
function rian_skip_links() {
	?>
	<section>
	<h2 class="screen-reader-text">Quickly to</h2>
		<ul>

			<li><a href="#rian-content" class="screen-reader-text button">Jump to content</a></li>
		</ul>
	</section>

	<?php

}

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );


//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 360,
	'height'          => 70,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'footer-widgets',
	'footer',
) );

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'parallax_author_box_gravatar' );
function parallax_author_box_gravatar( $size ) {

	return 176;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'parallax_comments_gravatar' );
function parallax_comments_gravatar( $args ) {

	$args['avatar_size'] = 120;

	return $args;

}

//* Customize the credits
add_filter( 'genesis_footer_creds_text', 'rrwd_footer_creds_text' );
function rrwd_footer_creds_text() {

	echo "<p>WordPress Accessible? Let's do this!</p>";
}

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 1 );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Register widget areas
genesis_register_sidebar( array(
	'id'           => 'home-section-1',
	'name'         => __( 'Home Section 1', 'parallax' ),
	'description'  => __( 'This is the home section 1 section.', 'parallax' ),
	'before_title' => '<h2 class="widgettitle widget-title">',
	'after_title'  => '</h2>',
) );
genesis_register_sidebar( array(
	'id'          => 'home-section-2',
	'name'        => __( 'Home Section 2', 'parallax' ),
	'description' => __( 'This is the home section 2 section.', 'parallax' ),
	'before_title' => '<h2 class="widgettitle widget-title">',
	'after_title'  => '</h2>',

) );
genesis_register_sidebar( array(
	'id'          => 'home-section-3',
	'name'        => __( 'Home Section 3', 'parallax' ),
	'description' => __( 'This is the home section 3 section.', 'parallax' ),
	'before_title' => '<h2 class="widgettitle widget-title">',
	'after_title'  => '</h2>',

) );

