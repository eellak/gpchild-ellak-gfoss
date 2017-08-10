<?php

/* functions, filters and hooks for ellak.gr child theme */

# axil 2015-09-06
# http://diywpblog.com/wordpress-optimization-remove-query-strings-from-static-resources/
function _remove_script_version( $src ){
	$parts = explode( '?ver', $src );
        return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

# Mediawiki SSO
add_action('wp_logout', 'mw_logout');
function mw_logout() {
    $cookiesSet = array_keys($_COOKIE);
    for ($x=0;$x<count($cookiesSet);$x++) setcookie($cookiesSet[$x],"",time()-1);
}

add_action( 'after_setup_theme', 'ellak_theme_setup' );
function ellak_theme_setup() {
	// remove generatepress action hooks
	remove_action( 'generate_before_content',
		'generate_featured_page_header_inside_single', 10 );
	remove_action( 'generate_credits', 'generate_add_footer_info' );

	// child theme translations in /languages
	load_child_theme_textdomain( 'gpchild-ellak', get_template_directory()
		. '/languages' );

	// hide admin bar for subscribers
	$user = wp_get_current_user();
	if( in_array( 'subscriber', $user->roles ) ) {
		show_admin_bar( false );
	}
}

// enqueue extra scripts and styles
add_action( 'wp_enqueue_scripts', 'ellak_font_awesome' );
function ellak_font_awesome() {
	// Font Awesome
	wp_enqueue_style( 'font-awesome',
		'//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );

	// Facebook SDK
	wp_enqueue_script( 'facebook-sdk', get_stylesheet_directory_uri() . '/js/facebook.js', array(), '2.3', true );
}

//enqueue boostrap scripts
add_action('wp_enqueue_scripts', 'enqueue_bootstrap');
function enqueue_bootstrap(){
    //the css file
    wp_enqueue_style('bootstrap_css_3.3', get_stylesheet_directory_uri().'/bootstrap.min.css');
    
    //the js file
    wp_enqueue_script('bootstrap_js_3.3', get_stylesheet_directory_uri().'/js/bootstrap.min.js', array('jquery'), '3.3', false);
}


/** 
 * enqueue the scripts for the edu_fos archive page
 **/
add_action('wp_enqueue_scripts', 'enqueue_edu_fos_js');
function enqueue_edu_fos_js(){
    wp_enqueue_script('edu_fos_js', get_stylesheet_directory_uri().'/js/edu_fos_js.js', array('jquery'), '0.1', false);
}

/**
 * Enqueue the style file for the edu_fos archive page
 **/
if(!function_exists('archive_edu_fos_style')){
    function archive_edu_fos_style(){
        //if(is_page_template('archive-github_contributor.php')){
            wp_enqueue_style('archive-edu_fos-style', get_stylesheet_directory_uri().'/css/archive-edu_fos-style.css');
        //}
    }
}
add_action('wp_enqueue_scripts', 'archive_edu_fos_style');

// add clearfix class in the header container
add_filter( 'generate_inside_header_class', 'ellak_inside_header_classes' );
function ellak_inside_header_classes( $classes ) {
	$classes[] = 'clearfix';
	return $classes;
}

// add greek subset in embedded fonts
add_filter( 'generate_fonts_subset', 'ellak_fonts_subset' );
function ellak_fonts_subset() {
	return 'latin,latin-ext,greek';
}

// load the ellak news bar if available
add_action( 'generate_before_header', 'ellak_load_newsbar' );
function ellak_load_newsbar() {
	if( function_exists( 'ellak_newsbar' ) ) {
		ellak_newsbar();
	}
}

// add slider in #primary, only in home. requires 'Advanced Post Slider' plugin
add_action( 'generate_before_main_content', 'ellak_slider' );
function ellak_slider() {
	if( is_front_page() && function_exists( "get_smooth_slider_recent" ) ){ get_smooth_slider_recent(); }
}

// social links
add_action( 'generate_before_header_content', 'ellak_social_links' );
function ellak_social_links() { ?>
	<div class="header-social-links">
		<ul class="social-links">
			<li class="social-link-opinion"><a href="https://ellak.gr/pite-mas-ti-gnomi-sas/" title="Πείτε μας τη γνώμη σας" target="_blank"><span>Πείτε μας τη γνώμη σας</span></a></li>
			<li class="social-link-facebook"><a href="https://www.facebook.com/eellak" title="Facebook" target="_blank"><span>Facebook</span></a></li>
			<li class="social-link-twitter"><a href="https://twitter.com/gfoss_en" title="Twitter" target="_blank"><span>Twitter</span></a></li>
			<li class="social-link-github"><a href="https://github.com/eellak" title="GitHub" target="_blank"><span>GitHub</span></a></li>
			<li class="social-link-vimeo"><a href="https://www.vimeo.com/eellak" title="Vimeo" target="_blank"><span>Vimeo</span></a></li>
			<li class="social-link-flickr"><a href="https://flickr.com/photos/eellak" title="Flickr" target="_blank"><span>Flickr</span></a></li>
			<li class="social-link-rss"><a href="https://ellak.gr/rss-feeds/" title="RSS" target="_blank"><span>RSS</span></a></li>
		</ul>
	</div><!-- .header-social-links -->
<?php }

// footer
add_action( 'generate_credits', 'ellak_credits' );
function ellak_credits() {
	echo __( '<a href="https://mathe.ellak.gr/" target="_blank">Υλοποίηση με χρήση του Ανοικτού Λογισμικού</a>', 'gpchild-ellak-opengov' )
		. ' <a href="https://wordpress.org/" target="_blank">Wordpress</a> | '
		. '<a href="https://ellak.gr/ori-chrisis" target="_blank">'
		. __( 'Όροι Χρήσης & Δήλωση Απορρήτου', 'gpchild-ellak-opengov' ) . '</a> | '
		. __( 'Άδεια χρήσης περιεχομένου:', 'gpchild-ellak' )
		. ' <a href="https://creativecommons.org/licenses/by-sa/4.0/deed.el">'
		. __( 'CC-BY-SA', 'gpchild-ellak' ) . '</a> | '
		. ' <a href="https://ellak.gr/stichia-epikinonias-chartis/">'
		. __( 'Επικοινωνία', 'gpchild-ellak' ) . '</a>';
}
remove_filter( 'wprss_pagination', 'wprss_pagination_links' );

/* Include the diadose_bar plugin */
add_action( 'generate_before_header', 'load_diadose_bar' );
function load_diadose_bar(){
    if(function_exists('diadose_bar')){
        diadose_bar();
    }
}

?>