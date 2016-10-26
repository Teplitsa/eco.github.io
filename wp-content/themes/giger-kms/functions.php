<?php
/**
 * bb functions and definitions
 *
 * @package bb
 */

define('FRL_VERSION', '1.0');
define('TST_DOC_URL', 'https://kms.te-st.ru/site-help/');


if ( ! isset( $content_width ) ) {
	$content_width = 800; /* pixels */
}


function rdc_setup() {

	// Inits
	load_theme_textdomain( 'rdc', get_template_directory() . '/lang' );
	//add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	// Thumbnails
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(640, 395, true ); // regular thumbnails
	add_image_size('square', 450, 450, true ); // square thumbnail
	add_image_size('medium-thumbnail', 790, 488, true ); // poster in widget
	add_image_size('landscape-mini', 300, 185, true ); // fixed size for embedding
	//add_image_size('cover', 400, 567, true ); // long thumbnail for pages

	// Menus
	$menus = array(
		'primary'   => 'Главное',
		'social'    => 'Социальные кнопки',
		'sitemap'   => 'Карта сайта'
	);

	register_nav_menus($menus);

	// Editor style
	//add_editor_style(array('css/editor-style.css'));
}
add_action( 'init', 'rdc_setup', 30 );


/** Custom image size for medialib **/
add_filter('image_size_names_choose', 'rdc_medialib_custom_image_sizes');
function rdc_medialib_custom_image_sizes($sizes) {

	$addsizes = apply_filters('rdc_medialib_custom_image_sizes', array(
		"landscape-mini" 	=> 'Горизонтальная миниатюра',
		"post-thumbnail" 	=> 'Стандартный',
		"medium-thumbnail" 	=> 'Фиксированный'
	));

	return array_merge($sizes, $addsizes);
}

/**
 * Register widget area.
 */
function rdc_widgets_init() {

	$config = array(
		'right_single' => array(
						'name' => 'Правая колонка - Записи',
						'description' => 'Боковая колонка справа на страницах новостей'
					),
		'right_event' => array(
						'name' => 'Правая колонка - Анонсы',
						'description' => 'Боковая колонка справа на страницах анонсов'
					),
		'footer' => array(
						'name' => 'Подвал - 4 виджета',
						'description' => 'Динамическая область в подвале: 4 виджета'
					),
	);

	foreach($config as $id => $sb) {

		$before = '<div id="%1$s" class="widget %2$s">';

		if(false !== strpos($id, 'footer')){
			$before = '<div id="%1$s" class="widget-bottom %2$s">';
		}

		register_sidebar(array(
			'name' => $sb['name'],
			'id' => $id.'-sidebar',
			'description' => $sb['description'],
			'before_widget' => $before,
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));
	}
}
add_action( 'init', 'rdc_widgets_init', 25 );


/**
 * Includes
 */

require get_template_directory().'/inc/class-cssjs.php';
require get_template_directory().'/inc/class-event.php';

require get_template_directory().'/inc/aq_resizer.php';
require get_template_directory().'/inc/author.php';
require get_template_directory().'/inc/cards.php';
require get_template_directory().'/inc/donations.php';
require get_template_directory().'/inc/events.php';
require get_template_directory().'/inc/extras.php';
require get_template_directory().'/inc/forms.php';
require get_template_directory().'/inc/post-types.php';
require get_template_directory().'/inc/related.php';
require get_template_directory().'/inc/request.php';
require get_template_directory().'/inc/shortcodes.php';
require get_template_directory().'/inc/social.php';
require get_template_directory().'/inc/template-tags.php';
require get_template_directory().'/inc/widgets.php';



if(is_admin()){
	require get_template_directory() . '/inc/admin.php';

}

function add_social_fields(){
	add_settings_field(
       'link-email',
       'Контактный email',
       'callback_adress_email',
       'general'
 	);
 	register_setting(
 	    'general',
 	    'email_adress'
 	);
	add_settings_field(
       'link-vk',
       'Адрес группы ВК',
       'callback_adress_vk',
       'general'
 	);
 	register_setting(
 	    'general',
 	    'vk_adress'
 	);
	add_settings_field(
       'link-ok',
       'Адрес группы ОК',
       'callback_adress_ok',
       'general'
 	);
 	register_setting(
 	    'general',
 	    'ok_adress'
 	);
	add_settings_field(
       'priem-vtorsirya',
       'Ссылка на страницу приёма вторсырья',
       'callback_priem_vtorsirya',
       'general'
 	);
 	register_setting(
 	    'general',
 	    'priem_vtorsirya'
 	);
	add_settings_field(
       'ecofront',
       'Ссылка на страницу Экофронта',
       'callback_ecofront',
       'general'
 	);
 	register_setting(
 	    'general',
 	    'field_ecofront'
 	);
}
add_action('admin_init', 'add_social_fields');
function callback_adress_email(){
   echo "<input class='regular-text' type='text' name='email_adress' value='". esc_attr(get_option('email_adress'))."'>";
}
function callback_adress_vk(){
   echo "<input class='regular-text' type='text' name='vk_adress' value='". esc_attr(get_option('vk_adress'))."'>";
}
function callback_adress_ok(){
   echo "<input class='regular-text' type='text' name='ok_adress' value='". esc_attr(get_option('ok_adress'))."'>";
}
function callback_priem_vtorsirya(){
   echo "<input class='regular-text' type='text' name='priem_vtorsirya' value='". esc_attr(get_option('priem_vtorsirya'))."'>";
}
function callback_ecofront(){
   echo "<input class='regular-text' type='text' name='field_ecofront' value='". esc_attr(get_option('field_ecofront'))."'>";
}

function getAllNews() {
	$parent_id = 31;
	
	$sub_cats = get_categories( array(
		'child_of' => $parent_id,
		'hide_empty' => 0
	) );
	$cats = [];
	if( $sub_cats ){
		foreach( $sub_cats as $cat ){
			$cats[] = $cat->cat_ID;
		}
		$custom_query_args = array(
				'category'    => $cats,
				'orderby'     => 'post_date',
				'order'       => 'DESC',
				'posts_per_page' => 8,
				'publish' => true,
			);

		$custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		$custom_query = new WP_Query( $custom_query_args );

		$temp_query = $wp_query;
		$wp_query   = NULL;
		$wp_query   = $custom_query;

		if ( $custom_query->have_posts() ) :
			echo '<div class="cards-loop sm-cols-1 md-cols-2 lg-cols-4">';
			$posts = $custom_query->posts;
			foreach ( $posts as $p ) :
				rdc_post_card($p);
			endforeach;
			echo '</div>';
		endif;
		$pagination = '<div class="news-pagination">';
		$pagination .= str_replace("Сюда", 
		"<svg class=\"svg-icon icon-prev\"><use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"#icon-prev\"></use></svg>Сюда", 
		str_replace("<a", "<a class='newer'", get_previous_posts_link( 'Сюда' )));
		$pagination .= str_replace("</a>", 
		"<svg class=\"svg-icon icon-next\"><use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"#icon-next\"></use></svg></a>",
		str_replace("<a", "<a class='older'", get_next_posts_link( 'Туда', $custom_query->max_num_pages )));
		$pagination .= '</div>';
		echo $pagination;
		$wp_query = NULL;
		$wp_query = $temp_query;
	}
}
add_shortcode('get-all-news','getAllNews');

function dump($what="", $die = true) {
	echo '<pre>'; var_dump($what);echo '</pre>';
	if ($die) die;
}