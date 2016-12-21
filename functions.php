<?php
/**
 *
 * Events functions and definitions
 *
 */

// loading the necessary elements
get_template_part('comments', 'template');
get_template_part('theme', 'customizer');
get_template_part('addons/class.gkoauth');
get_template_part('addons/class.gkutils');
get_template_part('addons/class.tgm');
get_template_part('shortcodes');

if ( ! function_exists( 'events_excerpt' ) ) :
/**
 *
 * Functions used to generate post excerpt
 *
 * @return HTML output
 *
 **/

function events_excerpt($text) {
    return $text . '&hellip;';
}

add_filter( 'get_the_excerpt', 'events_excerpt', 999 );
endif;

if ( ! function_exists( 'events_excerpt_more' ) ) :
function events_excerpt_more($text) {
    return '';
}

add_filter( 'excerpt_more', 'events_excerpt_more', 999 );
endif;

if ( ! function_exists( 'events_setup' ) ) :
/**
 * Events setup.
 *
 * Sets up theme defaults and registers the various WordPress features
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 *
 *
 * @return void
 */
function events_setup() {
	global $content_width;
	
	if ( ! isset( $content_width ) ) $content_width = 900;
	
	/*
	 * Makes Events available for translation.
	 *
	 */
	load_theme_textdomain( 'events', get_template_directory() . '/languages' );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Switches default core markup for search form, comment form,
	 * and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	/*
	 * This theme supports all available post formats by default.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'gallery', 'image', 'link', 'quote', 'video'
	) );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'events' ) );
	register_nav_menu( 'footer', __( 'Footer Menu', 'events' ) );
	register_nav_menu( 'social', __( 'Social Menu', 'events' ) );
	register_nav_menu( 'location', __( 'Location Menu', 'events' ) );
	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	
	// Support for custom background
	$args = array(
		'default-color' => 'f3f3f3',
		'default-image' => get_template_directory_uri() . '/images/bg-desktop.jpg',
	);
	add_theme_support( 'custom-background', $args );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
	
	// Support for custom header image on the frontpage
	$defaults = array(
		'default-image'          => get_template_directory_uri() . '/images/header_bg.jpg',
		'random-default'         => false,
		'width'                  => 0,
		'height'                 => 0,
		'flex-height'            => true,
		'flex-width'             => true,
		'default-text-color'     => '#fff',
		'header-text'            => true,
		'uploads'                => true,
		'wp-head-callback'       => '',
		'admin-head-callback'    => '',
		'admin-preview-callback' => '',
	);

	add_theme_support( 'custom-header', $defaults );
	
	add_theme_support('widget-customizer');
}
add_action( 'after_setup_theme', 'events_setup' );
endif;

if ( ! function_exists( 'events_add_editor_styles' ) ) :
/**
 * Enqueue scripts for the back-end.
 *
 * @return void
 */
function events_add_editor_styles() {
    add_editor_style('editor.css');
}
add_action('init', 'events_add_editor_styles');
endif;

if ( ! function_exists( 'events_scripts' ) ) :
/**
 * Enqueue scripts for the front end.
 *
 * @return void
 */
function events_scripts() {
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if(is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	// Loads JavaScript file with Modernizr
	wp_enqueue_script( 'events-modernizr', get_template_directory_uri() . '/js/modernizr.js', array(), '', true );

	// Loads JavaScript file with functionality specific to Events.
	wp_enqueue_script( 'events-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '', true );
	
	// Loads JavaScript file for responsive video.
	wp_enqueue_script('events-video',  get_template_directory_uri() . '/js/jquery.fitvids.js', false, false, true);
	
	// Loads JavaScript file for the scroll reveal
	if(get_theme_mod('events_scroll_reveal', '1') == '1') {
		wp_enqueue_script('events-scroll-reveal',  get_template_directory_uri() . '/js/scrollreveal.js', false, false, true);
	}
}

add_action( 'wp_enqueue_scripts', 'events_scripts' );
endif;

if ( ! function_exists( 'events_styles' ) ) :
/**
 * Enqueue styles for the front end.
 *
 * @return void
 */
function events_styles() {
	// Add normalize stylesheet.
	wp_enqueue_style('events-normalize', get_template_directory_uri() . '/css/normalize.css', false);

	// Add Google font from the customizer
	wp_enqueue_style('events-fonts-body', get_theme_mod('events_body_google_font', '//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700'), false);
	wp_enqueue_style('events-fonts-header', get_theme_mod('events_headers_google_font', '//fonts.googleapis.com/css?family=Nunito:400,700,300'), false);
	wp_enqueue_style('events-fonts-other', get_theme_mod('events_other_google_font'), false);
	
	// Font Awesome
	wp_enqueue_style('events-font-awesome', get_template_directory_uri() . '/css/font.awesome.css', false, '4.2.0' );
	
	// Events Pixellove icons
	wp_enqueue_style('events-pixellove', get_template_directory_uri() . '/css/events.pixellove.css', false, '4.1.0' );

	/* KT Cache Clearance after file edit automatically Start */
	$handle = 'events-style';
	$src =  get_stylesheet_uri();
	$deps = '';
	$ver = filemtime( get_stylesheet_directory().'/style.css');
	$media = 'screen, projection';
	wp_enqueue_style( $handle, $src, $deps, $ver, $media );
	/* KT Cache Clearance after file edit automatically End */
	
	// Loads our main stylesheet.
	//wp_enqueue_style( 'events-style', get_stylesheet_uri());
	
	// Loads RWD stylesheets - from child theme if css files are placed there.
	// small desktop
	if(file_exists(get_stylesheet_directory() . '/css/small.desktop.css') ) {
		wp_enqueue_style( 'events-small-desktop', get_stylesheet_directory_uri() . '/css/small.desktop.css', array('events-style'), false, '(max-width: '.get_theme_mod('events_theme_width', '1240').'px)');
	} else {
		wp_enqueue_style( 'events-small-desktop', get_template_directory_uri() . '/css/small.desktop.css', array('events-style'), false, '(max-width: '.get_theme_mod('events_theme_width', '1240').'px)');
	}
	// tablet
	if(file_exists(get_stylesheet_directory() . '/css/tablet.css') ) {
		wp_enqueue_style( 'events-tablet', get_stylesheet_directory_uri() . '/css/tablet.css', array('events-style'), false, '(max-width: '.get_theme_mod('events_tablet_width', '1040').'px)');
	} else {
		wp_enqueue_style( 'events-tablet', get_template_directory_uri() . '/css/tablet.css', array('events-style'), false, '(max-width: '.get_theme_mod('events_tablet_width', '1040').'px)');
	}
	// small tablet
	if(file_exists(get_stylesheet_directory() . '/css/small.tablet.css') ) {
		wp_enqueue_style( 'events-small-tablet', get_stylesheet_directory_uri() . '/css/small.tablet.css', array('events-style'), false, '(max-width: '.get_theme_mod('events_small_tablet_width', '840').'px)');
	} else {
		wp_enqueue_style( 'events-small-tablet', get_template_directory_uri() . '/css/small.tablet.css', array('events-style'), false, '(max-width: '.get_theme_mod('events_small_tablet_width', '840').'px)');
	}
	// mobile
	if(file_exists(get_stylesheet_directory() . '/css/mobile.css') ) {
		wp_enqueue_style( 'events-mobile', get_stylesheet_directory_uri() . '/css/mobile.css', array('events-style'), false, '(max-width: '.get_theme_mod('events_mobile_width', '640').'px)');
	} else {
		wp_enqueue_style( 'events-mobile', get_template_directory_uri() . '/css/mobile.css', array('events-style'), false, '(max-width: '.get_theme_mod('events_mobile_width', '640').'px)');
	}
	
	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'events-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'events-style' ) );
	wp_style_add_data( 'events-ie8', 'conditional', 'lt IE 9' );
	
	wp_enqueue_style( 'events-ie9', get_template_directory_uri() . '/css/ie9.css', array( 'events-style' ) );
	wp_style_add_data( 'events-ie9', 'conditional', 'IE 9' );
}

add_action( 'wp_enqueue_scripts', 'events_styles' );
endif;

if ( ! function_exists( 'events_wp_title' ) ) :
/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */
function events_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'events' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'events_wp_title', 10, 2 );
endif;

if ( ! function_exists( 'events_widgets_init' ) ) :
/**
 * Register widget area.
 *
 * @return void
 */
function events_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Header widget area', 'events' ),
		'id'            => 'header',
		'description'   => __( 'Appears at the top of the website.', 'events' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar( array(
		'name'          => __( 'Top I widget area', 'events' ),
		'id'            => 'top1',
		'description'   => __( 'Appears at the top of the website.', 'events' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar( array(
		'name'          => __( 'Top II widget area', 'events' ),
		'id'            => 'top2',
		'description'   => __( 'Appears at the top of the website.', 'events' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar( array(
		'name'          => __( 'Content Top', 'events' ),
		'id'            => 'content_top',
		'description'   => __( 'Appears at the top of the website content.', 'events' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar( array(
		'name'          => __( 'Content Bottom', 'events' ),
		'id'            => 'content_bottom',
		'description'   => __( 'Appears at the bottom of the website content.', 'events' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar( array(
		'name'          => __( 'Sidebar widget area', 'events' ),
		'id'            => 'sidebar',
		'description'   => __( 'Appears at the left/right side of the website.', 'events' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar( array(
		'name'          => __( 'Bottom I widget area', 'events' ),
		'id'            => 'bottom1',
		'description'   => __( 'Appears at the bottom of the website.', 'events' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar( array(
		'name'          => __( 'Bottom II widget area', 'events' ),
		'id'            => 'bottom2',
		'description'   => __( 'Appears at the bottom of the website.', 'events' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar( array(
		'name'          => __( 'Bottom III widget area', 'events' ),
		'id'            => 'bottom3',
		'description'   => __( 'Appears at the bottom of the website.', 'events' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
}
add_action( 'widgets_init', 'events_widgets_init' );
endif;

if ( ! function_exists( 'events_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 *
 * @return void
 */
function events_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h3 class="screen-reader-text"><?php _e( 'Posts navigation', 'events' ); ?></h3>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'events' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'events' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;


if( ! function_exists( 'events_video_code' ) ) :

function events_video_code() {
	$video_condition = stripos(get_the_content(), '</iframe>') !== FALSE || stripos(get_the_content(), '</video>') !== FALSE; 
	
	if($video_condition) {
		$video_code = '';
		
		if(stripos(get_the_content(), '</iframe>') !== FALSE) {
			$start = stripos(get_the_content(), '<iframe');
			$len = strlen(substr(get_the_content(), $start, stripos(get_the_content(), '</iframe>', $start)));
			$video_code = substr(get_the_content(), $start, $len + 9); 
		} elseif(stripos(get_the_content(), '</video>') !== FALSE) {
			$start = stripos(get_the_content(), '<video');
			$len = strlen(substr(get_the_content(), $start, stripos(get_the_content(), '</video>', $start)));
			$video_code = substr(get_the_content(), $start, $len + 8); 
		}
		
		return $video_code;
	} else {
		return FALSE;
	}
}

endif;


if (!function_exists( 'events_the_attached_image' )) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Events 1.0
 *
 * @return void
 */
function events_the_attached_image() {
	/**
	 * Filter the image attachment size to use.
	 *
	 * @since Events 1.0
	 *
	 * @param array $size {
	 *     @type int The attachment height in pixels.
	 *     @type int The attachment width in pixels.
	 * }
	 */
	$attachment_size     = apply_filters( 'events_attachment_size', array( 724, 724 ) );
	$next_attachment_url = wp_get_attachment_url();
	$post                = get_post();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

if ( ! function_exists( 'events_register_required_plugins' ) ) :
/**
 * Register the required plugins for this theme.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function events_register_required_plugins() {
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * 
     */
     $plugins = array(
          // Plugins pre-packaged with a theme.
          array(
              'name'               => 'GK Widget Rules',
              'slug'               => 'gk-widget-rules',
              'source'             => 'http://www.gavick.com/upd/gk-widget-rules.zip', 
              'required'           => true,
              'version'            => ''
          ),
          
          array(
              'name'               => 'GK News Show Pro',
              'slug'               => 'gk-nsp',
              'source'             => 'http://www.gavick.com/upd/gk-nsp.zip', 
              'required'           => false,              
              'version'            => ''
          ),
 
          array(
              'name'               => 'GK Tabs',
              'slug'               => 'gk-tabs',
              'source'             => 'http://www.gavick.com/upd/gk-tabs.zip', 
              'required'           => false,              
              'version'            => ''
          )  
      );
     
     /**
      * Array of configuration settings. 
      */
     $config = array(
         'id'           => 'tgmpa',
         'menu'         => 'tgmpa-install-plugins',
         'has_notices'  => true,
         'dismissable'  => true,                    
         'is_automatic' => false,
         'strings'      => array(
            'menu_title'                      => __( 'Install Plugins', 'events' ),
            'page_title'                      => __( 'Install Required Plugins', 'events' ),
            'installing'                      => __( 'Installing Plugin: %s', 'events' ), 
            'oops'                            => __( 'Something went wrong with the plugin API.', 'events' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'events' ), 
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'events' ),
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'events' ),
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'events' ),
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'events' ),
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'events' ),
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'events' ),
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'events' ),
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'events' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'events' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'events' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'events' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'events' ),
            'nag_type'                        => 'updated'
         )
     );
 
     tgmpa($plugins, $config);
}

add_action('tgmpa_register', 'events_register_required_plugins');
endif;
// EOF

/* KT- code to add metabox for uploading file as custom field Start */
function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
	<style type="text/css">
	.inside .speaker-admin-box label { float: left; width: 25%;}
	.inside .speaker-admin-box input { width: 73%; margin-bottom:10px;}
	.inside .speaker-admin-box input[type="checkbox"] { float: none; width: 15px;}
		</style>
        <div class="speaker-admin-box">
            <label for="meta_box_lastname">Lastname</label>
            <input name="meta_box_lastname" type="text" value="<?php echo get_post_meta($object->ID, "meta_box_lastname", true); ?>">
			<label for="meta_box_firstname">Firstname</label>
            <input name="meta_box_firstname" type="text" value="<?php echo get_post_meta($object->ID, "meta_box_firstname", true);?>">
			<label for="meta_box_jobtitle">Jobtitle</label>
            <input name="meta_box_jobtitle" type="text" value="<?php echo get_post_meta($object->ID, "meta_box_jobtitle", true); ?>">
			<label for="meta_box_company">Company</label>
            <input name="meta_box_company" type="text" value="<?php echo get_post_meta($object->ID, "meta_box_company", true); ?>">
			<label for="meta_box_checkbox">Check Box</label>
            <?php
                $checkbox_value = get_post_meta($object->ID, "meta_box_checkbox", true);

                if($checkbox_value == "")
                {
                    ?>
                        <input name="meta_box_checkbox" type="checkbox" value="true">
                    <?php
                }
                else if($checkbox_value == "true")
                {
                    ?>  
                        <input name="meta_box_checkbox" type="checkbox" value="true" checked>
                    <?php
                }
            ?>
        </div>
    <?php  
}

function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Custom Meta Box", "custom_meta_box_markup", "post", "normal", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");

function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    $meta_box_dropdown_value = "";
    $meta_box_checkbox_value = "";

    if(isset($_POST["meta_box_lastname"]))
    {
        $meta_box_lastname_value = $_POST["meta_box_lastname"];
    }   
    update_post_meta($post_id, "meta_box_lastname", $meta_box_lastname_value);
	
	
	if(isset($_POST["meta_box_firstname"]))
    {
        $meta_box_firstname_value = $_POST["meta_box_firstname"];
    }   
    update_post_meta($post_id, "meta_box_firstname", $meta_box_firstname_value);
	
	
	if(isset($_POST["meta_box_jobtitle"]))
    {
        $meta_box_job_value = $_POST["meta_box_jobtitle"];
    }   
    update_post_meta($post_id, "meta_box_jobtitle", $meta_box_job_value);	
	
	if(isset($_POST["meta_box_company"]))
    {
        $meta_box_company_value = $_POST["meta_box_company"];
    }   
    update_post_meta($post_id, "meta_box_company", $meta_box_company_value);	
	

    if(isset($_POST["meta_box_checkbox"]))
    {
        $meta_box_checkbox_value = $_POST["meta_box_checkbox"];
    }
    update_post_meta($post_id, "meta_box_checkbox", $meta_box_checkbox_value);
}

add_action("save_post", "save_custom_meta_box", 10, 3);

/* KT- code to add metabox for uploading file as custom field End */

/* KT - code to create shortcode for displaying speakers Start */
	function wpb_postsbycategory($atts) {
	global $post;
	$atts = shortcode_atts(
		array(
			'category' => 'keynote-speaker',
			'featured_category' => 'speaker',
		), $atts );	
	
	$keynote_query = new WP_Query( array( 'category_name' => "'".$atts['category']."'", 'posts_per_page' => 5, 'order' => 'ASC' ) );
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$speaker_query = new WP_Query( array( 'category_name' => "'".$atts['featured_category']."'", 'posts_per_page' => 12, 'orderby' => 'title', 'order' => 'ASC', 'paged' => $paged ) );	
	$incr = 1;
	$m = 1;
	
	$count_keynote = $keynote_query->found_posts;
	$count_featured = $speaker_query->found_posts;
		
	if($count_keynote==1) { $keynote_width="width_100"; } else if($count_keynote==3) { $keynote_width="symple-one-third"; } else if($count_keynote==4) { $keynote_width="symple-one-fourth"; } else if($count_keynote==5) { $keynote_width="symple-one-fifth"; } else {$keynote_width="width_auto";}
	if($count_featured<4) $featured_width="width_100"; else $featured_width="width_auto";
	
	$keynote_title = get_post_meta($post->ID, "speaker_keynote_title", true);
	$feature_title = get_post_meta($post->ID, "speaker_featured_title", true);
	?>
	<style>
	.width_100{width:100% !important;}
	</style>
	<div class="fullwidth full-inner fattend speaker-face-box" <?php if($count_keynote==3) { ?> style="width:100%; max-width:790px;" <?php } else if($count_keynote==4) { ?> style="width:100%; max-width:940px;" <?php }  else if($count_keynote==5) { ?> style="width:100%; max-width:1024px;" <?php } else { ?> style="width:100%; max-width:540px;" <?php } ?>>
	<?php
	if($paged<2) {		
	if ( $keynote_query->have_posts() ) {		
		if($keynote_title!="") echo "<h1 class='sub-title'>".$keynote_title."</h1>"; else echo "<h1 class='sub-title'>Keynote Speakers</h1>";
		while ( $keynote_query->have_posts() ) {
			$keynote_query->the_post();			
			$lname = get_post_meta($post->ID, "meta_box_lastname", true);
			$fname = get_post_meta($post->ID, "meta_box_firstname", true);
			$jtitle = get_post_meta($post->ID, "meta_box_jobtitle", true);
			$company = get_post_meta($post->ID, "meta_box_company", true);
			$show_hide = get_post_meta($post->ID, "meta_box_checkbox", true);
			
			$content_len = strlen(get_the_content());
			if($count_keynote==5) {
				if($m==1) { $cl = 'first'; } else if($m==2 || $m==3 || $m==4) {$cl = 'middle';} else if($m==5) {$cl = 'last';}				
			}
			else if($count_keynote==4) {
				if($m==1) { $cl = 'first'; } else if($m==2 || $m==3) {$cl = 'middle';} else if($m==4) {$cl = 'last';}				
			}
			else if($count_keynote==3) {
				if($m==1) { $cl = 'first'; } else if($m==2) {$cl = 'middle';} else if($m==3) {$cl = 'last';}				
			}
			else if($count_keynote==2) {
				if($m==1) { $cl = 'first'; } else if($m==2) {$cl = 'last';}
			}
			/* else {
				echo "Other".$count_keynote;
				if($m==1) { $cl = 'first'; } else if($m==2) {$cl = 'last';}
			} */
	?>
		<div class="symple-column symple-one-half symple-column-<?php echo $cl.' '.$keynote_width; ?>">
			<div class="face-block"><a href="#inline-content_<?php echo $m.'_'.$incr; ?>" rel="lightbox" data-gall="gall-frame" data-lightbox-type="inline"><img class="" src="<?php if ( has_post_thumbnail() ) {the_post_thumbnail_url('full');} else { echo "http://fpoimg.com/160x160"; } ?>" alt="<?php echo $fname.' '.$lname; ?>" width="160" height="160" /></a></div>
			<div class="face-block "><a href="#inline-content_<?php echo $m.'_'.$incr; ?>" rel="lightbox" data-gall="gall-frame" data-lightbox-type="inline"><span class="author"><?php echo $fname.' '.$lname; ?></span></a></div>
			<div class="face-block "><span class="author-role"><?php echo $jtitle; ?><br/><?php echo $company; ?></span></div>
			<span id="inline-content_<?php echo $m.'_'.$incr; ?>" class="sub-head-4d" style="display: none;">
				<div class="symple-column symple-one-fourth symple-column-first pop-left-center"><img class="" src="<?php if ( has_post_thumbnail() ) {the_post_thumbnail_url('full');} else { echo "http://fpoimg.com/160x160"; } ?>" alt="<?php echo $fname.' '.$lname; ?>" width="160" height="160" />
				<div class="face-block "><span class="author"><?php echo $fname.' '.$lname; ?></span><span class=""><?php echo $company; ?></span><span class="author-role"><?php echo $jtitle; ?></span></div>
				</div>
				<div class="symple-column symple-three-fourth symple-column-last"><?php if($content_len>1) echo get_the_content(); else echo "No Bio Information"; ?></div>
			</span><hr style="height:7px" class="symple-spacing">
		</div>		
	<?php
		$m++;
		}
	}
	//wp_reset_postdata(); // Restore original Post Data	
	}
	?>
	</div>
	
	<div class="fullwidth full-inner fattend speaker-face-box">
	<?php if($feature_title!="") echo "<h3 class='sub-title'>".$feature_title."</h3>"; else echo "<h3 class='sub-title'>Featured Session Speakers</h3>"; ?>
	<style type="text/css">
	.entry-header{display:none; visibility:hidden}
	.sub-title{color:#363636; text-align:center; margin:30px 0px; }
	.symple-one-half{min-height:auto !important;}
		
	.symple-divider.solid {clear: both;}
	.author-role{color: #4d4d4d; float: left; font-size: 0.6em; font-weight: 500; padding: 0 0 10px; width: 100%;}
	.fullwidth.fattend .speaker-page.symple-column{min-height:305px;}
	.pop-left-center{text-align:center;}
	.nivo-lightbox-wrap{bottom:28% !important; top:28% !important;}
	</style>
	
	<?php
	// The Loop
	if ( $speaker_query->have_posts() ) {
//var_dump($speaker_query);
		while ( $speaker_query->have_posts() ) {
			$speaker_query->the_post();			
			$lname = get_post_meta($post->ID, "meta_box_lastname", true);
			$fname = get_post_meta($post->ID, "meta_box_firstname", true);
			$jtitle = get_post_meta($post->ID, "meta_box_jobtitle", true);
			$company = get_post_meta($post->ID, "meta_box_company", true);
			$show_hide = get_post_meta($post->ID, "meta_box_checkbox", true);
			
			$content_len = strlen(get_the_content());
			
			//if ( has_post_thumbnail() ) { } else { $img = '<img class="" src="http://fpoimg.com/160x160" alt="Box1" width="160" height="160" />'; }

			if (($incr % 4) == 0)
				$cls = 'last';
			elseif(($incr % 4) == 1)
				$cls = 'first';
			else
				$cls = 'middle';			
		
			?>	
			
				<div class="speaker-page symple-column symple-one-fourth symple-column-<?php echo $cls; ?>">
				<div class="face-block"><a href="#inline-content_<?php echo $incr; ?>" rel="lightbox" data-gall="gall-frame" data-lightbox-type="inline"><img class="" src="<?php if ( has_post_thumbnail() ) {the_post_thumbnail_url('full');} else { echo "http://fpoimg.com/160x160"; } ?>" alt="<?php echo $fname.' '.$lname; ?>" width="160" height="160" /></a></div>
				<div class="face-block "><a href="#inline-content_<?php echo $incr; ?>" rel="lightbox" data-gall="gall-frame" data-lightbox-type="inline"><span class="author"><?php echo $fname.' '.$lname; ?></span></a></div>
				<div class="face-block "><span class="author-role"><?php echo $jtitle; ?><br/><?php echo $company; ?></span></div>
				<span id="inline-content_<?php echo $incr; ?>" class="sub-head-4d" style="display: none;">
					<div class="symple-column symple-one-fourth symple-column-first pop-left-center"><img class="" src="<?php if ( has_post_thumbnail() ) {the_post_thumbnail_url('full');} else { echo "http://fpoimg.com/160x160"; } ?>" alt="<?php echo $fname.' '.$lname; ?>" width="160" height="160" />
					<div class="face-block "><span class="author"><?php echo $fname.' '.$lname; ?></span><span class=""><?php echo $company; ?></span><span class="author-role"><?php echo $jtitle; ?></span></div>
					</div>
					<div class="symple-column symple-three-fourth symple-column-last"><?php if($content_len>1) echo get_the_content(); else echo "No Bio Information"; ?></div>
				</span><hr style="height:7px" class="symple-spacing">
				</div>
					
			<?php
			$incr++; 				
		}
	} else {
		// no posts found
	}	
	wp_reset_postdata(); // Restore original Post Data	
	echo "</div><hr style='margin-top: 20px; margin-bottom: 20px' class='symple-divider solid'>";
	
	if(function_exists('tw_pagination')) tw_pagination($speaker_query);
	}	
	add_shortcode('categoryposts', 'wpb_postsbycategory');
/* KT - code to create shortcode for displaying speakers End */
	
	
	function wpcodex_add_menu_order_support_for_post() {
		add_post_type_support( 'post', 'menu_order' );
	}
	add_action( 'init', 'wpcodex_add_menu_order_support_for_post' );
	
/* KT - Code to create shortcode for appending query string to reister & register now buttons Start */

/* ================== KT Hook Remove ?ver from style.css Start =======================*/
/* function remove_cssjs_ver( $src ) {
    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 ); */
/* ================== KT Hook Remove ?ver from style.css End =======================*/

/* ============================ KT: on 4Oct16 DTM script case logic for Dev and Live Server Start ============================ */
function load_dtm_script(){
	if ($_SERVER['HTTP_HOST'] == "s16970.p402.sites.pressdns.com"){
		wp_register_script('dtm_script', 'http://assets.adobedtm.com/e4bb86ac0ef46215a117e82e4f945d2ba5c51004/satelliteLib-cda308493510533dfd0ed9f46f75719a73108a84-staging.js', 
        array( 'jquery' ));
	} else {
		wp_register_script('dtm_script', 'http://assets.adobedtm.com/e4bb86ac0ef46215a117e82e4f945d2ba5c51004/satelliteLib-cda308493510533dfd0ed9f46f75719a73108a84.js', 
        array( 'jquery' ));
	}
    wp_enqueue_script( 'dtm_script' );
}
add_action('wp_enqueue_scripts', 'load_dtm_script');
/* ============================ KT: on 4Oct16 DTM script case logic for Dev and Live Server End ============================ */
?>