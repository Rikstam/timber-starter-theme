<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}

class StarterSite extends TimberSite {

	function __construct() {
		remove_action( 'wp_head', 'wp_generator' ) ;
		remove_action( 'wp_head', 'wlwmanifest_link' ) ;
		remove_action( 'wp_head', 'rsd_link' ) ;
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );

		add_action( 'init', array( $this, 'load_footer_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ) );

		parent::__construct();
	}

	function load_footer_scripts()
	{
			if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
					wp_register_script('mainscripts', get_template_directory_uri() . '/static/js/min/site-min.js', array('jquery'), '1.0.0', true); // Custom scripts
					wp_enqueue_script('mainscripts'); // Enqueue it!
			}
	}
	function load_styles()
	{

			wp_register_style('timberstyles', get_template_directory_uri() . '/static/css/main.css', array(), '1.0', 'all');
			wp_enqueue_style('timberstyles'); // Enqueue it!
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		return $context;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own fuctions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( 'myfoo', new Twig_Filter_Function( 'myfoo' ) );
		return $twig;
	}

}

new StarterSite();

function myfoo( $text ) {
	$text .= ' bar!';
	return $text;
}
