<?php
define('TEMPLATE_DOMAIN', 'bp-fun');
define('EDITOR_BG_ENABLE', 'no'); //should be yes or no...
define('USE_NEW_COMMENT_FORM','no');
////////////////////////////////////////////////////////////////////////////////
// load text domain
////////////////////////////////////////////////////////////////////////////////
load_theme_textdomain( TEMPLATE_DOMAIN, TEMPLATEPATH . '/languages/' );

////////////////////////////////////////////////////////////////////////////////
// browser detect
///////////////////////////////////////////////////////////////////
add_filter('body_class','browser_body_class');
function browser_body_class($classes) {
global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';
	if($is_iphone) $classes[] = 'iphone';
	return $classes;
}
function wp_add_css_ie_tweak() {
global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
if($is_IE) { ?>
<?php print "<style type='text/css' media='screen'>"; ?>
.picture-activity-thumb {
  width: 100px;
  height: 100px;
  display: block;
}
img.feat-thumb { height/**/:/**/ auto; }
<?php print "</style>"; ?>
<?php }
}
add_action('wp_head','wp_add_css_ie_tweak');
///////////////////////////////////////////////////////////////////////////
// Update Notifications Notice
///////////////////////////////////////////////////////////////////////////
if ( !function_exists( 'wdp_un_check' ) ) {
  add_action( 'admin_notices', 'wdp_un_check', 5 );
  add_action( 'network_admin_notices', 'wdp_un_check', 5 );
  function wdp_un_check() {
    if ( !class_exists( 'WPMUDEV_Update_Notifications' ) && current_user_can( 'edit_users' ) )
      echo '<div class="error fade"><p>' . __('Please install the latest version of <a href="http://premium.wpmudev.org/project/update-notifications/" title="Download Now &raquo;">our free Update Notifications plugin</a> which helps you stay up-to-date with the most stable, secure versions of WPMU DEV themes and plugins. <a href="http://premium.wpmudev.org/wpmu-dev/update-notifications-plugin-information/">More information &raquo;</a>', 'wpmudev') . '</a></p></div>';
  }
}

////////////////////////////////////////////////////////////////////////////////
// Get Featured Post Image
////////////////////////////////////////////////////////////////////////////////
function wp_custom_post_thumbnail($the_post_id='', $with_wrap='', $wrap_w='', $wrap_h='', $title='', $fetch_size='',$fetch_w='', $fetch_h='',$alt_class='') {
// do global first
global $wpdb, $post, $posts;
$detect_post_id = $the_post_id;
if($with_wrap == 'yes') {
$before_wrap = "<div style='width: $wrap_w; height: $wrap_h; overflow: hidden;'>";
$after_wrap = "</div>";
}
?>

<?php if(get_the_post_thumbnail() != "") : ?>

<?php
$image_id = get_post_thumbnail_id();
if($fetch_size == 'original') {
$image_url = wp_get_attachment_image_src($image_id,'large');
} else {
$image_url = wp_get_attachment_image_src($image_id,array($fetch_w,$fetch_h));
}
$image_url = $image_url[0];
?>
<?php echo $before_wrap; ?>
<img width="<?php echo $fetch_w; ?>" height="auto" class="feat-post-thumbnail <?php echo $alt_class; ?>" title="<?php the_title(); ?>" alt="" src="<?php echo $image_url; ?>">
<?php echo $after_wrap; ?>


<?php else: ?>

<?php
$images = get_children(array(
'post_parent' => $the_post_id,
'post_type' => 'attachment',
'numberposts' => 1,
'post_mime_type' => 'image')); ?>
<?php if ($images) : ?>
<?php foreach($images as $image) :
if($fetch_size == 'original') {
$attachment= wp_get_attachment_image_src($image->ID,'large');
} else {
$attachment= wp_get_attachment_image_src($image->ID, array($fetch_w,$fetch_h));
} ?>
<?php echo $before_wrap; ?>
<img width="<?php echo $fetch_w; ?>" height="auto" class="feat-post-attachment <?php echo $alt_class; ?>" title="<?php the_title(); ?>" alt="" src="<?php echo $attachment[0]; ?>">
<?php echo $after_wrap; ?>
<?php endforeach; ?>


<?php elseif( !$images ): ?>

<?php
$get_post_attachment = $wpdb->get_var("SELECT guid FROM " . $wpdb->prefix . "posts WHERE post_parent = '" . $detect_post_id . "' AND post_type = 'attachment' ORDER BY menu_order ASC LIMIT 1");
// If images exist for this page

if($get_post_attachment) {  ?>
<img width="<?php echo $fetch_w; ?>" height="auto" class="feat-post-wp <?php echo $alt_class; ?>" title="<?php the_title(); ?>" alt="" src="<?php echo $get_post_attachment; ?>">

<?php } else { ?>

<?php
$first_img = '';
ob_start();
ob_end_clean();
$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
$first_img = $matches[1][0]; ?>

<?php if($first_img) { ?>
<?php echo $before_wrap; ?>
<img width="<?php echo $fetch_w; ?>" height="auto" class="feat-post-regex <?php echo $alt_class; ?>" title="<?php the_title(); ?>" alt="" src="<?php echo $first_img; ?>">
<?php echo $after_wrap; ?>
<?php } ?>

<?php } ?>

<?php endif; ?>

<?php endif; ?>

<?php }


///////////////////////////////////////////////////////////////////////////
// Custom footer code
///////////////////////////////////////////////////////////////////////////
function wp_network_footer() {
global $blog_id, $current_site, $current_blog;
if( is_multisite() ) {
$current_site = get_current_site();
$current_network_site = get_current_site_name(get_current_site());

if ( function_exists( 'bp_exists' ) ) {
$current_network_domain = bp_get_root_domain();
} else {
if(function_exists('network_home_url')) {
$current_network_domain = network_home_url();
} else {
$current_network_domain = 'http://' . $current_site->domain . $current_site->path;
}
}

if( BLOG_ID_CURRENT_SITE != $current_blog->blog_id && BP_ROOT_BLOG != $current_blog->blog_id ) { ?>
<?php _e('Hosted by', TEMPLATE_DOMAIN); ?> <a target="_blank" title="<?php echo $current_network_site->site_name; ?>" href="<?php echo $current_network_domain; ?>"><?php echo $current_network_site->site_name; ?></a>
<?php } ?>

<?php
}
}

////////////////////////////////////////////////////////////////////////////////
// new code for wp 3.0+
////////////////////////////////////////////////////////////////////////////////
if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
    // Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );

    if(EDITOR_BG_ENABLE == 'yes') {
    // This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();
    // This theme allows users to set a custom background
	add_custom_background();
    }

	set_post_thumbnail_size( 200, 150, true ); // Normal post thumbnails
	add_image_size( 'single-post-thumbnail', 600, 9999 ); // Permalink thumbnail size
    add_theme_support( 'menus' ); // new nav menus for wp 3.0
    if ( ! isset( $content_width ) ) $content_width = 900;

    }


if ( function_exists( 'register_nav_menus' ) ) {
    // This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
    'top-nav' => __( 'Top Navigation',TEMPLATE_DOMAIN ),
    'main-nav' => __( 'Main Navigation',TEMPLATE_DOMAIN )
	) );
///////////////////////////////////////////////////////////////////////////////
// remove open ul to fit the custom bp navigation.php
///////////////////////////////////////////////////////////////////////////////
function bp_wp_custom_nav_menu($get_custom_location='', $get_default_menu=''){
$options = array('theme_location' => "$get_custom_location", 'menu_id' => '', 'echo' => false, 'container' => false, 'container_id' => '', 'fallback_cb' => "$get_default_menu");
$menu = wp_nav_menu($options);
$menu_list = preg_replace( array( '#^<ul[^>]*>#', '#</ul>$#' ), '', $menu );
return $menu_list;
}
function revert_wp_menu_page() { //revert back to normal if in wp 3.0 and menu not set ?>
<?php wp_list_pages('title_li=&depth=0'); ?>
<?php }

function revert_wp_menu_cat() { //revert back to normal if in wp 3.0 and menu not set ?>
<?php wp_list_categories('orderby=id&show_count=0&use_desc_for_title=0&title_li='); ?>
<?php }
}  // end register_nav_menus check


include( TEMPLATEPATH . '/_inc/functions/conditional-functions.php' );
include( TEMPLATEPATH . '/_inc/functions/widgets-functions.php' );

if($bp_existed == 'true') {
//load buddypress default functions//
include( TEMPLATEPATH . '/bp-functions.php' );

///////////////////////////////////////////////////////////////////////
/// check if is friend
///////////////////////////////////////////////////////////////////////
function bp_displayed_user_is_friend() {
global $bp;
$friend_privacy_enable = get_option('tn_buddyfun_friend_privacy_status');
$friend_privacy_redirect = get_option('tn_buddyfun_friend_privacy_redirect');

if($friend_privacy_enable == "enable") {
if ( bp_is_profile_component() || bp_is_member() ) {
if ( ('is_friend' != BP_Friends_Friendship::check_is_friend( $bp->loggedin_user->id, $bp->displayed_user->id )) && (bp_loggedin_user_id() != bp_displayed_user_id()) ) {
if ( !is_super_admin( bp_loggedin_user_id() ) ) {
if($friend_privacy_redirect == '') {
bp_core_redirect( $bp->root_domain );
} else {
bp_core_redirect( $friend_privacy_redirect );
}
}
}
}
} //enable
}
add_filter('get_header','bp_displayed_user_is_friend',3);

///////////////////////////////////////////////////////////////
// check privacy
////////////////////////////////////////////////////////////////
function check_if_privacy_on() {
global $bp;
$privacy_enable = get_option('tn_buddyfun_privacy_status');
$privacy_redirect = get_option('tn_buddyfun_privacy_redirect');
if($privacy_enable == "enable") {
if ( bp_is_profile_component() || bp_is_activity_component() || bp_is_page( BP_MEMBERS_SLUG ) ) {
if(!is_user_logged_in()) {
if($privacy_redirect == '') {
bp_core_redirect( $bp->root_domain . '/' . BP_REGISTER_SLUG . '/' );
} else {
bp_core_redirect( $privacy_redirect );
}
}
}
} //off
}
add_filter('get_header','check_if_privacy_on',1);

function check_if_create_group_limit() {
global $bp;
$create_limit_enable = get_option('tn_buddyfun_create_group_status');
$create_limit_redirect = get_option('tn_buddyfun_create_group_redirect');
if($create_limit_enable == "yes") {
if( bp_is_group_create() ) {
if ( current_user_can( 'delete_others_posts' ) ) { //only admins and editors
} else {
if( $create_limit_redirect == '' ) {
bp_core_redirect( $bp->root_domain . '/' );
} else {
bp_core_redirect( $create_limit_redirect );
}
}
}

} //off
}
add_filter('get_header','check_if_create_group_limit',2);

///////////////////////////////////////////////////////////////
// add action/////////////////////////////////////////
////////////////////////////////////////////////////////////////

function bp_forum_popular_tag() {
if ( BP_FORUMS_SLUG == bp_current_component() && bp_is_directory() ) : ?>
<div id="forum-directory-tags" class="widget tags">
<h2 class="widgettitle"><?php _e( 'Forum Topic Tags', TEMPLATE_DOMAIN) ?></h2>
<?php if ( function_exists('bp_forums_tag_heat_map') ) : ?>
<div class="textwidget" id="tag-text" style="line-height: 24px;"><?php bp_forums_tag_heat_map( 11, 20, 'px', 55 ); ?></div>
<?php endif; ?>
</div>
<?php endif; ?>
<?php }
add_action('bp_sidebar_dir', 'bp_forum_popular_tag');

///////////////////////////////////////////////////////////////////////
/// add like it facebook stream
///////////////////////////////////////////////////////////////////////
function add_stream_facebooklike_button() { ?>
<?php if(is_user_logged_in()) { ?>
<iframe src="http://www.facebook.com/plugins/like.php?href=<?php bp_activity_thread_permalink() ?>&amp;layout=standard&amp;show-faces=true&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; float: right; width: 300px; height: 30px; overflow:hidden;"></iframe>
<?php } ?>
<?php }
$tn_buddyfun_stream_facebook_like_status = get_option('tn_buddyfun_stream_facebook_like_status');
if($tn_buddyfun_stream_facebook_like_status == 'enable') {
add_action('bp_activity_entry_meta', 'add_stream_facebooklike_button');
}


////////////////////////////////////////////////////////////////////////////////
// buddypress single install adminbar css theme options
////////////////////////////////////////////////////////////////////////////////
if($multi_site_on == "false") {
function buddypress_single_adminbar_css() {
// only work for wpmudev buddypress theme with theme options
?>
<?php // save site options
$active_themename = get_option('template');
$check_if_theme_save = get_option('blog_1_active_theme');
if($check_if_theme_save == '') {
add_option('blog_1_active_theme', $active_themename);
} else {
update_option('blog_1_active_theme', $active_themename);
}
?>

<?php $main_blog_active_theme = get_option('blog_1_active_theme'); // styling global started here ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/global-adminbar.php" type="text/css" media="screen" />
<?php }

add_action('wp_head', 'buddypress_single_adminbar_css'); // init global wp_head
add_action('admin_head', 'buddypress_single_adminbar_css'); // init global admin_head
}

} // end if bp_exist




////////////////////////////////////////////////////////////////////////////////
// one-category
////////////////////////////////////////////////////////////////////////////////

function custom_the_category() {
$parentscategory ="";
foreach((get_the_category()) as $category) {
if ($category->category_parent == 0) {
$parentscategory .= ' <a href="' . get_category_link($category->cat_ID) . '" title="' . $category->name . '">' . $category->name . '</a>, ';
}
}
echo substr($parentscategory,0,-2);
}

////////////////////////////////////////////////////////////////////////////////
// wp 2.7 wp_list_comment
////////////////////////////////////////////////////////////////////////////////

function list_comments($comment, $args, $depth) {
global $bp_existed;
$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
<div class="comment-body" id="div-comment-<?php comment_ID(); ?>">
<?php if($bp_existed == 'true') { // check if bp existed  ?>
<?php echo bp_core_fetch_avatar( array( 'item_id' => $comment->user_id, 'width' => 52, 'height' => 52, 'email' => $comment->comment_author_email ) ); ?>
<?php } else { ?>
<?php echo get_avatar( $comment, 52 ) ?>
<?php } ?>
<div class="comment-author vcard">
<cite class="fn"><?php comment_author_link() ?></cite> <span class="says">-</span> <small><a href="#comment-<?php comment_ID() ?>"><?php comment_date(__('F jS, Y', TEMPLATE_DOMAIN)) ?> <?php _e("at",TEMPLATE_DOMAIN); ?> <?php comment_time() ?></small>
</a>
<div id="comment-text-<?php comment_ID(); ?>" class="comment_text">
<?php if ($comment->comment_approved == '0') : ?>
<em><?php _e('Your comment is awaiting moderation.', TEMPLATE_DOMAIN); ?></em>
<?php endif; ?>
<?php comment_text() ?>
<div class="reply">
<?php comment_reply_link(array_merge( $args, array('add_below'=> 'comment-text', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
</div>
</div>
</div>
</div>
<?php
}

////////////////////////////////////////////////////////////////////////////////
// wp 2.7 wp_list_pingback
////////////////////////////////////////////////////////////////////////////////

function list_pings($comment, $args, $depth) {
$GLOBALS['comment'] = $comment; ?>
<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php }

add_filter('get_comments_number', 'comment_count', 0);

function comment_count( $count ) {
	global $id;
    $comment_chk_variable = get_comments('post_id=' . $id);
	$comments_by_type = &separate_comments($comment_chk_variable);
	return count($comments_by_type['comment']);
}

////////////////////////////////////////////////////////////////////////////////
// WP-PageNavi
////////////////////////////////////////////////////////////////////////////////


function custom_wp_pagenavi($before = '', $after = '', $prelabel = '', $nxtlabel = '', $pages_to_show = 5, $always_show = false) {
	global $request, $posts_per_page, $wpdb, $paged;
	if(empty($prelabel)) {
		$prelabel  = '<strong>&laquo;</strong>';
	}
	if(empty($nxtlabel)) {
		$nxtlabel = '<strong>&raquo;</strong>';
	}
	$half_pages_to_show = round($pages_to_show/2);
	if (!is_single()) {
		if(!is_category()) {
			preg_match('#FROM\s(.*)\sORDER BY#siU', $request, $matches);
		} else {
			preg_match('#FROM\s(.*)\sGROUP BY#siU', $request, $matches);
		}
		$fromwhere = $matches[1];
		$numposts = $wpdb->get_var("SELECT COUNT(DISTINCT ID) FROM $fromwhere");
		$max_page = ceil($numposts /$posts_per_page);
		if(empty($paged)) {
			$paged = 1;
		}
		if($max_page > 1 || $always_show) {
			echo "$before <div class=\"wp-pagenavi\"><span class=\"pages\">Page $paged of $max_page:</span>";
			if ($paged >= ($pages_to_show-1)) {
				echo '<a href="'.get_pagenum_link().'">&laquo; First</a>';
			}
			previous_posts_link($prelabel);
			for($i = $paged - $half_pages_to_show; $i  <= $paged + $half_pages_to_show; $i++) {
				if ($i >= 1 && $i <= $max_page) {
					if($i == $paged) {
						echo "<strong class='current'>$i</strong>";
					} else {
						echo ' <a href="'.get_pagenum_link($i).'">'.$i.'</a> ';
					}
				}
			}
			next_posts_link($nxtlabel, $max_page);
			if (($paged+$half_pages_to_show) < ($max_page)) {
				echo '<a href="'.get_pagenum_link($max_page).'">Last &raquo;</a>';
			}
			echo "</div> $after";
		}
	}
}



////////////////////////////////////////////////////////////////////////////////
// Comment and pingback separate controls
////////////////////////////////////////////////////////////////////////////////

$bm_trackbacks = array();
$bm_comments = array();
function split_comments( $source ) {
if ( $source ) foreach ( $source as $comment ) {
global $bm_trackbacks;
global $bm_comments;
if ( $comment->comment_type == 'trackback' || $comment->comment_type == 'pingback' ) {
$bm_trackbacks[] = $comment;
} else {
$bm_comments[] = $comment;
}
}
}

////////////////////////////////////////////////////////////////////////////////
// excerpt the_content()
////////////////////////////////////////////////////////////////////////////////
function custom_the_excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}

function custom_the_content($limit) {
global $id, $post;
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]&gt;', $content);
  $content = strip_tags($content, '<p>');
  return $content . "<p><a href=\"". get_permalink() . "#more-$id\">" . __('...Click here to read more &raquo;', TEMPLATE_DOMAIN) . "</a></p>";
}

////////////////////////////////////////////////////////////////////////////////
// excerpt features
////////////////////////////////////////////////////////////////////////////////

function the_excerpt_feature($excerpt_length='', $allowedtags='', $filter_type='none', $use_more_link='', $more_link_text = '', $force_more_link=true, $fakeit=1, $fix_tags=true) {

if (preg_match('%^content($|_rss)|^excerpt($|_rss)%', $filter_type)) {
$filter_type = 'the_' . $filter_type;
}
$text = apply_filters($filter_type, get_the_excerpt_feature($excerpt_length, $allowedtags, $use_more_link, $more_link_text, $force_more_link, $fakeit));
$text = ($fix_tags) ? balanceTags($text) : $text;
echo $text;
}

function get_the_excerpt_feature($excerpt_length, $allowedtags, $use_more_link, $more_link_text, $force_more_link, $fakeit) {
global $id, $post;
$output = '';
$output = $post->post_excerpt;
if (!empty($post->post_password)) { // if there's a password
if ($_COOKIE['wp-postpass_'.COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
$output = __('There is no excerpt because this is a protected post.', TEMPLATE_DOMAIN);
return $output;
}
}

// If we haven't got an excerpt, make one.
if ((($output == '') && ($fakeit == 1)) || ($fakeit == 2)) {
$output = $post->post_content;
$output = strip_tags($output, $allowedtags);

$output = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $output );

$blah = explode(' ', $output);
if (count($blah) > $excerpt_length) {
$k = $excerpt_length;
$use_dotdotdot = 1;
} else {
$k = count($blah);
$use_dotdotdot = 0;
}
$excerpt = '';
for ($i=0; $i<$k; $i++) {
$excerpt .= $blah[$i] . ' ';
}
// Display "more" link (use css class 'more-link' to set layout).
if (($use_more_link && $use_dotdotdot) || $force_more_link) {
$excerpt .= "<a href=\"". get_permalink() . "#more-$id\">" . __('<br />...Click here to read more &raquo;', TEMPLATE_DOMAIN) . "</a>";
} else {
$excerpt .= ($use_dotdotdot) ? '...' : '';
}
$output = $excerpt;
} // end if no excerpt
return $output;
}

///////////////////////////////////////////////////////////////////////////////
// fetch post img
//////////////////////////////////////////////////////////////////////////////
function custom_get_post_img ($the_post_id='', $size='', $attributes='', $height='') {
$detect_post_id = $the_post_id;

$images = get_children(array(
'post_parent' => $the_post_id,
'post_type' => 'attachment',
'numberposts' => 1,
'post_mime_type' => 'image'));

if ($images != '') {
foreach($images as $image) {
$attachment=wp_get_attachment_image_src($image->ID, $size); ?>
<div class="custom_post_img no-att-no-cf" style="width: 100%; height: auto; background: url(<?php echo $attachment[0]; ?>) no-repeat center center; overflow:hidden; height: <?php echo $height; ?>px;"></div>
<?php } ?>
<?php } else { ?>

<?php
}
}

///////////////////////////////////////////////////////////////////////////////
// fetch post img
//////////////////////////////////////////////////////////////////////////////

function dez_get_attachment($the_post_id = '') {
global $wpdb;
$detect_post_id = $the_post_id;
$post_attachment_query = "SELECT guid FROM " . $wpdb->prefix . "posts WHERE post_parent = '" . $detect_post_id . "' AND post_type = 'attachment' ORDER BY menu_order ASC LIMIT 1";
$get_post_attachment = $wpdb->get_var($post_attachment_query);
// If images exist for this page

if(!$get_post_attachment) { ?>
<img title="<h1><?php the_title(); ?></h1>" alt="" src="<?php echo get_template_directory_uri(); ?>/_inc/images/no-images.jpg">
<?php } else {  ?>
<img title="<h1><?php the_title(); ?></h1>" alt="" src="<?php echo $get_post_attachment; ?>">
<?php }
}

////////////////////////////////////////////////////////////////////////////////
// shorter string
////////////////////////////////////////////////////////////////////////////////
function short_text($text='', $wordcount='') {
$text_count = strlen( $text );
if ( $text_count <= $wordcount ) {
$text = $text;
} else {
$text = substr( $text, 0, $wordcount );
$text = $text . '...';
}
return $text;
}


///////////////////////////////////////////////////////////////////////////////
// Get total count of multiple categories
//////////////////////////////////////////////////////////////////////////////
function dev_multi_category_count($catslugs = '') {
global $wpdb;
$catslug_array = $catslugs;
$slug_where = "cat_terms.term_id IN (" . $catslug_array . ")";

$sql =	"SELECT	COUNT( DISTINCT cat_posts.ID ) AS post_count " .
			"FROM 	" . $wpdb->term_taxonomy . " AS cat_term_taxonomy INNER JOIN " . $wpdb->terms . " AS cat_terms ON " .
						"cat_term_taxonomy.term_id = cat_terms.term_id " .
					"INNER JOIN " . $wpdb->term_relationships . " AS cat_term_relationships ON " .
						"cat_term_taxonomy.term_taxonomy_id = cat_term_relationships.term_taxonomy_id " .
					"INNER JOIN " . $wpdb->posts . " AS cat_posts ON " .
						"cat_term_relationships.object_id = cat_posts.ID " .
			"WHERE 	cat_posts.post_status = 'publish' AND " .
					"cat_posts.post_type = 'post' AND " .
					"cat_term_taxonomy.taxonomy = 'category' AND " .
					$slug_where;

$post_count = $wpdb->get_var($sql);
return $post_count;

}

///////////////////////////////////////////////////////////////////////////////
// get blogs posts and comments count
//////////////////////////////////////////////////////////////////////////////
function get_the_current_blog_post_count() {
global $wpdb;
$numposts = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "posts WHERE post_status = 'publish' AND post_type= 'post'");
return $numposts;
}

function get_the_current_blog_comment_count() {
global $wpdb;
$numcomms = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "comments WHERE comment_approved = '1'");
return $numcomms;
}
///////////////////////////////////////////////////////////////
// http and https detect function
////////////////////////////////////////////////////////////////
function server_https_detect() {
if($_SERVER['HTTPS']){
$the_server_ssl = 'https://';
} else {
$the_server_ssl = 'http://';
}
return $the_server_ssl;
}

////////////////////////////////////////////////////////////////////////////////
/// Register the widget columns
////////////////////////////////////////////////////////////////////////////////
function fun_widgets_init() {
global $bp_existed;

register_sidebar(
array(
'name' => __('left-column', TEMPLATE_DOMAIN),
'id' => __('left-column', TEMPLATE_DOMAIN),
'description' => __('Left Column Widget', TEMPLATE_DOMAIN),
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h2 class="widgettitle">',
'after_title' => '</h2>'
)
);

register_sidebar(
array(
'name' => __('center-column', TEMPLATE_DOMAIN),
'id' => __('center-column', TEMPLATE_DOMAIN),
'description' => __('Center Column Widget', TEMPLATE_DOMAIN),
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h2 class="widgettitle">',
'after_title' => '</h2>'
)
);

register_sidebar(
array(
'name' => __('right-column', TEMPLATE_DOMAIN),
'id' => __('right-column', TEMPLATE_DOMAIN),
'description' => __('Right Column Widget', TEMPLATE_DOMAIN),
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div></div>',
'before_title' => '<h2 class="widgettitle">',
'after_title' => '</h2><div class="widget-content">'
)
);

register_sidebar(
array(
'name' => __('blog-sidebar', TEMPLATE_DOMAIN),
'id' => __('blog-sidebar', TEMPLATE_DOMAIN),
'description' => __('Blog Sidebar Widget', TEMPLATE_DOMAIN),
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h2 class="widgettitle">',
'after_title' => '</h2>'
)
);


if($bp_existed == 'true') { // only apply for bp
register_sidebar(
array(
'name' => __('buddypress-right', TEMPLATE_DOMAIN),
'id' => __('buddypress-right', TEMPLATE_DOMAIN),
'description' => __('BuddyPress Right Widget', TEMPLATE_DOMAIN),
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h2 class="widgettitle">',
'after_title' => '</h2>'
)
);
}  // end

}
add_action( 'widgets_init', 'fun_widgets_init' );



///////////////////////////////////////////////////////////////
// admin header/////////////////////////////////////////
////////////////////////////////////////////////////////////////


function buddyfun_admin_head() { ?>
<link href="<?php echo get_template_directory_uri(); ?>/_inc/admin/options-css.css" rel="stylesheet" type="text/css" />
<?php if($_GET["page"] == "options-functions.php") { ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/_inc/js/jscolor.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/_inc/js/jquery-ui-personalized-1.6rc2.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/_inc/js/jquery.cookie.min.js"></script>
<script type="text/javascript">
jQuery.noConflict();
var $jd = jQuery;
$jd(document).ready(function(){
$jd('ul#tabm').tabs({event: "click"});
});
</script>
<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Cardo' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Crimson+Text' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=IM+Fell+DW+Pica' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Josefin+Sans+Std+Light' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Molengo' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Neuton' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Nobile' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=OFL+Sorts+Mill+Goudy+TT' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Tangerine' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Old+Standard+TT' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Volkorn' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Just+Another+Hand' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Terminal+Dosis+Light' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:light,regular,bold' rel='stylesheet' type='text/css'>
<?php } ?>
<?php }
add_action('admin_head', 'buddyfun_admin_head');





////////////////////////////////////////////////////////////////////////////////
// CUSTOM IMAGE HEADER  - IF ON WILL BE SHOWN ELSE WILL HIDE
////////////////////////////////////////////////////////////////////////////////

$header_enable = get_option('tn_buddyfun_header_on');
if($header_enable == 'enable') {
$custom_height = get_option('tn_buddyfun_image_height');
if($custom_height==''){$custom_height='150';}else{$custom_height = get_option('tn_buddyfun_image_height'); }

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', ''); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 960); //width is fixed
define('HEADER_IMAGE_HEIGHT', $custom_height);
define('NO_HEADER_TEXT', true );

function buddyfun_admin_header_style() { ?>
<style type="text/css">
#headimg {
	background: url(<?php header_image() ?>) no-repeat;
}
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
#headimg h1, #headimg #desc {
	display: none;
}
</style>
<?php }
add_custom_image_header('', 'buddyfun_admin_header_style');
}
//end check for header image


include( TEMPLATEPATH . '/_inc/functions/options-functions.php' );
////////////////////////////////////////////////////////////////////////////////
// get members-login slug
////////////////////////////////////////////////////////////////////////////////
function get_the_page_template_slug($tpl) {
global $wpdb;
$get_page_template = $wpdb->get_var("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_value = '". $tpl ."' AND meta_key = '_wp_page_template'");
$get_page_template_slug = $wpdb->get_var("SELECT post_name FROM " . $wpdb->prefix . "posts WHERE ID = '" . $get_page_template . "' AND post_type='page'");

return $get_page_template_slug;
}

////////////////////////////////////////////////////////////////////////////////
// get google web font
////////////////////////////////////////////////////////////////////////////////
function font_show(){
$bodytype = get_option('tn_buddyfun_body_font');
$headtype = get_option('tn_buddyfun_headline_font');

if ($bodytype == ""){ ?>
<?php } else if ($bodytype == "Cantarell, arial, serif" ){ ?>
<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Cardo, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Cardo' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Crimson Text, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Crimson+Text' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Droid Sans, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Droid Serif, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "IM Fell DW Pica, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=IM+Fell+DW+Pica' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Josefin Sans Std Light, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Josefin+Sans+Std+Light' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Lobster, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Molengo, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Molengo' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Neuton, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Neuton' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Nobile, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Nobile' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "OFL Sorts Mill Goudy TT, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=OFL+Sorts+Mill+Goudy+TT' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Reenie Beanie, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Tangerine, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Tangerine' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Old Standard TT, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Old+Standard+TT' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Volkorn, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Volkorn' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Yanone Kaffessatz, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'/>
<?php } else if ($bodytype == "Just Another Hand, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Just+Another+Hand' rel='stylesheet' type='text/css'>
<?php } else if ($bodytype == "Terminal Dosis Light, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Terminal+Dosis+Light' rel='stylesheet' type='text/css'>
<?php } else if ($bodytype == "Ubuntu, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:light,regular,bold' rel='stylesheet' type='text/css'>
<?php }

if ($headtype == ""){ ?>
<?php } else if ($headtype == "Cantarell, arial, serif" ){ ?>
<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Cardo, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Cardo' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Crimson Text, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Crimson+Text' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Droid Sans, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Droid Serif, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "IM Fell DW Pica, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=IM+Fell+DW+Pica' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Josefin Sans Std Light, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Josefin+Sans+Std+Light' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Lobster, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Molengo, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Molengo' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Neuton, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Neuton' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Nobile, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Nobile' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "OFL Sorts Mill Goudy TT, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=OFL+Sorts+Mill+Goudy+TT' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Reenie Beanie, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Tangerine, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Tangerine' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Old Standard TT, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Old+Standard+TT' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Volkorn, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Volkorn' rel='stylesheet' type='text/css'/>
<?php } else if ($headtype == "Yanone Kaffeesatz, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'>
<?php } else if ($headtype == "Just Another Hand, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Just+Another+Hand' rel='stylesheet' type='text/css'>
<?php } else if ($headtype == "Terminal Dosis Light, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Terminal+Dosis+Light' rel='stylesheet' type='text/css'>
<?php } else if ($headtype == "Ubuntu, arial, serif"){ ?>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:light,regular,bold' rel='stylesheet' type='text/css'>
<?php }

}

//add_filter('wp_nav_menu_items','add_social_login', 10, 2);
function add_social_login($items, $args) {
        
        
        $items .= '<li style="float:right">Test</li>';
        
        return $items;
}

?>